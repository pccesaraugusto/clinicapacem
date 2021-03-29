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

    $weeks = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
    ######################### DO NOT MODIFY (UNLESS SURE) END ########################
    //print var_dump($_REQUEST);
    //show page only if admin access level
    //request all neccessary variables for user update action.
    $id = (!empty($_REQUEST["id"])) ? strip_tags(str_replace("'", "`", $_REQUEST["id"])) : '';
    $name = (!empty($_REQUEST["name"])) ? strip_tags(str_replace("'", "`", $_REQUEST["name"])) : '';
    //setting variables
    $spot_price = (!empty($_REQUEST["spot_price"])) ? strip_tags(str_replace("'", "`", $_REQUEST["spot_price"])) : '0';
    $spot_invoice = (!empty($_REQUEST["spot_invoice"])) ? strip_tags(str_replace("'", "`", $_REQUEST["spot_invoice"])) : '0';
    $payment_method = (!empty($_REQUEST["payment_method"])) ? strip_tags(str_replace("'", "`", $_REQUEST["payment_method"])) : '';
    $interval = (!empty($_REQUEST["interval"])) ? strip_tags(str_replace("'", "`", $_REQUEST["interval"])) : '';
    $allow_times = (!empty($_REQUEST["allow_times"])) ? strip_tags(str_replace("'", "`", $_REQUEST["allow_times"])) : '';
    $allow_times_min = (!empty($_REQUEST["allow_times_min"])) ? strip_tags(str_replace("'", "`", $_REQUEST["allow_times_min"])) : '';
    $startDay = (!empty($_REQUEST["startDay"])) ? strip_tags(str_replace("'", "`", $_REQUEST["startDay"])) : '0';


    $spaces_available = (!empty($_REQUEST["spaces_available"])) ? strip_tags(str_replace("'", "`", $_REQUEST["spaces_available"])) : 1;
    $show_spaces_left = (!empty($_REQUEST["show_spaces_left"])) ? strip_tags(str_replace("'", "`", $_REQUEST["show_spaces_left"])) : 0;
    $show_event_titles = (!empty($_REQUEST["show_event_titles"])) ? strip_tags(str_replace("'", "`", $_REQUEST["show_event_titles"])) : 0;
    $show_event_image = (!empty($_REQUEST["show_event_image"])) ? strip_tags(str_replace("'", "`", $_REQUEST["show_event_image"])) : 0;
    $show_multiple_spaces = (!empty($_REQUEST["show_multiple_spaces"])) ? strip_tags(str_replace("'", "`", $_REQUEST["show_multiple_spaces"])) : 0;

    $serviceInfo = getServiceSettings($serviceID);
    ########################################################################################################################################################
    //edit attendees processing.
    $sent = false;
    if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes" && !empty($id)) {
        if (!empty($name)) {
            if ($demo === false) {

                $sql = "UPDATE bs_services SET name='" . $name . "' WHERE id='" . $id . "'";
                $result = mysql_query($sql) or die("oopsy, error occured when tryin to update service.");

                $sql = "UPDATE bs_service_settings SET 
					spot_price=" . $spot_price . ",
					spot_invoice=" . $spot_invoice . ",
					allow_times='" . $allow_times . "',
					startDay='" . $startDay . "',
					allow_times_min='" . $allow_times_min . "',
                    `payment_method`='" . $payment_method . "',
					`interval`='" . $interval . "',
					`spaces_available`='" . $spaces_available . "',
					`show_spaces_left`='" . $show_spaces_left . "',
					`show_event_titles`='" . $show_event_titles . "',
					`show_event_image`='" . $show_event_image . "',
					`show_multiple_spaces` ='" . $show_multiple_spaces . "'
					WHERE serviceId='{$id}'";
                $result = mysql_query($sql) or die("oopsy, error occured when tryin to save settings." . mysql_error());


                $sql = "DELETE FROM bs_schedule WHERE idService='{$id}'";
                $res = mysql_query($sql);

                for ($i = 0; $i < 7; $i++) {
                    $week_from_h = (!empty($_REQUEST["week_from_h_" . $i]) && is_array($_REQUEST["week_from_h_" . $i])) ? $_REQUEST["week_from_h_" . $i] : '';
                    $week_from_m = (!empty($_REQUEST["week_from_m_" . $i]) && is_array($_REQUEST["week_from_m_" . $i])) ? $_REQUEST["week_from_m_" . $i] : '';
                    $week_to_h = (!empty($_REQUEST["week_to_h_" . $i]) && is_array($_REQUEST["week_to_h_" . $i])) ? $_REQUEST["week_to_h_" . $i] : '';
                    $week_to_m = (!empty($_REQUEST["week_to_m_" . $i]) && is_array($_REQUEST["week_to_m_" . $i])) ? $_REQUEST["week_to_m_" . $i] : '';
                    for ($j = 0; $j < count($week_from_h); $j++) {
                        if ($week_from_h[$j] != "--" && $week_from_m[$j] != "--" && $week_to_h[$j] != "--" && $week_to_m[$j] != "--") {

                            $startTime = $week_from_h[$j] + $week_from_m[$j];
                            $endTime = $week_to_h[$j] + $week_to_m[$j];

                            $sql = "INSERT INTO bs_schedule (`idService`,`week_num`,`startTime`,`endTime`)
										VALUES ('{$id}','{$i}','{$startTime}','{$endTime}')";
                            $res = mysql_query($sql);
                            
                        }
                    }
                }

                
                addMessage(MSG_SRVUPD,"success");
            } else {
                
                addMessage(MSG_DEMO1,"warning");
            }
        } else {
            
            addMessage(ALLFIELDSREQ);
        }
    }
    ########################################################################################################################################################
    ########################################################################################################################################################
    //"edit page" action processing.
    if (!empty($_REQUEST["edit_page"]) && $_REQUEST["edit_page"] == "yes" && empty($id)) {
        if (!empty($name) && !empty($allow_times)&& !empty($allow_times_min)&& !empty($interval)) {
            if ($demo === false) {

                
                if (empty($id)) {
                    $sql = "INSERT INTO bs_services (name,date_created) VALUES ('" . $name . "',NOW())";
                    $result = mysql_query($sql) or die("oopsy, error occured when tryin to create new service.");
                    $id = mysql_insert_id();

                    $query = "INSERT INTO `bs_service_settings` (`serviceId`, `allow_times`, `allow_times_min`, `interval`,`startDay`,`spot_price`,`spot_invoice`, `spaces_available`,`show_spaces_left`,`show_event_titles`,`show_event_image`,`show_multiple_spaces`) VALUES 
					('{$id}', {$allow_times},{$allow_times_min}, {$interval}, '{$startDay}','{$spot_price}','{$spot_invoice}', '{$spaces_available}',{$show_spaces_left},{$show_event_titles},{$show_event_image},'{$show_multiple_spaces}')";
                    $result = mysql_query($query) or die("oopsy, error occured when tryin to create new service settings.<br>");



                    for ($i = 0; $i < 7; $i++) {
                        $week_from_h = (!empty($_REQUEST["week_from_h_" . $i]) && is_array($_REQUEST["week_from_h_" . $i])) ? $_REQUEST["week_from_h_" . $i] : '';
                        $week_from_m = (!empty($_REQUEST["week_from_m_" . $i]) && is_array($_REQUEST["week_from_m_" . $i])) ? $_REQUEST["week_from_m_" . $i] : '';
                        $week_to_h = (!empty($_REQUEST["week_to_h_" . $i]) && is_array($_REQUEST["week_to_h_" . $i])) ? $_REQUEST["week_to_h_" . $i] : '';
                        $week_to_m = (!empty($_REQUEST["week_to_m_" . $i]) && is_array($_REQUEST["week_to_m_" . $i])) ? $_REQUEST["week_to_m_" . $i] : '';
                        for ($j = 0; $j < count($week_from_h); $j++) {
                            if ($week_from_h[$j] != "--" && $week_from_m[$j] != "--" && $week_to_h[$j] != "--" && $week_to_m[$j] != "--") {

                                $startTime = $week_from_h[$j] + $week_from_m[$j];
                                $endTime = $week_to_h[$j] + $week_to_m[$j];

                                $sql = "INSERT INTO bs_schedule (`idService`,`week_num`,`startTime`,`endTime`)
										VALUES ('{$id}','{$i}','{$startTime}','{$endTime}')";
                                $res = mysql_query($sql);
                                
                            }
                        }
                    }
                    $id = $name = $interval = $allow_times = $allow_times_min = '';
                    $x0_from = $x1_from = $x2_from = $x3_from = $x4_from = $x5_from = $x6_from = $x0_to = $x1_to = $x2_to = $x3_to = $x4_to = $x5_to = $x6_to = '';
                    $spot_price = $spot_invoice = 0;
                    
                     addMessage(MSG_SRVSAVE,"success");
                    
                }
            } else {
                
                 addMessage(MSG_DEMO1,"warning");
            }
        } else {
            
             addMessage(ALLFIELDSREQ);
        }
    }
    ########################################################################################################################################################
   
   
    ###################################################################################################################################################

    if (!empty($id)) {
        //select service info and show it for editor.
        $sSQL = "SELECT id,name FROM bs_services WHERE id='" . $id . "'";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        if ($row = mysql_fetch_assoc($result)) {
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        }
        mysql_free_result($result);

        //select service settings and show it for editor.
        $sSQL = "SELECT  allow_times, allow_times_min, payment_method, `interval`,spot_price, startDay,spaces_available, show_spaces_left, show_event_titles, show_event_image,show_multiple_spaces,spot_invoice,use_popup FROM bs_service_settings WHERE serviceId='" . $id . "'";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        if ($row = mysql_fetch_assoc($result)) {
            foreach ($row as $key => $value) {
                $$key = $value;
            }
        }
        mysql_free_result($result);

        $week = array();
        $sql = "select * from bs_schedule 
			Where idService={$id} ORDER BY startTime ASC"; //print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $m_from = $row['startTime'] % 60;
            $h_from = ($row['startTime'] - $m_from);
            $m_to = $row['endTime'] % 60;
            $h_to = $row['endTime'] - $m_to;
            $week[$row['week_num']][] = array("startHH" => $h_from, "startMM" => $m_from, "endHH" => $h_to, "endMM" => $m_to);
        }
    }
    ?>

    <?php include "includes/admin_header.php"; ?>

    <script type="text/javascript">
        $(function() {
            $("#eventDate").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd"
            });
            //$('#reserveDateFrom').datepicker('option', {dateFormat: "yy-mm-dd"});

    	
            $('#show_multiple_spaces').bind('change',function(){
    	
                if($(this).is(':checked')){
                    $('#spei').show();
                }else{
                    $('#spei').hide();
                }
            })
        });
        function noAlpha(obj){
            reg = /[^0-9.,]/g;
            obj.value =  obj.value.replace(reg,"");
        }	
        function addTime(week_number,el){
            $.get("includes/getTime.php",{week:week_number},function(data){
                $(el).before(data);
            },"html")
        }
    </script>
    <div id="content">
        


     
<?php  getMessages(); ?>
        <div class="content_block">
             <h2><?php echo ADD_EDIT_SERV?> </h2>
            
            
           
            <strong><?php echo $msg; ?></strong>
            <form action="bs-services-add.php" enctype="multipart/form-data" method="post" name="ff1">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="152" height="25" align="right"><?php echo SERV_TTL?></td>
                        <td width="632" height="23">
                            <input type="text" name="name" id="title" value="<?php echo $name ?>" /></td>
                    </tr>
                    <!--#######################################################################################-->
                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="23">&nbsp;</td>
                    </tr>

                    <tr class="settings_title">
                        <td height="25" align="right"><?php echo TIME_BKK_SET?></td>
                        <td height="23">&nbsp;</td>
                    </tr>


                    <tr>
                        <td height="25" align="right"><?php echo BOOK_TIME_INTRV?></td>
                        <td height="23">  <select name="interval">
                                <option value="15" <?php echo $interval == "15" ? "selected" : "" ?>><?php echo MIN15?></option>
                                <option value="30" <?php echo $interval == "30" ? "selected" : "" ?>><?php echo MIN30?></option>
                                <option value="45" <?php echo $interval == "45" ? "selected" : "" ?>><?php echo MIN45?></option>
                                <!-- 45 minutes interval causing wrong display of manual bookings by admin. use at your own discretion -->
                                <option value="60" <?php echo $interval == "60" ? "selected" : "" ?>><?php echo H1?></option>
                                <option value="120" <?php echo $interval == "120" ? "selected" : "" ?>><?php echo H2?></option>
                                <option value="180" <?php echo $interval == "180" ? "selected" : "" ?>><?php echo H3?></option>
                                <option value="240" <?php echo $interval == "240" ? "selected" : "" ?>><?php echo H4?></option>
                                <option value="300" <?php echo $interval == "300" ? "selected" : "" ?>><?php echo H5?></option>

                            <option value="360" <?php echo $interval == "360" ? "selected" : "" ?>><?php echo H6?></option>
                            <option value="420" <?php echo $interval == "420" ? "selected" : "" ?>><?php echo H7?></option>
                            <option value="480" <?php echo $interval == "480" ? "selected" : "" ?>><?php echo H8?></option>
                            <option value="540" <?php echo $interval == "540" ? "selected" : "" ?>><?php echo H9?></option>
                            <option value="600" <?php echo $interval == "600" ? "selected" : "" ?>><?php echo H10?></option>
                            <option value="660" <?php echo $interval == "660" ? "selected" : "" ?>><?php echo H11?></option>
                            <option value="720" <?php echo $interval == "720" ? "selected" : "" ?>><?php echo H12?></option>

                            </select> <img src='images/info.png' border="0"  class="tipTip" title="<?php echo INTERV_MSG?>"/></td>
                    </tr>

                    

                    <tr>
                        <td width="236" height="25" align="right"><?php echo PRICE_SPOT?></td>
                        <td width="548" height="23">
                            <input type="text" name="spot_price" id="spot_price" style=" width:60px;" value="<?php echo $spot_price ?>" />&nbsp;<?php echo getOption('currency') ?>
                            <img src='images/info.png' border="0"  class="tipTip" title="<?php echo TIME_MSG?>"/>
                            
                        </td>
                    </tr>

                    <tr>
                        <td width="236" height="25" align="right"><?php echo ALLOW_MULT_SPACES?></td>
                        <td width="548" height="23">
                            <input type="checkbox" name="show_multiple_spaces" id="show_multiple_spaces" value="1" <?php echo $show_multiple_spaces ? "checked" : "" ?> /> 
                        </td>
                    </tr>

                    <tr id="spei" <?php echo $show_multiple_spaces ? "" : "style='display:none'" ?>>
                        <td width="236" height="25" align="right"><?php echo SPACES_INTRV?></td>
                        <td width="548" height="23">
                            <input type="text" name="spaces_available" id="spaces_available" style=" width:60px;" value="<?php echo $spaces_available ?>" /> 
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
                            <img src='images/info.png' border="0"  class="tipTip" title="<?php echo PAYMENT_MSG?>"/>
                            
                        </td>
                    </tr>

                    <tr>
                        <td width="236" height="25" align="right"><?php echo SHOW_SPAC?></td>
                        <td width="548" height="23">

                            <input type="radio" name="show_spaces_left" id="show_spaces_left" value="0" <?php echo $show_spaces_left == "0" ? "checked" : "" ?> /> <?php echo NO?> <br />
                            <input type="radio" name="show_spaces_left" id="show_spaces_left" value="1" <?php echo $show_spaces_left == "1" ? "checked" : "" ?>/> <?php echo YES?>
                        </td>
                    </tr>

                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="23">&nbsp;</td>
                    </tr>

                    <tr>
                        <td height="25" align="right" valign="top"><?php echo SPOT_MSG?></td>
                        <td height="23"> <input type="radio" name="allow_times_min" id="allow_times_min" value="1" <?php echo $allow_times_min == "1" ? "checked" : "" ?> /> 
                            <?php echo SPT1?><br />
                            <input type="radio" name="allow_times_min" id="allow_times_min" value="2" <?php echo $allow_times_min == "2" ? "checked" : "" ?>/> 
                            <?php echo SPT2?><br />
                            <input type="radio" name="allow_times_min" id="allow_times_min" value="3" <?php echo $allow_times_min == "3" ? "checked" : "" ?>/> 
                            <?php echo SPT3?><br />
                            <input type="radio" name="allow_times_min" id="allow_times_min" value="4" <?php echo $allow_times_min == "4" ? "checked" : "" ?>/> 
                            <?php echo SPT4?><br />
                            <input type="radio" name="allow_times_min" id="allow_times_min" value="99" <?php echo $allow_times_min == "99" ? "checked" : "" ?>/> 
                            <?php echo UNLM_SPOT?>
                        </td>
                    </tr>
                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="23">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="25" align="right" valign="top"><?php echo SPOT_MSG_MAX?></td>
                        <td height="23"> <input type="radio" name="allow_times" id="allow_times" value="1" <?php echo $allow_times == "1" ? "checked" : "" ?> /> 
                            <?php echo SPT1?><br />
                            <input type="radio" name="allow_times" id="allow_times" value="2" <?php echo $allow_times == "2" ? "checked" : "" ?>/> 
                            <?php echo SPT2?><br />
                            <input type="radio" name="allow_times" id="allow_times" value="3" <?php echo $allow_times == "3" ? "checked" : "" ?>/> 
                            <?php echo SPT3?><br />
                            <input type="radio" name="allow_times" id="allow_times" value="4" <?php echo $allow_times == "4" ? "checked" : "" ?>/> 
                            <?php echo SPT4?><br />
                            <input type="radio" name="allow_times" id="allow_times" value="99" <?php echo $allow_times == "99" ? "checked" : "" ?>/> 
                            <?php echo UNLM_SPOT?></td>
                    </tr>
                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="23">&nbsp;</td>
                    </tr>
                    <tr class="settings_title">
                        <td height="25" align="right"><?php echo BOOK_AVAIL?></td>
                        <td height="23"> &nbsp; &nbsp;<?php echo BOOK_MSG_DAY?></td>
                    </tr>

                    
    <?php
    for ($i = 0; $i < 7; $i++) {
        $step = $j = 0;
        $step = 15;
        $items = (isset($week[$i]) && (count($week[$i]) > 0)) ? count($week[$i]) : 1;
        //print $items;
        ?>
                        <tr style="font-size: 14px">
                            <td align=right valign="top" ><?php echo $monthList[$weeks[$i]] ?>:&nbsp;</td>
                            <td class="availability">
        <?php for ($j = 0; $j < $items; $j++) { ?>
                                    <div>
                                        <select class='hh' name="week_from_h_<?php echo $i ?>[]">
                                            <option value="--">- -</option>	
            <?php for ($h = 0; $h <= 23 && $h_to != '--'; $h++) { ?>
                                                <option value="<?php echo $h * 60 ?>" <?php echo (isset($week[$i][$j]['startHH']) && $week[$i][$j]['startHH'] == $h * 60) ? "selected='selected'" : '' ?>><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
            <?php } ?>
                                        </select>:
                                        <select class='mm' name="week_from_m_<?php echo $i ?>[]">
                                            <option value="--">- -</option>
                            <?php for ($m = 0; $m < 60 && $h_to != 'N/A'; $m = $m + $step) { ?>
                                                <option value="<?php echo $m ?>" <?php echo (isset($week[$i][$j]['startMM']) && $week[$i][$j]['startMM'] == $m) ? "selected='selected'" : '' ?>><?php echo str_pad($m, 2, "0", STR_PAD_LEFT) ?></option>
                            <?php } ?>
                                        </select>
                                        &nbsp;to&nbsp;
                                        <select class='hh' name="week_to_h_<?php echo $i ?>[]">
                                            <option value="--">- -</option>
            <?php for ($h = 0; $h <= 24 && $h_to != 'N/A'; $h++) { ?>
                                                <option value="<?php echo $h * 60 ?>" <?php echo (isset($week[$i][$j]['endHH']) && $week[$i][$j]['endHH'] == $h * 60) ? "selected='selected'" : '' ?>><?php echo str_pad($h, 2, "0", STR_PAD_LEFT) ?></option>
                                    <?php } ?>
                                        </select>:
                                        <select class='mm' name="week_to_m_<?php echo $i ?>[]">
                                            <option value="--">- -</option>
                                            <?php for ($m = 0; $m < 60 && $h != 'N/A'; $m = $m + $step) { ?>
                                                <option value="<?php echo $m ?>" <?php echo (isset($week[$i][$j]['endMM']) && $week[$i][$j]['endMM'] == $m) ? "selected='selected'" : '' ?>><?php echo str_pad($m, 2, "0", STR_PAD_LEFT) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                        <?php } ?>
                                <a href="javascript:;" onclick="addTime(<?php echo $i ?>,this)">add</a>
                            </td>
                        </tr>
    <?php } ?>



                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="23">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="25" align="right" valign="top"><?php echo CALND_WEEK_STARTS?></td>
                        <td height="23"><input type="radio" name="startDay" id="startDay" value="0" <?php echo $startDay == "0" ? "checked" : "" ?> /> <?php echo SUN?> <br />
                            <input type="radio" name="startDay" id="startDay" value="1" <?php echo $startDay == "1" ? "checked" : "" ?>/> <?php echo MON?></td>
                    </tr>

                    <tr class="settings_title">
                        <td height="25" align="right"><?php echo EVENT_DISP_SETT?></td>
                        <td height="23">&nbsp;</td>
                    </tr>

                    <tr>
                        <td height="25" align="right" valign="top"><?php echo SHOW_TTL?></td>
                        <td height="23"><input type="radio" name="show_event_titles" id="show_event_titles" value="0" <?php echo $show_event_titles == "0" ? "checked" : "" ?> /> No <br />
                            <input type="radio" name="show_event_titles" id="show_event_titles" value="1" <?php echo $show_event_titles == "1" ? "checked" : "" ?>/> Yes</td>
                    </tr>

                    <tr>
                        <td height="25" align="right" valign="top"><?php echo SHOW_IMG?></td>
                        <td height="23"><input type="radio" name="show_event_image" id="show_event_image" value="0" <?php echo $show_event_image == "0" ? "checked" : "" ?> /> No <br />
                            <input type="radio" name="show_event_image" id="show_event_image" value="1" <?php echo $show_event_image == "1" ? "checked" : "" ?>/> Yes</td>
                    </tr>
                    <!--#############################################################################################################-->

                    <tr>
                        <td height="25" align="right">&nbsp;</td>
                        <td height="32"><br />

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