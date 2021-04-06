<?php
    #tratando a session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    #definindo as configuracoes dos importes
    include "../includes/dbconnect.php";
	include "../includes/config.php";
    
     #pegando os dados para serem gravados na base de dados
    if (isset($_POST['hmg_client_id'])) {        
        #gravando os dados de homologacao
        $q = "INSERT INTO bs_settings_mercadopago_hmg (hmg_client_id, hmg_client_secret, hmg_public_key, hmg_access_token) VALUES ('" . $_POST['hmg_client_id'] . "', '" . $_POST['hmg_client_secret'] . "', '" . $_POST['hmg_public_key'] . "', '" . $_POST['hmg_access_token'] . "')";
        $res = mysql_query($q) or die("error! HMG-ERRO-INSERT: " . mysql_error());
        $orderID = mysql_insert_id();

        #enviando mensagem de sucesso para o usuario e retornando para a pagina de cadastro
        if ($orderID >= 1) {
            #redirecionando para página de cadastro
            $_SESSION['msgSucess'] = tratarMsgSucesso(SUCESS_MERCADO_PAGO_HMG);
            header("Location: ../bs-mercadopagosettings.php");

        }
    } else {
        #gravando os dados de producao
        $q = "INSERT INTO bs_settings_mercadopago_prod (prod_client_id, prod_client_secret, prod_public_key, prod_access_token) VALUES ('" . $_POST['prod_client_id'] . "', '" . $_POST['prod_client_secret'] . "', '" . $_POST['prod_public_key'] . "', '" . $_POST['prod_access_token'] . "')";
        $res = mysql_query($q) or die("error! PROD-ERRO-INSERT: " . mysql_error());
        $orderID = mysql_insert_id();

        #enviando mensagem de sucesso para o usuario e retornando para a pagina de cadastro
        if ($orderID >= 1) {
            #redirecionando para página de cadastro
            $_SESSION['msgSucess'] = tratarMsgSucesso(SUCESS_MERCADO_PAGO_PROD);
            header("Location: ../bs-mercadopagosettings.php");
        }
    }   

?>