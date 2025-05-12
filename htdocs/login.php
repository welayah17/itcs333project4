<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php

include 'db.php'; // Include DB connection
session_start(); // Start session at the very beginning

$errors = [];
$username = $password = "";

function test_input($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["username"])) {
    $errors['userRequired'] = "User name is required!";
  } else {
    $username = test_input($_POST["username"]);
  }

  if (empty($_POST["password"])) {
    $errors['passwordRequired'] = "Password is required!";
  } else {
    $password = test_input($_POST["password"]);
  }

  if (empty($errors)) {
    $prepare = $db->prepare("SELECT * FROM `user` WHERE username=?");
    $prepare->execute([$username]);

    if ($prepare->rowCount() > 0) {
      $data = $prepare->fetch();
      if (password_verify($password, $data['password'])) {
        $_SESSION['username'] = $data['username'];
        header("Location: index.php");
        exit(); // Always exit after a header redirect
      } else {
        $errors['ErrorUserOrPass'] = 'Incorrect username or password';
      }
    } else {
      $errors['NoResult'] = 'The entered data does not exist';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in page</title>
    <style>
      .form-container {
        max-width: 800px;
        width: 90%;
        margin: 50px auto;
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
      }

      @media (max-width: 576px) {
        .form-container {
          padding: 20px 15px;
          margin: 30px auto;
        }
      }
    </style>
</head>
<body>
  <?php include '../Header.html'; ?>

  <div class="container form-container my-5">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-8 col-sm-12">
        <h2 class="text-center mb-4">User Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
          <!-- Username -->
          <div class="form-group mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username"
              value="<?php echo htmlspecialchars($username); ?>" required>
            <small class="text-danger">
              <?php
                if (isset($errors['userRequired'])) echo $errors['userRequired'];
                if (isset($errors['ErrorUserOrPass'])) echo "<br>" . $errors['ErrorUserOrPass'];
                if (isset($errors['NoResult'])) echo "<br>" . $errors['NoResult'];
              ?>
            </small>
          </div>

          <!-- Password -->
          <div class="form-group mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            <small class="text-danger">
              <?php
                if (isset($errors['passwordRequired'])) echo $errors['passwordRequired'];
              ?>
            </small>
          </div>

          <!-- Submit Button -->
          <div class="d-grid">
            <button type="submit" name="Loginbtn" class="btn btn-info text-white">Login</button>
          </div>

          <!-- Links -->
          <div class="text-center mt-3">
            <p>If you don't have an account? <a href="register.php">Sign up</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../Footer.html'; ?>
</body>
</html>
