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


if ($_SESSION["logged_in"] != true) {
    header("Location: admin.php");
} else {

    bw_do_action("bw_load");
    bw_do_action("bw_admin");
    //get access level
    ######################### DO NOT MODIFY (UNLESS SURE) END ########################
    $filter = "";  //default filter variable. getting rid of undefined variable exception.
    $bgClass = "even"; // default first row highlighting CSS class
    $files_table = ""; //var with php generated html table.
    //get service id
    $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : 1;


    //"delete selected files" action processing.
    if (!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"] == "yes") {
        $filesToDel = (!empty($_REQUEST["filesToDel"])) ? strip_tags(str_replace("'", "`", $_REQUEST["filesToDel"])) : '';
        if (is_array($_POST['filesToDel'])) {
            if (join(",", $_POST['filesToDel']) != '') {
                $sql = "DELETE FROM bs_reserved_time_items WHERE reservedID IN ('" . join("','", $_POST['filesToDel']) . "')";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete reserved time items");
                $sql = "DELETE FROM bs_reserved_time WHERE id IN ('" . join("','", $_POST['filesToDel']) . "')";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                //$msg = "Selected manual bookings were deleted.";
                addMessage(MSG_MAN_DEL, "warning");
            }
        }
    }
    
    //"delete selected files" action processing.
    if (!empty($_REQUEST["del"]) && $_REQUEST["del"] == "yes") {
        $filesToDel = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
       
                $sql = "DELETE FROM bs_reserved_time_items WHERE reservedID ={$filesToDel}";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete reserved time items");
                $sql = "DELETE FROM bs_reserved_time WHERE id={$filesToDel}";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                //$msg = "Selected manual bookings were deleted.";
                addMessage(MSG_MAN_DEL, "warning");
         
    }


    //PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
    $sql = "SELECT br.*,bs.name as sname FROM bs_reserved_time br INNER JOIN bs_services bs ON bs.id=br.serviceID AND br.serviceID={$serviceID} ORDER BY dateCreated DESC";
    $result = mysql_query($sql) or die("error getting bookings from db");
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {

            //PERMISSION CHECK - for showing EDIT FILE icon.

            $editable = "<a href=\"bs-reserve.php?id=" . $rr["id"] . "\"><img src=\"images/pencil_16.png\" alt=\"Edit this booking\" border=\"0\"/></a>";
            
            $editable.="&nbsp;&nbsp;<a href='bs-reserve-view.php?id=" . $rr["id"] . "&amp;del=yes'><img src='images/delete_16.png' border=\"0\"></a>";
            
            $bgClass = ($bgClass == "even" ? "odd" : "even");

            $files_table .="<tr class=\"" . $bgClass . "\">";
            $files_table .="";
            $files_table .="<td height=\"24\"><input name=\"filesToDel[]\" type=\"checkbox\" value=\"" . $rr["id"] . "\" /></td>";
            $files_table .= "<td>" . $rr["reason"] . "</td>";
            $files_table .= "<td>" . ($rr["recurring"] ? "yes" : "no") . "</td>";
            $files_table .= "<td>" . $rr["sname"] . "</td>";
            $files_table .= "<td>" . getDateFormat($rr["reserveDateFrom"]) . date(((getTimeMode()) ? " g:i a" : " H:i"), strtotime($rr["reserveDateFrom"])) . "</td>";
            $files_table .= "<td>" . getDateFormat($rr["reserveDateTo"]) . date(((getTimeMode()) ? " g:i a" : " H:i"), strtotime($rr["reserveDateTo"])) . "</td>";
            $files_table .= "<td>" . $editable . "</td></tr>";
        } // end of all files from db query (end of while loop)
        //show button to complete file deletion if proper permissions.

        $files_table .="<tr><td height=\"32\" colspan=\"7\"><input name=\"delete_files\" type=\"submit\" value=\"".BTN_DELETESEL."\"  /></td></tr>";
    } else {
        //0 files found in database. ( end of IF mysql_num_rows > 0 )
        $files_table .="<tr><td colspan=\"7\">".ZERO_MAN_FOUND."</td></tr>";
    }
    ?>
    <?php include "includes/admin_header.php"; ?>

    <div id="content">
        


     

<?php getMessages(); ?>

        <div class="content_block">
            <h2><?php echo MAN_BOOK?></h2>
    <div class="bar">
  <?php
				$sql="SELECT * FROM bs_services";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)>1){
			?>
	<div class="servicesList">
		<form name="ff1" id="ff1" method="post">
                    <label>Selecione o Serviço:</label>
			<select name="serviceID">
				
				<?php while($row=mysql_fetch_assoc($res)){?>
					<option value="<?php echo $row['id']?>" <?php echo ($serviceID==$row['id'])?"selected":""?>><?php echo $row['name']?></option>
				<?php } ?>
			</select>
                    <div class="buttonCont">
                        <input type="submit" value="View Bookings">
                    </div>
		</form>
	</div>
  <div class="addButtonCont">
      <span>ou</span><a href="bs-reserve.php" class="button">Nova Reserva</a>
  </div>
	<div style="clear:both"></div>
	<?php } ?>
  </div>
        <h3><?php echo MAN_BOOK?> para <?echo getService($serviceID,'name')?></h3>
            <form enctype="multipart/form-data" action="bs-reserve-view.php" method="post" name="ff2">
                <input type="hidden" value="yes" name="files_delete" />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="topRow">
                        <td width="5%" height="30" align="center">&nbsp;</td>
                        <td width="20%" align="left"><strong><?php echo REASON?></strong></td>
                        <td width="10%" align="left"><strong><?php echo RECURRING?></strong></td>
                        <td width="20%" align="left"><strong><?php echo BOOKING_FRM_SERVICE?></strong></td>
                        <td width="20%" align="left"><strong><?php echo DATE_FORM_RES?></strong></td>
                        <td width="15%" align="left"><strong><?php echo DATE_RES_TO?></strong></td>
                        <td width="5%" height="30" align="center">&nbsp;</td>
                    </tr>
                <?php echo $files_table; ?>
                </table>
            </form>
        </div>

    <?php include "includes/admin_footer.php"; ?>
<?php
}?>