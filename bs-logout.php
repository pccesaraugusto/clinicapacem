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
	session_start();
    require_once("includes/dbconnect.php"); //Load the settings
	$msg="";
	
	if($_SESSION["logged_in"]!=true){ 
	header("Location: index.php");
	} else {
	$_SESSION['idUser']="";
	$_SESSION['username']= "";
	$_SESSION['accesslevel']= "";
	$_SESSION['logged_in'] = false;
	session_destroy();
	header("Location: index.php");
	}
?>