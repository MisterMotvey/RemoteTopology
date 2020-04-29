<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/site/session.php');
  // If you are nog logged -> Home
  if(!$_SESSION['status']) {
    $_SESSION['error'] = True;
    Redirect('');
  }
  // ElseIF admin -> redirect to admin page
  elseif($_SESSION['adminpriv']) {
    Redirect('admin/admin.php');
  }

  // TODO: Init stage

  $username = $_SESSION['username'];
  $dir_images = '/images/' . $_SESSION['champ'];


  // Connect to DB
  $conn = ConnectToDB();
  
  // Get current module for user
  $query = $conn->query("SELECT Module FROM `currentstate` WHERE Username='$username'");
  $module = $query->fetch();
  // If module not linux, redirect to choice
  if ($module[0] != 'A') {
    Redirect('choice.php');
  }

  // Get links for username from DB
  $query = $conn->query("SELECT *  FROM `AModuleLinks` WHERE `Username`='$username' "); 
  $links = $query->fetch(PDO::FETCH_ASSOC);
  // $links = array(
  //   'L-SRV'     => 'link',
  //   'L-FW'      => 'link',
  //   'L-RTR-A'   => 'link',
  //   'L-RTR-B'   => 'link',
  //   'L-CLI-A'   => 'link',
  //   'L-CLI-B'   => 'link',
  //   'OUT-CLI'   => 'link',
  //   'R-FW'      => 'link',
  //   'R-SRV'     => 'link',
  //   'R-RTR'     => 'link',
  //   'R-CLI'     => 'link'
  // );
  // foreach ($links as $device => $link) {
  //   $query = $conn->query("SELECT `$device`  FROM `AModuleLinks` WHERE `Username`='$username' ");  
  //   $result = $query->fetch();
  //   $links[$device] = $result[0];
  // }
?>
<!DOCTYPE html>
<html lang="en">
 <head>
   <meta charset="utf-8">
   <link rel="icon" type="image/png" href="/images/favicon.ico">
   <link rel="stylesheet" type="text/css" href="/css/fonts.css">
   <link rel="stylesheet" type="text/css" href="/css/master.css">
   <link rel="stylesheet" type="text/css" href="/css/devices.css">
   <script type="text/javascript" src="/scripts/function.js"></script>
   <title>Demo2020</title>
 </head>
 <body>
   <div class="top-panel">
     <div class="userinfo-top-panel">
         <?
         echo 'You logged in as '.$username;
          ?>
     </div>
     <div class="logout-top-panel">
        <a href="/choice.php">Back</a>
     </div>
   </div>
   <div class="main-content">
    <div class="main-scheme">
      <img src=" <?php echo $dir_images; ?>/Lscheme.png" alt="Scheme" class="main-scheme-image">

      <!-- Left -->
      <div class="host L-SRV"
        onclick="callhost('<?php echo $links['L-SRV']; ?>');">
      </div>

      <div class="host L-FW"
        onclick="callhost('<?php echo $links['L-FW']; ?>');">
      </div>

      <div class="host L-RTR-A"
        onclick="callhost('<?php echo $links['L-RTR-A']; ?>');">
      </div>

      <div class="host L-RTR-B"
        onclick="callhost('<?php echo $links['L-RTR-B']; ?>');">
      </div>

      <div class="host L-CLI-A"
        onclick="callhost('<?php echo $links['L-CLI-A']; ?>');">
      </div>

      <div class="host L-CLI-B"
        onclick="callhost('<?php echo $links['L-CLI-B']; ?>');">
      </div>

      <div class="host OUT-CLI"
        onclick="callhost('<?php echo $links['OUT-CLI']; ?>');">
      </div>

      <!-- Right -->

      <div class="host R-FW"
        onclick="callhost('<?php echo $links['R-FW']; ?>');">
      </div>

      <div class="host R-SRV"
        onclick="callhost('<?php echo $links['R-SRV']; ?>');">
      </div>

      <div class="host R-RTR"
        onclick="callhost('<?php echo $links['R-RTR']; ?>');">
      </div>

      <div class="host R-CLI"
        onclick="callhost('<?php echo $links['R-CLI']; ?>');">
      </div>
    </div>    
    <div class="timer top-left">
      <script type="text/javascript" src="http://demo2020.wsr39.online/dcnt/cn/cn.php?id=1004"></script>
    </div>
   </div>
   
  <footer>
    <p>
      Developed by 104auteam
      <a href='https://github.com/104auteam'><img src="/images/github.png" alt="Github"></a>
    </p>
  </footer>
 </body>
</html>
