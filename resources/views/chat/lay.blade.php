<!DOCTYPE html>
<html lang="ru" @if(Request::is("*/") && !Session::has("uid"))  @endif>
    <head>
        <title>Чат Джен Кота - @yield('title') бесплатный чат и файлообменник</title>
        <noscript><meta http-equiv="refresh" content="0; URL=/badbrowser"></noscript>
        <base href="https://jencat.ml"/>
        <link rel="canonical" href="{{URL::current()}}"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1.0, user-scalable=no"/>
        <meta name="description" content="VG Chat -  @yield('title') бесплатный чат и файлообменник"/>
        <meta name="keywords" content="VG Chat, chat, чат, файлообменник, Валик Губенко, cj suspend"/>
        <meta name="og:description" content="VG Chat -  @yield('title') бесплатный чат и файлообменник"/>
        <meta name="og:title" content="VG Chat"/>
        <meta name="og:image" content="https://jencat.ml/assets/images/logo.jpg"/>
        <!--[if lt IE 9]>
        <script src="/assets/js/libs/css3-mediaqueries.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" sizes="57x57" href="/extra/icons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/extra/icons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/extra/icons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/extra/icons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/extra/icons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/extra/icons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/extra/icons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/extra/icons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/extra/icons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/extra/icons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/extra/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/extra/icons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/extra/icons/favicon-16x16.png">

        <link rel="manifest" href="/extra/icons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>


        <link rel="stylesheet" href="/assets/css/bootstrap-material-design.min.css"/>
        <link rel="stylesheet" href="/assets/css/ripples.min.css"/>
        <link rel="stylesheet" href="/assets/css/jquery-ui.min.css"/>
        <link rel="stylesheet" href="/assets/css/jquery-ui.theme.min.css"/>
        <link rel="stylesheet" href="/assets/css/animate.min.css"/>
        <link rel="stylesheet" href="/assets/css/animate.min.css"/>
        <link rel="stylesheet" href="/assets/css/toastr.min.css"/>
        <link rel="stylesheet" href="/assets/css/perfect-scrollbar.min.css"/>
        <link rel="stylesheet" href="/assets/css/toastr.min.css"/>
        <link href='https://fonts.googleapis.com/css?family=Didact+Gothic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        {!! \Roumen\Feed\Feed::link(url('/rss/atom'), 'atom', 'Fastest ML News', 'ru') !!}
        {!! \Roumen\Feed\Feed::link(url('/rss/rss'), 'rss', 'Fastest ML News', 'ru') !!}
        @include('chat.styles.main')
        <link href="/assets/css/nouislider.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/assets/css/material-icons.min.css">
<meta name="google-site-verification" content="aAevCYBNHbnj-nOUS2bE0Qg6YD0crti-jJm_0Yh6cqA" />
        <link rel="stylesheet" href="/assets/css/chat.css"/>
        <script type="application/ld+json">
        { "@context" : "http://schema.org",
          "@type" : "Organization",
          "url" : "https://jencat.ml",
          "sameAs" : [ "http://vk.com/id38008511"],
          "contactPoint" : [
            { "@type" : "ContactPoint",
              "telephone" : "+380731000654",
              "contactType" : "customer service"
            } ] }
        </script>
        <script src="/assets/js/md5.js"></script>
        <script>
            /** GLOBALS **/
            var GLOBAL_CHAT_LOADED = false;
            var GLOBAL_AUDIO_LOADED = false;


            var XCache = {
                cache: {},
                has: function(key){

                    var answer = this.cache.hasOwnProperty(key);
                    if(!answer) {
                        console.warn(key+" no such key");
                        console.warn(Object.keys(this.cache));
                    }

                    return answer;
                },
                put: function(key,data) {
                    this.cache[key] = data;
                },
                resolve: function(key) {
                    if(this.cache.hasOwnProperty(key)) {
                        return this.cache[key];
                    }
                },
                clear: function(){
                    this.cache = {};
                    return void(0);
                }
            };


        </script>
        <script src="/assets/js/libs/layzr.min.js"></script>
    </head>
    <body  data-jkit="[background]" ng-class="{ contentLoading: loading }">
    <img id="preloader" src="/extra/anim/gears.gif" alt="Loading..."/>

    <div style="display: none; visibility: hidden">
    
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://jencat.ml/" itemprop="url">
            <span itemprop="title">Fastest</span>
          </a> ›
        </div>
        @if(Request::is("*/"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://jencat.ml/" itemprop="url">
            <span itemprop="title">{{Lang::get('login.login')}}</span>
          </a> ›
        </div>
        @elseif(Request::is("*register"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://jencat.ml/register" itemprop="url">
            <span itemprop="title">{{Lang::get('register.register')}}</span>
          </a> ›
        </div>
        @elseif(Request::is("*remind"))
        <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
          <a href="https://jencat.ml/remind" itemprop="url">
            <span itemprop="title">{{Lang::get('resetting.remind')}}</span>
          </a> ›
        </div>
        @endif
    </div>
    
        <div itemscope itemtype="http://schema.org/Product" style="display:none">
          <span itemprop="brand">Чат Джен Кота</span>
          <span itemprop="name">Чат Джен Кота</span>
    
          <span itemprop="description">Чат Джен Кота
          </span>
          Product #: <span itemprop="mpn">5415433</span>
          <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="ratingValue">5.0</span> stars, based on <span itemprop="reviewCount">5305
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
                        <a class="navbar-brand" href="https://jencat.ml" no-data-pjax>
                            <i class="material-icons" style="position: relative;top:-1px;color:white;">verified_user</i> Чат Джен Кота

                        </a>

                    </h1>
                </div>
                <div class="navbar-collapse collapse navbar-primary-collapse">
                    <div id="sse1">
                        <div id="sses1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a no-data-pjax href="javascript:void(0);" data-target="#" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons">language</i> <span class="menuitem">{{Lang::get('master.language')}}</span>
                                <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a no-data-pjax @if(Session::has('uid')) data-no-instant @endif href="/lang/ru">Русский</a></li>
                                <li><a no-data-pjax @if(Session::has('uid')) data-no-instant @endif href="/lang/en">English</a></li>
                            </ul>
                        </li>
                        @if(Session::has('uid'))
                        <li data-no-instant @if(Request::is("*/"))class="active" @endif><a data-pjax href="/"><i class="material-icons">chat</i> <span class="menuitem">{{Lang::get('master.chat')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*files*"))class="active" @endif ><a data-pjax href="/files"><i class="material-icons">folder_open</i> <span class="menuitem">{{Lang::get('master.files')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*audio*"))class="active" @endif ><a data-pjax href="/audio"><i class="material-icons">volume_up</i> <span class="menuitem">{{trans('master.music')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*users*"))class="active" @endif ><a data-pjax href="/users"><i class="material-icons">supervisor_account</i> <span class="menuitem">{{Lang::get('master.users')}}</span></a></li>
                        <li data-no-instant @if(Request::is("*settings*"))class="active" @endif ><a data-pjax href="/settings"><i class="material-icons">settings</i> <span class="menuitem">{{Lang::get('master.settings')}}</span></a></li>
                        <li><a data-no-instant href="/logout"><i class="material-icons">power_settings_new</i> <span class="menuitem">{{Lang::get('master.logout')}}</span></a></li>
                        @else
                        <li @if(Request::is("*/"))class="active"@endif><a href="/"><i class="material-icons">input</i> <span class="menuitem">{{Lang::get('master.login')}}</span></a></li>
                        <li @if(Request::is("*register"))class="active" @endif><a href="/register"><i class="material-icons">add_circle_outline</i> <span class="menuitem">{{Lang::get('master.register')}}</span></a></li>
                        <li @if(Request::is("*policy"))class="active" @endif><a href="/policy"><i class="material-icons">subject</i> <span class="menuitem">{{Lang::get('master.policy')}}</span></a></li>
                            <li @if(Request::is("*news"))class="active" @endif><a href="/news"><i class="material-icons">subject</i> <span class="menuitem">{{Lang::get('master.news')}}</span></a></li>
                        @endif
                        {{--<li style="vertical-align:middle;display:table-row;"><img style="margin:3px" src="/assets/images/rsz_comodo.png" alt="COMODO SECURE"/></li>--}}
                        {{--<li><a no-data-pjax href="javascript:void(0);">Time: <span id="clock"></span></a></li>--}}
                    </ul>
                            </div></div>
                </div>
            </div>
        </div>
    <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>

    @if(Request::is("*/") && !Session::has('uid'))
        <script src="/assets/js/IMPORTANT.js"></script>
    @else
        <script src="/assets/js/LIBRARIES-min.js"></script>
        <script src="/assets/js/libs/angular-sanitize.min.js"></script>
    @endif



    <script src="/assets/js/libs/parsley.min.js"></script>
    @yield('head_scriptss')


    <script type="text/javascript">
        new WOW().init();
    </script>
    <script src="/assets/js/libs/jquery.pjax.min.js"></script>

    @if(Session::has('uid'))
        @include('chat.scripts.main')
    @endif


        <div class="container" style="margin-top: 49px;" id="pjaxContainer">

            @yield('content')



        </div>
   <!-- <div class="navbar navbar-inverse navbar-bottom">
        <div class="container-fluid">
            <div class="navbar-header">

            </div>
            <div class="navbar-collapse navbar-primary-collapse">
               <center>Fastest LLC, (c) 2016</center>
            </div>
        </div>
    </div> -->




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
        <script src="/assets/js/navigator.js"></script>
        <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter35789225 = new Ya.Metrika({ id:35789225, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/35789225" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

        <script src="/assets/js/libs/drop.js"></script>
    </body>
</html>
