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

    <?php
    #busando os dados da chave do pix na base de dados
    $sql = "SELECT a.id as id, a.chave, a.ativa
           FROM bs_settings_chave_pix_prod as a 
           WHERE a.id = (SELECT max(id) FROM bs_settings_chave_pix_prod)
           AND a.ativa = 'Sim'";
    $result = mysql_query($sql) or die("err: " . mysql_error() . $sql);
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .link_pc {
            background-color: #1c87c9;
            border: none;
            color: white;
            padding: 8px 50px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 80px;
            border: 1px solid #888;
            width: 70%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        /* The Close Button */
        .fecharProd {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .fecharProd:hover,
        .fecharProd:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div id="content">

        <!-- Exibe msg para o usu??rio -->
        <?php
        if (isset($_SESSION['msgSucess'])) {
            if (strlen($_SESSION['msgSucess']) >= 5) {
                echo $_SESSION['msgSucess'];

                #limpa msg de sucesso
                unset($_SESSION['msgSucess']);
            }
        }
        ?>

        <div class="content_block" align="center">
            <form action="controller/ManterPagamentoPIX.php" enctype="multipart/form-data" method="post" name="ff1">
                <input type="hidden" name="acao" value="incluir">
                <div class="s_center">
                    <h2 align="center"><?php echo LB_COF_PIX_TITULO ?></h2><br>
                    <hr>
                    <table class="table" width="70%">
                        <thead>
                            <tr style="background-color: #4682B4; color: #FFFFFF;">
                                <th scope="col" width="5%"><?= LB_ID_TAB_PIX ?></th>
                                <th scope="col" width="40%"><?= LB_CHAVE_TB_PIX ?></th>
                                <th scope="col" width="10%"><?= LB_ATIVA_TB_PIX ?></th>
                                <th scope="col" width="15%"><?= LB_ACOES_TB_PIX ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysql_num_rows($result) > 0) {
                                while ($rr = mysql_fetch_assoc($result)) {
                            ?>
                                    <tr align="center">
                                        <td><?php echo $rr['id']; ?></th>
                                        <td><?php echo $rr['chave']; ?></td>
                                        <td><?php echo $rr['ativa']; ?></td>
                                        <td>
                                            <a href="<?php echo 'controller/ManterPagamentoPIX.php?acao=Excluir&id='. $rr['id'];  ?>" style="font-size:14px; color:red;"><b><i class="fa fa-trash-o">&nbsp;Excluir</i></b></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                #msg de tabela vazia para o usuario 
                                ?>
                                <table class="table" width="70%">
                                    <thead>
                                        <tr style="background-color: #FFF0F5; color: red;"><br><br>
                                            <td><?= NAO_EXITE_REGISTRO_CADASTRADO ?></td>
                                        </tr>
                                    </thead>
                                </table>
                            <?php } ?>
                        </tbody>
                    </table><br><br>
                    <table width="70%" border="0" align="center">
                        <thead align="center">
                            <tr>
                                <td width="80%">
                                    <input type="submit" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;" value="<?php echo BTN_INCLUIR_CHAVE_PIX ?>"></center>
                                </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </form>
        </div>

        <?php include "includes/admin_footer.php"; ?>
    <?php } ?>