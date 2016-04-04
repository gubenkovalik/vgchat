
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

    <script type="text/javascript">
        <!--

        //window.checkbrowser = true;
        if (window.checkbrowser) {
            try {
                if (new XMLHttpRequest()) {
                    location.replace('/');
                }
            } catch(e) {
                try {
                    if (new ActiveXObject('Msxml2.XMLHTTP')) {
                        location.replace('/');
                    }
                } catch(e) {}
                try {
                    if (new ActiveXObject('Microsoft.XMLHTTP')) {
                        location.replace('/');
                    }
                } catch(e) {}
            }
        }
        -->
    </script>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <script type="text/javascript" src="https://vk.com/js/iepngfix_tilebg.js?1"></script>

    <title>Вы используете устаревший браузер.</title>

    <link rel="stylesheet" type="text/css" href="https://vk.com/css/al/common.css?513" />

    <style>
        html, body {
            width: 100%;
            height: 100%;
            background: #F7F7F7;
            padding: 0px;
            margin: 0px;
        }
        #bad_browser {
            position: absolute;
            left: 50%;
            top: 50%;
            text-align: center;
            width: 530px;
            margin: -200px 0px 0px -250px;
            background: #FFF;
            line-height: 180%;
            border-bottom: 1px solid #E4E4E4;
            -webkit-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.15);
        }
        #content {
            padding: 20px;
            font-size: 1.19em;
        }
        #head {
            behavior: url(https://vk.com/js/iepngfix.htc?1);
            height: 59px;

        }
        #head.head1 {

        }
        #content div {
            margin: 10px 0 15px 0;
        }
        #content #browsers {
            width: 480px;
            height: 136px;
            margin: 15px auto 0px;
        }
        #browsers a {
            behavior: url(https://vk.com/js/iepngfix.htc?1);
            float: left;
            width: 120px;
            height: 20px;
            padding: 106px 0px 13px 0;
            -webkit-border-radius: 4px;
            -khtml-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
        #browsers a:hover {
            text-decoration: none;
            background-color: #edf1f5!important;
        }
        .is_2x #head {

            background-size: 132px 26px;
        }
        .is_2x #head.head1 {

            background-size: 44px 26px;
        }
        .is_2x #browsers a  {
            background-size: 80px 80px!important;
        }
    </style>
    <!--[if lte IE 8]>
    <style>
        #bad_browser {
            border: none;
        }
        #wrap {
            border: solid #C3C3C3;
            border-width: 0px 1px 1px;
        }
        #content {
            border: solid #D9E0E7;
            border-width: 0px 1px 1px;
        }
    </style>
    <![endif]-->

</head>

<body class="">

<div id="bad_browser">
    <div id="head" class="head"></div>
    <div id="wrap"><div id="content">
            <?=trans('master.bb_head');?>
            <div>
                <?=trans('master.bb_desc');?>
                <div id="browsers" style="width: 360px;"><a href="http://www.google.com/chrome/" target="_blank" style="background: url(https://vk.com/images/chrome.png?1) no-repeat 50% 17px;">Chrome</a><a href="http://www.opera.com/" target="_blank" style="background: url(https://vk.com/images/opera.png?1) no-repeat 50% 15px;">Opera</a><a href="http://www.mozilla-europe.org/" target="_blank" style="background: url(https://vk.com/images/firefox.png?1) no-repeat 50% 17px;">Firefox</a></div>
            </div>
        </div></div>
</div>

</body>