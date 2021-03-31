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
require_once("includes/config.php"); //Load the functions



$date = (!empty($_REQUEST["date"]))?strip_tags(str_replace("'","`",$_REQUEST["date"])):'';
$lb1 = (!empty($_REQUEST["lb1"]))?strip_tags(str_replace("'","`",$_REQUEST["lb1"])):'';
$lb2 = (!empty($_REQUEST["lb2"]))?strip_tags(str_replace("'","`",$_REQUEST["lb2"])):'';

$serviceID = (!empty($_REQUEST["serviceID"]))?strip_tags(str_replace("'","`",$_REQUEST["serviceID"])):1;
$eventID =( !empty($_REQUEST["eventID"]))?strip_tags(str_replace("'","`",$_REQUEST["eventID"])):'';
$date =(!empty($_REQUEST["date"]))?strip_tags(str_replace("'","`",$_REQUEST["date"])):'';

$startDay = getFirstDay($serviceID);
$iMonth = (!empty($_REQUEST["month"]))?strip_tags(str_replace("'","`",$_REQUEST["month"])):date('n');
$iYear = (!empty($_REQUEST["year"]))?strip_tags(str_replace("'","`",$_REQUEST["year"])):date('Y');	
$calendar = "";
$calendar = setupCalendar($iMonth,$iYear,$serviceID);
list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
$iCurrentMonth = date('n');
$iCurrentYear = date('Y');
$iCurrentDay = '';
if(($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)){
	$iCurrentDay = date('d');
	$thismonth=true;
}
$iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
$iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
$iCurrentDay = $iCurrentDay;
$iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);
$title =_getDate(date('F',$iCurrentMonth))." ".date('Y',$iCurrentMonth);
############################## REQUEST CALENDAR DATE IF NAVIGATION USED ################################
$serviceLink="&serviceID={$serviceID}";
################### PREPARE LINKS FOR CALENDAR NAVIGATION ######################
$prev_month_link = "<a href=\"?month=".date('m',$iPrevMonth)."&year=".date('Y',$iPrevMonth).$serviceLink."\" class=\"previous_month\">"._getDate(date('M',$iPrevMonth))."</a>";
$next_month_link = "<a href=\"?month=".date('m',$iNextMonth)."&year=".date('Y',$iNextMonth).$serviceLink."\" class=\"next_month\">"._getDate(date('M',$iNextMonth))."</a>";
################### PREPARE CALENDAR HEADER DEPENDING ON MON OR SUN AS FIRST DAY ######################
	
?>
<?php include "includes/header.php";?>

<div id="index">
<h1><?php echo WELCM_SYSTM?></h1>
<p>
<?php echo SAMPLE_TEXT?> <strong><?php echo VIEW?> <a href="index.php"><?php echo CALENDAR?></a></strong>
</p>

<div class="calendar">

<!-- CALENDAR NAVIGATION -->
    <table cellspacing="5" class="dash_border">
    <tr>
        <td height="50" width="100">
            <?php echo $prev_month_link?>
        </td>
        <th align="center" width="400">
            <?php echo $title?>
        </th>
        <td align="right"  width="100">
            <?php echo $next_month_link?>
        </td>
    </tr>
    </table>
<!-- CALENDAR NAVIGATION END -->
<br />

</div>
<div id="eventListConteiner">
<?php
		$dateStart = $iYear."-".$iMonth."-01";
		$dateEnd = $iYear."-".$iMonth."-".date("t",mktime(0, 0, 0, $iMonth, 1, $iYear));
		$query="SELECT * FROM bs_events WHERE eventDate >='".$dateStart." 00:00:00' AND eventDate <='".$dateEnd." 23:59:00' ORDER BY eventDate DESC ";//print $query;
				$result=mysql_query($query);
				if(mysql_num_rows($result)>0){
				while($row=mysql_fetch_assoc($result)){
				$spaces_left = getSpotsLeftForEvent($row["id"]);
                                $datetocheck = date("Y-m-d",strtotime($row['eventDate']));
                                 if($row['eventDate']>date("Y-m-d") && $spaces_left>0){
                                $click = getOption('use_popup') ? "getLightbox2('" . $row['id'] . "'," . $row['serviceID'] . ",'".$datetocheck."');" :
                                    "location.href='event-?eventID=" . urlencode($row['id']) . "&serviceID=" .  $row['serviceID'] . "&date=".$datetocheck."'";
                              }else{
                                  $click ="javascript:;";
}?>
	<div class="eventConteiner">
		<div class='eventTitle1'><a href="javascript:;" onclick="<?php echo $click?>"><?php echo $row['title']?></a> / <?php echo getService($row['serviceID'],'name')?><div><?php echo getDateFormat($row["eventDate"])?></div></div>
			<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom:10px">
				<tr>
					<td valign="top">
						<div class='eventDescription'>
						
						<?php if(!empty($row['path'])){?><img src="http://<?php echo $_SERVER['SERVER_NAME']?><?php echo $baseDir?><?php echo $row['path']?>" height="100"><?php }?>
						<?php echo $row['description']?>
                                                </div>
					</td>
					<td valign="top">
						<table class="tableR" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td colspan="2" class="tdB">
									<div class="eventTime">
										<img src="./images/new/clock.png">
										<?php echo EVENT_START?><span> <?php echo date((getTimeMode())?"g:i a":"H:i", strtotime($row["eventTime"]))?></span>
									</div>
								</td>
							</tr>
							<tr>
								<td width="50%"><div class="eventSpots"><span class="spot"><?php echo $spaces_left?></span><span class="spot1">spots<br>left</span></div></td>
								<td align="center"><div class="eventFee">
								<?php if($row["payment_required"]=="1"){
									$price = $row["entryFee"];
									if(getOption('enable_tax')){
										$price = $price + ($price * getOption('tax')/100);
									}
									echo getOption('currency')."&nbsp;".number_format($price,2);
										
									} else { 
									echo "<span style='color:#0FA1D2'><?php echo FREE?></span>";
									}?>
								</div></td>
							</tr>
							<tr>
							<?php if($row['eventDate']>date("Y-m-d") && $spaces_left>0){?>
								<td colspan="2"><input type="image" onClick="<?php echo $click?>" src="./images/reserve_btn.jpg"></td>
							<?php }?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
	</div>
<?php }}else{?>
	<h2><?php echo NO_EVENT_MONTH?></h2>
<?php }?>
</div>
<p class="copy"><?php echo SAMPLE_TEXT?><?php echo LINKTO?> <a href="admin.php"><?php echo ADMINAREA?></a></p>
</div>

<script language="javascript" type="text/javascript">		
	
	function getLightbox(reserveDate,eventId,serviceID){
		
		$.fn.colorbox({href:"event-booking.php?date="+reserveDate+"&serviceID="+serviceID+"&eventID="+eventId,innerWidth:'1056px'});
	}
        function getLightbox2(eventID,serviceID,date){
		$.fn.colorbox({href:'event-booking_frame.php?eventID='+eventID+"&serviceID="+serviceID+"&date="+date,innerWidth:'1056px'});	
		return false;
	}
	
	$(document).ready(function() {
	<?php if(!empty($eventID) && !empty($date) ){?>
	$.fn.colorbox({href:"event-booking.php?date=<?php echo $date?>&serviceID=<?php echo $serviceID?>&eventID=<?php echo $eventID?>",innerWidth:'1056px'});
	<?php } ?>
	<?php if(!empty($lb2) && $lb2=="yes" && !empty($date)){?>
	$.fn.colorbox({href:'event-booking.php?date=<?php echo $date?>&msg2=captcha&serviceID=<?php echo $serviceID?>'});
	<?php } ?>
							   });
</script> 

<?php include "includes/footer.php";?>