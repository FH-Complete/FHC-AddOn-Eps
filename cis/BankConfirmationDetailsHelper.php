<!DOCTYPE html>
<!--
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
-->
<?php
use org\cakephp;
require_once './org/cakephp/HttpSocket.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
	<?php
	//BankConfirmationDetails
	$xml = '<?xml version="1.0" encoding="UTF-8"?>
<epsp:EpsProtocolDetails SessionLanguage="DE" 
	xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2013/02 EPSProtocol-V25.xsd" 
	xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2013/02" 
	xmlns:epi="http://www.stuzza.at/namespaces/eps/epi/2013/02" 
	xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/2013/02" 
	xmlns:epsp="http://www.stuzza.at/namespaces/eps/protocol/2013/02" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<epsp:BankConfirmationDetails>
		<epsp:SessionId>String</epsp:SessionId>
		<eps:PaymentConfirmationDetails>
			<eps:PaymentInitiatorDetails>
				<epi:EpiDetails> 
					<epi:IdentificationDetails>
						<epi:Date>2013-02-28</epi:Date>
						<epi:ReferenceIdentifier>1234567890ABCDEFG</epi:ReferenceIdentifier>
					</epi:IdentificationDetails>
					<epi:PartyDetails>
						<epi:BfiPartyDetails>
							<epi:BfiBicIdentifier>XXXXXXXX</epi:BfiBicIdentifier>
						</epi:BfiPartyDetails>
						<epi:BeneficiaryPartyDetails>
							<epi:BeneficiaryNameAddressText>Max Mustermann</epi:BeneficiaryNameAddressText>
							<epi:BeneficiaryAccountIdentifier>ATXXXXXXXXXXXXXXXXX</epi:BeneficiaryAccountIdentifier>
						</epi:BeneficiaryPartyDetails>
					</epi:PartyDetails>
					<epi:PaymentInstructionDetails>
						<epi:RemittanceIdentifier>AT1234567890XYZ</epi:RemittanceIdentifier>
						<epi:InstructedAmount AmountCurrencyIdentifier="EUR">150.00</epi:InstructedAmount>
						<epi:ChargeCode>SHA</epi:ChargeCode>
						<epi:DateOptionDetails DateSpecificationCode="CRD">
							<epi:OptionDate>2013-02-28</epi:OptionDate>
							<epi:OptionTime>11:00:00-05:00</epi:OptionTime>
						</epi:DateOptionDetails>
					</epi:PaymentInstructionDetails>
				</epi:EpiDetails>
				<atrul:AustrianRulesDetails>
					<atrul:DigSig>SIG</atrul:DigSig>
					<atrul:ExpirationTime>2013-02-28T09:30:47Z</atrul:ExpirationTime>
				</atrul:AustrianRulesDetails>
			</eps:PaymentInitiatorDetails>
			<eps:PayConApprovingUnitDetails>
				<eps:ApprovingUnitBankIdentifier>AAAAAAAAAAA</eps:ApprovingUnitBankIdentifier>
			</eps:PayConApprovingUnitDetails>
			<eps:PayConApprovalTime>2007-03-16T14:30:47-05:00</eps:PayConApprovalTime>
			<eps:PaymentReferenceIdentifier>AT1234567890XYZ</eps:PaymentReferenceIdentifier>
			<eps:StatusCode>OK</eps:StatusCode>
			<dsig:Signature Id="hotVault" xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">
				<dsig:SignedInfo>
					<dsig:CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
					<dsig:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
					<dsig:Reference Id="reference-data-0" URI="">
						<dsig:Transforms>
							<dsig:Transform Algorithm="http://www.w3.org/2002/06/xmldsig-filter2">
								<xf2:XPath Filter="intersect" xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/20031001" xmlns:xf2="http://www.w3.org/2002/06/xmldsig-filter2">here()/ancestor::eps:PaymentConfirmationDetails[1]</xf2:XPath>
							</dsig:Transform>
							<dsig:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
							<dsig:Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/>
						</dsig:Transforms>
						<dsig:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
						<dsig:DigestValue>ClF6Qt/xrwTslCP4o5kJGmK+K6Q=</dsig:DigestValue>
					</dsig:Reference>
				</dsig:SignedInfo>
				<dsig:SignatureValue>EYZGtd+QUhhe5U0zH/3Q0OU76umCm5FrE6mpilHAdHGqpbApT3d22gHYXfdhIecO LxrgXbV5ejjH751EcZ6KxXb2cGuDiszkuYuAHy9MTKTL4GEUQo+97JJ86PQFpfOs kG01oErkgOGjx5efB5oDrSPJ2zk+PhsQw8Eqo8seFx8=</dsig:SignatureValue>
				<dsig:KeyInfo>
					<dsig:X509Data>
						<dsig:X509Certificate>MIIEpjCCA46gAwIBAgIDAMrDMA0GCSqGSIb3DQEBBQUAMIGfMQswCQYDVQQGEwJB VDFIMEYGA1UEChM/QS1UcnVzdCBHZXMuIGYuIFNpY2hlcmhlaXRzc3lzdGVtZSBp bSBlbGVrdHIuIERhdGVudmVya2VociBHbWJIMSIwIAYDVQQLExlhLXNpZ24tY29y cG9yYXRlLWxpZ2h0LTAxMSIwIAYDVQQDExlhLXNpZ24tY29ycG9yYXRlLWxpZ2h0 LTAxMB4XDTA0MTAwNjA5MDY1MVoXDTA3MTAwNjA5MDY1MVoweTELMAkGA1UEBhMC YXQxJDAiBgNVBAoTG1NwYXJrYXNzZW4gRGF0ZW5kaWVuc3QgR21iSDEQMA4GA1UE CxMHU3BhcmRhdDEbMBkGA1UEAxMSU3BhcmRhdC1lcHMtU2lnLTAxMRUwEwYDVQQF Eww2MTk0OTM5ODUxNzcwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAKkbmACF B8spL+bLKg+E5h14i/D4vALmrrBWSc98fVLB87rZ/TVK+cuXIh2ug3P5cOESQwCo buVfLGn0V+WrrH/JLgZn7KRc14j/VM0vlTURkiEkDnQgjUF5tDUCkG/ft5uJqjH9 IK8+DtKJgsSd0OYyB9Ewzi/8t33M9oOAI7tjAgMBAAGjggGSMIIBjjAJBgNVHRME AjAAMBEGA1UdDgQKBAhP62yLyG0dIDBYBgNVHSAEUTBPME0GByooABEBBwEwQjBA BggrBgEFBQcCARY0aHR0cDovL3d3dy5hLXRydXN0LmF0L2RvY3MvY3AvYS1zaWdu LWNvcnBvcmF0ZS1saWdodDATBgNVHSMEDDAKgAhOnn/UL8kfHzB/BggrBgEFBQcB AQRzMHEwRgYIKwYBBQUHMAKGOmh0dHA6Ly93d3cuYS10cnVzdC5hdC9jZXJ0cy9h LXNpZ24tY29ycG9yYXRlLWxpZ2h0LTAxYS5jcnQwJwYIKwYBBQUHMAGGG2h0dHA6 Ly9vY3NwLmEtdHJ1c3QuYXQvb2NzcDAOBgNVHQ8BAf8EBAMCBLAwbgYDVR0fBGcw ZTBjoGGgX4ZdbGRhcDovL2xkYXAuYS10cnVzdC5hdC9vdT1hLXNpZ24tY29ycG9y YXRlLWxpZ2h0LTAxLG89QS1UcnVzdCxjPUFUP2NlcnRpZmljYXRlcmV2b2NhdGlv bmxpc3Q/MA0GCSqGSIb3DQEBBQUAA4IBAQBsr+16GIfUploYYae39pVLdz4holom nbx3k9/KwjkReE2djJeRDk/46BWUfl9V/xPHOJ4GjaAU0WJpO7ITCsQeiVdUJbB5 pgHYFyHjgOyKz9DwCtcpWzdS3luspSJwrYhDd/Hk6+FxstDaKPN/O3Dj/7FcBChR hIdvrCXYmk2ah4ezI+B2hQ+n2pWttXkPvDXCUqEjOqAnTc1FBk34CBlSUphul0W5 G/NUtmIc/HrzjkfSFDZvSfRmCZmQRq4IlWYhSua7RuP93iAn8zrJ71PGzlAHowkk Hhchb9ZpjI93sIX1Qa0hH+4AQ6ImvHaBwioG0so8Gd/Vu2PQI1LBU9No</dsig:X509Certificate>
					</dsig:X509Data>
				</dsig:KeyInfo>
			</dsig:Signature>
		</eps:PaymentConfirmationDetails>
	</epsp:BankConfirmationDetails>
</epsp:EpsProtocolDetails>';
	$httpSocket = new cakephp\HttpSocket();
	$data = new SimpleXMLElement($xml);
	$response = null;
	$response = $httpSocket->post("https://localhost/addons/eps/cis/confirmation.php", $data->asXML(), array('header' => array('Content-Type' => 'text/xml; charset=UTF-8')));
	
	$file = fopen("shopResponseDetails.xml", "w");
	fwrite($file, $response);
	fclose($file);
	var_dump($response);
	
	?>
    </body>
</html>
