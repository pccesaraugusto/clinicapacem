Prezado(a) {%name%},<br /> <br />
Obrigado por sua reserva.<br />
Aqui está a sua informação de reserva:<br />
<br />Serviço: {%serviceName%}
<br />
<table cellspacing=0 cellpadding=4 border=1>
<tr>
	<th bgcolor="#cccccc"><?php echo TBL_DATE?></th>
	<th bgcolor="#cccccc"><?php echo TBL_TIME1?></th>
	<th bgcolor="#cccccc"><?php echo TBL_TIME2?></th>
	<th bgcolor="#cccccc"><?php echo TBL_QTY?></th>
</tr>
<?php foreach($_info as $item){?>
<tr>
	<td><?php echo $item['date']?></td>
	<td><?php echo $item['timeFrom']?></td>
	<td><?php echo $item['timeTo']?></td>
	<td><?php echo $item['qty']?></td>
	
</tr>
<?php }?>
</table>
<?php if($_payment){?>

Subtal: {%currencyB%}&nbsp;{%subtotal%}&nbsp;{%currencyA%}<br />

<?php if($_taxable){?>

Imposto: {%currencyB%} {%tax%} {%currencyA%} ( {%taxRate%}% )<br />

<?php }?>
Valor total a ser pago: {%currencyB%}&nbsp;<strong>{%total%}</strong>&nbsp;{%currencyA%}<br />
A sua reserva será processada e confirmada após recebermos o seu pagamento.<br />
<?php }?>
Status da Reserva: {%status%}
<br />Você pode facilmente gerenciar a sua reserva usando este link {%linkCancelReservation%}
<br/>
