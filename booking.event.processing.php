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

require_once("includes/config.php"); //Load the configurations

bw_do_action("bw_load");
##################################################################################
#  	1. GET ALL VARIABLES
$name = (!empty($_POST["name"])) ? strip_tags(str_replace("'", "`", $_POST["name"])) : '';
$phone = (!empty($_POST["phone"])) ? strip_tags(str_replace("'", "`", $_POST["phone"])) : '';
$email = (!empty($_POST["email"])) ? strip_tags(str_replace("'", "`", $_POST["email"])) : '';
$comments = (!empty($_POST["comments"])) ? strip_tags(str_replace("'", "`", $_POST["comments"])) : '';
$date = (!empty($_POST["date"])) ? strip_tags(str_replace("'", "`", $_POST["date"])) : '';
$eventID = (!empty($_POST["eventID"])) ? $_POST["eventID"] : '';
$captcha_sum = (!empty($_POST["captcha_sum"])) ? strip_tags(str_replace("'", "`", $_POST["captcha_sum"])) : '';
$captcha = (!empty($_POST["captcha"])) ? strip_tags(str_replace("'", "`", $_POST["captcha"])) : '';
$qty = (!empty($_REQUEST["qty_" . $eventID])) ? strip_tags(str_replace("'", "`", $_REQUEST["qty_" . $eventID])) : '1';
$serviceID = (!empty($_REQUEST["serviceID"])) ? strip_tags(str_replace("'", "`", $_REQUEST["serviceID"])) : 1;
$ref = (!empty($_REQUEST["ref"])) ? strip_tags(str_replace("'", "`", $_REQUEST["ref"])) : 'all';

$msg = $infoForBooking = "";

$eventSummery='';

$eventInfo = getEventInfo($eventID);

// captcha check
if (empty($captcha_sum) || empty($captcha) || md5($captcha) != $captcha_sum) {
    if ($ref == "one") {
        header("Location: event.php?date=" . $date . "&lb2=yes&serviceID={$serviceID}&name=" . urlencode($name) . "&phone=" . urlencode($phone) . "&email=" . urlencode($email) . "&qty_{$eventID}=" . urlencode($qty) . "&comments=" . urlencode($comments) . "&eventID={$eventID}");
    } else {
        if (getOption('use_popup')) {
            header("Location: index.php?eventID=" . $eventID . "&lb2=yes&serviceID={$serviceID}&name=" . urlencode($name) . "&phone=" . urlencode($phone) . "&email=" . urlencode($email) . "&qty_{$eventID}=" . urlencode($qty) . "&comments=" . urlencode($comments) . "&selEvent={$eventID}"."&date={$date}");
        } else {
            header("Location: event-booking.php?eventID=" . $eventID . "&lb2=yes&serviceID={$serviceID}&name=" . urlencode($name) . "&phone=" . urlencode($phone) . "&email=" . urlencode($email) . "&qty_{$eventID}=" . urlencode($qty) . "&comments=" . urlencode($comments) . "&selEvent={$eventID}"."&date={$date}");
        }
    }
    exit();
}


if (!empty($name) && !empty($phone) && !empty($email) && !empty($eventID)) {
    if (!preg_match("(^[-\w\.]+@([-a-z0-9]+\.)+[a-z]{2,4}$)i", $email)) {
       addMessage(BEP_10,"error");
    } else {

        ##################################################################################
        #  	3. PREPARE BOOKING DATE/TIME  
        # 	CREATE ORDER
        $status = $eventInfo[5]=='invoice' ? 1 : 2;

        $avilability = getSpotsLeftForEvent($eventID);

        if ($avilability >= $qty) {


            $q = "INSERT INTO bs_reservations (dateCreated, name, email, phone, comments,status,eventID, qty) VALUES (NOW(),'" . $name . "','" . $email . "','" . $phone . "','" . $comments . "','" . $status . "','" . $eventID . "','" . $qty . "')";
            $res = mysql_query($q) or die("error!");
            $orderID = mysql_insert_id();

            if (!empty($orderID) && !empty($eventID)) {
                $serviceName = getService($serviceID, 'name');
                //get customer name and email
                $custInf = getInfoByReservID($orderID);
                //get event information for email notification
                $eventInf = getEventInfo($eventID);

                $uid = md5($custInf[1] . "FtTtffT");
                $linkCancelReservation = "<a href=\"http://" . $_SERVER['SERVER_NAME'] . $baseDir . "manageReservation.php?email=" . urlencode($custInf[1]) . "&uid=" . $uid . "\">link</a>";

                $price_per_spot = $eventInf[4];
                $tax = $taxRate = 0;
                if (getOption('enable_tax')) {
                    $taxRate = getOption('tax');
                    $tax = ($eventInf[4] * $qty * getOption('tax') / 100);
                }

                $subject = BEP_5;
                $data = array(
                    "{%name%}" => $custInf[0],
                    "{%service%}" => $serviceName,
                    "{%eventName%}" => $eventInf[0],
                    "{%eventDate%}" => $eventInf[2],
                    "{%eventDescr%}" => $eventInf[1],
                    "{%qty%}" => $qty,
                    "{%status%}" => BOOKING_FRM_NOTCONFIRMED,
                    "{%link%}" => $linkCancelReservation,
                    "{%currencyB%}" => getOption('currency_position')=='b'?getOption('currency'):"",
                    "{%currencyA%}" => getOption('currency_position')=='a'?getOption('currency'):"",
                    "{%tax%}" => number_format($tax, 2),
                    "{%subtotal%}" => number_format(($qty * $price_per_spot), 2),
                    "{%total%}" => number_format(($qty * $price_per_spot) + $tax, 2),
                    "{%taxRate%}" => $taxRate,
                    "_payment" => $eventInf[3] == 1 ? 1 : 0,
                    "_taxable" => !empty($tax) ? 1 : 0,
                    "{%collect%}" => $eventInfo[5] ? BEP_6 : BEP_7
                );
                sendMail($custInf[1], $subject, "eventBookingConfirmationCustomer.php", $data);
                ##################################################################################
                #  	4. SEND NOTICE TO ADMIN AND CUSTOMER
                //send email to admin
                $adminMail = getAdminMail();
                $subject = $eventInfo[5] ? BEP_8." (#" . $orderID . ")!" : BEP_9." (#" . $orderID . ")!";
                sendMail($adminMail, $subject, "eventBookingConfirmationAdmin.php", $data);
                $sent = true;


                $orderSummery = getOrderSummery($orderID);

                if ($eventInf['payment_required'] == "1" && !empty($eventInf['payment_method'])) {
                    if (!empty($orderID) && !empty($eventInf['payment_method'])) {
                        $infoForBooking = do_payment($orderID, $eventInf['payment_method']);
                    }
                } else {
                    $infoForBooking = BEP_11;
                }
            }
        } else {
            addMessage(BEP_12,"error");
        }
    }
} else {
    //throw error
    addMessage(BEP_13,"error");
}

?>
<?php include "includes/header.php" ?>

<div id="index">
    <h1><?php echo BEP_14;?></h1>
    
<?php  getMessages(); ?>
<?php echo($orderSummery) ?>
<?php echo $infoForBooking;?>
    
<br>

<?php include "includes/footer.php" ?>