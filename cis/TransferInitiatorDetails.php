<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransferInitiatorDetails
 *
 * @author Stefan Puraner
 */
require_once './EpsXmlElement.php';

class TransferInitiatorDetails {
    /**
     * Händler ID
     * @var type 
     */
    public $haendlerId;
    
    /**
     * PIN für Service
     * @var type 
     */
    public $pin;
    
    /**
     * Erstellungsdatum der Zahlungsauftrages
     * @var type 
     */
    public $date;
    /**
     * Referenz der Zahlungsauftragsnachricht
     * @var type 
     */
    public $referenceIdentifier;
    /**
     * BIC des Begünstigten
     * @var type 
     */
    public $bfiBicIdentifier;
    /**
     * Name und Adress des Begünstigten
     * @var type 
     */
    public $BeneficiaryNameAdressText;
   /**
    * IBAN des Begünstigten
    * @var type 
    */
    public $beneficiaryAccountIdentifier;
    
    /**
     * Zahlungsreferenz
     * @var type 
     */
    public $remittanceIdentifier;
    
    /**
     * Überweisungsbetrag ->> Dezimaltrennzeichen "."
     * @var type 
     */
    public $instructedAmount;
    
    /**
     * Währungscode gem. ISO 4217
     * @var type 
     */
    public $amountCurrencyIdentifier = "EUR";
    
    /**
     * jene URL, an die der SO den Vitality-Check und die Zahlungsbestätigung schickt.
     * @var type 
     */
    public $confirmationUrl;
    /**
     * Rücksprungpunkt in den Webshop des Händlers
     * @var type 
     */
    public $transactionOkUrl;
    /**
     * Rücksprungpunkt bei NICHT erfolgreicher Durchführung der Transaktion
     * @var type 
     */
    public $transactionNokUrl;
    
    public function __construct($haendlerId,
	    $pin, 
	    $bfiBicIdentifier, 
	    $BeneficiaryNameAdressText, 
	    $beneficiaryAccountIdentifier, 
	    $remittanceIdentifier, 
	    $instructedAmount, 
	    $referenceIdentifier, 
	    $confirmationUrl,
	    $transactionOkUrl,
	    $transactionNokUrl)
	{
	$this->haendlerId = $haendlerId;
	$this->pin = $pin;
	$this->bfiBicIdentifier = $bfiBicIdentifier;
	$this->BeneficiaryNameAdressText = $BeneficiaryNameAdressText;
	$this->beneficiaryAccountIdentifier = $beneficiaryAccountIdentifier;
	$this->remittanceIdentifier = $remittanceIdentifier;
	$this->instructedAmount = $instructedAmount;
	$this->date = date("Y-m-d");
	$this->referenceIdentifier = $referenceIdentifier;
	$this->confirmationUrl = $confirmationUrl;
	$this->transactionOkUrl = $transactionOkUrl;
	$this->transactionNokUrl = $transactionNokUrl;
	
    }
    
    public function getSimpleXml()
    {
	$xml = new EpsXmlElement(EPSXMLHEADER);
	
	$transferInitiatorDetails = $xml->addChild("TransferInitiatorDetails", "", "epsp");
	$paymentInitiatorDetails = $transferInitiatorDetails->addChild("PaymentInitiatorDetails", "", "eps");
	$transferMsgDetails = $transferInitiatorDetails->addChild("TransferMsgDetails", "", "epsp");
	$transferMsgDetails->addChild("ConfirmationUrl", $this->confirmationUrl, "epsp");
	$transferMsgDetails->addChild("TransactionOkUrl", $this->transactionOkUrl, "epsp");
	$transferMsgDetails->addChild("TransactionNokUrl", $this->transactionNokUrl, "epsp");

	$authenticationDetails = $transferInitiatorDetails->addChild("AuthenticationDetails", "", "epsp");
	$authenticationDetails->addChild("UserId", $this->haendlerId, "epsp");
	$authenticationDetails->addChild("MD5Fingerprint", $this->generateMD5Fingerprint(),"epsp");
	
	$epiDetails = $paymentInitiatorDetails->addChild("EpiDetails", "", "epi");
	$identificationDetails = $epiDetails->addChild("IdentificationDetails", "", "epi");
	$identificationDetails->addChild("Date", $this->date, "epi");
	$identificationDetails->addChild("ReferenceIdentifier", $this->referenceIdentifier, "epi");
	
	$partyDetails = $epiDetails->addChild("PartyDetails", "", "epi");
	$bfiPartyDetails = $partyDetails->addChild("BfiPartyDetails", "", "epi");
	$bfiPartyDetails->addChild("BfiBicIdentifier", $this->bfiBicIdentifier, "epi");
	$beneficiaryPartyDetails = $partyDetails->addChild("BeneficiaryPartyDetails", "", "epi");
	$beneficiaryPartyDetails->addChild("BeneficiaryNameAddressText", $this->BeneficiaryNameAdressText, "epi");
	$beneficiaryPartyDetails->addChild("BeneficiaryAccountIdentifier", $this->beneficiaryAccountIdentifier, "epi");

	$paymentInstructionDetails = $epiDetails->addChild("PaymentInstructionDetails", "", "epi");
	$paymentInstructionDetails->addChild("RemittanceIdentifier", $this->remittanceIdentifier, "epi");
	$instructedAmount = $paymentInstructionDetails->addChild("InstructedAmount", $this->instructedAmount, "epi");
	$instructedAmount->xml->addAttribute("AmountCurrencyIdentifier", $this->amountCurrencyIdentifier);
	$paymentInstructionDetails->addChild("ChargeCode", "SHA", "epi");
	
	$austrianRulesDetails = $paymentInitiatorDetails->addChild("AustrianRulesDetails","", "atrul");
	$austrianRulesDetails->addChild("DigSig", "SIG", "atrul");
	
	return $xml;
    }
    
    /**
     * gerneriert aus verschiedenen Attributen einen MD5-Hash der 
     * zur Authentifikation verwendet wird
     * @return type
     */
    public function generateMD5Fingerprint()
    {
	$hash = $this->pin
		.$this->date
		.$this->referenceIdentifier
		.$this->beneficiaryAccountIdentifier
		.$this->remittanceIdentifier
		.$this->instructedAmount
		.$this->amountCurrencyIdentifier
		.$this->haendlerId;
	return md5($hash);
    }
}
