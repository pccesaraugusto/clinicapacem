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
#tratando a session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once("includes/config.php"); //Load the configurations

include "includes/plugins_grid.php";


if ($_SESSION["logged_in"] != true) {
    header("Location: admin.php");
} else {

?>
    <?php include "includes/admin_header.php"; ?>

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

    <script type="text/javascript">
        //-- funcao para verificar se os campos de homologacao foram preenchidos --//
        function valida_form_homologacao() {
            if (document.getElementById("hmg_client_id").value.length < 1) {
                alert('Por favor, preencha o campo Client Id de Homologação.');
                document.getElementById("hmg_client_id").focus();
                return false

            } else if (document.getElementById("hmg_client_secret").value.length < 1) {
                alert('Por favor, preencha o campo Client Secret de Homologação.');
                document.getElementById("hmg_client_secret").focus();
                return false

            } else if (document.getElementById("hmg_public_key").value.length < 1) {
                alert('Por favor, preencha o campo Public Key de Homologação.');
                document.getElementById("hmg_public_key").focus();
                return false

            } else if (document.getElementById("hmg_access_token").value.length < 1) {
                alert('Por favor, preencha o campo Access Token de Homologação.');
                document.getElementById("hmg_access_token").focus();
                return false

            }
        }

        //-- funcao para verificar se os campos de producao foram preenchidos --//
        function valida_form_producao() {
            if (document.getElementById("prod_client_id").value.length < 1) {
                alert('Por favor, preencha o campo Client Id de Produção.');
                document.getElementById("prod_client_id").focus();
                return false

            } else if (document.getElementById("prod_client_secret").value.length < 1) {
                alert('Por favor, preencha o campo Client Secret de Produção.');
                document.getElementById("prod_client_secret").focus();
                return false

            } else if (document.getElementById("prod_public_key").value.length < 1) {
                alert('Por favor, preencha o campo Public Key de Produção.');
                document.getElementById("prod_public_key").focus();
                return false

            } else if (document.getElementById("prod_access_token").value.length < 1) {
                alert('Por favor, preencha o campo Access Token de Produção.');
                document.getElementById("prod_access_token").focus();
                return false

            }
        }
    </script>

    <div id="content">

        <!-- Exibe msg para o usuário -->
        <?php
        if (isset($_SESSION['msgSucess'])) {
            if (strlen($_SESSION['msgSucess']) >= 5) {
                echo $_SESSION['msgSucess'];

                #limpa msg de sucesso
                unset($_SESSION['msgSucess']);
            }
        }
        ?>

        <div class="content_block">
            <form action="./controller/ManterMercadoPagoController.class.php" onsubmit="return valida_form_homologacao(this)" method="post" name="ff1">
                <div class="s_center">
                    <hr />
                    <h2 align="center" style="background-color: #ADD8E6;"><?php echo LB_COF_MERCADO_PAGO_TITULO ?></h2>
                    <hr />
                    <h2 align="center" style="font-size: 16px;"><?php echo LB_COF_MERCADO_PAGO_HMG ?></h2>

                    <!-- definindo os campos da area de homologacao - configuracoes -->
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo HMG_CRIENT_ID ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="hmg_client_id" id="hmg_client_id" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo HMG_CRIENT_SECRET ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="hmg_client_secret" id="hmg_client_secret" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo HMG_PUBLIC_KEY ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="hmg_public_key" id="hmg_public_key" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo HMG_ACCESS_TOKEN ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="hmg_access_token" id="hmg_access_token" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <p align="center" style="color: red;">(*) - Campos de preenchimento obrigatório.</p>

                    <table width="80%" border="0" align="center">
                        <thead align="center">
                            <tr>
                                <td width="80%">
                                    <input type="submit" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;" value="<?php echo BTN_SALVAR_DADOS_HMG ?>"></center>

                                    <a href="#" id="myBtnHmg" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;"><?php echo BTN_LOCACLIZAR_DADOS_HMG; ?></a>
                                </td>
                            </tr>
                        </thead>
                    </table><br /><br />
                </div>
            </form>

            <form action="./controller/ManterMercadoPagoController.class.php" onsubmit="return valida_form_producao(this)" method="post" name="ff2">
                <div class="s_center">
                    <hr />
                    <h2 align="center" style="font-size: 16px;"><?php echo LB_COF_MERCADO_PAGO_PROD ?></h2>

                    <!-- definindo os campos da area de homologacao - configuracoes -->
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo PROD_CRIENT_ID ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="prod_client_id" id="prod_client_id" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo PROD_CRIENT_SECRET ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="prod_client_secret" id="prod_client_secret" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo PROD_PUBLIC_KEY ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="prod_public_key" id="prod_public_key" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <br />
                    <table width="60%" border="0" align="center">
                        <thead align="left">
                            <tr style="background-color: #ADD8E6;">
                                <th width="20%">
                                    &nbsp;&nbsp;<?php echo PROD_ACCESS_TOKEN ?>
                                </th>
                                <td width="40%"><br />
                                    &nbsp;<input type="text" name="prod_access_token" id="prod_access_token" style="width:400px;" value="" />
                                    <strong style="color: red;">&nbsp;*</strong>
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <p align="center" style="color: red;">(*) - Campos de preenchimento obrigatório.</p>

                    <table width="80%" border="0" align="center">
                        <thead align="center">
                            <tr>
                                <td width="80%">
                                    <input type="submit" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;" value="<?php echo BTN_SALVAR_DADOS_PROD ?>"></center>

                                    <a href="#" id="myBtnProd" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;"><?php echo BTN_LOCACLIZAR_DADOS_HMG; ?></a>
                                </td>
                            </tr>
                        </thead>
                    </table><br /><br />

                </div>

            </form>
        </div>

        <?php include "includes/admin_footer.php"; ?>
        <?php }

    #localizar os dados ce hmg para exibir no modal
    $sql = "SELECT a.id, a.hmg_access_token, a.hmg_client_id, a.hmg_client_secret, a.hmg_public_key 
        FROM bs_settings_mercadopago_hmg as a 
        WHERE id = (SELECT max(id) FROM bs_settings_mercadopago_hmg)";
    $result = mysql_query($sql) or die("err: " . mysql_error() . $sql);

    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {
        ?>

            <!-- The Modal - SandBox - Homologacao-->
            <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close" style="color: red;">&times;</span>
                    <h2 style="background-color: green; color: #FFFFFF;">Chaves Cadastradas na Homologação - SANDBOX</h2>
                    <table>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Client Id&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['hmg_client_id']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Client Secret&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['hmg_client_secret']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Public Key&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['hmg_public_key']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Access Token: </strong>&nbsp;<?php echo $rr['hmg_access_token']; ?></td>
                        </tr>
                    </table>
                </div>

            </div>

        <?php

        }
    }

    #producao sql dados cadastrados
    $sql = "SELECT a.id as id, a.prod_access_token, a.prod_client_id, a.prod_client_secret, a.prod_public_key 
           FROM bs_settings_mercadopago_prod as a 
           WHERE id = (SELECT max(id) FROM bs_settings_mercadopago_prod)";
    $result = mysql_query($sql) or die("err: " . mysql_error() . $sql);
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {

        ?>

            <!-- The Modal - SandBox - Producao-->
            <div id="myModalProd" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <span class="fecharProd" style="color: red;">&times;</span>
                    <h2 style="background-color: green; color: #FFFFFF;">Chaves Cadastradas na Produção</h2>
                    <table>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Client Id&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['prod_client_id']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Client Secret&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['prod_client_secret']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Public Key&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>&nbsp;<?php echo $rr['prod_public_key']; ?></td>
                        </tr>
                        <tr>
                            <td><strong style="background-color: #ADD8E6;">Access Token: </strong>&nbsp;<?php echo $rr['prod_access_token']; ?></td>
                        </tr>
                    </table>
                </div>

            </div>

    <?php

        }
    }
    ?>


    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        var modalProd = document.getElementById("myModalProd");

        // Get the button that opens the modal
        var btnHmg = document.getElementById("myBtnHmg");
        var btnProd = document.getElementById("myBtnProd");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var spanProd = document.getElementsByClassName("fecharProd")[0];

        // When the user clicks the button, open the modal 
        btnHmg.onclick = function() {
            modal.style.display = "block";
        }

        btnProd.onclick = function() {
            modalProd.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        spanProd.onclick = function() {
            modalProd.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        window.onclick = function(event) {
            if (event.target == modalProd) {
                modalProd.style.display = "none";
            }
        }
    </script>