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
include "includes/dbconnect.php";
include "includes/config.php";
require_once('includes/paypal.class.php');  // include the class file
$paypal = new paypal_class;             // initiate an instance of the class
//$paypal->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

include "includes/header.php";
?>
<div id="index">
<h1><?php echo PP_THANK_H1?></h1>
<?php
switch ($_GET['action']) { 
    case 'success':      // Order was successful...
      echo "<p>".PP_THANKYOU."</p>";
	break;
      
    case 'cancel':       // Order was canceled...
      echo "<p>".PP_CANCEL."</p>";
	break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   	  if ($paypal->validate_ipn()) {		

		//-----> send notification 
		//creating message for sending

		$subject = PP_SUBJ_RECEIVED;
		$adminMail = getAdminMail();
		
		$data=array(
			"{%payerEmail%}"=>$paypal->pp_data['payer_email'],
			"{%date%}"=>date('m/d/Y'),
			"{%time%}"=>date('g:i A')
		);
		if(strstr($paypal->pp_data['custom'],"-")){
			
			//EVENT payment
			$tt = explode("-",$paypal->pp_data['custom']);
			$eventInf = getEventInfo($tt[1]);
			$data['{%text%}']="<br /> Payment was made for event \"".$eventInf[0]."\" (".$eventInf[2].")";
			
			$q="UPDATE bs_reservations SET status='4' WHERE id='".$tt[0]."' AND eventID='".$tt[1]."'";
			mysql_query($q);
			
			$q="INSERT INTO bs_transactions (reservationID,eventID,dateCreated,transactionID,amount,payment_status,currency,payer_email,payer_name)	VALUES ('".$tt[0]."','".$tt[1]."',NOW(),'".$paypal->pp_data['txn_id']."','".$paypal->pp_data['mc_gross_1']."','".$paypal->pp_data['payment_status']."','".$paypal->pp_data['mc_currency']."','".$paypal->pp_data['payer_email']."','".$paypal->pp_data['first_name']." ".$paypal->pp_data['last_name']."')";
			mysql_query($q);
			
		} else {
			//Booking payment
			$tt = $paypal->pp_data['custom'];
			$data['{%text%}']="<br /> Payment was made for regular booking";

			//-----> send notification end 		
			
			$q="UPDATE bs_reservations SET status='4' WHERE id='".$tt."'";
			mysql_query($q);
			
			$q="INSERT INTO bs_transactions (reservationID,eventID,dateCreated,transactionID,amount,payment_status,currency,payer_email,payer_name)	VALUES ('".$tt."','0',NOW(),'".$paypal->pp_data['txn_id']."','".$paypal->pp_data['mc_gross_1']."','".$paypal->pp_data['payment_status']."','".$paypal->pp_data['mc_currency']."','".$paypal->pp_data['payer_email']."','".$paypal->pp_data['first_name']." ".$paypal->pp_data['last_name']."')";
			mysql_query($q);
		}
		sendMail($adminMail,$subject,"paymentReceive.php",$data);
		
	  
	  
      }
      break;
 }     

include "includes/footer.php";?>