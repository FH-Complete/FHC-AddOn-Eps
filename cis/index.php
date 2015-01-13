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

    require_once './../eps.config.inc.php';
    require_once './SoHandler.php';
    require_once './TransferInitiatorDetails.php';
    require_once './../../../include/konto.class.php';
    require_once './../../../include/functions.inc.php';
    require_once './../../../include/benutzer.class.php';
    
    function cmp($a, $b)
    {
	return strcmp($a->bezeichnung, $b->bezeichnung);
    }
    
    function logMessage($zahlungsreferenz, $beschreibung, $data)
    {
	$logger = new webservicelog();
	$logger->webservicetyp_kurzbz = "eps";
	$logger->request_id = $zahlungsreferenz;
	$logger->beschreibung = $beschreibung;
	$logger->request_data = $data;
	$logger->save(true);
    }
    
    if(isset($_GET['buchungsnummer']))
	$buchungsnr=$_GET['buchungsnummer'];
    else
	$buchungsnr='';

    $konto = new konto();
    $konto->load($buchungsnr);
    
?>
<html>
    <head>
        <meta charset="UTF-8">
	<link href="../../../skin/style.css.php" rel="stylesheet" type="text/css">
        <title>Bankenauswahl</title>
    </head>
    <body align="center">
	<div align="center">
	    <img src="../../../skin/images/eps-logo_full.gif" width="300px">
	    <h1>Bankenauswahl</h1>
	    <form method="POST">
	    <?php
	    
	    if ($buchungsnr !== '') 
	    {
		$soHandler = new SoHandler();
		$banks = $soHandler->getBanksArray();
		usort($banks, "cmp");
		if($banks !== false)
		{
		    echo '<select name="bic">';
		    foreach($banks as $bank)
		    {
			echo '<option value="'.$bank->bic.'"><img src="test.jpg"/>'.$bank->bezeichnung.'</option>';
		    }
		    echo '</select><br/><br/>';
		    echo '<input type="submit" value="Auswählen"/>';
		}
	    }
	    else
	    {
		echo "<div align='center' text-align='left'>Daten unvollständig!</div>";
	    }
		?>
	    </form>
	</div>
	    <?php
	    $uid = get_uid();
	    $user = new benutzer();
	    $user->load($uid);
	    if(isset($_POST['bic']) && $_POST['bic'] !== NULL && $buchungsnr!='' && ($konto->person_id === $user->person_id))
	    {
		
		$bic = $_POST['bic'];
		$bank = $soHandler->getBankByBic($bic);
		$url = (string)$bank->epsUrl;
		
		$betrag = $konto->betrag * -1;
		$betrag = str_replace(",", ".", $betrag);
		
		$transferInitDetails = new TransferInitiatorDetails(EPS_HAENDLERID, EPS_PIN, BFI_BIC, BFI_NAME, BFI_IBAN, $konto->zahlungsreferenz, $betrag, $konto->buchungstyp_kurzbz, CONFIRMATIONURL, TRANSACTIONOKURL, TRANSACTIONNOKURL);
		$xml = $transferInitDetails->getSimpleXml();
		$dom = new DOMDocument();
		$dom->loadXML($xml->xml->asXml());
		if($dom->schemaValidate(EPS_SCHEMA))
		{
	    	    $response = $soHandler->sendTransferInitiatorDetails($transferInitDetails, $url);
	    	    $xml = new SimpleXMLElement($response);
		    $soResponse = $xml->children(EPSP_NAMESPACE);
		    
	    	    $errorDetails = $soResponse->BankResponseDetails->ErrorDetails;
		    if(((string) $errorDetails->ErrorCode) !== '000')
		    {
			echo "<div align='center' text-align='left'>Fehlercode: ".$errorDetails->ErrorCode.'<br/>';
			echo "Details: ".$errorDetails->ErrorMsg.'<br/></div>';
		    }
		    else
		    {
			header('Location: '.$soResponse->BankResponseDetails->ClientRedirectUrl);
		    }
		}
	    }
	?>
    </body>
</html>
