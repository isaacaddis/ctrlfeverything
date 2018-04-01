import socket
import pygame
import time
import pygame.camera
import urllib
import base64
from json import load
from urllib2 import urlopen

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


while True:
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect((TCP_IP, TCP_PORT))
    s.send(deviceName.encode('utf-8'))
    imageJpg = cam.get_image()
    pygame.image.save(imageJpg,"pic.jpg")
    imageData = open("pic.jpg","rb").read()
    size = str(len(imageData))
    print(size)
    #c=s.recv(1)
    #print(c)
    #s.sendall("c")
    s.send(size.encode('utf-8'))
    s.sendall(imageData)
    s.close()
    time.sleep(15)
