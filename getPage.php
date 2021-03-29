<?php
    /******************************************************************************
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
    #*******************************************************************************/


    ######################### DO NOT MODIFY (UNLESS SURE) ########################
    session_start();
    require_once("includes/dbconnect.php"); //Load the settings
    require_once("includes/config.php"); //Load the functions
    $msg = "";
    $page = (isset($_GET['p']))?urldecode($_GET['p']):"";

        bw_do_action("bw_load");

        include "includes/admin_header.php";
        
        
?>
    <div id="content">
        <h1><?php echo APPLICATION_TITLE?></h1>
        
        <div class="content_block">
            <h2><?php echo ADMIN_WELCOME?></h2>
            <?php if(bw_get_page($page)){
                
            }else{?>
                Error
            <?php }?>
        </div>
    </div>

<?php
        include "includes/admin_footer.php";
    
?>