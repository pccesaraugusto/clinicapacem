<script type="text/JavaScript">
<!--
 var bookQty = <?php echo $availebleSpaces?>;
 var bookQtyTmp = bookQty;
 var feePay = <?php echo $fee?>;
function checkForm() {
	var maximumBookings = <?php echo $maximumBookings?>;
	var minimumBookings = <?php echo $minimumBookings?>;
	var maxfordisp = <?php echo $maximumBookings*$int/60?>;
	var minfordisp = <?php echo $minimumBookings*$int/60?>;
	
	var err=0;
	var msg2="";
<?php
$reqFields=array(
	"name",
	"phone",
	"email",
	"tipopgto",
	"captcha"
	
);

foreach ($reqFields as $v) { ?>

	if (document.getElementById('<?php echo $v?>').value==0 || document.getElementById('<?php echo $v?>').value=="00") {
		if (err==0) {
			document.getElementById('<?php echo $v?>').focus();
		}
		document.getElementById('<?php echo $v?>').style.backgroundColor='#ffa5a5';
		err=1;
	}
<?php } ?>
	
	var reg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/; // not valid
	var reg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/; // valid   
	if (document.getElementById('email').value==0 || !reg2.test(document.getElementById('email').value)) {
	if (err==0) {
		document.getElementById('email').focus();
	}
	document.getElementById('email').style.backgroundColor='#ffa5a5';
	err=1;
	}
	
	//check checkboxes, must be at least 1, and not more than 3
  var checks = document.getElementsByName('time[]');
  var boxLength = checks.length;
  var totalChecked = 0;
    
    for ( i=0; i < boxLength; i++ ) {
      if ( checks[i].checked == true ) {
		totalChecked++;
      }
	}
	
	try{
		var qty = document.getElementById('qty').value;
		
		if(qty==''){
			document.getElementById('qty').style.backgroundColor='#ffa5a5';
			err=1;
		}
	}catch(e){
		var qty=1;
	}
	
if (err==0) {
		 if(totalChecked>0 && totalChecked>=minimumBookings && totalChecked<=maximumBookings){
			 //return false;
			err==0; 
		 } else { 
		 	if(maximumBookings==99){ var tt = ""; } else { var tt = ", maximum "+maxfordisp+" hour(s)"; }
		 	alert("Minimum booking time "+minfordisp+" hour(s)"+tt+". Please adjust your booking!");
		 	return false;
		 }
		 
		 if(err==0 && ((bookQtyTmp-qty)>=0)){
			//alert ('ok');
			return true;
		 }else{
			
			alert("Maximum booking qty "+bookQtyTmp+" . Please adjust your booking!");
		 	return false;
		}
	} else {
		alert("Please complete all highlited fields to continue.");
		return false;
	}
	
}
function calcFee(){
	var el=$('#feeValue');
	bookQtyTmp=bookQty;
	var tmp=bookQtyTmp*1;
	var intervals=$("input[name='time[]']:checked").length; 
	$("input[name='time[]']:checked").each(function(){
		if(($(this).attr('rel'))*1<=tmp){
			tmp=$(this).attr('rel');
		}
		
	});
	//console.log(tmp);
	
	if(tmp<bookQtyTmp){
		bookQtyTmp=tmp
	}
	//console.log(bookQtyTmp);
	if($('#qty').length){
		var qty =$('#qty').val();
	}else{
		var qty =1;
	}
	//console.log(intervals);
	//console.log(feePay);
	//console.log(qty);
	var fee=qty*feePay*intervals;
	el.html('<?php echo (getOption('currency_position')=='b'?getOption('currency')." ":"")?>'+formatNumber(fee)+' <?php echo (getOption('currency_position')=='a'?getOption('currency'):"")?>');
}

function checkFieldBack(fieldObj) {
	if (fieldObj.value!=0) {
		fieldObj.style.backgroundColor='#FFF';
	}
}

function checkNumeric(value){
		var anum=/(^\d+$)|(^\d+\.\d+$)/
		if (anum.test(value))
			return true;
		return false;
    }

function noAlpha(obj){
	reg = /[^0-9.,]/g;
	obj.value =  obj.value.replace(reg,"");
 }
 function formatNumber(nStr)
		{
		   nStr += '';
		   x = nStr.split('.');
		   x1 = x[0];
		   x2 = x.length > 1 ? '.' +  x[1].substring(0,2) : '.00';
		   var rgx = /(\d+)(\d{3})/;
		   while (rgx.test(x1)) {
			  x1 = x1.replace(rgx, '$1' + ',' + '$2');
		   }
		   return x1 + x2;
		}
$(function(){
	
		$("input[name='time[]']").bind('change',calcFee);
		if($('#qty').length){
			$('#qty').bind('change',function(){calcFee();})
			$('#qty').bind('keyup',function(){calcFee();})
			
		}
		calcFee();
	
})

//-->
</script> 