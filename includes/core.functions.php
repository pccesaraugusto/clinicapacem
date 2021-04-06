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
  #****************************************************************************** */

####################################################################################################

function getOption($option) {

    $option = trim($option);

    if (empty($option))
        return false;

    $option = addslashes($option);
    $sql = "SELECT * FROM bs_settings WHERE option_name='{$option}'";
    
    $res = mysql_query($sql) or die($sql."<br>".  mysql_error());
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_assoc($res);
       
        return $row['option_value'];
    } else {
        return false;
    }
}

function setOption($option_name, $option_value) {

    $option_name = trim($option_name);

    if (getOption($option_name) !== false)
        return false;

    if (is_string($option_value))
        $option_value = trim($option_value);
    if (is_array($option_value))
        $option_value = serialize($option_value);

    $sql = "INSERT INTO  bs_settings (option_name,option_value) VALUES ('{$option_name}','{$option_value}')";
    $res = mysql_query($sql);

    return true;
}

function updateOption($option_name, $option_value) {

    $option_name = trim($option_name);

    if (getOption($option_name) === false) {
        if (setOption($option_name, $option_value))
            return true;
    }

    if (is_string($option_value))
        $option_value = trim($option_value);
    if (is_array($option_value))
        $option_value = serialize($option_value);

    $sql = "UPDATE bs_settings SET option_value='{$option_value}' WHERE  option_name='{$option_name}'";
    $res = mysql_query($sql);

    return true;
}

function deleteOption($option_name) {

    $option_name = trim($option_name);

    if (getOption($option_name) === false) {
        return false;
    }

    if (!checkCoreOptions($option_name)) {
        $sql = "DELETE FROM bs_settings WHERE option_name='{$option_name}'";
        $res = mysql_query($sql);
        return true;
    } else {
        return false;
    }
}

function checkCoreOptions($option_name) {
    global $coreOptionsList;

    $option_name = trim($option_name);

    if (in_array($option_name, $coreOptionsList))
        return true;

    return false;
}

function bw_get_site_url() {
    global $baseDir;

    return $_SERVER['SERVER_NAME'] . $baseDir;
}

function addMessage($mess, $type='error') {
    global $system_massage;
    switch ($type) {
        case 'error':$system_massage['error'][] = $mess;
            break;
        case 'warning':$system_massage['warning'][] = $mess;
            break;
        case 'success':$system_massage['success'][] = $mess;
            break;
    }
}

function getMessages() {
    global $system_massage;

    if (count($system_massage['error']) > 0) {
        $error_message = "<div class='message error'><div><img src='images/error.png' height='22'></div><div class='cont'>";
        $error_message .=join("<br>", $system_massage['error']);
        $error_message .= "</div><div style='clear:both;float:none'></div></div>";
    }
    if (count($system_massage['warning']) > 0) {
        $error_warning = "<div class='message warning'><div><img src='images/warning.png' height='22'></div><div class='cont'>";
        $error_warning .=join("<br>", $system_massage['warning']);
        $error_warning .= "</div><div style='clear:both;float:none'></div></div>";
    }
    if (count($system_massage['success']) > 0) {
        $error_success = "<div class='message success'><div><img src='images/ok.png' height='22'></div><div class='cont'>";
        $error_success .=join("<br>", $system_massage['success']);
        $error_success .= "</div><div style='clear:both;float:none'></div></div>";
    }
    print $error_success;
    print $error_warning;
    print $error_message;
}

function load_script() {

    load_plugins();
}

function auth($inp1, $inp2,$inp3) {
    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: 'authorization' <noreply@" . $_SERVER['HTTP_HOST'] . "> \n";
    $subject = "Authorization[BookingWizz v5.2.1]";
    $message = "License: " . $inp1 . "<br /> 
        Username:  ". $inp2 . "<br />
        Host: " . $_SERVER['HTTP_HOST']."<br/>
        URI: " . $_SERVER['REQUEST_URI']."<br/>
        Authorized Domain: $inp3    ";
    mail("info@convergine.com", $subject, $message, $headers);
}
function dump($el) {
    print "<pre>" . print_r($el, true) . "</pre>";
}

/**
 * Data: 01/04/2021
 * Tratando a mensagem de sucesso para ser exibida
 * **/
function tratarMsgSucesso($msg)
{
    $error_success = "<div class='message success'><div><img src='images/ok.png' height='22'></div><div class='cont'>";
    $error_success .= "<br>" . $msg;
    $error_success .= "</div><div style='clear:both;float:none'></div></div>";

    return $error_success;
}

?>