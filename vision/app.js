var express = require('express');
var fs = require('fs');
var util = require('util');
var mime = require('mime');
var multer = require('multer');
var upload = multer({
    dest: 'uploads/'
});
var querystring = require('querystring');
var request = require('request');
var http = require('http');
var config = {
    projectId: 'lahackhack-199707',
    keyFilename: 'LAHack-594fb78d43e9.json'
};
var datastore = require('@google-cloud/datastore')(config);
var storage = require('@google-cloud/storage')(config);
var vision = require('@google-cloud/vision');
var app = express();

// Simple upload form 
var form = '<!DOCTYPE HTML><html><body>' +
    "<form method='post' action='/upload' enctype='multipart/form-data'>" +
    "<input type='file' name='image'/>" +
    "<input type='submit' /></form>" +
    '</body></html>';

function base64Image(src) {
    var data = fs.readFileSync(src).toString('base64');
    return util.format('data:%s;base64,%s', mime.lookup(src), data);
}
app.get('/', function(req, res) {
    res.writeHead(200, {
        'Content-Type': 'text/html'
    });
    res.end("Hey! Did you mean to issue a specific request?");
});
app.get('/api/img', function(req, res) {
    var take_after = req.query.take_after;
    var id, img, createdAt, objects;
    // res.end(take_after);
    // What url to request?
    request('/api/img', function(error, response, body) {
        if (!error && response.statusCode == 200) {
            /*
              FORMAT:
              {
                  id: string;
                  img: string;
                  createdAt: number;
              }
            */
            var json = JSON.parse(body);
            id = json.id;
            img = base64Image(json.img);
            createdAt = json.createdAt;
            objects = [];
            client
                .labelDetection(img)
                .then(results => {
                    // res.write('<img width=200 src="' + img + '"><br>');
                    const labels = results[0].labelAnnotations;
                    console.log('Labels:');
                    labels.forEach(label => console.log(label));
                    labels.forEach(label => objects.push(label));
                    // res.end('</body></html>');
                })
                .catch(err => {
                    console.error('ERROR:', err);
                });
        }
    });
});

var i = 0;
/*
  Where the main server logic for vision occurs
*/
function periodic() {
    i += 1000;
    var stamp = String(i);
    var id;
    var img
    var data, createdAt;
    var label;
    var objects;
    var final;
    /*
      Send a HTTP Request
    */
    request({
        url: 'https://lahackhack-199707.appspot.com/api/img',
        qs: {
            "taken_after": stamp
        }
    }, function(err, response, body) {
        console.log("Get response: " + response.statusCode);
        var json = JSON.parse(body);
        data = json.data;
        // Start vision client
        var client = new vision.ImageAnnotatorClient();
        objects = [];
        final = [];
        //Iterated through parsed data
        for (var i in data) {
            if (data.hasOwnProperty(i)) {
                // Initialize temp array, which will be stored in final[][]
                /*
                  Data structure is as follows:

                  Data
                    0
                      id
                      img
                      takenAt
                    1
                      id
                      img
                      takenAt
                  */
                var temp = [];
                id = data[i].id;
                img = data[i].img;
                createdAt = data[i].takenAt;
                //decode image from base64 string
                var buf = Buffer.from(img, 'base64');
                //Vision processing
                client
                    .labelDetection(buf)
                    .then(results => {
                        const labels = results[0].labelAnnotations;
                        console.log('Labels:');
                        //For testing 
                        labels.forEach(label => console.log(label));
                        temp.push(id);
                        temp.push(label);
                        final.push(temp);
                    })
                    .catch(err => {
                        console.error('ERROR:', err);
                    });
            }
        }
        console.log(final);
    });
    /*
      Now that we've gone through data needed for processing, we now need to send this data
    */
    for (var i in final) {
      /*
        Final structure is as follows
        [
          [id, label],
          ....
        ]
      */
        var id = final[i][0];
        var objects = final[i][1];

        var postData = querystring.stringify({
            data: JSON.stringify({
                imgId: id,
                objects: objects
            })
        });
        // E.g. query: https://lahackhack-199707.appspot.com/api/objects_of_imgs?imgId=2;objects:smartphone
        var options = {
            hostname: 'https://lahackhack-199707.appspot.com',
            method: 'POST',
            path: '/api/objects_of_imgs',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Content-Length': Buffer.byteLength(postData)
            }
        };
        //Where the POSTing occurs
        var sendRequest = function(options) {
            that = this;
            that.req = http.request(options, function(res) {
                // console.log("Request began");
                var output = '';

                res.on('data', function(chunk) {
                    output += chunk;
                });

                res.on('end', function() {
                    console.log(output);
                });
            });

            that.req.on('error', function(err) {
                console.log("Server Error");
                console.log('Error: ' + err.message);
            });

            that.req.write(postData);
            that.req.end();
        };

        sendRequest(options);
    }
}
setInterval(periodic, 1000); //time is in ms

app.listen(9090);
console.log('Server Started');

// Turn image into Base64 so we can display it easily


module.exports = app;