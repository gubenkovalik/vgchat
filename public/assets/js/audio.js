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
};

var playTrack = function(cl) {

    var el = $(cl).parent();
    var id = $(el).attr('data-aid');
    $(cl).blur();
    $(cl).mouseleave();
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

function changeTrack(el){

    if($(el).attr('id') == playing) return;

    var audios = document.getElementsByTagName('audio');


    for (i = 0; i < audios.length; i++) {
        audios[i].pause();
        audios[i].currentTime = 0;
    }

    el.play();

    playing = $(el).attr('id');
}