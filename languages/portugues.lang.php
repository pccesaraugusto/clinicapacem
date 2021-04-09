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
    define(MENU1,"Programar");
    define(MENU2,"Reservas");
    define(MENU3,"Eventos");
    define(MENU4,"Lista de eventos");
    define(MENU5,"Adicionar Eventos");
    define(MENU6,"Reservas manuais");
    define(MENU7,"Adicionar Reserva Manual");
    define(MENU8,"Lista de Reservas");
    define(MENU9,"ServiÇos");
    define(MENU10,"Lista de Serviços");
    define(MENU11,"Adicionar Serviço");



    # booking-nojs.php
    # General
    define(APPLICATION_TITLE,"Sistema de Agenda Online");//(used multiple times)
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
    define(PAGE_TITLE2,"Editar Reserva em Evento");
    define(BOOKING_LST_EVENTS,"Eventos");
    define(DATE_BOOK_PLC,"Data da reserva foi adicionada:");
    define(EVENT_LIST,"&laquo; Voltar para evento");

    # bs-events-add.php
    define(ADM_MSG5,"Reserva adicionada com sucesso!");
    define(ADM_MSG6,"Alguns campos obrigatórios estão vazios!");
    define(EVENT_SUC_MSG,"Evento foi atualizado com sucesso!");
    define(EVENT_SUC_UPD,"Evento foi criado com sucesso!");
    define(EVENT_STR_TIME,"A data e hora de início do evento não pode ser após fim!");
    define(STAT_UPDT,"Status atualizado!");
    define(NTFC_ESENT,"Os E-mails de notificação foram enviados.");
    define(SEL_ATT_DEL,"O Participante selecionado foi apagado.");
    define(NO_FOUND,"0 participantes encontrados no banco de dados");
    define(EVENT_DISCRP,"Descrição do Evento:");
    define(IMGJPG,"Nota: as imagens não serão redimensionadas, a imagem deve ser JPG");
    define(CRNT_EV_IMG,"Imagem do evento atual:");
    define(DEL_IMG,"Apagar Imagem");
    define(EVENT_ST_DATE,"Data de início do evento:");//(used multiple times)
    define(EVENT_NOTE_OVER,"por favor note: evento irá sobrepor as reservas existentes nesse dia");
    define(EVENT_ENDDATE,"Data de término do evento:");
    define(MAX_SPACE,"Maximo de vagas:");
    define(NUMB_PLZ,"somente números, ex.: 25");
    define(PAYMT,"Pagamento:");
    define(REC,"Obrigatório");
    define(NOTREC,"Não Obrigatório");
    define(PAY_METD,"Forma de pagamento:");//(used multiple times)
    define(PAYPAL_GTW,"PayPal");
    define(OFFL_INVC,"Depósito/Transferência");
    define(OFFL_INVC_MSG,"Se Depósito/Transferência selecionado a reserva será confirmada automaticamente, é sua a responsabilidade de recolher o pagamento de cliente");
    define(PRICE,"Preço:");
    define(TCT_QNTT,"Quantidade de Ticket:");
    define(MLTP_TCT_CSTM,"Mais de um Ticket por cliente");
    define(ONE_TCT_CSTM,"1 Ticket por cliente");
    define(MXM_TCT,"Maximo de tickets:");
    define(TCT_MSG,"será aplicada somente se &quot;múltiplos bilhetes por cliente&quot; foi selecionado acima");
    define(ADD_MNL_BOOK,"Adicionar reserva manual");
    define(ALL_BOOKED,"Todos os espaços reservados");
    define(DATE_SUBSCR,"Data de inscrição");
    define(EVENT_TTL,"Título do evento");//(used multiple times)
    define(EVENT_ID,"ID");//(used multiple times)
    define(ALLFIELDSREQ,"Todos os campos são obrigatórios!");//(used multiple times)
    define(ADD_EDIT_EVENT,"Adicionar/Editar Evento");//(used multiple times)

    # bs-events.php
    define(ZERO_EVENT_DATABASE,"0 eventos encontrados no banco de dados");
    define(BOOK_SYS_ADMIN,"Administração de Reservas");
    define(END_DATE,"Data do Fim");
    define(PAYMENT_QUEST,"Pagamento Obrigatório?");
    define(MSG_EVDELETED,"Os Eventos selecionados foram apagados!");
    define(BTN_DELETESEL,"Apagar selecionados");
    define(SYL_AT,"Até");
    define(SYL_LEFT,"Restantes");
    define(SYL_TOTAL,"total");

    #bs-reserve-view.php
    define(ZERO_MAN_FOUND,"0 reservas manuais encontradas no banco de dados");
    define(MAN_BOOK,"Reservas manuais");
    define(REASON,"Razão");
    define(RECURRING,"Recorrente");
    define(DATE_FORM_RES,"Data reservada de");
    define(DATE_RES_TO,"Data reservada até");
    define(ADD_EDIT_MAN_BOOK,"Adicionar/Editar Reserva Manual");
    define(SHRT_DESCRPTN,"Descrição Curta:");  // same as small description
    define(SEL_SERVICE,"Selecionar Serviço:");//(used multiple times)
    define(RES_DATE_FROM,"Data reservada de:");
    define(RES_DATE_TO,"Data reservada até:");
    define(REP,"Repetir");
    define(DAILY,"Diário");
    define(WEEKLY,"Semanal");
    define(MONTHLY,"Mensal");
    define(YEARLY,"Anual");
    define(EVERY,"Sempre:");
    define(MSG_MAN_DEL,"Reserva Manual selecionata foi apagada.");

    #bs-reserve.php
    define(MSG_TMBK,"Horário já reservado!");
    define(MSG_DATETO1,"Reservado Data Para mais cedo do que o intervalo mínimo");
    define(MSG_BKSAVE,"Reserva foi salva com sucesso!");

    #bs-schedule.php
    define(SCHEDL,"Programar");
    define(SEL_DATE,"Selecionar Dia:");

    #bs-services-add.php
    define(ZEO_FOUND_BS,"0 serviços encontrados no banco de dados");
    define(SERVICES,"Serviços");
    define(DATE_CRTD,"Data de criação");
    define(ADD_EDIT_SERV,"Adicionar/Editar Serviço");
    define(SERV_TTL,"Título do Serviço:");
    define(TIME_BKK_SET,"Configurações datas da reserva");
    define(BOOK_TIME_INTRV,"Intervalo de tempo de reserva:");
    define(MIN15,"15 minutos");
    define(MIN30,"30 minutos");
    define(MIN45,"45 minutos");
    define(H1,"1 hora");
    define(H2,"2 horas");
    define(H3,"3 horas");
    define(H4,"4 horas");
    define(H5,"5 horas");
    define(H6,"6 horas");
    define(H7,"7 horas");
    define(H8,"8 horas");
    define(H9,"9 horas");
    define(H10,"10 horas");
    define(H11,"11 horas");
    define(H12,"12 horas");

    define(INTERV_MSG,"(1 lugar = 1 intervalo de reserva que você selecionou anteriormente.)");
    define(PRICE_SPOT,"Preço por um lugar:");// price per one SPOT???
    define(TIME_MSG,"(se reservas de tempo precisa ser gratuita - colocar 0, formato 00.00)");// time bookings? - doesn't sound right
    define(ALLOW_MULT_SPACES,"Permitir vários espaços:");// spots vs spaces?
    define(SPACES_INTRV,"Espaços por cada intervalo:");
    define(PAYMENT_MSG,"(Se Depósito/Transferência selecionado a reserva será confirmada automaticamente, é sua a responsabilidade de recolher o pagamento de cliente)");
    define(SHOW_SPAC,"Mostrar os espaços restantes (intervalo de reserva):");
    define(NO,"Não");//(used multiple times)
    define(YES,"Sim");//(used multiple times)
    define(SPOT_MSG,"Quantidade mínima de Lugares que você deseja permitir a agendar por uma reserva:");
    define(UNLM_SPOT,"Lugares Ilimitados");
    define(SPOT_MSG_MAX,"Máximo de Lugares que você deseja permitir a agendar por uma reserva:");
    define(SPT1,"1 lugar");
    define(SPT2,"2 lugares");
    define(SPT3,"3 lugares");
    define(SPT4,"4 lugares");
    define(BOOK_AVAIL,"Disponibilidade de Reserva");
    define(BOOK_MSG_DAY,"(por favor definir o tempo disponível para reserva para cada dia, ou colocar N/D se não disponível)");
    define(PICK_DAY,"Dia da Semana"); //weekday? pick a day?
    define(CALND_WEEK_STARTS,"Semana começa em:");
    define(SUN,"Domingo");
    define(MON,"Segunda");
    define(EVENT_DISP_SETT,"Configurações de Apresentação de Eventos");
    define(SHOW_TTL,"Mostrar títulos de eventos no calendário:");
    define(SHOW_IMG,"Mostrar imagem do evento (redimencionado):");
    define(MSG_SRVUPD,"Serviço Atualizado!");
    define(MSG_DEMO1,"Desculpe, é proibido ação selecionada em demonstração ao vivo");
    define(MSG_SRVSAVE,"Serviço foi criado com sucesso!");
    define(MSG_SRVDEL,"Serviço selecionado foi excluído.");
    define(MSG_NOTE,"Todas as reservas (tempo e eventos) associados a este serviço será excluído, operação irreversível");

    #bs-settings.php
    define(DEMO_PASS_MSG," Sorry, password cannot be changed in demo");
    define(PASS_NOMATCH,"Senha incorreta!");
    define(SCRP_SETNG,"Configurações de Script");
    define(ACC_SETNG,"Definições de Acesso");
    define(NEWPASS_ADMN,"Nova senha de Administrador:");
    define(CNFRM_PASS,"Confirmar Senha:");
    define(NOTIF__EMAIL,"Email de notificação:");
    define(PYPAL_STNG,"Configurações do PayPal");
    define(TAX_ON,"Habilitar imposto:");
    define(TAX,"Imposto:");
    define(PAYPAL_EMAIL,"Email do PayPal:");
    define(PAYPAL_CURRN,"Moeda de Pagamento:");
    define(PAYPAL_CURRN_SUP,"(moedas suportadas paypal)");
    define(DIPL_SETTNG,"Configurações de exibição");
    define(TIME_MODE,"Formato da Hora:");
    define(DATE_FORMT,"Formato da Data:");
    define(POPUP_MSG_BOOK,"Usar janela PopUp para reservas:");
    define(CURNT_SYMBL,"Símbolo da moeda:");
    define(CURNT_POS,"Posição do Símbolo:");
    define(LANG,"Idioma:");
    define(PLUGINS,"Plugins Instalados");
    define(MSG_SETSAVED,"As configurações foram atualizadas!");
    define(MSG_ADMPSCHG,"A senha do administrador foi trocada!");
    define(MSG_PSDNTMTCH,"As senhas não são iguais!");
    define(BTN_SUBMITCHANGES,"Enviar Alterações");

    #event-booking.php
    define(MSG_JS_ALLFIELDS,"Por favor, preencha todos os campos destacados para continuar.");

    #Event-booking-nojs.php
    define(RESERV_MSG,"Obrigado. Você receberá e-mail de confirmação a respeito de sua reserva após administrador irá processá-lo.");
    define(FIELDS_NEEDED,"Following fields required: Name, Email, Phone, Selected Event. Please double check your input.");
    define(CAPTCHA_ERROR,"Erro Captcha! Tente de novo!");//(used multiple times)
    define(JAVA_NEEDED,"Favor habilitar o JavaScript ou atualizar para melhorar");//to better what?? //(used multiple times)
    define(BROWSER,"navegador");//(used multiple times)
    define(YNAME,"Seu nome");//(used multiple times)
    define(BOOKING_FORM,"Formulário");//(used multiple times)

    #eventlist.php
    define(WELCM_SYSTM,"Bem vindo à nossa agenda.");
    define(SAMPLE_TEXT,"Você pode facilmente agendar consultas, eventos, aulas e programas. O Sistema de Agenda Online oferece ótimas opções de gestão, flexibilidade e customização. Selecione abaixo o Serviço ou evento, escolha o dia para reserva e veja os horário disponíveis para agendar.");
    define(EVENTS_LIST,"Lista completa de Eventos");
    define(VIEW,"Ver");
    define(CALENDAR,"Calendário");
    define(EVENT_START,"Evento começa às ");
    define(FREE,"GRÁTIS");
    define(NO_EVENT_MONTH,"Não há eventos no mês atual");
    define(LINKTO,"Link to");
    define(ADMINAREA,"ÁREA ADMINISTRATIVA");

    #Forgot.php
    define(WRONG_EMAIL,"E-mail de notificação errado");
    define(WRONG_EMAIL2,"E-mail do Paypal errado");
    define(CHANGE_PASS_TO_NEW,"Por favor, altere a senha assim que você fizer o login.");
    define(NEW_PASS_SENT,"Nova senha foi definida e enviada para o seu e-mail.");
    define(WRONG_USERNAME,"Nome de usuário errado (Notificação de e-mail) e/ou email do Paypal");
    define(WRONG_USERNAME2,"Notificação de e-mail vazio e/ou errado email do Paypal.");
    define(MSG_BSFORGOT_TITLE,"Esqueceu a senha");

    #booking.event.processing.php
    define(BEP_5,"Reserva de Evento adicionada!");
    define(BEP_6,"<br />Status da Reserva: Confirmado (Por favor, recolher o pagamento do cliente)<br />");
    define(BEP_7,"<br />Status da Reserva: Não Confirmado<br />");
    define(BEP_8,"Nova reserva de evento confirmada");
    define(BEP_9,"Nova reserva de evento não confirmada");
    define(BEP_10,"Endereço de email inválido. Por favor verifique."); //used multiple times
    define(BEP_11,"Obrigado. Você receberá e-mail de confirmação a respeito de sua reserva após administrador irá processá-lo.");
    define(BEP_12,"Desculpe, alguém já reservou o lugar");
    define(BEP_13,"Seguintes campos são obrigatórios: Nome, E-mail, telefone, evento selecionado. Por favor verifique. ");
    define(BEP_14,"Obrigado por sua reserva!"); //h1 page title - used multiple times
    define(BEP_15,"&laquo; Voltar ao Calendário"); //used multiple times
    define(BEP_16,"Ordem de pagamento para "); //payment order
    define(SEL_TIME,"Por favor, selecione o tempo desejado."); // used multiple times
    define(BEP_16,"Nova reserva  não confirmada ");
    define(BEP_17,"Seguintes campos obrigatórios: nome, E-mail, telefone e tempo reservado. Por favor, verifique. ");
    define(BEP_18,"Algum intervalo de tempo excede o número de lugares. Por favor, verifique sua entrada.");
    define(AVAIL,"Disponível");

    #manageReservation.php
    define(MNG_ATTDEL,"Participante selecionado foi cancelado.");
    define(MNG_0FOUND,"0 participantes encontrados no banco de dados");
    define(MNG_RESERFOR,"Reservas para ");
    define(TBL_NAME,"Nome");
    define(TBL_QTY,"Qtd.");
    define(TBL_SERVICE,"Serviço");
    define(TBL_EVENT,"Evento");
    define(TBL_TIME,"Tempo");
    define(TBL_DATE,"Date da Reserva");
    define(TBL_MNG,"Gerenciar");

    #paypal.ipn.php
    define(PP_SUBJ_RECEIVED,"Novo Pagamento Recebido");
    define(PP_CANCEL,"O pagamento foi cancelado!");
    define(PP_THANK_H1,"Obrigado!");
    define(PP_THANKYOU,"Obrigado por seu pagamento, entraremos em contato em breve!");

    #thank-you.php
    define(THNK_H1,"Obrigado pela sua reserva!");
    define(THNK_TEXT,"Texto de confirmação e instruções.");

    #functions.php
    define(ZERO_SPACES,"0 lugares disponíveis");
    define(ZERO_SPACES2,"0 lugares");
    define(SPC_AVAIL," Espaços Disponíveis");
    define(EVENTS_SCHEDULED," evento(s) programado");
    define(BOOK_NOW,"Reserve agora");
    define(TXT_RESERVED,"Reservados pelo administrador pelo motivo: ");
    define(SPACES," lugares");
    define(TXT_EVENT,"Evento");
    define(TXT_PAST,"Acabado");
    define(TXT_QTY,"Qtd: ");
    define(TXT_EVENT_START,"O Evento começa às ");
    define(TXT_EVENT_ENDS,"O Evento termina às ");
    define(SPC_LEFT," lugares restantes ");
    define(TXT_PLSSELECT,"Por favor seleccione evento desejado.");
    define(ADM_NONWORKING,"Este dia é marcado como dia de folga, de modo que 0 reservas encontradas.");
    define(TXT_EVENT2,"Evento");
    define(TXT_AVAIL,"Disponível para reserva ");
    define(TXT_MINUTES_MAX," Minutos máximo;");
    define(TXT_MAX," máximo;");
    define(TXT_MINUTES," minutos");
    define(TXT_AND," e ");
    define(TXT_HOURSS," hora(s)");
    define(TXT_MINUTES_MIN," minutos mínimo; "); 
    define(TXT_MIN," mínimo; ");
    define(TXT_SPOTS_LEFT,"lugares<br>restantes");
    define(TXT_FUNC_QTY,"Qtd.");
    define(TXT_FUNC_FREE,"GRÁTIS");
    define(TXT_FUNC_PAYMENT_FOR,"Pagamento de reserva");
    define(TXT_FUNC_PAYMNT_EVENT,"Pagamento de eventO");
    define(TXT_FUNC_CLICK_HERE_TO_PAY,"Pagar agora");
    define(TXT_FUNC_CLICK_HERE_TO_PIX,"Pagar agora");
    define(TXT_FUNC_CLICK_HERE_TO_MERCADO_PAGO,"Pagar agora");
    define(TXT_FUNC_THANK_YOU_MSG,"<p>Obrigado. Você receberá um e-mail de confirmação a respeito de sua reserva após administrador irá processá-lo.</p>");
    define(TXT_FUNC_ALMOST_DONE,"Está quase pronto. Há apenas uma coisa a fazer, O pagamento. Por favor, clique no botão abaixo e você será transferido para o PayPal.com para um pagamento rápido e seguro. Por favor, note que a sua reserva só será confirmada após o pagamento");

    #new messages in v5.2.1
    define(REPEAT_MSG,"Verifique se a sua data reservada é igual ao atual intervalo final recorrente. Por exemplo, se reserva recorrente deve terminar em 6 semanas a partir de agora, definir \"data para\" para \"hoje+6 semanas\"");

    #definicoes da tela de configuracoes do pix
    define(LB_COF_PIX_TITULO,"Realizar configurações do PIX");

    #definicoes da tela de configuracoes do mercado pago
    define(LB_COF_MERCADO_PAGO_TITULO,"Realizar configurações do Mercado Pago");
    define(LB_COF_MERCADO_PAGO_HMG, "Informe abaixo os dados da área do <b>SandBox</b> - Homologação");
    define(LB_COF_MERCADO_PAGO_PROD, "Informe abaixo os dados da área de <b>Produção</b>");
    define(HMG_CRIENT_ID, "Client Id");
    define(HMG_CRIENT_SECRET, "Client Secret");
    define(HMG_PUBLIC_KEY, "Public Key");
    define(HMG_ACCESS_TOKEN, "Access Token");
    define(BTN_SALVAR_DADOS_HMG, "Salvar Homologação");
    define(PROD_CRIENT_ID, "Client Id");
    define(PROD_CRIENT_SECRET, "Client Secret");
    define(PROD_PUBLIC_KEY, "Public Key");
    define(PROD_ACCESS_TOKEN, "Access Token");
    define(BTN_SALVAR_DADOS_PROD, "Salvar Produção");
    define(SUCESS_MERCADO_PAGO_HMG, 'Atenção! Os dados da Homologação foram incluídos com sucesso.');
    define(SUCESS_MERCADO_PAGO_PROD, 'Atenção! Os dados da Produção foram incluídos com sucesso.');
    define(SELECT_PIX,"Pix");
    define(SELECT_PAYPAL,"Paypal");
    define(SELECT_MERCADO_PAGO,"Mercado Pago");
    define(LB_OPCOES_SELECT, "Tipo de Pagamento");
    define(BTN_LOCACLIZAR_DADOS_HMG, 'Ver Dados Cadastrados');

    define(LB_ID_TAB_PIX,'ID');
    define(LB_CHAVE_TB_PIX, 'Chave');
    define(LB_ATIVA_TB_PIX, 'Ativa');
    define(LB_ACOES_TB_PIX, 'Ação');
    define(NAO_EXITE_REGISTRO_CADASTRADO, 'Atenção! Não existe registro cadastrado atualmente na sua base de dados.');
    define(BTN_SALVAR_CHAVE_PIX, 'Salvar');
    define(BTN_INCLUIR_CHAVE_PIX, 'Novo registro');
    define(BTN_VOLTAR_INCLUIR_CHAVE_PIX, 'Voltar p/ tela anterior');
    define(TITLE_SUB_CHAVE_PIX, 'Informe os dados abaixo para incluir a nova chave do pix');
    define(SUCESS_PIX_INCLUIR_CHAVE, 'Atenção! A nova chave do PIX foi cadastrada com sucesso.');
    define(SUCESS_PIX_EXCLUIR_CHAVE, 'Atenção! A chave PIX selecionada foi excuída com sucesso.');
    define(BTN_SAIR_PGTO_PIX, 'Sair');
    
    
?>