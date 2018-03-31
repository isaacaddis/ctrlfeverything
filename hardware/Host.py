import socket
import time
from multiprocessing import Process

TCP_IP = "192.168.43.31"
TCP_PORT = 5005
BUFFER_SIZE = 1024

def baseWork():
    print("baseWork")

def controlf(conn, addr):
    print("process working")
    data = conn.recv(BUFFER_SIZE)
    print ("data",data)
    conn.send(data)
    conn.close()
    s.close()

p = Process(target=baseWork)
p.start()
p.join()
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.bind((TCP_IP,TCP_PORT))
s.listen(1)

while True:
    conn, addr = s.accept()
    p = Process(target=controlf,args=(conn,addr))
    p.start()
    p.join()
