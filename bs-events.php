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

	######################### DO NOT MODIFY (UNLESS SURE) ########################
	require_once("includes/config.php"); //Load the configurations
	$msg="";
	
	if($_SESSION["logged_in"]!=true){ 
	header("Location: admin.php");
	} else {
	//get access level
            
        bw_do_action("bw_load");
        bw_do_action("bw_admin");
	
	######################### DO NOT MODIFY (UNLESS SURE) END ########################
	$filter = "";  //default filter variable. getting rid of undefined variable exception.
	$bgClass="even"; // default first row highlighting CSS class
	$files_table = ""; //var with php generated html table.
	
	//get service id
	$serviceID = (!empty($_REQUEST["serviceID"]))?strip_tags(str_replace("'","`",$_REQUEST["serviceID"])):1;
	
	
	//"delete selected files" action processing.
	if(!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"]=="yes"){
		
		if(is_array($_POST['filesToDel'])){ 
			if(join(",", $_POST['filesToDel'])!='') {
				//delete record from database
				$sql="SELECT * FROM bs_events WHERE id IN ('".join("','", $_POST['filesToDel'])."')";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete images 1");
				while($row=mysql_fetch_assoc($result)){
					@unlink($_SERVER['DOCUMENT_ROOT'].$baseDir.$row['path']);
				}
				
				$sql="DELETE FROM bs_reservations_items WHERE eventID IN ('".join("','", $_POST['filesToDel'])."')";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 1");
				$sql="DELETE FROM bs_reservations WHERE eventID IN ('".join("','", $_POST['filesToDel'])."')";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 2");
				$sql="DELETE FROM bs_events WHERE id IN ('".join("','", $_POST['filesToDel'])."')";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 3");

                addMessage(MSG_EVDELETED,"warning");
			}
		} 
	}
        if(!empty($_REQUEST["del"]) && $_REQUEST["del"]=="yes"){
		$filesToDel = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
		
				$sql="SELECT * FROM bs_events WHERE id ='$filesToDel'";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete images 1");
				while($row=mysql_fetch_assoc($result)){
					@unlink($_SERVER['DOCUMENT_ROOT'].$baseDir.$row['path']);
				}
				
				$sql="DELETE FROM bs_reservations_items WHERE eventID ='$filesToDel'";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 1");
				$sql="DELETE FROM bs_reservations WHERE eventID ='$filesToDel'";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 2");
				$sql="DELETE FROM bs_events WHERE id='$filesToDel'";
				$result=mysql_query($sql) or die("oopsy, error when tryin to delete events 3");

                addMessage(MSG_EVDELETED,"warning");
			
		
	}
	
	
	//PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
	  $sql="SELECT * FROM bs_events WHERE serviceID={$serviceID} ORDER BY eventDate DESC";
	  $result=mysql_query($sql) or die("error getting events from db");
	  if(mysql_num_rows($result)>0){
		  while($rr=mysql_fetch_assoc($result)){

			 $service=($rr["serviceID"]==0)?"Escolha a Agenda":getService($rr["serviceID"],"name");
			   
			 $editable="<a href=\"bs-events-add.php?id=".$rr["id"]."\"><img src=\"images/pencil_16.png\" alt=\"Edit this event\" border=\"0\"/></a>";  
                          $editable.="&nbsp;&nbsp;<a href='bs-events.php?id=" . $rr["id"] . "&amp;del=yes'><img src='images/delete_16.png' border=\"0\"></a>";
                         
	  		 $bgClass=($bgClass=="even"?"odd":"even");
			 
			 $files_table .="<tr class=\"".$bgClass."\">";
			 $files_table .="";
			 $files_table .="<td height=\"24\"><input name=\"filesToDel[]\" type=\"checkbox\" value=\"".$rr["id"]."\" /></td>";
			 $files_table .= "<td>".$rr["id"]."</td>";
                         $files_table .= "<td>".$rr["title"]."</td>";
			 $files_table .= "<td>".$service."</td>";
			 $files_table .= "<td>".getDateFormat($rr["eventDate"])." ".SYL_AT." ".date((getTimeMode())?"g:i a":"H:i", strtotime($rr["eventDate"]))."</td>";
			 $files_table .= "<td>".getDateFormat($rr["eventDateEnd"])." ".SYL_AT." ".date((getTimeMode())?"g:i a":"H:i", strtotime($rr["eventDateEnd"]))."</td>";
			 $files_table .= "<td><b>".getSpotsLeftForEvent($rr["id"])."</b> ".SYL_LEFT." <b>".$rr["spaces"]."</b> ".SYL_TOTAL."</td>";
			 $files_table .= "<td>".($rr["payment_required"]=="1"?"Yes, ".(number_format($rr["entryFee"],2))." ".  getOption("currency"):"No")."</td>";
			 $files_table .= "<td>".$editable."</td></tr>";

	     } // end of all files from db query (end of while loop)
	
		   //show button to complete file deletion if proper permissions.
		  
			$files_table .="<tr><td height=\"32\" colspan=\"7\"><input name=\"delete_files\" type=\"submit\" value=\"".BTN_DELETESEL."\"  /></td></tr>";
		   
	   
	  } else { 
		//0 files found in database. ( end of IF mysql_num_rows > 0 )
		$files_table .="<tr><td colspan=\"4\">".ZERO_EVENT_DATABASE."</td></tr>";
	  } 
	  
	
?>
<?php include "includes/admin_header.php";?>

<div id="content">

  

  
 
 
 <?php  getMessages(); ?>
 <div class="content_block">
     
  <h2><?php echo BOOKING_LST_EVENTS?></h2>
  <div class="bar">
  <?php
				$sql="SELECT * FROM bs_services";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)>1){
			?>
	<div class="servicesList">
		<form name="ff1" id="ff1" method="post">
                    <label>Selecione o Evento:</label>
			<select name="serviceID">
				
				<?php while($row=mysql_fetch_assoc($res)){?>
					<option value="<?php echo $row['id']?>" <?php echo ($serviceID==$row['id'])?"selected":""?>><?php echo $row['name']?></option>
				<?php } ?>
			</select>
                    <div class="buttonCont">
                        <input type="submit" value="Ver Eventos">
                    </div>
		</form>
	</div>
  <div class="addButtonCont">
      <span>ou</span><a href="bs-events-add.php" class="button">Novo Evento</a>
  </div>
	<div style="clear:both"></div>
	<?php } ?>
  </div>
        <h3><?php echo BOOKING_LST_EVENTS?> para <?echo getService($serviceID,'name')?></h3>
  <form enctype="multipart/form-data" action="bs-events.php" method="post" name="ff2">
  <input type="hidden" value="yes" name="files_delete" />
  <input type="hidden" value="<?php echo $serviceID?>" name="serviceID" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="topRow">
  	<td width="4%" height="30" align="center">&nbsp;</td>
        <td width="5%" align="left"><strong><?php echo EVENT_ID?></strong></td>
    <td width="15%" align="left"><strong><?php echo EVENT_TTL?></strong></td>
	<td width="13%" align="left"><strong><?php echo BOOKING_FRM_SERVICE?></strong></td>
    <td width="13%" align="left"><strong><?php echo EVENT_ST_DATE?></strong></td>
	<td width="15%" align="left"><strong><?php echo END_DATE?></strong></td>
    <td width="13%" align="left"><strong><?php echo BOOKING_LST_SPACES?></strong></td>
    <td width="15%" align="left"><strong><?php echo PAYMENT_QUEST?></strong></td>
    <td width="14%" height="30" align="center">&nbsp;</td>
  </tr>
  <?php echo $files_table; ?>
</table>
</form>
 </div>
 
<?php include "includes/admin_footer.php";?>
<?php } ?>