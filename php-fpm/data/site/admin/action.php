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
                $activate = $_POST['activate'];
                $query = $conn->query("UPDATE `currentstate` SET `State`=$activate WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");
                break;
            case 'ChangeStateUser':
                // Change state for username ->
                $activate = $_POST['activateUser'];
                $username = $_POST['username'];
                $query = $conn->query("UPDATE `currentstate` SET `State`=$activate WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0 AND `Username`='$username')");  
                break;
            case 'ChangeStateUsers':
                // Change state for user range ->
                $activate = $_POST['activateUser'];
                $username1 = $_POST['username1'];
                $username2 = $_POST['username2'];
                $query = $conn->query("UPDATE `currentstate` SET `State`=$activate 
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
                $username = $_POST['username'];
                $password = md5($_POST['password']);
                $query = $conn->query("UPDATE `credentials` SET `Password`='$password' WHERE `adminpriv`=0 AND `Username`='$username'");  
                break;
            case 'ChangeModuleAll':
                // Change module for ALL
                $module = $_POST['module'];
                $query = $conn->query("UPDATE `currentstate` SET `Module`='$module' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");  
                break;
            case 'ChangeModuleUser':
                // Change module for user
                $module = $_POST['module'];
                $username = $_POST['username'];
                $query = $conn->query("UPDATE `currentstate` SET `Module`='$module' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0 AND `Username`='$username')");  
                break;
            case 'ChangeModuleUsers':
                // Change module for users range
                $module = $_POST['module'];
                $username1 = $_POST['username1'];
                $username2 = $_POST['username2'];
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
                $sql    = "SELECT A FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query  = $conn->query($sql);
                $DEVICES = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['A']);

                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE `AModuleLinks` SET `$device`='$link' WHERE `Username`='$username'");
                }
                break;
            case 'ChangeBLinkUser':
                // Change link on module B for user
                // $username   = $_POST['username'];
                // $devicelist = $_SESSION['DEVICE_LIST_B'];
                // for ($devicecount=0; $devicecount < sizeof($devicelist); $devicecount++) { 
                //     $device = $devicelist[$devicecount];
                //     $link   = $_POST[$device];
                //     $query  = $conn->query("UPDATE `BModuleLinks` SET `$device`='$link' WHERE `Username`='$username'");
                // }
                $username   = $_POST['username'];
                $sql    = "SELECT B FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query  = $conn->query($sql);
                $DEVICES = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['B']);

                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE `BModuleLinks` SET `$device`='$link' WHERE `Username`='$username'");
                }

                break;
            case 'ChangeCLinkUser':
                // Change link on module C for user
                // $username   = $_POST['username'];
                // $devicelist = $_SESSION['DEVICE_LIST_C'];
                // for ($devicecount=0; $devicecount < sizeof($devicelist); $devicecount++) { 
                //     $device = $devicelist[$devicecount];
                //     $link   = $_POST[$device];
                //     $query  = $conn->query("UPDATE `CModuleLinks` SET `$device`='$link' WHERE `Username`='$username'");
                // }

                $username   = $_POST['username'];
                $sql    = "SELECT C FROM championships.Devices WHERE `Champ` =
                (SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$username')";
                $query  = $conn->query($sql);
                $DEVICES = $query->fetch(PDO::FETCH_ASSOC);
                $DEVICES_LIST = preg_split("/,/", $DEVICES['C']);

                foreach ($DEVICES_LIST as $device) {
                    $link   = $_POST[$device];
                    $query  = $conn->query("UPDATE `CModuleLinks` SET `$device`='$link' WHERE `Username`='$username'");
                }
                
                break;
            case 'ChangeChampAll':
                // Change Championship for ALL
                $champ   = $_POST['champ'];
                $query = $conn->query("UPDATE `currentstate` SET `Championship`='$champ' WHERE `Username` IN (SELECT `Username` FROM `credentials` WHERE `adminpriv`=0)");
                break;
            default:
                break; 
        }
    }
    Redirect('admin/admin.php');
?>