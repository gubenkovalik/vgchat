// CAUTION!!! THIS FILE USES HTTPS as SERVER USES SSL
// CHANGE https TO http IF YOUR WEB SERVER IS NOT ON SSL

var fs = require('fs');
var ss = require('socket.io-stream');
var path = require('path');
/** THIS IS ONLY FOR SSL **/
var privateKey = fs.readFileSync('/home/valik/ssl/domain.key').toString();
var certificate = fs.readFileSync('/home/valik/ssl/jencat.ml.crt').toString();
var ca = fs.readFileSync('/home/valik/ssl/inter.crt').toString();
/** END **/


var http = require('http'); // FOR HTTPS
var https = require('https'); // FOR SSL
var server = https.createServer({key: privateKey, cert: certificate, ca: ca}, function (req, res) {

});
//var server = https.createServer({key:privateKey,cert:certificate,ca:ca}, function(req, res){ //// FOR HTTP
//
//});

var io = require('socket.io').listen(server);
var redis = require("redis"),
    client = redis.createClient();

var activeUsers = 0;
var typingUsers = 0;

console.reset = function () {
    return process.stdout.write('\033c');
};
var nicknames = {};
function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax = arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}
osize = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
function strip_tags(str) {
    return str.replace(/<\/?[^>]+>/gi, '');
}
client.on("error", function (err) {
    console.log("Error " + err);
});

server.listen(3000, function () {
    console.log("Running! OK");
    console.log(">");
});
io.on('connection', function (socket) {

    onlineUpdater();
    activeUsers++;
    socket.on('disconnect', function (msg) {
        activeUsers--;
    });



    socket.on('chat message', function (msg) {
        console.log(msg);
        var sessid = msg.sessid;
        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {
                msg.message = strip_tags(msg.message);
                io.emit('chat message', msg);
            }
        });
    });
    socket.on('chat bell', function (data) {
        var sessid = data.sessid;

        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {
                io.emit('chat bell', {nickname: data.nickname});
            }
        });
    });

    socket.on('chat typing', function (data) {
        var sessid = data.sessid;
        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {

                var nick = data.nickname;
                if (!nicknames.hasOwnProperty(nick)) {
                    nicknames[nick] = new Date().getTime();
                }
                io.emit('chat typing', nicknames);
            }
        });
    });
    socket.on('chat notyping', function (data) {
        var sessid = data.sessid;
        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {

                var nick = data.nickname;
                if (nicknames.hasOwnProperty(nick)) {
                    delete nicknames[nick];

                    io.emit('chat typing', nicknames);
                }
            }
        });
    });

    socket.on('user notify', function (data) {
        console.log("Notification: " + data.uid + " " + data.msg);
        var sessid = data.sessid;
        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {

                var uid = data.uid;
                var msg = data.msg;
                var nickname = data.nickname;
                var avatar = data.avatar;
                var loclink = data.loclink;

                io.emit('user notify', {uid: uid, msg: msg, nickname: nickname, avatar: avatar, loclink: loclink});

            }
        });
    });

    socket.on('activity', function (data) {
        var sessid = data.sessid;
        client.get('secure:' + sessid, function (err, reply) {
            if (reply != null) {

                var id = data.id;
                io.emit('activity', {active: data.active, id: id});
            }
        });
    });

    socket.on('broadcast', function (data) {
        console.log("Broadcast: " + data.msg);
        io.emit('broadcast', {msg: data.msg})
    });
    socket.on('gps', function (data) {
        var imei = data.imei;
	console.log(imei);
        io.emit('gps '+imei);
    });
});
setInterval(function () {
    for (var index in nicknames) {
        var attr = nicknames[index];
        if ((new Date().getTime() - attr) > 4000) {

            delete nicknames[index];

        }
    }
  
    io.emit('chat typing', nicknames);

}, 3000);
var importantMsg = "";
setInterval(function () {
    console.reset();
    console.log(activeUsers + " active users");
    console.log(osize(nicknames) + " typing users");
    console.log(importantMsg);
}, 3000);
var onlineUpdater = function () {

    if (activeUsers < 1) return;

    var req = https.get('https://jencat.ml/users/online/get', function (res) {

        var bodyChunks = [];
        res.on('data', function (chunk) {
            // You can process streamed parts here...
            bodyChunks.push(chunk);
        }).on('end', function () {

            try {
                var body = JSON.parse(Buffer.concat(bodyChunks));
                io.emit("users online", body);
                importantMsg = "";
            } catch (e) {
                importantMsg = "Server error...";
            }
        })
    });

    req.on('error', function (e) {
        console.log(e);
    });
};
setInterval(onlineUpdater, 5000);

