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

     ########################################################################################################################################################
    //"delete selected attendees" action processing.
    if (!empty($_REQUEST["del"]) && $_REQUEST["del"] == "yes") {
        $todelID = (!empty($_REQUEST["bsid"])) ? strip_tags(str_replace("'", "`", $_REQUEST["bsid"])) : '';
        if (!empty($todelID)) {
            if ($demo === false) {
                $sql = "SELECT * FROM bs_events WHERE serviceID='{$todelID}'";
                $res = mysql_query($sql);
                while ($row = mysql_fetch_assoc($res)) {
                    $sql = "DELETE FROM bs_reservations_items WHERE eventID='{$row['id']}'";
                    $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 1");
                    $sql = "DELETE FROM bs_reservations WHERE eventID='{$row['id']}'";
                    $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 2");
                }
                $sql = "DELETE FROM bs_events WHERE serviceID='{$todelID}'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 3");

                $sql = "DELETE FROM bs_reservations WHERE serviceID='{$todelID}'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete reservations 2");
                ################################################################################
                $sql = "SELECT * FROM bs_reserved_time WHERE serviceID='{$todelID}'";
                $res = mysql_query($sql);
                while ($row = mysql_fetch_assoc($res)) {
                    $sql = "DELETE FROM bs_reserved_time_items WHERE reservedID='{$row['id']}'";
                    $result = mysql_query($sql) or die("oopsy, error when tryin to resrved times");
                }
                $sql = "DELETE FROM bs_reserved_time WHERE serviceID='{$todelID}'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 3");
                ##################################################################################

                $sql = "DELETE FROM bs_services WHERE id='" . $todelID . "'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 1");

                $sql = "DELETE FROM bs_service_settings WHERE serviceId='" . $todelID . "'";
                $result = mysql_query($sql) or die("oopsy, error when tryin to delete events 1");

                
                addMessage(MSG_SRVDEL,"success");
                $id = '';
            } else {
               
                addMessage(MSG_DEMO1,"warning");
            }
        }
    }

 $files_table = "";
    ###################################################################################################################################################
    //PAGES TABLE  GENERATION TO SHOW IN HTML BELOW
    $sql = "SELECT * FROM bs_services ORDER BY date_created DESC";
    $result = mysql_query($sql) or die("error getting attendees from db");
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {

            $note = MSG_NOTE;
            $editable = "<a href=\"bs-services-add.php?id=" . $rr["id"] . "\"><img src=\"images/pencil_16.png\" alt=\"Edit this service\" border=\"0\"/></a>";

            $editable.=($rr["id"] != 1) ?"&nbsp;&nbsp;<a href='javascript:void(0)' onclick='if(confirm(\"Delete selected service?" . $note . "\")){(document.location.href=\"bs-services.php?id=" . $id . "&amp;del=yes&amp;bsid=" . $rr["id"] . "\")}'><img src='images/delete_16.png' border=\"0\"></a>":"";
            $bgClass = ($bgClass == "even" ? "odd" : "even");

            

            $files_table .="<tr class=\"" . $bgClass . "\">";
            $files_table .="";
           
            
             $files_table .= "<td align='center'>" . $rr["id"] . "</td>";
            $files_table .= "<td>" . $rr["name"] . "</td>";
            $files_table .= "<td>" . $rr["date_created"] . "</td>";
            $files_table .= "<td valign='center'>" . $editable . "</td></tr>";
        } // end of all files from db query (end of while loop)
    } else {
        //0 files found in database. ( end of IF mysql_num_rows > 0 )
        $files_table .="<tr><td colspan=\"4\">".ZEO_FOUND_BS."</td></tr>";
    }
    
    ########################################################################################################################################################
    //prepare attendees page.
?>
<?php include "includes/admin_header.php"; ?>
   <div id="content">
        


     
<?php  getMessages(); ?>
        <div class="content_block">
            <h2><?php echo SERVICES?></h2>
            <a href="bs-services-add.php" class="button">Novo Serviço</a>
            <form enctype="multipart/form-data" action="bs-services.php" method="post" name="ff2">
                <input type="hidden" value="yes" name="attendees_edit" />
                <input value="<?php echo $id; ?>" name="id" type="hidden" />
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="topRow">
                        
                         <td width="3%" height="30" align="center">ID</td>
                        <td width="18%" align="left"><strong><?php echo BOOKING_FRM_NAME?></strong></td>
                        <td width="19%" align="left"><strong><?php echo DATE_CRTD?></strong></td>
                        <td width="4%" height="30" align="center">&nbsp;</td>
                    </tr>
                        <?php echo $files_table; ?>
                </table>
                
            </form>
           
        </div>





    <?php include "includes/admin_footer.php"; ?>
<?php } ?>