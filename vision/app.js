
var express = require('express');
var fs = require('fs');
var util = require('util');
var mime = require('mime');
var multer = require('multer');
var upload = multer({dest: 'uploads/'});

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
  res.end(form);
});


app.post('/upload', upload.single('image'), function(req, res, next){
  // Creates a client
  const client = new vision.ImageAnnotatorClient();

  /**
   * TODO(developer): Uncomment the following line before running the sample.
   */
  // const fileName = 'Local image file, e.g. /path/to/image.png';

  // Performs label detection on the local file

  client
    .labelDetection(req.file.path)
    .then(results => {
      res.write('<!DOCTYPE HTML><html><body>');

      // Base64 the image so we can display it on the page
	  res.write('<img width=200 src="' + base64Image(req.file.path) + '"><br>');
      const labels = results[0].labelAnnotations;
      console.log('Labels:');
      labels.forEach(label => res.write(JSON.stringify(label, null, 4)));
      res.end('</body></html>');
    })
    .catch(err => {
      console.error('ERROR:', err);
    });
  // [END vision_label_detection]
});

app.listen(8080);
console.log('Server Started');

// Turn image into Base64 so we can display it easily


module.exports = app;