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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Fehler</title>
    </head>
    <body>
	<?php
	$fehler = $_GET['epserrorcode'];
	switch ($fehler) {
	    case "ERROR1":
		echo 'Zahlungsbestätigung konnte nicht an Händler übermittelt werden.';
		break;
	    case "ERROR2":
		echo 'XML oder Signatur ungültig';
		break;
	    case "ERROR3":
		echo 'Der Vorgang wurde vom Käufer abgebrochen.';
		break;
	    default:
		echo 'Ein unbekannter Fehler ist aufgetreten.';
		break;
	}
	?>
	<br/>
	<span>Zurück zum <a href="javascript: self.close()">CIS</a></span> 
    </body>
</html>
