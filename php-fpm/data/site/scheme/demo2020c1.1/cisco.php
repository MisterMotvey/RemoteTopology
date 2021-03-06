<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/site/session.php');
  // If you are not logged -> Home
  if(!$_SESSION['status']) {
    $_SESSION['error'] = True;
    Redirect('');
  }
  // ElseIF admin -> redirect to admin page
  elseif($_SESSION['adminpriv']) {
    Redirect('admin/admin.php');
  }
  // ElseIF champ dont demo2020c1.1 -> redirect to admin page
  elseif($_SESSION['champ'] != 'demo2020c1.1') {
    Redirect('choice.php');
  }

  // TODO: Init stage
  
  $username = $_SESSION['username'];
  $password = $_SESSION['password'];
  $docker_address = $_SERVER['HTTP_HOST'] . ':2222/ssh/host/';
  $dir_images = '/images/' . $_SESSION['champ'];

  // Connect to DB
  $conn = ConnectToDB();

  // Get current module for user
  $query = $conn->query("SELECT Module FROM currentstate WHERE Username='$username'");
  $module = $query->fetch(); $module = $module[0];
  // If module not cisco, redirect to choice
  if ($module != 'C') {
    Redirect('choice.php');
  }
  
  // Get links for username from DB
  $table = 'championships.`' . $_SESSION['champ'].$module.'`'; 
  $query = $conn->query("SELECT * FROM $table WHERE `Username`='$username' "); 
  $links = $query->fetch(PDO::FETCH_ASSOC);
  
  // TODO: Generate personal devices link
  // EXAMPLE
  // http:// $_SERVER['HTTP_HOST'] /ssh/host/10.11.8.4?header=Device&user=root&pass=toor
  
  $digi_address = $links['DIGIAddress'];

  foreach ($links as $device => $link) {
    if ($device == 'PC1' or $device == 'PC2' or $device == 'SRV1') {
      // Not edit hosts link, DB give us normal links so continue
      continue;
    }
    // For device $links[$device] -> port 
    $port = $links[$device];
    $links[$device] =
    'http://'.$docker_address.$digi_address.'?'.
            'header='.$device.'&'.
            'port='.$port.'&'.
            'user='.$username.'&'.
            'pass='.$password;
  }
?>
<!DOCTYPE html>
<html lang="en">
 <head>
   <meta charset="utf-8">
   <link rel="icon" type="image/png" href="/images/favicon.ico">
   <link rel="stylesheet" type="text/css" href='/css/fonts.css'>
   <link rel="stylesheet" type="text/css" href='/css/master.css'>
   <link rel="stylesheet" type="text/css" href='/css/champs/<?php echo $_SESSION['champ']; ?>.css'>
   <script type="text/javascript" src="/scripts/function.js"></script>
   <title> <?php echo $_SESSION['champ'] ?> </title>
 </head>
 <body>
   <div class="top-panel">
     <div class="userinfo-top-panel">
        <?php
          echo 'You logged in as '.$_SESSION['username'];
        ?>
     </div>
     <div class="logout-top-panel">
        <a href="/choice.php">Back</a>
     </div>
   </div>
   <div class="main-content">
    <div class="main-scheme">
      <img src=" <?php echo $dir_images; ?>/Cscheme.png" alt="Scheme" class="main-scheme-image">
      <div class="device BR1"
      onclick="call('<?php echo $links['BR1']; ?>');">
      </div>
      <div class="device FW1"
      onclick="call('<?php echo $links['FW1']; ?>');">
      </div>
      <div class="device HQ1"
      onclick="call('<?php echo $links['HQ1']; ?>');">
      </div>
      <div class="device SW1"
      onclick="call('<?php echo $links['SW1']; ?>');">
      </div>
      <div class="device SW2"
      onclick="call('<?php echo $links['SW2']; ?>');">
      </div>
      <div class="device SW3"
      onclick="call('<?php echo $links['SW3']; ?>');">
      </div>
      <div class="host SRV1-Cisco"
      onclick="callhost('<?php echo $links['SRV1']; ?>');">
      </div>
      <div class="host PC1"
      onclick="callhost('<?php echo $links['PC1']; ?>');">
      </div>
      <div class="host PC2"
      onclick="callhost('<?php echo $links['PC2']; ?>');">
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
