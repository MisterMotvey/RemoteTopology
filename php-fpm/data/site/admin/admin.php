<?php
    require_once('../session.php');
    // If you are not admin -> redirect to home
    if (!$_SESSION['adminpriv']) {
        Redirect('');
    }
    // Init

    // Connect to DB
    $conn = ConnectToDB();

    // High user number in $MAX
    $query = $conn->query("SELECT MAX(Number) FROM credentials"); $result = $query->fetch();
    $MAX = $result[0];
    
    // Select user list in $SELECT_USERS
    // $SELECT_USERS_GET -> tempplate for get info from DB on Module
    // An open tag is declared in the block where it is used.
    $SELECT_USERS       = '<select name="username" class="admin-select">';
    for ($userNumber = 1; $userNumber <= $MAX; $userNumber++) { 
        // Query Username
        $query = $conn->query("SELECT `Username` FROM `credentials` WHERE `Number`=$userNumber AND `adminpriv`=0");
        $username = $query->fetch();
        if(!$username[0]) {
            continue;
        }
        $SELECT_USERS        .= '<option value=' . $username[0] . ' class="admin-select" id="username">' . $username[0] . '</option>';    
        $SELECT_USERS_GET  .= '<option value=' . $username[0] . ' class="admin-select" id="username">' . $username[0] . '</option>';            
    }
    $SELECT_USERS       .= '</select>';
    $SELECT_USERS_GET   .= '</select>';

    // onchange="showUser(this.value)"
    // Select modules in $SELECT_MODULES
    $modules = array('A','B','C');
    $SELECT_MODULES = '<select name="module" class="admin-select">';
    foreach ($modules as $module) {
        $SELECT_MODULES .= '<option value=' . $module . ' class="admin-select">' . $module . '</option>';    
    }
    $SELECT_MODULES .= '</select>';

    // Select Champ in $SELECT_CHAMP
    $query = $conn->query("SELECT `Event` FROM championships.champ_list");
    $championships = $query->fetchAll(PDO::FETCH_COLUMN, 0);
    $SELECT_CHAMP = '<select name="champ" class="admin-select">';
    foreach ($championships as $champ) {
        $SELECT_CHAMP .= '<option value=' . $champ . ' class="admin-select">' . $champ . '</option>';    
    }
    $SELECT_CHAMP .= '</select>';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="/css/master.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery.accordion.css">
    <link rel="stylesheet" type="text/css" href="/css/admin.css">
    <script src="/scripts/jquery-3.5.0.min.js"></script>
    <script src="/scripts/jquery.accordion.js"></script>
    <script src="/scripts/function.js"></script>
    <title>RemoteTopology</title>
    <style>
        html {
            -moz-user-select: auto;
            -khtml-user-select: auto;
            user-select: auto;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            showRecords(10, 1);
        });
    </script>
  </head>
  <body>
    <div class="admin-top-panel">
        <div class="admin-top-panel-functionblock">
            <div class="admin-top-panel-functionblock-function">
                <img src="/images/admin/users.png" alt="users">
                USERS
            </div>
            <div class="admin-top-panel-functionblock-function">
                <img src="/images/admin/stands.png" alt="stands">
                STANDS
            </div>
            <div class="admin-top-panel-functionblock-function">
                <img src="/images/admin/championships.png" alt="championships">
                CHAMPIONSHIPS
            </div>
            <div class="admin-top-panel-functionblock-function">
                <img src="/images/admin/servers.png" alt="servers">
                SERVERS
            </div>
            <div class="admin-top-panel-functionblock-function">
                <img src="/images/admin/report.png" alt="report">
                REPORT
            </div>
        </div>
        <div class="admin-top-panel-usersinfo">
            <a href="/session_destroy.php">
                <img src="/images/admin/signout.png" alt="Logout">
            </a>
        </div>
    </div>
    <div class="admin-info">
        <div class="loader"></div>
        <div id="results"></div>
    </div>
    <footer>
      <p>
        Developed by 104auteam
        <a href='https://github.com/104auteam'><img src="/images/github.png" alt="Github"></a>
      </p>
    </footer>
  </body>
</html>
