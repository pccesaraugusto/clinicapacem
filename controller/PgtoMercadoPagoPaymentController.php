<?php
#pegando os imports
include "../includes/dbconnect.php";
include "../includes/config.php";
require_once "../lib/vendor/autoload.php";

#pegando o valor a ser pago
$valor = $_GET['dados'];
$nome  = $_GET['nome'];
$email = $_GET['email'];

$token = $_REQUEST["token"];
$payment_method_id = $_REQUEST["payment_method_id"];
$installments = $_REQUEST["installments"];
$issuer_id = $_REQUEST["issuer_id"];

$datapublickey = "";

#pegando o ultimo dados da configuracao do token do mercado pago de hmg
/*$sql = "SELECT a.id, a.hmg_access_token, a.hmg_client_id, a.hmg_client_secret, a.hmg_public_key 
        FROM bs_settings_mercadopago_hmg as a 
        WHERE id = (SELECT max(id) FROM bs_settings_mercadopago_hmg)";
$result = mysql_query($sql) or die("err: " . mysql_error() . $sql);

if (mysql_num_rows($result) > 0) {
    while ($rr = mysql_fetch_assoc($result)) {
        $datapublickey = $rr['hmg_access_token'];
    }
} else {
    echo 'Resultado da query = 0';
}*/

#pegando o ultimo dados da configuracao do token do mercado pago de producao
$sql = "SELECT a.id as id, a.prod_access_token, a.prod_client_id, a.prod_client_secret, a.prod_public_key 
           FROM bs_settings_mercadopago_prod as a 
           WHERE id = (SELECT max(id) FROM bs_settings_mercadopago_prod)";
$result = mysql_query($sql) or die("err: " . mysql_error() . $sql);
    if(mysql_num_rows($result)>0){
        while($rr=mysql_fetch_assoc($result)){
            $datapublickey = $rr['prod_access_token'];
        }
    }else{
        echo 'Resultado da query = 0';
    }   


#configuracao do sandbox ou producao:
MercadoPago\SDK::setAccessToken($datapublickey);

#configuracao de producao:
//MercadoPago\SDK::setAccessToken("ACCESSE TOKEN DE PRODUCAO");

$payment = new MercadoPago\Payment();
$payment->transaction_amount = floatval($valor);
$payment->token = $token;
$payment->description = " para ". $nome;
$payment->installments = $installments;
$payment->payment_method_id = $payment_method_id;
$payment->issuer_id = $issuer_id;
$payment->payer = array(
    "email" => $email
);

// Armazena e envia o pagamento
$payment->save();

// Imprime o status do pagamento
//echo $payment->status;
?>

<!-- Tratando a exibi????o da mensagem de confirma????o de pagamento -->
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirma????o do pagamento mercado pago</title>
</head>

<body>
    <form action="https://clinicapacem.com.br/" method="POST">
        <hr />
        <h2 align="center" style="background-color: #87CEEB; color: #FFFFFF;">Informa????es da Reserva / Mercado Pago</h2>
        <h2 align="center">Informa????es do Pagamento Realizado:</h2>
        <table width="70%" border="0" class='summery' align="center">
            <?php
            if ($payment->status == 'approved') {
            ?>
                <tr align="justify">
                    <td align="justify" style="background-color: #20B2AA; color: #FFFFFF;">
                        <?php echo '<b>Aten????o! O seu pagamento est?? em analise, ?? confirma????o do pagamento ser?? enviada por e-mail, ap??s libera????o. </b>'; ?>
                    </td>
                </tr>
            <?php
            } else {
            ?>
                <tr align="justify">
                    <td align="justify" style="background-color: #A52A2A; color: #FFFFFF;"><b>Aten????o! Houve um problema durante o pagamento. Favor informar ao respons??vel pelo site.</b></td>
                </tr>
            <?php } ?>
        </table>
        <div>
            <br>
            <table width="70%" border="0" class='summery' align="center">
                <tr align="left">
                    <td align="center">
                        <button type="submit" style="background-color: #39b54a; color: #FFFFFF;">Fechar</button>
                    </td>
                </tr>
            </table>
        </div>

        <hr />
    </form>
</body>

</html>