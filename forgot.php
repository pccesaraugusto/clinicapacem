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

//RETRIEVE VARIABLES
$username = (!empty($_REQUEST['username'])) ? strip_tags(str_replace("'", "`", $_REQUEST['username'])) : '';
$email = (!empty($_REQUEST['email'])) ? strip_tags(str_replace("'", "`", $_REQUEST['email'])) : '';

// RETRIEVE
if (!empty($_REQUEST["restore"]) && $_REQUEST['restore'] == "yes") {
    if ($username == "" || $email == "") {
        $msg = WRONG_USERNAME2;
        addMessage(LOGIN_ERROR2, "error");
    } else {



        if (getOption("pemail") !== false && getOption("password") !== false) {


            if ($email != getOption("pemail")) {
                
                addMessage(WRONG_EMAIL2, "error");
                //addLog($row["id"],"Error during password retrieving. Wrong email.");
            } elseif ($username != getOption("email")) {
                
                addMessage(WRONG_EMAIL, "error");
            } else {

                getOption("password");

                $newPass = randomPassword();

                if ($demo === false) {

                    updateOption("password", md5($newPass));
                    //creating message for sending
                    $headers = "MIME-Version: 1.0\n";
                    $headers .= "Content-type: text/html; charset=utf-8\n";
                    $headers .= "From: 'Booking Management' <noreply@" . $_SERVER['HTTP_HOST'] . "> \n";
                    $subject = "Password Reset";
                    $message = "Dear " . $row["username"] . ",<br /> <br />";
                    $message .="Recently you have reset your password for " . $_SERVER['HTTP_HOST'] . ", here it is: <br />";
                    $message .="<br />Username: " .getOption("username");
                    $message .="<br />Password: " . $newPass;
                    $message .="<br />Please change password as soon as you login.";
                    $data = array(
                        "{%username%}"=>getOption("username"),
                        "{%password%}"=>$newPass
                    );
                    mail(getOption("email"), $subject, $message, $headers);
                    sendMail(getOption("email"),$subject,"PasswordRetrieval.php",$data);
                   
                    addMessage(NEW_PASS_SENT, "success");
                    
                    //addLog($row["id"],"Successfully reset password.");
                } else {
                    
                    addMessage(NEW_PASS_SENT, "error");
                }
            }
        } else {
            
            addMessage(WRONG_USERNAME, "error");
        }
    }
}

if ($_SESSION["logged_in"] == true) {
    header("Location: admin-index.php");
} else {
    ?>
    <?php include "includes/admin_header.php"; ?>
    <div id="content">
        <div style="width: 500px;margin: 0 auto;padding-bottom: 5px">
    <?php getMessages(); ?>
        </div>
        <div class="login_container"> 
            <h3><?php echo MSG_BSFORGOT_TITLE; ?></h3>
    <?php echo "<span style='color:#ff0000'>" . $msg . "</span>"; ?>
            <div class="login">

                <form method="post" action="forgot.php" enctype="multipart/form-data"  name="ff1">

                    <div class="line">
                        <label>Notification Email: </label><input type="text" id="username" name="username" size="30" />
                    </div>
                    <div class="line">
                        <label>Paypal Merchant Email: </label><input type="text" id="email" name="email"  size="30" />
                    </div>
                    <div class="line">
                        <a href="admin.php">Login?</a>
                    </div>
                    <center>
                        <input type="submit" name="submit" value="<?php echo ADM_BTN_SUBMIT; ?>" tabindex="2"/>
                    </center>		
                    <input type="hidden" value="yes" name="restore"  />
                </form>

            </div>
        </div>

    </div>
    <?php include "includes/admin_footer.php"; ?>

<?php } ?>