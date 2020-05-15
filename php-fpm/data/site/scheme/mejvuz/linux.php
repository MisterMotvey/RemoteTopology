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
  $query  = $conn->query("SELECT Module FROM `currentstate` WHERE Username='$username'"); $module = $query->fetch();
  $module = $module[0];
  // If module not linux, redirect to choice
  if ($module[0] != 'A') {
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
      <div class="host SRV-L"
        onclick="callhost('<?php echo $links['SRV-L']; ?>');">
      </div>

      <div class="host SRV-R"
        onclick="callhost('<?php echo $links['SRV-R']; ?>');">
      </div>

      <div class="host STR"
        onclick="callhost('<?php echo $links['STR']; ?>');">
      </div>

      <div class="host CORE"
        onclick="callhost('<?php echo $links['CORE']; ?>');">
      </div>

      <div class="host FW"
        onclick="callhost('<?php echo $links['FW']; ?>');">
      </div>

      <!-- Right -->

      <div class="host CLI-ADM"
        onclick="callhost('<?php echo $links['CLI-ADM']; ?>');">
      </div>

      <div class="host CLI-OUT"
        onclick="callhost('<?php echo $links['CLI-OUT']; ?>');">
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
