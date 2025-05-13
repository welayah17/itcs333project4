<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php
require '../db.php'; 

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $fullName = test_input($_POST['full_name']);
      $username = test_input($_POST['username']);
      $email = test_input($_POST['email']);
      $password = test_input($_POST['password']);
      $university = test_input($_POST['university']);
      $phoneNumber = test_input($_POST['phone_number']);
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $imagePath = "";

      if (!empty($_FILES['profile_picture']['name'])) {
          $targetDir = "../Images/Profile/";
          $filename = time() . "_" . basename($_FILES['profile_picture']['name']);
          $targetFile = $targetDir . $filename;

          if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
              $imagePath = $targetFile;
          }
      }

      try {
          $stmt = $db->prepare("INSERT INTO User (fullName, username, universityEmail, password, universityName, image, phoneNumber)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
          $stmt->execute([$fullName, $username, $email, $hashedPassword, $university, $imagePath, $phoneNumber]);

          if ($stmt->rowCount() === 1) {
              header("Location: ../Login.php");
              exit();
          } else {
              echo "Something went wrong while inserting data.";
          }
      } catch (PDOException $e) {
          die("Error: " . $e->getMessage());
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    
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

/* Optional: Adjust padding for smaller screens */
@media (max-width: 576px) {
  .form-container {
    padding: 20px 15px;
    margin: 30px auto;
  }
}

    </style>
</head>
<body>
  <?php include '../Header.php'; ?>

    <div class="container form-container my-5">
  <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12">
      <h2 class="text-center mb-4">User Registration</h2>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

        <!-- Full Name -->
        <div class="form-group mb-3">
          <label for="full_name" class="form-label">Full Name:</label>
          <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Enter your full name" required
            pattern="[A-Za-z\s]{2,50}" title="Please enter 2-50 alphabetic characters only.">
        </div>

        <!-- Username -->
        <div class="form-group mb-3">
          <label for="username" class="form-label">Username:</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="Enter a username" required
            pattern="[A-Za-z0-9_]{4,30}" title="Username must be 4-30 characters and can contain letters, numbers, and underscores.">
        </div>

        <!-- Email -->
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Enter your university email" required
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address.">
        </div>

        <!-- Password -->
        <div class="form-group mb-3">
          <label for="password" class="form-label">Password:</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter a proper password" required
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
            title="Password must be at least 8 characters long and include a number, uppercase and lowercase letter.">
        </div>

        <!-- University -->
        <div class="form-group mb-3">
          <label for="university" class="form-label">University:</label>
          <input type="text" id="university" name="university" class="form-control" placeholder="Enter your university name"
            pattern=".{2,100}" title="University name must be at least 2 characters.">
        </div>

        <!-- Profile Picture -->
        <div class="form-group mb-3">
          <label for="profile_picture" class="form-label">Profile Picture:</label>
          <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept="image/*">
        </div>

        <!-- Phone Number -->
        <div class="form-group mb-3">
          <label for="phone_number" class="form-label">Phone Number:</label>
          <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="Enter your phone number"
            pattern="^[0-9]{8}$" title="Phone number should be 8 digits.">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-info mt-2 text-white">Register</button>
      </form>
    </div>
  </div>
</div>

<?php include '../Footer.php'; ?>
</body>
</html>