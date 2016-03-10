
angular.module('chatService', [])
    .factory('Chat', function($http) {
        $http.defaults.withCredentials = true;
        return {
            get : function(skip) {
                return $http.get('/api/get?skip='+skip);
            },

            save : function(chatData) {


                return $http({
                    method: 'POST',
                    url: '/api/send',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Cookie': document.location
                    },
                    data: $.param(chatData)
                });
            }
        }

    });
