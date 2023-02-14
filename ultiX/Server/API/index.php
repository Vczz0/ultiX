<?php
require_once "session.php";


if (!is_login()) {
    header("Location: login.php");
}


$path_to_agents = "../ultiX-Data/data.json";
?>



<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"/>
      <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
      <link rel="stylesheet" href="css/style.css" />
      <script src="./js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
      <script src="./js/jquery-3.5.1.js"></script>
      <script src="./js/jquery.dataTables.min.js"></script>
      <script src="./js/dataTables.bootstrap5.min.js"></script>
      <script src="./js/script.js"></script>
      <title>ultiX - Panel</title>
   </head>
   <body>
      <!-- top navigation bar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
         <div class="container-fluid">
            <button
               class="navbar-toggler"
               type="button"
               data-bs-toggle="offcanvas"
               data-bs-target="#sidebar"
               aria-controls="offcanvasExample"
               >
            <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
            </button>
            <a
               class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold"
               href="#"
               >ultiX - Panel</a
               >
            </form>

         </div>
      </nav>
      <!-- top navigation bar -->
      <!-- offcanvas -->
      <div
         class="offcanvas offcanvas-start sidebar-nav bg-dark"
         tabindex="-1"
         id="sidebar"
         >
         <div class="offcanvas-body p-0">
            <nav class="navbar-dark">
               <ul class="navbar-nav">
                  <li>
                     <div class="text-muted small fw-bold text-uppercase px-3">
                        Panel
                     </div>
                  </li>
                  <li>
                     <a href="index.php" class="nav-link px-3  active">
                     <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                     <span>Dashboard</span>
                     </a>
                     <a href="statics.php" class="nav-link px-3">
                     <span class="me-2"><i class="bi bi-bar-chart-line-fill"></i></span>
                     <span>Statics</span>
                     </a>
                  </li>
                  <li class="my-2">
                     <hr class="dropdown-divider bg-light" />
                  </li>
                  <li>
                     <div class="text-muted small fw-bold text-uppercase px-3">
                        Author
                     </div>
                     <a href="https://github.com/Vczz0/" class="nav-link px-3">
                     <span class="me-2"> <i class="bi bi-info-circle-fill"></i> </span>
                     <span>RealDutch7</span>
                     </a>
                  </li>
               </ul>
            </nav>
         </div>
      </div>
      <!-- offcanvas -->
      <main class="main mt-5 pt-3">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-12 mb-3">
                  <div class="card">
                     <div class="card-body">
                        <h2> <i class="bi bi-laptop"></i> Agents</h2>
                        <hr class="my-2">
                        <button type="button" onclick="refPage()"class="btn btn-success"> <i class="bi bi-arrow-clockwise"></i> Refresh</button>
                        <hr class="my-2">
                        <div class="alert alert-primary" role="alert">
                           <i class="bi bi-info-circle"></i> Agents are show below
                        </div>
                        <div id="alerts"> </div>
                        <hr class="my-2">
                        <div class="table-responsive">
                           <table
                              id="bot-table"
                              class="table data-table agent-table table-bordered table-hover"
                              style="width: 100%"
                              >
                              <thead class="table">
                                 <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date since last response</th>
                                    <th scope="col">Persist</th>
                                    <th scope="col">UID</th>
                                    <th scope="col">Format</td>
                                    <th scope="col">OS</th>
                                    <th scope="col">Delete</th>
                                    <th scope="col">Uninstall</th>
                                    <th scope="col">Add. Persist.</th>
                                    <th scope="col">Del. Persist.</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php
                                 global $path_to_agents;
                                 $agent_data = file_get_contents($path_to_agents);
                                 $data = json_decode($agent_data, true);
                                 $total_agents = 1;
                                 foreach ($data["agents"]as $field => $value) {

                                    date_default_timezone_set("Europe/Amsterdam");
                                    $currentDate = date("d-m-Y H:i:s");

                                    $start_date = strtotime($currentDate);
                                    $end_date = strtotime($value["LastOnline"]);

                                    $difinSec = ($start_date - $end_date);
                                    echo "<tr>";
                                    echo '<th>'.$total_agents.'</th>';
                                    if ($difinSec >= 30) {
                                       echo '<td>' . '<button type="button" class="btn btn-secondary">'.'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                       <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
                                     </svg> Offline</button>'. '</td>';
                                       echo '<td>'. " ". $value["LastOnline"] . '</td>';
                                    } 
                                    else {
                                       echo '<td>' . '<button type="button" class="btn btn-success">'.'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                       <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
                                     </svg>'. " ".$value["Status"].'</button>'. '</td>';
                                       echo '<td> Online</td>';
                                    }

                                    if ($value["Persist"] == "True") {
                                       echo '<td> <button type="button" class="btn btn-success"> <i class="bi bi-check-circle"></i> True</button> </td>';
                                    } else {
                                       echo '<td> <button type="button" class="btn btn-danger"><i class="bi bi-x-circle"></i> False</button> </td>';
                                    }

                                    echo '<th>' . '<a href="modules/systeminfo.php?uid='.$value["uid"].'"'."'".'>'.$value["uid"].'</a>'.'</th>';
                                    echo '<td>' . '<button type="button" class="btn btn-dark">' . '<img src="images/Formats/' . $value["Format"] . '.png' . '"' . 'width="20"' . '>'. " ". $value["Format"] . '</button>'. '</td>';
                                    echo '<td>' . '<button type="button" class="btn btn-dark">' . '<img src="images/Formats/' . $value["OS"] . '.png' . '"' . 'width="20"' . '>'. " ". $value["OS"] . '</button>'.  '</td>';
                                    echo '<td>' . '<button type="button" onclick="DeleteUID('. "'" . $value["uid"]. "'" . ')" class="btn btn-danger"> <i class="bi bi-trash"></i> Delete</button>' . '</td>';
                                    echo '<td>' . '<button type="button" onclick="UninstallUID(' . "'" . $value["uid"] . "'" . ')" class="btn btn-danger"> <i class="bi bi-folder-minus"></i> Uninstall</button>' . '</td>';
                                    echo '<td>' . '<button type="button" onclick="PersistAdd(' . "'" . $value["uid"] . "'" . ')" class="btn btn-success"> <i class="bi bi-plus-circle"></i> Add Persist </button>' . '</td>';
                                    echo '<td>' . '<button type="button" onclick="PersistDel(' . "'" . $value["uid"] . "'" . ')" class="btn btn-danger"> <i class="bi bi-dash-circle"></i> Del Persist</button>' . '</td>';
                                    echo "</tr>";
                                    $total_agents += 1;
                                 }
                                 
                                 
                                 ?>
                                 </tbody>
                              </tfoot>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <script>

function DeleteUID($uid) {
   if ($uid !== ""){
      var command = {
         "command": "deleteUID",
         "parameters": "None",
         "uid": $uid,
      };
      alertMessage(" ", "Command has been sent to Database");
      $.post("commands.php", command, "json").done(function(data) {
         alertMessageGood(" ", data);
         refPage();
      });        
   } else {
      alertMessageError(" ", "Invalid input, Messagebox title and / or Message are requiered")
   }
}     

function UninstallUID($uid) {
   if ($uid !== ""){
      var command = {
         "command": "uninstallUID",
         "parameters": "None",
         "uid": $uid,
      };
      alertMessage(" ", "Command has been sent to Database");
      $.post("commands.php", command, "json").done(function(data) {
         alertMessageGood(" ", data);
      });        
   } else {
      alertMessageError(" ", "Invalid input, Messagebox title and / or Message are requiered")
   }
}

function PersistDel($uid) {
   if ($uid !== ""){
      var command = {
         "command": "delPersist",
         "parameters": "None",
         "uid": $uid,
      };
      alertMessage(" ", "Command has been sent to Database");
      $.post("commands.php", command, "json").done(function(data) {
         alertMessageGood(" ", data);
      });        
   } else {
      alertMessageError(" ", "Invalid input, Messagebox title and / or Message are requiered")
   }
}

function PersistAdd($uid) {
   if ($uid !== ""){
      var command = {
         "command": "addPersist",
         "parameters": "None",
         "uid": $uid,
      };
      alertMessage(" ", "Command has been sent to Database");
      $.post("commands.php", command, "json").done(function(data) {
         alertMessageGood(" ", data);
      });        
   } else {
      alertMessageError(" ", "Invalid input, Messagebox title and / or Message are requiered")
   }
}  

function alertMessageGood(title, data){
    $("#alerts").append(
        '<div class="alert alert-success alert-dismissible fade show" role="alert">' + 
            '<strong>' + '<i class="bi bi-check"></i>' + title + '</strong> ' + data + 
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">' +
            
            '</button>'+ 
        '</div>'
    )
}
function alertMessage(title, data){
    $("#alerts").append(
        '<div class="alert alert-primary alert-dismissible fade show" role="alert">' + 
            '<strong>' + '<i class="bi bi-info-circle"></i>' + title + '</strong> ' + data + 
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">' +
            
            '</button>'+ 
        '</div>'
    )
}

function alertMessageError(title, data){
    $("#alerts").append(
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' + 
            '<strong>' + '<i class="bi bi-x-circle"></i>' + title + '</strong> ' + data + 
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">' +
            
            '</button>'+ 
        '</div>'
    )
}
</script>
         </div>
         <?php
         include("footer.php");
         ?>
      </main>
   </body>
</html>
