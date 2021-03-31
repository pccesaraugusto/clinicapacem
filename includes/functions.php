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
/* $currDir = dirname(__FILE__).'/';
  include_once $currDir."config.php"; */

function setupCalendar1($iMonth, $iYear, $serviceID=1) {
    global $baseDir;
    $calendar="";
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                } else {

                    $admReserve = checkAdminReserve($datetocheck, $serviceID);
                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate LIKE '%" . $datetocheck . "%' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";

                    $result = mysql_query($query);
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!

                        if (!empty($admReserve)) {
                            $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                        } else {
                            $event_num = mysql_num_rows($result);
                            //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                            $event_available = false;
                            $event_count = 0;
                            $text = "";

                            while ($row = mysql_fetch_assoc($result)) {
                                $spaces_left = getSpotsLeftForEvent($row["id"]);
                                if ($spaces_left > 0) {
                                    $event_available = true;
                                    $event_count++;
                                }

                                if (getServiceSettings($serviceID, 'show_event_titles')) {
                                    $text.="<div>{$row['title']}</div>";
                                }
                                if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                    $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                                }
                            }

                            $text = empty($text) ? $event_num . EVENTS_SCHEDULED : $text;

                            if ($event_available) {
                                $bgClass = "cal_reg_on";
                            } else {
                                $bgClass = "cal_reg_off";
                            }

                            $calendar .= "<td id=\"" . $iDay . "\"";
                            if ($iCurrentDay != $iDay) {
                                $var = "";
                            } else {
                                $var = "_today";
                            }
                            $click = getOption('use_popup') ? "getLightbox2('" . $datetocheck . "'," . $serviceID . ");" : "location.href='event-booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                            if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                                $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" onClick=\"{$click}\"";
                            } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                                $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "';\" onClick=\"{$click}\"";
                            }
                            $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                            //check if this day available for booking or not.
                            if ($bgClass == "cal_reg_off") {
                                $calendar .= "<span class='hide-me-for-nojs'><br/>".ZERO_SPACES."</span><noscript><br/>".ZERO_SPACES."</noscript>";
                            } else {
                                $calendar .= "<div class='cal_text hide-me-for-nojs'>" . $text . "</div><noscript><br/><a href='event-booking-nojs.php?date=" . $datetocheck . "'>" . $text . "</a></noscript>";
                            }
                            $calendar .= "</td>";
                        }
                    } else {
                        //we dont have events for this day, lets check bookings.
                        $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                        if ($cur_spots > 0) {
                            $bgClass = "cal_reg_on";
                        } else {
                            $bgClass = "cal_reg_off";
                        }
                        $calendar .= "<td id=\"" . $iDay . "\"";
                        if ($iCurrentDay != $iDay) {
                            $var = "";
                        } else {
                            $var = "_today";
                        }
                        $click = getOption('use_popup') ? "getLightbox('" . $datetocheck . "'," . $serviceID . ");" : "location.href='booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                            $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" onClick=\"{$click}\"";
                        } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                            $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "';\" onClick=\"{$click}\"";
                        }
                        $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                        //check if this day available for booking or not.
                        $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');
                        if ($bgClass == "cal_reg_off") {
                            $calendar .= "<span class='hide-me-for-nojs'><br/>" . ($showSpaces ? ZERO_SPACES : "") . "</span><noscript><br/>" . ($showSpaces ? ZERO_SPACES : "") . "</noscript>";
                        } else {
                            $spotsText = ($showSpaces) ? $cur_spots . SPC_AVAIL : "";
                            $calendar .= "<div class='cal_text hide-me-for-nojs'>" . $spotsText . "</div><noscript><br/><a href='booking-nojs.php?date=" . $datetocheck . "'>" . $spotsText . "</a></noscript>";
                        }
                        $calendar .= "</td>";
                    } // end EVENT CHECKER.
                    ########################################################################################################################################
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach 
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function setupSmallCalendar($iMonth, $iYear, $serviceID=1) {
    global $baseDir;
    $linkPrefix = "http://" . $_SERVER['SERVER_NAME'] . $baseDir;
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'><div class='day_number'>" . $iDay . "</div></td>";
                } else {


                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $datetocheck . " 23:59' AND eventDateEnd >= '" . $datetocheck . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";

                    $result = mysql_query($query);

                    $bgClass = "cal_reg_off";
                    $text = "";
                    $textTime = "";
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!


                        $bgClass = "cal_reg_on";
                        $event_num = mysql_num_rows($result);
                        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                        $event_available = false;
                        $event_count = 0;





                        while ($row = mysql_fetch_assoc($result)) {

                            $spaces_left = getSpotsLeftForEvent($row["id"]);
                            //$click = "location.href='{$linkPrefix}event-booking.php?eventID=" . urlencode($row['id']) . "&serviceID=" . $serviceID . "'";
                            $click = "getLightbox2('" . $row['id'] . "'," . $serviceID . ",'" . $datetocheck . "');";
                            if ($spaces_left > 0) {
                                $event_available = true;
                                $event_count++;
                            }
                            $text.="<div onclick=\"{$click};return false;\" class='eventConteiner'>";
                            if (getServiceSettings($serviceID, 'show_event_titles')) {
                                $text.="<div>{$row['title']}</div>";
                            }
                            if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                            }
                            $text.="</div>";
                        }
                    }
                    //we dont have events for this day, lets check bookings.
                    $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                    $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');
                   
                    if ($cur_spots > 0) {
                        $bgClass = "cal_reg_on";
                        //$clickTime = "location.href='{$linkPrefix}booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        $clickTime = "getLightbox('" . $datetocheck . "'," . $serviceID . ");";
                        $spotsText = ($showSpaces) ?  $cur_spots . " spaces available" : 'book';
                        $spotsText = '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . $spotsText . "</div>";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                    } else {
                        $spotsText = "";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                        $clickTime = '';
                    }

                    // end EVENT CHECKER.
                    ########################################################################################################################################

                    $calendar .= "<td id=\"" . $iDay . "\"";
                    if ($iCurrentDay != $iDay) {
                        $var = "";
                    } else {
                        $var = "_today";
                    }

                    if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"this.className='mainmenu5';\" onmouseout=\"this.className='" . $bgClass . "';\" ";
                    } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"this.className='mainmenu5';\" onmouseout=\"this.className='" . $bgClass . $var . "' \"";
                    }
                    $calendar .= "class=\"" . $bgClass . $var . "\"><div class='day_number'>" . $iDay;
                    if (!empty($textTime) || !empty($text)) {
                        $calendar .="<div class='showInfo'>" . $textTime . $text . "<b></b></div>";
                    }
                    //check if this day available for booking or not.
                    /* if(Empty($text)){
                      $calendar .= "<span class='hide-me-for-nojs'><br/>0 spaces available</span><noscript><br/>0 spaces available</noscript>";
                      } else {
                      $calendar .= "<div class='cal_text hide-me-for-nojs'>".$text."</div><noscript><br/><a href='event-booking-nojs.php?date=".$datetocheck."'>".$text."</a></noscript>";
                      } */
                    $calendar .= "</div></td>";
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach 
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function setupCalendar($iMonth, $iYear, $serviceID=1) {
    $calendar="";
    global $baseDir;
    $startDay = getFirstDay($serviceID);
    $thismonth = false;

    $iMonth2 = date('m', strtotime(date("Y") . "-" . $iMonth . "-01"));
    if (!$iMonth || !$iYear) {
        $iMonth = date('n');
        $iYear = date('Y');
    }

    ############################## BUILD BASE CALENDAR ################################
    $aCalendar = buildCalendar($iMonth, $iYear, $serviceID);
    list($iPrevMonth, $iPrevYear) = prevMonth($iMonth, $iYear);
    list($iNextMonth, $iNextYear) = nextMonth($iMonth, $iYear);
    $iCurrentMonth = date('n');
    $iCurrentYear = date('Y');
    $iCurrentDay = '';
    if (($iMonth == $iCurrentMonth) && ($iYear == $iCurrentYear)) {
        $iCurrentDay = date('d');
        $thismonth = true;
    }
    $iNextMonth = mktime(0, 0, 0, $iNextMonth, 1, $iNextYear);
    $iPrevMonth = mktime(0, 0, 0, $iPrevMonth, 1, $iPrevYear);
    $iCurrentDay = $iCurrentDay;
    $iCurrentMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

    ############################ PREPARE CALENDAR DATA #############################
    foreach ($aCalendar as $aWeek) {
        $calendar .= "<tr>";
        foreach ($aWeek as $iDay => $mDay) {
            if ($iDay == '') {
                $calendar .= "<td colspan=\"" . $mDay . "\"  class=\"cal_reg_off\">&nbsp;</td>";
            } else {
                if (strlen($iDay) == 1) {
                    $iDay = '0' . $iDay;
                }
                $datetocheck = $iYear . "-" . $iMonth2 . "-" . $iDay;



                if ($datetocheck < date("Y-m-d")) {
                    $calendar .= "<td id=\"" . $iDay . "\" class='cal_reg_off'>" . $iDay . "</td>";
                } else {


                    //we need to check reserved time by admin, in case this day is booked off by him.
                    ######################### EVENT CHECKER ###################################################################################################
                    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $datetocheck . " 23:59' AND eventDateEnd >= '" . $datetocheck . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
                    //print $query;
                    $result = mysql_query($query);
                    //$calendar .= "<td id=\"".$iDay."\" class='cal_reg_off'>".$iDay."</td>";
                    $bgClass = "cal_reg_off";
                    $text = "";
                    $textTime = "";
                    if (mysql_num_rows($result) > 0) {
                        //we have events for this day!


                        $bgClass = "cal_reg_on";
                        $event_num = mysql_num_rows($result);
                        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
                        $event_available = false;
                        $event_count = 0;





                        while ($row = mysql_fetch_assoc($result)) {

                            $spaces_left = getSpotsLeftForEvent($row["id"]);
                            $click = getOption('use_popup') ? "getLightbox2('" . $row['id'] . "'," . $serviceID . ",'".$datetocheck."');" : "location.href='event-booking.php?eventID=" . urlencode($row['id']) . "&serviceID=" . $serviceID . "&date=".$datetocheck."'";
                            if ($spaces_left > 0) {
                                $event_available = true;
                                $event_count++;
                                
                            }else{
                                $click = "javascript:;";
                            }
                            $text.="<div onclick=\"{$click};return false;\" class='eventConteiner ".($spaces_left<1?"disabled":"")."'>";
                            if (getServiceSettings($serviceID, 'show_event_titles')) {
                                $text.="<div>{$row['title']}</div>";
                            }else{
                                $text.="<div>Event</div>";
                            }
                            if (getServiceSettings($serviceID, 'show_event_image') && !empty($row['path'])) {

                                $text.="<div><img src='{$baseDir}{$row['path']}' width='40'></div>";
                            }
                            $text.="</div>";
                        }
                    }
                    //we dont have events for this day, lets check bookings.
                    $cur_spots = checkSpotsLeft($datetocheck, $serviceID);
                    $showSpaces = getServiceSettings($serviceID, 'show_spaces_left');
                    if ($cur_spots > 0) {
                        $bgClass = "cal_reg_on";
                        $clickTime = getOption('use_popup') ? "getLightbox('" . $datetocheck . "'," . $serviceID . ");" : "location.href='booking.php?date=" . urlencode($datetocheck) . "&serviceID=" . $serviceID . "'";
                        $spotsText = ($showSpaces) ? '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . $cur_spots . SPC_AVAIL ."</div>" : '<div class="cal_text hide-me-for-nojs" onclick="' . $clickTime . '">' . BOOK_NOW . "</div>";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                    } else {
                        $spotsText = ($showSpaces) ? "<span class='hide-me-for-nojs'><br/>" . $cur_spots . SPC_AVAIL."</span>" : "";
                        //$text .="<div class='eventConteiner' onclick=\"{$clickTime}\">{$spotsText}</div>";
                        $textTime .=$spotsText;
                        $clickTime = '';
                    }

                    // end EVENT CHECKER.
                    ########################################################################################################################################

                    $calendar .= "<td id=\"" . $iDay . "\"";
                    if ($iCurrentDay != $iDay) {
                        $var = "";
                    } else {
                        $var = "_today";
                    }

                    if ($iCurrentDay != $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . "';\" ";
                    } else if ($iCurrentDay == $iDay && $bgClass != "cal_reg_off") {
                        $calendar .= "onmouseover=\"getElementById('" . $iDay . "').className='mainmenu5';\" onmouseout=\"getElementById('" . $iDay . "').className='" . $bgClass . $var . "' \"";
                    }
                    $calendar .= "class=\"" . $bgClass . $var . "\">" . $iDay;
                    $calendar .=$textTime . $text;
                    //check if this day available for booking or not.
                    /* if(Empty($text)){
                      $calendar .= "<span class='hide-me-for-nojs'><br/>0 spaces available</span><noscript><br/>0 spaces available</noscript>";
                      } else {
                      $calendar .= "<div class='cal_text hide-me-for-nojs'>".$text."</div><noscript><br/><a href='event-booking-nojs.php?date=".$datetocheck."'>".$text."</a></noscript>";
                      } */
                    $calendar .= "</td>";
                } //end if iDay
            }
        }
        $calendar .= "</tr>";
    } //end foreach 
    ############################## END PREPARE CALENDAR DATA ################################

    return $calendar;
}

function buildCalendar($iMonth, $iYear, $serviceID=1) {
    $myFirstDay = getFirstDay($serviceID);
    $iFirstDayTimeStamp = mktime(0, 0, 0, $iMonth, 1, $iYear);
    $iFirstDayNum = date('w', $iFirstDayTimeStamp);
    $iFirstDayNum++;
    $iDayCount = date('t', $iFirstDayTimeStamp);
    $aCalendar = array();
    if ($myFirstDay == "0") {
        //SUNDAY
        if ($iFirstDayNum > 1) {
            $aCalendar[1][''] = $iFirstDayNum - 1; // how many empty squares before actual day 1.
        }
        $i = 1;
        $j = 1;

        while ($j <= $iDayCount) {
            $aCalendar[$i][$j] = $j;
            if (floor(($j + $iFirstDayNum - 1) / 7) >= $i) {
                $i++;
            }
            $j++;
        }
        if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
            $aCalendar[$i][''] = 7 - $iM;
        }
    } else if ($myFirstDay == "1") {
        //MONDAY
        $iFirstDayNum--;
        if ($iFirstDayNum > 1 && $iFirstDayNum < 6) {
            //echo "off1";
            $tmp = 1;
            $aCalendar[1][''] = $iFirstDayNum - $tmp;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - $tmp) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else if ($iFirstDayNum == 0) {

            //echo "off2";
            $tmp = 1;
            $aCalendar[1][''] = 6;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum + 6) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else if ($iFirstDayNum == 6) {

            //echo "off2";
            $tmp = 1;
            $aCalendar[1][''] = 5;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - 1) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        } else {
            //echo "off3";
            //echo $iFirstDayNum;
            $tmp = 1;
            $i = 1;
            $j = 1;

            while ($j <= $iDayCount) {
                $aCalendar[$i][$j] = $j;
                if (floor(($j + $iFirstDayNum - $tmp) / 7) >= $i) {
                    $i++;
                }
                $j++;
            }
            if ((isset($aCalendar[$i])) && ($iM = count($aCalendar[$i])) < 7) {
                $aCalendar[$i][''] = 7 - $iM; //last row - how many empty squares.
            }
        }
    }
    return $aCalendar;
}

function nextMonth($iMonth, $iYear) {
    if ($iMonth == 12) {
        $iMonth = 1;
        $iYear++;
    } else {
        $iMonth++;
    }
    return array($iMonth, $iYear);
}

function nextDay($iDay, $iMonth, $iYear) {
    $iDayTimestamp = mktime(0, 0, 0, $iMonth, $iDay, $iYear);
    $iNextDayTimestamp = strtotime('+1 day', $iDayTimestamp);
    return $iNextDayTimestamp;
}

function prevDay($iDay, $iMonth, $iYear) {
    $iDayTimestamp = mktime(0, 0, 0, $iMonth, $iDay, $iYear);
    $iPrevDayTimestamp = strtotime('-1 day', $iDayTimestamp);
    return $iPrevDayTimestamp;
}

function prevMonth($iMonth, $iYear) {
    if ($iMonth == 1) {
        $iMonth = 12;
        $iYear--;
    } else {
        $iMonth--;
    }
    return array($iMonth, $iYear);
}

function getMaxSecondsForThisDay($day) {
    $tt = 0;
    $q = "SELECT * FROM bs_settings WHERE id='1'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    /* if(!empty($rr[$day."_from"]) && $rr[$day."_from"]!="N/A"){ $from = explode(":",$rr[$day."_from"]); } else { $from[0]=0; }
      if(!empty($rr[$day."_to"]) && $rr[$day."_to"]!="N/A"){ $to = explode(":",$rr[$day."_to"]);} else { $to[0]=0; } */ //LEFTOVERS FROM V2
    if (!empty($rr[$day . "_from"]) && $rr[$day . "_from"] != "N/A" && $rr[$day . "_from"] != "0") {
        $from = $rr[$day . "_from"] / 60;
    } else {
        $from = 0;
    }
    if (!empty($rr[$day . "_to"]) && $rr[$day . "_to"] != "N/A" && $rr[$day . "_to"] != "0") {
        $to = $rr[$day . "_to"] / 60;
    } else {
        $to = 0;
    }
    $tt = (($to - $from) * 60) * 60;
    return $tt;
}

function getStartEndTime($day, $serviceID=1) {
    $tt = array();
    $tt[0] = 0;
    $tt[1] = 0;
    $q = "SELECT * FROM bs_service_settings WHERE serviceId='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    /* if(!empty($rr[$day."_from"]) && $rr[$day."_from"]!="N/A"){ $from = explode(":",$rr[$day."_from"]); } else { $from[0]=0; }
      if(!empty($rr[$day."_to"]) && $rr[$day."_to"]!="N/A"){ $to = explode(":",$rr[$day."_to"]);} else { $to[0]=0; } */ //LEFTOVERS FROM V2
    /* if(!empty($rr[$day."_from"]) && $rr[$day."_from"]!="N/A" && $rr[$day."_from"]!="0"){ $from = $rr[$day."_from"]/60; } else { $from=0; }
      if(!empty($rr[$day."_to"]) && $rr[$day."_to"]!="N/A" && $rr[$day."_to"]!="0"){ $to = $rr[$day."_to"]/60;} else { $to=0; } */
    /* $tt[0]= $from;
      $tt[1]=$to;
      $tt[2]= $from*60;
      $tt[3]=$to*60; */
    if (!empty($rr[$day . "_from"]) && $rr[$day . "_from"] != "N/A" && $rr[$day . "_from"] != "0") {
        $from = $rr[$day . "_from"];
    } else {
        $from = 0;
    }
    if (!empty($rr[$day . "_to"]) && $rr[$day . "_to"] != "N/A" && $rr[$day . "_to"] != "0") {
        $to = $rr[$day . "_to"];
    } else {
        $to = 0;
    }

    $tt[0] = ($from - ($from % 60)) / 60;
    $tt[1] = ($to - ($to % 60)) / 60;
    $tt[2] = $from;
    $tt[3] = $to;
    //print var_dump($tt);
    return $tt;
}

function getSpotsLeftForEvent($id) {
    $q = "SELECT payment_required,spaces FROM bs_events WHERE id='" . $id . "'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    $space = $rr["spaces"];
    //if($rr["payment_required"]=="1"){ $status = "4";} else { $status = "1"; }
    $q = "SELECT SUM(qty) as num FROM bs_reservations WHERE eventID='" . $id . "' AND (status='1' OR status='4')";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $space - $rr["num"];
}

function getMaxBooking($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["allow_times"];
}

function getMinBooking($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["allow_times_min"];
}

function getInterval($serviceID=1) {
    $q = "SELECT `interval` FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res); //print $rr["interval"];
    return $rr["interval"];
}

function getBookings($date, $time, $serviceID=1) {
    $text = "";
    //if($time<10){ $time = "0".$time; }
    $q = "SELECT a.*, b.reason FROM bs_reserved_time_items a, bs_reserved_time b WHERE a.dateFrom LIKE '" . $date . " " . $time . "%' AND a.reservedID=b.id AND b.serviceID={$serviceID} ORDER BY a.dateFrom ASC LIMIT 1";
    $res = mysql_query($q);
    if (mysql_num_rows($res) > 0) {
        while ($rr = mysql_fetch_assoc($res)) {
            $text .= TXT_RESERVED . $rr["reason"] . "<br/>";
        }
    }
    $q = "SELECT bs_reservations.* FROM `bs_reservations` INNER JOIN bs_reservations_items  on bs_reservations_items.reservationID = bs_reservations.id  WHERE (bs_reservations.status='1' OR bs_reservations.status='4') AND bs_reservations_items.reserveDateFrom LIKE '" . $date . " " . $time . "%' AND `bs_reservations`.serviceID={$serviceID} ORDER BY bs_reservations_items.reserveDateFrom ASC  LIMIT 1";
    $res = mysql_query($q);
    if (mysql_num_rows($res) > 0) {
        while ($rr = mysql_fetch_assoc($res)) {
            $text .= "<a href='bs-bookings-edit.php?id=" . $rr["id"] . "'>" . $rr["name"] . " (" . $rr["phone"] . ")</a><br/>";
        }
    }
    return $text;
}

function getInfoByReservID($reservID) {
    $q = "SELECT name,email,qty FROM bs_reservations WHERE id='" . $reservID . "'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    $t = array();
    $t[0] = $rr["name"];
    $t[1] = $rr["email"];
    $t[2] = $rr["qty"];
    return $t;
}

function getEventInfo($id) {
    $t = array();
    $q = "SELECT * FROM bs_events WHERE id='" . $id . "'";
    $res = mysql_query($q);
    if (mysql_num_rows($res) < 1)
        return false;
    $rr = mysql_fetch_assoc($res);
    
    $t = $rr;
    $t[0] = $rr["title"];
    $t[1] = $rr["description"];
    if (date("d-m-Y", strtotime($rr["eventDate"])) == date("d-m-Y", strtotime($rr["eventDateEnd"]))) {
        $t[2] = getDateFormat($rr["eventDate"]) . " " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($rr["eventDate"])) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($rr["eventDateEnd"]));
    } else {
        $t[2] = "from: " . getDateFormat($rr["eventDate"]) . " " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($rr["eventDate"]));
        $t[2].=" to: " . getDateFormat($rr["eventDateEnd"]) . " " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($rr["eventDateEnd"]));
    }
    $t[3] = $rr["payment_required"];
    $t[4] = $rr["entryFee"];
    $t[5] = $rr["payment_method"];
    $t[6] = $rr["serviceID"];
    $t[7] = date("Y-m-d", strtotime($rr["eventDate"]));
    
    


    return $t;
}

function getOrderSummery($orderId) { 
    $info = '';

    bw_apply_filter("pre_order_summery", $info, $orderId);

    $info .="<div class='orderSummery'>";

    $currency = getOption('currency');
    $currencyPos = getOption('currency_position');

    $paid = false;

    $orderInfo = getBooking($orderId);


    if (!empty($orderInfo['eventID'])) {
        $eventInfo = getEventInfo($orderInfo['eventID']);
        $info .="<h2>Informações do Evento</h2>";
        $info .="<ul class='summery'>";
        $info .="<li><label>Título:</label>{$eventInfo['title']}</li>";
        $info .="<li><label>Descrição:</label><span>{$eventInfo['description']}</span></li>";
        $info .="<li><label>Início:</label>" . getDateFormat($eventInfo['eventDate']) . " " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($eventInfo['eventDate'])) . "</li>";
        $info .="<li><label>Encerramento:</label>" . getDateFormat($eventInfo['eventDateEnd']) . " " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($eventInfo['eventDateEnd'])) . "</li>";
        $info .="<li><label>QTD:</label>{$orderInfo['qty']}</li>";
        $info .="</ul><div style='clear:both'></div>";
        if ($eventInfo['payment_required'] == 1) {
            $paid = true;
        }
    } else {
        $info .="<h2>Informações de reserva</h2>";
        $info .="<ul class='summery'>";
        $booking_times = '';
        $booking_date = '';
        $bookint_times_count = 0;
        $sSQL = "SELECT * FROM bs_reservations_items WHERE reservationID='" . $orderId . "' ORDER BY reserveDateFrom ASC";
        $result = mysql_query($sSQL) or die("err: " . mysql_error() . $sSQL);
        while ($row = mysql_fetch_assoc($result)) {

            $booking_times .=date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateFrom"])) . " - " .
                            date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["reserveDateTo"])) . "<br/>";
            $booking_date = getDateFormat($row["reserveDateTo"]);
            $bookint_times_count++;
        }
        $info .="<li><label>Data da Reserva:</label>{$booking_date}</li>";
        $info .="<li><label>Hora da Reserva:</label><span>{$booking_times}</span></li>";
        $info .="<li><label>Qtd:</label>{$orderInfo['qty']}</li>";
        $price = getServiceSettings($orderInfo['serviceID'], 'spot_price');
        if($price > 0 ){
            
            $price = number_format($price,2);
            $paid = true;
            $info .="<li><label>Preço:</label>".($currencyPos=='b'?$currency:"")." {$price} ".($currencyPos=='a'?$currency:"")."</li>";
            
        }
        $info .="</ul><div style='clear:both'></div>";
    }
    if ($paid) {
        $orderPymentInfo = get_payment_info($orderId);
        $amount = number_format($orderPymentInfo['amount'], 2);
        $subTotal = number_format($orderPymentInfo['subAmount'], 2);
        $tax = number_format($orderPymentInfo['tax'], 2);
        $taxRate = $orderPymentInfo['taxRate'];

        $info .="<h2>Resumo do Pedido</h2>";
        $info .="<ul class='summery'>";

        if (!empty($tax) && $tax>0) {
            $info.="<li><label>Sub Total:</label>".($currencyPos=='b'?$currency:"")." $subTotal ".($currencyPos=='a'?$currency:"")." </li>";
            $info.="<li><label>Imposto:</label>".($currencyPos=='b'?$currency:"")." $tax ".($currencyPos=='a'?$currency:"")." ( $taxRate % )</li>";
            $info.="<li class='total'><label>Total:</label>".($currencyPos=='b'?$currency:"")." $amount ".($currencyPos=='a'?$currency:"")."</li>";
        } else {
            $info.="<li class='total'><label>Total:</label>".($currencyPos=='b'?$currency:"")." $amount ".($currencyPos=='a'?$currency:"")."</li>";
        }
        $info .="</ul>";
    }


    $info.="<div style='clear:both'></div></div>";
    //print $info;
    return bw_apply_filter("order_summery", $info, $orderId);
}

function getAdminMail() {
    return getOption("email");
}

function getTimeMode() {

    return getOption("time_mode");
}

function getAdminPaypal() {

    $tt = array();
    $tt[0] = getOption("pemail");
    $tt[1] = getOption("pcurrency");
    return $tt;
}

function checkSpotsLeft($date, $serviceID=1) {
    $spots = 0; //print $serviceID;
    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces'); //check option for multiple timeBooking
    $availebleSpaces = $show_multiple_spaces ? getServiceSettings($serviceID, 'spaces_available') : 1;

    ##########################################################################################################################
    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY 
    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];
    
    $n = $schedule['countItems'];



    foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
        foreach ($v as $kk => $vv) { //$vv = time slot in above date 
            //echo $vv;
            if (isset($events[$k]) && in_array($vv, $events[$k])) {
                
            } elseif (isset($admins[$k]) && array_key_exists($vv, $admins[$k])) {

                //current timestamp
                $spacesBooked = $admins[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked;
                $currTime = strtotime(date("Y-m-d H:i"));
                //timestamp on start time interval
                $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                if ($spotTimeStart < $currTime) {
                    //this interval passed already.
                } elseif ($spacesAllowed >= 1) {
                    $spots+=$spacesAllowed;
                }
                
                if (isset($users[$k]) && array_key_exists($vv, $users[$k])) {

                    //current timestamp
                    $spacesBooked = $users[$k][$vv];
                    $spacesAllowed = $spacesAllowed - $spacesBooked;
                    $currTime = strtotime(date("Y-m-d H:i"));
                    //timestamp on start time interval
                    $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                    if ($spotTimeStart < $currTime) {
                        //this interval passed already.
                    } elseif ($spacesAllowed >= 1) {
                        $spots+=$spacesAllowed;
                    }
                }
            }elseif (isset($users[$k]) && array_key_exists($vv, $users[$k])) {

                //current timestamp
                $spacesBooked = $users[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked;
                $currTime = strtotime(date("Y-m-d H:i"));
                //timestamp on start time interval
                $spotTimeStart = strtotime(date("Y-m-d", strtotime($k)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
                if ($spotTimeStart < $currTime) {
                    //this interval passed already.
                } elseif ($spacesAllowed >= 1) {
                    $spots+=$spacesAllowed;
                }
            } else {
                $spots+=$availebleSpaces;
            }
        }
    }

    return $spots;
}

function checkForEvents($from, $to, $serviceID) {


    $sSQL = "SELECT * FROM bs_events WHERE serviceID='{$serviceID}' AND (
				(eventDate < '{$to}' AND eventDateEnd >= '{$to}') OR
				(eventDateEnd > '{$from}' AND eventDate <= '{$from}') OR
				(eventDate <= '{$from}' AND eventDateEnd >= '{$to}') OR
				(eventDate >= '{$from}' AND eventDateEnd <= '{$to}'))";
    //print $sSQL;
    $res = mysql_query($sSQL);
    if (mysql_num_rows($res) > 0) {
        return true;
    } else {
        return false;
    }
}

function checkForAdminReserv($from, $to, $serviceID) {
    //print $from." - ".$to."<br>";
    $qty = 0;
    $qtyTmp = 0;
    $recurring = array();
    $date = date("Y-m-d", strtotime($from)); //print $date;
    $sSQL = "SELECT * FROM bs_reserved_time WHERE serviceID='{$serviceID}' AND recurring=1 AND reserveDateTo>='{$to}'"; //print $sSQL;
    $res = mysql_query($sSQL);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {

            $startDate = date("Y-m-d", strtotime($row['reserveDateFrom']));
            $endDate = date("Y-m-d", strtotime($row['reserveDateTo']));
            $startTime = date("H:i", strtotime($row['reserveDateFrom']));
            $endTime = date("H:i", strtotime($row['reserveDateTo']));
            $st = $startDate;
            $en = $endDate;
            for ($i = $st; $i <= $date . " 23:59:59"; $i = date("Y-m-d", strtotime($i . " +{$row['repeate_interval']} {$row['repeate']}"))) {
                //print $i;
                $reserveDateFrom = $date . " " . $startTime;
                $reserveDateTo = $date . " " . $endTime;
                if ($date == date("Y-m-d", strtotime($i))) {

                    if (($reserveDateFrom < $to AND $reserveDateTo >= $to) OR
                            ($reserveDateTo > $from AND $reserveDateFrom <= $from) OR
                            ($reserveDateFrom <= $from AND $reserveDateTo >= $to) OR
                            ($reserveDateFrom > $from AND $reserveDateTo <= $to)) {
                        $recurring[$row['qty']] = array("start" => $reserveDateFrom, "end" => $reserveDateTo);
                        $qtyTmp+=intval($row['qty']);
                    }
                }

                //$i=$b;
            }
        }
    }
    //dump($recurring);
    //print $qtyTmp."-";
    $qty = $qtyTmp;
    $sSQL = "SELECT * FROM bs_reserved_time WHERE serviceID='{$serviceID}' AND recurring=0 AND(
				(reserveDateFrom < '{$to}' AND reserveDateTo >= '{$to}') OR
				(reserveDateTo > '{$from}' AND reserveDateFrom <= '{$from}') OR
				(reserveDateFrom <= '{$from}' AND reserveDateTo >= '{$to}') OR
				(reserveDateFrom >= '{$from}' AND reserveDateTo <= '{$to}'))";
    //print $sSQL;
    $res = mysql_query($sSQL);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {
            $qty +=$row['qty'];
        }
    } else {
        //return false;
    }

    return $qty;
}

function checkForUserReserv($from, $to, $serviceID) {

    $qty = 0;
    $sSQL = "SELECT bri.* FROM `bs_reservations_items` bri
			INNER JOIN bs_reservations br on bri.reservationID = br.id
				WHERE br.serviceID='{$serviceID}' AND (
				(bri.reserveDateFrom < '{$to}' AND bri.reserveDateTo >= '{$to}') OR
				(bri.reserveDateTo > '{$from}' AND bri.reserveDateFrom <= '{$from}') OR
				(bri.reserveDateFrom <= '{$from}' AND bri.reserveDateTo >= '{$to}') OR
				(bri.reserveDateFrom >= '{$from}' AND bri.reserveDateTo <= '{$to}'))
				AND (br.status='1' OR br.status='4')  
				ORDER BY bri.reserveDateFrom ASC";
    //print $sSQL;
    $res = mysql_query($sSQL);
    if (mysql_num_rows($res) > 0) {
        while ($row = mysql_fetch_assoc($res)) {
            $qty +=$row['qty'];
        }
        return $qty;
    } else {
        return false;
    }
}

/* function checkAdminReserve($date,$serviceID=1){
  $spots = 0;
  $int = getInterval($serviceID); //interval in minutes.
  $reservedArray=array();
  $seconds = 0;
  $availability = "";
  $tt = getStartEndTime(date("w",strtotime($date)),$serviceID); //week day of selected day.

  ##########################################################################################################################
  #	GET RESERVED TIME / RESERVED ARRAY
  //ADMIN RESERVED TIME
  $query="SELECT rti.*,rt.serviceID FROM bs_reserved_time_items rti INNER JOIN bs_reserved_time rt ON rt.id=rti.reservedID WHERE dateFrom LIKE '".$date."%' AND rt.serviceID={$serviceID} ORDER BY dateFrom ASC ";
  //$query="SELECT * FROM bs_reserved_time_items WHERE dateFrom LIKE '".$date."%' ORDER BY dateFrom ASC ";
  $result=mysql_query($query);
  if(mysql_num_rows($result)>0){
  while($rr=mysql_fetch_assoc($result)){
  //IF ADMIN SELECTED FROM 12:00 to 18:00 (more than 1 interval time between 2 spots)
  if(date("Y-m-d H:i", strtotime($rr["dateFrom"]." +".$int." minutes"))!=$rr["dateTo"]){
  for($a=date("Y-m-d H:i", strtotime($rr["dateFrom"]));$a<date("Y-m-d H:i", strtotime($rr["dateTo"]));$a=date("Y-m-d H:i", strtotime($a." +".$int." minutes"))){
  $reservedArray[date("Y-m-d", strtotime($a))][] = date("H:i", strtotime($a));
  }

  } else {
  $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][] = date("H:i", strtotime($rr["dateFrom"]));
  }
  # TODO - question: what if i had intervals was 30m, and we had bookings but then time passes and we changed interval to be 1h. What will be displayed.
  # on front - we can block past dates, however If somebody booked something in future, and we suddenly changed the interval time - for now we can
  # simply state in admin that if you changed it - you have to manually advice customers and manually change their bookings (1 by 1)
  }
  }
  return $reservedArray;
  } */

function getScheduleService($idService, $date) {
    $availabilityArr = array();
    $events = array();
    $admins = array();
    $users = array();
    $int = getInterval($idService);

    $dayOfWeek = date("w", strtotime($date));
    $sql = "SELECT * FROM bs_schedule
			WHERE idService='{$idService}' AND week_num='{$dayOfWeek}' ORDER BY startTime ASC"; //print $sql;
    $res = mysql_query($sql) or die(mysql_error() . "<br>" . $sql);
    $n = 0;
    while ($row = mysql_fetch_assoc($res)) {
        //$schedule[]=array("start"=>$row['startTime'],"end"=>$row['endTime']);

        $st = date("Y-m-d H:i", strtotime($date . " +" . $row['startTime'] . " minutes"));
        $et = date("Y-m-d H:i", strtotime($date . " +" . $row['endTime'] . " minutes"));
        $a = $st;

        //layout counter
        $b = date("Y-m-d H:i", strtotime($a . " +" . $int . " minutes")); //default value for B is start time.
        for ($a = $st; $b <= $et; $b = date("Y-m-d H:i", strtotime($a . " +" . $int . " minutes"))) {
            //echo "a: ".$a." // "."b: ".$b."<br />";
            if (checkForEvents($a, $b, $idService)) {
                $events[date("Y-m-d", strtotime($a))][] = date("H:i", strtotime($a));
            }
            $qtyAdminReservation = checkForAdminReserv($a, $b, $idService); //print "<br>".$qtyAdminReservation."<br>";
            if ($qtyAdminReservation > 0) {
                $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] = isset($admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]) ? $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyAdminReservation : $admins[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyAdminReservation;
            }
            $qtyUserReservation = checkForUserReserv($a, $b, $idService);
            if ($qtyUserReservation !== false) {
                //$users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] = $qtyUserReservation;
                $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))] = isset($users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]) ? $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+=$qtyUserReservation : $users[date("Y-m-d", strtotime($a))][date("H:i", strtotime($a))]+= $qtyUserReservation;
            }
            $availabilityArr[date("Y-m-d", strtotime($a))][] = date("H:i", strtotime($a));
            $a = $b;
            $n++;
        }
    }
    return array("availability" => $availabilityArr, "events" => $events, "admins" => $admins, "users" => $users, "countItems" => $n);
}

function getAvailableBookingsTable($date, $serviceID=1, $time=null, $qty=1) {
    ####################################### PREPARE AVAILABILITY TABLE ##############################################
    $int = getInterval($serviceID); //interval in minutes.

    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces'); //check option for multiple timeBooking
    $availebleSpaces = $show_multiple_spaces ? getServiceSettings($serviceID, 'spaces_available') : 1;
    $spot_price = getServiceSettings($serviceID, 'spot_price');
    $seconds = 0;
    $availability = "";

    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY 

    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];
    $n = $schedule['countItems'];
    //print dump($availabilityArr);
    //dump($admins);
    //dump($users);
    //print $n;
    $availability .= "<div class='timeEvCont'><table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign='top' width='270' style='text-align:center;'>";

    $n = ($n - ($n % 2)) / 2;
    $count = 0;
    foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
        //var_dump($availabilityArr);
        foreach ($v as $kk => $vv) { //$vv = time slot in above date 
            if ($time == null) {
                $time = array();
            }
            //current timestamp
            $currTime = strtotime(date("Y-m-d H:i"));
            //timestamp on start time interval
            $spotTimeStart = strtotime(date("Y-m-d", strtotime($date)) . " " . $vv . " -" . (5) . " minutes"); //5-minutes befo select interval in past
            if ($count == $n) {
                $availability .= "</td><td align='center' valign='top' width='270'>";
                $count = 0;
            }
            $availability .="<div class='timeItem'>";
            //select intervat to past
            if (isset($events[$k]) && in_array($vv, $events[$k])) {
                $availability .= date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . " - ".TXT_EVENT.".<br />";
            } elseif ($spotTimeStart < $currTime) {
                $availability .= date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . " - ".TXT_PAST.".<br />";
            } elseif ((isset($admins[$k]) && array_key_exists($vv, $admins[$k]))) {
                $spacesBookedUser = isset($users[$k][$vv])?$users[$k][$vv]:0;
                $spacesBooked = $admins[$k][$vv];
                $spacesAllowed = $availebleSpaces - $spacesBooked-$spacesBookedUser;
                if ($spacesAllowed >= 1) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    $txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    $availability .="<input type=\"checkbox\"" . (in_array($msm, $time) ? "checked" : "") . " value=\"" . $msm . "\" name=\"time[]\" rel=\"$spacesAllowed\"> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}<br />";
                } else {
                    $txt = $show_multiple_spaces ? '&nbsp;-&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    $availability .="<input type='checkbox' disabled><span style='color:#ccc'> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</span><br />";
                }
             } elseif ((isset($users[$k]) && array_key_exists($vv, $users[$k]))) {

                $spacesBooked = $users[$k][$vv];
                
                $spacesAllowed = $availebleSpaces - $spacesBooked;
                
                if ($spacesAllowed >= 1) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    $txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$spacesAllowed} ".SPACES.")</span>" : "";
                    $availability .="<input type=\"checkbox\"" . (in_array($msm, $time) ? "checked" : "") . " value=\"" . $msm . "\" name=\"time[]\" rel=\"$availebleSpaces\"> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}<br />";
                } else {
                    $txt = $show_multiple_spaces ? '&nbsp;-&nbsp;<span class="spaces">('.ZERO_SPACES2.')</span>' : "";
                    $availability .="<input type='checkbox' disabled><span style='color:#ccc'> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}</span><br />";
                }
                /*$msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                $txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>({$availebleSpaces} ".SPACES.")</span>" : "";
                $availability .="<input type=\"checkbox\"" . (in_array($msm, $time) ? "checked" : "") . " value=\"" . $msm . "\" name=\"time[]\" rel=\"$availebleSpaces\"> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}<br />";*/
            } else {
                $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                $txt = $show_multiple_spaces ? "&nbsp;-&nbsp;<span class='spaces'>(".$availebleSpaces.SPACES.")</span>" : "&nbsp;-&nbsp;<span class='spaces'>(1 ".SPACES.")</span>";
                $availability .="<input type=\"checkbox\"" . (in_array($msm, $time) ? "checked" : "") . " value=\"" . $msm . "\" name=\"time[]\" rel=\"$availebleSpaces\"> - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "{$txt}<br />";
            }
            $availability .="</div>";
            $count++;
        }
    };
    $currencyPos = getOption('currency_position');
    $cuurency = getOption('currency');
    $availability .="</td></tr></table><div class='qtyCont'>";
    $availability .=$show_multiple_spaces ?"<span>".TXT_QTY." <span class='spinner'><input type='text' name='qty' id='qty' value='$qty' style='width:40px'></span></span>" : "";
    $availability .=$spot_price ? "&nbsp;<span id='feeValue'>".$cuurency . "&nbsp;</span>" : "";
    $availability .="</div></div>";
    ##########################################################################################################################

    return $availability;
}

function checkQtyForTimeBooking($serviceID, $time, $date, $interval, $qty) {
    //print "$serviceID<br>$date<br>$interval<br>$qty";
    $availebleSpaces = getServiceSettings($serviceID, 'spaces_available');
    $error = false;
    $qtyTmp = 0;
    $sumQty = 0;
    foreach ($time as $k => $v) {
        $from = date("Y-m-d H:i:s", strtotime($date . " +" . $v . " minutes"));
        $to = date("Y-m-d H:i:s", strtotime($from . " +" . $interval . " minutes"));
        $adminQTY = checkForAdminReserv($from, $to, $serviceID);
        //print gettype($qtyTmp)."<br>";
        $sumQty = $adminQTY;
        /* $query="SELECT bs_reservations_items.* FROM `bs_reservations_items` 
          INNER JOIN bs_reservations on bs_reservations_items.reservationID = bs_reservations.id
          WHERE (bs_reservations.status='1' OR bs_reservations.status='4') AND
          bs_reservations_items.reserveDateFrom LIKE '".$startTime."%' AND
          bs_reservations_items.reserveDateTo LIKE '".$endTime."%' AND
          bs_reservations.serviceID={$serviceID} ORDER BY bs_reservations_items.reserveDateFrom ASC "; */
        //print $query;
        $sSQL = "SELECT bri.* FROM `bs_reservations_items` bri
			INNER JOIN bs_reservations br on bri.reservationID = br.id
				WHERE br.serviceID='{$serviceID}' AND (
				(bri.reserveDateFrom < '{$to}' AND bri.reserveDateTo >= '{$to}') OR
				(bri.reserveDateTo > '{$from}' AND bri.reserveDateFrom <= '{$from}') OR
				(bri.reserveDateFrom <= '{$from}' AND bri.reserveDateTo >= '{$to}') OR
				(bri.reserveDateFrom >= '{$from}' AND bri.reserveDateTo <= '{$to}'))
				AND (br.status='1' OR br.status='4')  
				ORDER BY bri.reserveDateFrom ASC";

        $result = mysql_query($sSQL);
        if (mysql_num_rows($result) > 0) {
            if (mysql_num_rows($result) > 1) {

                while ($row = mysql_fetch_assoc($result)) {
                    $qtyTmp+=$row['qty'];
                }
                $sumQty+=$qtyTmp + $qty;
                if ($sumQty > $availebleSpaces) {
                    $error = true;
                }
            } else {
                $qtyTmp = mysql_fetch_assoc($result);
                $sumQty+=$qtyTmp['qty'] + $qty;
                if ($sumQty > $availebleSpaces) {
                    $error = true;
                }
            }
        }
    }

    return $error;
}

function getScheduleTable($date, $serviceID=1) {
    global $baseDir;
    ####################################### PREPARE AVAILABILITY TABLE ##############################################
    $int = getInterval($serviceID); //interval in minutes.
    $reservedArray = array();
    $reservationData = array();
    $seconds = 0;
    $availability = "";
    $availebleSpaces = getServiceSettings($serviceID, 'spaces_available');
    $show_multiple_spaces = getServiceSettings($serviceID, 'show_multiple_spaces');
    ##########################################################################################################################
    #	GET RESERVED TIME / RESERVED ARRAY
    //$query="SELECT * FROM bs_events WHERE eventDate LIKE '%".$date."' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
    $query = "SELECT * FROM bs_events WHERE eventDate <= '" . $date . " 23:59' AND eventDateEnd >= '" . $date . " 00:00' AND serviceID='{$serviceID}' ORDER BY eventDate ASC ";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        $availability .= "<div class='eventWrapper'>";
        //we have events for this day!
        $event_num = mysql_num_rows($result);
        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
        $event_available = false;
        $event_count = 0;
        $text = "";
        $curr = getAdminPaypal();
        while ($row = mysql_fetch_assoc($result)) {
            $spaces_left = getSpotsLeftForEvent($row["id"]);
            $availability .="<div class='eventContainer'>";
            if ($row["path"] != "") {
                $availability .="<div class='eventImage' style='text-align:center;'><img src='" . $baseDir."/".$row["path"] . "' alt='" . $row["title"] . "' /></div>";
            }

            $availability .="<div class='event'><b>" . $row["title"] . "</b><br />";
            $availability .= TXT_EVENT_START." <b>" . getDateFormat($row["eventDate"]) . "&nbsp;&nbsp;" . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["eventDate"])) . "</b><br> ";
            $availability .= TXT_EVENT_ENDS." <b>" . getDateFormat($row["eventDateEnd"]) . "&nbsp;&nbsp;" . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["eventDateEnd"])) . "</b>.<br>";
            $availability .=$spaces_left . SPC_LEFT."</b><br />" . $row["description"];
            $q2 = "SELECT * FROM bs_reservations WHERE eventID='" . $row["id"] . "'";
            $res2 = mysql_query($q2);
            if (mysql_num_rows($res2) > 0) {
                $availability .= "<br /><br /><b>Attendees:</b>";
                while ($r2 = mysql_fetch_assoc($res2)) {
                    $availability .= "<br />" . $r2["name"] . " " . $r2["phone"] . " (" . $r2["qty"] . " ticket" . ($r2["qty"] > 1 ? "s" : "") . ")";
                }
            }
            $availability .="</div><br clear='all'></div>";
        }
        if ($event_count == 1) {
            
        } else if ($event_count > 1) {
            $text = "<p>".TXT_PLSSELECT."</p>";
        } else {
            $text = "";
        }

        $availability .="</div>";
    }





    //ADMIN RESERVED TIME
    $query = "SELECT rti.*,rt.serviceID FROM bs_reserved_time_items rti 
			INNER JOIN bs_reserved_time rt ON rt.id=rti.reservedID 
			WHERE dateFrom LIKE '" . $date . "%' AND rt.serviceID={$serviceID} ORDER BY dateFrom ASC ";
    //$query="SELECT * FROM bs_reserved_time_items WHERE dateFrom LIKE '".$date."%' ORDER BY dateFrom ASC ";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {
            //IF ADMIN SELECTED FROM 12:00 to 18:00 (more than 1 interval time between 2 spots)
            if (isset($reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))])) {
                $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))] = $rr["qty"] + $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))];
            } else {
                $reservedArray[date("Y-m-d", strtotime($rr["dateFrom"]))][date("H:i", strtotime($rr["dateFrom"]))] = $rr["qty"];
            }
            # TODO - question: what if i had intervals was 30m, and we had bookings but then time passes and we changed interval to be 1h. What will be displayed.
            # on front - we can block past dates, however If somebody booked something in future, and we suddenly changed the interval time - for now we can 
            # simply state in admin that if you changed it - you have to manually advice customers and manually change their bookings (1 by 1)
        }
    }

    //ACTUAL CUSTOMER BOOKINGS

    $query = "SELECT bs_reservations_items.*,bs_reservations.name,bs_reservations.phone,bs_reservations.id as rid FROM `bs_reservations_items` 
	INNER JOIN bs_reservations on bs_reservations_items.reservationID = bs_reservations.id 
	WHERE (bs_reservations.status='1' OR bs_reservations.status='4') AND 
	bs_reservations_items.reserveDateFrom LIKE '" . $date . "%' AND 
	bs_reservations.serviceID={$serviceID} ORDER BY bs_reservations_items.reserveDateFrom ASC ";
    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        while ($rr = mysql_fetch_assoc($result)) {
            if (isset($reservedArray[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))])) {
                $reservedArray[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))] = $rr["qty"] + $reservedArray[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))];
            } else {
                $reservedArray[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))] = $rr["qty"];
            }
            $reservationInfo = "<div><a href='bs-bookings-edit.php?id=" . $rr["rid"] . "'>" . $rr["name"] . "&nbsp; (phone:" . $rr["phone"] . "; qty=" . $rr['qty'] . ")</a></div>";
            if (isset($reservationData[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))])) {
                $reservationData[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))] =
                        $reservationData[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))] . $reservationInfo;
            } else {
                $reservationData[date("Y-m-d", strtotime($rr["reserveDateFrom"]))][date("H:i", strtotime($rr["reserveDateFrom"]))] = $reservationInfo;
            }
        }
    }
    //dump($reservationData);
    //dump($reservedArray);
    ##########################################################################################################################
    ##########################################################################################################################
    # PREPARE AVAILABILITY ARRAY 
    $schedule = getScheduleService($serviceID, $date);
    $availabilityArr = $schedule['availability'];
    $events = $schedule['events'];
    $n = $schedule['countItems'];
    $admins = $schedule['admins'];
    $users = $schedule['users'];
   
    //dump($availabilityArr);
    //$ww= date("w",strtotime($date));
    //$tt = getStartEndTime($ww,$serviceID);
    if (!count($availabilityArr)) {
        $availability .= ADM_NONWORKING;
    } else {
        $availability .= "<table width=\"500\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign='top'>";
        $n = ($n - ($n % 2)) / 2;
        $count = 0;
        foreach ($availabilityArr as $k => $v) { //$v= date  (  2010-10-05 )
            foreach ($v as $kk => $vv) { //$vv = time slot in above date 
                if ($count == $n) {
                    $availability .= "</td><td align='left' valign='top'>";
                    $count = 0;
                }
                $bookLink = "<a class='book' href='bs-reserve.php?serviceID={$serviceID}&reserveDateFrom={$date}&reserveDateTo={$date}&1_from_h=".date("H", strtotime($vv))."&1_from_m=".date("i", strtotime($vv))."&2_from_h=".date("H", strtotime($vv. " +" . $int . " minutes"))."&2_from_m=".date("i", strtotime($vv. " +" . $int . " minutes"))."' ></a>";
                if (isset($events[$k]) && in_array($vv, $events[$k])) {
                    $availability .="<tr class='schedule_na'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'>".TXT_EVENT2."</td></tr>";
                } elseif (isset($admins[$k]) && array_key_exists($vv, $admins[$k])) {
                    //print(checkForAdminReserv("$k $vv",date("Y-m-d H:m;i",strtotime("$k $vv +$int minutes")),$serviceID));
                    $spacesBookedUser = isset($users[$k][$vv])?$users[$k][$vv]:0;
                    $spacesBooked = $admins[$k][$vv];
                    $adminReserveData = "<br><a href='javascript:;'>Manual Reservation (qty = $spacesBooked)</a>";
                    $spacesAllowed = $availebleSpaces - $spacesBooked-$spacesBookedUser;
                    if ($spacesAllowed >= 1) {
                        $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                        $spacesAllowed = $show_multiple_spaces ? $spacesAllowed : 1;
                        $availability .="<tr class='schedule_av'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span> {$bookLink}". SPC_LEFT. $adminReserveData .(isset($reservationData[$k][$vv])?$reservationData[$k][$vv]:""). "</td></tr>";
                    } else {

                        $availability .="<tr class='schedule_av empty'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span>". SPC_LEFT. $adminReserveData . (isset($reservationData[$k][$vv])?$reservationData[$k][$vv]:"")."</td></tr>";
                    }
                } elseif (isset($users[$k]) || (isset($users[$k]) && !array_key_exists($vv, $users[$k]))) {
                    $msm = ((int) substr($vv, 0, 2)) * 60 + ((int) substr($vv, -2)); //minutes since miodnight of current day.
                    //$availebleSpaces;
                    $spacesBooked = $users[$k][$vv];
                    $spacesAllowed = $availebleSpaces - $spacesBooked;
                    $availability .="<tr class='schedule_av ".($spacesAllowed==0?"empty":"")."'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$spacesAllowed}</span>".($spacesAllowed?$bookLink:"") .SPC_LEFT . $reservationData[$k][$vv] . "</td></tr>";
                } else {
                    $availebleSpaces = $show_multiple_spaces ? $availebleSpaces : 1;
                    $availability .= "<tr class='schedule_av'><td width='100' valign='top' class='time'><div>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv)) . " - " . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($vv . " +" . $int . " minutes")) . "</div></td><td valign='top'><span class='space'>{$availebleSpaces}</span>". SPC_LEFT. $reservationData[$k][$vv] . "{$bookLink}</td></tr>";
                }


                $count++;
            }
        }
        $availability .="</td></tr></table>";
    }
    ##########################################################################################################################

    return $availability;
}

function getService($id, $field=null) {

    $sql = "SELECT * FROM bs_services WHERE id='{$id}'";
    $res = mysql_query($sql);
    if ($field == null) {
        return mysql_fetch_assoc($res);
    } else {
        $row = mysql_fetch_assoc($res);
        return $row[$field];
    }
}

function getServiceSettings($id, $field=null) {

    $sql = "SELECT * FROM bs_service_settings WHERE serviceID='{$id}'";
    $res = mysql_query($sql);
    if ($field == null) {
        return mysql_fetch_assoc($res);
    } else {
        $row = mysql_fetch_assoc($res);
        return $row[$field];
    }
}

function getBookingText($serviceID=1) {
    $tt = array();
    $maximumBookings = getMaxBooking($serviceID);
    $inter = getInterval($serviceID);
    $intervalConverted = $inter * $maximumBookings;
    //interval 15*X / 30*X / 45*X / 60*X /
    // example with 2 maximum bookings - 30 / 60 / 90 / 120
    if ($intervalConverted < 60) {
        //minutes
        if ($maximumBookings != 0 && $maximumBookings != 99) {
            $tt[0] = $intervalConverted . TXT_MINUTES_MAX;
        } else {
            $tt[0] = "";
        }
    } else {
        //hours
        $fullHours = ($intervalConverted - $intervalConverted % 60) / 60;
        $fullMinutes = $intervalConverted - ($fullHours * 60);
        if ($maximumBookings != 0 && $maximumBookings != 99) {
            $tt[0] = $fullHours . TXT_HOURSS . ($fullMinutes > 0 ? TXT_AND . $fullMinutes . TXT_MINUTES : "") . TXT_MAX;
        } else {
            $tt[0] = "";
        }
    }


    $minimumBookings = getMinBooking($serviceID);
    $intervalConverted = $inter * $minimumBookings;
    if ($intervalConverted < 60) {
        //minutes
        if ($minimumBookings != 0 && $minimumBookings != 99) {
            $tt[1] = $intervalConverted . TXT_MINUTES_MIN;
        } else {
            $tt[1] = "";
        }
    } else {
        //hours
        $fullHours = ($intervalConverted - $intervalConverted % 60) / 60;
        $fullMinutes = $intervalConverted - ($fullHours * 60);
        if ($minimumBookings != 0 && $minimumBookings != 99) {
            $tt[1] = $fullHours . TXT_HOURSS . ($fullMinutes > 0 ? TXT_AND . $fullMinutes . TXT_MINUTES : "") . TXT_MIN ;
        } else {
            $tt[1] = "";
        }
    }
    return $tt;
}

function getBooking($id, $field=null) {

    $q = "SELECT bs_reservations.*,bs_services.name as sname FROM `bs_reservations` 
		INNER JOIN bs_services ON bs_services.id=bs_reservations.serviceID
		WHERE bs_reservations.id='{$id}'";
    $res = mysql_query($q);
    if (mysql_num_rows($res) > 0) {
        $rr = mysql_fetch_assoc($res);
        if (empty($field)) {
            return $rr;
        } else {
            return $rr[$field];
        }
    }
    return false;
}

function getPricePerSpot($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["spot_price"];
}

function getMaxQtyEvent($id) {
    $q = "SELECT max_qty FROM bs_events WHERE id='" . $id . "'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["max_qty"];
}

function getFirstDay($serviceID=1) {
    $q = "SELECT * FROM bs_service_settings WHERE serviceId ='{$serviceID}'";
    $res = mysql_query($q);
    $rr = mysql_fetch_assoc($res);
    return $rr["startDay"];
}

function uploadFile($inputFile, $sFolderPictures) {
    $image_path = $inputFile['tmp_name'];
    $photoFileNametmp = $inputFile['name'];
    $fileNamePartstmp = explode(".", $photoFileNametmp);
    $fileExtensiontmp = strtolower(end($fileNamePartstmp)); // part behind last dot
    // $arrAllow=array("jpeg", "jpg", "png", "gif");//, "BMP", "TIFF"
    //	if (!in_array($fileExtensiontmp, $arrAllow)) { 
    //	$err.= "Picture's extension should be .jpg, .jpeg, .png, or .gif<br />";
    //	}
    if ($inputFile['size'] > 20971520) {
        $ssize = sprintf("%01.2f", $inputFile['size'] / 1048576);
        $err = "Your file is " . $ssize . ". Max file size is 20 MB.";
    }
    if (!isset($err)) {
        // $newFile=$_SERVER['DOCUMENT_ROOT'].$sFolderPictures;//print $newFile;
        $newFile = $sFolderPictures; //print $newFile;
        $ret = move_uploaded_file($inputFile['tmp_name'], $newFile);
        if (!$ret) {
            ?>
            <table width="100%"><tr><td class="error" colspan="2">Upload failed. No file received. Check your installation directory in dbconnect.php</td></tr></table>      <?php
        } else {
            $imgPath = $sFolderPictures;
        }
    } else {
        ?><table width="100%"><tr><td class="error" colspan="2">Upload failed. No file received. Check your installation directory in dbconnect.php</td></tr></table>
    <?php
    }
    if (file_exists($inputFile['tmp_name'])) {
        @unlink($inputFile['tmp_name']);
    }
    return $imgPath;
}


function getEventList($eventID=null, $qty=null) {
    $availability = ""; //print "dd".$qty;


    $query = "SELECT * FROM bs_events WHERE id={$eventID} ORDER BY eventDate ASC ";
    
    $currencyPos = getOption('currency_position');
    $currency = getOption('currency');


    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        $availability .= "<div class='eventWrapper'>";
        //we have events for this day!
        $event_num = mysql_num_rows($result);
        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
        $event_available = false;
        $event_count = 0;
        $text = "";
        $curr = getAdminPaypal();
        while ($row = mysql_fetch_assoc($result)) {
            $spaces_left = getSpotsLeftForEvent($row["id"]);
            $availability .="<div class='eventContainer'>";

            $availability .="<div class='eventCheckbox'>";
            if ($spaces_left > 0) {

                $availability .="<input type='hidden' name='eventID' value='" . $row["id"] . "' >";
            } else {
                $availability .= "&nbsp;";
            }
            $availability .="</div>";
            $availability .="<div class='eventTitle'><b>" . $row["title"] . "</b></div>";
            $availability .= "<table class='evntCont' width='100%'><tr><td width='80%' valign='center'><div class='eventDescr'>";

            $availability .=TXT_EVENT_START." <span>" . getDateFormat($row["eventDate"]) . "&nbsp;&nbsp;" . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($row["eventDate"])) . "</span><br>
									".TXT_EVENT_ENDS." <span>" . getDateFormat($row["eventDateEnd"])  . "&nbsp;&nbsp;". date((getTimeMode()) ? " g:i a" : "H:i", strtotime($row["eventDateEnd"])) . "</span>
					<br />";
            if ($row["path"] != "") {
                $availability .="<div class='eventImage'><img src='." . $row["path"] . "' alt='" . $row["title"] . "' /></div>";
            }
            $availability .=$row["description"] . "</div><td>";
            $availability .="<td class='brd_l'><div class='spots'><span class='spot'>" . $spaces_left . "</span><span class='spot1'>".TXT_SPOTS_LEFT."</span></div></td>";
            if ($row["allow_multiple"] == "1") {
                $qty_max = (getMaxQtyEvent($row["id"]) > $spaces_left) ? $spaces_left : getMaxQtyEvent($row["id"]);
                $availability .= "<td class='brd_l'><div class='tickets'>
                    <select name='qty_" . $row["id"] . "' onchange='updatePrice(this)'>";
                $availability .="<option value='1'>".TXT_FUNC_QTY."</option>";
                for ($i = 1; $i <= $qty_max; $i++) {
                    $availability .= "<option value='" . $i . "' " . (!empty($qty) && $i == $qty && $row["id"] == $eventID ? "selected='selected'" : "") . ">" . $i . "</option>";
                }
                $availability .= "</select></div></td>";
            }
            if ($row["payment_required"] == "1") {

                $price = $row["entryFee"];
                if (getOption('enable_tax')) {
                    $price = $price + ($price * getOption('tax') / 100);
                }
                $availability .= "<td class='brd_l'><div class='fee'><b> ".($currencyPos=='b'?$currency:"")." <span  id='price'>" . number_format($price, 2) . "</span> ".($currencyPos=='a'?$currency:"")."</div></td>";
            } else {
                $availability .= "<td class='brd_l'><div class='fee'><span style='color:#0FA1D2'>".TXT_FUNC_FREE."</span></div></td>";
            }

            $availability .="</tr></table>";
            $availability .="<br clear='all'><div class='social'>" . getSocial($row["id"]) . "</div>";
            $availability .="</div>";
        }
        if ($event_count == 1) {
            
        } else if ($event_count > 1) {
            $text = "<p>".TXT_PLSSELECT."</p>";
        } else {
            $text = "";
        }

        $availability .="</div>";
    }

    return $availability;
}

function getEventsList($date, $serviceID=1, $eventID=null, $selEvent=null, $qty=null) {
    $availability = ""; //print "dd".$qty;

    if (!empty($eventID)) {
        $query = "SELECT * FROM bs_events WHERE id={$eventID} ORDER BY eventDate ASC ";
    } else {
        $query = "SELECT * FROM bs_events WHERE eventDate LIKE '%" . $date . "%' AND serviceID={$serviceID} ORDER BY eventDate ASC ";
    }

    $result = mysql_query($query);
    if (mysql_num_rows($result) > 0) {
        $availability .= "<div class='eventWrapper'>";
        //we have events for this day!
        $event_num = mysql_num_rows($result);
        //we need to check if at least one event has spaces. if yes then { $bgClass="cal_reg_on";  } else { $bgClass="cal_reg_off"; }
        $event_available = false;
        $event_count = 0;
        $text = "";
        $curr = getAdminPaypal();
        while ($row = mysql_fetch_assoc($result)) {
            $spaces_left = getSpotsLeftForEvent($row["id"]);
            $availability .="<div class='eventContainer'>";

            $availability .="<div class='eventCheckbox'>";
            if ($spaces_left > 0) {
                if (!empty($selEvent)) {
                    $availability .="<input type='radio' name='eventID' value='" . $row["id"] . "' " . ($selEvent == $row['id'] ? "checked" : "") . ">";
                } else {
                    $availability .="<input type='radio' name='eventID' value='" . $row["id"] . "' checked>";
                }
            } else {
                $availability .= "&nbsp;";
            }
            $availability .="</div>";
            $availability .="<div class='eventTitle'><b>" . $row["title"] . "</b></div>";
            $availability .= "<table class='evntCont' width='100%'><tr><td width='80%' valign='center'><div class='eventDescr'>";
            if ($row["path"] != "") {
                $availability .="<div class='eventImage'><img src='." . $row["path"] . "' alt='" . $row["title"] . "' /></div>";
            }
            $availability .="Event starts at <span>" . date((getTimeMode()) ? "g:i a" : "H:i", strtotime($row["eventTime"])) . "</span><br />" . $row["description"] . "</div><td>";
            $availability .="<td class='brd_l'><div class='spots'><span class='spot'>" . $spaces_left . "</span><span class='spot1'>".TXT_SPOTS_LEFT."</span></div></td>";
            if ($row["allow_multiple"] == "1") {
                $qty_max = (getMaxQtyEvent($row["id"]) > $spaces_left) ? $spaces_left : getMaxQtyEvent($row["id"]);
                $availability .= "<td class='brd_l'><div class='tickets'><select name='qty_" . $row["id"] . "'>";
                $availability .="<option value='1'>".TXT_FUNC_QTY."</option>";
                for ($i = 1; $i <= $qty_max; $i++) {
                    $availability .= "<option value='" . $i . "' " . (!empty($qty) && $i == $qty && $row["id"] == $selEvent ? "selected='selected'" : "") . ">" . $i . "</option>";
                }
                $availability .= "</select></div></td>";
            }
            if ($row["payment_required"] == "1") {
                $price = $row["entryFee"];
                if (getOption('enable_tax')) {
                    $price = $price + ($price * getOption('tax') / 100);
                }
                $availability .= "<td class='brd_l'><div class='fee'><b> " . getOption('currency') . " " . number_format($price, 2) . "</div></td>";
            } else {
                $availability .= "<td class='brd_l'><div class='fee'><span style='color:#0FA1D2'>".TXT_FUNC_FREE."</span></div></td>";
            }

            $availability .="</tr></table>";
            $availability .="<br clear='all'><div class='social'>" . getSocial($row["id"]) . "</div>";
            $availability .="</div>";
        }
        if ($event_count == 1) {
            
        } else if ($event_count > 1) {
            $text = "<p>".TXT_PLSSELECT."</p>";
        } else {
            $text = "";
        }

        $availability .="</div>";
    }

    return $availability;
}

function getSocial($eventId) {
    global $baseDir;
    $query = "SELECT * FROM bs_events WHERE id={$eventId} ORDER BY eventDate ASC "; //print $_SERVER["HTTP_HOST"];
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $url = $_SERVER["HTTP_HOST"] . $baseDir . "event.php?eventID={$row['id']}";

    $soc = '<table><tr>';

    $soc.='<td><div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="' . $url . '" send="true" layout="button_count" width="150" show_faces="true" font=""></fb:like></td>';

    //$soc.='<td><iframe src="http://www.facebook.com/plugins/like.php?href&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:25px;" allowTransparency="true"></iframe></td>';

    $soc.='<td><div style="display:inline-block">

   <a href="http://twitter.com/share?url=' . urlencode("http://" . $url) . "&amp;via={$row['title']}" . '" data-url="' . $_SERVER["HTTP_HOST"] . '" data-counturl="' . $_SERVER["HTTP_HOST"] . '" class="twitter-share-button">Tweet</a>

</div></td>';
    $soc.="<td><g:plusone href=\"{$url}\"size=\"medium\"></g:plusone></td>";
    $soc.="</tr></table>";
    return $soc;
}

function randomPassword
(
//autor: Femi Hasani [www.vision.to]
$length=7, //string length
        $uselower=1, //use lowercase letters
        $useupper=1, // use uppercase letters
        $usespecial=1, //use special characters
        $usenumbers=1, //use numbers
        $prefix=''
) {
    $key = $prefix;
// Seed random number generator
    srand((double) microtime() * rand(1000000, 9999999));
    $charset = "";
    if ($uselower == 1)
        $charset .= "abcdefghijkmnopqrstuvwxyz";
    if ($useupper == 1)
        $charset .= "ABCDEFGHIJKLMNPQRSTUVWXYZ";
    if ($usenumbers == 1)
        $charset .= "0123456789";
    //if ($usespecial == 1) $charset .= "#%^*()_+-{}][";
    if ($usespecial == 1)
        $charset .= "#*_+-";
    while ($length > 0) {
        $key .= $charset[rand(0, strlen($charset) - 1)];
        $length--;
    }
    return $key;
}
function get_month_list(){
    $monthList = array();
for($i=1;$i<13;$i++){
	$r = date("F",strtotime("2000-".$i."-01"));
	$monthList[date("F",strtotime("2000-".$i."-01"))]=constant($r);
}
for($i=1;$i<13;$i++){
	$r = date("M",strtotime("2000-".$i."-01"));
	$monthList[date("M",strtotime("2000-".$i."-01"))]=constant($r);
}
for($i=1;$i<8;$i++){
	$r = date("D",strtotime("22-01-2012 +$i days"));
	$monthList[date("D",strtotime("22-01-2012 +$i days"))]=constant($r);
}
return $monthList;
}

function getShortWeek($n) {
    $monthList = get_month_list();
    return strtr(date("D", strtotime("22-01-2012 +$n days")), $monthList);
}

#####################################################################################################

function getLangList() {
    $langList = array();
    
    $path = MAIN_PATH . "\languages";
    $path1 = MAIN_PATH . "/languages";
    if(is_dir($path)){
            foreach (scandir($path) as $lang) {
                //print $lang;
                if (strpos($lang, "lang") !== false) {
                    $langList[] = substr($lang, 0, strpos($lang, "."));
                }
            }
        }elseif(is_dir($path1)){
           
            foreach (scandir($path1) as $lang) {
                //print $lang;
                if (strpos($lang, "lang") !== false) {
                    $langList[] = substr($lang, 0, strpos($lang, "."));
                }
            }
        }
    
    return $langList;
}

function _getDate($date) {
    $monthList = get_month_list();

    return strtr($date, $monthList);
}

function getDateFormat($date) {
    $monthList = get_month_list();

    return strtr(date(getOption('date_mode'), strtotime($date)), $monthList);
}


function checkSchedule($reserveDateFrom, $reserveDateTo, $x1_from, $x2_from, $serviceID, $qty=1, $id=null) {

    $a =  $reserveDateFrom;
    $b = date("Y-m-d", strtotime($a . " +1 day"));
    for ($a = $reserveDateFrom; $a <= $reserveDateTo; $b = date("Y-m-d", strtotime($a . " +1 day"))) {
        
        //print $a."<br>".$b."<br><br>";
        $where = $id != null ? " AND bs_reserved_time.id !={$id}" : "";
        $sSQL = "SELECT * FROM bs_reserved_time
				WHERE bs_reserved_time.recurring=0 AND bs_reserved_time.serviceID='{$serviceID}'{$where} AND (
			(reserveDateFrom < '{$a} {$x2_from}:00' AND reserveDateTo >= '{$a} {$x2_from}:00') OR
			(reserveDateTo > '{$a} {$x1_from}:00' AND reserveDateFrom <= '{$a} {$x1_from}:00') OR
			(reserveDateFrom <= '{$a} {$x1_from}:00' AND reserveDateTo >= '{$a} {$x2_from}:00') OR
			(reserveDateFrom >= '{$a} {$x1_from}:00' AND reserveDateTo <= '{$a} {$x2_from}:00'))"; //print $sSQL;
        $res = mysql_query($sSQL);
        if (mysql_num_rows($res) > 0) {
            //print "yes";
            return false;
        }
        $a = $b;
    }
    return true;

    //$result = mysql_query($sSQL) or die("err: " . mysql_error().$sSQL);
}

function sendMail($email, $subject, $template, $data=null) {
    global $baseDir;
    $headers = "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "From: '" . $_SERVER['SERVER_NAME'] . "' <contato@" . str_replace('www.', '', $_SERVER['SERVER_NAME']) . "> \n";

    if ($data == null) {
        $message = $template;
    } else {
        $data ['{%server%}'] = $_SERVER['SERVER_NAME'];
        foreach ($data as $k => $v) {
            $$k = $v;
        }
        ob_start();
        include MAIN_PATH . "/emailTemplates/{$template}";
        $templ = ob_get_contents();
        ob_clean();

        //$templ=file_get_contents($_SERVER["DOCUMENT_ROOT"].$baseDir."emailTemplates/{$template}");
        $message = strtr($templ, $data);
    }
    $message.="<br><br>Kind Regards,<br><a href='http://{$_SERVER['SERVER_NAME']}'>{$_SERVER['SERVER_NAME']}</a>";
    //$message.="<br><br><a href='http://{$_SERVER['SERVER_NAME']}'><img src='http://{$_SERVER['SERVER_NAME']}/images/logo_sm.png'></a>";

    mail($email, $subject, $message, $headers);
}

function get_menu() {

    bw_do_action('get_menu');
}

function bw_get_page($page) {
    //print "bw_get_page_$page";
    bw_do_action("bw_get_page_$page");
    return true;
}

function get_admin_page($page) {
    //print "bw_get_page_$page";
    bw_do_action("get_admin_page_$page");
    return true;
}

/*
  function print_title(){
  $prefix = ' There';
  $title = "Hello" .$prefix;
  $title = bw_apply_filter("print_title", $title,$prefix);
  return $title;
  }
 */

function getBookingDetailsText($orderID) {
    $text="";
    $q = "SELECT * FROM  bs_reservations_items WHERE reservationID ='{$orderID}'";
    $res = mysql_query($q);
    while ($rr = mysql_fetch_assoc($res)) {
        $text.="[ " . getDateFormat($rr["reserveDateFrom"]) . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($rr["reserveDateFrom"])) . " - " .
                getDateFormat($rr["reserveDateTo"]) . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($rr["reserveDateTo"])) . " ]";
    }
    return $text;
}

function get_payment_info($orderID) {
    $amount = 0;
    $tax = 0;
    $taxRate = getOption("enable_tax") ? getOption("tax") : 0;
    $bookingInfo = getBooking($orderID);
    $qty = $bookingInfo['qty'];

    if (empty($bookingInfo['eventID'])) {
        $price = getServiceSettings($bookingInfo['serviceID'], "spot_price");

        $sql = "SELECT COUNT(*) as spots FROM bs_reservations_items WHERE reservationID ='{$orderID}'";
        $result = mysql_query($sql);
        $spots = mysql_result($result, 0, 'spots');

        $subAmount = $spots * $price * $qty;
        $tax = $subAmount * $taxRate / 100;
        $amount = $subAmount + $tax;
        $paymentInfo = TXT_FUNC_PAYMENT_FOR." " . getBookingDetailsText($orderID);
    } else {
        $sql = "SELECT * FROM bs_events WHERE id ='{$bookingInfo['eventID']}'";
        $result = mysql_query($sql);
        $eventInfo = mysql_fetch_assoc($result);

        if ($eventInfo['payment_required'] == 1 && !empty($eventInfo['entryFee'])) {
            $subAmount = $eventInfo['entryFee'] * $qty;
            $tax = $subAmount * $taxRate / 100;
            $amount = $subAmount + $tax;
            $paymentInfo = TXT_FUNC_PAYMNT_EVENT . " '{$eventInfo['title']}' on " . getDateFormat($eventInfo["eventDate"]) . date((getTimeMode()) ? " g:i a" : " H:i", strtotime($eventInfo["eventDate"]));
        }
    }
    return array(
        "tax" => $tax,
        "subAmount" => $subAmount,
        "taxRate" => $taxRate,
        "amount" => $amount,
        "paymentInfo" => $paymentInfo
    );
}

function payment_paypal($pre_text, $orderID, $type=null) {

    $payment_info = get_payment_info($orderID);


    $paypal_form = $pre_text . TXT_FUNC_ALMOST_DONE;//($type==null?TXT_FUNC_ALMOST_DONE:"");
    if (IS_WP_PLUGIN == '1' && $type!='pay') {
        $paypal_form .= '<br><input type="button" value="'. TXT_FUNC_CLICK_HERE_TO_PAY .'" onclick="top.redirect(\'http://' . MAIN_URL . 'paypal.processing.php?orderID=' . $orderID . '\')">';
        
    } else {
        //CREATE PAYPAL PROCESSING
        require_once(MAIN_PATH . '/includes/paypal.class.php');
        $paypal = new paypal_class;
        $paypal->add_field('business', getOption('pemail'));
        //$scrpt = str_replace("booking.processing.php", "paypal.ipn.php", $_SERVER['SCRIPT_NAME']);
        //$scrpt = str_replace("booking.event.processing.php", "paypal.ipn.php", $_SERVER['SCRIPT_NAME']);
        $scrpt = MAIN_URL . 'paypal.ipn.php';
        $paypal->add_field('return', "http://" . $scrpt . '?action=success');
        $paypal->add_field('cancel_return', "http://" . $scrpt . '?action=cancel');
        $paypal->add_field('notify_url', "http://" . $scrpt . '?action=ipn');
        $paypal->add_field('item_name_1', $payment_info['paymentInfo']);
        $paypal->add_field('amount_1', number_format($payment_info['subAmount'], 2));
        $paypal->add_field('item_number_1', "0001");
        $paypal->add_field('quantity_1', '1');
        $paypal->add_field('custom', $orderID);
        $paypal->add_field('upload', 1);
        $paypal->add_field('cmd', '_cart');
        $paypal->add_field('txn_type', 'cart');
        if (!empty($payment_info['tax'])) {
            $paypal->add_field('tax_cart', $payment_info['tax']);
        }
        $paypal->add_field('num_cart_items', 1);
        $paypal->add_field('payment_gross', number_format($payment_info['subAmount'], 2));
        $paypal->add_field('currency_code', getOption('pcurrency'));
        $paypal_form .= "<form method=\"post\" name=\"paypal_form\" id=\"paypal_form\"";
        $paypal_form .= "action=\"" . $paypal->paypal_url . "\">\n";
        foreach ($paypal->fields as $name => $value) {
            $paypal_form .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
        }
        $paypal_form .= '<center><h4>Escolha a sua forma de pagamento</h4></center>';
        $paypal_form .= '<table style="width:100%" border="0">
                            <tr align="center">
                                <th><img src="./images/paypal-logo-2.svg" border="0" width="120" height="80" /></th>
                                <th><img src="./images/pix-banco-central-logo.svg" border="0" width="150" height="90" /></th>
                                <th><img src="./images/mercado-pago-logo.svg" border="0" width="120" height="80" /></th>
                            </tr>
                            <tr align="center">
                                <td>
                                   <input type="submit" class="ui-button ui-widget ui-corner-all" style="background-color:#39b54a; color:#FFFFFF;" value="' . TXT_FUNC_CLICK_HERE_TO_PAY . '"></center>
                                </td>
                                <td>
                                    <input type="submit" class="ui-button ui-widget ui-corner-all" style="background-color:#39b54a; color:#FFFFFF;" formaction="pgtopix.php?orderID=' . $orderID . '" value="' . TXT_FUNC_CLICK_HERE_TO_PAY . '"></center>
                                </td>
                                <td>                                
                                    <input type="submit" class="ui-button ui-widget ui-corner-all" style="background-color:#39b54a; color:#FFFFFF;" formaction="pgtomercadopago.php?orderID='. $orderID .'" value="' . TXT_FUNC_CLICK_HERE_TO_PAY . '"></center>
                                </td>
                            </tr>
                        </table>';
        $paypal_form .= "</form>\n";
    }
    return $paypal_form;
}

function payment_invoice($pre_text, $orderID,$type) {
    $text = $pre_text . TXT_FUNC_THANK_YOU_MSG;
    if(IS_WP_PLUGIN!='1'){
        $text .='<a href="http://'. MAIN_URL.'index.php">'.BEP_15.'</a>';
    }
    return $text;
}

function do_payment($orderID, $payment_method,$type=null) {
    $value = "";
    bw_add_action("do_payment", "payment_" . $payment_method, $orderID,$type);
    return bw_apply_filter("do_payment", $value, $orderID,$type);
}
?>