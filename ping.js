require('dotenv').config()
var ping = require ("net-ping");
var mysql = require('mysql');

var session = ping.createSession ();

var interval = 30;
var targets = ["192.168.1.3", "192.168.1.1", "192.168.1.30"];


setInterval(function (){
    for (var i = 0; i < targets.length; i++) {
        session.pingHost(targets[i], function (error, target, sent, rcvd) {
            var rtt = rcvd - sent;
            if (error) {
                sendToDB(target, 0);
                console.log(target + ": " + error.toString());
            } else {
                sendToDB(target, 1, rtt);
                console.log(target + ": Alive (ms=" + rtt + ")");
                console.log("Sent at: " + sent);
            }
        });
    }
}, interval * 1000);

function sendToDB(ip, status, rtt) {
    var connection = mysql.createConnection({
        host     : process.env.DB_HOST,
        user     : process.env.DB_USER,
        password : process.env.DB_PASS,
        database : process.env.DB_NAME
    });

    connection.connect(function(err) {
        if (err) throw err;
        console.log("Connected!");
        var sql = "INSERT INTO liveclient (client_ip, is_alive, rtt) VALUES ?";
        var values = [
            [ip, status, rtt]
        ];
        connection.query(sql, [values], function (err, result) {
            if (err) throw err;
            console.log("Number of records inserted: " + result.affectedRows);
            connection.end();
        });
    });
}
