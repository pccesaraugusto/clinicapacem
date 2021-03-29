{%name%},<br /> <br /> 
Esta é a confirmação de sua reserva<br />
Serviço: {%serviceName%}
<br />Email: {%email%}
<br />
Fone: {%phone%}
<br />
Comentários: {%comments%}
<br />
Informações de reserva: 
<br />
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
{%collect%}
<?php if($_payment){?>

Subotal : {%currencyB%}&nbsp{%subtotal%}&nbsp;{%currencyA%}<br />

<?php if($_taxable){?>

Imposto : {%currencyB%} {%tax%} {%currencyA%} ( {%taxRate%}% )<br />

<?php }?>
Valor total a ser pago: {%currencyB%}&nbsp;<strong>{%total%}</strong>&nbsp;{%currencyA%}<br />
A sua reserva será processada e confirmada após recebermos o seu pagamento.<br />
<?php }?>
Status da Reserva: {%status%}
<br/>
