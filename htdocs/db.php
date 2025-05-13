<?php
    $host = "127.0.0.1";  // localhost also works sometimes
    $dbname = getenv("db_name");
    $user = getenv("db_user");
    $pass = getenv("db_pass");

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     // echo "<p>Connected to database!</p>";
    } catch (PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage() . "</p>";
    }
    ?>
