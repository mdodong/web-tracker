<?php
/* 
* Database and other configuration settings
*/

/*** mysql hostname ***/
define('DB_HOST','localhost');

/*** mysql username ***/
define('DB_USER','hybrid-admin');

/*** mysql password ***/
define('DB_PASSWORD','');

/*** mysql database name ***/
define('DB_NAME','tracker-hybrid');

/*** mysql DSN ***/
define('DB_DSN',"mysql:host=".DB_HOST .";dbname=" .DB_NAME);

define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );

date_default_timezone_set('America/Chicago');

require( CLASS_PATH . "/class.Caselog.php" );



///Colors
$colors[]      = 'background-color: #65CC35; color: #FFF;';
$colors[] = 'background-color: #47A2F2; color: #FFF;';
$colors[]     = 'background-color: #FFDA00; color: #FFF;';
$colors[]        = 'background-color: #FF3871; color: #FFF;';
$colors[]    = 'background-color: #337AB7; color: #FFF;';


?>
