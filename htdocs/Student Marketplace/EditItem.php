<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<?php 
session_start();
require '../db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/EI.css">
 
    <title>SM: Edit Item</title>
</head>
<body>
 
<div id="successMessage" class="alert alert-success mt-3 d-none" role="alert">
  Changes saved successfully!
</div>
<div id="errorMessage" class="alert alert-danger mt-3 d-none" role="alert">
  Please correct the errors before saving.
</div>

    <form id="EditItem" class="container my-4 p-4 border rounded bg-light shadow" action="#">
        <h2 class="mb-4">Item Details</h2>
        <span id="itemId">ID: 123456</span>
         <div id="itemDate & itemService" class="note-meta">April 12, 2025 • Marketplace</div>
        <!-- rating -->
        <div class="rating">
            <a href="ReviewsTable.html">
            87 rating 4.5 out of 5 <br>
            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>
            </a>
        </div>
      
    <!-- Image Preview -->
<div class="mb-4 text-center">
  <img src="../Images/56063.jpg" class="img-thumbnail" alt="Item Image" id="itemImage" title="Item Image" style="max-width: 200px;">
</div>

<!-- Title -->
<div class="mb-3">
  <label for="itemTitle" class="form-label">Item Title</label>
  <input type="text" class="form-control" id="itemTitle" placeholder="Item Title" value="Item Title"
         pattern="^[A-Za-z0-9 ,.?!'-]{3,100}$"
         title="Title should be 3–100 characters and can include letters, numbers, and basic punctuation.">
</div>

<!-- Price -->
<div class="mb-3">
  <label for="itemPrice" class="form-label">Price (BD)</label>
  <input type="text" class="form-control" id="itemPrice" placeholder="itemPrice" value="49.99"
         pattern="^\d+(\.\d{1,2})?$"
         title="Enter a valid price (e.g., 10 or 49.99)">
</div>

<!-- Category -->
<div class="form-group">
  <label for="category" class="form-label">Category:</label>
  <select id="itemCategory" name="itemCategory" class="form-control" required>
    <option value="" disabled selected hidden> Select a category</option>
    <!-- Dynamic category options will be inserted here -->
  </select>
</div>

<!-- Custom Category (Initially Hidden) -->
<div id="customCategoryWrapper" class="form-group d-none mt-2">
  <label for="customCategory" class="form-label">Enter Custom Category:</label>
  <input type="text" id="customCategory" class="form-control" name="customCategory"  pattern="^[A-Za-z\s]{3,50}$"
         title="Category should be 3–50 letters only." placeholder="Type your category">
</div>


<!-- Status Field -->
<div class="mb-3">
  <label for="itemStatus" class="form-label">Status</label>
  <select id="itemStatus" class="form-select" required>
    <option value="">Select status</option>
    <option value="Available">Available</option>
    <option value="Sold">Sold</option>
    <option value="Pending">Pending</option>
  </select>
</div>

<!-- Phone Number -->
<div class="form-group">
  <label for="phone" class="form-label">Phone Number:</label>
  <input type="tel" id="itemPhone" class="form-control" name="phone" placeholder="Enter your phone number"
         value="Seller phone number .." pattern="^[0-9]{8}$"
         title="Phone number must be exactly 8 digits." required>
  <small class="form-text text-muted">Include only numbers, e.g., 12345678</small>
</div>

<!-- Description -->
<div class="mb-3">
  <label for="description" class="form-label">Detailed Description</label>
  <textarea class="form-control" id="itemDescription" placeholder="Enter detailed description..." rows="4"
            pattern="^[\s\S]{10,1000}$"
            title="Description should be between 10 and 1000 characters.">Detailed description...</textarea>
</div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 flex-wrap">
          <button type="submit" class="btn btn-success" id="saveBtn" title="save"><i class="fa-solid fa-floppy-disk"></i> Save</button>
          <a href="../Student Marketplace/DeleteItem.html"><button type="button" class="btn btn-danger" title="Delete"><i class="fa-solid fa-trash"></i> Delete</button></a>
          <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo" title="Comments">
            <i class="fa-solid fa-comments"></i> Comments
          </button>
          <a href="../Student Marketplace/Messaging.html" class="btn btn-info text-white" title="Contact Seller"><i class="fa-solid fa-users"></i> Contact</a>
          <a href="MainListingPage.html" class="btn btn-dark" title="Back to Listing"><i class="fa-solid fa-list"></i> Back to Listing</a>
        </div>
        <!-- Comments display part -->
        <!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start" id="demo">
    <div class="offcanvas-header">
      <h1 class="offcanvas-title"><i class="fa-solid fa-comment"></i> Comments</h1>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <p>Display the comments from the dataset</p>
      <button class="comment-btn" data-bs-toggle="modal" data-bs-target="#cartModal">Add your comment</button>
    </div>
  </div>
  

<!-- End of comments part-->
</form>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary"> <i class="fa-solid fa-comment"></i> Add your comment.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body fs-5">
            <div class="comments-section">
                <textarea placeholder="Add a comment..."></textarea> <br>
                <button class="comment-btn mt-2 btn btn-info text-white">Submit Comment</button>
              </div>
        </div>
      </div>
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
 <script src="../Student Marketplace/EditItem.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
      
</body>
</html>