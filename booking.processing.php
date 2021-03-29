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

	require_once("includes/config.php"); //Load the configurations
       
        bw_do_action("bw_load");
	##################################################################################
	#  	1. GET ALL VARIABLES
	$name = (!empty($_POST["name"]))?strip_tags(str_replace("'","`",$_POST["name"])):'';
	$phone = (!empty($_POST["phone"]))?strip_tags(str_replace("'","`",$_POST["phone"])):'';
	$email = (!empty($_POST["email"]))?strip_tags(str_replace("'","`",$_POST["email"])):'';
	$comments = (!empty($_POST["comments"]))?strip_tags(str_replace("'","`",$_POST["comments"])):'';
	$date = (!empty($_POST["date"]))?strip_tags(str_replace("'","`",$_POST["date"])):'';
	$interval = (!empty($_POST["interval"]))?strip_tags(str_replace("'","`",$_POST["interval"])):'';
	$time = (!empty($_POST["time"]))?$_POST["time"]:'';
	$captcha_sum = (!empty($_POST["captcha_sum"]))?strip_tags(str_replace("'","`",$_POST["captcha_sum"])):'';
	$captcha = (!empty($_POST["captcha"]))?strip_tags(str_replace("'","`",$_POST["captcha"])):'';
	$serviceID = (!empty($_REQUEST["serviceID"]))?strip_tags(str_replace("'","`",$_REQUEST["serviceID"])):1;
	$qty = (!empty($_REQUEST["qty"]))?intval($_REQUEST["qty"]):1;
	// captcha check
	
	if(empty($captcha_sum) || empty($captcha) || md5($captcha)!=$captcha_sum){
		$queryString=array(
			"date"=>$date,
			"lb1"=>"yes",
			"serviceID"=>$serviceID,
			"name"=>$name,
			"phone"=>$phone,
			"email"=>$email,
			"comments"=>$comments,
			"time"=>$time,
			"qty"=>$qty
			
		);
		
		$timeURL=http_build_query($time);
		if(getOption('use_popup')){
			header("Location: index.php?".http_build_query($queryString));
		}else{
			header("Location: booking.php?".http_build_query($queryString));
		}
	exit();
	}
	## Check Qty allowed
	$error=checkQtyForTimeBooking($serviceID,$time,$date,$interval,$qty);
	
	$status=getServiceSettings($serviceID,'payment_method')=='invoice'?1:2;
	if(!$error){
		if(!empty($name) && !empty($phone) && !empty($email)){
		
			if(!preg_match("(^[-\w\.]+@([-a-z0-9]+\.)+[a-z]{2,4}$)i", $email)){
				$msg = "<div class='error_msg'>".BEP_10."</div>";
			} else {
				
				##################################################################################
				#  	3. PREPARE BOOKING DATE/TIME  
				# CREATE ORDER
				$q="INSERT INTO bs_reservations (dateCreated, name, email, phone, comments,status, `interval`,`serviceID`,`qty`) 
                                    VALUES (NOW(),'".$name."','".$email."','".$phone."','".$comments."','".$status."','".$interval."','".$serviceID."','".$qty."')";
				$res=mysql_query($q) or die("error! 001:".mysql_error());
				$orderID=mysql_insert_id();
				$serviceName = getService($serviceID,'name');
				if(!empty($orderID)){
					$tempVar ="";
					$bookingData=array();					
					$spots=0;
					foreach($time as $k=>$v){
						$dateFrom = date("Y-m-d H:i:s", strtotime($date." +".$v." minutes"));
						$dateTo = date("Y-m-d H:i:s", strtotime($dateFrom." +".$interval." minutes"));
						$q="INSERT INTO bs_reservations_items (reservationID,dateCreated,reserveDateFrom,reserveDateTo,qty) 
                                                    VALUES ('".$orderID."',NOW(),'".$dateFrom."','".$dateTo."','".$qty."')";
						$res=mysql_query($q) or die("error! 002");
						
						//needed for message
						$tempVar .= "<tr><td>".getDateFormat($date)."</td><td>".date((getTimeMode())?"g:i a":"H:i", strtotime($dateFrom))."</td><td>".date((getTimeMode())?"g:i a":"H:i", strtotime($dateTo))."</td><td>".$qty."</td></tr>";
						$bookingData[] = array(
							'date'=>getDateFormat($date),
							'timeFrom'=>date((getTimeMode())?"g:i a":"H:i", strtotime($dateFrom)),
							'timeTo'=>date((getTimeMode())?"g:i a":"H:i", strtotime($dateTo)),
							'qty'=>$qty
						);
						$spots++;
					}
					
					
					$price_per_spot = getPricePerSpot($serviceID);
					$tax = $taxRate = 0;
					if(getOption('enable_tax')){
						$taxRate = getOption('tax');
						$tax = ($price_per_spot * $spots * getOption('tax')/100);
					}
					
                                        if($price_per_spot == 0){
                                            $infoForBooking = BEP_11;
                                        }else{
                                            $infoForBooking = do_payment($orderID,getServiceSettings($serviceID,"payment_method"));
                                        }
					
					$uid=md5($email."FtTtffT");
					$linkCancelReservation="<a href=\"http://".$_SERVER['SERVER_NAME'].$baseDir."manageReservation.php?email=".urlencode($email)."&uid=".$uid."\">link</a>";
					##################################################################################
					#  	4. SEND NOTICE TO ADMIN AND CUSTOMER
					//send email to admin
					
					$subject = BEP_16." (#".$orderID.")!";

					$adminMail = getAdminMail();
					
					$data = array(
						"{%name%}"=>$name,
						"{%serviceName%}"=>$serviceName,
						"{%email%}"=>$email,
						"{%phone%}"=>$phone,
						"{%comments%}"=>$comments,
						"{%status%}"=>BOOKING_FRM_NOTCONFIRMED,
						"_info"=>$bookingData,
						"{%collect%}"=>($status==1?" (Please collect payment from customer)<br/>":""),
						"{%currencyB%}" => getOption('currency_position')=='b'?getOption('currency'):"",
                                                "{%currencyA%}" => getOption('currency_position')=='a'?getOption('currency'):"",
						"{%tax%}"=>number_format($tax,2),
						"{%subtotal%}"=>number_format(($qty*$price_per_spot*count($bookingData)),2),
						"{%total%}"=>number_format(($qty*$price_per_spot*count($bookingData))+$tax,2),
						"{%taxRate%}"=>$taxRate,
						"_payment"=>($price_per_spot!=0?1:0),
						"_taxable"=>!empty($tax)?1:0,
						"{%linkCancelReservation%}"=>$linkCancelReservation
						);
					sendMail($adminMail,$subject,"timeBookingConfirmationAdmin.php",$data);
					//send email to customer
	
					sendMail($email,$subject,"timeBookingConfirmationCustomer.php",$data);	
					//header("Location: thank-you.php");
				} 
			} 
		} else { 
		//throw error
		$msg = "<div class='error_msg'>".BEP_17."</div>";
		}
	}else{
		$msg = "<div class='error_msg'>".BEP_18."</div>";
		$paypal_form = "";
	}
	
	
?>
<?php include "includes/header.php"?>

<div id="index">
<h1><?php echo BEP_14;?></h1>
<?php echo $msg;?>
<?php echo !$error?getOrderSummery($orderID):"";?>

<?php echo $infoForBooking?>
<br>


<?php include "includes/footer.php"?>