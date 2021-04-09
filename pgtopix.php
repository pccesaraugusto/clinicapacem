<?php
#definindo as configuracoes dos importes
include "includes/dbconnect.php";
include "includes/config.php";

require __DIR__ . '/lib/vendor/autoload.php';
require __DIR__ . "/App/Pix/Payload.php";

use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

#pegar os dados do comprador para adicionar aqui
$nome     = $_GET['nome'];
$orderID  = $_GET['orderID'];
$email    = $_GET['email'];
$amount   = $_GET['amount'];

$amount   = str_replace(',', '.', $amount);

$chave = '';

#buscar os dados da tabela do mysql para adicionar a chave do pix aqui
$sql = "SELECT a.id as id, a.chave, a.ativa
           FROM bs_settings_chave_pix_prod as a 
           WHERE a.id = (SELECT max(id) FROM bs_settings_chave_pix_prod)
           AND a.ativa = 'Sim'";
$result = mysql_query($sql) or die("err: " . mysql_error() . $sql);

if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {
                $chave = $rr['chave'];
        }
}


#populando a classe Payload - instancia principal do payload pix
$obPayload = (new Payload)->setPixKey($chave)
        ->setDescription('Pagamento do Pedido: ' . $orderID)
        ->setMerchantName($nome)
        ->setMerchantCity('Brasilia')
        ->setAmount(floatval($amount))
        ->setTxid('wdev1234'); //-- id da transacao --//

#criando a variavel para receber o Payload QR Code onde retorna a string do qr code, codigo de pagamento
$payloadQrCode = $obPayload->getPayload();

#criando uma instancia do qr code
$obQrCode = new QrCode($payloadQrCode);

#imagem do qr code de 400x400
$image = (new Output\Png)->output($obQrCode, 300);

?>

<!-- Mostrando a imagem do QR Code e seu codigo de pagamento -->
<html>

<head>
        <title>Pagamento Pix QR Code</title>
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
</head>

<body>
        <h2 align="center" style="background-color:  #4682B4; color: #FFFFFF;">Pagamento PIX com QR Code - Clinica Pacem</h2>
        <br><hr>
                <p align="center" style="color: red;">Atenção! Após a realização do pagamento, favor aguardar e-mail de confirmação. Favor olhar sua caixa de spam.</p>
                <hr>
        <center><img src="data:image/png;base64, <?= base64_encode($image) ?>" alt="QrCode Pix"></center>
        <br><br>
        <div>
                <b style="background-color:  #4682B4; color: #FFFFFF;">Código do PIX: </b><br>
                <strong style="font-size: 14px;"><?= $payloadQrCode ?></strong>
        </div>
        <br>
        <div align="center">
                <a href="https://clinicapacem.com.br/" class="link_pc" style="background-color:#39b54a; color:#FFFFFF;"><?php echo BTN_SAIR_PGTO_PIX; ?></a>
        </div>
</body>

</html>