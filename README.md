# CtrlFEverything

An attempt to make a "CTRL-F" function for your home, made at LA Hacks 2018.

## How can controllers talk to server

The controller sends new images to server by using HTTP request.


the path is `/img`.

### Request

the method is `POST`, the body is as following.
```
{
    img: string;
    ip: string;
    deviceName: string;
    takeAt: number;
}
```
`takenAt` should be unix timestamp in ms.
`img` should be image in jpeg format, encoded in base64.

### response

On success the server returns with status code 200, with an empty body.

## How img processing program talks to server

Generally, the img processing program send a HTTP request to retrieve the img it wants to process. Once the processing is done, it sends another HTTP request to server to update the objects table as well as the img object relation table.

### Retrieve the img from server

the path is `/img`

#### request

`GET`
the param should be `take_after` in unix timestamp in ms
```
/img?take_after=1522495009422
```

#### response

```
{
    id: string;
    img: string;
    createdAt: number;
}
```
`img` is the base64 code of the img in jpeg format
`createdAt` is the unix timestamp in ms

### Update the related object of imgs

the path is `/objects_of_imgs`

#### request

`POST`
```
{
    imgId: string;
    objects: string[];
}[]
```
_DONT_ send the raw img back, only send the img id back.

#### response

If the operation success the server will return a HTTP response with 200 code, and empty body.

## Vision

Our vision code is run through the Google Cloud Vision API using Node.js. 

Refer to /vision/, to see the server in its entirety. 
