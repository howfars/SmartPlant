//Setup basic express server
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var mysql = require('mysql');   //调用mysql模块
var bodyParser = require('body-parser');
var http=require('http');
var querystring=require('querystring');

//global varieties
var port = process.env.PORT || 3000;
var dataPoint={
	update_time:null,
	Temperature:null,
	Humidity:null,
	Light:null,
	Moisture:null
};

var returnData={ errno: 0,
    data:
        [ { update_at: '2017-09-07 09:22:47',
            id: 'Temperature',
            create_time: '2017-07-11 10:11:36',
            current_value: '27.90' },
            { update_at: '2017-09-07 09:22:47',
                id: 'Humidity',
                create_time: '2017-07-11 10:11:36',
                current_value: '94.80' },
            { update_at: '2017-09-07 09:22:47',
                id: 'Light',
                create_time: '2017-07-11 10:11:36',
                current_value: '734.00' },
            { update_at: '2017-09-07 09:22:47',
                id: 'Moisture',
                create_time: '2017-07-13 10:24:20',
                current_value: '27.00' } ],
    error: 'succ' };
//向onenet发送get请求，得到最近一次上传的数据
var options={
	hostname: 'api.heclouds.com',
	//port:80,
	path:'/devices/9275610/datastreams?datastream_ids=Temperature,Humidity,Light,Moisture',
	method:'GET',
	headers:{'api-key':'eqhPnXKYnzGx4iMvVpHQ=5uW1CI='}//key headers’ value should be an object.
};

var GetAndSave=function() {
    var req = http.request(options, function (res) {
        res.setEncoding('utf8');
        res.on('data', function (chunk) {
            var returnData = JSON.parse(chunk);
            dataPoint.update_time = returnData.data[0].update_at;
            dataPoint.Temperature = returnData.data[0].current_value;
            dataPoint.Humidity = returnData.data[1].current_value;
            dataPoint.Light = returnData.data[2].current_value;
            dataPoint.Moisture = returnData.data[3].current_value;
            console.log(returnData);
            SaveToMySQL();
        });
    });

//如果有错误会输出错误
    req.on('error', function (e) {
        console.log('错误：' + e.message);
    });
    req.end();
}
setInterval(function(){GetAndSave();},5000);


//
var SaveToMySQL=function() {




    var connection = mysql.createConnection({
        host: '127.0.0.1',
        user: 'root',
        password: 'password',
        port: '3306',
        database: 'StationTest'             //数据库名称
    });

//建立连接
    connection.connect(function (err) {
        if (err) {
            console.log('[query] - :' + err);
            return;
        }
        console.log('[connection connect] succeed!');
    });
    var insertSQL = 'INSERT IGNORE INTO `weathertest` (`update_time`, `Temperature`, `Humidity`, `Light`, `Moisture`) VALUES (?,?,?,?,?)';
    var Params = [dataPoint.update_time, dataPoint.Temperature, dataPoint.Humidity, dataPoint.Light, dataPoint.Moisture];

//执行查询
    connection.query(insertSQL, Params, function (err, result) {

        if (err) {
            console.log('[Insert Error] - ', err.message);
            return;
        }
        console.log('insert succeed!');
    });

//关闭connection
    connection.end(function (err) {
        if (err) {
            return;
        }
        console.log('[connection end] succeed!');
    });
}



var connection = mysql.createConnection({
        host: '127.0.0.1',
        user: 'root',
        password: 'password',
        port: '3306',
        database: 'WiFiStation'             //数据库名称
    });
	
	//建立连接
    connection.connect(function (err) {
        if (err) {
            console.log('[query] - :' + err);
            return;
        }
        console.log('[connection connect] succeed!');
    });

    //var insertSQL = 'insert into weatherdata (temperature,humidity,lightness) values(?,?,?)';
    //var Params = [response.tem, response.hum, response.light];
	var Params = response.msg.value;
	var ds_id = response.msg.ds_id;
	//var Key=ds_id.substr(1,ds_id.length-2);
	var insertSQL = ' insert into weatherdata (ds_id) values(?) ';
	
	

//执行查询
    connection.query(insertSQL, Params, function (err, result) {

        if (err) {
            console.log('[Insert Error] - ', err.message);
            return;
        }
        console.log('insert succeed!');
    });

//关闭connection
    connection.end(function (err) {
        if (err) {
            return;
        }
        console.log('[connection end] succeed!');
    });

*/





