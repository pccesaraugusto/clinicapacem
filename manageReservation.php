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

$email=!empty($_REQUEST["email"])?addslashes(urldecode($_REQUEST["email"])):"";

$uid=!empty($_REQUEST["uid"])?$_REQUEST["uid"]:"";

$uidOr=md5($email.'FtTtffT');
//print $email."<br>".$uid."<br>".$uidOr;

	
	########################################################################################################################################################
	//"delete selected attendees" action processing.
	if(!empty($_REQUEST["del"]) && $_REQUEST["del"]=="yes" && !empty($email) && $uid==$uidOr && !empty($_REQUEST["status"]) && !empty($_REQUEST["id"])){
		$todelID = (!empty($_REQUEST["bsid"]))?strip_tags(str_replace("'","`",$_REQUEST["bsid"])):'';
				if(!empty($todelID)){
					//$sql="DELETE FROM bs_reservations_items WHERE reservationID='".$todelID."'";
					//$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 1");
					//$sql="DELETE FROM bs_reservations WHERE id='".$todelID."'";
					if($_REQUEST["status"]!=2){
						##################################################################################
						#  	 SEND NOTICE TO ADMIN AND CUSTOMER
						//send email to admin
						$headers  = "MIME-Version: 1.0\n";
						$headers .= "Content-type: text/html; charset=utf-8\n";
						$headers .= "From: 'Your Booking System' <noreply@".$_SERVER['HTTP_HOST']."> \n";
						$subject = "Cancel booking (#".$_REQUEST["id"].")!";
						$message = "Dear administrator,<br /> <br /> Booking (#".$_REQUEST["id"].")! was cenceled on your website. <br />";
						$message .="<br>Service: ".getBooking($_REQUEST["id"],'sname');
						$message .="<br>Name: ".getBooking($_REQUEST["id"],'name');
						$message .="<br>Phone: ".getBooking($_REQUEST["id"],'phone');
						$message .="<br>Email: ".getBooking($_REQUEST["id"],'email');
						$adminMail = getAdminMail();
						mail($adminMail,$subject,$message,$headers);
						}
						$sql="UPDATE bs_reservations SET status=5 WHERE id='".$todelID."'";
						$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 2");
						$msg2 .= "<span style='color:#00aa00'>".MNG_ATTDEL."</span>";
				}
	}
	########################################################################################################################################################
	
	
	
	
	
	//prepare attendees page.
	$files_table = "";
	###################################################################################################################################################
	if(!empty($email) && $uid==$uidOr){
	//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
	  $sql="SELECT br.*,e.title as eventTitle,e.eventTime,e.payment_required, s.name as serviceName  FROM bs_reservations br
			INNER JOIN bs_services s ON br.serviceID=s.id
			LEFT JOIN bs_events e ON e.id=br.eventID
			WHERE br.email='".$email."' 
			ORDER BY br.dateCreated DESC";
	  $result=mysql_query($sql) or die("error getting attendees from db");
	  if(mysql_num_rows($result)>0){
		  while($rr=mysql_fetch_assoc($result)){
					  	   
			 //$editable="<a href=\"bs-events-add.php?id=".$rr["id"]."\"><img src=\"images/pencil_16.png\" alt=\"Edit this event\" border=\"0\"/></a>";  
			 $paymentRequired = false;
                         $paymentLink='';
                         if($rr['status']==2){
                             if(!empty($rr['eventID'])){
                                 if($rr['payment_required']==1){
                                     if(getSpotsLeftForEvent($rr['eventID'])>=$rr['qty']){
                                        $paymentRequired=true;
                                     }else{
                                         $rr['status']=3;
                                     }
                                 }
                             }else{
                                 if(getServiceSettings($rr['serviceID'], 'spot_price')){
                                     $times = array();
                                     $date = '';
                                     $Sqll = "SELECT * FROM bs_reservations_items WHERE reservationID='{$rr['id']}'";
                                     $Ress = mysql_query($Sqll);
                                     while($row=  mysql_fetch_assoc($Ress)){
                                         $h = date("H",strtotime($row['reserveDateFrom']));
                                         $m=date("i",strtotime($row['reserveDateTo']));
                                         $times[]=($h*60)+$m;
                                         $date = date("Y-m-d",strtotime($row['reserveDateFrom']));
                                     }
                                     
                                     if(checkQtyForTimeBooking($rr['serviceID'],$times,$date,getServiceSettings($rr['serviceID'], 'interval'),$rr['qty'])){
                                      
                                          $rr['status']=3;  
                                     }else{
                                         $paymentRequired=true;
                                     }
                                      
                                 }
                             }
                         }
                         
                         if($paymentRequired){
                            $paymentLink = "<a href='payment_order.php?orderID={$rr['id']}&serviceID={$rr['serviceID']}'>Pay</a>";
                         }
                         
			 $editable = "&nbsp;";
			 $delete = "<a href='manageReservation.php?email=".$email."&amp;uid=".$uid."&amp;id=".$rr['id']."&amp;del=yes&amp;bsid=".$rr["id"]."&amp;status=".$rr['status']."'><img src='images/delete_16.png' width='10' border=\"0\"></a>";
			 $status = '';
			 $serviceName=$rr['serviceName'];
			 $eventName=$rr['eventTitle'];
			 $time=!empty($rr['eventTime'])?date(((getTimeMode())?"g:i a":"H:i"),strtotime($rr['eventTime'])):'';
			 $qq="SELECT * FROM bs_reservations_items WHERE reservationID='".$rr["id"]."'";
				$res=mysql_query($qq);
				if(mysql_num_rows($res)>0){
					while($r2=mysql_fetch_assoc($res)){
						$time .=date(((getTimeMode())?"g:i a":"H:i"),strtotime($r2["reserveDateFrom"]))." to ".date((getTimeMode())?"g:i a":"H:i",strtotime($r2["reserveDateTo"]))."<br/>";
					}
				}
			 switch($rr['status']){
				case "1":$status=BOOKING_FRM_CONFIRMED;
				break;
				case "2":$status=BOOKING_FRM_NOTCONFIRMED;
				break;
				case "3":$status=BOOKING_FRM_CANCELLED;
				break;
				case "4":$status=BOOKING_FRM_PAID;
				break;
				case "5":$status=BOOKING_FRM_USERCANCELLED;
				break;
			 }
			 
			 $bgClass=($bgClass=="even"?"odd":"even");
			 
			 $files_table .="<tr class=\"".$bgClass."\">";
			 $files_table .="";
			 $files_table .="<td height=\"24\">".$delete."</td>";
			 $files_table .= "<td>".$rr["name"]."</td>";
			 $files_table .= "<td>".$rr["qty"]."</td>";
			 $files_table .= "<td>".$serviceName."</td>";
			 $files_table .= "<td>".$eventName."</td>";
			 $files_table .= "<td>".$time."</td>";
			 //$files_table .= "<td>".$rr["email"]."</td>";
			 $files_table .= "<td>".getDateFormat($rr["dateCreated"])."</td>";

			 $files_table .= "<td>".$status."</td>";
			 
                         $files_table .= "<td>".$paymentLink."</td></tr>";
	
		 } // end of all files from db query (end of while loop)
	
		   //show button to complete file deletion if proper permissions.
		  
			//$files_table .="<tr><td height=\"32\" colspan=\"7\"  align='right'><input name=\"delete_files\" type=\"submit\" value=\"Update Statuses\"  /></td></tr>";
		   
	   
	  } else { 
		//0 files found in database. ( end of IF mysql_num_rows > 0 )
		$files_table .="<tr><td colspan=\"7\">".MNG_0FOUND."</td></tr>";
	  } 
	}
	###################################################################################################################################################
?>
<?php include "includes/header.php";?>
<div id="content">
<h1><?php echo MNG_RESERFOR; ?><?php echo $email?></h1>
 
 <?php if(!empty($email) && $uid==$uidOr){?>
	 
	  <strong><?php echo $msg2; ?></strong>
	  <form enctype="multipart/form-data" action="bs-events-add.php" method="post" name="ff2">
	  <input type="hidden" value="yes" name="attendees_edit" />
	  <input value="<?php echo $id;?>" name="id" type="hidden" />
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr class="topRow">
		<td width="3%" height="30" align="center">&nbsp;</td>
		<td width="14%" align="left"><strong><?php echo TBL_NAME; ?></strong></td>
		<td width="5%" align="left"><strong><?php echo TBL_QTY; ?></strong></td>
		<td width="15%" align="left"><strong><?php echo TBL_SERVICE; ?></strong></td>
		<td width="15%" align="left"><strong><?php echo TBL_EVENT; ?></strong></td>
		<td width="18%" align="left"><strong><?php echo TBL_TIME; ?></strong></td>
		<td width="15%" align="left"><strong><?php echo TBL_DATE; ?></strong></td>
		<td width="31%" align="left"><strong><?php echo TBL_MNG; ?></strong></td>
		
                <td width="4%" height="30" align="center">&nbsp;</td>
	  </tr>
	  <?php echo $files_table; ?>
	</table>
	</form>
<?php }else{?>
	<h2><?php echo NO_ACCESS;?></h2>
<?php }?>

<?php include "includes/footer.php";?>