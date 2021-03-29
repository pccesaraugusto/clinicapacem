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
    //
    //Load the database file
    require_once("includes/dbconnect.php");


    $tt = "";
    $continue = true;
    $success = false;
    $license = (!empty($_REQUEST['license'])) ? strip_tags(str_replace("'", "`", $_REQUEST['license'])) : '';
    $username = (!empty($_REQUEST['username'])) ? strip_tags(str_replace("'", "`", $_REQUEST['username'])) : '';

    $_domain = $_SERVER['HTTP_HOST'];
    $domain = $_domain;

   	if (!empty($_REQUEST["install"]) && $_REQUEST['install'] == "yes"){
           $l = $license;
           
           require_once("includes/core.functions.php");
           require_once("includes/grid.functions.php");
           		if($continue){

                       $query = "ALTER TABLE `bs_reservations` ADD `interval` int( 20 ) NULL ";
                       if (mysql_query($query)) {
                           $tt .= "bs_reservations updated <br />";
                       }

                       $query = "ALTER TABLE `bs_reservations` ADD `qty` INT( 20 ) NOT NULL DEFAULT '1';";
                       if (mysql_query($query)) {
                           $tt .= "Altered table 'bs_reservations' (1a/7)<br/>";
                       }


                       $query = "ALTER TABLE `bs_reservations` ADD `eventID` int( 20 ) NULL ";
                       if (mysql_query($query)) {
                           $tt .= "bs_reservations updated <br />";
                       }

                       $query = "ALTER TABLE `bs_reservations_items` ADD `eventID` int( 20 ) NULL ";
                       if (mysql_query($query)) {
                           $tt .= "bs_reservations_items updated <br />";
                       }


                       $query = "ALTER TABLE `bs_reserved_time` ADD `interval` INT( 20 ) NULL ;";
                       if (mysql_query($query)) {
                           $tt .= "Altered table 'bs_reservations' (1a/7)<br/>";
                       }
                       $query = "ALTER TABLE  `bs_reserved_time` ADD  `qty` INT NOT NULL DEFAULT  '1'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_reserved_time_items' (7/7)<br/>";
                       }
                       $query = "DROP TABLE `bs_settings`;";
                       if (mysql_query($query)) {
                           $tt .= "bs_settings dropped! <br />";
                       }


                       $query = "CREATE TABLE IF NOT EXISTS `bs_settings` (
                          `id` int(11) NOT NULL auto_increment,
                          `email` varchar(255) default NULL,
                          `username` varchar(255) default NULL,
                          `password` varchar(255) default NULL,
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
                          `allow_times` int(20) NOT NULL default '1',
                          `allow_times_min` int(20) NOT NULL default '1',
                          `interval` int(20) NOT NULL default '60',
                          `spot_price` double NOT NULL default '0',
                          `pemail` varchar(255) NOT NULL,
                          `pcurrency` varchar(255) NOT NULL default 'USD',
                          PRIMARY KEY  (`id`)
                        )";
                       if (mysql_query($query)) {
                           $tt .= "Created table 'bs_settings'<br/>";
                       }


                       $query = "INSERT INTO `bs_settings` (`id`, `email`, `username`, `password`, `1_from`, `1_to`, `2_from`, `2_to`, `3_from`, `3_to`, `4_from`, `4_to`, `5_from`, `5_to`, `6_from`, `6_to`, `0_from`, `0_to`, `allow_times`, `pemail`, `pcurrency`, `allow_times_min`, `interval`) VALUES
                (1, 'changeme@something.com', 'admin', '1a1dc91c907325c69271ddf0c944bc72', '480', '1020', '480', '1020', '480', '1020', '480', '1020', '480', '1020', '480', '1020', '480', '1020', 120,'','USD',1, 60)";
                       if (mysql_query($query)) {
                           $tt .= "Created default settings (4a/7)<br/>";
                       }


                       $query = "ALTER TABLE `bs_settings` ADD `startDay` TINYINT( 5 ) NOT NULL DEFAULT '0' COMMENT '0- sunday, 1 - monday',
                ADD  `use_popup` TINYINT (1) NOT NULL DEFAULT  '1' COMMENT  '( use popup for booking 1-yes,0-no )';";
                       if (mysql_query($query)) {
                           $tt .= "Created default settings (4b/7)<br/>";
                       }


                       $query = "ALTER TABLE `bs_events` ADD `max_qty` INT( 20 ) NOT NULL DEFAULT '1',
                        ADD `allow_multiple` INT( 20 ) NOT NULL DEFAULT '2' COMMENT '1- yes 2 - no',
                        ADD `eventDateEnd` DATETIME NOT NULL ,
                        ADD `path` VARCHAR( 255 ) NULL ;";
                       if (mysql_query($query)) {
                           $tt .= "Altered table 'bs_events' (5a/7)<br/>";
                       }


                       $query = "CREATE TABLE `bs_reserved_time_items` (
                        `id` INT( 20 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        `dateCreated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `reservedID` INT( 20 ) NOT NULL ,
                        `dateFrom` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `dateTo` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `tinterval` INT( 20 ) NOT NULL
                        )";
                       if (mysql_query($query)) {
                           $tt .= "Created table 'bs_reserved_time_items' (7/7)<br/>";
                       }

                       $query = "ALTER TABLE  `bs_reserved_time_items` ADD  `qty` INT NOT NULL DEFAULT  '1' AFTER  `dateCreated`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_reserved_time_items' (7/7)<br/>";
                       }
                       ############################################################################################################################
                       $query = "CREATE TABLE IF NOT EXISTS `bs_services` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` varchar(150) NOT NULL,
                      `date_created` date NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ";
                       if (mysql_query($query)) {
                           $tt .= "Created table 'bs_services' (3/7)<br/>";
                       }

                       /*############################## add time mode field to settins, for switch 12h/24h                                */
                       $query = "ALTER TABLE `bs_settings` ADD `time_mode` TINYINT( 5 ) NOT NULL DEFAULT '0' ";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (3/7)<br/>";
                       }
                       /*############################## add time mode field to settins, for switch 12h/24h                                */
                       $query = "INSERT INTO `bs_services` (`id`, `name`, `date_created`) VALUES
            (								1, 'Escolha o Servi&ccedil;o', '0000-00-00') ";
                       if (mysql_query($query)) {
                           $tt .= "Inserting default service to table 'bs_services' (3/7)<br/>";
                       }


                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "ALTER TABLE `bs_events` ADD `serviceID` INT NOT NULL AFTER `eventDate` ";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_events' (3/7)<br/>";
                       }


                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "ALTER TABLE `bs_events` ADD `entryInvoice` TINYINT NOT NULL DEFAULT '0' COMMENT '1-set invoice,0-online payment' AFTER `entryFee`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_events' (3/7)<br/>";
                       }

                       $query = "ALTER TABLE  `bs_events` CHANGE  `entryInvoice`  `payment_method` VARCHAR( 100 ) NOT NULL DEFAULT  '0'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_events' (3/7)<br/>";
                       }

                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "
                        CREATE TABLE IF NOT EXISTS `bs_service_settings` (
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
                              `allow_times` int(20) NOT NULL DEFAULT '1',
                              `allow_times_min` int(20) NOT NULL DEFAULT '1',
                              `interval` int(20) NOT NULL DEFAULT '60',
                              `spot_price` double NOT NULL DEFAULT '0',
                              `spot_invoice` tinyint(4) NOT NULL DEFAULT '0',
                              `startDay` tinyint(5) NOT NULL DEFAULT '0' COMMENT '0- sunday, 1 - monday',
                              PRIMARY KEY (`id`)
                            ) ";
                       if (mysql_query($query)) {
                           $tt .= "Insert table 'bs_service_settings' (3/7)<br/>";
                       }
                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "INSERT INTO `bs_service_settings` (`id`, `serviceId`, `1_from`, `1_to`, `2_from`, `2_to`, `3_from`, `3_to`, `4_from`, `4_to`, `5_from`, `5_to`, `6_from`, `6_to`, `0_from`, `0_to`, `allow_times`, `allow_times_min`, `interval`, `spot_price`, `spot_invoice`, `startDay`) VALUES
            (1, 1, '240', '420', '', '', '', '', '300', '660', '', '', '', '', '', '', 2, 1, 60, 20, 1, 0)";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_service_settings' (3/7)<br/>";
                       }

                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "ALTER TABLE `bs_reservations` ADD `serviceID` INT NOT NULL DEFAULT '1'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_events' (3/7)<br/>";
                       }
                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "ALTER TABLE `bs_reserved_time` ADD `serviceID` INT NOT NULL DEFAULT '1'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_reserved_time' (3/7)<br/>";
                       }
                       /*############################## add serviceID field to bs_events, for relationship between service to event                                */
                       $query = "ALTER TABLE `bs_settings` ADD `weeks` TEXT NOT NULL AFTER `time_mode` ,
                            ADD `months` TEXT NOT NULL AFTER `weeks`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (3/7)<br/>";
                       }
                       $query = "ALTER TABLE `bs_settings` CHANGE `weeks` `weeks` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
                            CHANGE `months` `months` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (3/7)<br/>";
                       }
                       $query = "UPDATE `bs_settings` SET `weeks`='a:2:{s:4:\"full\";a:7:{i:0;s:6:\"Sunday\";i:1;s:6:\"Monday\";i:2;s:7:\"Tuesday\";i:3;s:9:\"Wednesday\";i:4;s:8:\"Thursday\";i:5;s:6:\"Friday\";i:6;s:8:\"Saturday\";}s:5:\"short\";a:7:{i:0;s:3:\"Sun\";i:1;s:3:\"Mon\";i:2;s:3:\"Tue\";i:3;s:3:\"Wed\";i:4;s:3:\"Thu\";i:5;s:3:\"Fri\";i:6;s:3:\"Sat\";}}',`months`='a:2:{s:4:\"full\";a:12:{i:1;s:7:\"January\";i:2;s:8:\"February\";i:3;s:5:\"March\";i:4;s:5:\"April\";i:5;s:3:\"May\";i:6;s:4:\"June\";i:7;s:4:\"July\";i:8;s:6:\"August\";i:9;s:9:\"September\";i:10;s:7:\"October\";i:11;s:8:\"November\";i:12;s:8:\"December\";}s:5:\"short\";a:12:{i:1;s:3:\"Jan\";i:2;s:3:\"Feb\";i:3;s:3:\"Mar\";i:4;s:3:\"Apr\";i:5;s:3:\"May\";i:6;s:3:\"Jun\";i:7;s:3:\"Jul\";i:8;s:3:\"Aug\";i:9;s:3:\"Sep\";i:10;s:3:\"Oct\";i:11;s:3:\"Nov\";i:12;s:3:\"Dec\";}}' WHERE id=1";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (5.0)<br/>";
                       }

                       $query = "ALTER TABLE `bs_settings` ADD `lang` VARCHAR( 150 ) NOT NULL DEFAULT 'english'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (6.0)<br/>";
                       }

                       $query = "ALTER TABLE  `bs_settings` ADD  `currency` VARCHAR( 100 ) NOT NULL DEFAULT  '$' COMMENT  'currency symbol' AFTER  `pcurrency`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (6.0)<br/>";
                       }

                       $query = "ALTER TABLE  `bs_settings` ADD  `tax` DECIMAL( 4, 2 ) NOT NULL DEFAULT  '0' COMMENT  'tax rate' AFTER  `currency` ,
                        ADD  `enable_tax` TINYINT NOT NULL DEFAULT  '0' COMMENT  '1-tax enabled; 0-tax disabled' AFTER  `tax`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_settings' (6.0)<br/>";
                       }


                       $query = "ALTER TABLE `bs_service_settings` ADD `spaces_available` VARCHAR( 100 ) NOT NULL COMMENT 'spaces available per each REGULAR timed slot',
                    ADD `show_spaces_left` TINYINT( 1 ) NOT NULL COMMENT '1-show,0-not show',
                    ADD `show_event_titles` TINYINT( 1 ) NOT NULL COMMENT '1-show,0-not show',
                    ADD `show_event_image` TINYINT( 1 ) NOT NULL COMMENT '1-show,0-not show',
                    ADD `date_mode` VARCHAR( 50 ) NOT NULL DEFAULT  'Y-m-d' AFTER  `time_mode`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_service_settings' <br/>";
                       }

                       $query = "ALTER TABLE `bs_settings`
                          DROP `weeks`,
                          DROP `months`;";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_service_settings' <br/>";
                       }
                       $query = "ALTER TABLE  `bs_service_settings` ADD  `payment_method` VARCHAR( 200 ) NOT NULL DEFAULT 'invoice' AFTER `spot_price`";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_service_settings' <br/>";
                       }


                       $query = "ALTER TABLE  `bs_reserved_time` ADD  `repeate` ENUM(  'year',  'month',  'week',  'day' ) NOT NULL ,
                            ADD  `repeate_interval` INT NOT NULL ,
                            ADD  `recurring` TINYINT NOT NULL DEFAULT  '0'";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_reserved_time' <br/>";
                       }

                       $query = "DROP TABLE bs_settings";
                       if (mysql_query($query)) {
                           $tt .= "Droped table 'bs_settings' <br/>";
                       }

                       $query = "CREATE TABLE IF NOT EXISTS `bs_settings` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `option_name` varchar(200) NOT NULL,
                                  `option_value` text NOT NULL,
                                  PRIMARY KEY (`id`),
                                  UNIQUE KEY `option_name` (`option_name`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;";

                       if (mysql_query($query)) {
                           $tt .= "Added settings into table 'bs_settings' <br/>";
                       }
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
                       if (mysql_query($query)) {
                           $tt .= "Added settings into table 'bs_settings' <br/>";
                       }

                       $query = "ALTER TABLE  `bs_transactions` CHANGE  `transactionID`  `transactionID` VARCHAR( 50 ) NULL DEFAULT NULL";
                       if (mysql_query($query)) {
                           $tt .= "Alter table 'bs_transactions' <br/>";
                       }




                       if($continue){
                            $tt .= "<br/><br/><div class=success>Update complete! You are now using BookingWizz v5.2.1, thank you for purchasing! <br />
                            Please delete this file now and go to your booking index.php file.<br />Default username/password: <b>admin/pass</b></div>";
                            $success = true;
                            $a = auth($l,$username,$domain);
                        }
                   }

       }

           ?>
       <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
           "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
       <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
       <head>
           <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
           <title>BookingWizz v5.2.1</title>
           <link rel="stylesheet" href="css/seo-admin.css" type="text/css"/>
       </head>
       <body>
       <div id="header">
           <div class="scriptname left">BookingWizz v5.2.1 - Upgrade Wizard</div>
          
           <br class="clear"/>
       </div>

       <div id="content">

           <div class="install_container">
               <div class="login">
                   <?php if (!empty($tt)) {
                   echo $tt;
               }
                   if ($success) {
                   } else {
                       ?><br/>
                       <form method="post" action="upgrade.php" enctype="multipart/form-data" name="ff1">


                           <p>Please enter your CodeCanyon license key (located in the license text file in your
                               purchase confirmation email from Envato, or login to your account and go to downloads,
                               you will see red link "License Certificate" next to our product (<a
                                   href="http://screencast.com/t/mI0BCJxSK0w" target="_blank">screenshot</a>)). </p>

                           <label>License Key:</label> <input type="text" id="license" name="license"
                                                              value="<?php echo $license?>" size="100"
                                                              style="width: 250px"/><br class="clear"/>
                           <label>Username: </label><input type="text" id="username" name="username"
                                                           value="<?php echo $username?>" size="30"/><br class="clear"/>

                           <br/>


                           <div class="text_center">

                               <input type="image" name="submit" src="images/new/btn_submit.jpg"
                                      value="<?php echo ADM_BTN_SUBMIT;?>" tabindex="2"/>
                           </div>
                           <input type="hidden" value="yes" name="install"/>
                       </form>
                       <?php } ?>
               </div>
           </div>
       </div>

       <div class="footer">
           <a href="http://www.convergine.com" target="_blank"><img src="images/convergine.png" border="0"></a>
       </div>
       </body>
       </html>