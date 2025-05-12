<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    
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
        
        <h2 class="text-center mb-4">User Profile</h2>
  
        <div class="row g-4 align-items-start justify-content-center">
  
          <!-- User Image Column -->
          <div class="col-md-4 text-center">
            <img src="../../Images/4163433.jpg" alt="User Image" class="img-fluid rounded-circle shadow" style="max-width: 200px;">
          </div>
  
          <!-- Form Column -->
          <div class="col-md-8">
            <form action="#" method="POST" enctype="multipart/form-data">
              
              <!-- Full Name -->
              <div class="form-group mb-3">
                <label for="full_name" class="form-label">Full Name:</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="your full name" placeholder="Enter your full name" disabled>
              </div>
  
              <!-- Email -->
              <div class="form-group mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="your email" placeholder="Enter your university email" disabled>
              </div>
  
              <!-- Password -->
              <div class="form-group mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" value="your password" placeholder="Enter a proper password" disabled>
              </div>
  
              <!-- University -->
              <div class="form-group mb-3">
                <label for="university" class="form-label">University:</label>
                <input type="text" id="university" name="university" class="form-control" value="your university name" placeholder="Enter your university name" disabled>
              </div>
  
              <!-- Phone Number -->
              <div class="form-group mb-3">
                <label for="phone_number" class="form-label">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" class="form-control" value="your phone number" placeholder="Enter your phone number" disabled>
              </div>
  
              <!-- Submit Button -->
              <div class="text-end">
                <button type="button" class="btn btn-info mt-2 text-white">Edit profile</button>
              </div>
  
            </form>
          </div>
  
        </div>
  
      </div>
    </div>
  </div>
  
   <?php include '../Footer.php'; ?>
</body>
</html>