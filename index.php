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

$eventID = (!empty($_GET["eventID"]))?$_GET["eventID"]:'';
$selEvent = (!empty($_GET["selEvent"]))?$_GET["selEvent"]:'';
$name = (!empty($_REQUEST["name"]))?strip_tags(str_replace("'","`",$_REQUEST["name"])):'';
$phone = (!empty($_REQUEST["phone"]))?strip_tags(str_replace("'","`",$_REQUEST["phone"])):'';
$email = (!empty($_REQUEST["email"]))?strip_tags(str_replace("'","`",$_REQUEST["email"])):'';
$comments = (!empty($_REQUEST["comments"]))?strip_tags(str_replace("'","`",$_REQUEST["comments"])):'';
$qty = (!empty($_REQUEST["qty_".$selEvent]))?strip_tags(str_replace("'","`",$_REQUEST["qty_".$selEvent])):'';
$time = (!empty($_GET["time"]))?$_GET["time"]:'';

$serviceID = (!empty($_REQUEST["serviceID"]))?strip_tags(str_replace("'","`",$_REQUEST["serviceID"])):1;
############################## REQUEST CALENDAR DATE IF NAVIGATION USED ################################
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
$title =_getDate(date('F Y',$iCurrentMonth));

$serviceLink="&serviceID={$serviceID}";
################### PREPARE LINKS FOR CALENDAR NAVIGATION ######################
$prev_month_link = "<a href=\"?month=".date('m',$iPrevMonth)."&year=".date('Y',$iPrevMonth).$serviceLink."\" class=\"previous_month\">"._getDate(date('M',$iPrevMonth))."</a>";
$next_month_link = "<a href=\"?month=".date('m',$iNextMonth)."&year=".date('Y',$iNextMonth).$serviceLink."\" class=\"next_month\">"._getDate(date('M',$iNextMonth))."</a>";
################### PREPARE CALENDAR HEADER DEPENDING ON MON OR SUN AS FIRST DAY ######################
if($startDay=="0"){
	$calendarHeader = '<td class="weekend dash_border">'.getShortWeek(0).'</td><td class="dash_border">'.getShortWeek(1).'</td><td class="dash_border">'.getShortWeek(2).'</td><td class="dash_border">'.getShortWeek(3).'</td><td class="dash_border">'.getShortWeek(4).'</td><td class="dash_border">'.getShortWeek(5).'</td><td class="weekend dash_border">'.getShortWeek(6).'</td>';
} else if($startDay=="1"){ 
	$calendarHeader = '<td class="dash_border">'.getShortWeek(1).'</td><td class="dash_border">'.getShortWeek(2).'</td><td class="dash_border">'.getShortWeek(3).'</td><td class="dash_border">'.getShortWeek(4).'</td><td class="dash_border">'.getShortWeek(5).'</td><td class="weekend dash_border">'.getShortWeek(6).'</td><td class="weekend dash_border">'.getShortWeek(0).'</td>';
}
	
	
	
?>

<?php include "includes/header.php"?>

<div id="index">
<h1><?php echo WELCM_SYSTM; ?></h1>
<p><?php echo SAMPLE_TEXT;?></p>
<p><a href="eventList.php"><?php echo EVENTS_LIST?></a></p>
<div class="calendar">
			<?php
				$sql="SELECT * FROM bs_services";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)>1){
			?>
	<div style="float:right">
		<form name="ff1" id="ff1" method="post">
			<select name="serviceID" onchange="document.forms['ff1'].submit()">
				
				<?php while($row=mysql_fetch_assoc($res)){?>
					<option value="<?php echo $row['id']?>" <?php echo ($serviceID==$row['id'])?"selected":""?>><?php echo $row['name']?></option>
				<?php }?>
			</select>
		</form>
	</div>
	<div style="clear:both"></div>
	<?php }?>
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

<table cellpadding="2" cellspacing="5" border="0">
<tbody>
    <?php echo $calendarHeader; ?>
</tbody>
<?php echo $calendar; ?>
</table>
</div>
</div>

<?php if($demo===true){?><p class="copy">Link to <a href="admin.php">ADMIN AREA</a></p><?php } ?>

<script language="javascript" type="text/javascript">		
	function getLightbox(reserveDate,serviceID){
		$.fn.colorbox({href:'booking_frame.php?date='+reserveDate+"&serviceID="+serviceID,innerWidth:'1056px'});
			return false;
	}
	function getLightbox2(eventID,serviceID,date){
		$.fn.colorbox({href:'event-booking_frame.php?eventID='+eventID+"&serviceID="+serviceID+"&date="+date,innerWidth:'1056px'});	
		return false;
	}
	
	$(document).ready(function() {
	<?php if(!empty($lb1) && $lb1=="yes" && !empty($date)){?>
	$.fn.colorbox({href:"booking.php?date=<?php echo $date?>&msg2=captcha&serviceID=<?php echo $serviceID?>&name=<?php echo urlencode($name)?>&phone=<?php echo urlencode($phone)?>&email=<?php echo urlencode($email)?>&comments=<?php echo urlencode($comments)?>&<?php echo http_build_query(array('time'=>$time))?>"});	
	<?php } ?>
	<?php if(!empty($lb2) && $lb2=="yes" && !empty($eventID)){?>
	$.fn.colorbox({href:"event-booking_frame.php?eventID=<?php echo $eventID?>&msg2=captcha&serviceID=<?php echo $serviceID?>&name=<?php echo urlencode($name)?>&phone=<?php echo urlencode($phone)?>&email=<?php echo urlencode($email)?>&qty_<?php echo $selEvent?>=<?php echo urlencode($qty)?>&comments=<?php echo urlencode($comments)?>&selEvent=<?php echo $selEvent ?>&date=<?php echo $date?>"});	
	<?php } ?>
							   });
</script> 

<?php include "includes/footer.php"?>
