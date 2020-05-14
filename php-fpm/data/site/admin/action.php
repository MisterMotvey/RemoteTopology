<?php
    require_once('../session.php');
    // If you are login -> redirect 
    if (!$_SESSION['adminpriv']) {
        Redirect('');
    }
    $conn = ConnectToDB();
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'ChangeStateAll':
                // Change state for all ->
                $activate   = $_POST['activate'];
                $query      = $conn->query("UPDATE `currentstate` SET `State`=$activate WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");
                break;
            case 'ChangeStateUser':
                // Change state for username ->
                $activate   = $_POST['activateUser'];
                $username   = $_POST['username'];
                $query      = $conn->query("UPDATE `currentstate` SET `State`=$activate WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0 AND `Username`='$username')");  
                break;
            case 'ChangeStateUsers':
                // Change state for user range ->
                $activate   = $_POST['activateUser'];
                $username1  = $_POST['username1'];
                $username2  = $_POST['username2'];
                $query      = $conn->query("UPDATE `currentstate` SET `State`=$activate 
                WHERE `Username` IN (
                    SELECT `Username` FROM `credentials` WHERE `Number` BETWEEN 
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username1')
                    AND
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username2')
                )
                ");
                break;
            case 'ChangePassUser':
                // Change password for username
                $username   = $_POST['username'];
                $password   = md5($_POST['password']);
                $query      = $conn->query("UPDATE `credentials` SET `Password`='$password' WHERE `adminpriv`=0 AND `Username`='$username'");  
                break;
            case 'ChangeModuleAll':
                // Change module for ALL
                $module = $_POST['module'];
                $query  = $conn->query("UPDATE `currentstate` SET `Module`='$module' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");  
                break;
            case 'ChangeModuleUser':
                // Change module for user
                $module     = $_POST['module'];
                $username   = $_POST['username'];
                $query      = $conn->query("UPDATE `currentstate` SET `Module`='$module' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0 AND `Username`='$username')");  
                break;
            case 'ChangeModuleUsers':
                // Change module for users range
                $module     = $_POST['module'];
                $username1  = $_POST['username1'];
                $username2  = $_POST['username2'];
                $query = $conn->query("UPDATE `currentstate` SET `Module`='$module' 
                WHERE `Username` IN (
                    SELECT `Username` FROM `credentials` WHERE `Number` BETWEEN 
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username1')
                    AND
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username2')
                )
                ");  
                break;
            case 'ChangeALinkUser':
                // Change link on module A for user
                $username   = $_POST['username'];
                $sql        = "SELECT A FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query      = $conn->query($sql);
                $DEVICES    = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['A']);
                // Create table name
                $query = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                $champ = $query->fetch(); $champ = $champ[0];
                $table = 'championships.`' . $champ .'A`';
                
                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                }
                break;
            case 'ChangeBLinkUser':
                // Change link on module B for user
                $username   = $_POST['username'];
                $sql        = "SELECT B FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query      = $conn->query($sql);
                $DEVICES    = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['B']);
                // Create table name
                $query = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                $champ = $query->fetch(); $champ = $champ[0];
                $table = 'championships.`' . $champ .'B`';
                
                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                }
                break;
            case 'ChangeCLinkUser':
                // Change link on module C for user
                $username   = $_POST['username'];
                $sql        = "SELECT C FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query      = $conn->query($sql);
                $DEVICES    = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['C']);
                // Create table name
                $query = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                $champ = $query->fetch(); $champ = $champ[0];
                $table = 'championships.`' . $champ .'C`';

                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                }
                break;
            case 'ChangeChampAll':
                // Change Championship for ALL
                $champ  = $_POST['champ'];
                $query  = $conn->query("UPDATE `currentstate` SET `Championship`='$champ' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");
                break;
            case 'ChangeChampUser':
                // Change Championship for user
                $champ      = $_POST['champ'];
                $username   = $_POST['username'];
                $query  = $conn->query("UPDATE `currentstate` SET `Championship`='$champ' WHERE `Username`='$username'");
                break;
            case 'ChangeChampUsers':
                // Change Championship for ALL
                $champ      = $_POST['champ'];
                $username1  = $_POST['username1'];
                $username2  = $_POST['username2'];
                $query = $conn->query("UPDATE `currentstate` SET `Championship`='$champ' 
                WHERE `Username` IN (
                    SELECT `Username` FROM `credentials` WHERE `Number` BETWEEN 
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username1')
                    AND
                    (SELECT `Number` FROM `credentials` WHERE `Username`='$username2')
                )
                ");  
                break;
            case 'ChangeTimerChamp':
                // Change Timer for Champ
                $champ  = $_POST['champ'];
                $timer  = $_POST['timer'];
                $query = $conn->query("UPDATE championships.champ_list SET `Timer`='$timer' WHERE `Event`='$champ'");
                break;
            default:
                break; 
        }
    }
    Redirect('admin/admin.php');
?>