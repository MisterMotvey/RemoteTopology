<?php
require_once('../session.php');
if (!$_SESSION['adminpriv']) {
    Redirect('');
}
// Just Get module table for username (for admin panel)
// If not admin -> to home

// INIT
$q      = $_GET['q'];
$module = $_GET['m'];
$conn = ConnectToDB();

// Set Device list and save to SESSION
$_SESSION['DEVICE_LIST_A'] = $DEVICE_LIST_A = array(
    'L-CLI-A',
    'L-CLI-B',
    'L-RTR-A',
    'L-RTR-B',
    'L-FW',
    'L-SRV',
    'OUT-CLI',
    'R-FW',
    'R-SRV',
    'R-RTR',
    'R-CLI'   
);
$_SESSION['DEVICE_LIST_B'] = $DEVICE_LIST_B = array(
    'DC1',
    'DCA',
    'SRV1',
    'CLI1',
    'R1',
    'R2',
    'DC2',
    'SRV2',
    'CLI2'
);
$_SESSION['DEVICE_LIST_C'] = $DEVICE_LIST_C = array(
    'DIGIAddress',
    'HQ1',
    'BR1',
    'SW1',
    'SW2',
    'SW3',
    'FW1',
    'PC1',
    'PC2',
    'SRV1'
);


echo "<table class='admin-table-main'>
<tr>
<th>Device</th>
<th>Link</th>
</tr>";
// Create table with devices
switch ($module) {
    case 'A':
        $sql    = "SELECT * FROM `AModuleLinks` WHERE `Username` = '".$q."'";
        $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
        foreach ($DEVICE_LIST_A as $device) {
            echo "<tr>";
            echo "<td>" . $device . "</td>";
            echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
            echo "</tr>";
        }
        break;

    case 'B':
        $sql    = "SELECT * FROM `BModuleLinks` WHERE `Username` = '".$q."'";
        $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
        foreach ($DEVICE_LIST_B as $device) {
            echo "<tr>";
            echo "<td>" . $device . "</td>";
            echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
            echo "</tr>";
        }
        break;
        
    case 'C':
        $sql    = "SELECT * FROM `CModuleLinks` WHERE `Username` = '".$q."'";
        $query  = $conn->query($sql); $result = $query->fetch(PDO::FETCH_ASSOC);
        foreach ($DEVICE_LIST_C as $device) {
            echo "<tr>";
            echo "<td>" . $device . "</td>";
            echo "<td><input type='text' class='admin-get-link-input' value='" . $result[$device] . "' name='" . $device . "' </td>";
            echo "</tr>";
        }
        break;

    default:
        break;
}
echo "</table>";
echo "<input type='submit' value='Update'>";

?>