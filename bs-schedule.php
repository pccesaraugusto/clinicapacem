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
} else {

    bw_do_action("bw_load");
    bw_do_action("bw_admin");

    ######################### DO NOT MODIFY (UNLESS SURE) END ########################
    //show page only if admin access level
    //request all neccessary variables for user update action.
    $selectedDay = (!empty($_REQUEST["selectedDay"])) ? strip_tags(str_replace("'", "`", $_REQUEST["selectedDay"])) : date("Y-m-d");
    $serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : 1;


    if (!empty($selectedDay)) {
        $availability = getScheduleTable($selectedDay, $serviceID);
    } else {
        $availability = "Please select a day above";
    }
    ?>

    <?php include "includes/admin_header.php"; ?>

    <script type="text/javascript">
        $(function() {

            $("#reserveDateFrom").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd"
            });
            //$('#reserveDateTo').datepicker('option', {dateFormat: "yy-mm-dd"})
    	
    	
        });
    	
    </script>

    <div id="content">
        


     

        <div class="content_block">
            <h2><?php echo SCHEDL ?></h2>
            <p>Ver cronograma para o serviço na data selecionada</p>
            <strong><?php echo $msg; ?></strong>
            <div class="bar">

	<div class="servicesList">
		<form name="ff1" action="bs-schedule.php" id="ff1" method="post">
                    <div style="float: left">
                    <label>Selecione o Serviço:</label>
			<select name="serviceID">
				
				<?php 
                                $sql="SELECT * FROM bs_services";
				$res=mysql_query($sql);
                                while($row=mysql_fetch_assoc($res)){?>
					<option value="<?php echo $row['id']?>" <?php echo ($serviceID==$row['id'])?"selected":""?>><?php echo $row['name']?></option>
				<?php } ?>
			</select>
                    </div>
                        <div style="float: left;margin-left: 20px">
                             <label>Selecione a Data:</label>
                            <input type="text" name="selectedDay" id="reserveDateFrom" value="<?php echo $selectedDay ?>" />
                        </div>
                        <div style="clear:both"></div>
                    <div class="buttonCont">
                        <input type="submit" value="Ver Disponibilidade">
                    </div>
		</form>
	</div>
  
	<div style="clear:both"></div>
	
  </div>
            <h3><?php echo SCHEDL?> para <?php echo getService($serviceID,'name')?> em <?php echo $selectedDay?></h3>
                <table width="784" border="0" cellspacing="0" cellpadding="0">
                    
                            <?php if (!empty($selectedDay)) { ?>
                        <tr>
                            <td height="25" align="right">&nbsp;</td>
                            <td height="25">&nbsp;</td>
                        </tr>

                        <tr>
                            <td height="25" colspan="2" align="left">

                            <?php echo $availability ?>

                            </td>
                        </tr>


                        <tr>
                            <td height="25" align="right">&nbsp;</td>
                            <td height="25">&nbsp;</td>
                        </tr>
                         <?php } ?>

                </table>
            
        </div>

    <?php include "includes/admin_footer.php"; ?>
<?php } ?>