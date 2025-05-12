<?php
require '../db.php';
session_start();
include '../Header.php';


?>
<div class="container form-container"> <!-- Start of Container -->
        <div class="row justify-content-center"> <!-- Start of row -->
        <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12"> <!-- Responsive width --> <!-- Start of col -->

     <!-- Success Message -->
<div id="successMessage" class="alert alert-success d-none mb-5">
  <h1 class="mb-3 text-success">🎉 Item Added Successfully!</h1>
  <p class="text-muted">Thanks for contributing! Your item has been successfully added to the marketplace.</p>
  <a href="../Student Marketplace/MainListingPage.html" class="btn btn-outline-success mt-4">Back to Marketplace Listing Page</a>
</div>

<!-- Error Message -->
<div id="errorMessage" class="alert alert-danger d-none mb-5">
  <h1 class="mb-3 text-danger">⚠️ Something Went Wrong</h1>
  <p class="text-muted">Oops! There was a problem submitting your item. Please try again or check your form for missing details.</p>
  <a href="../Student Marketplace/ItemCreationForm.html" class="btn btn-outline-danger mt-4">Return to Form</a>
</div>


          <form id="AddItem" method="POST" action="InsertItem.php" enctype="multipart/form-data" novalidate>

            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
              <h6 class="mb-0">Students Marketplace:</h6>
              <h2 class="fw-bold mb-0">Post Your Listing</h2>
            </div>

            <!-- Title -->
            <div class="form-group">
              <label for="title" class="form-label">Title:</label>
              <input type="text" id="title" class="form-control" name="title" placeholder="Add a title" 
                     pattern="^[A-Za-z0-9 ,.?!'-]{3,100}$" 
                     title="Title should be 3-100 characters, letters/numbers and basic punctuation." required>
            </div>

            <!-- Description -->
            <div class="form-group">
              <label for="description" class="form-label">Description:</label>
              <textarea id="description" class="form-control" name="description" rows="2" 
                        placeholder="Write a description... " 
                        pattern="^[\s\S]{5,1000}$" 
                        title="Description should be at least 5 characters long."  required></textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
              <label for="price" class="form-label">Price:</label>
              <input type="number" id="price" class="form-control" name="price" step="0.01" min="0" 
                     placeholder="Add a price" title="Price must be a positive number." required>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
              <label for="phone" class="form-label">Phone Number:</label>
              <input type="tel" id="phone" class="form-control" name="phone" 
                     placeholder="Enter your phone number" pattern="[0-9]{8}" 
                     title="Phone number must be 8 digits only." required>
              <small class="form-text text-muted">Include only numbers, e.g., 12345678</small>
            </div>

            <!-- Category -->
<div class="form-group">
  <label for="category" class="form-label">Category:</label>
  <select id="category" name="category" class="form-control" required>
    <option value="" disabled selected hidden>Select a category</option>
    <option value="Books">Books</option>
    <option value="Electronics">Electronics</option>
    <option value="Clothing">Clothing</option>
    <option value="Furniture">Furniture</option>
    <option value="Other">Other</option>
  </select>
</div>

<!-- Custom Category (Initially Hidden) -->
<div id="customCategoryWrapper" class="form-group d-none mt-2">
  <label for="customCategory" class="form-label">Enter Custom Category:</label>
  <input type="text" id="customCategory" class="form-control" name="customCategory" placeholder="Type your category">
</div>



            <!-- Images -->
            <div class="form-group">
              <label for="images" class="form-label">Upload Images:</label>
              <input type="file" id="images" class="form-control" name="images[]" multiple accept="image/*">
            </div>

            <!-- Status -->
            <div class="form-group">
              <label for="status" class="form-label">Status:</label>
              <select id="status" class="form-control" name="status" required>
                <option value="" disabled selected hidden>Select a status</option>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
                <option value="Pending">Pending</option>
              </select>
            </div>

            <br>

            <!-- Buttons -->
            <div class="form-group d-flex">
              <button type="submit" class="btn btn-info me-2 text-white">Add Item</button>
              <button type="reset" class="btn btn-secondary me-2 cancel-btn">Cancel</button>
              <button type="button" class="btn btn-dark back-btn" onclick="history.back()">Back</button>
            </div>
          </form>

      </div> <!-- End of col -->
      </div> <!-- End of row -->
    </div> <!-- End of Container -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
      const categorySelect = document.getElementById("category");
      const customCategoryWrapper = document.getElementById("customCategoryWrapper");
      const customCategoryInput = document.getElementById("customCategory");

      categorySelect.addEventListener("change", function () {
        if (categorySelect.value === "Other") {
          customCategoryWrapper.classList.remove("d-none");
          customCategoryInput.setAttribute("required", "required");
        } else {
          customCategoryWrapper.classList.add("d-none");
          customCategoryInput.removeAttribute("required");
          customCategoryInput.value = "";
        }
      });
    });

</script>

<?php include '../Footer.php'; ?>