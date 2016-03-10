var chatApp = angular.module('chatApp', ['chatCtrl', 'chatService'])
    .filter('to_trusted', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);


$('*').keypress(function(){
   $("#message").focus();
});

ion.sound({
    sounds: [
        {name: "notification"}
    ],

    // main config
    path: "/assets/sounds/",
    preload: true,
    multiplay: true,
    volume: 0.9
});