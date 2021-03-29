<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Agenda Online</title>
<link rel="stylesheet" href="css/bs-admin.css" type="text/css" />


<script src="js/jquery-1.7.2.min.js"></script>
<link type="text/css" href="css/redmond/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"></script>


<link type="text/css" media="screen" rel="stylesheet" href="css/colorbox.css" />
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<script type="text/javascript" src="js/spinner.js"></script>

<?php bw_do_action("bw_header_includes"); ?>
<script>
    $(function(){
        try{
            if($("#index").length){
                top.resizeFrame($('#index').height()+200,1100);
            }else{
                top.resizeFrame($('#resize').height()+200,1100);
            }
        
        }catch(e){}
        
            
        
    }
)

</script>
</head>
    <body style="height: auto">
<noscript>
    <div class="js_error">Favor habilitar o JavaScript ou atualizar para  <a href="http://www.mozilla.com/en-US/firefox/upgrade.html" target="_blank">navegar melhor</a></div>
</noscript>