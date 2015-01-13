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
define("EPSP_NAMESPACE", "http://www.stuzza.at/namespaces/eps/protocol/2013/02");
define("EPS_NAMESPACE", "http://www.stuzza.at/namespaces/eps/payment/2013/02");
define("EPI_NAMESPACE", "http://www.stuzza.at/namespaces/eps/epi/2013/02");

define("EPS_SCHEMA", "./xsd/EPSProtocol-V25.xsd");
define("BANK_LIST_SCHEMA", "./xsd/epsSOBankListProtocol.xsd");
define("SO_URL", "https://routing.eps.or.at/appl/epsSO/data/haendler/v2_5");
define("EPSXMLHEADER", '<?xml version="1.0" encoding="UTF-8"?>'
    . '<epsp:EpsProtocolDetails '
    . 'xsi:schemaLocation="http://www.stuzza.at/namespaces/eps/protocol/2013/02 EPSProtocol-V25.xsd" '
    . 'xmlns:atrul="http://www.stuzza.at/namespaces/eps/austrianrules/2013/02" '
    . 'xmlns:epi="http://www.stuzza.at/namespaces/eps/epi/2013/02" '
    . 'xmlns:eps="http://www.stuzza.at/namespaces/eps/payment/2013/02" '
    . 'xmlns:epsp="http://www.stuzza.at/namespaces/eps/protocol/2013/02" '
    . 'xmlns:dsig="http://www.w3.org/2000/09/xmldsig#" '
    . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" SessionLanguage="DE"/>');



// Path to Confirmation Site
define("CONFIRMATIONURL", "https://localhost/addons/eps/cis/confirmation.php");
// Path to OK Site
define("TRANSACTIONOKURL", "https://localhost/addons/eps/cis/transactionOk.php");
// Path to Error Site
define("TRANSACTIONNOKURL", "https://localhost/addons/eps/cis/fehler.php");

// Haendler ID    
define("EPS_HAENDLERID", "");
// Pin Code
define("EPS_PIN", "");
// BIC
define("BFI_BIC", "");
// IBAN
define("BFI_IBAN", "");
// Name
define("BFI_NAME", "");
