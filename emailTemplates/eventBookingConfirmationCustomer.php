{%name%},
<br><br>
Obrigado por sua reserva.
<br><br>
Serviço: {%service%}<br>
Nome do Evento: {%eventName%}<br>
Data do Evento: {%eventDate%}<br>
Ticket Quantity: {%qty%}<br><br>
Descrição do Evento: {%eventDescr%}<br>

<br>
<?php if($_payment){?>

Subtotal: {%currencyB%}&nbsp;{%subtotal%}&nbsp;{%currencyA%}<br />

<?php if($_taxable){?>

Imposto : {%currencyB%} {%tax%} {%currencyA%} ( {%taxRate%}% )<br />

<?php }?>
Valor total a ser pago: {%currencyB%}&nbsp;<strong>{%total%}</strong>&nbsp;{%currencyA%}<br />
A sua reserva será processada e confirmada após recebermos o seu pagamento.<br />
<?php }?>
Status da Reserva:{%status%}

Você pode facilmente gerenciar a sua reserva usando este link:{%link%}
