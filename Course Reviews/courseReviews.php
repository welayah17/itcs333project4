<?php
session_start();

 
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';


unset($_SESSION['error'], $_SESSION['success']);
require 'config/config.php';     

?>
<?php
require 'config/config.php';

// count stars
$ratings = [];
$total_reviews = 0;

for ($i = 1; $i <= 5; $i++) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM reviews WHERE stars = ?");
    $stmt->execute([$i]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ratings[$i] = $result['count'];
    $total_reviews += $ratings[$i];
}

foreach ($ratings as &$rating) {
    if ($total_reviews > 0) {
        $rating = round(($rating / $total_reviews) * 100, 2);
    } else {
        $rating = 0;
    }
}
unset($rating); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <div class="container-fluid">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Course Review</title>

    <!--Bootstrap links-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons @1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #ffcf9d; font-family: Arial, sans-serif;">
  <Style>
    .nav-bar {
      margin-bottom: 0px;
      padding-top: 5px;
      padding-bottom: 5px;
      justify-content: center;
      height: 70px;
      background-color: #a89c29;
    }
    .nav-bar>li {
      display: inline;
      justify-content: center;
    }
    li a {
      display: inline;
      color: white;
      text-align: left;
      padding-top: 16px;
      padding-bottom: 20px;
      padding-left: 15px;
      text-decoration: none;
      font-family: Monaco;
    }
    #search {
      float: right;
      padding: 6px;
      border: none;
      background-color: wheat;
      margin-top: 8px;
      margin-right: 16px;
      width: 15%;
      height: 40px;
      color: #333;
      border-radius: 5px;
    }
    body {
      background-color: #ffcf9d;
      padding-top: 30px;
      font-family: Monaco;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    #header1 {
      margin: 5px;
      padding-top: 10px;
      padding-left: 10px;
      color: #493628;
      font-family: Papyrus
    }
    #header2 {
      padding: 6px;
      width: 65%;
      border-bottom: 4px solid #c14600;
    }
    #textbox1 {
      height: 50px;
    }
    .marginBottom {
      margin-bottom: 70px;
    }
    .pr {
      width: 35%;
    }
    /*Stars*/
    .StarsPosition {
      display: flex;
      align-items: center;
      margin-right: 30px;
    }
    .star:hover {
      color: gold;
    }
    .fullStar {
      color: gold;
    }
    .startsstrap {
      display: flex;
      justify-content: center;
    }
    .star {
      font-size: 30px;
      cursor: pointer;
      color: gray;
      transition: color 0.2s ease-in-out;
    }
    .star.hovered {
      color: gold;
    }
    .star.filled {
      color: gold;
    }
    /*end star*/
    .rating {
      display: flex;
      align-items: center;
      flex-direction: column;
      background-color: #ff9d23;
      border-radius: 10px;
      gap: 10px;
      padding: 10px;
    }
    .button {
      width: 160px;
      height: 35px;
      display: block;
      justify-content: space-between;
      margin: 0 auto;
      background-color: #c14600;
      border-radius: 10px;
      margin: 15px;
    }
    #footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #f0a04b;
      text-align: left;
      padding: 10px;
      color: #333;
      margin-top: auto;
    }
    #page {
      padding-bottom: 10px;
    }
    .container-left {
      display: flex;
      justify-content: left;
      flex-direction: column;
      width: 40%;
      margin-left: 60%;
    }
    .raitNum span {
      margin-left: -10%;
    }
    .container-right {
      display: flex;
      justify-content: right;
      padding-right: 50%;
      margin-top: 0%;
      position: relative;
      bottom: 370px;
    }
    .cards {
      min-width: 350px;
      max-width: 350px;
    }
    .commentStars {
      display: flex;
      align-items: center;
    }
    .commentCardContainer {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .commentcard {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 15px;   
      margin: 0 auto;
      margin-bottom: 10px;
    }
  
    body {
      font-family: Arial, sans-serif;
      background-color: #ffcf9d;
     
    }
    .CommentCard .profilepic img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      border: 2px solid #333;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .CommentCard .commentStar i {
      color: gray;
      font-size: 24px;
      margin-right: 5px;
    }
    .CommentCard .commentStar .fullStar {
      color: gold;
    }
    .CommentCard p {
      font-size: 16px;
      line-height: 1.6;
      margin-top: 10px;
    }
    .CommentCard footer {
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .CommentCard footer small {
      font-size: 14px;
      color: #666;
    }
    .CommentCard footer a {
      color: #333;
      text-decoration: none;
    }
    #commentHeader {
      margin-bottom: 30px;
    }
    #comment {
      width: 300px;
    }
    .textareain {
      width: 80%;
      border-radius: 5px;
    }
    @media(max-width:720px) {
      .btns {
        flex-direction: column;
      }
    }
    .mr-60px {
      margin-left: 60px;
    }
    /*loading */
    #loading {
      text-align: center;
      margin-top: 20px;
    }
    .spinner-border {
      width: 50px;
      height: 50px;
      border-width: 6px;
    }
    #loading p {
      margin-top: 10px;
      font-size: 18px;
      color: #493628;
    }
  </Style>
  <div id="top">
    <header>
      <ul class="nav-bar">
        <li><a href="">Home</a></li>
        <li><a href="">Contact</a></li>
        <li><a href="">About</a></li>
        <input class="search" id="search" type="text" placeholder="Search.." />
      </ul>
    </header>
  </div>
  <br>
  <div id="page">
    <h1 id="header1">Course Reviews</h1>
    <h4 id="header2">Contribute to evaluating the courses you joined</h4>
    <div class="marginBottom"></div>
    <!-- List of Available Courses  -->
    <!-- select -->
    <label for="courseLevel" id="header3">
      <h4>Select the course level:</h4>
    </label>
    <!-- end select -->
<form method="get" action="courseReviews.php" class="mb-4">
    <div class="row position-relative">
        <div class="col-md-6 position-relative">
            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by course name..."
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <!--   search suggestions -->
            <div id="suggestions" class="list-group position-absolute w-100 mt-1" style="z-index: 1000; display: none;"></div>
        </div>
        <div class="col-md-4">
            <select name="level" class="form-select">
                <option value="">All Levels</option>
                <option value="1" <?= ($_GET['level'] ?? '') == '1' ? 'selected' : '' ?>>First Year</option>
                <option value="2" <?= ($_GET['level'] ?? '') == '2' ? 'selected' : '' ?>>Second Year</option>
                <option value="3" <?= ($_GET['level'] ?? '') == '3' ? 'selected' : '' ?>>Third Year</option>
                <option value="4" <?= ($_GET['level'] ?? '') == '4' ? 'selected' : '' ?>>Fourth Year</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </div>
</form>
<!-- Container -->
<div class="container mt-5">
    <div class="row">
        <!-- reviws lines -->
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm">
                <h3><strong>Our students' reviews</strong></h3>
                <p id="total-reviews" class="text-muted">Based on <?= $total_reviews ?> reviews</p>
<?php
// connect to the database
include 'config/config.php';

// import the reviews
// fetch the reviews from the database
$query = $pdo->query("SELECT stars, COUNT(*) as count FROM reviews GROUP BY stars");
$ratings = array_fill(1, 5, 0); // array to hold counts for stars 1 to 5

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $star = (int)$row['stars'];
    if ($star >= 1 && $star <= 5) {
        $ratings[$star] = (int)$row['count'];
    }
}

// calculate total reviews
$total_reviews = array_sum($ratings);
?>

<?php for ($i = 5; $i >= 1; $i--): 
    $count = $ratings[$i];
    $percentage = $total_reviews > 0 ? round(($count / $total_reviews) * 100, 2) : 0;
?>
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span><?= $i ?>.0 <?= $i > 1 ? 'stars' : 'star' ?></span>
            <span class="badge bg-dark"><?= $count ?> review</span>
        </div>
        <div class="progress" style="height: 24px;">
            <div class="progress-bar bg-success" role="progressbar"
                style="width: <?= $percentage ?>%;" 
                aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                <?= $percentage ?>%
            </div>
        </div>
    </div>
<?php endfor; ?>



            </div>
        </div>

        <!-- add reviews -->
        <div class="col-md-8">
            <div class="card p-4 mb-4 shadow-sm">
                <h3 class="text-center"><strong>Share your own review..</strong></h3>

                <!-- Stars -->
                <form action="config/add_review.php" method="POST" class="formtext">
                    <div class="mb-3 text-center">
                        <select id="ratingSelect" class="form-select w-auto mb-3" name="stars" required>
                            <option selected disabled>Select rating</option>
                            <option value="1">⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                        </select>

                        <select name="course_level" id="course_level" class="form-select w-auto mb-3" required>
                            <option selected disabled>Select course level</option>
                            <option value="1">First Year</option>
                            <option value="2">Second Year</option>
                            <option value="3">Third Year</option>
                            <option value="4">Fourth Year</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Enter your name." required>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="coursename" class="form-control" placeholder="Enter course name." required>
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Enter your comment (optional)"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <button type="reset" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
    <h3 id="commentHeader" style="z-index: 999;margin-bottom: 20px;">These are some of your reviews</h3>
    <!--1end-->

        <div style="display: flex; flex-direction: row; gap: 10px; flex-wrap: wrap;margin-bottom: 50px;">
        <!--  vewe comments   -->
        <?php include("config/filter_reviews.php") ?>
      
    </div>
<!--2end-->
  </div>
  <div id="cards-container"></div>
  <div width="100%" height="500px" style="border: none;" frameborder="0"
    style="margin-bottom: 100px;">
  <iframe src="index.html" style="width:100%; height:400px; border:none;"></iframe>
  </div>
  <br></div>
  <div class="btn-toolbar justify-content-between" style="margin-bottom: 50px;" role="toolbar"
    aria-label="Toolbar with button groups">
    <div class="btn-group" role="group" aria-label="First group">
      <button id="btn1" type="button" class="btn btn-outline-secondary">1</button>
      <button id="btn2" type="button" class="btn btn-outline-secondary">2</button>
      <button id="btn3" type="button" class="btn btn-outline-secondary">3</button>
      <button id="btn4" type="button" class="btn btn-outline-secondary">4</button>
    </div>
  </div>
  <!--footer-->
  <div class="marginBottom"></div>
  <footer id="footer">&copy; 2025 Campus Hub | All Rights Reserved</footer>
  </div>
  </div>
  <script src="script.js"></script>
</body>

<!-- Loading Indicator -->
<div id="loading" style="display: none; text-align: center; margin-top: 20px;">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
  <p>Loading data, please wait...</p>
</div>
<script>
  document.querySelector('form[method="POST"]').addEventListener('submit', function(event) {
    const username = document.querySelector('input[name="username"]').value.trim();
    const coursename = document.querySelector('input[name="coursename"]').value.trim();
    const stars = document.querySelector('select[name="stars"]').value;
    const courseLevel = document.querySelector('select[name="course_level"]').value;

    if (!username || !coursename || !stars || !courseLevel) {
      event.preventDefault(); //  
      alert('Make sure you filled all the required fields ');
    }
  });
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('suggestions');

    
    const courses = [
        "Mathematics I", "Physics I", "Programming Basics",
        "Calculus", "Data Structures", "Digital Electronics",
        "Linear Algebra", "Web Development", "Database Systems"
    ];

       
    searchInput.addEventListener('input', function () {
        const query = this.value.trim().toLowerCase();

        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
            return;
        }

        // filtering
        const filtered = courses.filter(course => course.toLowerCase().includes(query));

        
        suggestionsBox.innerHTML = '';

        if (filtered.length === 0) {
            suggestionsBox.style.display = 'none';
            return;
        }

        //  results
        filtered.forEach(course => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.textContent = course;

           
            item.addEventListener('click', function () {
                searchInput.value = course;
                suggestionsBox.style.display = 'none';
            });

            suggestionsBox.appendChild(item);
        });

        suggestionsBox.style.display = 'block';
    });

   
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</html>