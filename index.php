<?php 
/**
  * ePub3 Ebook maker
  * Initializes the application
  * @author Amanda Rose  
  *
  */
session_start();

// Include App Config
include_once('config.php');

//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);

// Class Auto Loader
include_once('lib/autoloader.class.php');
spl_autoload_register('Autoloader::AllLoader');

// Include App dependancies
include_once('dep/htmlpurifier-4.6.0/library/HTMLPurifier.auto.php');

// retrieve controller/action/param from url
$url = Util::getUrlData();

// Instantiates the Main class
$main = new Main( $url );
