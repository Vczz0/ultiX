# ultiX
ultix is a Remote Acces Tool, also know as a "RAT". Developed by RealDutch7.  
I would like to hear your feedback, issues, on this program. You can send me DM, my discord is in my bio. Thankyou!  
```It took a long time to develop this, so star would be appreciated!```
> ultiX is tested on a Windows 10 machine, the server is hosted in 000Webhost. 

# Info
1. languages
    - Python
    - PHP
    - HTML
    - CSS
    - JS
   
2. Webserver
    - 000Webhost

3. Options 
  ultiX has a built in logger
  you can set your own time between logs
  logger options:
    - Log active window
    - Log Running proccesess on a computer
    - Log Clipboard text
    - Log wifipasswords
    
# Requirements
- Python 3.10.6
- 000Webhost (minimal) free account.
- ultix.zip
- Windows machine

# Instalation
1. Sign in or create your 000Webhost account.
2. click on "Create new website"
3. fill in the required info.
4. click on "Filemanager" > "Upload"
5. click on Public HTML > delete ".htaccess"
6. open your filemanager and drag in the "ultix.zip" > click on "upload"
7. click on "ultix" > "server" > "API" right click on the "index.php" file > "View"
8. Sign in with the username "Admin" and the password "root", you can change the username or the password.

# Building a file and walktrough
1. Open the folder in terminal
2. run ```pip3 install -r requirements.txt```
2. cd to the ultiX folder
3. run the builder.py
  copy the url of commands.php and past in the builder
  set if you want to enable logger or not 
    - set wich loggers you want to enable and the time interval between the logs
  set if you want to enable the persist module
    - this module will create a regkey, and change the python file to a .pyw wich wont show a terminal. so that when the computer restarts the program will restart.
    - you can also set a custum folder for ultiX to copy itself tho.
  give a filename for the program. the file will be stored in Dara/filename
 4. You can also convert the file to .exe the server supports this, but its not included as a option in the builder.
  if you want to convert the .py to an exe run this command. ```pyinstaller Data\filename --onefile --noconsole```
    - if you want to use an icon this command. ```pyinstaller Data\filename --onefile --noconsole --i {path to icon} ```

# Functions
- Statics
    - a worldmap with red dots, wich represents the host, this values are based of IP so its not accurate
    - Graphs wich visualize data, for exmaple the os, or the platform 
- System Information 
    - Displays information about the host, for example if its a VM. RAM, Storage info, GPU, CPU
- Terminal
    - Shell access to Host
 - Files
    - Filemanager for files on host, please note that you can only download on file per request
 - Downloaded Files
    - Where you can download or view the downloaded files from the host
 - Camera
    - Where you can snap pictures from the camera, you need to provide an Camera, like 0 or 1
- Screencapture
    - Where you can snap pictures from the host display, multiple screens will be show in te same photo
- Microphone 
    - Where you can record the mic from the host, this requieres the time to record, for example 10 or 1
 - Shutdown / Reboot
    - In where you can either shutdown or reboot the host computer
 - Clipboard
    - read the clipboard op the host
 - Wifi
     - Where you can read the names, and passwords of the host.
 - Active Processes
     - Where you can view or search for certain running processes, for exmaple firefox, brave
 - Window Text
     - Read the active window of host
 - WinLogin
    - Prompts a fake login screen, wich tries to fish for credentials
 - Messagebox
    - Prompts a messagebox, you can set either a Warning, Information or an Error box
    
# Images

> Note: i used DEMO values, this info like IP's is all Fake!

- Home
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/index.JPG" width="350" alt="accessibility text">

- Home with Client
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/indexWithClient.JPG" width="350" alt="accessibility text">

- Statics
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/statics.JPG" width="350" alt="accessibility text">

- System Information
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/sysINFO.JPG" width="350" alt="accessibility text">

- Camera
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/Camera.JPG" width="350" alt="accessibility text">

- Terminal
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/terminal.JPG" width="350" alt="accessibility text">

- Wifi passwords
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/wifi.JPG" width="350" alt="accessibility text">

- Window Text
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/winText.JPG" width="350" alt="accessibility text">
- Clipboard
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/clip.JPG" width="350" alt="accessibility text">

- Filemanager
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/filemanager.JPG" width="350" alt="accessibility text">

- Downloaded Files
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/downloadedFiles.JPG" width="350" alt="accessibility text">

- MessageBox
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/mess.JPG" width="350" alt="accessibility text">

- Microphone
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/mic.JPG" width="350" alt="accessibility text">

- Running Proccesess
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/proc.JPG" width="350" alt="accessibility text">

- Screencapture
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/screencapture.JPG" width="350" alt="accessibility text">

- Security login
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/secLogin.JPG" width="350" alt="accessibility text">

- Shutdown / Reboot
<img src="https://github.com/Vczz0/ultiX/blob/main/Images/shutdown.JPG" width="350" alt="accessibility text">
