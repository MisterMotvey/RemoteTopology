<?php
    require_once('session.php');
    // If you are nog logged -> Home
    if(!$_SESSION['status']) {
      $_SESSION['error'] = True;
      Redirect('');
    }
    // ElseIF admin -> redirect to admin page
    elseif($_SESSION['adminpriv']) {
      Redirect('admin/admin.php');
    }
    
    // Init
    $username = $_SESSION['username'];
    // Connect to DB
    $conn = ConnectToDB();
    // Query module, champ and timer information
    $query  = $conn->query("SELECT * FROM `currentstate` WHERE `Username`='$username'"); $result = $query->fetch(PDO::FETCH_ASSOC);
    $module = $result['Module'];
    $champ  = $_SESSION['champ'] = $result['Championship']; 

    $query = $conn->query("SELECT `Timer` FROM championships.champ_list WHERE `Event`='$champ'"); $timer = $query->fetch();
    $timer = $_SESSION['timer'] = $timer[0];

    // If user view this page -> disable Trystate
    $query = $conn->query("UPDATE `currentstate` SET `State` = False WHERE `Username`='$username'");

    $dir_module = '/scheme/' . $_SESSION['champ'];
    $dir_images = '/images/' . $_SESSION['champ'];
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="/css/master.css">
    <script type="text/javascript" src="/scripts/function.js"></script>
    <title>RemoteTopology</title>
  </head>
  <body>
  <div class="top-panel">
     <div class="userinfo-top-panel">
         <?
         echo 'You logged in as '.$username;
          ?>
     </div>
     <div class="logout-top-panel">
        <a href="/session_destroy.php">Logout</a>
     </div>
   </div>
    <main>
        <div 
          <?php
            // If this module, output choice-item OR lock :)
            if ($module == 'A') {
              echo 'class="choice-item">';
              echo '<a href="' . $dir_module . '/linux.php">';
              echo '<img src="/images/moduleA.png" alt="Linux">';
              echo '</a>';
              echo '<p>A</p>';
            }
            else {
              echo 'class="choice-item-lock">';
              echo '<img src="/images/moduleA.png" alt="Linux">';
              echo '<p>🔒A🔒</p>';
            }
          ?>
        </div>   
        <div 
          <?php
            // If this module, output choice-item OR lock :)
            if ($module == 'B') {
              echo 'class="choice-item">';
              echo '<a href="' . $dir_module . '/windows.php">';
              echo '<img src="/images/moduleB.png" alt="Windows">';
              echo '</a>';
              echo '<p>B</p>';
            }
            else {
              echo 'class="choice-item-lock">';
              echo '<img src="/images/moduleB.png" alt="Windows">';
              echo '<p>🔒B🔒</p>';
            }
          ?>
        </div>
        <div
          <?php
          // If this module, output choice-item OR lock :)
          if ($module == 'C') {
            echo 'class="choice-item">';
            echo '<a href="' . $dir_module . '/cisco.php">';
            echo '<img src="/images/moduleC.png" alt="Cisco">';
            echo '</a>';
            echo '<p>C</p>';
          }
          else {
            echo 'class="choice-item-lock">';
            echo '<img src="/images/moduleC.png" alt="Cisco">';
            echo '<p>🔒C🔒</p>';
          }
          ?>
        </div>
        <div class='attention'>
          <h1>Attention!</h1>
          <p>All actions on the network are logged. If you try to disconnect from the VPN connection, your attempt will be reset to null.</p>                        
          <h2>
            Before disconnect left<br>
            <?php 
              echo $_SESSION['timer'];
            ?>
          </h2>
        </div>
    </main>
    <footer>
      <p>
        Developed by 104auteam
        <a href='https://github.com/104auteam'><img src="images/github.png" alt="Github"></a>
      </p>
    </footer>
  </body>
</html>
