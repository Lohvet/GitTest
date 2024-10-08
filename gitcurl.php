<?php
    $servername = "localhost";
    $username = "store_admin";
    $password = "password1#";
    $dbname = "store_data";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);

    }

    $query = "CREATE TABLE IF NOT EXISTS planets (ID INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255) NOT NULL, rotation_period VARCHAR(50),
        orbital_period VARCHAR(50),
        diameter VARCHAR(50),
        climate VARCHAR(255),
        gravity VARCHAR(100),
        terrain VARCHAR(255),
        surface_water VARCHAR(50),
        population VARCHAR(50),
        created DATETIME,
        edited DATETIME,
        url VARCHAR(255)
    )";

    if ($conn->query($query) === TRUE) {
        echo "planets created successfully<br>";
    } else {
        echo $conn->error;
    }

    //initiate cURL
    $curl = curl_init('https://swapi.dev/api/planets/');

    if (!$curl) {
        die("Couldn't initialize a cURL handle");
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);

    if (curl_errno($curl)) {
        echo(curl_error($curl));
        die();
    }

    curl_close($curl);

    // JSON code to fetch data
    $data = json_decode($result, true);

    foreach ($data['results'] as $planet) {
        $name = mysqli_real_escape_string($conn, $planet['name']);
        $rotation_period = mysqli_real_escape_string($conn, $planet['rotation_period']);
        $orbital_period = mysqli_real_escape_string($conn, $planet['orbital_period']);
        $diameter = mysqli_real_escape_string($conn, $planet['diameter']);
        $climate = mysqli_real_escape_string($conn, $planet['climate']);
        $gravity = mysqli_real_escape_string($conn, $planet['gravity']);
        $terrain = mysqli_real_escape_string($conn, $planet['terrain']);
        $surface_water = mysqli_real_escape_string($conn, $planet['surface_water']);
        $population = mysqli_real_escape_string($conn, $planet['population']);
        $created = mysqli_real_escape_string($conn, $planet['created']);
        $edited = mysqli_real_escape_string($conn, $planet['edited']);
        $url = mysqli_real_escape_string($conn, $planet['url']);

        $sql = "INSERT INTO planets (name, rotation_period, orbital_period, diameter, climate, gravity, terrain, surface_water, population, created, edited, url) 
                VALUES ('$name', '$rotation_period', '$orbital_period', '$diameter', '$climate', '$gravity', '$terrain', '$surface_water', '$population', '$created', '$edited', '$url')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully for planet: $name <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $sql = "SELECT * FROM planets";
    $result = $conn->query($sql);

    $planets = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $planets[] = array( //append each associative array to become multi-dimensional array
                'name' => $row['name'],
                'rotation_period' => $row['rotation_period'],
                'orbital_period' => $row['orbital_period'],
                'diameter' => $row['diameter'],
                'climate' => $row['climate'],
                'gravity' => $row['gravity'],
                'terrain' => $row['terrain'],
                'surface_water' => $row['surface_water'],
                'population' => $row['population'],
                'created' => $row['created'],
                'edited' => $row['edited'],
                'url' => $row['url']
            );
        }
    }

    echo "<pre>";
    print_r($planets);
    echo "</pre>";

    $conn->close();
?>
