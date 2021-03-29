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

    ######################### DO NOT MODIFY (UNLESS SURE) END ########################
    //show page only if admin access level
    //request all neccessary variables for user update action.
    $id = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
    $reason = (!empty($_REQUEST["reason"])) ? strip_tags(str_replace("'", "`", $_REQUEST["reason"])) : '';
    $reserveDateFrom = (!empty($_REQUEST["reserveDateFrom"])) ? strip_tags(str_replace("'", "`", $_REQUEST["reserveDateFrom"])) : '';
    $reserveDateTo = (!empty($_REQUEST["reserveDateTo"])) ? strip_tags(str_replace("'", "`", $_REQUEST["reserveDateTo"])) : $reserveDateFrom;
    $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : '1';
    $qty = (!empty($_REQUEST["qty"])) ? strip_tags(str_replace("'", "`", $_REQUEST["qty"])) : '1';

    $recurring = (isset($_REQUEST["recurring"])) ? strip_tags(str_replace("'", "`", $_REQUEST["recurring"])) : '0';
    $repeate_interval = (!empty($_REQUEST["repeate_interval"])) ? strip_tags(str_replace("'", "`", $_REQUEST["repeate_interval"])) : '1';
    $repeate = (!empty($_REQUEST["repeate"])) ? strip_tags(str_replace("'", "`", $_REQUEST["repeate"])) : '';


    $x1_from_h = (!empty($_REQUEST["1_from_h"])) ? strip_tags(str_replace("'", "`", $_REQUEST["1_from_h"])) : '';
    $x1_from_m = (!empty($_REQUEST["1_from_m"])) ? strip_tags(str_replace("'", "`", $_REQUEST["1_from_m"])) : '';

    $x2_from_h = (!empty($_REQUEST["2_from_h"])) ? strip_tags(str_replace("'", "`", $_REQUEST["2_from_h"])) : '';
    $x2_from_m = (!empty($_REQUEST["2_from_m"])) ? strip_tags(str_replace("'", "`", $_REQUEST["2_from_m"])) : '';

    $x1_from = $x1_from_h . ":" . $x1_from_m;
    $x2_from = $x2_from_h . ":" . $x2_from_m;


    //"edit page" action processing.
    if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes") {

        if (!empty($reason) && !empty($reserveDateFrom) && !empty($reserveDateTo) && !empty($serviceID)) {

            if (!checkSchedule($reserveDateFrom, $reserveDateTo, $x1_from, $x2_from, $serviceID, $qty, $id) && !$recurring) {

                //$msg = "This time booked!";
                addMessage(MSG_TMBK,"warning");
            } elseif ((date("Y-m-d", strtotime($reserveDateTo)) < date("Y-m-d", strtotime($reserveDateFrom . " +$repeate_interval $repeate"))) && $recurring) {

                //$msg = "Reserved Date To earlier than the minimum interval";
                addMessage(MSG_DATETO1);
            } else {
                //$msg .= "Booking was successfully updated!";
                //addMessage("Booking was successfully updated!","success");
                if (!empty($id)) {
                    // DELETE ALL RECORDS for this booking
                    $q = "DELETE FROM bs_reserved_time WHERE id='" . $id . "'";
                    mysql_query($q);
                    $q = "DELETE FROM bs_reserved_time_items WHERE reservedID='" . $id . "'";
                    mysql_query($q);
                }

                //now create the record from scratch....
                $sql = "INSERT INTO bs_reserved_time (serviceID,dateCreated,reason,reserveDateFrom,reserveDateTo,qty,repeate,repeate_interval,recurring) 
				VALUES ('" . $serviceID . "',NOW(),'" . $reason . "','" . $reserveDateFrom . " " . $x1_from . "','" . $reserveDateTo . " " . $x2_from . "','" . $qty . "','" . $repeate . "','" . $repeate_interval . "','" . $recurring . "')";
                $result = mysql_query($sql) or die("oopsy, error occured when tryin to create new booking.");
                $id = mysql_insert_id();
                //$msg = "Booking was successfully saved!";
                addMessage(MSG_BKSAVE,"success");
            }
        } else {
            //$msg = "All fields are required!";
            addMessage(ALLFIELDSREQ);
        }
    }

    //select editable user's info and show it for editor.
    $sSQL = "SELECT * FROM bs_reserved_time WHERE id='" . $id . "'";
    $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
    if ($row = mysql_fetch_assoc($result)) {
        foreach ($row as $key => $value) {
            $$key = $value;
        }
    }
    mysql_free_result($result);
    $maxQty = getServiceSettings($serviceID, 'show_multiple_spaces') ? getServiceSettings($serviceID, 'spaces_available') : 1;
    if (!empty($id)) {
        $tt1 = explode(" ", $reserveDateFrom);
        $reserveDateFrom = $tt1[0];
        $x1_from_h = substr($tt1[1], 0, 2);
        $x1_from_m = substr($tt1[1], 3, 5);

        $tt2 = explode(" ", $reserveDateTo);
        $reserveDateTo = $tt2[0];
        $x2_from_h = substr($tt2[1], 0, 2);
        $x2_from_m = substr($tt2[1], 3, 5);
    }
    ?>

    <?php include "includes/admin_header.php"; ?>
    <?php if (!$recurring) { ?>
        <style>
            .recurring{
                display:none;
            }
    <?php } ?>
    </style>
    <script type="text/javascript">
        $(function() {
            $("#reserveDateFrom").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd"
            });
            //$('#reserveDateFrom').datepicker('option', {dateFormat: "yy-mm-dd"});
            $("#reserveDateTo").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd"
            });
            //$('#reserveDateTo').datepicker('option', {dateFormat: "yy-mm-dd"})
    	
            $('#serviceID').bind("change",function(){
                var qty = $(this+":selected").attr("rel");
                var el='';
                for(var i=1;i<=qty;i++){
                    el+="<option value='"+i+"'>"+i+"</option>";
                }
                $("#qty").html(el);
            })
            $("#recurring").bind("change",function(){
    	
                if($(this).is(':checked')){
                    $('.recurring').show();
                }else{
                    $('.recurring').hide();
                }
            })
            $("#repeate").bind("change",function(){
    	
                $("#int_name").html($(this).val());
            })
        });
    	
    </script>
    <div id="content">
       


     
<?php  getMessages(); ?>
        <div class="content_block">
            <h2><?php echo ADD_EDIT_MAN_BOOK?></h2>
            
            <form action="bs-reserve.php" enctype="multipart/form-data" method="post" name="ff1">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="152" height="25" align="right"><?php echo SHRT_DESCRPTN?></td>
                        <td width="632" height="23">
                            <input type="text" name="reason" id="reason" value="<?php echo $reason ?>" /></td>
                    </tr>

                    <tr>
                        <td width="326" height="25" align="right"><?php echo SEL_SERVICE?></td>
                        <td width="458" height="23">
                        <?php
                        $sql = "SELECT *,bs.id as sid FROM bs_services bs  INNER JOIN bs_service_settings bss ON bss.serviceId=bs.id";
                        $res = mysql_query($sql);
                        if (mysql_num_rows($res) > 0) {
                            ?>


                                <select name="serviceID" id="serviceID">

                                <?php while ($row = mysql_fetch_assoc($res)) { ?>
                                        <option value="<?php echo $row['sid'] ?>" rel="<?php echo $row['show_multiple_spaces'] ? $row['spaces_available'] : 1 ?>" <?php echo ($serviceID == $row['sid']) ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                    <?php } ?>
                                </select>


                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="152" height="25" align="right"><?php echo BOOKING_FRM_QTY?></td>
                        <td width="632" height="23">
                            <select name="qty" id="qty" class='small'>
                                <?php for ($i = 1; $i <= $maxQty; $i++) { ?>
                                    <option value="<?php echo $i ?>" <?php echo $qty == $i ? "selected='selected'" : "" ?>><?php echo $i ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="152" height="25" align="right"><?php echo RES_DATE_FROM?></td>
                        <td width="632" height="23">
                            <input type="text" name="reserveDateFrom" class='small' id="reserveDateFrom" value="<?php echo $reserveDateFrom ?>" /> 
                            <select name="1_from_h" class='smaller'>

                                <?php for ($i = 0; $i <= 23; $i++) { ?>
                                    <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <?php echo $x1_from_h == $i ? "selected='selected'" : "" ?>><?php echo date((getTimeMode()) ? "g a" : "H", strtotime(date("Y-m-d") . " +" . ($i * 60) . " minutes")) ?></option>
                                <?php } ?>
                            </select>  
                            <select name="1_from_m" class='smaller'>
                                <?php for ($i = 0; $i <= 45; $i = $i + 15) { ?>
                                    <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <?php echo $x1_from_m == $i ? "selected='selected'" : "" ?>><?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="152" height="25" align="right"><?php echo RES_DATE_TO?></td>
                        <td width="632" height="23">
                            <input type="text" name="reserveDateTo" class='small' id="reserveDateTo" value="<?php echo $reserveDateTo ?>" />
                            <select name="2_from_h" class='smaller'>

                                <?php for ($i = 0; $i <= 23; $i++) { ?>
                                    <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <?php echo $x2_from_h == $i ? "selected='selected'" : "" ?>><?php echo date((getTimeMode()) ? "g a" : "H", strtotime(date("Y-m-d") . " +" . ($i * 60) . " minutes")) ?></option>
                                <?php } ?>
                            </select> 
                            <select name="2_from_m" class='smaller'>
                                 <?php for ($i = 0; $i <= 45; $i = $i + 15) { ?>
                                    <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?>" <?php echo $x2_from_m == $i ? "selected='selected'" : "" ?>><?php echo str_pad($i, 2, "0", STR_PAD_LEFT) ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="152" height="25" align="right"><?php echo RECURRING?>:</td>
                        <td width="632" height="23">
                            <input type="checkbox" name="recurring" id="recurring" value="1" <?php echo $recurring ? "checked" : "" ?> />

                        </td>
                    </tr>
                    <tr class="recurring">
                        <td width="152" height="25" align="right"><?php echo REP?>:</td>
                        <td width="632" height="23">
                            <select name="repeate" id="repeate" class='small'>	
                                <option value="day" <?php echo $repeate == 'day' ? "selected='selected'" : "" ?>><?php echo DAILY?></option>
                                <option value="week" <?php echo $repeate == 'week' ? "selected='selected'" : "" ?>><?php echo WEEKLY?></option>
                                <option value="month" <?php echo $repeate == 'month' ? "selected='selected'" : "" ?>><?php echo MONTHLY?></option>
                                <option value="year" <?php echo $repeate == 'year' ? "selected='selected'" : "" ?>><?php echo YEARLY?></option>

                            </select>
                            <img src='images/info.png' border="0"  class="tipTip" title="<?php echo REPEAT_MSG?>"/>
                        </td>
                    </tr>
                    <tr class="recurring">
                        <td width="152" height="25" align="right"><?php echo EVERY?></td>
                        <td width="632" height="23">
                            <select name="repeate_interval">
                                <?php for ($i = 1; $i <= 20; $i++) { ?>
                                    <option value="<?php echo $i ?>" <?php echo $repeate_interval == $i ? "selected='selected'" : "" ?>><?php echo $i ?></option>
                                <?php } ?>

                            </select>
                            <span id="int_name"><?php echo empty($repeate) ? "day" : $repeate ?></span>s
                        </td>
                    </tr>
                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="32">


                            <input type="submit" name="create" id="create" value="<?php echo ADM_BTN_SUBMIT;?>" />
                            <input value="yes" name="edit_page" type="hidden" />
                            <input value="<?php echo $id; ?>" name="id" type="hidden" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>

    <?php include "includes/admin_footer.php"; ?>
    <?php } ?>