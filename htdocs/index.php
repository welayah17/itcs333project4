<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php 
session_start();
require 'db.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home Page</title>
    <style>
        html { scroll-behavior: smooth; }

        a, h1{
            text-decoration: none;
            color: #6a11cb;
        }
        .card {
            box-shadow:5px 5px 10px #2575fc;
        }
     .carousel-caption p {
        color: black;
     }
    .carousel-img {
      width: 100%;
      height: 600px;
      object-fit: fill;
    }

    @media (max-width: 768px) {
      .carousel-img {
        height: 250px;
      }
    }
    body {
  background: linear-gradient(135deg, #f0f0ff, #ffffff);
  margin: 0;
  padding: 0;
  color: #333;
}
    main h2 {

  color: #5b0dcb;
  margin-bottom: 40px;
}
.card {
  background-color: white;
  border-radius: 15px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: left;
}
.card:hover {
    transform: translateY(-4px);
  }

.card h5 {
    font-family: 'Georgia', serif;
    font-weight: 600;
  margin-top: 0;
  color: #5b0dcb;
  display: block;
}

.card-btn button {
  margin-top: 10px;
  padding: 8px 16px;
  background-color: #7c3aed;
  color: white;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-family: 'Georgia', serif;
  transition: background-color 0.2s ease;
}
.collapse-content a {
    display: block;
    padding: 0.25rem 0;
    color: #0d6efd;
    text-decoration: none;
  }

  .collapse-content a:hover {
    text-decoration: none;
  }

  .explore-btn {
    background-color: #f8f2e4;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
    font-family: 'Georgia', serif;
    padding: 0.4rem 1rem;
    width: 100%;
    margin-top: 1rem;
    transition: background-color 0.2s ease;
  }

  .collapse-content {
    padding-top: 1rem;
    border-top: 1px dashed #ccc;
    margin-top: 1rem;
    font-size: 0.95rem;
  }

    </style>
</head>
<body>
 <?php include 'Header.php'; ?>
  <div id="campusHubCarousel" class="carousel slide container mt-4" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#campusHubCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#campusHubCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#campusHubCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#campusHubCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner rounded shadow-sm">
      <div class="carousel-item active">
        <img src="../Images/3902762.jpg" class="d-block carousel-img" alt="Campus Life">
        <div class="carousel-caption d-none d-md-block">
          <h1 class="fw-bold">Explore Campus Life</h1>
          <p>Stay up-to-date with our Events Calendar and never miss a moment.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../Images/4163433.jpg" class="d-block carousel-img" alt="Study Group Finder">
        <div class="carousel-caption d-none d-md-block">
          <h1 class="fw-bold">Study Smarter Together</h1>
          <p>Connect with peers through our Study Group Finder and boost your grades.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../Images/2452376.jpg" class="d-block carousel-img" alt="Course Reviews">
        <div class="carousel-caption d-none d-md-block">
          <h1 class="fw-bold">Learn from Experience</h1>
          <p>Read and share real student insights through Course Reviews and Notes.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../Images/56063.jpg" class="d-block carousel-img" alt="Student Marketplace">
        <div class="carousel-caption d-none d-md-block">
          <h1 class="fw-bold">Buy & Sell on Campus</h1>
          <p>Browse the Student Marketplace for textbooks, tech, and more.</p>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#campusHubCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#campusHubCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>



<div class="container my-4">
<main class="d-flex flex-column align-items-center px-3 px-md-5">
    <div class="row justify-content-center align-items-center my-4 flex-wrap w-100">
        <div class="col-lg-6 col-md-12 mb-4 text-center text-md-start">
          <div class="jumbotron jumbotron-fluid bg-transparent text-dark">
            <div class="container">
              <h1 class="display-5 fw-bold">Welcome to Campus Hub</h1>
              <p class="lead">
                Your one-stop platform for everything happening on campus! 
                Stay in the loop with our <strong>Events Calendar</strong>, find peers using our <strong>Study Group Finder</strong>, read and share <strong>Course Reviews & Notes</strong>, catch the latest <strong>Campus News</strong>, join <strong>Club Activities</strong>, and explore the <strong>Student Marketplace</strong>.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 text-center">
          <img src="../Images/freepik__isometric-university-illustration-with-students-st__37349.jpeg" class="img-fluid rounded shadow" alt="Campus Hub Services" style="width:80%;">
        </div>
      </div>



      <div class="row justify-content-center text-center mb-4 w-100">
        <div class="col-lg-8">
            <section  id="About">
          <h2 class="fw-bold">About Campus Hub</h2>
          <p class="text-muted mt-3">
            Campus Hub is your all-in-one platform for staying connected, informed, and involved on campus.
            Whether you're looking for upcoming events, academic resources, student clubs, or a vibrant marketplace—
            we bring it all together in one place.
          </p>
        </section>
        </div>
      </div>
    </main>     

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="Services"> <!-- Start of Card row -->
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5>Events Calendar</h5>
                    <p>Stay updated with campus events and activities.</p>
                    <a class="card-btn" data-bs-toggle="collapse" href="#collapseOne">
                    <button>Explore</button>
                    </a>
                    <div id="collapseOne" class="collapse collapse-content show" data-bs-parent="#Services">
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5>Study Group Finder</h5>
                    <p>Find and join study groups on your campus.</p>
                    <a class="card-btn" data-bs-toggle="collapse" href="#collapseTwo"><button>Explore</button></a>
                    <div id="collapseTwo" class="collapse collapse-content" data-bs-parent="#Services">

                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5>Course Reviews</h5>
                    <p>Read and share reviews on campus courses.</p>
                    <a class="card-btn" data-bs-toggle="collapse" href="#collapseThree"><button>Explore</button></a>
                    <div id="collapseThree" class="collapse collapse-content" data-bs-parent="#Services">

                    </div>
               </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5>Course Notes</h5>
                    <p>Access and contribute course notes and materials.</p>
                    <a class="card-btn" data-bs-toggle="collapse" href="#collapseFour"><button>Explore</button></a>
                    <div id="collapseFour" class="collapse collapse-content" data-bs-parent="#Services">

                    </div>
               </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                    <h5>Campus News</h5>
                    <p>Stay informed with the latest campus news and updates.</p>
                    <a class="card-btn" data-bs-toggle="collapse" href="#collapseFive"><button>Explore</button></a>
                    <div id="collapseFive" class="collapse collapse-content" data-bs-parent="#Services">

                    </div>
               </div> 
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                   <h5>Club Activities</h5>
                   <p>Discover and join student clubs and activities.</p>
                   <a class="card-btn" data-bs-toggle="collapse" href="#collapseSix"><button>Explore</button></a>
                   <div id="collapseSix" class="collapse collapse-content" data-bs-parent="#Services">
                    <a href="Club Activities/page1.php"> 📌 Club Activities </a>
                    <a href="Club Activities/page2.php"> ➕ Add new activity</a>
                   </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card w-100 h-100">
                <div class="card-body">
                   <h5>Student Marketplace</h5>
                   <p>Buy, sell, and exchange items with fellow students.</p>
                   <a class="card-btn" data-bs-toggle="collapse" href="#collapseSeven"><button>Explore</button></a>
                   <div id="collapseSeven" class="collapse collapse-content" data-bs-parent="#Services">
                    <a href="Student Marketplace/MainListingPage.php"> 🛒 Student Marketplace Listing</a>
                    <a href="Student Marketplace/ItemCreationForm.php"> ➕ Add Item</a>
                    <a href="Student Marketplace/EditItem.php"> ✏️ Edit Item </a>
                   </div>
            </div>
            </div>
        </div>

    </div> <!-- End of Card row -->
    <hr class="hr mt-5">
    <h1 class="display-4 text-center mb-4 mt-4"> Services </h1> <br>
    <header class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
        <!-- Search and Filter -->
        <div class="input-group search-bar-responsive">
          <span class="input-group-text"><i class="search-icon fas fa-search"></i></span>
          <input type="text" class="form-control" placeholder="Search for items...">
          <span class="input-group-text">
            <div class="dropdown">
              <button type="button" data-bs-toggle="dropdown" class="btn p-0 border-0 bg-transparent">
                <i class="filter-icon fas fa-filter"></i>
              </button>
              <ul class="dropdown-menu">
                <li class="dropdown-item">Events Calendar</li>
                <li class="dropdown-item">Study Group Finder</li>
                <li class="dropdown-item">Course Reviews</li>
                <li class="dropdown-item">Course Notes</li>
                <li class="dropdown-item">Campus News</li>
                <li class="dropdown-item">Club Activities</li>
                <li class="dropdown-item">Student Marketplace</li>
              </ul>
            </div>
          </span>

        </div>

        <!-- Sort Dropdown -->
        <div class="sorting-controls">
        <select class="form-select w-auto">
          <option selected>Sort by Price</option>
          <option value="date">Sort by Date</option>
          <option value="popularity">Sort by Popularity</option>
        </select>
        </div>

        <!-- Add New Item -->
            <div class="dropdown btn btn-info">
                <button type="button" data-bs-toggle="dropdown" class="btn text-white p-0 border-0 bg-transparent">
                    Add New Item
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="Events Calendar/EventsCalendar.html">Add New Event</a></li>
                    <li><a class="dropdown-item" href="Study Group Finder/add-group.html">Add New Study Group</a></li>
                    <li><a class="dropdown-item" href="Course Reviews/CourseReviews.html">Add New Course Reviews</a></li>
                    <li><a class="dropdown-item" href="Course Notes/Cours-notes.html">Add New Course Note</a></li>
                    <li><a class="dropdown-item" href="Campus News/phase1/add-news.html">Add New Campus News</a></li>
                    <li><a class="dropdown-item" href="Club Activities/page2.php">Add New Club Activities</a></li>
                    <li><a class="dropdown-item" href="Student Marketplace/ItemCreationForm.html">Add New Item in Student Marketplace</a></li>
                </ul>
              </div>

      </header>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
              <img src="../Images/3902762.jpg" class="note-img img-fluid mb-3" alt="Textbook">
              <div class="note-meta">April 12, 2025 • Marketplace</div>
              <div class="note-title">Used Biology Textbook (BIO101)</div>
              <div class="note-price">$25.00</div>
              <div class="note-body mb-3">
                Lightly used. No markings inside. Great for first-year students taking intro to biology. Pick up on campus or request delivery.
              </div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="#" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
                <a href="Student Marketplace/Messaging.html" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>

            </div>
          </div>
          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
              <img src="../Images/2452376.jpg" class="note-img img-fluid mb-3" alt="Library photo">
              <div class="note-meta">April 12, 2025 • Campus News</div>
              <div class="note-title">New Study Spaces Open in Library</div>
              <div class="note-price">$45.00</div>
              <div class="note-body mb-3">
                The university has unveiled newly renovated study areas on the second floor of the library, aimed at encouraging collaborative learning...
              </div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="#" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
                <a href="#" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>

            </div>
          </div>

          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
              <img src="../Images/4163433.jpg" class="note-img img-fluid mb-3" alt="Earth Week">
              <div class="note-meta">April 11, 2025 • Events</div>
              <div class="note-title">Earth Week Festival This Weekend</div>
              <div class="note-price">$45.00</div>
              <div class="note-body  mb-3">
                Join student clubs and faculty for a weekend of eco-friendly fun, food, and workshops. Activities include plant swaps, talks, and art booths...
              </div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="#" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
                <a href="#" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
              <img src="../Images/freepik__isometric-university-illustration-with-students-st__37349.jpeg" class="note-img img-fluid mb-3" alt="Earth Week">
              <div class="note-meta">April 10, 2025 • Course Reviews</div>
              <div class="note-title">Top-Rated Spring Courses by Students</div>
              <div class="note-price">$45.00</div>
              <div class="note-body  mb-3">
                Curious what classes your peers love most this semester? Here's a roundup of the top-rated electives and required courses by department...
              </div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="#" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
                <a href="#" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
              <img src="../Images/56063.jpg" class="note-img img-fluid mb-3" alt="Calculator">
              <div class="note-meta">April 11, 2025 • Marketplace</div>
              <div class="note-title">TI-84 Plus Graphing Calculator</div>
              <div class="note-price">$45.00</div>
              <div class="note-body mb-3">
                Barely used and in perfect condition. Essential for most STEM classes. Battery included.
              </div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="#" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
                <a href="Student Marketplace/Messaging.html" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>

            </div>
          </div>
        </div>
      <!-- Pagination -->
      <nav class="pagination-controls mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item"><a class="page-link" href="#"><i class="pagination fa-solid fa-backward"></i></a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item"><a class="page-link" href="#">5</a></li>
          <li class="page-item"><a class="page-link" href="#"><i class="pagination fa-solid fa-forward"></i></a></li>
        </ul>
      </nav>
    </div>

  </div> <!-- End of Container -->

<?php include 'Footer.php'; ?>
</body>
</html>

