<?php
/******************************************************************************
#                         BookingWizz v5
#******************************************************************************
#      Author:     Convergine.com
#      Email:      info@convergine.com
#      Website:    http://www.convergine.com
#
#
#      Version:    5
#      Copyright:  (c) 2010 - 2012  Convergine.com
#	   Icons from PixelMixer - http://pixel-mixer.com/basic_set/ and by Manuel Lopez - http://www.iconfinder.com/search/?q=iconset%3A48_px_web_icons
#      
#*******************************************************************************/
        
	//Load the database file
	@require_once("dbconnect.php");
	
		
	$query = "CREATE TABLE IF NOT EXISTS `bs_reservations` (
			  `id` int(20) NOT NULL AUTO_INCREMENT,
			  `serviceID` int(11) NOT NULL DEFAULT '1',
			  `dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `name` varchar(255) NOT NULL,
			  `email` varchar(255) NOT NULL,
			  `phone` varchar(255) NOT NULL,
			  `comments` mediumtext NOT NULL,
			  `status` tinyint(5) NOT NULL DEFAULT '2' COMMENT '1 - confirmed, 2 - not confirmed',
			  `eventID` int(20) DEFAULT NULL,
			  `interval` int(20) DEFAULT NULL,
			  `qty` int(20) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=22 ;";
	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_reservations' (1/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_reservations' (1/7)<br/><br /></div>"; $BWContinue = false; }
				
	########################################################################################################################################

	$query = "CREATE TABLE IF NOT EXISTS `bs_reservations_items` (
  	`id` int(20) NOT NULL auto_increment,
  	`reservationID` int(20) NOT NULL,
  	`dateCreated` datetime NOT NULL default '0000-00-00 00:00:00',
  	`reserveDateFrom` datetime NOT NULL default '0000-00-00 00:00:00',
  	`reserveDateTo` datetime NOT NULL default '0000-00-00 00:00:00',
	`eventID` int(20) NULL,
	`qty` int(20) NOT NULL default '1',
  	PRIMARY KEY  (`id`)
	)";

	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_reservations_items' (2/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_reservations_items' (2/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################

	$query = "CREATE TABLE IF NOT EXISTS `bs_reserved_time` (
		  `id` int(20) NOT NULL AUTO_INCREMENT,
		  `serviceID` int(11) NOT NULL DEFAULT '1',
		  `reason` varchar(255) NOT NULL,
		  `dateCreated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `reserveDateFrom` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `reserveDateTo` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `interval` int(20) DEFAULT NULL,
		  `qty` INT NOT NULL DEFAULT  '1',
		  `repeate` ENUM(  'year',  'month',  'week',  'day' ) NOT NULL ,
		  `repeate_interval` INT NOT NULL ,
		  `recurring` TINYINT NOT NULL DEFAULT  '0',
		  PRIMARY KEY (`id`)
		)";
	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_reserved_time' (3/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_reserved_time' (3/7)<br/><br /></div>"; $BWContinue = false; }
########################################################################################################################################

	$query = "CREATE TABLE IF NOT EXISTS `bs_settings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `option_name` varchar(200) NOT NULL,
                      `option_value` text NOT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `option_name` (`option_name`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;";
        if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_settings' (4/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_settings' (4/7)<br/><br /></div>"; $BWContinue = false; }
         $query = "INSERT INTO `bs_settings` (`id`, `option_name`, `option_value`) VALUES
                    (1, 'email', 'some@email.ca'),
                    (2, 'username', 'admin'),
                    (3, 'password', '1a1dc91c907325c69271ddf0c944bc72'),
                    (4, 'pemail', 'some@email.com'),
                    (5, 'pcurrency', 'USD'),
                    (6, 'currency', '$'),
                    (7, 'tax', ''),
                    (8, 'enable_tax', '0'),
                    (9, 'time_mode', '0'),
                    (10, 'date_mode', 'Y-m-d'),
                    (11, 'use_popup', '0'),
                    (12, 'lang', 'english'),
                    (13, 'active_plugins', ''),
                    (14, 'payment_methods','a:2:{s:7:\"invoice\";s:15:\"Offline Invoice\";s:6:\"paypal\";s:14:\"PayPal Gateway\";}'),
                    (15, 'currency_position', 'a');";

	if(mysql_query($query)){
					$BWMessage .= "Fill table 'bs_settings' (4/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_settings' (4/7)<br/><br /></div>"; $BWContinue = false; }	
					
	########################################################################################################################################

	
	
	$query = "CREATE TABLE IF NOT EXISTS `bs_events` (
			  `id` int(20) NOT NULL AUTO_INCREMENT,
			  `eventDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `eventDateEnd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `serviceID` int(11) NOT NULL,
			  `eventTime` varchar(255) DEFAULT NULL,
			  `spaces` int(20) DEFAULT '10',
			  `title` varchar(255) DEFAULT NULL,
			  `entryFee` double NOT NULL DEFAULT '0',
                          `payment_method` VARCHAR( 100 ) NOT NULL DEFAULT  '0',
			  `payment_required` tinyint(5) NOT NULL DEFAULT '2',
			  `description` longtext,
			  `max_qty` int(20) NOT NULL DEFAULT '1',
			  `allow_multiple` int(20) NOT NULL DEFAULT '2' COMMENT '1- yes 2 - no',
			  `path` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			)";

		if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_events' (5/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_events' (5/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################
	
	
	$query = "CREATE TABLE IF NOT EXISTS `bs_transactions` (
	  `id` int(11) NOT NULL auto_increment,
	  `reservationID` int(20)  NULL,
	  `eventID` int(20)  NULL,
	  `transactionID` varchar(50) NULL,
	  `dateCreated` datetime NOT NULL default '0000-00-00 00:00:00',
	  `currency` varchar(255) NULL,
	  `amount` double NULL,
	  `payment_status` varchar(255) NULL,
	  `payer_email` varchar(255) NULL,
	  `payer_name` varchar(255) NULL,
	  PRIMARY KEY  (`id`)
	)";

	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_transactions' (6/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_transactions' (6/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################
	
	$query = "CREATE TABLE `bs_reserved_time_items` (
			`id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`dateCreated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`reservedID` INT( 20 ) NOT NULL ,
			`dateFrom` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`dateTo` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			`tinterval` INT( 20 ) NOT NULL,
			`qty` INT NOT NULL DEFAULT  '1' 
			)";

	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_reserved_time_items' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_reserved_time_items' (7/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################

	$query = "CREATE TABLE IF NOT EXISTS `bs_services` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(150) NOT NULL,
			  `date_created` date NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=21";

	if(mysql_query($query)){
					$BWMessage .= "Created table 'bs_services' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_services' (7/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################	
	$query = "INSERT INTO `bs_services` (`id`, `name`, `date_created`) VALUES
				(1, 'Escolha a Agenda', '0000-00-00')";

	if(mysql_query($query)){
					$BWMessage .= "Alter table 'bs_services' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_services' (7/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################	

	$query = "CREATE TABLE IF NOT EXISTS `bs_service_settings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `serviceId` int(11) NOT NULL,
			  `1_from` varchar(255) NOT NULL,
			  `1_to` varchar(255) NOT NULL,
			  `2_from` varchar(255) NOT NULL,
			  `2_to` varchar(255) NOT NULL,
			  `3_from` varchar(255) NOT NULL,
			  `3_to` varchar(255) NOT NULL,
			  `4_from` varchar(255) NOT NULL,
			  `4_to` varchar(255) NOT NULL,
			  `5_from` varchar(255) NOT NULL,
			  `5_to` varchar(255) NOT NULL,
			  `6_from` varchar(255) NOT NULL,
			  `6_to` varchar(255) NOT NULL,
			  `0_from` varchar(255) NOT NULL,
			  `0_to` varchar(255) NOT NULL,
                          `payment_method` varchar(255) NOT NULL,
			  `allow_times` int(20) NOT NULL DEFAULT '1',
			  `allow_times_min` int(20) NOT NULL DEFAULT '1',
			  `interval` int(20) NOT NULL DEFAULT '60',
			  `spot_price` double NOT NULL DEFAULT '0',
			  `spot_invoice` tinyint(4) NOT NULL DEFAULT '0',
			  `startDay` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0- sunday, 1 - monday',		  
			  `spaces_available` varchar(255) NOT NULL DEFAULT '1' COMMENT 'spaces available per each REGULAR timed slot',
			  `show_spaces_left` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-show,0-not show',
			  `show_event_titles` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-show,0-not show',
			  `show_event_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-show,0-not show',
			  `show_multiple_spaces` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1-show,0-not show',
			  `use_popup` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1-show,0-not show',
			  PRIMARY KEY (`id`)
			) ";

	if(mysql_query($query)){
					$BWMessage .= "Create table 'bs_service_settings' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'bs_service_settings' (7/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################
	$query = "INSERT INTO `bs_service_settings` (`id`, `serviceId`, `1_from`, `1_to`, `2_from`, `2_to`, `3_from`, `3_to`, `4_from`, `4_to`, `5_from`, `5_to`, `6_from`, `6_to`, `0_from`, `0_to`,`payment_method`, `allow_times`, `allow_times_min`, `interval`, `spot_price`, `spot_invoice`, `startDay`, `spaces_available`, `show_spaces_left`, `show_event_titles`, `show_event_image`,`show_multiple_spaces`,`use_popup`) VALUES
(1, 1, '240', '420', '', '', '', '', '300', '660', '', '', '', '', '', '','invoice', 99, 2, 30, 10, 0, 1, '1', 0, 1, 0, 0,0)";

	if(mysql_query($query)){
					$BWMessage .= "Alter table 'bs_service_settings' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't add 'Default settings' (7/7)<br/><br /></div>"; $BWContinue = false; }
	########################################################################################################################################
	
	########################################################################################################################################
	$query = "CREATE TABLE IF NOT EXISTS `bs_schedule` (
		  `idItem` int(11) NOT NULL AUTO_INCREMENT,
		  `idService` int(11) NOT NULL,
		  `week_num` int(11) NOT NULL,
		  `startTime` int(11) NOT NULL,
		  `endTime` int(11) NOT NULL,
		  PRIMARY KEY (`idItem`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;";
		

	if(mysql_query($query)){
					$BWMessage .= "Create table 'bs_schedule' (7/7)<br/><br/>";
				} else { $BWMessage .= "<div class=error><b>ERROR!</b> can't create 'Default settings' (7/7)<br/><br /></div>"; $BWContinue = false; }
?>