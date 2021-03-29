<?php
ob_start();
/* * ****************************************************************************
  #                         BookingWizz v5.2.1
  #******************************************************************************
  #      Author:     Convergine (http://www.convergine.com)
  #      Website:    http://www.convergine.com
  #      Support:    http://support.convergine.com
  #      Version:     5.2.1
  #
  #      Copyright:   (c) 2009 - 2012  Convergine.com
  #	   Icons from PixelMixer - http://pixel-mixer.com/basic_set/ and by Manuel Lopez - http://www.iconfinder.com/search/?q=iconset%3A48_px_web_icons
  #
  #****************************************************************************** */
$do = (isset($_GET['do']))?addslashes($_GET['do']):"";
$n = (isset($_GET['n']))?addslashes($_GET['n']):"";

if(!empty ($do) && $do == "activate"){
    if(bw_activate_plugin($n)) addMessage ("Plugin $n was successfully activated", "success");
}
if(!empty ($do) && $do == "deactivate"){
    if( bw_deactivate_plugin($n)) addMessage("Plugin $n was successfully deactivated", "success");
}

$files_table='';

$pluginsList = get_plugins_list();//dump($pluginsList);
$activePlugins = is_array(unserialize(getOption('active_plugins')))?unserialize(getOption('active_plugins')):array();//dump($activePlugins);


    
  

foreach ($pluginsList as $plugin){
    
    if(in_array($plugin['name'], $activePlugins)){
        $editable = "<a href='?do=deactivate&n=".  urlencode($plugin['name'])."'>Deactivate</a>";
    }else{
        $editable = "<a href='?do=activate&n=".  urlencode($plugin['name'])."'>Activate</a>";
    }
    
    
    
    $files_table .= "<h3>" . $plugin['plugin_name'] . "</h3>";
    $files_table .= "<p>" . $plugin['plugin_description'] . "</p>";
    $files_table .=  $editable ;
}

///bw_activate_plugin('credit_card_payment');
if(count($pluginsList)>0){
?>

     
        <?php echo $files_table; ?>
   

<?php
}
$plugin_list =ob_get_contents();
ob_clean()
?>