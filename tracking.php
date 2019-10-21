<!DOCTYPE html>
<html class="html" lang="ru-RU">
<head>

    <script type="text/javascript">
        if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["jquery-1.8.3.min.js", "museutils.js", "jquery.watch.js", "jquery.musemenu.js", "tarify-po-sng-i-miru.css"], "outOfDate":[]};
    </script>

    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="generator" content="2015.0.2.310"/>
    <link rel="shortcut icon" href="/favicon.ico">
    <title>Тарифы по СНГ и миру | Ваш курьер</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/site_global.css?4052507572"/>
    <link rel="stylesheet" type="text/css" href="css/master_______-a.css?496128881"/>
    <link rel="stylesheet" type="text/css" href="css/tarify-po-sng-i-miru.css?533705652" id="pagesheet"/>
    <link rel="stylesheet" type="text/css" href="css/tracking.css"/>
    <link rel="stylesheet" type="text/css" href="css/new.css"/>
    <!-- Other scripts -->
    <script type="text/javascript">
        document.documentElement.className += ' js';
    </script>
    <!-- JS includes -->
    <!--[if lt IE 9]>
    <script src="scripts/html5shiv.js?4241844378" type="text/javascript"></script>
    <![endif]-->
    <!--/*

*/
-->
</head>
<body>

<div class="clearfix" id="page"><!-- column -->
    <div class="position_content" id="page_position_content">
        <div class="browser_width colelem" id="u79-bw">
            <div id="u79"><!-- group -->
                <div class="clearfix" id="u79_align_to_page">
                    <div class="clip_frame grpelem" id="u86"><!-- image -->
                        <img class="block" id="u86_img" src="images/telephone.png" alt="" width="23" height="23"/>
                    </div>
                    <div class="clearfix grpelem" id="u92-4"><!-- content -->
                        <p><span id="u92">+7 (495) 646-12-58</span></p>
                    </div>
                    <a class="nonblock nontext MuseLinkActive clearfix grpelem" id="u94-4" href="obratnaya-svyaz.html"><!-- content --><p><span id="u94">Обратная связь</span></p></a>
                </div>
            </div>
        </div>
        <div class="clearfix colelem" id="pu1443-9"><!-- group -->
            <div class="clearfix grpelem" id="u1443-9"><!-- content -->
                <div class="tracking">
                <?php

$login = "ФРЕГАТ 99";
$password = "parshkova";

                $track = isset($_POST['trackNumber']) ? $_POST['trackNumber'] : false;

                $error = false;

                if ($track) {
                    $client = new SoapClient("http://web.cse.ru/cse82_reg/ws/web1c.1cws?wsdl",  array(
                            'trace' => true,
                            'soap_version' => SOAP_1_2,
                            'login' => 'web',
                            'password'=> 'web',
                        )
                    );

                    $trackResult = $client->Tracking(
                        array(
                            'login' => $login,
                            'password' => $password,
                            'documents' => array(
                                'Key' => 'Documents',
                                'Properties' => array(
                                    'Key'  => 'DocumentType',
                                    'Value' => 'Order',
                                    'ValueType' => 'string'
                                ),
                                'List' => array(
                                    'Key' => $track
                                )
                            )
                        )
                    );

                    if (isset($trackResult->return->List->List))  {
                        if (!is_array($trackResult->return->List->List))
                            $trackResult->return->List->List = array($trackResult->return->List->List);

                        // Результат
                        ?>

                        <table>
                            <thead>
                            <tr>
                                <td>Дата</td>
                                <td>Информация</td>
                                <td>Операция</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($trackResult->return->List->List as $list){
                                $date = new DateTime($list->Properties[4]->Value);
                                $datetime = $date->format('d-m-y, H:i');
                                ?>
                                <tr>
                                    <td><?php echo $datetime; ?></td>
                                    <td><?php echo $list->Value; ?></td>
                                    <td><?php echo $list->Key; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                        <?php

                    } else
                        $error = true;

                }

                if (!$track || $error) {
                    //форма
                    ?>

                    <form method="post">
                        <label for="track">Номер заказа:</label>
                        <input type="text" name="trackNumber" value="<?php echo $track; ?>">
                        <?php if ($error) { ?>
                            <p>Введите валидный номер заказа.</p>
                        <?php } ?>
                        <button type="submit">Проверить</button>
                    </form>

                    <?php
                }
                ?>
                </div>
            </div>
            <div class="browser_width grpelem" id="u126-bw">
                <div id="u126"><!-- group -->
                    <div class="clearfix" id="u126_align_to_page">
                        <div class="nonblock nontext MuseLinkActive clearfix grpelem menu-dropdown-item" id="u185-4">
                            <a id="u185" href="/index.html" class="menu-dropdown-item__link">Главная</a>
                            <div class="dropdown">
                                <div class="dropdown__item"><a href="/vyzvat-kurera.html" class="dropdown__link">Вызвать курьера</a></div>
                                <div class="dropdown__item"><a href="/uslugi-kurerskoy-sluzhby.html" class="dropdown__link">Услуги</a></div>
                                <div class="dropdown__item"><a href="/dostavka-dokumentov.html" class="dropdown__link">Доставка документов</a></div>
                                <div class="dropdown__item"><a href="/dostavka-gruzov.html" class="dropdown__link">Доставка грузов</a></div>
                                <div class="dropdown__item"><a href="/telefon-kurerskoy-sluzhby.html" class="dropdown__link">Телефон курьерской службы</a></div>
                                <div class="dropdown__item"><a href="/express-dostavka-dokumentov.html" class="dropdown__link">Экспресс-доставка документов</a></div>
                            </div>
                        </div>
                        <a class="nonblock nontext clearfix grpelem" id="u241-4" href="tarify-moskva-i-oblast.html"><!-- content --><p><span id="u241">Тарифы по Москве и МО</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u242-4" href="tarify-po-rossii.html"><!-- content --><p><span id="u242">Тарифы по России</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u243-4" href="tarify-po-sng-i-miru.html"><!-- content --><p><span id="u243">Тарифы по СНГ и миру</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u555-5" href="taryfny-kalkulyator.html"><!-- content --><p><span id="u555">Калькулятор</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u244-4" href="kontakty.html"><!-- content --><p><span id="u244">Контакты</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u246-4" href="vakansii.html"><!-- content --><p><span id="u246">Вакансии</span></p></a>
                        <a class="nonblock nontext clearfix grpelem" id="u245-4" href="tracking.php"><!-- content --><p><span id="u245">Отследить груз</span></p></a>
                    </div>
                </div>
            </div>
            <div class="clearfix grpelem" id="u96-12"><!-- content -->
                <p id="u96-2"><span id="u96">Срочная доставка документов и</span></p>
                <p><span id="u96-3">грузов «от двери до двери»</span></p>
                <p><span id="u96-5">по Москве, России и миру</span></p>
                <p><span id="u96-7">​</span><span id="u96-8"><span class="actAsInlineDiv normal_text" id="u113"><!-- content --><a class="nonblock nontext actAsDiv clearfix excludeFromNormalFlow" id="u108-6" href="http://lk.y-courier.ru/user/login"><!-- content --><span class="actAsPara"><span id="u108">​</span><span><span class="actAsInlineDiv normal_text" id="u109"><!-- content --><span class="actAsDiv clip_frame excludeFromNormalFlow" id="u110"><!-- image --><img id="u110_img" src="images/key.png" alt="" width="13" height="13"/></span></span></span>&nbsp; Личный кабинет</span></a></span></span><span id="u96-9"></span></p>
            </div>
            <a class="nonblock nontext clip_frame grpelem" id="u114" href="index.html"><!-- image --><img class="block" id="u114_img" src="images/delivery-truck.png" alt=""/></a>
        </div>
        <div class="verticalspacer"></div>
        <div class="browser_width colelem" id="u296-bw">
            <div id="u296"><!-- column -->
                <div class="clearfix" id="u296_align_to_page">
                    <nav class="MenuBar clearfix colelem" id="menuu297"><!-- horizontal box -->
                        <div class="MenuItemContainer clearfix grpelem" id="u298"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu clearfix colelem" id="u301" href="index.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u304-4"><!-- content --><p>Главная</p></div></a>
                        </div>
                        <div class="MenuItemContainer clearfix grpelem" id="u375"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu clearfix colelem" id="u376" href="tarify-moskva-i-oblast.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u378-4"><!-- content --><p>Тарифы по Москве и МО</p></div></a>
                        </div>
                        <div class="MenuItemContainer clearfix grpelem" id="u382"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu clearfix colelem" id="u383" href="tarify-po-rossii.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u384-4"><!-- content --><p>Тарифы по России</p></div></a>
                        </div>
                        <div class="MenuItemContainer clearfix grpelem" id="u389"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu MuseMenuActive clearfix colelem" id="u390" href="tarify-po-sng-i-miru.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u392-4"><!-- content --><p>Тарифы по СНГ и миру</p></div></a>
                        </div>
                        <div class="MenuItemContainer clearfix grpelem" id="u396"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu clearfix colelem" id="u399" href="vakansii.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u400-4"><!-- content --><p>Вакансии</p></div></a>
                        </div>
                        <div class="MenuItemContainer clearfix grpelem" id="u403"><!-- vertical box -->
                            <a class="nonblock nontext MenuItem MenuItemWithSubMenu clearfix colelem" id="u406" href="kontakty.html"><!-- horizontal box --><div class="MenuItemLabel NoWrap clearfix grpelem" id="u409-4"><!-- content --><p>Контакты</p></div></a>
                        </div>
                    </nav>
                    <div class="clearfix colelem" id="u440"><!-- group -->
                        <div class="clip_frame grpelem" id="u434"><!-- image -->
                            <img class="block" id="u434_img" src="images/telephone-litl.png" alt="" width="18" height="18"/>
                        </div>
                        <div class="clearfix grpelem" id="u418-4"><!-- content -->
                            <p>+7 (495) 646-12-58</p>
                        </div>
                    </div>
       <style>
         .studio-link {
            text-align: center;
            margin-top: 115px;
            margin-left: 200px;
            color: #929292;
            font-size: 11px;
         }
         .studio-link a {
            color: #929292 !important;
         }
       </style>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- JS includes -->
<script type="text/javascript">
    if (document.location.protocol != 'https:') document.write('\x3Cscript src="http://musecdn.businesscatalyst.com/scripts/4.0/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
<script type="text/javascript">
    window.jQuery || document.write('\x3Cscript src="scripts/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
<script src="scripts/museutils.js?275725342" type="text/javascript"></script>
<script src="scripts/jquery.watch.js?3999102769" type="text/javascript"></script>
<script src="scripts/jquery.musemenu.js?4042164668" type="text/javascript"></script>
<!-- Other scripts -->
<script type="text/javascript">
    $(document).ready(function() { try {
        (function(){var a={},b=function(a){if(a.match(/^rgb/))return a=a.replace(/\s+/g,"").match(/([\d\,]+)/gi)[0].split(","),(parseInt(a[0])<<16)+(parseInt(a[1])<<8)+parseInt(a[2]);if(a.match(/^\#/))return parseInt(a.substr(1),16);return 0};(function(){$('link[type="text/css"]').each(function(){var b=($(this).attr("href")||"").match(/\/?css\/([\w\-]+\.css)\?(\d+)/);b&&b[1]&&b[2]&&(a[b[1]]=b[2])})})();(function(){$("body").append('<div class="version" style="display:none; width:1px; height:1px;"></div>');
            for(var c=$(".version"),d=0;d<Muse.assets.required.length;){var f=Muse.assets.required[d],g=f.match(/([\w\-\.]+)\.(\w+)$/),k=g&&g[1]?g[1]:null,g=g&&g[2]?g[2]:null;switch(g.toLowerCase()){case "css":k=k.replace(/\W/gi,"_").replace(/^([^a-z])/gi,"_$1");c.addClass(k);var g=b(c.css("color")),h=b(c.css("background-color"));g!=0||h!=0?(Muse.assets.required.splice(d,1),"undefined"!=typeof a[f]&&(g!=a[f]>>>24||h!=(a[f]&16777215))&&Muse.assets.outOfDate.push(f)):d++;c.removeClass(k);break;case "js":k.match(/^jquery-[\d\.]+/gi)&&
            typeof $!="undefined"?Muse.assets.required.splice(d,1):d++;break;default:throw Error("Unsupported file type: "+g);}}c.remove();if(Muse.assets.outOfDate.length||Muse.assets.required.length)c="Некоторые файлы на сервере могут отсутствовать или быть некорректными. Очистите кэш-память браузера и повторите попытку. Если проблему не удается устранить, свяжитесь с разработчиками сайта.",(d=location&&location.search&&location.search.match&&location.search.match(/muse_debug/gi))&&Muse.assets.outOfDate.length&&(c+="\nOut of date: "+Muse.assets.outOfDate.join(",")),d&&Muse.assets.required.length&&(c+="\nMissing: "+Muse.assets.required.join(",")),alert(c)})()})();
        /* body */
        Muse.Utils.transformMarkupToFixBrowserProblemsPreInit();/* body */
        Muse.Utils.prepHyperlinks(true);/* body */
        Muse.Utils.resizeHeight()/* resize height */
        Muse.Utils.initWidget('.MenuBar', function(elem) { return $(elem).museMenu(); });/* unifiedNavBar */
        Muse.Utils.fullPage('#page');/* 100% height page */
        Muse.Utils.showWidgetsWhenReady();/* body */
        Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
    } catch(e) { if (e && 'function' == typeof e.notify) e.notify(); else Muse.Assert.fail('Error calling selector function:' + e); }});
</script>
</body>
</html>


