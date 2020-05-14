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
    <link rel="stylesheet" type="text/css" href="/css/admin.css">
    <script src="/scripts/jquery-3.5.0.min.js"></script>
    <script src="/scripts/function.js"></script>
    <title>RemoteTopology</title>
    <style>
        html {
            -moz-user-select: auto;
            -khtml-user-select: auto;
            user-select: auto;
        }
    </style>
  </head>
  <body>
    <div class="top-panel">
        <div class="userinfo-top-panel">
            <?
            echo 'You logged in as '.$_SESSION['username'];
            ?>
        </div>
        <div class="logout-top-panel">
            <a href="/session_destroy.php">Logout</a>
        </div>
    </div>
    <div class="admin-info">
    
        <!-- TODO: Feature Update ALL TryState -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                TryState UPDATE FOR ALL (except admins):
                <br>
                <input type="radio" id="activate" name="activate" value="True">
                <label for="activate">Activate</label>
                <input type="radio" id="deactivate" name="activate" value="False">
                <label for="deactivate">Deactivate</label>
                <input type="hidden" name="action" value="ChangeStateAll">
                <input type="submit" value="Apply">
            </form>
        </div>

        <!-- TODO: Feature Update TryState for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                TryState UPDATE FOR USER:
                <br>
                <input type="radio" id="activateUser" name="activateUser" value="True">
                <label for="activateUser">Activate</label>
                <input type="radio" id="deactivateUser" name="activateUser" value="False">
                <label for="deactivateUser">Deactivate</label>
                
                <?php echo $SELECT_USERS; ?>
                <input type="hidden" name="action" value="ChangeStateUser">
                <input type="submit" value="Apply">    
            </form>
        </div>

        <!-- TODO: Feature Update TryState for user range -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
            TryState UPDATE FOR Users range:
                <br>
                <input type="radio" id="activateUsers" name="activateUser" value="True">
                <label for="activateUsers">Activate</label>
                <input type="radio" id="deactivateUsers" name="activateUser" value="False">
                <label for="deactivateUsers">Deactivate</label>
                
                <select name="username1" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <select name="username2" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <input type="hidden" name="action" value="ChangeStateUsers">
                <input type="submit" value="Apply">
            </form>
        </div>

        <!-- TODO: Feature Update ALL Module -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Module UPDATE FOR ALL:
                <br>
                <?php echo $SELECT_MODULES; ?>
                <input type="hidden" name="action" value="ChangeModuleAll">
                <input type="submit" value="Apply">
            </form>
        </div>

        <!-- TODO: Feature Update Module for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Module UPDATE FOR User:
                <br>
                <?php echo $SELECT_USERS; ?>
                <?php echo $SELECT_MODULES; ?>
                <input type="hidden" name="action" value="ChangeModuleUser">
                <input type="submit" value="Apply">
            </form>
        </div>

        <!-- TODO: Feature Update Module for user range-->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Module UPDATE FOR Users range:
                <br>
                <select name="username1" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <select name="username2" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <?php echo $SELECT_MODULES; ?>
                <input type="hidden" name="action" value="ChangeModuleUsers">
                <input type="submit" value="Apply">
            </form>
        </div>
        
        <!-- TODO: Feature Update Module for user range-->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Module UPDATE FOR Users range:
                <br>
                <select name="username1" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <select name="username2" class="admin-select">
                <?php echo $SELECT_USERS_GET; ?>
                <?php echo $SELECT_MODULES; ?>
                <input type="hidden" name="action" value="ChangeModuleUsers">
                <input type="submit" value="Apply">
            </form>
        </div>
        
        <!-- TODO: Feature Update Championship for ALL -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Champ UPDATE FOR ALL:
                <br>
                <?php echo $SELECT_CHAMP; ?>
                <input type="hidden" name="action" value="ChangeChampAll">
                <input type="submit" value="Apply">    
            </form>
            <h3 style="color: black; background-color: cadetblue; width: 200px; text-align: center;">DONT TOUCH</h3>
        </div>
        
        <!-- TODO: Feature Update password for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php">
                Password UPDATE FOR USER (clear text):
                <br>
                <?php echo $SELECT_USERS; ?>
                <input type="password" name="password">
                <input type="hidden" name="action" value="ChangePassUser">
                <input type="submit" value="Apply">    
            </form>
        </div>
        <table class="admin-table-main">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>AdminPriv</th>
                <th>CurrentModule</th>
                <th>Championship</th>
                <th>TryState</th>
            </tr>
        <?php

        // Quantly users ->
        $query = $conn->query("SELECT MAX(Number) FROM credentials");
        $result = $query->fetch();
        for ($userNumber = 1; $userNumber <= $MAX ; $userNumber++) { 
            // Query Username
            $query = $conn->query("SELECT `Username` FROM `credentials` WHERE `Number`=$userNumber");
            $username = $query->fetch();
            // Query Password
            $query = $conn->query("SELECT `Password` FROM `credentials` WHERE `Number`=$userNumber");
            $password = $query->fetch();
            // Query AdminPriv
            $query = $conn->query("SELECT `adminpriv` FROM `credentials` WHERE `Number`=$userNumber");
            $adminpriv = $query->fetch();
            // Query CurrentModule
            $query = $conn->query("SELECT `Module` FROM `currentstate` WHERE `Username`='$username[0]'");
            $module = $query->fetch();
            // Query Current championship
            $query = $conn->query("SELECT `Championship` FROM `currentstate` WHERE `Username`='$username[0]'");
            $champ = $query->fetch();
            // Query Try State
            $query = $conn->query("SELECT `State` FROM `currentstate` WHERE `Username`='$username[0]'");
            $trystate = $query->fetch();
        ?>
            <tr>
                <td><?php echo $userNumber; ?></td>
                <td><?php echo $username[0]; ?></td>
                <td><?php echo $password[0]; ?></td>
                <td><?php echo $adminpriv[0]; ?></td>
                <td><?php echo $module[0]; ?></td>
                <td><?php echo $champ[0]; ?></td>
                <?php
                    if($trystate[0]) {
                        echo "<td class='admin-tryactive'>";
                    }
                    else {
                        echo "<td class='admin-deactive'>";
                    }
                    echo $trystate[0].'</td>'; 
                ?>
            </tr>
        <?php } ?>
        </table>

        <!-- TODO: Feature Get A Module Info for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php" >
                GET&UPDATE Module A LINKS FOR USER:
                <br>
                <select name="username" class="admin-select" onchange="GetModuleTable(this.value, 'A')">
                <?php echo $SELECT_USERS_GET; ?>
                <input type="hidden" name="action" value="ChangeALinkUser">
                <div id="AModule"></div>
            </form>
        </div>
        
        <!-- TODO: Feature Get B Module Info for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php" >
                GET&UPDATE Module B LINKS FOR USER:
                <br>
                <select name="username" class="admin-select" onchange="GetModuleTable(this.value, 'B')">
                <?php echo $SELECT_USERS_GET; ?>
                <input type="hidden" name="action" value="ChangeBLinkUser">
                <div id="BModule"></div>
            </form>
        </div>
           
        <!-- TODO: Feature Get C Module Info for username -->
        <div class="admin-feature">
            <form method="post" action="/admin/action.php" >
                GET&UPDATE Module C LINKS FOR USER:
                <br>
                <select name="username" class="admin-select" onchange="GetModuleTable(this.value, 'C')">
                <?php echo $SELECT_USERS_GET; ?>
                <input type="hidden" name="action" value="ChangeCLinkUser">
                <div id="CModule"></div>
            </form>
        </div>

        <!-- TODO: Link to Timer -->
            
        <a href="http://demo2020.wsr39.online/dcnt/admin/index.php?_rtp=login">Timer Page</a>
        <br>
        <table class='admin-table-timer'>
            <tbody>
                <!-- Demo2020 Timer -->
                <tr>
                    <td>Demo 2020</td> 
                    <td><script type="text/javascript" src="http://demo2020.wsr39.online/dcnt/cn/cn.php?id=1004"></script></td>
                </tr>
                
                <!-- MejVuz Timer -->
                <tr>
                    <td>MejVuz</td> 
                    <td><script type="text/javascript" src="http://demo2020.wsr39.online/dcnt/cn/cn.php?id=1006"></script></td>
                </tr>
                
            </tbody>
        </table>

    </div>
    <footer>
      <p>
        Developed by 104auteam
        <a href='https://github.com/104auteam'><img src="/images/github.png" alt="Github"></a>
      </p>
    </footer>
  </body>
</html>
