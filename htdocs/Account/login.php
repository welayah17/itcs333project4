<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/76f78292cc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/Main.css">
    
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
    <div class="container form-container my-5">
        <div class="row justify-content-center">
          <div class="col-xl-6 col-lg-7 col-md-8 col-sm-12">
            <h2 class="text-center mb-4">User Login</h2>
            <form action="#" method="POST">
      
              <!-- Email -->
              <div class="form-group mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required
                  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                  title="Please enter a valid email address.">
              </div>
      
              <!-- Password -->
              <div class="form-group mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required
                  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                  title="Password must be at least 8 characters long, include a number, uppercase and lowercase letter.">
              </div>
      
              <!-- Submit Button -->
              <div class="d-grid">
                <button type="submit" class="btn btn-info text-white">Login</button>
              </div>
      
              <!-- Optional: Forgot Password Link -->
              <div class="text-center mt-3">
                <a href="#" class="text-decoration-none text-muted">Forgot your password?</a>
              </div>
            </form>
          </div>
        </div>
      </div>
         <!-- Bootstrap 5 Footer -->
  <footer class="text-white text-center sticky-bottom py-4 mt-5" style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
    <div class="container">
      <div class="mb-3">
        <a href="#" class="text-white me-4"><i class="fa-solid fa-phone"></i></a>
        <a href="#" class="text-white me-4"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="#" class="text-white me-4"><i class="fa-brands fa-instagram"></i></a>
        <i class="fa-regular fa-copyright"></i> Copyright 2025 - All Rights Reserved. University of Bahrain
      </div>
    </div>
  </footer>
          
          <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>  
</body>
</html>