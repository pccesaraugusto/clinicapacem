<?php
/******************************************************************************
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
#*******************************************************************************/

######################### DO NOT MODIFY (UNLESS SURE) ########################
require_once("includes/config.php"); //Load the configurations

$msg = "";
$msg2 = "";
if ($_SESSION["logged_in"] != true) {
    header("Location: admin.php");
    exit();
} else {
    bw_do_action("bw_load");
    bw_do_action("bw_admin");
    
    ######################### DO NOT MODIFY (UNLESS SURE) END ########################

    //show page only if admin access level

    //request all neccessary variables for user update action.
    $id = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
    $title = (!empty($_REQUEST["title"])) ? strip_tags(str_replace("'", "`", $_REQUEST["title"])) : '';
    $spaces = (!empty($_REQUEST["spaces"])) ? strip_tags(str_replace("'", "`", $_REQUEST["spaces"])) : '';
    $max_qty = (!empty($_REQUEST["max_qty"])) ? strip_tags(str_replace("'", "`", $_REQUEST["max_qty"])) : '1';
    $allow_multiple = (!empty($_REQUEST["allow_multiple"])) ? strip_tags(str_replace("'", "`", $_REQUEST["allow_multiple"])) : '2';
    $description = (!empty($_REQUEST["description"])) ? strip_tags(str_replace("'", "`", $_REQUEST["description"])) : '';
    $entryFee = (!empty($_REQUEST["entryFee"])) ? strip_tags(str_replace("'", "`", $_REQUEST["entryFee"])) : '';
    $payment_required = (!empty($_REQUEST["payment_required"])) ? strip_tags(str_replace("'", "`", $_REQUEST["payment_required"])) : '';
    $eventDate = (!empty($_REQUEST["eventDate"])) ? strip_tags(str_replace("'", "`", $_REQUEST["eventDate"])) : '';
    $eventDateEnd = (!empty($_REQUEST["eventDateEnd"])) ? strip_tags(str_replace("'", "`", $_REQUEST["eventDateEnd"])) : '';
    $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : '';
    $payment_method = (!empty($_REQUEST["payment_method"])) ? strip_tags(str_replace("'", "`", $_REQUEST["payment_method"])) : '';

    $name = (!empty($_REQUEST["name"])) ? strip_tags(str_replace("'", "`", $_REQUEST["name"])) : '';
    $email = (!empty($_REQUEST["email"])) ? strip_tags(str_replace("'", "`", $_REQUEST["email"])) : '';
    $qty_booking = (!empty($_REQUEST["qty_booking"])) ? strip_tags(str_replace("'", "`", $_REQUEST["qty_booking"])) : '';
    $comments = (!empty($_REQUEST["comments"])) ? strip_tags(str_replace("'", "`", $_REQUEST["comments"])) : '';
    $phone = (!empty($_REQUEST["phone"])) ? strip_tags(str_replace("'", "`", $_REQUEST["phone"])) : '';
    $status_add = (!empty($_REQUEST["status_add"])) ? strip_tags(str_replace("'", "`", $_REQUEST["status_add"])) : '';

    $status_upd = (!empty($_POST["status_upd"])) ? $_POST["status_upd"] : '';
    $old_status_upd = (!empty($_POST["old_status_upd"])) ? $_POST["old_status_upd"] : '';

    $hh = (!empty($_POST["hh"])) ? $_POST["hh"] : '';
    $mm = (!empty($_POST["mm"])) ? $_POST["mm"] : '';
    $hh1 = (!empty($_POST["hh1"])) ? $_POST["hh1"] : '';
    $mm1 = (!empty($_POST["mm1"])) ? $_POST["mm1"] : '';

    $time_selector = "";
    $eventTime = $hh . ":" . $mm;
    $eventTimeEnd = $hh1 . ":" . $mm1;


    ########################################################################################################################################################
    if (isset($_POST['manual_booking']) && $_POST['manual_booking'] == 'yes' && !empty($_POST['id'])) {

        if (!empty($name) && !empty($qty_booking)) {
            $q = "INSERT INTO bs_reservations (dateCreated, name, email, phone, comments,status,eventID, qty)
			VALUES (NOW(),'" . $name . "','" . $email . "','" . $phone . "','" . $comments . "','" . $status_add . "','" . $id . "','" . $qty_booking . "')";
            $res = mysql_query($q) or die("error!");
            //$msg2 .= "<span style='color:#00aa00'>" . ADM_MSG5 . "</span>";
             addMessage(ADM_MSG5,"success");
        } else {
            //$msg2 .= "<span style='color:#aa0000'>" . ADM_MSG6 . "</span>";
             addMessage(ADM_MSG6,"error");
        }
    }
    ########################################################################################################################################################

    ########################################################################################################################################################
    if (isset($_GET['delImg']) && $_GET['delImg'] == 'yes' && !empty($_GET['id'])) {

        $sql = "SELECT path FROM bs_events WHERE id='" . $id . "'";
        $result = mysql_query($sql) or die("oopsy, error when tryin to get images 1");
        @unlink($_SERVER['DOCUMENT_ROOT'] . $baseDir . mysql_result($result, 'path'));

        $sSQL = "UPDATE bs_events SET path='' WHERE id='" . $id . "'";
        mysql_query($sSQL) or die("Invalid query: " . mysql_error() . " - $sSQL");
    }
    ########################################################################################################################################################
    //"edit page" action processing.
    if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes") {
        if (!empty($eventTime) && !empty($title) && !empty($spaces) && !empty($description) && !empty($eventDate) && !empty($allow_multiple)) {

            $eventDateEnd = ($eventDateEnd == 'YYYY-MM-DD' ? $eventDate : $eventDateEnd) . " " . $eventTimeEnd;
            $eventDate = $eventDate . " " . $eventTime;

            if ($eventDate < $eventDateEnd) {
               $msg= EVENT_SUC_MSG;
                

                if (empty($id)) {
                    $sql = "INSERT INTO bs_events (serviceID,eventDate,eventDateEnd,title,spaces,description,entryFee,payment_method,payment_required,max_qty,allow_multiple)
							VALUES ('" . $serviceID . "','" . $eventDate . "','" . $eventDateEnd . "','" . $title . "','" . $spaces . "','" . $description . "','" . $entryFee . "','" . $payment_method . "','" . $payment_required . "','" . $max_qty . "','" . $allow_multiple . "')";

                    $result = mysql_query($sql) or die("oopsy, error occured when tryin to create new event." . "<br>" . $sql);
                    $id = mysql_insert_id();
                    //$msg = "<span style='color:#00aa00'>".EVENT_SUC_UPD."</span.>";
                   
                    $msg= EVENT_SUC_UPD;
                }
                

                if (!empty($_FILES['picture']['name'])) {
                    $name = mktime();
                    $imgPathUrl == null;
                    $photoFileNametmp = $_FILES['picture']['name'];
                    $fileNamePartstmp = explode(".", $photoFileNametmp);
                    $counter2 = count($fileNamePartstmp) - 1;
                    $fileExtensiontmp = strtolower($fileNamePartstmp[$counter2]); // part behind last dot

                    if ($demo) {
                        $imgPath = $baseDir . "images/defaultEvent.jpg";
                    } else {
                        $imgPath = uploadFile($_FILES['picture'], $_SERVER['DOCUMENT_ROOT'] . $baseDir . "uploads/" . $name . "." . $fileExtensiontmp);
                        $imgPathUrl = "/uploads/" . $name . "." . $fileExtensiontmp;
                    }
                    $sql = "SELECT path FROM bs_events WHERE id='" . $id . "'";
                    $result = mysql_query($sql) or die("oopsy, error when tryin to get images 1");
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $baseDir . mysql_result($result, 'path'));

                    $sSQL = "UPDATE bs_events SET path='" . $imgPathUrl . "' WHERE id='" . $id . "'";
                    mysql_query($sSQL) or die("Invalid query: " . mysql_error() . " - $sSQL");

                }
                addMessage($msg,"success");
                $sql = "UPDATE bs_events SET title='" . $title . "',
											eventDate='" . $eventDate . "',
											eventDateEnd='" . $eventDateEnd . "',
											eventTime='" . $eventTime . "',
											serviceID='" . $serviceID . "',
											spaces='" . $spaces . "',
											description='" . $description . "',
											entryFee='" . $entryFee . "',
											payment_method='" . $payment_method . "',
											payment_required='" . $payment_required . "',
											max_qty='" . $max_qty . "',
											allow_multiple='" . $allow_multiple . "' WHERE id='" . $id . "'";
                $result = mysql_query($sql) or die("oopsy, error occured when tryin to update event.");

            } else {

                //$msg = "<span style='color:#aa0000'>".EVENT_STR_TIME."</span>";
                addMessage(EVENT_STR_TIME,"error");
            }
            //print $sql;
        } else {
            //$msg = "<span style='color:#aa0000'>All fields are required!</span>";
            addMessage(ALLFIELDSREQ,"error");
        }
    }
    ########################################################################################################################################################


    ########################################################################################################################################################
    //edit attendees processing.
    $sent = false;
    if (!empty($_REQUEST["attendees_edit"]) && $_REQUEST["attendees_edit"] == "yes") {
        if (!empty($status_upd)) {
            foreach ($status_upd as $key => $value) {
                if ($old_status_upd[$key] != $status_upd[$key]) {
                    $sql = "UPDATE bs_reservations SET status='" . $value . "' WHERE id='" . $key . "' AND eventID='" . $id . "'";
                    $result = mysql_query($sql) or die("oopsy, error occured when tryin to update event.");

                    //get customer name and email
                    $custInf = getInfoByReservID($key);
                    //get event information for email notification
                    $eventInf = getEventInfo($id);


                    if ($old_status_upd[$key] != $status_upd[$key] && $status_upd[$key] == "1" && !empty($old_status_upd[$key])) {
                        //send confirmation to client only if previous status was anything but "Confirmed"

                        $subject = "Event booking confirmed!";
                        mail($custInf[1], $subject, $message, $headers);
                        $data = array(
                            "{%name%}" => $custInf[0],
                            "{%service%}" => $serviceName,
                            "{%eventName%}" => $eventInf[0],
                            "{%eventDate%}" => $eventInf[2],
                            "{%eventDescr%}" => $eventInf[1],
                            "{%qty%}" => $custInf[2],
                            "{%status%}" => 'Confirmed'
                        );
                        sendMail($custInf[1], $subject, "eventBookingConfirmationStatus.php", $data);
                        $sent = true;
                        //$msg2 .= "Event confirmation email sent to customers!";
                    }


                    if ($old_status_upd[$key] != $status_upd[$key] && $status_upd[$key] == "3" && !empty($old_status_upd[$key])) {
                        //send cancel email to customer only if status was anything but cancelled (before)

                        $subject = "Event booking cancelled!";
                        $data = array(
                            "{%name%}" => $custInf[0],
                            "{%service%}" => $serviceName,
                            "{%eventName%}" => $eventInf[0],
                            "{%eventDate%}" => $eventInf[2],
                            "{%eventDescr%}" => $eventInf[1],
                            "{%qty%}" => $custInf[2],
                            "{%status%}" => 'Cancelled'
                        );
                        sendMail($custInf[1], $subject, "eventBookingConfirmationStatus.php", $data);
                        $sent = true;
                        //$msg2 .= "Event cancellation email sent to customer!";
                    }


                }
            }

            //$msg2 .= "<span style='color:#00aa00'>".STAT_UPDT."</span>";
             addMessage(STAT_UPDT,"success");
            if ($sent)
               // $msg2 .= "<span style='color:#00aa00'>".NTFC_ESENT."</span>";
             addMessage(NTFC_ESENT,"success");
        }
    }
    ########################################################################################################################################################


    ########################################################################################################################################################
    //"delete selected attendees" action processing.
    if (!empty($_REQUEST["del"]) && $_REQUEST["del"] == "yes") {
        $todelID = (!empty($_REQUEST["bsid"])) ? strip_tags(str_replace("'", "`", $_REQUEST["bsid"])) : '';
        if (!empty($todelID)) {
            $sql = "DELETE FROM bs_reservations_items WHERE reservationID='" . $todelID . "'";
            $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 1");
            $sql = "DELETE FROM bs_reservations WHERE id='" . $todelID . "'";
            $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 2");
            //$msg2 .= "<span style='color:#00aa00'>".SEL_ATT_DEL."</span>";
            addMessage(SEL_ATT_DEL,"warning");
        }
    }
    ########################################################################################################################################################


    //prepare attendees page.
    $files_table = "";
    ###################################################################################################################################################
    if (!empty($id)) {
        //PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
        $sql = "SELECT * FROM bs_reservations WHERE eventID='" . $id . "' ORDER BY dateCreated DESC";
        $result = mysql_query($sql) or die("error getting attendees from db");
        if (mysql_num_rows($result) > 0) {
            while ($rr = mysql_fetch_assoc($result)) {

                //$editable="<a href=\"bs-events-add.php?id=".$rr["id"]."\"><img src=\"images/pencil_16.png\" alt=\"Edit this event\" border=\"0\"/></a>";
                if (!empty($rr["comments"])) {
                    $editable = "<img src=\"images/bubble_16.png\" alt=\"Customers comment\" class=\"tipTip\" title=\"" . $rr["comments"] . "\" border=\"0\"/>";
                } else {
                    $editable = "&nbsp;";
                }
                $editable .= "<a href=\"bs-bookings_event-edit.php?id={$rr['id']}\"><img src=\"images/pencil_16.png\" alt=\"Edit this booking\" border=\"0\"/></a>";
                $bgClass = ($bgClass == "even" ? "odd" : "even");

                $files_table .= "<tr class=\"" . $bgClass . "\">";
                $files_table .= "";
                $files_table .= "<td height=\"24\"><a href='bs-events-add.php?id=" . $id . "&amp;del=yes&amp;bsid=" . $rr["id"] . "'><img src='images/delete_16.png' width='10' border=\"0\"></a></td>";
                $files_table .= "<td>" . $rr["name"] . "</td>";
                $files_table .= "<td>" . $rr["phone"] . "</td>";
                $files_table .= "<td>" . $rr["qty"] . "</td>";
                $files_table .= "<td>" . $rr["email"] . "</td>";
                $files_table .= "<td>" . getDateFormat($rr["dateCreated"]) . "</td>";

                $files_table .= "<td><input type='hidden' name='old_status_upd[" . $rr["id"] . "]' value='" . $rr["status"] . "'>
			 <select name='status_upd[" . $rr["id"] . "]'>
			 <option value='1' " . ($rr["status"] == "1" ? "selected" : "") . ">".BOOKING_FRM_CONFIRMED."</option>
			 <option value='2' " . ($rr["status"] == "2" ? "selected" : "") . ">".BOOKING_FRM_NOTCONFIRMED."</option>
			 <option value='3' " . ($rr["status"] == "3" ? "selected" : "") . ">".BOOKING_FRM_CANCELLED."</option>
			 <option value='4' " . ($rr["status"] == "4" ? "selected" : "") . ">".BOOKING_FRM_PAID."</option>
			 <option value='5' " . ($rr["status"] == "5" ? "selected" : "") . ">".BOOKING_FRM_USERCANCELLED."</option>
			 </select></td>";
                $files_table .= "<td>" . $editable . "</td></tr>";

            } // end of all files from db query (end of while loop)

            //show button to complete file deletion if proper permissions.

            $files_table .= "<tr><td height=\"32\" colspan=\"7\"  align='right'><input name=\"delete_files\" type=\"submit\" value=\"Update Statuses\"  /></td></tr>";


        } else {
            //0 files found in database. ( end of IF mysql_num_rows > 0 )
            $files_table .= "<tr><td colspan=\"7\">".NO_FOUND."</td></tr>";
        }
    }
    ###################################################################################################################################################


    //select event info and show it for editor.
    $sSQL = "SELECT id,title,eventDate,eventDateEnd,eventTime,spaces,description,entryFee,payment_required, allow_multiple, max_qty, path, serviceID,payment_method FROM bs_events WHERE id='" . $id . "'";
    $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
    if ($row = mysql_fetch_assoc($result)) {
        foreach ($row as $key => $value) {
            $$key = $value;
        }
    }
    mysql_free_result($result);

    if (!empty($eventDate)) {
        $timetmp = explode(" ", $eventDate);
        $timetmp = explode(":", $timetmp[1]);
    } else {
        $timetmp = array();
        $timetmp[0] = $hh;
        $timetmp[1] = $mm;
    }

    if (!empty($eventDateEnd)) {
        $timetmp1 = explode(" ", $eventDateEnd);
        $timetmp1 = explode(":", $timetmp1[1]);
    } else {
        $timetmp1 = array();
        $timetmp1[0] = $hh1;
        $timetmp1[1] = $mm1;
    }

    $time_selector .= "<select name='hh' class='small'>";
    for ($i = 0; $i < 24; $i++) {
        $time_selector .= "<option value='" . str_pad($i, 2, "0", STR_PAD_LEFT) . "' " . ($timetmp[0] == $i || $i==$hh ? "selected" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>";
    }
    $time_selector .= "</select>";

    $time_selector .= "&nbsp;<select name='mm' class='small'>";
    for ($m = 0; $m < 60; $m++) {
        $time_selector .= "<option value='" . str_pad($m, 2, "0", STR_PAD_LEFT) . "' " . ($timetmp[1] == $m ||$m==$mm ? "selected" : "") . ">" . str_pad($m, 2, "0", STR_PAD_LEFT) . "</option>";
    }
    $time_selector .= "</select>";

    $time_selector1 .= "<select name='hh1' class='small'>";
    for ($i = 0; $i < 24; $i++) {
        $time_selector1 .= "<option value='" . str_pad($i, 2, "0", STR_PAD_LEFT) . "' " . ($timetmp1[0] == $i || $i==$hh1  ? "selected" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>";
    }
    $time_selector1 .= "</select>";

    $time_selector1 .= "&nbsp;<select name='mm1' class='small'>";
    for ($m = 0; $m < 60; $m++) {
        $time_selector1 .= "<option value='" . str_pad($m, 2, "0", STR_PAD_LEFT) . "' " . ($timetmp1[1] == $m || $m==$mm1  ? "selected" : "") . ">" . str_pad($m, 2, "0", STR_PAD_LEFT) . "</option>";
    }
    $time_selector1 .= "</select>";
    ?>

<?php include "includes/admin_header.php"; ?>

<script type="text/javascript">
    $(function () {
        $("#eventDate").datepicker({
            changeMonth:true,
            changeYear:true,
            dateFormat:"yy-mm-dd"
        });
        $("#eventDateEnd").datepicker({
            changeMonth:true,
            changeYear:true,
            dateFormat:"yy-mm-dd"
        });
        //$('#reserveDateFrom').datepicker('option', {dateFormat: "yy-mm-dd"});

        

    });
    function noAlpha(obj) {
        reg = /[^0-9.,]/g;
        obj.value = obj.value.replace(reg, "");
    }
</script>
<div id="content">



    
<?php  getMessages(); ?>
<div class="content_block">
    <h2><?php echo ADD_EDIT_EVENT; ?></h2>
   

    <form action="bs-events-add.php" enctype="multipart/form-data" method="post" name="ff1">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="152" height="25" align="right"><?php echo EVENT_TTL?></td>
                <td width="632" height="23">
                    <input type="text" name="title" id="title" value="<?php echo $title?>"/></td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo BOOKING_FRM_SERVICE?></td>
                <td width="632" height="23">
                    <select name="serviceID">
                        <?php
                        $sql = "SELECT * FROM bs_services";
                        $res = mysql_query($sql);
                        while ($row = mysql_fetch_assoc($res)) {
                            ?>
                            <option
                                value="<?php echo $row['id']?>" <?php echo ($serviceID == $row['id']) ? "selected" : ""?>><?php echo $row['name']?></option>
                            <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right" valign="top"><?php echo EVENT_DISCRP?></td>
                <td width="632" height="23">
                    <textarea name="description" id="description" rows="8"
                              cols="50"/><?php echo $description?></textarea></td>
            </tr>


            <tr>
                <td width="152" height="25" align="right">Imagem:</td>
                <td width="632" height="23"><input type="file" name="picture" class="txt"/>
                    <img src='images/info.png' border="0"  class="tipTip" title="<?php echo IMGJPG?>"/>
                    
                </td>
            </tr>

            <?php if (isset($path) && $path != '') { ?>
            <tr>
                <td width="152" height="25" align="right" valign="top"><?php echo CRNT_EV_IMG?></td>
                <td width="632" height="23">
                    <a href="bs-events-add.php?id=<?php echo $id?>&delImg=yes"><?php echo DEL_IMG?></a><br/>
                    <img height="100" src=".<?php echo $path?>"/></td>
            </tr>
            <?php } ?>



            <tr>
                <td width="152" height="25" align="right"><?php echo EVENT_ST_DATE?></td>
                <td width="632" height="23">
                    <input type="text" name="eventDate" id="eventDate" class="small"
                           value="<?php echo (!empty($eventDate) ? date("Y-m-d", strtotime($eventDate)) : "YYYY-MM-DD")?>"/>
                    <?php echo $time_selector;?>
                     
                    

                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo EVENT_ENDDATE?></td>
                <td width="632" height="23">
                    <input type="text" name="eventDateEnd" id="eventDateEnd" class="small"
                           value="<?php echo (!empty($eventDateEnd) ? date("Y-m-d", strtotime($eventDateEnd)) : "YYYY-MM-DD")?>"/>
                    <?php echo $time_selector1;?>
                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo MAX_SPACE?></td>
                <td width="632" height="23">
                    <input type="text" name="spaces" id="spaces" class="small" value="<?php echo $spaces?>" onkeyup="noAlpha(this)"/>
                    <?php NUMB_PLZ?>
                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo PAYMT?></td>
                <td width="632" height="23">
                    <input type="radio" name="payment_required"
                           value="1" <?php echo $payment_required == "1" ? "checked" : ""?> />
                    <?php echo REC?>
                    <input type="radio" name="payment_required"
                           value="2" <?php echo $payment_required == "2" ? "checked" : ""?> <?php if (empty($payment_required)) {
                        echo "checked";
                    } ?> />
                    
                    <?php echo NOTREC?>
                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo PAY_METD?></td>
                <td width="632" height="23">

                            <select name="payment_method">
                            <?php
                            $paymentMethosList = unserialize(getOption("payment_methods"));
                            foreach ($paymentMethosList as $key => $value) {
                                ?>
                                <option value="<?php echo $key ?>" <?php echo $payment_method == $key ? "selected" : "" ?>><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                        <img src='images/info.png' border="0"  class="tipTip" title=" <?php echo OFFL_INVC_MSG?>"/>
                   
                </td>
            </tr>
            <tr>
                <td width="152" height="25" align="right"><?php echo PRICE?></td>
                <td width="632" height="23">
                    <input type="text" name="entryFee" id="entryFee" value="<?php echo $entryFee?>" class="small"
                           onkeyup="noAlpha(this)"/>&nbsp;<?php echo getOption('currency')?>
                     <img src='images/info.png' border="0"  class="tipTip" title="<?php echo NUMB_PLZ?>"/>
                    
                </td>
            </tr>

            <tr>
                <td width="152" height="25" align="right"><?php echo TCT_QNTT?></td>
                <td width="632" height="23">
                    <input type="radio" name="allow_multiple"
                           value="1" <?php echo $allow_multiple == "1" ? "checked" : ""?> />
                    <?php echo MLTP_TCT_CSTM?>
                    <input type="radio" name="allow_multiple"
                           value="2" <?php echo $allow_multiple == "2" ? "checked" : ""?> <?php if (empty($allow_multiple)) {
                        echo "selected";
                    } ?> />
                    <?php echo ONE_TCT_CSTM?>
                </td>
            </tr>

            <tr>
                <td width="152" height="25" align="right"><?php echo MXM_TCT?></td>
                <td width="632" height="23">
                    <input type="text" name="max_qty" id="max_qty" value="<?php echo $max_qty?>" class="small"
                           onkeyup="noAlpha(this)"/>
                    <img src='images/info.png' border="0"  class="tipTip" title="<?php echo TCT_MSG?>"/>
                    
                </td>
            </tr>

            <tr>
                <td height="25" align="right">&nbsp;</td>
                <td height="32"><br/>

                    <input type="submit" name="create" id="create" value="<?php echo ADM_BTN_SUBMIT;?>"/>
                    <input value="yes" name="edit_page" type="hidden"/>
                    <input value="<?php echo $id;?>" name="id" type="hidden"/>
                </td>
            </tr>
        </table>
    </form>


    <?php if (!empty($id)) { ?>
    <h2><?php echo ADD_MNL_BOOK?></h2>
    <strong><?php echo $msg3; ?></strong>
        <?php if (getSpotsLeftForEvent($id) > 0) { ?>
        <form enctype="multipart/form-data" action="bs-events-add.php" method="post" name="ff2">
            <table width="784" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_NAME?>:</td>
                    <td width="632" height="23">
                        <input type="text" name="name" id="name" value="<?php echo $name?>"/></td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_EMAIL?>:</td>
                    <td width="632" height="23">
                        <input type="text" name="email" id="email" value="<?php echo $email?>"/></td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_PHONE?>:</td>
                    <td width="632" height="23">
                        <input type="text" name="phone" id="phone" value="<?php echo $phone?>"/></td>
                </tr>

                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_STATUS?></td>
                    <td width="632" height="23">
                        <select name='status_add'>
                            <option value='1' <?php echo $status_add == "1" ? "selected='selected'" : ""?>><?php echo
                                BOOKING_FRM_CONFIRMED?>                            </option>
                            <option value='2' <?php echo $status_add == "2" ? "selected='selected'" : ""?>><?php echo BOOKING_FRM_NOTCONFIRMED?>
                            </option>
                            <option value='3' <?php echo $status_add == "3" ? "selected='selected'" : ""?>><?php echo BOOKING_FRM_CANCELLED?>
                            </option>
                            <option value='4' <?php echo $status_add == "4" ? "selected='selected'" : ""?>><?php echo BOOKING_FRM_PAID?></option>
                            <option value='5' <?php echo $status_add == "5" ? "selected='selected'" : ""?>><?php echo BOOKING_FRM_USERCANCELLED?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_COMMENTS?>:</td>
                    <td width="632" height="23">
                        <textarea type="text" name="comments" id="comments"><?php echo $comments?></textarea></td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_QTY?></td>
                    <td width="632" height="23">
                        <select name="qty_booking">
                            <?php for ($i = 1; $i <= getSpotsLeftForEvent($id); $i++) { ?>
                            <option
                                value="<?php echo $i?>" <?php echo $qty_booking == $i ? "selected='selected'" : ""?>><?php echo $i?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right">&nbsp;</td>
                    <td width="632" height="23">
                        <input value="Add Booking" type="submit"/></td>
                </tr>
                

            </table>
            <input type="hidden" value="yes" name="manual_booking"/>
            <input value="<?php echo $id;?>" name="id" type="hidden"/>
        </form>
            <?php } else { ?>
        <?php echo ALL_BOOKED?>
            <?php } ?>
    <h2>Attendees</h2>
    <strong><?php echo $msg2; ?></strong>
    <form enctype="multipart/form-data" action="bs-events-add.php" method="post" name="ff2">
        <input type="hidden" value="yes" name="attendees_edit"/>
        <input value="<?php echo $id;?>" name="id" type="hidden"/>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="topRow">
                <td width="3%" height="30" align="center">&nbsp;</td>
                <td width="21%" align="left"><strong><?php echo BOOKING_FRM_NAME?></strong></td>
                <td width="19%" align="left"><strong><?php echo BOOKING_FRM_PHONE?></strong></td>
                <td width="21%" align="left"><strong><?php echo BOOKING_FRM_QTY?></strong></td>
                <td width="18%" align="left"><strong><?php echo BOOKING_FRM_EMAIL?></strong></td>
                <td width="20%" align="left"><strong><?php echo DATE_SUBSCR?></strong></td>
                <td width="15%" align="left"><strong><?php echo BOOKING_FRM_STATUS?></strong></td>
                <td width="4%" height="30" align="center">&nbsp;</td>
            </tr>
            <?php echo $files_table; ?>
        </table>
    </form>
    </div>
 </div>
 <?php } ?>
<?php include "includes/admin_footer.php"; ?>
<?php }  ?>