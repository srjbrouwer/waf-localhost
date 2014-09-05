<?php
/*	
	Index
	File:		index.php
	Created:	Brouwer, S.R.J.
	
	Version
		1.00
*/
//-----------------------[set timezone]------------------------
date_default_timezone_set("Europe/Paris");
//------------------------[Allow Includes]------------------------
define('_VALID_INCLUDE_', TRUE);
//------------------------[Activate Sessions]------------------------
session_start();
//------------------------[Functions]------------------------
require("includes/functions.php");
//------------------------[Startup]--------------------------
startup_load_classes();
$site = new Site();

?>
