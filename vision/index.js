const http = require('http');
const request = require('request');

const express = require('express');
const app = express();

const port = process.env.PORT || '8080';

const config = {
    projectId: 'lahackhack-199707',
    keyFileName: 'LAHack-594fb78d43e9.json',
};

const dataStore = require('@google-cloud/datastore')(config);
const storage = require('@google-cloud/storage')(config);
const vision = require('@google-cloud/vision');

const visionClient = new vision.ImageAnnotatorClient();

const host = `https://lahackhack-199707.appspot.com/api`;

function getLabelsAsync(imgBase64) {
    const rawImg = Buffer.from(imgBase64, 'base64');
    return visionClient
        .labelDetection(rawImg)
        .then(res => {
            const labels = res[0].labelAnnotations.map(label => label.description);
            return labels;
        });
}



function getImages(takenAfter, cb) {
    request({
        url: `${host}/img`,
        qs: { "taken_after": takenAfter }
    }, function (err, response, body) {
        const data = JSON.parse(body).data;
        cb(data);
    });
}

function getTakenAfter(cb) {
    cb(0);
}

function mainAsync() {
    return new Promise((res, rej) => getTakenAfter(takenAfter => getImages(takenAfter, images => {
        const promises = images.map(img => {
            const id = img.id;
            const imgBase64 = img.img;
            return getLabelsAsync(imgBase64).then(objects => ({
                imgId: id,
                objects,
            }));
        });
        res(Promise.all(promises).then(body => {
            request({
                url: `${host}/objects_of_imgs`,
                headers: {
                    'content-type': 'application/json',
                },
                method: 'POST',
                body: JSON.stringify(body)
            });
        }, (err => {
            if(err) {
                throw new Error(err);
            }
        })));
    })));
}

app.get('/tasks/find_objects_on_imgs', (req, res) => {
    mainAsync().then(() => console.log('task done!')).then(() => res.status(200).end()).catch(err => res.status(500).end(JSON.stringify(err)));
});

http.createServer(app).listen(port);
