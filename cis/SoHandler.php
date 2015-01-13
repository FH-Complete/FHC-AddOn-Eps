<?php
/* Copyright (C) 2014 fhcomplete.org
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors: Stefan Puraner <stefan.puraner@technikum-wien.at>
 */

use org\cakephp;
require_once './org/cakephp/HttpSocket.php';
require_once './ShopResponseDetails.php';
require_once './EpsXmlElement.php';
require_once './../../../config/cis.config.inc.php';
require_once './../../../include/webservicelog.class.php';

class SoHandler {
    
    public $httpSocket;
    private $logger;
    
    public function __construct() {
	$this->httpSocket = new cakephp\HttpSocket();
	$this->logger = new webservicelog();
    }

    private function getBanks()
    {
	//URL für Abruf der Bankenliste
	$response = $this->httpSocket->get(SO_URL);
	$dom = new DOMDocument();
	$dom->loadXML($response);
	if($dom->schemaValidate(BANK_LIST_SCHEMA))
	{
	    return $response;
	}
	return false;
    }
    
    public function getBankByBic($bic)
    {
	$banks = $this->getBanksArray();
	$bank = false;
	foreach($banks as $b)
	{
	    if((string) $b->bic === $bic)
	    {
		$bank = $b;
	    }
	}
	return $bank;
    }
    
    public function getBanksArray()
    {
	$xml = $this->getBanks();
	if($xml !== false)
	{
	    $xml = new SimpleXMLElement($xml);
	    $banks = array();
	    foreach ($xml as $bank)
	    {
		array_push($banks, $bank);
	    }
	    return $banks;
	}
	else
	{
	    return false;
	}
    }
    
    public function sendTransferInitiatorDetails($transferInitDetails, $url)
    {
	$data = $transferInitDetails->getSimpleXml();
	$data = $data->xml->asXml();
	$this->logMessage($transferInitDetails->remittanceIdentifier ,"TransferInitiatorDetails", $data);
	$response = $this->httpSocket->post($url, $data, array('header' => array('Content-Type' => 'text/xml; charset=UTF-8')));
	$this->logMessage($transferInitDetails->remittanceIdentifier, "BankResponseDetails", $response);
	if($response->code != 200)
	{
	    return false;
	}
	return $response;
    }
    
    public function handleConfirmation($rawPostStream = 'php://input', $outputStream = 'php://output')
    {
	
	$shopResponseDetails = new ShopResponseDetails();
	try {
	    $xmlPost = file_get_contents($rawPostStream);
	    $xml = new EpsXmlElement($xmlPost);
	    $dom = new DOMDocument();
	    $dom->loadXML($xml->xml->asXml());
	    $statusCode = "NOT SET";
	    $remittanceIdentifier = "";
	    if($dom->schemaValidate(EPS_SCHEMA))
	    {
		$children = $xml->xml->children(EPSP_NAMESPACE);
		if($children[0]->getName() == 'BankConfirmationDetails')
		{
		    $bankConfirmationDetails = $children[0];
		    $a = $bankConfirmationDetails->children(EPS_NAMESPACE);
		    $paymentConfirmationDetails = $a[0];
		    $b = $paymentConfirmationDetails->children(EPI_NAMESPACE);
		    $c = $paymentConfirmationDetails->PaymentInitiatorDetails->children(EPI_NAMESPACE);
		    $epiDetails = $c[0];
		    $remittanceIdentifier = $epiDetails->PaymentInstructionDetails->RemittanceIdentifier;
		    
		    $this->logMessage($remittanceIdentifier, "BankConfirmationDetails", $xml->xml->asXML());
		    
		    $shopResponseDetails->sessionId = $bankConfirmationDetails->SessionId;
		    $shopResponseDetails->statusCode = $paymentConfirmationDetails->StatusCode;
		    if(!empty($paymentConfirmationDetails->PaymentReferenceIdentifier))
		    {
			$shopResponseDetails->paymentReferenceIdentifier = $paymentConfirmationDetails->PaymentReferenceIdentifier;
		    }
		    $statusCode = (String)$a->PaymentConfirmationDetails->StatusCode;
		    $this->paymentConfirmation($statusCode);
		    $xml = $shopResponseDetails->getSimpleXml();
		    $this->logMessage($remittanceIdentifier, "ShopResponseDetails", $xml->xml->asXml());
		    file_put_contents($outputStream, $xml->xml->asXml());
		}
		elseif ($children[0]->getName() == 'VitalityCheckDetails') {
		    $remittanceIdentifier = $children[0]->children(EPI_NAMESPACE);
		    $this->logMessage($remittanceIdentifier, "VitalityCheckDetails", $xml->xml->asXml());
		    file_put_contents($outputStream, $xmlPost);
		}
		else
		{
		    $this->logMessage("Debugging", "Could not handle given value: ".$children[0]->getName(), $xml->xml->asXml());
		}
	    }
	    else
	    {
		$shopResponseDetails->error = "XML konnte nicht validiert werden.";
		$xml = $shopResponseDetails->getSimpleXml();
		$this->logMessage($remittanceIdentifier, "ShopResponseDetails", $xml->xml->asXml());
		file_put_contents($outputStream, $xml->xml->asXml());
	    }
	} catch (Exception $ex) {

	}
	
    }
    
    private function paymentConfirmation($statusCode)
    {
	switch($statusCode)
	{
	    case "OK":
		/***********************************
		 * Abschluss des Zahlungsvorganges
		 * Logging, DB-Eintrag, etc
		 ***********************************/
		break;
	    case "NOK":
		/***********************************
		 * Fehlerbehandlung bei 
		 * Abbruch des Zahlungsvorganges
		 * Logging, DB-Eintrag, etc
		 ***********************************/
		break;
	}
    }
    
    private function logMessage($zahlungsreferenz, $beschreibung, $data)
    {
	$this->logger->webservicetyp_kurzbz = "eps";
	$this->logger->request_id = $zahlungsreferenz;
	$this->logger->beschreibung = $beschreibung;
	$this->logger->request_data = $data;
	$this->logger->save(true);
	$this->logger = new webservicelog();
    }
}
