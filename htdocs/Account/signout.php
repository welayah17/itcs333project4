<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Log them out
    unset($_SESSION['username']);
    session_destroy();

    // Redirect to login page
    header('Location: login.php');
    exit();
} else {
    // User is not logged in, show error
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Sign Out Failed</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <meta http-equiv="refresh" content="5;url=../index.php">
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100 bg-light">
      <div class="text-center">
        <h1 class="mb-3 text-danger">⚠️ Sign Out Failed</h1>
        <p class="text-muted">Oops! You are not logged in.</p>
        <a href="../index.php" class="btn btn-outline-danger mt-4">Return to Dashboard</a>
        <p class="mt-3 text-muted">You will be redirected in 5 seconds...</p>
      </div>
    </body>
    </html>
    <?php
}
?>
