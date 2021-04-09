<?php
    #definindo as configuracoes dos importes
    include "../includes/dbconnect.php";
	include "../includes/config.php";

    #pegando a acao para tratar o que sera feito
    $acao = $_POST['acao'];

    #abrindo tela de novo registro chave pix 
    if($acao == 'incluir'){
        #redireciona para tela de incluir nova chave pix.
        header("Location: ../bs-incluirchavepix.php");
        exit();
    }

    #tratando a inclusao de uma nova chave do pix
    if($acao == 'salvarInclusao'){
         #gravando os dados da chave do pix na producao
        $q = "INSERT INTO bs_settings_chave_pix_prod(chave, ativa) VALUES ('" . $_POST['chave'] . "', '" . $_POST['ativa'] . "')";
        $res = mysql_query($q) or die("error! PROD-ERRO-INSERT: " . mysql_error());
        $orderID = mysql_insert_id();

        #enviando mensagem de sucesso para o usuario e retornando para a pagina de cadastro
        if ($orderID >= 1) {
            #redirecionando para página de cadastro
            $_SESSION['msgSucess'] = tratarMsgSucesso(SUCESS_PIX_INCLUIR_CHAVE);
            header("Location: ../bs-pixsettings.php");

        }
    }

    #tratando a exclusao da chave do pix
    if(isset($_GET['acao'])){
        if($_GET['acao'] == 'Excluir'){
            $sql = "DELETE FROM bs_settings_chave_pix_prod WHERE id = ". $_GET['id'];
            $result = mysql_query($sql) or die("oopsy, error when tryin to delete bookings");

            #enviando mensagem de sucesso para o usuario
             $_SESSION['msgSucess'] = tratarMsgSucesso(SUCESS_PIX_EXCLUIR_CHAVE);
            header("Location: ../bs-pixsettings.php");
                
        }
    }

?>