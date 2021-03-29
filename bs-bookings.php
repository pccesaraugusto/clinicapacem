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
$msg = "";

if ($_SESSION["logged_in"] != true) {
    header("Location: admin.php");
    exit();
} else {
    //get access level
    bw_do_action("bw_load");
    bw_do_action("bw_admin");
    
    //get service id
    $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : 1;

    ######################### DO NOT MODIFY (UNLESS SURE) END ########################
    $filter = " WHERE eventID IS NULL AND serviceID=" . $serviceID; //default filter variable. getting rid of undefined variable exception.
    $bgClass = "even"; // default first row highlighting CSS class
    $files_table = ""; //var with php generated html table.
    //paging settings
    // how many rows to show per page
    $rowsPerPage = 15;
    // by default we show first page
    $pageNum = 1;
    // if $_GET['page'] defined, use it as page number
    if (isset($_REQUEST['page'])) {
        $pageNum = $_REQUEST['page'];
    }
    $offset = ($pageNum - 1) * $rowsPerPage;
    //CREATE PAGING LINKS
    // how many rows we have in database
    $query = "SELECT COUNT(id) AS numrows FROM bs_reservations " . $filter;
    $result = mysql_query($query) or die('Error, query failed');
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $numrows = $row['numrows'];
    // how many pages we have when using paging?
    $maxPage = ceil($numrows / $rowsPerPage);
    // print the link to access each page
    $self = $_SERVER['PHP_SELF'];
    $nav = '';
    for ($page = 1; $page <= $maxPage; $page++) {
        if ($page == $pageNum) {
            $nav .= " $page "; // no need to create a link to current page
        } else {
            $nav .= " <a href=\"$self?page=$page\">$page</a> ";
        }
    }

    // creating previous and next link
    // plus the link to go straight to
    // the first and last page

    if ($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = " <a href=\"$self?page=$page\">Prev</a> ";
        $first = " <a href=\"$self?page=1\">1st Page</a> ";
    } else {
        $prev = '&nbsp;'; // we're on page one, don't print previous link
        $first = '&nbsp;'; // nor the first page link
    }

    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = " <a href=\"$self?page=$page\">Next</a> ";
        $last = " <a href=\"$self?page=$maxPage\">Last</a> ";
    } else {
        $next = '&nbsp;'; // we're on the last page, don't print next link
        $last = '&nbsp;'; // nor the last page link
    }


    //"delete selected " action processing.
    if (!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"] == "yes") {
        $filesToDel = (!empty($_REQUEST["filesToDel"])) ? strip_tags(str_replace("'", "`", $_REQUEST["filesToDel"])) : '';
        if (is_array($_POST['filesToDel'])) {
            if (join(",", $_POST['filesToDel']) != '') {
                //delete booking from database
                $sql = "DELETE FROM bs_reservations_items WHERE reservationID IN ('" . join("','", $_POST['filesToDel']) . "')";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                $sql = "DELETE FROM bs_reservations WHERE id IN ('" . join("','", $_POST['filesToDel']) . "')";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                
                addMessage(ADM_MSG3,"warning");
            }
        }
    }
     if (!empty($_REQUEST["del"]) && $_REQUEST["del"] == "yes") {
        $filesToDel = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
        
                //delete booking from database
                $sql = "DELETE FROM bs_reservations_items WHERE reservationID= '{$filesToDel}'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                $sql = "DELETE FROM bs_reservations WHERE id= '{$filesToDel}'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");
                
                addMessage(ADM_MSG3,"warning");
         
    }


    //PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
    $sql = "SELECT br.*,e.title FROM bs_reservations br
                          LEFT JOIN bs_events e ON e.id=br.eventID
			  WHERE br.serviceID={$serviceID} 
			  ORDER BY br.dateCreated DESC LIMIT " . $offset . ", " . $rowsPerPage;
    $result = mysql_query($sql) or die("error getting bookings from db");
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {

            if(empty($rr['eventID'])){
                $editable = "<a href=\"bs-bookings-edit.php?id=" . $rr["id"] . "\"><img src=\"images/pencil_16.png\" alt=\"Edit this booking\" border=\"0\"/></a>";
            }else{
                $editable = "<a href=\"bs-bookings_event-edit.php?id=" . $rr["id"] . "\"><img src=\"images/pencil_16.png\" alt=\"Edit this booking\" border=\"0\"/></a>";
            }
            $editable.="&nbsp;&nbsp;<a href='bs-bookings.php?id=" . $rr["id"] . "&amp;del=yes'><img src='images/delete_16.png' border=\"0\"></a>";
            $bgClass = ($bgClass == "even" ? "odd" : "even");

            $files_table .= "<tr class=\"" . $bgClass . "\">";
            $files_table .= "";
            $files_table .= "<td height=\"24\"><input name=\"filesToDel[]\" type=\"checkbox\" value=\"" . $rr["id"] . "\" /></td>";
            $files_table .= "<td>" . $rr["title"] . "</td>";
            $files_table .= "<td>" . $rr["name"] . "</td>";

            $files_table .= "<td>" . $rr["email"] . "</td>";
            $files_table .= "<td>" . $rr["phone"] . "</td>";
            $files_table .= "<td>" . getDateFormat($rr["dateCreated"]) . date(((getTimeMode()) ? " g:i a" : " H:i"), strtotime($rr["dateCreated"])) . "</td>";
            $status = '';
            switch ($rr['status']) {
                case "1":
                    $status = BOOKING_FRM_CONFIRMED;
                    break;
                case "2":
                    $status = BOOKING_FRM_NOTCONFIRMED;
                    break;
                case "3":
                    $status = BOOKING_FRM_CANCELLED;
                    break;
                case "4":
                    $status = BOOKING_FRM_PAID;
                    break;
                case "5":
                    $status = BOOKING_FRM_USERCANCELLED;
                    break;
            }
            $files_table .= "<td>";
            $qq = "SELECT * FROM bs_reservations_items WHERE reservationID='" . $rr["id"] . "'";
            $res = mysql_query($qq);
            if (mysql_num_rows($res) > 0) {
                while ($r2 = mysql_fetch_assoc($res)) {
                    $files_table .= getDateFormat($r2["reserveDateFrom"]) . date(((getTimeMode()) ? " g:i a" : " H:i"), strtotime($r2["reserveDateFrom"])) . " to " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($r2["reserveDateTo"])) . "<br/>";
                }
            }
            $files_table .= "</td>";
            $files_table .= "<td align='center'>" . $rr["qty"] . "</td>";
            $files_table .= "<td>" . $status . "</td>";
            $files_table .= "<td>" . $editable . "</td></tr>";
        } // end of all records from db query (end of while loop)
        //show button to complete record deletion if proper permissions.

        $files_table .= "<tr><td height=\"32\" colspan=\"7\"><input name=\"delete_files\" type=\"submit\" value=\"" . ADM_BTN_DELETE . "\"  /></td></tr>";
    } else {
        //0 files found in database. ( end of IF mysql_num_rows > 0 )
        $files_table .= "<tr><td colspan=\"7\">" . ADM_MSG4 . "</td></tr>";
    }
    ?>
    <?php include "includes/admin_header.php"; ?>

    <div id="content">
        
    <?php  getMessages(); ?>
        <div class="content_block">
            <h2><?php echo PAGE_TITLE1 ?></h2>
             
    <div class="bar">
  <?php
				$sql="SELECT * FROM bs_services";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)>1){
			?>
	<div class="servicesList">
		<form name="ff1" id="ff1" method="post">
                    <label>Selecionar serviço:</label>
			<select name="serviceID">
				
				<?php while($row=mysql_fetch_assoc($res)){?>
					<option value="<?php echo $row['id']?>" <?php echo ($serviceID==$row['id'])?"selected":""?>><?php echo $row['name']?></option>
				<?php } ?>
			</select>
                    <div class="buttonCont">
                        <input type="submit" value="Ver Reservas">
                    </div>
		</form>
	</div>
  
	<div style="clear:both"></div>
	<?php } ?>
  </div>
            <h3><?php echo PAGE_TITLE1?> para <?echo getService($serviceID,'name')?></h3>
            <form enctype="multipart/form-data" action="bs-bookings.php" method="post" name="ff2">
                <input type="hidden" value="yes" name="files_delete"/>
                <input type="hidden" value="<?php echo $serviceID ?>" name="serviceID"/>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="topRow">
                        <td width="2%" height="30" align="center">&nbsp;</td>
                        <td width="10%" align="left"><strong><?php echo BOOKING_LST_EVENT ?></strong></td>
                        <td width="10%" align="left"><strong><?php echo BOOKING_LST_NAME ?></strong></td>
                        <td width="17%" align="left"><strong><?php echo BOOKING_LST_EMAIL ?></strong></td>
                        <td width="10%" align="left"><strong><?php echo BOOKING_LST_PHONE ?></strong></td>
                        <td width="15%" align="left"><strong><?php echo BOOKING_LST_ON ?></strong></td>
                        <td width="15%" align="left"><strong><?php echo BOOKING_LST_DATES ?></strong></td>
                        <td width="5%" align="left"><strong><?php echo BOOKING_LST_SPACES ?></strong></td>
                        <td width="8%" align="left"><strong><?php echo BOOKING_LST_STATUS ?></strong></td>
                        <td width="18%" height="30" align="center">&nbsp;</td>
                    </tr>
    <?php echo $files_table; ?>
                    <!-- PAGING NAVIGATION LINKS ROW -->
                    <tr>
                        <td colspan="7" align="right"
                            class="paging"><?php echo $first . $prev . $nav . $next . $last; ?></td>
                    </tr>
                    <!-- PAGING NAVIGATION LINKS ROW END -->
                </table>
            </form>
        </div>

    <?php include "includes/admin_footer.php"; ?>
<?php } ?>