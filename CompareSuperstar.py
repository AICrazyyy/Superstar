#coding=utf-8
import face_recognition
import os
import re
import sys
import numpy as np
import imghdr
params = sys.argv[1:]
#params = ['./UserImg/User_105/photo.jpg']
path="./SuperstarImgs"
def compare_face(face_encodings,face_to_compare):
    return np.linalg.norm(face_encodings - face_to_compare, axis=1)

code = np.load("result.npz") #加载一次即可
def start():
    distance=[]
    newdistance=[]
    Top3=[]
    imgType = imghdr.what(params[0])
    if imgType==None:
        print("TypeError")
        return 0
    encoding = face_recognition.face_encodings(face_recognition.load_image_file(params[0]))
    if encoding==[]:
        print("FaceError")
        return 0
    names=os.listdir(path)
    for i in range(len(names)):
        distance.append(compare_face(code[names[i]][0],encoding).tolist())
        newdistance.append(distance[i][0])
    DesDistance = np.argsort(newdistance).tolist()
    for i in range(3):
        faceindex=DesDistance[i]
        reg = r'(.*)\.jpg'
        imgre = re.compile(reg)
        name=imgre.findall(names[faceindex])
        Top3.append(path+'/'+names[faceindex]+"#相似度:"+str('%.2f' %(100-round(newdistance[DesDistance[i]]*100,2)))+"%")
        #print(str(name[0])+":相似度 "+str(120-round(newdistance[DesDistance[i]]*100,2))+"%")
    print(str(Top3[0]+" "+Top3[1]+" "+Top3[2]).encode("UTF-8"))
    # f=open('top3.txt','w')
    # for info in Top3:
    #     f.write(info)
    #     f.write('\n')
    # f.close()
start()



