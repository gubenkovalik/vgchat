

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