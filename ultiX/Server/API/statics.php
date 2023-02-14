<?php
require_once "session.php";


if (!is_login()) {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"/>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
      <script src="js/jquery-3.5.1.js"></script>
      <script src="js/jquery.dataTables.min.js"></script>
      <script src="js/dataTables.bootstrap5.min.js"></script>
      <script src="js/script.js"></script>
      <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
      <link rel="stylesheet" href="css/style.css" />
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
         integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
         crossorigin=""/>
      <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
         integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
         crossorigin=""></script>
      <title>ultiX - Control Panel</title>
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
                     <a href="index.php" class="nav-link px-3">
                     <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                     <span>Dashboard</span>
                     </a>
                     <a href="statics.php" class="nav-link px-3 active">
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
            <div class="card">
               <div class="card-body">
                  <h2> <i class="bi bi-bar-chart-line-fill"></i> Statics</h2>
                  <hr class="my-2">
                  <button type="button" onclick="refPage()"class="btn btn-success"> <i class="bi bi-arrow-clockwise"></i> Refresh</button>
                  <hr class="my-2">
                  <div class="col">
                     <div class="card">
                        <div class="card-header"> 
                           <i class="bi bi-map"></i> World Map
                        </div>
                        <div class="card-body">
                           <div id="map" style="height: 600px;"></div>
                        </div>
                        <script type="text/javascript">
                           var map = L.map('map').setView([51.505, -0.09], 2);
                           <?php
                              // Return GPS cords
                              $agent_path = "../ultiX-Data/data.json";
                              $path = file_get_contents($agent_path);
                              $data = json_decode($path, true);
                              
                              $gps = ""; // Define gps string to nothing.
                              foreach ($data["agents"] as $field => $value) {
                                 $gps .= $value["Lat"];
                                 $gps .= " , ";
                                 $gps .= $value["Long"];
                                 echo "var circle = L.circle([" . $value["Lat"] . "," .  $value["Long"] . "], { ";
                                 echo "color: 'red',";
                                 echo "fillColor: '#f03',";
                                 echo "fillOpacity: 0.5,";
                                 echo "radius: 1500";
                                 echo "}).addTo(map);";
                                 echo "circle.bindPopup(" . "'". $value["uid"]. " Lat:". $value["Lat"]. " Long: ". $value["Long"]. "'". ");";
                              }
                              
                              ?>
                           L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                              setZoom: 1,
                              minZoom: 2,
                              attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                           }).addTo(map);
                        </script>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <div class="card">
                           <div class="card-header"> 
                           <i class="bi bi-bar-chart-fill"></i> Status
                           </div>
                           <div class="card-body">
                              <?php

                              $online = 0;
                              $offline = 0;
                              
                              $agent_path = "../ultiX-Data/data.json";
                              $path = file_get_contents($agent_path);
                              $data = json_decode($path, true);

                              foreach ($data["agents"] as $field => $value) {
                                 date_default_timezone_set("Europe/Amsterdam");
                                 $currentDate = date("d-m-Y H:i:s");

                                 $start_date = strtotime($currentDate);
                                 $end_date = strtotime($value["LastOnline"]);

                                 $difinSec = ($start_date - $end_date);

                                 if ($difinSec >= 30) {
                                    $offline += 1;
                                 } else {
                                    $online += 1;
                                 }
                              }
                                 
                                 // echo $windows;
                                 // echo $linux;
                                 // echo $total_agents;
                                 ?>
                              <div class="table-responsive-md">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <!-- <td scope="col">#</td>
                                             <th scope="col">First</td> -->
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>Online</td>
                                          <td><?php echo $online?></td>
                                       </tr>
                                       <tr>
                                          <td>Offline</td>
                                          <td> <?php echo $offline?></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <canvas id="status" style="width:100%;max-width:600px"></canvas>
                              <script type="text/javascript">
                                 var online = "<?php echo $online?>";
                                 var offline = "<?php echo $offline?>";
                                 
                                 var xValues = ["Online", "Offline"];
                                 var yValues = [online, offline];
                                 var barColors = [
                                 "#26ff00",
                                 "#ff0000"
                                 ];
                                 
                                 new Chart("status", {
                                       type: "pie",
                                       data: {
                                          labels: xValues,
                                          datasets: [{
                                          backgroundColor: barColors,
                                          data: yValues
                                          }]
                                       },
                                       options: {
                                          title: {
                                          display: false,
                                          text: "Top Platforms"
                                          }
                                       }
                                 });
                              </script>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card">
                           <div class="card-header"> 
                              <i class="bi bi-bricks"></i> Formats
                           </div>
                           <div class="card-body">
                              <?php
                                 $totalAgents = 0;
                                 $exe = 0;
                                 $python = 0;
                                 
                                 $agent_path = "../ultiX-Data/data.json";
                                 $path = file_get_contents($agent_path);
                                 $data = json_decode($path, true);
                                 
                                 foreach ($data["agents"] as $field => $value) {
                                    if ($value["Format"] == "Windows"){
                                          $exe += 1;
                                    }
                                 
                                    if ($value["Format"] == "Python") {
                                          $python += 1;
                                    }
                                 
                                 }
                                 
                                 // echo $windows;
                                 // echo $linux;
                                 // echo $total_agents;
                                 ?>
                              <div class="table-responsive-md">
                                 <table class="table table-bordered">
                                    <thead>
                                       <tr>
                                          <!-- <td scope="col">#</td>
                                             <th scope="col">First</td> -->
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                                          <td>Total Python Formats</td>
                                          <td><?php echo $python?></td>
                                       </tr>
                                       <tr>
                                          <td>Total Executable Formats</td>
                                          <td> <?php echo $exe?></td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <canvas id="formats" style="width:100%;max-width:600px"></canvas>
                              <script type="text/javascript">
                                 var exe = "<?php echo $exe?>";
                                 var Python = "<?php echo $python?>";
                                 
                                 var xValues = ["Executable", "Python"];
                                 var yValues = [exe, Python];
                                 var barColors = [
                                 "#00adef",
                                 "#FFD43B"
                                 ];
                                 
                                 new Chart("formats", {
                                       type: "pie",
                                       data: {
                                          labels: xValues,
                                          datasets: [{
                                          backgroundColor: barColors,
                                          data: yValues
                                          }]
                                       },
                                       options: {
                                          title: {
                                          display: false,
                                          text: "Top Platforms"
                                          }
                                       }
                                 });
                              </script>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php
         include("footer.php");
         ?>
      </main>
   </body>
</html>
