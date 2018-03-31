# CtrlFEverything

An attempt to make a "CTRL-F" function for your home, made at LA Hacks 2018.

## How img processing program talks to server

Generally, the img processing program send a HTTP request to retrieve the img it wants to process. Once the processing is done, it sends another HTTP request to server to update the objects table as well as the img object relation table.

### Retrieve the img from server

the path is TBA

#### request
```
{
    createAfter: number;
}
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

#### request
```
{
    imgId: string;
    objects: string[];
}[]
```
_DONT_ send the raw img back, only send the img id back.

#### response
If the operation success the server will return a HTTP response with 200 code, and empty body.
