<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php session_start(); try { if (isset($_SESSION['un'])) {
unset($_SESSION['un']); session_destroy(); header('Location: Login.php');
exit(); } else { throw new Exception("No active session found."); } } catch
(Exception $e) { // Let execution continue to show the error HTML below } ?>

<!-- Error Message -->
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="refresh" content="5;url=Dashboard.html" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Out Error</title>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
  </head>
  <body
    class="d-flex justify-content-center align-items-center vh-100 bg-light"
  >
    <div class="text-center">
      <h1 class="mb-3 text-danger">⚠️ Sign Out Failed</h1>
      <p class="text-muted">
        Oops! Something went wrong while signing you out. Please try again.
      </p>
      <a href="Dashboard.html" class="btn btn-outline-danger mt-4"
        >Return to Dashboard</a
      >
      <p class="mt-3 text-muted">Redirecting in 5 seconds...</p>
    </div>
  </body>
</html>
