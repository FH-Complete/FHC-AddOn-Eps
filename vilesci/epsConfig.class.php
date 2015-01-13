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
 * Authors: Christian Paminger <christian.paminger@technikum-wien.at>,
 *          Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at> and
 *          Rudolf Hangl <rudolf.hangl@technikum-wien.at>.
 */

/**
 * Description of epsConfig
 *
 * @author Stefan Puraner
 */
require_once(dirname(__FILE__).'/basis_db.class.php');

class epsConfig extends basis_db{
    
    public $new;
    public $configId;
    public $haendler_id;
    public $pin;
    public $bic;
    public $iban;
    public $name;
    
    /**
    * Konstruktor
    * @param $buchungsnr Nr der zu ladenden Buchung (default=null)
    */
    public function __construct($configId=null)
    {
	 parent::__construct();

	 if($configId!=null)
	     $this->load($configId);
    }
    
    public function save($new=null)
    {
	
    }
    
    public function load($config_id)
    {
	
    }
    
    public function loadLatest()
    {
	
    }
    
    public function delete($config_id)
    {
	
    }
}
