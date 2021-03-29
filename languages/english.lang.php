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

    /******************************************************************************
      BOOKING SYSTEM INTERFACE TEXT STRINGS (BOTH FRONT + ADMIN)
    ******************************************************************************/
    define(January,"Janeiro");
    define(February,"Fevereiro");
    define(March,"Março");
    define(April,"Abril");
    define(May,"Maio");
    define(June,"Junho");
    define(July,"Julho");
    define(August,"Agosto");
    define(September,"Setembro");
    define(October,"Outubro");
    define(November,"Novembro");
    define(December,"Dezembro");

    define(Jan,"Jan");
    define(Feb,"Fev");
    define(Mar,"Mar");
    define(Apr,"Abr");
    define(May,"Mai");
    define(Jun,"Jun");
    define(Jul,"Jul");
    define(Aug,"Ago");
    define(Sep,"Set");
    define(Oct,"Out");
    define(Nov,"Nov");
    define(Dec,"Dez");

    define(Mon,"Seg");
    define(Tue,"Ter");
    define(Wed,"Qua");
    define(Thu,"Qui");
    define(Fri,"Sex");
    define(Sat,"Sab");
    define(Sun,"Dom");

    #index_small.php
    define(SML_PREV,"Anterior");
    define(SML_NEXT,"Posterior");
    define(SML_D0,"Do");
    define(SML_D1,"Se");
    define(SML_D2,"Te");
    define(SML_D3,"Qa");
    define(SML_D4,"Qi");
    define(SML_D5,"Se");
    define(SML_D6,"Sa");
    define (EVENTS_LIST_TITLE,"Eventos para");

    # admin navigation items
    define(MENU1,"Agendar");
    define(MENU2,"Reservas");
    define(MENU3,"Eventos");
    define(MENU4,"Lista de eventos");
    define(MENU5,"Adicionar Eventos");
    define(MENU6,"Reservas manuais");
    define(MENU7,"Adicionar Reserva Manual");
    define(MENU8,"Lista de Reservas");
    define(MENU9,"Serviços");
    define(MENU10,"Lista de Serviços");
    define(MENU11,"Adicionar Serviço");



    # booking-nojs.php
    # General
    define(APPLICATION_TITLE,"Sistema de Agendamento Online");//(used multiple times)
    define(GENERIC_QUERY_FAIL,"Oopsy, erro ocorreu ao tentar executar a consulta.");
    define(BOOKING_FRM_CONFIRMED,"Confirmado");//(used multiple times)
    define(BOOKING_FRM_NOTCONFIRMED,"Não Confirmado");//(used multiple times)
    define(BOOKING_FRM_USERCANCELLED,"Usuário Cancelado");//(used multiple times)
    define(BOOKING_FRM_CANCELLED,"Cancelado");//(used multiple times)
    define(BOOKING_FRM_PAID, "Pago");//(used multiple times)
    define(NO_ACCESS, "Você não tem acesso para esta página");//(used multiple times)

    # Emails which are sent from admin side
    define(EMAIL_SUBJ_CONFIRMED,"Reserva Confirmada!");
    define(EMAIL_SUBJ_CANCELLED,"Reserva Cancelada!");
    define(ADM_MSG1,"E-mail de confirmação de Reserva enviado para o cliente!");
    define(ADM_MSG2,"E-mail de cancelamento de Reserva enviado para o cliente!");

    # admin-index.php
    define(ADMIN_WELCOME,"Bem vindo ao painel administrativo do Sistema de Agendamento Online");
    define(ADMIN_WELCOME_TEXT,"Por favor, utilize o menu para confirmar/excluir/adicionar reservas.<br />Você também deve ir para as configurações e definir a disponibilidade dos dias/horários predefinidos no momento da reserva.<br />Use &quot;Adicionar reserva manual&quot; para adicionar reserva manual no banco de dados ou se você quiser reservar alguma data manualmente.");

    # admin.php
    define(LOGIN_USERNAME,"Usuário:");
    define(LOGIN_PASSWORD,"Senha:");
    define(LOGIN_FORGOT,"Esqueceu a senha?");
    define(LOGIN_ERROR1,"Usuário e/ou senha em branco.");
    define(LOGIN_ERROR2,"Usuário e/ou senha incorreto");

    # bs-bookings-edit.php
    define(BOOKING_SUCC,"Reserva foi atualizado com sucesso!");
    define(BOOKING_TIME_UPDATED,"Hora da Reserva foi atualizado!");
    define(BOOKING_TIME_DELETED,"Hora da Reserva foi deletado!");
    define(TBL_DATE,"Data");
    define(TBL_TIME1,"Hora de");
    define(TBL_TIME2,"até");
    define(TBL_QTY,"Qtd: ");//(used multiple times)
    define(BOOKING_EDIT_TITLE,"Edite Site de reserva");
    define(BOOKING_FRM_DATE,"Data da reserva foi colocado:");
    define(BOOKING_FRM_SERVICE,"Serviço:");//(used multiple times)
    define(BOOKING_FRM_STATUS,"Status:");//(used multiple times)
    define(BOOKING_FRM_SELECT, "Seleciona");
    define(BOOKING_FRM_QTY, "Qtd: ");//(used multiple times)
    define(BOOKING_FRM_NAME, "Nome");// (used multiple times)
    define(BOOKING_FRM_EMAIL, "E-mail");//(used multiple times)
    define(BOOKING_FRM_PHONE, "Fone");//(used multiple times)
    define(BOOKING_FRM_COMMENTS, "Comentátios");//(used multiple times)
    define(BOOKING_FRM_BOOKEDDATES, "Datas Reservadas:");
    define(BOOKING_FRM_NOTE1, "Atenção: para apagar um período de tempo reservado você precisa deixar vazios os dois campos &quot;DE&quot; e &quot;ATÉ&quot;");

    # bs-bookings.php
    define(ADM_MSG3,"Reservas selecionadas foram apagadas.");
    define(ADM_MSG4,"0 reservas encontradas no banco de dados");
    define(ADM_BTN_DELETE,"Apagar Seleção");
    define(ADM_BTN_SUBMIT,"Enviar");
    define(PAGE_TITLE1,"Reservas");
    define(BOOKING_LST_NAME,"Nome");
    define(BOOKING_LST_EVENT,"Evento");
    define(BOOKING_LST_EMAIL,"Email");
    define(BOOKING_LST_PHONE,"Fone");
    define(BOOKING_LST_ON,"Reserva em");
    define(BOOKING_LST_DATES,"Datas de Reserva");
    define(BOOKING_LST_SPACES,"Espaços");//(used multiple times)
    define(BOOKING_LST_STATUS,"Status");

    # bs-bookings_event-edit.php
    define(PAGE_TITLE2,"Edit Website Event Booking");
    define(BOOKING_LST_EVENTS,"Events");
    define(DATE_BOOK_PLC,"Date booking was placed:");
    define(EVENT_LIST,"&laquo; Back To Event");

    # bs-events-add.php
    define(ADM_MSG5,"Booking was successfully added!");
    define(ADM_MSG6,"Some required fields are empty!");
    define(EVENT_SUC_MSG,"Event was successfully updated!");
    define(EVENT_SUC_UPD,"Event was successfully created!");
    define(EVENT_STR_TIME,"Event start time can't be after end time!");
    define(STAT_UPDT,"Statuses updated!");
    define(NTFC_ESENT,"Notification emails were sent.");
    define(SEL_ATT_DEL,"Selected attendee was deleted.");
    define(NO_FOUND,"0 attendees found in database");
    define(EVENT_DISCRP,"Event Description:");
    define(IMGJPG,"Note: images will not be resized, image must be JPG only");
    define(CRNT_EV_IMG,"Current Event Image:");
    define(DEL_IMG,"Delete Image");
    define(EVENT_ST_DATE,"Event Start Date:");//(used multiple times)
    define(EVENT_NOTE_OVER,"please note: event will override any existing bookings on that day");
    define(EVENT_ENDDATE,"Event End Date:");
    define(MAX_SPACE,"Maximum Spaces:");
    define(NUMB_PLZ,"numbers only, eg 28");
    define(PAYMT,"Payment:");
    define(REC,"Required");
    define(NOTREC,"Not Required");
    define(PAY_METD,"Payment Method:");//(used multiple times)
    define(PAYPAL_GTW,"PayPal Gateway");
    define(OFFL_INVC,"Offline Invoice");
    define(OFFL_INVC_MSG,"If offline invoice selected - reservation will be confirmed automatically, it is your responsibility to collect payment from customer");
    define(PRICE,"Price:");
    define(TCT_QNTT,"Ticket Quantity:");
    define(MLTP_TCT_CSTM,"Multiple tickets per customer");
    define(ONE_TCT_CSTM,"1 ticket per customer");
    define(MXM_TCT,"Maximum tickets:");
    define(TCT_MSG,"will be applied only if &quot;multiple tickets per customer&quot; was selected above");
    define(ADD_MNL_BOOK,"Add manual booking");
    define(ALL_BOOKED,"All spaces are booked");
    define(DATE_SUBSCR,"Date Subscribed");
    define(EVENT_TTL,"Event Title");//(used multiple times)
    define(EVENT_ID,"ID");//(used multiple times)
    define(ALLFIELDSREQ,"All fields are required!");//(used multiple times)
    define(ADD_EDIT_EVENT,"Add/Edit Event");//(used multiple times)

    # bs-events.php
    define(ZERO_EVENT_DATABASE,"0 events found in deatabase");
    define(BOOK_SYS_ADMIN,"Booking System Admin ");
    define(END_DATE,"End Date");
    define(PAYMENT_QUEST,"Payment Required?");
    define(MSG_EVDELETED,"Selected events were deleted!");
    define(BTN_DELETESEL,"Delete Selected");
    define(SYL_AT,"at");
    define(SYL_LEFT,"left of");
    define(SYL_TOTAL,"total");

    #bs-reserve-view.php
    define(ZERO_MAN_FOUND,"0 manual bookings found in database");
    define(MAN_BOOK,"Manual Bookings");
    define(REASON,"Reason");
    define(RECURRING,"Recurring");
    define(DATE_FORM_RES,"Date Reserved From");
    define(DATE_RES_TO,"Date Reserved To");
    define(ADD_EDIT_MAN_BOOK,"Add/Edit Manual Booking");
    define(SHRT_DESCRPTN,"Short Description:");  // same as small description
    define(SEL_SERVICE,"Select Service:");//(used multiple times)
    define(RES_DATE_FROM,"Reserved Date From:");
    define(RES_DATE_TO,"Reserved Date To:");
    define(REP,"Repeat");
    define(DAILY,"daily");
    define(WEEKLY,"weekly");
    define(MONTHLY,"monthly");
    define(YEARLY,"yearly");
    define(EVERY,"Every:");
    define(MSG_MAN_DEL,"Selected manual bookings were deleted.");

    #bs-reserve.php
    define(MSG_TMBK,"This time booked!");
    define(MSG_DATETO1,"Reserved Date To earlier than the minimum interval");
    define(MSG_BKSAVE,"Booking was successfully saved!");

    #bs-schedule.php
    define(SCHEDL,"Schedule");
    define(SEL_DATE,"Select Day:");

    #bs-services-add.php
    define(ZEO_FOUND_BS,"0 services found in database");
    define(SERVICES,"Services");
    define(DATE_CRTD,"Date Created");
    define(ADD_EDIT_SERV,"Add/Edit Service");
    define(SERV_TTL,"Service Title:");
    define(TIME_BKK_SET,"Time Booking Settings");
    define(BOOK_TIME_INTRV,"Booking time interval:");
    define(MIN15,"15 minutes");
    define(MIN30,"30 minutes");
    define(MIN45,"45 minutes");
    define(H1,"1 hour");
    define(H2,"2 hours");
    define(H3,"3 hours");
    define(H4,"4 hours");
    define(H5,"5 hours");
    define(H6,"6 hours");
    define(H7,"7 hours");
    define(H8,"8 hours");
    define(H9,"9 hours");
    define(H10,"10 hours");
    define(H11,"11 hours");
    define(H12,"12 hours");

    define(INTERV_MSG,"(1 spot = 1 booking interval which you selected above.)");
    define(PRICE_SPOT,"Price per 1 space:");// price per one SPOT???
    define(TIME_MSG,"(if time bookings need to be free - put 0, otherwise XX.XX format)");// time bookings? - doesn't sound right
    define(ALLOW_MULT_SPACES,"Allow Multiple Spaces:");// spots vs spaces?
    define(SPACES_INTRV,"Spaces per each interval:");
    define(PAYMENT_MSG,"(If offline invoice selected - reservation will be confirmed automatically, it is your responsibility to collect payment from customer)");
    define(SHOW_SPAC,"Show spaces left (interval booking):");
    define(NO,"No");//(used multiple times)
    define(YES,"Yes");//(used multiple times)
    define(SPOT_MSG,"Minimum spots you want to allow to book per 1 reservation:");
    define(UNLM_SPOT,"Unlimited Spots");
    define(SPOT_MSG_MAX,"Maximum spots you want to allow to book per 1 reservation:");
    define(SPT1,"1 spot");
    define(SPT2,"2 spots");
    define(SPT3,"3 spots");
    define(SPT4,"4 spots");
    define(BOOK_AVAIL,"Booking Availability");
    define(BOOK_MSG_DAY,"(please set time available for booking for each day, or put N/A if not available)");
    define(PICK_DAY,"Week day"); //weekday? pick a day?
    define(CALND_WEEK_STARTS,"Calendar week starts on:");
    define(SUN,"Sunday");
    define(MON,"Monday");
    define(EVENT_DISP_SETT,"Events Display Settings");
    define(SHOW_TTL,"Show event titles on calendar:");
    define(SHOW_IMG,"Show event image (resized):");
    define(MSG_SRVUPD,"Service updated!");
    define(MSG_DEMO1,"Sorry, selected action is forbidden in live demo");
    define(MSG_SRVSAVE,"Service was successfully created!");
    define(MSG_SRVDEL,"Selected service was deleted.");
    define(MSG_NOTE,"All reservations (time & events) associated with this service will deleted,operation irreversible");

    #bs-settings.php
    define(DEMO_PASS_MSG," Sorry, password cannot be changed in demo");
    define(PASS_NOMATCH,"Passwords don't match!");
    define(SCRP_SETNG,"Script Settings");
    define(ACC_SETNG,"Access Settings");
    define(NEWPASS_ADMN,"New Administrator Password:");
    define(CNFRM_PASS,"Confirm Password:");
    define(NOTIF__EMAIL," Notification Email:");
    define(PYPAL_STNG,"PayPal Settings");
    define(TAX_ON,"Enable Tax:");
    define(TAX,"Tax:");
    define(PAYPAL_EMAIL,"PayPal Merchant Email:");
    define(PAYPAL_CURRN,"Payment Currency:");
    define(PAYPAL_CURRN_SUP,"(paypal supported currencies only)");
    define(DIPL_SETTNG,"Display Settings");
    define(TIME_MODE,"Time mode:");
    define(DATE_FORMT,"Date format:");
    define(POPUP_MSG_BOOK,"Use PopUp window for booking:");
    define(CURNT_SYMBL,"Currency Symbol:");
    define(CURNT_POS,"Currency Position:");
    define(LANG,"Language:");
    define(PLUGINS,"Installed Plugins");
    define(MSG_SETSAVED,"Settings were updated!");
    define(MSG_ADMPSCHG,"Administrator password was changed!");
    define(MSG_PSDNTMTCH,"Passwords don't match!");
    define(BTN_SUBMITCHANGES,"Submit Changes");

    #event-booking.php
    define(MSG_JS_ALLFIELDS,"Please complete all highlited fields to continue.");

    #Event-booking-nojs.php
    define(RESERV_MSG,"Thank you. You will receive confirmation email regarding your reservation after administrator will process it.");
    define(FIELDS_NEEDED,"Following fields required: Name, Email, Phone, Selected Event. Please double check your input.");
    define(CAPTCHA_ERROR,"Captcha error! Please try again!");//(used multiple times)
    define(JAVA_NEEDED,"Please enable JavaScript or upgrade to better");//to better what?? //(used multiple times)
    define(BROWSER,"browser");//(used multiple times)
    define(YNAME,"Your Name");//(used multiple times)
    define(BOOKING_FORM,"Booking Form");//(used multiple times)

    #eventlist.php
    define(WELCM_SYSTM,"Welcome to our booking system.");
    define(SAMPLE_TEXT,"Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. ");
    define(EVENTS_LIST,"Full Events List");
    define(VIEW,"View");
    define(CALENDAR,"Calendar");
    define(EVENT_START,"Event starts at ");
    define(FREE,"FREE");
    define(NO_EVENT_MONTH,"No events in current month");
    define(LINKTO,"Link to");
    define(ADMINAREA,"ADMIN AREA");

    #Forgot.php
    define(WRONG_EMAIL,"Wrong Notification E-mail");
    define(WRONG_EMAIL2,"Wrong Paypal Merchant Email");
    define(CHANGE_PASS_TO_NEW,"Please change password as soon as you login.");
    define(NEW_PASS_SENT,"New password was set and sent to your email.");
    define(WRONG_USERNAME,"Wrong username (Notification Email) and/or Paypal Merchant Email");
    define(WRONG_USERNAME2,"Empty Notification Email and/or Wrong Paypal Merchant Email.");
    define(MSG_BSFORGOT_TITLE,"Booking System Forgot Password");

    #booking.event.processing.php
    define(BEP_5,"Event booking placed!");
    define(BEP_6,"<br />Reservation Status:Confirmed (Please collect payment from customer)<br />");
    define(BEP_7,"<br />Reservation Status: Not Confirmed<br />");
    define(BEP_8,"New confirmed event reservation");
    define(BEP_9,"New un-confirmed event reservation");
    define(BEP_10,"Invalid email address. Please check your input."); //used multiple times
    define(BEP_11,"Thank you. You will receive confirmation email regarding your reservation after administrator will process it.");
    define(BEP_12,"Sorry, somebody just booked that seat");
    define(BEP_13,"Following fields required: Name, Email, Phone, Selected Event. Please double check your input. ");
    define(BEP_14,"Thank you for your reservation!"); //h1 page title - used multiple times
    define(BEP_15,"&laquo; Back To Calendar"); //used multiple times
    define(BEP_16,"Payment For order "); //payment order
    define(SEL_TIME,"Please select desired time."); // used multiple times
    define(BEP_16,"New un-confirmed booking ");
    define(BEP_17,"Following fields required: Name, Email, Phone and booked time. Please double check your input. ");
    define(BEP_18,"Some time interval exceeds the Number of seats. Please check your input.");
    define(AVAIL,"Availability");

    #manageReservation.php
    define(MNG_ATTDEL,"Selected attendee was cancelled.");
    define(MNG_0FOUND,"0 attendees found in database");
    define(MNG_RESERFOR,"Reservations for ");
    define(TBL_NAME,"Name");
    define(TBL_QTY,"Qty.");
    define(TBL_SERVICE,"Service");
    define(TBL_EVENT,"Event");
    define(TBL_TIME,"Time");
    define(TBL_DATE,"Date Subscribed");
    define(TBL_MNG,"Manage");

    #paypal.ipn.php
    define(PP_SUBJ_RECEIVED,"New Payment Received");
    define(PP_CANCEL,"The payment was canceled!");
    define(PP_THANK_H1,"Thank you !");
    define(PP_THANKYOU,"Thank you for your payment, we will contact you shortly!");

    #thank-you.php
    define(THNK_H1,"Thank you for your reservation!");
    define(THNK_TEXT,"Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.");

    #functions.php
    define(ZERO_SPACES,"0 spaces available");
    define(ZERO_SPACES2,"0 spaces");
    define(SPC_AVAIL," spaces available");
    define(EVENTS_SCHEDULED," event(s) scheduled");
    define(BOOK_NOW,"Book Now");
    define(TXT_RESERVED,"Reserved by admin with reason: ");
    define(SPACES," spaces");
    define(TXT_EVENT,"Event");
    define(TXT_PAST,"Past");
    define(TXT_QTY,"Qty: ");
    define(TXT_EVENT_START,"Event starts at ");
    define(TXT_EVENT_ENDS,"Event ends at ");
    define(SPC_LEFT," spaces left ");
    define(TXT_PLSSELECT,"Please select desired event.");
    define(ADM_NONWORKING,"This day is marked as not-working day, so 0 bookings found.");
    define(TXT_EVENT2,"Event");
    define(TXT_AVAIL,"Available for booking ");
    define(TXT_MINUTES_MAX," minutes maximum;");
    define(TXT_MAX," maximum;");
    define(TXT_MINUTES," minutes");
    define(TXT_AND," and ");
    define(TXT_HOURSS," hour(s)");
    define(TXT_MINUTES_MIN," minutes minimum; ");
    define(TXT_MIN," minimum; ");
    define(TXT_SPOTS_LEFT,"spots<br>left");
    define(TXT_FUNC_QTY,"Qty.");
    define(TXT_FUNC_FREE,"FREE");
    define(TXT_FUNC_PAYMENT_FOR,"Payment for reservation");
    define(TXT_FUNC_PAYMNT_EVENT,"Payment for event");
    define(TXT_FUNC_CLICK_HERE_TO_PAY,"Click Here To Pay For Booking");
    define(TXT_FUNC_THANK_YOU_MSG,"<p>Thank you. You will receive confirmation email regarding your reservation after administrator will process it.</p>");
    define(TXT_FUNC_ALMOST_DONE,"You're almost done. There's just one thing left to do - payment. Please click button below and you will be transfered to PayPal.com for fast and secure payment. Please note that your booking will be confirmed only after");

    #new messages in v5.2.1
    define(REPEAT_MSG,"Please make sure your reserved date to equals to actual recurring interval end. For example if recurring booking must end in 6 weeks from now, set \"date to\" to \"today+6 weeks\"");

?>