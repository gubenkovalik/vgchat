<!DOCTYPE html>
<html ng-app="chatApp" lang="ru" @if(Request::is("*/") && !Session::has("uid"))  @endif>
    <head>
        <title>VG Chat - @yield('title') бесплатный чат и файлообменник</title>
        <base href="https://fastest.ml"/>
        <link rel="canonical" href="{{URL::current()}}"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0, user-scalable=no"/>
        <meta name="description" content="VG Chat - @yield('title') бесплатный чат и файлообменник"/>
        <meta name="keywords" content="VG Chat, chat, чат, файлообменник, Валик Губенко, cj suspend"/>
        <meta name="og:description" content="VG Chat - @yield('title') бесплатный чат и файлообменник"/>
        <meta name="og:title" content="VG Chat"/>
        <meta name="og:image" content="https://fastest.ml/assets/images/logo.jpg"/>
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <![endif]-->
        <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="/assets/css/bootstrap-theme.min.css"/>
        <link rel="stylesheet" href="/assets/css/bootstrap-material-design.min.css"/>
        <link rel="stylesheet" href="/assets/css/ripples.min.css"/>
        <link rel="stylesheet" href="/assets/css/jquery-ui.min.css"/>
        <link rel="stylesheet" href="/assets/css/jquery-ui.theme.min.css"/>
        <link rel="stylesheet" href="/assets/css/animate.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css"/>
        <link href='https://fonts.googleapis.com/css?family=Didact+Gothic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <style>
            @media screen and (max-width: 850px) {
                .menuitem {
                    display: none !important;
                }
            }
            @media screen and (max-width: 768px) {
                .menuitem {
                    display: inline !important;
                }
            }
            body {
                background-image: url('/assets/images/bg2.jpg');
                background-attachment: fixed;
            }
            label, .control-label {
                color: rgba(91, 91, 91, 1) !important;

            }
            * {
                font-smooth: always !important;
                font-smooth: 2em !important;
                -webkit-font-smoothing: subpixel-antialiased !important;
                -moz-osx-font-smoothing: grayscale !important;
                font-family: Tahoma, sans-serif;;
                text-shadow: rgba(0, 0, 0, 0.18) 1px 1px 1px;
                -webkit-text-shadow: rgba(0, 0, 0, 0.18) 1px 1px 1px;
            }
        </style>
         
        <link rel="stylesheet" href="/assets/css/material-icons.min.css">
<meta name="google-site-verification" content="aAevCYBNHbnj-nOUS2bE0Qg6YD0crti-jJm_0Yh6cqA" />
        <link rel="stylesheet" href="/assets/css/chat.css"/>
        <script type="application/ld+json">
        { "@context" : "http://schema.org",
          "@type" : "Organization",
          "url" : "https://fastest.ml",
          "sameAs" : [ "http://vk.com/id38008511"],
          "contactPoint" : [
            { "@type" : "ContactPoint",
              "telephone" : "+380731000654",
              "contactType" : "customer service"
            } ] }
        </script>

         

        

    </head>
    <body ng-controller="chatCtrl" data-jkit="[background]">

    <div style="display: none; visibility: hidden">
    
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://fastest.ml/" itemprop="url">
            <span itemprop="title">Fastest</span>
          </a> ›
        </div>
        @if(Request::is("*/"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://fastest.ml/" itemprop="url">
            <span itemprop="title">{{Lang::get('login.login')}}</span>
          </a> ›
        </div>
        @elseif(Request::is("*register"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://fastest.ml/register" itemprop="url">
            <span itemprop="title">{{Lang::get('register.register')}}</span>
          </a> ›
        </div>
        @elseif(Request::is("*remind"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://fastest.ml/remind" itemprop="url">
            <span itemprop="title">{{Lang::get('resetting.remind')}}</span>
          </a> ›
        </div>
        @endif
    </div>
    
        <div itemscope itemtype="http://schema.org/Product" style="display:none">
          <span itemprop="brand">VG Chat</span>
          <span itemprop="name">VG Chat</span>
    
          <span itemprop="description">VG Free Chat
          </span>
          Product #: <span itemprop="mpn">5415433</span>
          <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="ratingValue">5.0</span> stars, based on <span itemprop="reviewCount">784
              </span> reviews
          </span>

        </div>
        <div class="navbar navbar-inverse fixed navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-primary-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <h1 style="display:inline;margin:0;padding:0; font-size:inherit;">
                        <a class="navbar-brand" href="https://fastest.ml">
                            <i class="material-icons text-primary" style="position: relative;top:-1px;">verified_user</i> VG Chat
                        </a>
                    </h1>
                </div>
                <div class="navbar-collapse collapse navbar-primary-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="javascript:void(0);" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons">language</i> <span class="menuitem">{{Lang::get('master.language')}}</span>
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a data-no-instant @if(Session::has('uid')) data-no-instant @endif href="/lang/ru">Русский</a></li>
                                <li><a data-no-instant @if(Session::has('uid')) data-no-instant @endif href="/lang/en">English</a></li>
                            </ul>
                        </li>
                        @if(Session::has('uid'))
                        <li data-no-instant @if(Request::is("*/"))class="active" @endif><a href="/"><i class="material-icons">chat</i> <span class="menuitem">{{Lang::get('master.chat')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*files*"))class="active" @endif ><a href="/files"><i class="material-icons">folder_open</i> <span class="menuitem">{{Lang::get('master.files')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*users*"))class="active" @endif ><a href="/users"><i class="material-icons">supervisor_account</i> <span class="menuitem">{{Lang::get('master.users')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*settings*"))class="active" @endif ><a href="/settings"><i class="material-icons">settings</i> <span class="menuitem">{{Lang::get('master.settings')}}</span></a></li>
                        <li><a data-no-instant href="/logout"><i class="material-icons">power_settings_new</i> <span class="menuitem">{{Lang::get('master.logout')}}</span></a></li>
                        @else
                        <li @if(Request::is("*/"))class="active"@endif><a href="/"><i class="material-icons">input</i> <span class="menuitem">{{Lang::get('master.login')}}</span></a></li>
                        <li @if(Request::is("*register"))class="active" @endif><a href="/register"><i class="material-icons">add_circle_outline</i> <span class="menuitem">{{Lang::get('master.register')}}</span></a></li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
        <div class="container" style="margin-top: 49px;">
            @yield('content')
        </div>
        <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
        @if(Request::is("*/") && !Session::has('uid'))
            <script src="/assets/js/IMPORTANT.js"></script>
        @else
            <script src="/assets/js/LIBRARIES-min.js"></script>
        @endif
    
    @if(Session::has('uid'))
        <script data-no-instant src="/assets/js/socket/io.js"></script>
        <script>
            var _sessid = "{{Session::get('sessid')}}";
            var socket = io.connect('https://fastest.ml:3000', {secure: true});

            Notification.requestPermission(function(){});
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "onclick": null,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "7000",
                "extendedTimeOut": "3000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            socket.on("user notify", function(data){
                console.log(data);
                if(data.uid == '{{Session::get('uid')}}'){

                    toastr.info(data.msg, data.nickname);
                    ion.sound.play("notification");
                    try {
                        ion.sound.play("notification");
                        var mailNotification = new Notification(data.nickname, {
                            tag: Math.random().toString(),
                            body: data.msg,
                            icon: data.avatar
                        });

                    } catch(e){

                    }
                }
            });

            socket.on("broadcast", function(data){
                console.log(data);
                toastr.success(data.msg);
                try {
                    ion.sound.play("notification");
                    var mailNotification = new Notification(data.msg);
                } catch(e){

                }
            });


        </script>
        @endif
        
        <script src="/assets/js/libs/parsley.min.js"></script>
        @yield('head_scriptss')
     

        <script type="text/javascript">
            new WOW().init();
        </script>

       

        @yield('scripts')
        <script>
            eval(function(p,a,c,k,e,d){e=function(c){return c};if(!''.replace(/^/,String)){while(c--){d[c]=k[c]||c}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(0).1(2(){$.3.4()});',5,5,'document|ready|function|material|init'.split('|'),0,{}))
        </script>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-35904290-5', 'auto');
          ga('send', 'pageview');

          
        </script>
 
        <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter35789225 = new Ya.Metrika({ id:35789225, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/35789225" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    </body>
</html>