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
  // If module not Windows, redirect to choice
  if ($module[0] != 'B') {
    Redirect('choice.php');
  }

  // Get links for username from DB
  $query = $conn->query("SELECT *  FROM `BModuleLinks` WHERE `Username`='$username' "); 
  $links = $query->fetch(PDO::FETCH_ASSOC);
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
         echo 'You logged in as '.$_SESSION['username'];
          ?>
     </div>
     <div class="logout-top-panel">
        <a href="/choice.php">Back</a>
     </div>
   </div>
   <div class="main-content">
    <div class="main-scheme">
      <img src=" <?php echo $dir_images; ?>/Wscheme.png" alt="Scheme" class="main-scheme-image">

      <div class="host SRV1-Windows"
        onclick="callhost('<?php echo $links['SRV1']; ?>');">
      </div>
      
      <div class="host DC1"
        onclick="callhost('<?php echo $links['DC1']; ?>');">
      </div>
      
      <div class="host DCA"
        onclick="callhost('<?php echo $links['DCA']; ?>');">
      </div>
      
      <div class="host CLI1"
        onclick="callhost('<?php echo $links['CLI1']; ?>');">
      </div>
      
      <div class="host R1"
        onclick="callhost('<?php echo $links['R1']; ?>');">
      </div>
      
      <div class="host R2"
        onclick="callhost('<?php echo $links['R2']; ?>');">
      </div>
      
      <div class="host DC2"
        onclick="callhost('<?php echo $links['DC2']; ?>');">
      </div>
      
      <div class="host SRV2"
        onclick="callhost('<?php echo $links['SRV2']; ?>');">
      </div>
      
      <div class="host CLI2"
        onclick="callhost('<?php echo $links['CLI2']; ?>');">
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
