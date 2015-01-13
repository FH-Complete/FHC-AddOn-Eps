<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
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
	//Vitality Check
	$xml = '<?xml version="1.0" encoding="UTF-8"?> <epsp:EpsProtocolDetails SessionLanguage="DE" xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2013/02 EPSProtocol-V25.xsd" xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2013/02" xmlns:epi="http://www.stuzza.at/namespaces/eps/epi/2013/02" xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/2013/02" xmlns:epsp="http://www.stuzza.at/namespaces/eps/protocol/2013/02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> <epsp:VitalityCheckDetails> <epi:RemittanceIdentifier>AT1234567890XYZ</epi:RemittanceIdentifier> </epsp:VitalityCheckDetails> </epsp:EpsProtocolDetails>';
	$httpSocket = new cakephp\HttpSocket();
	$data = new SimpleXMLElement($xml);
	$response = $httpSocket->post("https://localhost/addons/eps/cis/confirmation.php", $data->asXML(), array('header' => array('Content-Type' => 'text/xml; charset=UTF-8')));
	/*$datei = fopen("vitalityCheck.xml", "w");
	fwrite($datei, $response);
	fclose($datei);*/
	var_dump($response->body);
	var_dump($response->raw);
	var_dump($response->context);
	?>
    </body>
</html>
