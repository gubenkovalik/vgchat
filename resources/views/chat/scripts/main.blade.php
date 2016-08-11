<script src="/assets/js/libs/angular-pjax.js"></script>
<script>
    var player;
    var playing_id;
    var state = "stopped";
    var updater;
    var slider;

    var slidersInit = function(){
        $("div.player-controls > div.slider").each(function () {

            $(this).empty().slider({
                value: 0,
                min:0,
                range: "max",
                disabled: true,
                animate: true
            });
        });
        if(state == "playing") {
            playTrack($("[data-aid='"+playing_id+"'").children().eq(0), true);
        }
    };
    var updaterTask = function(){
        slider.slider({
            value: player.currentTime
        });
        var minutes = Math.floor(player.currentTime / 60);

        var seconds = Math.floor(player.currentTime - minutes * 60);

        if(minutes < 10) minutes = "0"+minutes;
        if(seconds < 10) seconds = "0"+seconds;

        $("[data-aid='"+playing_id+"'] .ui-slider-handle").attr('title', minutes+":"+seconds);
        $("[data-aid='"+playing_id+"'] .currentTime").html(minutes+":"+seconds);

        var audio = player;

        if(audio != null) {
            console.log("Start: " + audio.buffered.start(0));
            console.log("End: " + audio.buffered.end(0));

        }
    };

    var playTrack = function(cl, dontPause) {

        var el = $(cl).parent();
        var id = $(el).attr('data-aid');
        $(cl).blur();
        $(cl).mouseleave();

        if(dontPause == true) {
            clearInterval(updater);
            $(cl).children().eq(0).html('pause');
            updater = setInterval(updaterTask, 1000);
            state = "playing";
        } else {
            if(state == "playing") {

                if (playing_id == id) {
                    player.pause();
                    $(cl).children().eq(0).html('play_arrow');
                    clearInterval(updater);
                    state = "stopped";
                    return;
                }
            } else if(state == "stopped"){

                if(playing_id == id){
                    player.play();
                    $(cl).children().eq(0).html('pause');
                    updater = setInterval(updaterTask, 1000);
                    state = "playing";
                    return
                }
            }
            slidersInit();
        }






        var src = $(el).attr('src');
        var duration = $(el).attr('data-duration');
        console.log(duration);
        slider = $(el).children().eq(1);
        slider.empty().slider({
            max: duration,
            value:0,
            disabled:false,
            animate: true,
            range: "max",
            min: 0,
            slide: function(ui, val){
                player.currentTime = val.value
            }
        });
        if(dontPause == true) {
            return;
        }
        if(player != null){
            player.pause();
            clearInterval(updater);
        }
        $('.playbtnind').html('play_arrow');
        $('.currentTime').html('00:00');

        player = new Audio(src);
        player.play();
        state = "playing";
        playing_id = id;
        $(cl).children().eq(0).html('pause');
        console.log(slider);

        updater = setInterval(updaterTask, 1000);
    };



    var playing;
</script>
<script data-no-instant src="/assets/js/socket/io.js"></script>
<script>
    var _sessid = "{{Session::get('sessid')}}";

    var socket = io.connect('https://jencat.ml:3000', {secure: true});


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
    @if(!Request::is("*/"))
    socket.on('chat message', function(msg){
        if(msg.nickname != _nickname){
            toastr.warning(msg.message, msg.nickname);
        }
    });
    @endif

</script>
<script>
    function tpl(data){
        return '<div class="msg ng-scope wow lightSpeedIn"   data-wow-duration="0.5s"  style="background-color:rgba(255, 156, 13, 0.05);" ng-hide="loading" ng-repeat="message in messages">\
\
                    <div ng-hide="loading" class="list-group-item " >\
                    <div class="row-picture">\
                       \
                        <span style="display: inline" class="dataUserOnline_'+data.user_id+'">\
                            <span style="color:green;vertical-align: middle; display:inline;">• </span>\
                        </span>\
            <img style="display: inline" height="80px" class="circle avatarImage" src="'+data.avatar+'" alt="'+data.nickname+'">\
                    </div>\
                    <div class="row-content">\
                    <h4 class="list-group-item-heading">'+data.nickname+'</h4>\
            \
                <p class="list-group-item-text" ng-bind-html="message.message | to_trusted">'+data.message+'</p>\
                <span style="float:right;font-size:8pt;color:#888" class="msg-right">'+data.date+'</span>\
                </div>\
                </div>\
            \
            <'+'script'+'>\
            setTimeout(function(){\
                $(".msg").animate({backgroundColor: "white"}, 2200);\
            }, 2000);\
            <'+'/script'+'>\
                <div class="list-group-separator"></div>\
                </div>';
    }

    function rebootstrap(){

        return;
        $('[ng-app]').each(function(){
            angular.bootstrap(this, [$(this).attr('ng-app')])
        });
        $('[ng-controller] .ng-deferred').show();
        $('[ng-controller] .ng-loading').hide();
    }
</script>