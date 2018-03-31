
var express = require('express');
var fs = require('fs');
var util = require('util');
var mime = require('mime');
var multer = require('multer');
var upload = multer({dest: 'uploads/'});
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
app.get('/api/img', function(req, res){
  var take_after = req.query.take_after;
  var id, img, createdAt,objects;
  // res.end(take_after);
  // What url to request?
  request('/api/img', function (error, response, body) {
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
      objects =[];
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
function periodic() {
  i+=1000;
  var stamp = String(i);
  var id;
  var img
  var data, createdAt;
  var label;
  var objects;
  var final;
  request({url:'https://lahackhack-199707.appspot.com/api/img', qs:{"taken_after":stamp}}, function(err, response, body) {
    // if(err) { console.log(err); return; }
    console.log("Get response: " + response.statusCode);
    var json = JSON.parse(body);
    // console.log(json);
    var client = new vision.ImageAnnotatorClient();
    data = json.data;
    objects = [];
    final = [];
    for (var i in data) {
        if (data.hasOwnProperty(i)) {
         var temp = [];
         id = data[i].id;
         img= data[i].img;
         createdAt = data[i].takenAt;
         var buf = Buffer.from(img, 'base64');
         client
           .labelDetection(buf)
           .then(results => {
             const labels = results[0].labelAnnotations;
             console.log('Labels:'); 
             labels.forEach(label => console.log(label));
             // labels.forEach(label => objects.push(label));
             // // confirm later
             // label = objects[3];
             // console.log(label);
             temp.push(id);
             temp.push(label);
             final.push(temp);
           })
           .catch(err => {
             console.error('ERROR:', err);
           }); 
        }
    }
    console.log(id);
    console.log(objects);
  });
}
//   for(var i in final){
//     var id = final[i][0];
//     var objects = final[i][1];
  
//   var postData = querystring.stringify({data:JSON.stringify ({imgId:id,objects:objects})});
//   var options = {
//       hostname: 'lahackhack-199707.appspot.com',
//       method: 'POST',
//       path: '/api/objects_of_imgs',
//       headers: {
//           'Content-Type': 'application/x-www-form-urlencoded',
//           'Content-Length': Buffer.byteLength(postData)
//       }
//   };
//   var sendRequest = function(options)
//   {
//       that = this;
//       that.req = http.request(options,function(res)
//       {
//           // console.log("Request began");
//           var output = '';

//           res.on('data', function (chunk) {
//               output += chunk;
//           });

//           res.on('end', function () {
//               console.log(output);
//           });
//       });

//       that.req.on('error', function (err)
//       {
//           console.log("Server Error");
//           console.log('Error: ' + err.message);
//       });

//       that.req.write(postData);
//       that.req.end();
//   };

//   sendRequest(options);
// }
// }
setInterval(periodic, 1000); //time is in ms

// app.post('/upload', upload.single('image'), function(req, res, next){
//   // Creates a client
//   const client = new vision.ImageAnnotatorClient();

//   /**
//    * TODO(developer): Uncomment the following line before running the sample.
//    */
//   // const fileName = 'Local image file, e.g. /path/to/image.png';

//   // Performs label detection on the local file

//   client
//     .labelDetection(req.file.path)
//     .then(results => {
//       res.write('<!DOCTYPE HTML><html><body>');

//       // Base64 the image so we can display it on the page
// 	  res.write('<img width=200 src="' + base64Image(req.file.path) + '"><br>');
//       const labels = results[0].labelAnnotations;
//       console.log('Labels:');
//       labels.forEach(label => console.log(JSON.stringify(label, null, 4)));
//       res.end('</body></html>');
//     })
//     .catch(err => {
//       console.error('ERROR:', err);
//     });
//   // [END vision_label_detection]
// });

app.listen(9090);
console.log('Server Started');

// Turn image into Base64 so we can display it easily


module.exports = app;