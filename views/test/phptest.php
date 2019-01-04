<?php echo '<p>DB Test:</p>';    

    // In the variables section below, replace user and password with your own MySQL credentials as created on your server
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    if (array_key_exists("host", $url)){
        $server = $url["host"];
        $username = $url["user"];
        $password = $url["pass"];
        $db = substr($url["path"], 1);

        // Create MySQL connection
        $conn = new mysqli($server, $username, $password, $db);
    } else {
        $servername = "localhost";
        $username = "RTAdmin";
        $password = "rogers001";

        // Create MySQL connection
        $conn = mysqli_connect($servername, $username, $password);
    }

    // Check connection - if it fails, output will include the error message
    if (!$conn) {
        die('<p>Connection failed: <p>' . mysqli_connect_error());
    }
    echo '<p>Connected successfully as ' . $username . '</p>';
    
?>