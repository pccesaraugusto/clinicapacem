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
        exit();
    } else {
        
         bw_do_action("bw_load");
        bw_do_action("bw_admin");
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
        $qty = (!empty($_REQUEST["qty"])) ? strip_tags(str_replace("'", "`", $_REQUEST["qty"])) : '';

        //"edit page" action processing.
        if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes" && !empty($name)) {
            //$msg .= BOOKING_SUCC;
            addMessage(BOOKING_SUCC,"success");
            /*if(empty($id)){
                   $sql="INSERT INTO bs_reserved_time (dateCreated,reason,reserveDateFrom,reserveDateTo) VALUES (NOW(),'".$reason."','".$reserveDateFrom." ".$x1_from.":00"."','".$reserveDateTo." ".$x2_from.":00"."')";
                   $result=mysql_query($sql) or die("oopsy, error occured when tryin to create new booking.");
                   $id = mysql_insert_id();
                   $msg = "Booking was successfully created!";
               }*/
            $sql = "UPDATE bs_reservations SET
			name='" . $name . "',
			phone='" . $phone . "',
			email='" . $email . "',
			status='" . $status . "',
			comments='" . $comments . "' ,
			serviceID='" . $serviceID . "',
			qty='" . $qty . "'
			WHERE id='" . $id . "'";
            $result = mysql_query($sql) or die(GENERIC_QUERY_FAIL);
        }

        //select editable user's info and show it for editor.
        $sSQL = "SELECT id,dateCreated,name,phone,email,status,comments,serviceID,qty FROM bs_reservations WHERE id='" . $id . "'";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        if ($row = mysql_fetch_assoc($result)) {
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        }
        mysql_free_result($result);


        //booked dates processing.
        $bookingArrFrom = (!empty($_REQUEST["bookingArrFrom"])) ? $_REQUEST["bookingArrFrom"] : '';
        $bookingArrTo = (!empty($_REQUEST["bookingArrTo"])) ? $_REQUEST["bookingArrTo"] : '';

        $act1 = false;
        $act2 = false;
        if (!empty($bookingArrTo) && !empty($bookingArrFrom)) {
            foreach ($bookingArrFrom as $key => $value) {
                if (!empty($value)) {
                    //update
                    $q = "UPDATE bs_reservations_items SET
					reserveDateFrom='" . $value . "',
					reserveDateTo='" . $bookingArrTo[$key] . "',
					qty='" . $qty . "'
					WHERE id='" . $key . "'";
                    //echo $q;
                    mysql_query($q) or die(mysql_error());
                    $act1 = true;
                } else {
                    //delete
                    if (empty($bookingArrTo[$key])) {
                        $q = "DELETE FROM bs_reservations_items WHERE id='" . $key . "'";
                        mysql_query($q);
                        $act2 = true;
                    }
                }
            }
        }
        if ($act1) {
           // $msg .= BOOKING_TIME_UPDATED;
            addMessage(BOOKING_TIME_UPDATED,"success");
        }
        if ($act2) {
            //$msg .= BOOKING_TIME_DELETED;
            addMessage(BOOKING_TIME_DELETED,"success");
        }
        $booked_dates = "";
        $bookingData = array();
        $sSQL = "SELECT * FROM bs_reservations_items WHERE reservationID='" . $id . "'";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        while ($row = mysql_fetch_assoc($result)) {
            $booked_dates .= "From: <input type='text' name='bookingArrFrom[" . $row["id"] . "]' value='" . $row["reserveDateFrom"] . "'><br />";
            $booked_dates .= "To: <input type='text' name='bookingArrTo[" . $row["id"] . "]' value='" . $row["reserveDateTo"] . "'><br /><br />";


            $bookingData[] = array(
                'date' => getDateFormat($row["reserveDateFrom"]),
                'timeFrom' => date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateFrom"])),
                'timeTo' => date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateTo"])),
                'qty' => $qty
            );
        }


        if ($old_status != $status && $status == "1" && !empty($old_status)) {
            //send confirmation to client.
            //send email to customer

            $subject = EMAIL_SUBJ_CONFIRMED;
            $data = array(
                "{%name%}" => $name,
                "{%status%}" => BOOKING_FRM_CONFIRMED,
                "_info" => $bookingData,

            );
            sendMail($email, $subject, "timeBookingConfirmationStatus.php", $data);

            addMessage(ADM_MSG1,"success");
        }


        if ($old_status != $status && $status == "3" && !empty($old_status)) {
            //send cancel email to customer

            $subject = EMAIL_SUBJ_CANCELLED;
            $data = array(
                "{%name%}" => $name,
                "{%status%}" => BOOKING_FRM_CANCELLED,
                "_info" => $bookingData,

            );
            sendMail($email, $subject, "timeBookingConfirmationStatus.php", $data);
            addMessage(ADM_MSG2,"success");
        }

        ?>

    <?php include "includes/admin_header.php"; ?>

<div id="content">
    


    
<?php  getMessages(); ?>
    <div class="content_block">
        <h2><?php echo BOOKING_EDIT_TITLE ?></h2>
        

        <form action="bs-bookings-edit.php" enctype="multipart/form-data" method="post" name="ff1">
            <input type="hidden" value="<?php echo $status?>" name="old_status"/>
            <table width="784" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_DATE ?></td>
                    <td width="600" height="23">&nbsp;
                        <?php echo getDateFormat($dateCreated) . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["reserveDateFrom"]))?></td>
                </tr>
                <tr>
                    <td width="152" height="25" align="right"><?php echo BOOKING_FRM_SERVICE ?></td>
                    <td width="632" height="23">
                        <select name="serviceID">
                            <?php
                            $sql = "SELECT * FROM bs_services";
                            $res = mysql_query($sql);
                            while ($row = mysql_fetch_assoc($res)) {
                                ?>
                                <option value="<?php echo $row['id']?>" <?php echo ($serviceID == $row['id']) ? "selected" : ""?>><?php echo $row['name']?></option>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_STATUS ?></td>
                    <td width="600" height="23">
                        <select name="status">
                            <option value=""><?php echo BOOKING_FRM_SELECT ?></option>
                            <option value="1" <?php echo $status == "1" ? "selected" : ""?>><?php echo BOOKING_FRM_CONFIRMED ?></option>
                            <option value="2" <?php echo $status == "2" ? "selected" : ""?>><?php echo BOOKING_FRM_NOTCONFIRMED ?></option>
                            <option value="3" <?php echo $status == "3" ? "selected" : ""?>><?php echo BOOKING_FRM_CANCELLED ?></option>
                            <option value="4" <?php echo $status == "4" ? "selected" : ""?>><?php echo BOOKING_FRM_PAID?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_QTY ?></td>
                    <td width="600" height="23">
                        <input type="text" name="qty" id="qty" class="small" value="<?php echo $qty?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_NAME ?></td>
                    <td width="600" height="23">
                        <input type="text" name="name" id="name" value="<?php echo $name?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_EMAIL ?></td>
                    <td width="600" height="23">
                        <input type="text" name="email" id="email" value="<?php echo $email?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right"><?php echo BOOKING_FRM_PHONE ?></td>
                    <td width="600" height="23">
                        <input type="text" name="phone" id="phone" value="<?php echo $phone?>"/></td>
                </tr>
                <tr>
                    <td width="184" height="25" align="right" valign="top"><?php echo BOOKING_FRM_COMMENTS ?></td>
                    <td width="600" height="23">
                        <textarea name="comments" rows="5" cols="50"><?php echo $comments?></textarea></td>
                </tr>

                <tr>
                    <td width="184" height="25" align="right" valign="top"><?php echo BOOKING_FRM_BOOKEDDATES ?></td>
                    <td width="600" height="23"><?php echo $booked_dates?></td>
                </tr>

                <tr>
                    <td height="25" align="right">&nbsp;</td>
                    <td height="32">
                        <?php echo BOOKING_FRM_NOTE1 ?><br/>

                        <input type="submit" name="create" id="create" value="<?php echo ADM_BTN_SUBMIT ?>"/>
                        <input value="yes" name="edit_page" type="hidden"/>
                        <input value="<?php echo $id;?>" name="id" type="hidden"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php include "includes/admin_footer.php";?>
<?php } ?>