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
  // ElseIF champ dont mejvuz -> redirect to admin page
  elseif($_SESSION['champ'] != 'mejvuz') {
    Redirect('choice.php');
  }
  
  // TODO: Init stage
  
  $username = $_SESSION['username'];
  $dir_images = '/images/' . $_SESSION['champ'];

  // Connect to DB
  $conn = ConnectToDB();
  
  // Get current module for user
  $query = $conn->query("SELECT Module FROM `currentstate` WHERE Username='$username'"); $module = $query->fetch();
  $module = $module[0];
  // If module not Windows, redirect to choice
  if ($module != 'B') {
    Redirect('choice.php');
  }
  
  // Get links for username from DB
  $table  = $_SESSION['champ'].$module;
  $query  = $conn->query("SELECT *  FROM championships.`$table` WHERE `Username`='$username' "); 
  $links  = $query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
 <head>
   <meta charset="utf-8">
   <link rel="icon" type="image/png" href="/images/favicon.ico">
   <link rel="stylesheet" type="text/css" href="/css/fonts.css">
   <link rel="stylesheet" type="text/css" href="/css/master.css">
   <link rel="stylesheet" type="text/css" href='/css/champs/<?php echo $_SESSION['champ']; ?>.css'>
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

      <div class="host FS1"
        onclick="callhost('<?php echo $links['FS1']; ?>');">
      </div>
      
      <div class="host DC1"
        onclick="callhost('<?php echo $links['DC1']; ?>');">
      </div>
      
      <div class="host RCA"
        onclick="callhost('<?php echo $links['RCA']; ?>');">
      </div>
      
      <div class="host CLI1"
        onclick="callhost('<?php echo $links['CLI1']; ?>');">
      </div>
      
      <div class="host BR1-W"
        onclick="callhost('<?php echo $links['BR1']; ?>');">
      </div>
      
      <div class="host BR2"
        onclick="callhost('<?php echo $links['BR2']; ?>');">
      </div>
      
      <div class="host DC2"
        onclick="callhost('<?php echo $links['DC2']; ?>');">
      </div>
      
      <div class="host FS2"
        onclick="callhost('<?php echo $links['FS2']; ?>');">
      </div>
      
      <div class="host CLI2"
        onclick="callhost('<?php echo $links['CLI2']; ?>');">
      </div>

      <div class="host CLI3"
        onclick="callhost('<?php echo $links['CLI3']; ?>');">
      </div>

      <div class="host RDS"
        onclick="callhost('<?php echo $links['RDS']; ?>');">
      </div>

    </div>    
    <div class="timer top-left">
      <?php echo $_SESSION['timer']; ?>
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
