<?php ob_end_flush();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Agenda Online | Admin area</title>
<link rel="stylesheet" href="css/bs-admin.css" type="text/css" />
<?php 
if(IS_WP_PLUGIN=='1'){?>
   <link rel="stylesheet" href="css/bs_wp_admin.css" type="text/css" /> 
<?php
}
?>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link href="css/selectbox.css" rel="stylesheet" type="text/css"/>

<script src="js/jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

<link type="text/css" media="screen" rel="stylesheet" href="css/colorbox.css" />
<script type="text/javascript" src="js/jquery.colorbox.js"></script>

<link type="text/css" media="screen" rel="stylesheet" href="css/tipTip.css" />
<script type="text/javascript" src="js/jquery.tipTip.js"></script>

<script type="text/javascript" src="js/jquery.selectbox-0.5.js"></script>
<script>
    $(function(){
        $(".tipTip").tipTip({maxWidth:"200px", edgeOffset:10,defaultPosition:'right'});
        $('#Items').selectbox();
	$('#Items2').selectbox();
        
        jQuery("#BW_frame",top.document).height(jQuery("#content").height()+200)
    });

</script>


<?php bw_do_action("bw_admin_header_includes");?>

</head>
<body class="admin">
    <div id="wrapper">
<noscript>
    <div class="js_error">Favor habilitar o JavaScript ou atualizar para melhor <a href="http://www.mozilla.com/en-US/firefox/upgrade.html" target="_blank">navegar</a></div>
</noscript>
<?php if(IS_WP_PLUGIN!='1'){?>
    <div id="header">
        <div class="title"><img src="images/logo.png"/></div>
        <?php if($_SESSION["logged_in"]==true){?>

        <div style="clear: both"></div>
        <div class="mainmenu">
            <ul>
                <li><a href="index.php" target="_blank">Calendário</a></li>
                <li><a href="admin-index.php">Painel</a></li>
                <li><a href="bs-settings.php">Configurações</a></li>
                 
                <li><a href="bs-logout.php">Sair</a></li>
            </ul>
            <div style="clear: both"></div>
        </div>
        <div class="menu">
			<ul>
                            <?php 
                                $subMenu = array();
                                $currSubMenu = array();
                                foreach ($menuList as $k=>$v){
                                    $mainActive = false;
                                    if(isset($v['sub_menu'])){
                                        $subMenu = $v['sub_menu'];
                                        foreach($subMenu as $m=>$s){
                                            if(in_array(BW_SELF,$s)){
                                                $mainActive = true;
                                                $currSubMenu = $subMenu;
                                            }
                                        }
                                    }elseif(BW_SELF=="getAdminPage.php"){
                                       
                                        if(strpos($_SERVER['REQUEST_URI'],$v['menu_link'] ) )$mainActive = true;;
                                    }else{
                                        if(BW_SELF==$v['menu_link']){
                                            $mainActive = true;
                                        }
                                    }
                            ?>
				<li class="<?php echo $mainActive?"active":""?>"><a href="<?php echo $v['menu_link']?>"><?php echo strtoupper($v['menu_title'])?></a></li>
				
                                <?php }?>
			</ul>
			<div class="clear">
			</div>
		</div>
        <?php if(count($currSubMenu)){?>
                <div class="header_bottom">
			<ul>
                            <?php
                                $i=0;
                                foreach($currSubMenu as $k=>$v){
                            ?>
				<li class="<?php echo $i==0?"nosep":""?> <?php echo BW_SELF==$v['menu_link']?"active":""?>"><a href="<?php echo $v['menu_link']?>"><?php echo $v['menu_title']?></a></li>
				
                                <?php $i++;}?>
			</ul>
		</div>
        <?php } ?>
        <?php } ?>
    </div>
        
        <?php }?>
    <div style="clear: both"></div>