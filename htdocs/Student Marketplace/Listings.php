<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SM: Main Listing Page</title>
  <?php include '../Header.php'; ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<div class="container">
  <h1 class="display-4 text-center mb-4 mt-4">Welcome to Students Marketplace</h1>

  <header class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
    <!-- Search and Filter -->
    <form id="filterForm" class="d-flex gap-5 align-items-center mb-4">
      <div class="input-group search-bar-responsive">
        <span class="input-group-text"><i class="search-icon fas fa-search"></i></span>
        <input type="text" class="form-control" name="search" id="searchInput" placeholder="Search for items...">

        <span class="input-group-text">
          <div class="dropdown">
            <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown">
              <i class="filter-icon fas fa-filter" id="filterIcon"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="categoryMenu">
              <li><a class="dropdown-item" data-value="">All Categories</a></li>
              <!-- JS will populate categories -->
            </ul>
            <input type="hidden" name="category" id="selectedCategoryInput">
          </div>
        </span>
      </div>

      <div class="sorting-controls">
        <select class="form-select w-auto" name="sort" id="sortSelect">
          <option value="">Sort by</option>
          <option value="d.new">Date: Newest</option>
          <option value="d.old">Date: Oldest</option>
          <option value="t.asc">Title: A-Z</option>
          <option value="t.desc">Title: Z-A</option>
          <option value="p.asc">Price: Low to High</option>
          <option value="p.desc">Price: High to Low</option>
          <option value="popularity">Most Popular</option>
        </select>
      </div>

      <a href="ItemCreationForm.php" class="btn btn-info text-white">Add New Item</a>
    </form>
  </header>

  <div id="loading" class="text-center my-4" style="display: none;">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="studentList"></div>

  <nav class="pagination-controls mt-4">
    <ul class="pagination justify-content-center" id="pagination"></ul>
  </nav>
</div>

<script>
  function fetchCategories() {
    $.getJSON("../api/Student Marketplace/listings.php", function(data) {
      const categories = [...new Set(data.data.map(item => item.category))];
      categories.forEach(cat => {
        $("#categoryMenu").append(`<li><a class='dropdown-item' data-value='${cat}'>${cat}</a></li>`);
      });
    });
  }

  function fetchListings(page = 1) {
    const params = $("#filterForm").serialize() + `&page=${page}`;
    $("#loading").show();
    $("#studentList").hide();

    $.getJSON("../api/Student Marketplace/listings.php?" + params, function(response) {
      const listings = response.data;
      const pagination = response.pagination;
      $("#studentList").empty();
      $("#pagination").empty();

      listings.forEach(listing => {
        $("#studentList").append(`
          <div class="col">
            <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column" style="cursor:pointer;" onclick="window.location.href='ItemDetailsView.php?id=${listing.id}'">
              <img src="${listing.image}" class="note-img img-fluid mb-3" alt="${listing.title}">
              <div class="note-meta">${new Date(listing.publish_date).toLocaleDateString()} • ${listing.category}</div>
              <div class="note-title">${listing.title}</div>
              <div class="note-price">BD ${listing.price.toFixed(2)}</div>
              <div class="note-body mb-3">${listing.description.substring(0, 100)}...</div>
              <div class="mt-auto d-flex flex-column flex-md-row gap-2">
                <a href="ItemDetailsView.php?id=${listing.id}" class="note-button w-100">📄 View Details</a>
                <a href="Messages.php?id=${listing.id}" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
              </div>
            </div>
          </div>`);
      });

      for (let i = 1; i <= pagination.total_pages; i++) {
        $("#pagination").append(`
          <li class="page-item ${pagination.current_page === i ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
          </li>`);
      }

      $("#loading").hide();
      $("#studentList").show();
    });
  }

  $(document).ready(function () {
    fetchCategories();
    fetchListings();

    $("#filterForm").on("change", "#sortSelect", function () {
      fetchListings();
    });

    $("#filterForm").on("input", "#searchInput", function () {
      clearTimeout($.data(this, 'timer'));
      const wait = setTimeout(() => fetchListings(), 500);
      $(this).data('timer', wait);
    });

    $("#categoryMenu").on("click", "a.dropdown-item", function () {
      $("#selectedCategoryInput").val($(this).data("value"));
      fetchListings();
    });

    $("#pagination").on("click", "a.page-link", function (e) {
      e.preventDefault();
      const page = $(this).data("page");
      if (page) fetchListings(page);
    });
  });
</script>
</body>
</html>