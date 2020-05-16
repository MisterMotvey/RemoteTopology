<?php
require_once('../session.php');
if (!$_SESSION['adminpriv']) {
    Redirect('');
}
// Just Get module table for username (for admin panel)
// If not admin -> to home

// INIT
$q       = $_GET['q'];
$module  = $_GET['m'];
$conn    = ConnectToDB();
$query   = $conn->query("SELECT Championship FROM usersinfo.currentstate WHERE `Username` = '$q'");
$champ   = $query->fetch(); $champ = $champ[0];

// Set Device list in DEVICES_LIST:
// $DEVICES_LIST[0] => 'L-CLI-A'
// $DEVICES_LIST[1] => 'L-CLI-B'
$query   = $conn->query("SELECT Complex FROM championships.champ_list WHERE `Event` = '$champ'");
$complex_champ = $query->fetch(); $complex_champ = $complex_champ[0];

if ($complex_champ == True) {
    $table          = 'championships.`' . $champ . '`';
    $sql            = "SELECT Complex FROM championships.Devices WHERE `Champ` = '$champ'";
    $query          = $conn->query($sql);
    $DEVICES        = $query->fetch(PDO::FETCH_ASSOC);
    $DEVICES_LIST   = preg_split("/,/", $DEVICES['Complex']);
}
else {
    $table          = 'championships.`' . $champ . $module . '`';
    $sql            = "SELECT $module FROM championships.Devices WHERE `Champ` = '$champ'";
    $query          = $conn->query($sql);
    $DEVICES        = $query->fetch(PDO::FETCH_ASSOC);
    $DEVICES_LIST   = preg_split("/,/", $DEVICES[$module]);
}





// For DEBUG
// foreach ($DEVICES as $device) {
//     echo $device;
// }
// Create Table with devices
echo "<table class='admin-table-main'>
<tr>
<th>Device</th>
<th>Link</th>
</tr>";
$query = $conn->query("SELECT * FROM $table WHERE `Username` = '$q'");
$result = $query->fetch(PDO::FETCH_ASSOC);

foreach ($DEVICES_LIST as $device) {
    echo "<tr>";
    echo "<td>" . $device . "</td>";
    echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
    echo "</tr>";
}
echo "</table>";
echo "<input type='submit' value='Update'>";
// END

// $_SESSION['DEVICE_LIST_A'] = $DEVICE_LIST_A = array(
//     'L-CLI-A',
//     'L-CLI-B',
//     'L-RTR-A',
//     'L-RTR-B',
//     'L-FW',
//     'L-SRV',
//     'OUT-CLI',
//     'R-FW',
//     'R-SRV',
//     'R-RTR',
//     'R-CLI'   
// );
// $_SESSION['DEVICE_LIST_B'] = $DEVICE_LIST_B = array(
//     'DC1',
//     'DCA',
//     'SRV1',
//     'CLI1',
//     'R1',
//     'R2',
//     'DC2',
//     'SRV2',
//     'CLI2'
// );
// $_SESSION['DEVICE_LIST_C'] = $DEVICE_LIST_C = array(
//     'DIGIAddress',
//     'HQ1',
//     'BR1',
//     'SW1',
//     'SW2',
//     'SW3',
//     'FW1',
//     'PC1',
//     'PC2',
//     'SRV1'
// );


// echo "<table class='admin-table-main'>
// <tr>
// <th>Device</th>
// <th>Link</th>
// </tr>";
// // Create table with devices
// switch ($module) {
//     case 'A':
//         $sql    = "SELECT * FROM `AModuleLinks` WHERE `Username` = '".$q."'";
//         $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
//         foreach ($DEVICE_LIST_A as $device) {
//             echo "<tr>";
//             echo "<td>" . $device . "</td>";
//             echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
//             echo "</tr>";
//         }
//         break;

//     case 'B':
//         $sql    = "SELECT * FROM `BModuleLinks` WHERE `Username` = '".$q."'";
//         $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
//         foreach ($DEVICE_LIST_B as $device) {
//             echo "<tr>";
//             echo "<td>" . $device . "</td>";
//             echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
//             echo "</tr>";
//         }
//         break;
        
//     case 'C':
//         $sql    = "SELECT * FROM `CModuleLinks` WHERE `Username` = '".$q."'";
//         $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
//         foreach ($DEVICE_LIST_C as $device) {
//             echo "<tr>";
//             echo "<td>" . $device . "</td>";
//             echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
//             echo "</tr>";
//         }
//         break;

//     default:
//         break;
// }
// echo "</table>";
// echo "<input type='submit' value='Update'>";

?>