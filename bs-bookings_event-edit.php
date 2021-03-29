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
    session_start();
    require_once("includes/dbconnect.php"); //Load the settings
    require_once("includes/config.php"); //Load the functions
    

    if ($_SESSION["logged_in"] != true) {
        header("Location: admin.php");
        exit();
    } else {

        ######################## DO NOT MODIFY (UNLESS SURE) END ########################

        //show page only if admin access level

        //request all neccessary variables for user update action.
        $id = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
        $name = (!empty($_REQUEST["name"])) ? strip_tags(str_replace("'", "`", $_REQUEST["name"])) : '';
        $email = (!empty($_REQUEST["email"])) ? strip_tags(str_replace("'", "`", $_REQUEST["email"])) : '';
        $phone = (!empty($_REQUEST["phone"])) ? strip_tags(str_replace("'", "`", $_REQUEST["phone"])) : '';
        $status = (!empty($_REQUEST["status"])) ? strip_tags(str_replace("'", "`", $_REQUEST["status"])) : '';
        $old_status = (!empty($_REQUEST["old_status"])) ? strip_tags(str_replace("'", "`", $_REQUEST["old_status"])) : '';
        $comments = (!empty($_REQUEST["comments"])) ? strip_tags(str_replace("'", "`", $_REQUEST["comments"])) : '';
        $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : '';
        $eventID = (!empty($_REQUEST["eventID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["eventID"])) : '';


        //"edit page" action processing.
        if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes" && !empty($name)) {
            $msg .= "Booking was successfully updated!";
            $sql = "UPDATE bs_reservations SET
			name='" . $name . "',
			phone='" . $phone . "',
			email='" . $email . "',
			status='" . $status . "',
			comments='" . $comments . "' ,
			
			eventID='" . $eventID . "'
			WHERE id='" . $id . "'";
            $result = mysql_query($sql) or die("oopsy, error occured when tryin to update page.");
            
        }

        //select editable user's info and show it for editor.
        $sSQL = "SELECT id,dateCreated,name,phone,email,status,comments,serviceID,eventID  FROM bs_reservations WHERE id='" . $id . "'";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        if ($row = mysql_fetch_assoc($result)) {
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        }
        mysql_free_result($result);

        //get customer name and email
        $custInf = getInfoByReservID($id);
        //get event information for email notification
        $eventInf = getEventInfo($eventID);


        if ($old_status != $status && $status == "1" && !empty($old_status)) {
            //send confirmation to client.
            //send email to customer
            $subject = "Event booking confirmed!";
            mail($custInf[1], $subject, $message, $headers);
            $data = array(
                "{%name%}" => $custInf[0],
                "{%service%}" => $serviceName,
                "{%eventName%}" => $eventInf[0],
                "{%eventDate%}" => $eventInf[2],
                "{%eventDescr%}" => $eventInf[1],
                "{%qty%}" => $custInf[2],
                "{%status%}" => BOOKING_FRM_CONFIRMED
            );
            sendMail($custInf[1], $subject, "eventBookingConfirmationStatus.php", $data);
            $sent = true;
            
            addMessage(ADM_MSG1, "success");
        }


        if ($old_status != $status && $status == "3" && !empty($old_status)) {
            //send cancel email to customer
            $subject = "Event booking confirmed!";
            //mail($custInf[1], $subject, $message, $headers);
            $data = array(
                "{%name%}" => $custInf[0],
                "{%service%}" => $serviceName,
                "{%eventName%}" => $eventInf[0],
                "{%eventDate%}" => $eventInf[2],
                "{%eventDescr%}" => $eventInf[1],
                "{%qty%}" => $custInf[2],
                "{%status%}" => BOOKING_FRM_CANCELLED
            );
            sendMail($custInf[1], $subject, "eventBookingConfirmationStatus.php", $data);
            $sent = true;
            
            addMessage(ADM_MSG2, "success");
        }

        include "includes/admin_header.php"; ?>
<div id="content">
    


    <?php getMessages(); ?>

    <div class="content_block">
        <h2><?php echo PAGE_TITLE2 ?></h2>
       

        <form action="bs-bookings_event-edit.php" enctype="multipart/form-data" method="post" name="ff1">
            <input type="hidden" value="<?php echo $status?>" name="old_status"/>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="184" height="25" align="right"><?php echo DATE_BOOK_PLC?></td>
                    <td width="600" height="23">
                        <?php echo date("d M Y, g:i a", strtotime($dateCreated))?></td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_LST_EVENTS ?>:</td>
                    <td width="632" height="23">
                        <select name="eventID">
                            <?php
                            $sql = "SELECT * FROM bs_events WHERE serviceID='{$serviceID}'";
                            $res = mysql_query($sql);
                            while ($row = mysql_fetch_assoc($res)) {
                                ?>
                                <option value="<?php echo $row['id']?>" <?php echo ($eventID == $row['id']) ? "selected" : ""?>><?php echo $row['title']?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_LST_STATUS ?>:</td>
                    <td width="600" height="23">
                        <select name="status">
                            <option value=""><?php echo BOOKING_FRM_SELECT ?></option>
                            <option value="1" <?php echo $status == "1" ? "selected" : ""?>><?php echo BOOKING_FRM_CONFIRMED ?></option>
                            <option value="2" <?php echo $status == "2" ? "selected" : ""?>><?php echo BOOKING_FRM_NOTCONFIRMED ?></option>
                            <option value="3" <?php echo $status == "3" ? "selected" : ""?>><?php echo BOOKING_FRM_CANCELLED ?></option>
                            <option value="4" <?php echo $status == "4" ? "selected" : ""?>><?php echo BOOKING_FRM_PAID ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_LST_NAME?>:</td>
                    <td width="600" height="23">
                        <input type="text" name="name" id="name" value="<?php echo $name?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_LST_EMAIL?>:</td>
                    <td width="600" height="23">
                        <input type="text" name="email" id="email" value="<?php echo $email?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_LST_PHONE?>:</td>
                    <td width="600" height="23">
                        <input type="text" name="phone" id="phone" value="<?php echo $phone?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right" valign="top"><?php echo BOOKING_FRM_COMMENTS?></td>
                    <td width="600" height="23">
                        <textarea name="comments" rows="5" cols="50"><?php echo $comments?></textarea></td>
                </tr>


                <tr>
                    <td height="25" align="right">&nbsp;</td>
                    <td height="32">


                        <input type="submit" name="create" id="create" value="<?php echo ADM_BTN_SUBMIT ?>"/>
                        <input value="yes" name="edit_page" type="hidden"/>
                        <input value="<?php echo $id;?>" name="id" type="hidden"/>
                    </td>
                </tr>
                <tr>
                    <td height="25" colspan="2" align="left"><a href="bs-events-add.php?id=<?php echo $eventID?>"><?php echo EVENT_LIST?></a></td>
                    
                </tr>
            </table>
        </form>
    </div>
    <?php include "includes/admin_footer.php";?>
    <?php } ?>