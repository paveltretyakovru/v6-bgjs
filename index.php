<?php
/*
require_once("../sharedAPI/LogicGameResourceManager.php");
require_once("../sharedAPI/LogicGameLocalization.php");
require_once("../sharedAPI/LogicGameSessionManager.php");
require_once("../sharedAPI/LogicGameVkAuth.php");

$v .= '10';
$sm = new LogicGameSessionManager(BACKGAMMON_ID);
$s = $sm->getAuthServerInstance();

// Vk auth
$isVk = false;
$vkAuth = new LogicGameVkAuth($s, $sm);
$vkAuth->tryVkAuth();
$isVk = $vkAuth->hasVkAuth();

$i18n = $s->getI18n();

$isFreshUser = $sm->isFreshUser();

$s->updateActivity();

$visitorStats = $s->getVisitorStats();
*/
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="624">
    <meta name="viewport" content="width=624, target-densitydpi=160dpi,  user-scalable=no">
    <title>Длинные нарды</title>

    <?php
        echo "<link media='screen' href='css/build/shared.css?v=$v' rel='stylesheet' type='text/css'>\n\r";
        ?>

    <link rel="stylesheet" type="text/css" href="css/v6-game-client.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link media='screen' href='css/game-layout.css' rel='stylesheet' type='text/css'>

</head>
<body>

<div id='gamedialog'>
    
</div>

<!-- MAIN -->
<table class="mainLayout" cellspacing="0" cellpadding="0">
<tr>

<td class="gameAreaLayout" style='width:33.3%'>

    <div class="gameArea" id="gameArea" style='width:600px'>

        <!-- TOP LINKS -->
        <div class="titleBand">
            <div class="titleBandInner">
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tr>
                        <td width="1%" style="white-space: nowrap;">
                            <span class="titleBandLink" id="title">
                                Длинные нарды
                            </span>
                        </td>

                        <td>&nbsp;</td>

                        <td width="1%" align="center" style="white-space: nowrap;">
                            <span class="titleBandLink" id="showDescription">
                                Описаниие
                            </span>
                        </td>

                        <td>&nbsp;</td>

                        <td width="1%" align="center" style="white-space: nowrap;">
                            <span id="gbShow" class="titleBandLink">
                                Вопросы и отзывы
                            </span>
                        </td>

                        <td>&nbsp;</td>

                        <td width="1%" align="right" style="white-space: nowrap;">
                            <a href="/" class="titleBandLink" >
                                Перейти на другие игры
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- TOP BUTTONS --> <div class="controlPanel nonSelectable">
            <table class="controlPanelLayout" cellpadding="0">
                <tr>
                    <td id="tbLeave" class="cpButton cpNormal nonSelectable">Покинуть игру</td>
                    <td id="tbUndo" class="cpButton cpNormal nonSelectable">Ход назад</td>                    
                    <!--
                    <td id="tbNewGameContainer" class="cpButton cpNormal nonSelectable cpKillHover">
                        <table style="width: 100%; height: 100%;" cellspacing="0" cellpadding="0">
                            <tr>
                                <td id="tbPreviousGame" style="border-right: 1px solid #BBB; width: 15px;"
                                    class="cpNormal roundedLeft4px"> < </td>
                                <td id="tbNewGame" class="cpNormal roundedRight4px">Новая игра</td>
                            </tr>
                        </table>
                    </td>
                    -->
                    <td id="tbThrow" class="cpButton cpNormal nonSelectable">Сдаться</td>
                    <!--<td id="tbReplay" class="cpButton cpNormal nonSelectable">Начать сначала</td> -->
                    <td id="tbDraw" class="cpButton cpNormal nonSelectable" style="padding: 5px;">Предложить ничью</td>
                </tr>
            </table>
        </div>

        <!-- GAME FIELD !! важно чтобы был див с таким айдишников и центрированием, относительного него и будет центрироваться блок с авторизацией !!-->
        <div id="field" style='padding-bottom: 0px; min-height: 700px'>

		<div class='dop_menu' style='height: 50px'>
		<div>
		<ul id='dop_bottom_menu'>
			<li id='home1pl'>*</li>
			<li id='gamestatus'></li>
			<li id='home2pl'>*</li>
		</ul>
		</div>

		</div>
		
		<div id='outbody'>
		  <div id='outwhite'></div>
		  <div id='outblack'></div>
		</div>

            <div id='container'>

            </div>

        </div>

        <!-- BOTTOM BUTTONS -->
        <div class="controlPanel nonSelectable">		

            <table class="controlPanelLayout">
                <tr>
                    <td id="bbParameters" class="cpButton cpNormal nonSelectable">Параметры</td>
                    <td id="bbHistory" class="cpButton cpNormal nonSelectable">История</td>
                    <td id="bbRatings" class="cpButton cpNormal nonSelectable">Рейтинг</td>
                    <td id="bbLoginRegister" class="cpButton cpNormal nonSelectable"> Авторизация </td>
                    <td id="bbProfile" class="cpButton cpNormal nonSelectable"> <span id="bbProfileLabel">Личный кабинет</span> <span id="bbUnreadMsgCount"></span> </td>
                </tr>
            </table>
        </div>

        <!-- INCLUDE AUTHFORM  !! обязательно для включения, в блоке, с центрированием !! -->
        <!-- welcome panel !-->
<div class="bubblePanel _hackOverFieldPanel" id="welcomePanel">
    <div class="overFieldInnerPanel">

        <h4 style="margin-top: 3px; margin-bottom: 13px; text-align: center;">
            Авторизация        </h4>

        <table style="margin-bottom: 5px; width: 100%;" cellspacing="5px">
            <tr>
                <td class="constantWidthTd" id="wpReg" style="width: 25%;">
                    <div style='color: #C42E21; font-weight: bold;' class="lrWelcomeBtnText">
                        Регистрация                    </div>
                    <span class='lrWelcomeHint'>только<br/>имя и пароль </span>
                </td>
                <td class="constantWidthTd" id="wpLogin" style="width: 25%;">
                    <div style='font-weight: bold; color: #444;' class="lrWelcomeBtnText">
                        Войти                    </div>
                    <span class='lrWelcomeHint'>если вы уже<br/>регистрировались</span>
                </td>
                <td class="constantWidthTd" id="wpVK" style="width: 25%;">
                    <div style='font-weight: bold; color: #444;' class="lrWelcomeBtnText">
                        <p style="margin-top: 2px;margin-bottom: 4px;"> Войти через</p> <img src="images/icons/vk_logo.png">
                    </div>
                </td>
                <td class="constantWidthTd" id="wpClose" style="width: 25%;">
                    <div style='font-weight: bold; color: #444;' class="lrWelcomeBtnText">
                        Играть как гость                    </div>
                    <span class='lrWelcomeHint'>без регистрации</span>
                </td>
            </tr>
        </table>
        <div id="WelcomePanelContainer">

            <div style="padding-top: 10px;" id="lrLoginSection" class="lrSection">
                <h4>Войти</h4>
                <!--<span class="lrHeaderHint">если у вас уже есть имя и пароль</span>-->

                <form id="loginForm" method="POST" action="#">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="sessionId" id="hfSessionId" value="">
                    <input type="hidden" name="userId" id="hfUserId" value="">
                    <table style="margin-top: 5px;" width="100%">
                        <tr>
                            <td width="10%">Имя&nbsp;пользователя:</td>
                            <td width="10%">
                                <input id="loginUsername" name="login"/>
                            </td>
                            <td rowspan="3" style="vertical-align: top;">
                                <div style="float: left;" id="loginResult"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Пароль:</td>
                            <td><input type="password" id="loginPasswd" name="password"/></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 5px;">
                                <input class="lrRememberCheckBox" name="remember" value="1" type="checkbox" checked>
                                запомнить меня на этом компьютере                            </td>
                        </tr>
                    </table>
                    <br/>

                    <div style="margin-bottom: 32px;">
                        <div class="constantWidthBtn" id="loginCommit">
                            Войти                        </div>
                        <p class="msgShort" id="restorePass" style="float: right;">Забыли пароль?</p>
                    </div>
                </form>

            </div>

            <div style="padding-top: 10px;" id="lrRegisterSection" class="lrSection">
                <h4>Ввести новое имя</h4>
                <!--<span class="lrHeaderHint">если у вас ещё нет имени и пароля</span>-->

                <form id="regForm" method="post" action="#" autocomplete="off">
                    <table style="margin-top: 5px;">
                        <tr>
                            <td>
                                Имя&nbsp;пользователя:                            </td>
                            <td><input type="text" id="regUsername" name="login" autocomplete="off"/></td>
                            <td>
                                <div id="usernameAlert"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Пароль:                            </td>
                            <td><input type="password" id="regPasswd" name="password"/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                Повторите пароль:                            </td>
                            <td><input type="password" id="regPasswdVerification"/></td>
                            <td>
                                <div id="passwdAlert"></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div id="regResult"></div>
                            </td>
                        </tr>
                    </table>
                    <br/>


                    <div style="margin-bottom: 32px;">
                        <div class="constantWidthBtn" id="regMeBtn">
                            Продолжить                        </div>
                    </div>
                </form>
            </div>

            <div style="padding-top: 10px;" id="lrGuestSection" class="lrSection">
                <p>Вы можете играть как <span id='guestName'></span>.</p>
                <p>Вам не будет доступна история Ваших игр, Вы не сможете обмениваться личными сообщениями с
                    другими игроками.</p>
                <div class="constantWidthBtn" id="guestContinue">Продолжить</div>
                <br>
            </div>

            <div style="padding-top: 10px;" class="lrSection" id="restorePassPanel">
                <h4>Восстановление пароля</h4>
                <span class="lrHeaderHint">На почту, указанную в профиле, будет отправлен новый пароль</span>
                            <form id="rpForm" method="POST" action="#">
                                <input type="hidden" name="action" value="login">
                                <table style="margin-top: 5px;" width="100%">
                                    <tr>
                                        <td width="10%">Имя&nbsp;пользователя:</td>
                                        <td width="10%">
                                            <input id="rpUsername" name="login"/>
                                        </td>
                                        <td rowspan="3" style="vertical-align: top;">
                                            <div style="float: left;" id="rpResult"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Электронная почта</td>
                                        <td><input id="rpMail" name="mail"/></td>
                                    </tr>
                                </table>
                                <br/>

                                <div style="margin-bottom: 32px;">
                                    <div class="constantWidthBtn" id="rpCommit">
                                        Отправить
                                        <!--<input type="submit" style="display: none;"/>-->
                                    </div>
                                    <div class="constantWidthBtn" id="rpCancel">
                                        Отмена                                    </div>
                                </div>
                            </form>
            </div>
        </div>
    </div>
</div>
<!-- end of welcome panel -->

<!-- login panel !-->
<div class="bubblePanel _hackOverFieldPanel" id="loginRegisterPanel">
    <img id="lrCloseIcon" class="closePanelIcon iconPadding" alt="Закрыть форму входа / ввода нового имени"
         src="images/icons/icon_close.png">

    <div class="overFieldInnerPanel">
        <h2 class="lrCommonHeader">Войти в личный кабинет</h2>

        <div id="lrLoginRegisterContainer">




        </div>
    </div>
</div>
<!-- end of register panel -->

<!-- restore password panel !-->

<!-- end of register panel -->

<!-- change password panel !-->
<div class="bubblePanel _hackOverFieldPanel" id="changePassPanel">
    <img id="cpCloseIcon" class="closePanelIcon iconPadding" alt="Закрыть форму входа / ввода нового имени"
         src="images/icons/icon_close.png">

    <div class="overFieldInnerPanel">
        <h2 class="lrCommonHeader">Смена пароля</h2>

        <div id="cpLoginRegisterContainer">

            <div style="padding-top: 10px;" id="cpSection" class="lrSection">
                <form id="cpForm" method="POST" action="#">
                    <input type="hidden" name="action" value="login">
                    <table style="margin-top: 5px;" width="100%">
                        <tr>
                            <td width="35%">Старый пароль</td>
                            <td width="35%">
                                <input type="password" id="cpOldPassword" name="cpOldPassword"/>
                            </td>
                            <td rowspan="3" style="vertical-align: top;">
                                <div style="float: left;" id="cpResult"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Новый пароль</td>
                            <td><input type="password" id="cpNewPassword1" name="cpNewPassword1"/></td>
                        </tr>
                        <tr>
                            <td>Повторите новый пароль</td>
                            <td><input type="password" id="cpNewPassword2" name="cpNewPassword2"/></td>
                        </tr>
                    </table>
                    <br/>

                    <div style="margin-bottom: 32px;">
                        <div class="constantWidthBtn" id="cpCommit">
                            Ок
                        </div>
                        <div class="constantWidthBtn" id="cpCancel">
                            Отмена                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end of change password panel -->    </div>


    <!-- BOTTOM AREA AND PANELS !! обязательно для включения, в блоке, с центрированием !! -->
    <div class="bottomArea" id="bottomArea">
        <!-- profile panel -->
<div class="bubblePanel bottomSubPanel bottomPanel _hackPaddingLayer" id="profilePanel" style="display: none">
    <div id="profileMainTab">
        <img id="profileCloseImg" src="images/icons/icon_close.png" alt="Закрыть"/>

        <div id="profileLoading"
             style="padding-top: 20px; padding-left:20px; height:35px; font-family: Verdana; font-weight: bold;">
            <span style="float: left; color:#444444;">Загрузка...&nbsp;</span>
            <img style="float: left; margin-top: -10px;" src="images/icons/loading.gif">
        </div>

        <div id="profileContents" style="display: none;">
            <div style='float: left;'>
                <h4>Ваш личный кабинет (&laquo;<span id='profileUsername'></span>&raquo;)</h4>
            </div>
            <img src="images/icons/loading.gif" class="profileLoadingIcon" id="profileLoadingIcon">

            <div class="clear"></div>

            <table>
                <tr>
                    <td width="1%" style="vertical-align: top;">
                        <div id="profilePhoto"
                             style="float: left; padding-top: 10px; padding-right: 10px; width: 135px;">
                            <div class="profilePhotoFrame" id="profilePhotoFrame">
                                <img src="images/nophoto.jpg" class="profilePhoto"
                                     alt="Фото не загружено"/>
                            </div>
                        </div>
                    </td>
                    <td style="vertical-align: top;">
                        <div id="profileData" style="float: left; padding-top: 3px;">
                            <table id="profilePIStaticLayout">
                                <tr>
                                    <td>
                                        <div id="profilePIStatic"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="profileEditBtn" class="constantWidthBtn nonSelectable"
                                             style="margin-left: 0px;">Редактировать профиль                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <div id="profilePIEditable">
                                <form id="profileForm" method="post" enctype="multipart/form-data"
                                      action="/gw/profile/updateProfile.php">
                                    <table>
                                        <tr>
                                            <td>День рождения:</td>
                                            <td>
                                                <table style="width: 100%" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td style='text-align: left;'>
                                                            <select name="birthDay" id="profileBirthDay"
                                                                    class="profileShortField">
                                                                <option value='0'>—</option>
                                                                <option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option>                                                            </select>
                                                        </td>
                                                        <td style='text-align: center;'>
                                                            <select name="birthMonth" id="profileBirthMonth"
                                                                    class="profileShortField">
                                                                <option value='0'>—</option>
                                                                <option value='1'>января</option><option value='2'>февраля</option><option value='3'>марта</option><option value='4'>апреля</option><option value='5'>мая</option><option value='6'>июня</option><option value='7'>июля</option><option value='8'>августа</option><option value='9'>сентября</option><option value='10'>октября</option><option value='11'>ноября</option><option value='12'>декабря</option>                                                            </select>
                                                        </td>
                                                        <td style='text-align: right;'>
                                                            <select name="birthYear" id="profileBirthYear"
                                                                    class="profileShortField">
                                                                <option value='0'>—</option>
                                                                
                                                                <option value='2015'>2015</option>
                                                                
                                                                <option value='2014'>2014</option>
                                                                
                                                                <option value='2013'>2013</option>
                                                                
                                                                <option value='2012'>2012</option>
                                                                
                                                                <option value='2011'>2011</option>
                                                                
                                                                <option value='2010'>2010</option>
                                                                
                                                                <option value='2009'>2009</option>
                                                                
                                                                <option value='2008'>2008</option>
                                                                
                                                                <option value='2007'>2007</option>
                                                                
                                                                <option value='2006'>2006</option>
                                                                
                                                                <option value='2005'>2005</option>
                                                                
                                                                <option value='2004'>2004</option>
                                                                
                                                                <option value='2003'>2003</option>
                                                                
                                                                <option value='2002'>2002</option>
                                                                
                                                                <option value='2001'>2001</option>
                                                                
                                                                <option value='2000'>2000</option>
                                                                
                                                                <option value='1999'>1999</option>
                                                                
                                                                <option value='1998'>1998</option>
                                                                
                                                                <option value='1997'>1997</option>
                                                                
                                                                <option value='1996'>1996</option>
                                                                
                                                                <option value='1995'>1995</option>
                                                                
                                                                <option value='1994'>1994</option>
                                                                
                                                                <option value='1993'>1993</option>
                                                                
                                                                <option value='1992'>1992</option>
                                                                
                                                                <option value='1991'>1991</option>
                                                                
                                                                <option value='1990'>1990</option>
                                                                
                                                                <option value='1989'>1989</option>
                                                                
                                                                <option value='1988'>1988</option>
                                                                
                                                                <option value='1987'>1987</option>
                                                                
                                                                <option value='1986'>1986</option>
                                                                
                                                                <option value='1985'>1985</option>
                                                                
                                                                <option value='1984'>1984</option>
                                                                
                                                                <option value='1983'>1983</option>
                                                                
                                                                <option value='1982'>1982</option>
                                                                
                                                                <option value='1981'>1981</option>
                                                                
                                                                <option value='1980'>1980</option>
                                                                
                                                                <option value='1979'>1979</option>
                                                                
                                                                <option value='1978'>1978</option>
                                                                
                                                                <option value='1977'>1977</option>
                                                                
                                                                <option value='1976'>1976</option>
                                                                
                                                                <option value='1975'>1975</option>
                                                                
                                                                <option value='1974'>1974</option>
                                                                
                                                                <option value='1973'>1973</option>
                                                                
                                                                <option value='1972'>1972</option>
                                                                
                                                                <option value='1971'>1971</option>
                                                                
                                                                <option value='1970'>1970</option>
                                                                
                                                                <option value='1969'>1969</option>
                                                                
                                                                <option value='1968'>1968</option>
                                                                
                                                                <option value='1967'>1967</option>
                                                                
                                                                <option value='1966'>1966</option>
                                                                
                                                                <option value='1965'>1965</option>
                                                                
                                                                <option value='1964'>1964</option>
                                                                
                                                                <option value='1963'>1963</option>
                                                                
                                                                <option value='1962'>1962</option>
                                                                
                                                                <option value='1961'>1961</option>
                                                                
                                                                <option value='1960'>1960</option>
                                                                
                                                                <option value='1959'>1959</option>
                                                                
                                                                <option value='1958'>1958</option>
                                                                
                                                                <option value='1957'>1957</option>
                                                                
                                                                <option value='1956'>1956</option>
                                                                
                                                                <option value='1955'>1955</option>
                                                                
                                                                <option value='1954'>1954</option>
                                                                
                                                                <option value='1953'>1953</option>
                                                                
                                                                <option value='1952'>1952</option>
                                                                
                                                                <option value='1951'>1951</option>
                                                                
                                                                <option value='1950'>1950</option>
                                                                
                                                                <option value='1949'>1949</option>
                                                                
                                                                <option value='1948'>1948</option>
                                                                
                                                                <option value='1947'>1947</option>
                                                                
                                                                <option value='1946'>1946</option>
                                                                
                                                                <option value='1945'>1945</option>
                                                                
                                                                <option value='1944'>1944</option>
                                                                
                                                                <option value='1943'>1943</option>
                                                                
                                                                <option value='1942'>1942</option>
                                                                
                                                                <option value='1941'>1941</option>
                                                                
                                                                <option value='1940'>1940</option>
                                                                
                                                                <option value='1939'>1939</option>
                                                                
                                                                <option value='1938'>1938</option>
                                                                
                                                                <option value='1937'>1937</option>
                                                                
                                                                <option value='1936'>1936</option>
                                                                
                                                                <option value='1935'>1935</option>
                                                                
                                                                <option value='1934'>1934</option>
                                                                
                                                                <option value='1933'>1933</option>
                                                                
                                                                <option value='1932'>1932</option>
                                                                
                                                                <option value='1931'>1931</option>
                                                                
                                                                <option value='1930'>1930</option>
                                                                
                                                                <option value='1929'>1929</option>
                                                                
                                                                <option value='1928'>1928</option>
                                                                
                                                                <option value='1927'>1927</option>
                                                                
                                                                <option value='1926'>1926</option>
                                                                
                                                                <option value='1925'>1925</option>
                                                                
                                                                <option value='1924'>1924</option>
                                                                
                                                                <option value='1923'>1923</option>
                                                                
                                                                <option value='1922'>1922</option>
                                                                
                                                                <option value='1921'>1921</option>
                                                                
                                                                <option value='1920'>1920</option>
                                                                
                                                                <option value='1919'>1919</option>
                                                                
                                                                <option value='1918'>1918</option>
                                                                
                                                                <option value='1917'>1917</option>
                                                                
                                                                <option value='1916'>1916</option>
                                                                
                                                                <option value='1915'>1915</option>
                                                                
                                                                <option value='1914'>1914</option>
                                                                
                                                                <option value='1913'>1913</option>
                                                                
                                                                <option value='1912'>1912</option>
                                                                
                                                                <option value='1911'>1911</option>
                                                                
                                                                <option value='1910'>1910</option>
                                                                
                                                                <option value='1909'>1909</option>
                                                                
                                                                <option value='1908'>1908</option>
                                                                
                                                                <option value='1907'>1907</option>
                                                                
                                                                <option value='1906'>1906</option>
                                                                
                                                                <option value='1905'>1905</option>
                                                                
                                                                <option value='1904'>1904</option>
                                                                
                                                                <option value='1903'>1903</option>
                                                                
                                                                <option value='1902'>1902</option>
                                                                
                                                                <option value='1901'>1901</option>
                                                                
                                                                <option value='1900'>1900</option>
                                                                                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Город:</td>
                                            <td>
                                                <input name="where" id="profileWhere" type="text" class="profileField"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ссылка в соц-сети:</td>
                                            <td>
                                                <input name="link" id="profileLink" type="text" class="profileField"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td title = "Для восстановления пароля">Электронная почта:</td>
                                            <td title = "Для восстановления пароля">
                                                <input name="mail" id="profileMail" type="email" class="profileField"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>О себе:</td>
                                            <td>
                                                <textarea name="about" id="profileAbout" type="text"
                                                          class="profileField"></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Фото:</td>
                                            <td>
                                                <input id="profilePhotoField" name="photo" type="file" size="33"
                                                       class="profileField"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="profileCP">
                                                    <div id="profileSaveBtn" class="constantWidthBtn nonSelectable"
                                                         style="margin-left: 0px;">
                                                        Сохранить                                                    </div>
                                                    <div id="profileDiscardChangesBtn"
                                                         class="constantWidthBtn nonSelectable"
                                                         style="margin-left: 0px;">
                                                        Отмена                                                    </div>
                                                    <img id="profileLoadingImg" src="images/icons/loading.gif"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div style="padding-top:15px; padding-left: 3px; clear: both;">
                            <div class="bspTopHeader" id="profileUnreadMsgAlert">
                                <span id='profileReadMsgBtn'>: 1</span>
                            </div>
                            <div style="display: block; padding-bottom: 15px;">
                                <input type="checkbox" id="profileGoInvisible">Скрыть моё пребывание на сайте                                <img id="profileGoInvisibleLoadingImg" src="images/icons/loading_transparent.gif"
                                     style="width: 14px; padding-left: 5px; display: none;" alt=""/>
                            </div>
                            <span class="bspTopHeader" id="profileGoToInbox">
                                Посмотреть личные сообщения                            </span><br>
                            <span class="bspTopHeader" id="sendToAdmin">
                                Написать админу                            </span>
                            <span class="bspTopHeader" id="changePassword">
                                Изменить пароль                            </span>
                        </div>
                        <div id="profileSideActivity"></div>
                    </td>
                </tr>
            </table>

            <div class="clear"></div>
            <div class="profileLogoutPanel">
                <div style="margin-top: 20px; padding-top: 5px; border-top: 1px dashed rgb(204, 204, 204); display: block;">
                    <span class="bspAuxBtn" style="float: left;" id="profileLogoutBtn">[Выйти из ЛК и играть как гость]</span>
                    <span class="bspAuxBtn" style="float: right;" id="shareBtn">[Рекомендовать игру в соц. сетях]</span>
                </div>
                <div class="clear"></div>
            </div>
        </div>

    </div>

    <div id="profileSubTab">
    </div>
</div>
<!-- end of profile panel --><div class="bubblePanel bottomSubPanel bottomPanel _hackPaddingLayer" id="guestBookPanel">
    <img id="gbLoadingIcon" class="loadingIcon" src="images/icons/loading.gif" alt="Загрузка"/>
    <img id="gbCloseIcon" class="closePanelIcon" src="images/icons/icon_close.png"
         alt="Закрыть гостевую книгу"/>

    <div id="gbContents"></div>
</div>
    </div>

</td>


</tr>

    <!-- FOOTER STATS  -->
<tr>
    <td style="text-align: center; padding-bottom: 10px;">
        <!-- !! обязательно для включения, в блоке, с центрированием !! -->
        <div id="activityDiv">
            <p>Сейчас на сайте — гостей: 0, зарегистрированных пользователей: 0 (из 0).</p>
            </a>
    </div>
<div id="vstats">
    <p><p>Всего уникальных посетителей — вчера: 0, сегодня: 0</p>    </p>
</div>
<div id="copyright">&copy;
    Программный продукт <a href="http://v6.spb.ru/" target="_blank">Юридического центра &laquo;Восстания-6&raquo;</a>
    <script type="text/javascript"><!--
    document.write("<img src='//counter.yadro.ru/hit?t39.5;r" +
            escape(document.referrer) + ((typeof(screen) == "undefined") ? "" :
            ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                    screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
            ";" + Math.random() +
            "' " +
            "border='0' width='1' height='1'>")
    //--></script>
</div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter20303959 = new Ya.Metrika({id:20303959,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") +
            "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/20303959"
                    style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->    </td>
</tr>
</table>


<!--- !!!!!!!!!!!!!!!!!!! BONES !!!!!!!!!!!!!!!! ---->

    <div class="die" id="die1">
        <div class="face" data-id="1">
            <div class="pip center middle"></div>
        </div>
        <div class="face" data-id="2">
            <div class="pip top right"></div>
            <div class="pip bottom left"></div>
        </div>
        <div class="face" data-id="3">
            <div class="pip top left"></div>
            <div class="pip center middle"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="4">
            <div class="pip top left"></div>
            <div class="pip bottom left"></div>
            <div class="pip top right"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="5">
            <div class="pip top left"></div>
            <div class="pip bottom left"></div>
            <div class="pip center middle"></div>
            <div class="pip top right"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="6">
            <div class="pip top left"></div>
            <div class="pip middle left"></div>
            <div class="pip bottom left"></div>
            <div class="pip top right"></div>
            <div class="pip middle right"></div>
            <div class="pip bottom right"></div>
        </div>
    </div>
    <div class="die" id="die2">
        <div class="face" data-id="1">
            <div class="pip center middle"></div>
        </div>
        <div class="face" data-id="2">
            <div class="pip top right"></div>
            <div class="pip bottom left"></div>
        </div>
        <div class="face" data-id="3">
            <div class="pip top left"></div>
            <div class="pip center middle"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="4">
            <div class="pip top left"></div>
            <div class="pip bottom left"></div>
            <div class="pip top right"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="5">
            <div class="pip top left"></div>
            <div class="pip bottom left"></div>
            <div class="pip center middle"></div>
            <div class="pip top right"></div>
            <div class="pip bottom right"></div>
        </div>
        <div class="face" data-id="6">
            <div class="pip top left"></div>
            <div class="pip middle left"></div>
            <div class="pip bottom left"></div>
            <div class="pip top right"></div>
            <div class="pip middle right"></div>
            <div class="pip bottom right"></div>
            </div>
    </div>

	<div class="die" id="die3">
		<div class="face" data-id="1">
			<div class="pip centerSmall middle"></div>
		</div>
		<div class="face" data-id="2">
			<div class="pip top right"></div>
			<div class="pip bottom left"></div>
		</div>
		<div class="face" data-id="3">
			<div class="pip top left"></div>
			<div class="pip centerSmall middle"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="4">
			<div class="pip top left"></div>
			<div class="pip bottom left"></div>
			<div class="pip top right"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="5">
			<div class="pip top left"></div>
			<div class="pip bottom left"></div>
			<div class="pip centerSmall middle"></div>
			<div class="pip top right"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="6">
			<div class="pip top left"></div>
			<div class="pip middleSmall left"></div>
			<div class="pip bottom left"></div>
			<div class="pip top right"></div>
			<div class="pip middleSmall right"></div>
			<div class="pip bottom right"></div>
			</div>
	</div>

	<div class="die" id="die4">
		<div class="face" data-id="1">
			<div class="pip center middle"></div>
		</div>
		<div class="face" data-id="2">
			<div class="pip top right"></div>
			<div class="pip bottom left"></div>
		</div>
		<div class="face" data-id="3">
			<div class="pip top left"></div>
			<div class="pip centerSmall middle"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="4">
			<div class="pip top left"></div>
			<div class="pip bottom left"></div>
			<div class="pip top right"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="5">
			<div class="pip top left"></div>
			<div class="pip bottom left"></div>
			<div class="pip centerSmall middle"></div>
			<div class="pip top right"></div>
			<div class="pip bottom right"></div>
		</div>
		<div class="face" data-id="6">
			<div class="pip top left"></div>
			<div class="pip middleSmall left"></div>
			<div class="pip bottom left"></div>
			<div class="pip top right"></div>
			<div class="pip middleSmall right"></div>
			<div class="pip bottom right"></div>
			</div>
	</div>
	

    <p id='server_message'></p>

	<!-- Подключение сторонних библиотек и плагинов -->

	<script type="text/javascript" src="js/kinetic.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui.js"></script>
<!--  	<script type="text/javascript" src="js/socket.io.js"></script>-->
  	<script type="text/javascript" src="js/ion.sound.min.js"></script>
  	<script type="text/javascript" src="js/underscore.js"></script>
  	<script type="text/javascript" src="js/backbone.js"></script>
  	<script type="text/javascript" src="js/jquery.rotate.js"></script>
    <script type="text/javascript" src="js/v6-game-client.js"></script>

  	<!-- скрипты системы -->
  	<script type="text/javascript" src="js/game/socket.js?v=123"></script>
  	<script type="text/javascript" src="js/game/game.js?v=123"></script>
  	<script type="text/javascript" src="js/game/board.js?v=123"></script>
  	<script type="text/javascript" src="js/game/bones.js?v=123"></script>
  	<script type="text/javascript" src="js/game/rules.js?v=123"></script>
  	<script type="text/javascript" src="js/game/piece.js?v=123"></script>
  	<!-- <script type="text/javascript" src="js/auth.js?v=123"></script> -->
	<script type="text/javascript" src="js/main.js?v=123"></script>







</body>
</html>