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

s.send(deviceName.encode('utf-8'))
#while True:
for x in range(0,1):
    imageJpg = cam.get_image()
    pygame.image.save(imageJpg,"pic.jpg")
    imageData = open("pic.jpg","rb").read()
    size = len(imageData)
    s.recv(1)
    s.sendall(str(size))
    s.sendall(imageData)
s.close()
