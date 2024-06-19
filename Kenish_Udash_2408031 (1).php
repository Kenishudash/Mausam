<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7-days data of Udupi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: linear-gradient(135deg, #00feba, #5b548a);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
            background: #abaed3;
        }

        th {
            background-color: #3e3b51;
            color: white;
        }

    </style>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "seven_days";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $createDatabase="CREATE DATABASE seven_days";


    $createTable="CREATE TABLE information(city_name varchar(255),date varchar, temperature float, icon varchar, weather_condition varchar, Humidity float, pressure float, wind_speed float, visibility varchar";

    $city_name = "Udupi";
    $date = date('F j, Y');
    $today_data_exists = false;

    // Check if data for today already exists in the database
    $checkTodayData = "SELECT * FROM information WHERE city_name='$city_name' AND date='$date'";
    $resultTodayData = mysqli_query($conn, $checkTodayData);

    if (mysqli_num_rows($resultTodayData) > 0) {
        $today_data_exists = true;
    }

    if (!$today_data_exists) {
        // Fetch data from the weather API
        $url = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . $city_name . "&appid=e095904cfd08803eaec21e89730df270";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        // Extract relevant information from API response
        $temperature = $data['main']['temp'];
        $icon = $data['weather'][0]['icon'];
        $weather_condition = $data['weather'][0]['description'];
        $humidity =  $data['main']['humidity'];
        $pressure =  $data['main']['pressure'];
        $wind_speed = $data['wind']['speed'];
        $visibility = $data['visibility'];

        $icon_url = "https://openweathermap.org/img/wn/" . $icon . "@2x.png";

        // Insert data into the database
        $insertData = "INSERT INTO information(city_name, date, temperature, icon, weather_condition, Humidity, Pressure, wind_speed, Visibility)
                       VALUES('$city_name', '$date', $temperature, '$icon_url', '$weather_condition', $humidity, $pressure, $wind_speed, '$visibility')";

        if (mysqli_query($conn, $insertData)) {
            echo "";
        } else {
            echo "Failed to insert data: " . mysqli_error($conn);
        }
    }

    // Display data from the database

    $selectData = "SELECT * FROM information WHERE city_name='$city_name'";
    $result = mysqli_query($conn, $selectData);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>City Name</th><th>Date</th><th>Temperature</th><th>Icon</th><th>Weather Condition</th><th>Humidity</th><th>Pressure</th><th>Wind Speed</th><th>Visibility</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["city_name"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["temperature"] . " °C</td>";
            $icon = $row["icon"];
            echo "<td><img src='" . $icon . "' alt='Weather Icon'></td>";
            echo "<td>" . $row["weather_condition"] . "</td>";
            echo "<td>" . $row["Humidity"] . " %</td>";
            echo "<td>" . $row["Pressure"] . " hPa</td>";
            echo "<td>" . $row["Wind_speed"] . " m/s</td>";
            echo "<td>" . $row["Visibility"] . " metre</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No data found for today.";
    }

    mysqli_close($conn);
    ?>
    
</body>

</html>

