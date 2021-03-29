<?php
/* * ****************************************************************************
  #                         BookingWizz v5.2.1
  #******************************************************************************
  #      Author:     Convergine (http://www.convergine.com)
  #      Website:    http://www.convergine.com
  #      Support:    http://support.convergine.com
  #      Version:     5.2.1
  #
  #      Copyright:   (c) 2009 - 2012  Convergine.com
  #	   Icons from PixelMixer - http://pixel-mixer.com/basic_set/ and by Manuel Lopez - http://www.iconfinder.com/search/?q=iconset%3A48_px_web_icons
  #
  #****************************************************************************** */

@session_start();
ob_start();
define(MAIN_PATH,dirname(dirname(__FILE__))) ;//main path of Bookig Wizard directory
require_once(MAIN_PATH."/includes/dbconnect.php"); //Load the db connect

define(MAIN_URL,$_SERVER['SERVER_NAME'].$baseDir);

$system_massage = array("error"=>array(),"warning"=>array(),"success"=>array()); // arra of system messages

require_once(MAIN_PATH."/includes/core.functions.php"); //Load the functions
require_once(MAIN_PATH."/includes/plugin.functions.php"); //Load the functions
require_once(MAIN_PATH."/includes/functions.php"); //Load the functions

define(IS_WP_PLUGIN,getOption("is_word_press"));

$languagePath = MAIN_PATH."/languages/".getOption('lang').".lang.php";
if(is_file($languagePath)) {

	include MAIN_PATH."/languages/".getOption('lang').".lang.php";

}else{
	print "ERROR !!! Language file ".getOption('lang').".lang.php not found";
}

$monthList = array();
for($i=1;$i<13;$i++){
	$r = date("F",strtotime("2000-".$i."-01"));
	$monthList[date("F",strtotime("2000-".$i."-01"))]=constant($r);
}
for($i=1;$i<13;$i++){
	$r = date("M",strtotime("2000-".$i."-01"));
	$monthList[date("M",strtotime("2000-".$i."-01"))]=constant($r);
}
for($i=1;$i<8;$i++){
	$r = date("D",strtotime("22-01-2012 +$i days"));
	$monthList[date("D",strtotime("22-01-2012 +$i days"))]=constant($r);
}

define("BW_SELF", basename($_SERVER['SCRIPT_FILENAME'])) ;

// options which connot be deleted
$coreOptionsList = array(
    
    "email",
    "username",
    "password",
    "pemail",
    "pcurrency",
    "currency" ,
    "tax",
    "enable_tax",
    "time_mode",
    "date_mode",
    "use_popup",
    "lang",
    "payment_methods"
);

$menuList = array(
    array(
        "menu_title"=>MENU1,
        "menu_link"=>"bs-schedule.php",
        
    ),
    array(
        "menu_title"=>MENU2,
        "menu_link"=>"bs-bookings.php",
        
    ),
    array(
        "menu_title"=>MENU3,
        "menu_link"=>"bs-events.php",
        "sub_menu"=>
            array(
                array(
                    "menu_title"=>MENU4,
                    "menu_link"=>"bs-events.php"
                ),
                array(
                    "menu_title"=>MENU5,
                    "menu_link"=>"bs-events-add.php"
                )
            )
    ),
    array(
        "menu_title"=>MENU6,
        "menu_link"=>"bs-reserve-view.php",
        "sub_menu"=>
            array(
                array(
                    "menu_title"=>MENU8,
                    "menu_link"=>"bs-reserve-view.php"
                ),
                array(
                    "menu_title"=>MENU7,
                    "menu_link"=>"bs-reserve.php"
                )
            )
    ),
    array(
        "menu_title"=>MENU9,
        "menu_link"=>"bs-services.php",
        "sub_menu"=>
            array(
                array(
                    "menu_title"=>MENU10,
                    "menu_link"=>"bs-services.php"
                ),
                array(
                    "menu_title"=>MENU11,
                    "menu_link"=>"bs-services-add.php"
                )
            )
    )

    
 
);




   bw_add_action("bw_load", "load_script");

?>