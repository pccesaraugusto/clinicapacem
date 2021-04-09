<?php
#pegando os imports
include "includes/dbconnect.php";
include "includes/config.php";

bw_do_action("bw_load");

#pegando os dados do agendamento para exibir como detalhes
$orderId = $_GET['orderID'];
$nome    = $_GET['nome'];
$email   = $_GET['email'];

$datapublickey = "";

#pegando o ultimo dados da configuracao do token do mercado pago de hmg
/*$sql = "SELECT a.id, a.hmg_access_token, a.hmg_client_id, a.hmg_client_secret, a.hmg_public_key 
        FROM bs_settings_mercadopago_hmg as a 
        WHERE id = (SELECT max(id) FROM bs_settings_mercadopago_hmg)";
$result = mysql_query($sql) or die("err: " . mysql_error() . $sql);

if (mysql_num_rows($result) > 0) {
    while ($rr = mysql_fetch_assoc($result)) {
        $datapublickey = $rr['hmg_public_key'];
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
            $datapublickey = $rr['prod_public_key'];
        }
    }else{
        echo 'Resultado da query = 0';
    }   
    

#tratando ois dados da exibicao do detalhamento 
$currency = getOption('currency');
$currencyPos = getOption('currency_position');
bw_apply_filter("pre_order_summery", $info, $orderId);
$orderInfo = getBooking($orderId);
$booking_times = '';
$booking_date = '';
$bookint_times_count = 0;

#montando o sql para buscar as informacoes 
$sSQL = "SELECT * FROM bs_reservations_items WHERE reservationID='" . $orderId . "' ORDER BY reserveDateFrom ASC";
$result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
while ($row = mysql_fetch_assoc($result)) {

    $booking_times .= date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateFrom"])) . " - " .
        date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateTo"])) . "<br/>";
    $booking_date = getDateFormat($row["reserveDateTo"]);
    $bookint_times_count++;
}

#definindo a data de reserva
$dataReserva = new DateTime($booking_dat);

#definindo o preco
$price = getServiceSettings($orderInfo['serviceID'], 'spot_price');

if ($price > 0) {
    $price = number_format($price, 2);
    $paid = true;
    $amount = ($currencyPos == 'b' ? $currency : "") . " {$price} " . ($currencyPos == 'a' ? $currency : "");
}

#retirando o R$ do valor total
$resp = str_replace('R$ ', '', $amount);

$valor = str_replace(',', '.', $resp);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Realizando pagamento mercado pago</title>

    <style>
        button.mercadopago-button {
            /* Seus atributos CSS */
            background-color: #39b54a;
            color: #FFFFFF;
            border: 1px solid #111;
            font-size: small;

            position: absolute;
            top: 77%;
            left: 50%;
            transform: translate(-50%, -50%);

            border-top-left-radius: .3125em;
            border-top-right-radius: .3125em;
            border-bottom-left-radius: .3125em;
            border-bottom-right-radius: .3125em; 
        }
    </style>

</head>

<body>
    <form action="https://clinicapacem.com.br/controller/PgtoMercadoPagoPaymentController.php?dados=<?php echo $valor; ?>&nome=<?php echo $nome; ?>&email=<?php echo $email; ?>" method="POST">
        <hr />
        <h2 align="center" style="background-color: #87CEEB; color: #FFFFFF;">Informações da Reserva / Mercado Pago</h2>
        <table width="25%" border="0" class='summery' align="center">
            <tr align="center">
                <td align="left" width="13%" style="background-color: #5F9EA0; color: #FFFFFF;">Data da Reserva:</td>
                <td align="left" width="12%" style="background-color: #DCDCDC; color: #000000;"><?php echo $dataReserva->format('d/m/Y'); ?></td>
            </tr>
            <tr align="center">
                <td align="left" width="13%" style="background-color: #5F9EA0; color: #FFFFFF;">Hora da Reserva:</td>
                <td align="left" width="12%" style="background-color: #DCDCDC; color: #000000;"><?php echo $booking_times; ?></td>
            </tr>
            <tr align="center">
                <td align="left" width="13%" style="background-color: #5F9EA0; color: #FFFFFF;">Quantidade:</td>
                <td align="left" width="12%" style="background-color: #DCDCDC; color: #000000;"><?php echo $orderInfo['qty']; ?></td>
            </tr>

            <tr align="center">
                <td align="left" width="13%" style="background-color: #5F9EA0; color: #FFFFFF;">Preço:</td>
                <td align="left" width="12%" style="background-color: #DCDCDC; color: #000000;"><?php echo $amount; ?></td>
            </tr>
        </table>
        <h2 align="center">Resumo do Pedido</h2>
        <table width="25%" border="0" class='summery' align="center">
            <tr align="left">
                <td align="right" width="13%"><b>Total:</b></td>
                <td align="left" width="12%"><b><?php echo $amount; ?></b></td>
            </tr>
        </table>
        <div>
            <br>
            <table width="60%" border="0" class='summery' align="center">
                <tr align="left">
                    <td align="justify">
                        Está quase pronto. Há apenas uma coisa a fazer, O pagamento. Por favor, clique no botão abaixo e você será transferido para o Mercado Pago para um pagamento rápido e seguro. Por favor, note que a sua reserva só será confirmada após o pagamento.
                    </td>
                </tr>
            </table>
        </div>
        <?php
        #retirando o R$ do valor total
        $amount = str_replace('R$ ', '', $amount);

        $valorTotal = str_replace(',', '.', $amount);

        ?>
        <hr />
        <div align="center">
            <img src="./images/mercado-pago-logo.svg" border="0" width="120" height="80" />
        </div>

        <script src="https://www.mercadopago.com.br/integrations/v1/web-tokenize-checkout.js" data-public-key="<?php echo $datapublickey; ?>" data-transaction-amount="<?php echo doubleval($valorTotal); ?>" data-summary-product-label="Agendamento do código: <?php echo $orderId; ?>" data-button-label="Pagar agora">
        </script>
        <br><br><br>
        <hr />
    </form>
</body>


</html>