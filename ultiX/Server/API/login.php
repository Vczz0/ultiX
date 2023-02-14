<?php
require_once "session.php";

$username_post = $_POST["username"];
$password_post = $_POST["password"];

if ($username_post !== "" && $password_post !== "") {
    if (isset($username_post) && isset($password_post)) {
        global $username_post;
        global $password_post;
    
        $status = login($username_post, $password_post);
        if ($status == true) {
            header("Location: index.php");
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Invalid Username and or Password
          </div>';
            logout();
        }
    }
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
      <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css" />
      <link rel="stylesheet" href="css/style.css" />
      <script src="./js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
      <script src="./js/jquery-3.5.1.js"></script>
      <script src="./js/jquery.dataTables.min.js"></script>
      <script src="./js/dataTables.bootstrap5.min.js"></script>
      <script src="./js/script.js"></script>
      <title>ultiX - Login</title>
   </head>
   <body>
        <div class="alertMessage">
            <div id="alerts"> </div>
        </div>

        <div class="login-card">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"> <i class="bi bi-person"></i> </span>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username" id="username" name="username"  aria-describedby="addon-wrapping">
                        </div>
                        <br>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping"> <i class="bi bi-shield-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Password" aria-label="Password" id="password" name="password" aria-describedby="addon-wrapping">
                        </div>
                        <br>
                        <input type="checkbox" onclick="checkPW()"> Show Password 
                        <br>
                        <button type="button submit" class="btn btn-success">Login</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
function checkPW() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
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
         <?php
         include("footer.php");
         ?>
      </main>
   </body>
</html>
