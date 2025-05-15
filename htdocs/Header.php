<?php session_start(); ?>
<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Campus Hub</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" />

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/76f78292cc.js" crossorigin="anonymous"></script>

  <!-- Custom Styles -->
  <link rel="stylesheet" href="../CSS/Main.css" />
  <link rel="stylesheet" href="../CSS/MLP.css" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm" style="background: linear-gradient(90deg, #6a11cb, #2575fc);">
    <div class="container-fluid px-4">
      <a class="navbar-brand fw-bold fs-4" href="#">Campus Hub</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto align-items-center gap-2">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
          </li>

          <!-- Services Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="servicesDropdown">
              <li><a class="dropdown-item" href="../Events Calendar/EventsCalendar.html">Events Calendar</a></li>
              <li><a class="dropdown-item" href="../Study Group Finder/index.html">Study Group Finder</a></li>
              <li><a class="dropdown-item" href="../Course Reviews/CourseReviews.html">Course Reviews</a></li>
              <li><a class="dropdown-item" href="../Course Notes/Cours-notes.html">Course Notes</a></li>
              <li><a class="dropdown-item" href="../Campus News/phase1/campusNews.html">Campus News</a></li>
              <li><a class="dropdown-item" href="../Club Activities/page1.html">Club Activities</a></li>
              <li><a class="dropdown-item" href="../Student Marketplace/Listings.php">Student Marketplace</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#About">About</a>
          </li>

          <!-- Account Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" id="accountDropdown"
              role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="accountDropdown">
               <?php if (isset($_SESSION['username'])): ?>
                <li><a class="dropdown-item" href="../Account/profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="../Account/signout.php">Sign out</a></li>
              <?php else: ?>
                <li><a class="dropdown-item" href="../Account/login.php">Sign in</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="../Account/register.php">Register Now</a></li>
              <?php endif; ?>
            </ul>
          </li>

          <!-- Search Bar -->
          <li class="nav-item">
            <div class="search-bar">
              <input type="text" placeholder="Search Campus Hub..." aria-label="Search Campus Hub" />
              <button title="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
