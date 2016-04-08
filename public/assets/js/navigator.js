//function startTime() {
//    var today = new Date();
//    var h = today.getHours();
//    var m = today.getMinutes();
//    var s = today.getSeconds();
//    m = checkTime(m);
//    s = checkTime(s);
//    document.getElementById('clock').innerHTML =
//        h + ":" + m + ":" + s;
//    var t = setTimeout(startTime, 500);
//}
//function checkTime(i) {
//    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
//    return i;
//}

$.pjax.defaults.timeout = 6000;

//$(document).pjax('a', '#pjaxContainer');
$(document).on('click', 'a', function (e) {
    var _this = this;
    var url = $(this).attr('href');

    var noPjax = $(this).attr("no-data-pjax") != null;

    if (noPjax) {
        location.href = url;
        return;
    }



    e.preventDefault();
    e.stopImmediatePropagation();
    e.stopPropagation();
    $("#preloader").fadeIn(200);
    $('#pjaxContainer').fadeOut(200);
    setTimeout(function () {
        $('li').removeClass('active');
        $(_this).parent().addClass('active');
        $.pjax({url: url, container: '#pjaxContainer'});
        afterLoadPjax();

    }, 200)


});
function afterLoadPjax(){
    $('#pjaxContainer').fadeIn(200);
    $("#preloader").fadeOut(200);
}
$.fn.extend({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $(this).addClass('animated ' + animationName).one(animationEnd, function () {
            $(this).removeClass('animated ' + animationName);
        });
    }
});
$(document).ready(function () {
    $("#preloader").fadeOut(300);
});
$(document)
    .on('pjax:start', function () {

    })
    .on('pjax:end', function () {
        afterLoadPjax();

    });
