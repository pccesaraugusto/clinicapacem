administrador,
<br><br>
Obrigado por sua reserva.
<br><br>
Serviço: {%service%}<br>
Nome do Evento: {%eventName%}<br>
Data do Evento: {%eventDate%}<br>
Quantidade de Tickets: {%qty%}<br><br>
Descrição do Evento: {%eventDescr%}<br>

<br>
<?php if($_payment){?>

Subtotal : {%currencyB%}&nbsp;{%subtotal%}&nbsp;{%currencyA%}<br />

<?php if($_taxable){?>

imposto : {%currencyB%} {%tax%} {%currencyA%} ( {%taxRate%}% )<br />

<?php }?>
Valor total a ser pago: {%currencyB%}&nbsp;<strong>{%total%}</strong>&nbsp;{%currencyA%}<br />
A sua reserva será processada e confirmada após recebermos o seu pagamento.<br />
<?php }?>
{%collect%}


Você pode facilmente gerenciar a sua reserva usando este link:{%link%}
