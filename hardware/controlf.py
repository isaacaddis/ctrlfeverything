import pygame
import pygame.camera
import time
import json
import urllib2
from json import load
from urllib2 import urlopen
import base64

RESOLUTION = (1280,720)
TIME_DELAY_SECONDS = 1
URL = "https://lahackhack-199707.appspot.com/api/img"

def getJson():
    imageJpg = cam.get_image()
    pygame.image.save(imageJpg,"pic.jpg")
    with open("pic.jpg","rb") as openImage:
        img=base64.b64encode(openImage.read())
    takenAt= int(round(time.time()*1000))
    deviceName = "base1"
    ip=load(urlopen("http://httpbin.org/ip"))["origin"]
    data = {"takenAt":takenAt,"ip":ip,"deviceName":deviceName,"img":img}
    jsonData = json.dumps(data)
    jsonDataFile = open("jsondatafile.txt","w")
    jsonDataFile.write(jsonData)
    return jsonData


pygame.camera.init()
cam = pygame.camera.Camera("/dev/video0",RESOLUTION)
cam.start()
req = urllib2.Request(URL)
req.add_header("Content-Type","application/json")
#while True:
for x in range(0,1):
    json = getJson()
    response = urllib2.urlopen(req,json)
    time.sleep(TIME_DELAY_SECONDS)
