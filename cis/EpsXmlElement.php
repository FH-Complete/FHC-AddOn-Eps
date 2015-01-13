<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EpsXmlElement
 *
 * @author Stefan Puraner
 */
class EpsXmlElement {
    
   public $xml;
   
   public function __construct($data) {
       if(is_a($data, "SimpleXMLElement"))
	    $this->xml = $data;
       else
	    $this->xml = new SimpleXMLElement($data);
   }
   
   public function addChild($child, $value="", $namespace="")
   {
       $namespaces = $this->xml->getDocNamespaces();
       $ns = $namespace;
       if(array_key_exists($namespace, $namespaces))
       {
	   $child = $namespace .":".$child;
	   $ns = $namespaces[$ns];
       }
       return new self($this->xml->addChild($child, $value, $ns));
   }
   
}
