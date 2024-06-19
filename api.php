
<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "seven_days";

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$selectData = "SELECT * FROM information WHERE city_name='Udupi'";
$result = mysqli_query($conn, $selectData);

$dataArray = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataArray[] = $row;
    }
}

mysqli_close($conn);

echo json_encode($dataArray);
?>