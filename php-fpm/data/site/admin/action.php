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

                    // Check available user on champ and create if no
                    $query   = $conn->query("SELECT Username FROM $table WHERE `Username` = '$username'");
                    $available = $query->fetch(); $available = $available[0];
                    if (!$available) $query   = $conn->query("INSERT $table (Username) VALUES ('$username')");

                    foreach ($DEVICES_LIST as $device) {
                        $link   = $_POST[$device];
                        $query  = $conn->query("UPDATE $table SET `$device`='$link' WHERE `Username`='$username'");
                    }
                }
                else {
                    // For debug lol :))))
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
                    
                    // Check available user on champ and create if no
                    $query   = $conn->query("SELECT Username FROM $table WHERE `Username` = '$username'");
                    $available = $query->fetch(); $available = $available[0];

                    if (!$available) $query   = $conn->query("INSERT $table (Username) VALUES ('$username')");

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
            case 'AddUser':
                // Add new User to champ
                $number     = $_POST['number'] + 1;
                $username   = $_POST['user'];
                $password   = $_POST['pass'];
                $md5pass    = md5($_POST['pass']);
                $champ      = $_POST['champ'];
                if( isset($_POST['adminpriv']) ) $adminpriv = 1;
                else                             $adminpriv = 0;
                $module     = $_POST['module'];

                // Check username in DB
                $query   = $conn->query("SELECT Username FROM usersinfo.`credentials` WHERE `Username` = '$username'");
                $break = $query->fetch(); $break = $break[0];
                if($break) break;

                // Add into usersinfo
                $query = $conn->query(
                    "INSERT usersinfo.`credentials`(Number, Username, Password, adminpriv) 
                    VALUES ($number, '$username', '$md5pass', $adminpriv);                    
                    INSERT usersinfo.`currentstate`(Username, State, Module, Championship) 
                    VALUES ('$username', 1, '$module', '$champ');                    
                    -- ");
                
                // Write clean username and pass to file 
                $fp = fopen('data/9bc65c2abec141778ffaa729489f3e87.txt', 'a') or die("Unable to open file!");
                $string = "$username/$password\n";
                fwrite($fp, $string);
                fclose($fp);
                
                // Get `Complex` champ 
                $query   = $conn->query("SELECT Complex FROM championships.champ_list WHERE `Event` = '$champ'");
                $complex_champ = $query->fetch(); $complex_champ = $complex_champ[0];

                if ($complex_champ) {
                    $query = $conn->query(
                        "INSERT championships.`$champ` (Username) 
                        VALUES ('$username');                    
                        ");
                }
                else {
                    $modules = ['A', 'B', 'C']; 
                    foreach ( $modules  as $module) {
                        $table = $champ . $module;
                        // echo $table;
                        $query = $conn->query(
                            "INSERT championships.`$table` (Username) 
                            VALUES ('$username');                    
                            ");
                    }
                }
                // For debug lol :))))
                // $query   = $conn->query("SELECT Complex213 FROM championships.champ_list WHERE `Event` = '$champ'");$complex_champ = $query->fetch();
                break;

                case 'DropUser':
                    // Change Timer for Champ
                    $username   = $_POST['username'];
                    // Drop from ALL Championships
                    $query = $conn->query("SELECT `Event` FROM championships.champ_list");
                    $champlist = $query->fetchAll(PDO::FETCH_COLUMN, 0);
                    // print_r($champlist);
                    foreach ($champlist as $number => $champ) {
                        // Parse All championat
                        // Get `Complex` champ 
                        $query   = $conn->query("SELECT Complex FROM championships.champ_list WHERE `Event` = '$champ'");
                        $complex_champ = $query->fetch(); $complex_champ = $complex_champ[0];
                        if($complex_champ) {
                            // Drop from champ table if complex
                            $query = $conn->query("DELETE FROM championships.`$champ` WHERE Username='$username'");
                        }
                        else {
                            // Or Drop from all module champ table if not complex
                            $modules = ['A', 'B', 'C']; 
                            foreach ( $modules  as $module) {
                                $table = $champ . $module;
                                $query = $conn->query("DELETE FROM championships.`$table` WHERE Username='$username'");
                            }
                        }
                    }

                    // Drop string from txt fiile
                    $string=file_get_contents('data/9bc65c2abec141778ffaa729489f3e87.txt');
                    $string=explode("\n",$string);
                    foreach($string as $key=>$value)
                    {
                        $line=explode("/",$value);
                        // $line[0] - username 
                        // $line[1] - password
                        if( $line[0] == $username ) {
                            unset($string[$key]);
                        }
                    }
                    $string=implode("\n",$string);
                    file_put_contents('data/9bc65c2abec141778ffaa729489f3e87.txt', $string);
                    // Drop from usersinfo
                    $query      = $conn->query(
                        "DELETE FROM usersinfo.`credentials` WHERE Username='$username';
                        SET @i := 0;
                        UPDATE usersinfo.`credentials` SET  `Number` = (@i := @i + 1);
                        ALTER TABLE usersinfo.`credentials` AUTO_INCREMENT = 1;
                        DELETE FROM usersinfo.`currentstate` WHERE Username='$username';
                        ");
                break;
            default: 
                break; 
        }
    }
    Redirect('admin/admin.php');
?>