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

include "includes/plugins_grid.php";


if ($_SESSION["logged_in"] != true) {
    header("Location: admin.php");
} else {

    bw_do_action("bw_load");
    bw_do_action("bw_admin");

    //dump($BW_actions);
    ######################### DO NOT MODIFY (UNLESS SURE) END ########################

    $email = (!empty($_REQUEST["email"])) ? strip_tags(str_replace("'", "`", $_REQUEST["email"])) : '';
    $pemail = (!empty($_REQUEST["pemail"])) ? strip_tags(str_replace("'", "`", $_REQUEST["pemail"])) : '';
    $pcurrency = (!empty($_REQUEST["pcurrency"])) ? strip_tags(str_replace("'", "`", $_REQUEST["pcurrency"])) : '';
    $currency = (!empty($_REQUEST["currency"])) ? strip_tags(str_replace("'", "`", $_REQUEST["currency"])) : '';
    $currencyPos = (isset($_REQUEST["currencyPos"])) ? strip_tags(str_replace("'", "`", $_REQUEST["currencyPos"])) : 'a';

    $tax = (!empty($_REQUEST["tax"])) ? strip_tags(str_replace("'", "`", $_REQUEST["tax"])) : '';
    $enable_tax = (isset($_REQUEST["enable_tax"])) ? strip_tags(str_replace("'", "`", $_REQUEST["enable_tax"])) : '0';

    $new_pass = (!empty($_REQUEST["new_pass"])) ? strip_tags(str_replace("'", "`", $_REQUEST["new_pass"])) : '';
    $new_pass2 = (!empty($_REQUEST["new_pass2"])) ? strip_tags(str_replace("'", "`", $_REQUEST["new_pass2"])) : '';

    $use_popup = (isset($_REQUEST["use_popup"])) ? strip_tags(str_replace("'", "`", $_REQUEST["use_popup"])) : '0';
    $time_mode = (isset($_REQUEST["time_mode"])) ? strip_tags(str_replace("'", "`", $_REQUEST["time_mode"])) : 0;
    $date_mode = (isset($_REQUEST["date_mode"])) ? strip_tags(str_replace("'", "`", $_REQUEST["date_mode"])) : '';

    $lang = (!empty($_REQUEST["lang"])) ? strip_tags(str_replace("'", "`", $_REQUEST["lang"])) : '';

    $langList = getLangList();





    if (!empty($_REQUEST["edit_settings"]) && $_REQUEST["edit_settings"] == "yes") {


        updateOption('email', $email);
        updateOption('pemail', $pemail);
        updateOption('pcurrency', $pcurrency);
        updateOption('currency', htmlspecialchars($currency));
        updateOption('tax', $tax);
        updateOption('enable_tax', $enable_tax);
        updateOption('time_mode', $time_mode);
        updateOption('use_popup', $use_popup);
        updateOption('lang', $lang);
        updateOption('date_mode', $date_mode);
        updateOption('currency_position', $currencyPos);
        addMessage(MSG_SETSAVED, "success");


        if (!empty($new_pass) && !empty($new_pass2)) {
            if (md5($new_pass) == md5($new_pass2)) {

                if ($demo) {
                    $msg .= "<span style='color:#aa0000'>" . DEMO_PASS_MSG . "</span>";
                } else {

                    updateOption('password', md5($new_pass));
                    addMessage(MSG_ADMPSCHG, "success");
                    //$msg.="<span style='color:#00aa00'> Administrator password was changed!</span>";
                }
            } else {
                addMessage(MSG_PSDNTMTCH, "warning");
                $msg .= "<span style='color:#00aa00'>" . PASS_NOMATCH . "</span>";
            }
        }
    }

    //print $months;
    $email = getOption('email');
    $pemail = getOption('pemail');
    $pcurrency = getOption('pcurrency');
    $currency = getOption('currency');
    $tax = getOption('tax');
    $enable_tax = getOption('enable_tax');
    $time_mode = getOption('time_mode');
    $use_popup = getOption('use_popup');
    $lang = getOption('lang');
    $date_mode = getOption('date_mode');
    $currencyPos = getOption('currency_position');
?>
    <?php include "includes/admin_header.php"; ?>

    <script type="text/javascript">
        $(function() {

            $('#enable_tax').bind('change', function() {

                if ($(this).is(':checked')) {
                    $('#tax').show();
                } else {
                    $('#tax').hide();
                }
            })
        });

        function noAlpha(obj) {
            reg = /[^0-9.,]/g;
            obj.value = obj.value.replace(reg, "");
        }
    </script>
    <div id="content">


        <?php getMessages(); ?>
        <div class="content_block">
            <form action="bs-settings.php" enctype="multipart/form-data" method="post" name="ff1">
                <div class="s_center">
                    <h2 align="center"><?php echo LB_COF_PIX_TITULO ?></h2>

                    
                </div>
            </form>
        </div>

        <?php include "includes/admin_footer.php"; ?>
    <?php } ?>