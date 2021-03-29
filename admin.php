<?php
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

session_start();
require_once("includes/dbconnect.php"); //Load the settings
require_once("includes/config.php"); //Load the functions
$msg = "";

//LOGIN VARIABLES
$username = (!empty($_REQUEST['username'])) ? strip_tags(str_replace("'", "`", $_REQUEST['username'])) : '';
$password = (!empty($_REQUEST['password'])) ? strip_tags(str_replace("'", "`", $_REQUEST['password'])) : '';

// LOGIN
if (!empty($_REQUEST["login"]) && $_REQUEST['login'] == "yes") {
    if ($username == "" || $password == "") {
        //$msg = LOGIN_ERROR1;
        addMessage(LOGIN_ERROR1,"error");
    } else {


        if(getOption("username") !== false && getOption("password") !== false){
            $username_or = getOption("username");
            $password_or = getOption("password");

            if (md5($password) != $password_or) {
                //$msg = LOGIN_ERROR2;
                addMessage(LOGIN_ERROR2,"error");
            } else {

                $_SESSION['idUser'] = 1;
                $_SESSION['username'] = $username_or;
                $_SESSION['accesslevel'] = 1899;
                $_SESSION['logged_in'] = true;

                //addLog($row["id"],"Successfully logged in.");
            }
        } else {
            //$msg = LOGIN_ERROR2;
            addMessage(LOGIN_ERROR2,"error");
        }
    }
}

if ($_SESSION["logged_in"] == true) {
    header("Location: admin-index.php");
    exit();
} else {
    ?>
    <?php include "includes/admin_header.php"; ?>

    <div id="content">
        <div style="width: 500px;margin: 0 auto;padding-bottom: 5px">
<?php  getMessages(); ?>
        </div>
        <div class="login_container"> 
            <h3>Agenda Online</h3>
            
            <div class="login">
            
                <form method="post" action="admin.php" enctype="multipart/form-data"  name="ff1">
                <div class="line">
                    <label><?php echo LOGIN_USERNAME ?></label><input type="text" id="username" name="username" size="30" />
                </div>
                    <div class="line">
                        <label><?php echo LOGIN_PASSWORD ?></label><input type="password" id="password" name="password"  size="30" />
                    </div>
                    <div class="line">
                        <a href="forgot.php"><?php echo LOGIN_FORGOT ?></a>
                    </div>
                    <center>
                    <input type="submit" name="submit" value="<?php echo ADM_BTN_SUBMIT;?>" tabindex="2"/>
                    </center>
                    <input type="hidden" value="yes" name="login"  />

                </form>

            </div>
        </div>

    </div>
    <?php include "includes/admin_footer.php"; ?>
<?php } ?>