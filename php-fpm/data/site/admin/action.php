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
            case 'ChangeLinkUser':
                $module     = $_POST['module'];
                $username   = $_POST['username'];
                $query   = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                $champ   = $query->fetch(); $champ = $champ[0];
                // Set Device list in DEVICES_LIST:
                // $DEVICES_LIST[0] => 'L-CLI-A'
                // $DEVICES_LIST[1] => 'L-CLI-B'
                $query   = $conn->query("SELECT Complex FROM championships.champ_list WHERE `Event` = '$champ'");
                $complex_champ = $query->fetch(); $complex_champ = $complex_champ[0];

                if ($complex_champ == True) {
                    $query = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                    $champ = $query->fetch(); $champ = $champ[0];
                    $table = 'championships.`' . $champ .'`';
                    $sql            = "SELECT Complex FROM championships.Devices WHERE `Champ` = '$champ'";
                    $query          = $conn->query($sql);
                    $DEVICES        = $query->fetch(PDO::FETCH_ASSOC);
                    $DEVICES_LIST   = preg_split("/,/", $DEVICES['Complex']);
                    foreach ($DEVICES_LIST as $device) {
                        $link   = $_POST[$device];
                        $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                    }
                }
                else {
                    // echo $module;       $query   = $conn->query("SELECT Complex213 FROM championships.champ_list WHERE `Event` = '$champ'");$complex_champ = $query->fetch();
                    $sql        = "SELECT $module FROM championships.Devices WHERE `Champ` =
                    (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                    $query      = $conn->query($sql);
                    $DEVICES    = $query->fetch(PDO::FETCH_ASSOC);
                    $DEVICES_LIST = preg_split("/,/", $DEVICES[$module]);
                    // Create table name
                    $query = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username'");
                    $champ = $query->fetch(); $champ = $champ[0];
                    $table = 'championships.`' . $champ . $module . '`';                    
                    foreach ($DEVICES_LIST as $device) {
                        $link   = $_POST[$device];
                        $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                    }
                    
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