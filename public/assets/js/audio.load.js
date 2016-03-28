var addToPlaylist;
var loadPlaylist;
var loadSearch;
angular.module('audioService', [])
    .factory('Audio', function ($http) {
        $http.defaults.withCredentials = true;
        return {
            get: function (q) {
                return $http.get('/audio/search?q=' + q);
            }
        }
    });

angular.module('audioController', [])
    .controller('chatCtrl', function ($scope, $http, Audio, $sce) {
        $scope.audios = {};
        $scope.loading = true;
        $scope.trustSrc = function (src) {
            return $sce.trustAsResourceUrl(src);
        };
        $("#loadingInd").show(0);
        Audio.get($("#q").val().trim()).success(function (data) {

            $scope.audios = data;
            $scope.loading = false;
            $("#loadingInd").hide(0);
            $("#allAudio").show();
            setTimeout(slidersInit, 1000);
        });

        $scope.doSearch = function () {
            if ($("#q").val().trim().length == 0) return;

            history.pushState(null, null, "audio?q="+encodeURIComponent($("#q").val().trim()));
            $("#loadingInd").show(0);
            Audio.get($("#q").val().trim()).success(function (data) {
                $scope.audios = data;
                $scope.loading = false;
                $("#loadingInd").hide(0);
                setTimeout(slidersInit, 1000);
            });
        };
        addToPlaylist = function(el){
            var aid = $(el).attr('data-aid');
            var oid = $(el).attr('data-oid');
            if($(el).html() == "done") {
                console.log("already added");
                return;
            }

            $.ajax({
                url:'/audio/add',
                data: {aid: aid, oid: oid},
                type:'POST'
            }).done(function(r){
                $(el).children().eq(0).html('done');

            })
        };

        loadPlaylist = function(){
            $scope.loading = true;
            $("#loadingInd").show(0);

            Audio.get("get_added_audios_secret_query").success(function (data) {

                $scope.audios = data;
                $scope.loading = false;
                $("#loadingInd").hide(0);
                $("#allAudio").show();
                setTimeout(slidersInit, 1000);
            });
        };

        loadSearch = function() {
            $scope.audios = {};
            $scope.loading = true;
            $("#loadingInd").show(0);

            Audio.get($("#q").val().trim()).success(function (data) {

                $scope.audios = data;
                $scope.loading = false;
                $("#loadingInd").hide(0);
                $("#allAudio").show();
                setTimeout(slidersInit, 1000);
            });
        };


    });

var chatApp = angular.module('chatApp', ['audioController', 'audioService', 'ngSanitize'])
    .filter('to_trusted', ['$sce', function ($sce) {
        return function (src) {
            return $sce.trustAsResourceUrl(src);
        };
    }]);
