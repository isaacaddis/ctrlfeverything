import socket
import pygame
import time
import pygame.camera
#import urllib
import base64
#from json import load
#from urllib2 import urlopen

RESOLUTION = (1280, 720)
TIME_DELAY_SECONDS=1

TCP_IP = '192.168.43.31'
TCP_PORT = 5005
BUFFER_SIZE = 1024
#MESSAGE = "Hello, World!"

deviceName="computer"
pygame.camera.init()
cam=pygame.camera.Camera("/dev/video0",RESOLUTION)
cam.start()

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect((TCP_IP, TCP_PORT))


#while True:
for x in range(0,1):
    test = "test here"
    imageJpg = cam.get_image()
    pygame.image.save(imageJpg,"pic.jpg")
    with open("pic.jpg","rb") as openImage:
        img=base64.b64encode(openImage.read())
    takenAt=int(round(time.time()*1000))
    s.send(takenAt.encode('utf-8'))
    s.send(test.encode('utf-8'))


s.close()
