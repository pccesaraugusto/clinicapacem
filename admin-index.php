<?php
    /******************************************************************************
    #                         BookingWizz v5.2.1
    #******************************************************************************
    #      Author:     Convergine (http://www.convergine.com)
    #      Website:    http://www.convergine.com
    #      Support:    http://support.convergine.com
    #      Version:    5.2.1
    #
    #      Copyright:   (c) 2009 - 2012  Convergine.com
    #	   Icons from PixelMixer - http://pixel-mixer.com/basic_set/ and by Manuel Lopez - http://www.iconfinder.com/search/?q=iconset%3A48_px_web_icons
    #
    #*******************************************************************************/


    ######################### DO NOT MODIFY (UNLESS SURE) ########################
    require_once("includes/config.php"); //Load the configurations
    $msg = "";

    if ($_SESSION["logged_in"] != true) {
        header("Location: admin.php");
        exit();
    } else {
        bw_do_action("bw_load");
        bw_do_action("bw_admin");
        include "includes/admin_header.php";
?>
    <div id="content">
        
        
        <div class="content_block ">
            <div class="padd">
            <h2><?php echo ADMIN_WELCOME?></h2>
            <?php echo ADMIN_WELCOME_TEXT?>
            <ul class="dashboard">
                <li><a href="bs-schedule.php"><img src="./images/dash_logo1.jpg" border="0"/></a>
                    <span><a href="bs-schedule.php"><?php echo MENU1?></a></span>
                </li>
                <li><a href="bs-bookings.php"><img src="./images/dash_logo2.jpg" border="0"/></a>
                    <span><a href="bs-bookings.php"><?php echo MENU2?></a></span>
                </li>
                <li><a href="bs-events.php"><img src="./images/dash_logo3.jpg" border="0"/></a>
                    <span><a href="bs-events.php"><?php echo MENU3?></a></span>
                    <a href="bs-events-add.php"><?php echo strtolower(MENU5)?></a>
                </li>
                <li><a href="bs-reserve-view.php"><img src="./images/dash_logo4.jpg" border="0"/></a>
                    <span><a href="bs-reserve-view.php"><?php echo MENU6?></a></span>
                    <a href="bs-reserve.php"><?php echo strtolower(MENU7) ?></a>
                </li>
                <li><a href="bs-services.php"><img src="./images/dash_logo5.jpg" border="0"/></a>
                    <span><a href="bs-services.php"><?php echo MENU9;?></a></span>
                    <a href="bs-services-add.php"><?php echo strtolower(MENU11);?></a>
                </li>
            </ul>
            <div style="clear: both"></a>
            <br>
<h2>Como Funciona</h2>
                                <ul>
                                    <li>Você pode facilmente configurar consultas, eventos, aulas, programas e começar a aceitar reservas gratuitas ou pagas online. O Sistema de Agendamento Online oferece ótimas opções de gestão, flexibilidade e customização. Por exemplo, você pode definir um período de tempo disponível para registro para cada dia (segunda a domingo), o preço definido, disponibilidade, várias restrições, notificação por email, definir quantas horas é possível agendar ocliente por reserva.</li>
                                    <li>Uma vez que agenda o lugar do cliente através de calendário simples, mas atraente e personalizável - o cliente e o administrador receberão uma notificação para seus e-mails. Por padrão reserva "não confirmada " , até que o proprietário do site irá alterar o status de reserva para " CONFIRMADO " tempo selecionado na reserva calendário para esse dia estará disponível para reserva.</li>
                                    <li>Dono do site também tem a opção de reservar tempo manualmente através do painel de controle, por exemplo, se há uma festa corporativa e de toda a instalação será reservado para o dia inteiro - admin pode adicionar essa reserva através do painel de controle - para que o cliente vai ver no calendário que este dia não está disponível para reservas.</li>


                                



            </div>
        </div>
    </div>

<?php
        include "includes/admin_footer.php";
    }
?>