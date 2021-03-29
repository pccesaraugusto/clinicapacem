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
	
	bw_do_action("bw_load");
	$name = (!empty($_REQUEST["name"]))?strip_tags(str_replace("'","`",$_REQUEST["name"])):'';
	$phone = (!empty($_REQUEST["phone"]))?strip_tags(str_replace("'","`",$_REQUEST["phone"])):'';
	$email = (!empty($_REQUEST["email"]))?strip_tags(str_replace("'","`",$_REQUEST["email"])):'';
	$comments = (!empty($_REQUEST["comments"]))?strip_tags(str_replace("'","`",$_REQUEST["comments"])):'';
	$date = (!empty($_REQUEST["date"]))?strip_tags(str_replace("'","`",$_REQUEST["date"])):'';
	$captcha_sum = (!empty($_POST["captcha_sum"]))?strip_tags(str_replace("'","`",$_POST["captcha_sum"])):'';
	$captcha = (!empty($_POST["captcha"]))?strip_tags(str_replace("'","`",$_POST["captcha"])):'';
	$msg2 = (!empty($_REQUEST["msg2"]))?strip_tags(str_replace("'","`",$_REQUEST["msg2"])):'';
	$serviceID = (!empty($_REQUEST["serviceID"]))?strip_tags(str_replace("'","`",$_REQUEST["serviceID"])):1;
	$time = (!empty($_GET["time"]))?$_GET["time"]:'';
	$qty = (!empty($_REQUEST["qty"]))?strip_tags(str_replace("'","`",$_REQUEST["qty"])):1;
	$lb1 = (!empty($_REQUEST["lb1"]))?strip_tags(str_replace("'","`",$_REQUEST["lb1"])):'';
	//print_r($time);
	
	$availability = getAvailableBookingsTable($date,$serviceID,$time,$qty);
	$int = getInterval($serviceID); //interval in minutes.
	
	
	##########################################################################################################################
	#  GET MAXIMUM AND MINIMUM INTERVALS FOR BOOKING AND JS VALIDATION	
	$maximumBookings = getMaxBooking($serviceID);
	$minimumBookings = getMinBooking($serviceID);
	$bookingTexts = getBookingText($serviceID);
	$availebleSpaces = getServiceSettings($serviceID,'spaces_available');
	$fee = getServiceSettings($serviceID,'spot_price');
		if(getOption('enable_tax')){
			$fee = $fee + ($fee * getOption('tax')/100);
		}
	##########################################################################################################################
		
	if(!empty($lb1) && $lb1=="yes"){
		$msg = "<div class='error_msg'> ".CAPTCHA_ERROR."; </div>";
	}
?>
<?php include "includes/header.php";?>
<?php include "includes/javascript.validation.php";?>

<?php echo $msg; ?>
<div class="internal_booking_form" id="resize">

<form name="ff1" enctype="multipart/form-data" method="post" action="booking.processing.php" onsubmit="return checkForm();">  
<input type="hidden" value="<?php echo $date?>" name="date">
<input type="hidden" name="interval" value="<?php echo $int;?>" />
<input type="hidden" name="serviceID" value="<?php echo $serviceID;?>" />

<h2>Horários disponíveis para <?php  echo _getDate(date(getOption("date_mode"),strtotime($date)))?> </h2>



<p class="desireTime"><?php echo SEL_TIME. " ". $bookingTexts[0]?> <?php echo $bookingTexts[1]?></p>

<?php echo $availability?>

 <?php
			  $num1 = rand(1,9);
			  $num2 = rand(1,9);
			  $sum = $num1 + $num2;
			  ?>
			<div class="tab"><?php echo BOOKING_FORM;?></div>
			<div class="book_form">
				<table width="650" class="booking_form">
					<tr>
						<td align="left">
							<span><?php echo YNAME;?>*:&nbsp;</span>
							<input type="text" name="name" id="name" value="<?php echo $name?>"  onchange="checkFieldBack(this)"/>
							<span><?php echo BOOKING_FRM_PHONE;?>*:&nbsp;</span>
							<input type="text" name="phone" id="phone" value="<?php echo $phone?>"  onchange="checkFieldBack(this)" onkeyup="noAlpha(this)"/>
							<span><?php echo BOOKING_FRM_EMAIL;?>*:&nbsp;</span>
							<input type="text" name="email" id="email"  value="<?php echo $email?>" onchange="checkFieldBack(this);"/>
						
							
						</td>
						
						<td align="left" style="padding-left:10px">
							<span><?php echo BOOKING_FRM_COMMENTS;?>:&nbsp;</span>
							<textarea name="comments" id="comments" cols="15" rows="5" onchange="checkFieldBack(this)"><?php echo $comments?></textarea>
							<div style="float: left; margin: 33px 89px 0pt 0pt;">
							<?php echo $num1." + ".$num2." = "?><input type="text" name="captcha" id="captcha"  value="" style="width:50px;" onchange="checkFieldBack(this);"/>
							</div><input type="image" src="images/reserve_btn.jpg" style="margin-top: 28px;" />
							<input type="hidden" name="captcha_sum" value="<?php echo md5($sum);?>" />
						</td>
					</tr>
				</table>
			</div>

              </form>
<script>
jQuery(function(){
    jQuery("#qty").spinner({min:1});
})
</script>		  
<?php include "includes/footer.php";?>