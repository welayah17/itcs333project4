<?php 
 session_start();
require '../db.php';

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';
$category = $_GET['category'] ?? '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;

// Build WHERE clause
$where = "WHERE 1";
$params = [];

if (!empty($search)) {
    $where .= " AND (title LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search%";
}
if (!empty($category)) {
    $where .= " AND category = :category";
    $params[':category'] = $category;
}

// Sorting
$orderBy = "ORDER BY id DESC";
switch ($sort) {
    case "p.asc": $orderBy = "ORDER BY price ASC"; break;
    case "p.desc": $orderBy = "ORDER BY price DESC"; break;
    case "t.asc": $orderBy = "ORDER BY title ASC"; break;
    case "t.desc": $orderBy = "ORDER BY title DESC"; break;
    case "d.new": $orderBy = "ORDER BY publishDate DESC"; break;
    case "d.old": $orderBy = "ORDER BY publishDate ASC"; break;
    case "popularity": $orderBy = "ORDER BY popularity DESC"; break;
}

// Count total for pagination
$countStmt = $db->prepare("SELECT COUNT(*) FROM StudentMarketplaceItem $where");
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch paginated results
$sql = "SELECT * FROM StudentMarketplaceItem $where $orderBy LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include '../Header.php'; ?>
        <title>SM: Main Listing Page</title>
</head>
<body>


      <div class="container">
        <h1 class="display-4 text-center mb-4 mt-4"> Welcome to Students Marketplace </h1> <br>
        <header class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">
          <!-- Search and Filter -->
          <form method="GET" class="d-flex gap-5 align-items-center mb-4">
          <div class="input-group search-bar-responsive">
            <span  type="submit" class="input-group-text"><i class="search-icon fas fa-search"></i></span>
            <input type="text" class="form-control" name="search" id="searchInput"
             placeholder="Search for items..." value="<?= htmlspecialchars($search) ?>"
             onkeydown="if(event.key==='Enter'){ this.form.submit(); }">

             <!-- Filter Dropdown Trigger -->
              <span class="input-group-text">
                <div class="dropdown">
                  <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown">
                    <i class="filter-icon fas fa-filter <?= $category ? 'text-primary' : '' ?>"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item <?= $category == '' ? 'active' : '' ?>" href="#" data-value="">All Categories</a></li>
                    <?php
                      $catStmt = $db->query("SELECT DISTINCT category FROM StudentMarketplaceItem");
                      while ($row = $catStmt->fetch(PDO::FETCH_ASSOC)):
                        $catVal = $row['category'];
                    ?>
                      <li>
                        <a class="dropdown-item <?= $category == $catVal ? 'active' : '' ?>"
                           href="#" data-value="<?= htmlspecialchars($catVal) ?>">
                          <?= htmlspecialchars($catVal) ?>
                        </a>
                      </li>
                    <?php endwhile; ?>
                  </ul>
                  <!-- Hidden input to capture the selected category -->
                  <input type="hidden" name="category" id="selectedCategoryInput" value="<?= htmlspecialchars($category) ?>">
                </div>
              </span>
            </div>

        <!-- Sort Dropdown -->
  <div class="sorting-controls">
    <!-- Sort Dropdown -->
    <select class="form-select w-auto" name="sort" onchange="this.form.submit()">
      <option value="">Sort by</option>
      <option value="d.new" <?= $sort == 'd.new' ? 'selected' : '' ?>>Date: Newest</option>
      <option value="d.old" <?= $sort == 'd.old' ? 'selected' : '' ?>>Date: Oldest</option>
      <option value="t.asc" <?= $sort == 't.asc' ? 'selected' : '' ?>>Title: A-Z</option>
      <option value="t.desc" <?= $sort == 't.desc' ? 'selected' : '' ?>>Title: Z-A</option>
      <option value="p.asc" <?= $sort == 'p.asc' ? 'selected' : '' ?>>Price: Low to High</option>
      <option value="p.desc" <?= $sort == 'p.desc' ? 'selected' : '' ?>>Price: High to Low</option>
      <option value="popularity" <?= $sort == 'popularity' ? 'selected' : '' ?>>Most Popular</option>
    </select>
  </div>

          <!-- Add New Item -->
          <a href="ItemCreationForm.php" class="btn btn-info text-white">Add New Item</a>

          </form>
        </header>
        <br><small id="searchHint" class="text-muted"></small>
            <h2>Items</h2>

        <div id="loading" class="text-center my-4" style="display: none;">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"> Loading...</span>
          </div>
        </div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="studentList">
<?php foreach ($listings as $listing): ?>
  <div class="col">
    <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column" style="cursor: pointer;" onclick="window.location.href='ItemDetailsView.php?id=<?= $listing['id'] ?>'">
      <img src="<?= htmlspecialchars($listing['image']) ?>" class="note-img img-fluid mb-3" alt="<?= htmlspecialchars($listing['title']) ?>">
      <div class="note-meta"><?= date("F j, Y", strtotime($listing['publishDate'])) ?> • <?= htmlspecialchars($listing['category']) ?></div>
      <div class="note-title"><?= htmlspecialchars($listing['title']) ?></div>
      <div class="note-price">BD <?= number_format($listing['price'], 2) ?></div>
      <div class="note-body mb-3">
        <?= htmlspecialchars(substr($listing['description'], 0, 100)) ?>...
      </div>
      <div class="mt-auto d-flex flex-column flex-md-row gap-2">
        <a href="ItemDetailsView.php?id=<?= $listing['id'] ?>" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
        <a href="Messages.php?id=<?= $listing['id'] ?>" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>


<!-- Pagination -->
<nav class="pagination-controls mt-4">
  <ul class="pagination justify-content-center" id="pagination">

    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $page > 1 ? '?page=' . ($page - 1) . '&search=' . urlencode($search) . '&sort=' . $sort . '&category=' . $category : '#' ?>">
        <i class="fa-solid fa-backward"></i>
      </a>
    </li>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&category=<?= $category ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= $page < $totalPages ? '?page=' . ($page + 1) . '&search=' . urlencode($search) . '&sort=' . $sort . '&category=' . $category : '#' ?>">
        <i class="fa-solid fa-forward"></i>
      </a>
    </li>

  </ul>
</nav>
        </div>


        </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const searchInput = document.getElementById("searchInput");
      const loadingEl = document.getElementById("loading");
      const cardsContainer = document.getElementById("studentList");
      const searchHint = document.getElementById("searchHint");
      const paginationContainer = document.getElementById("pagination"); 
      const searchForm = searchInput.closest("form");
      let searchTimeout;

      // 🔄 Category dropdown filter behavior
      document.querySelectorAll('.dropdown-menu a.dropdown-item').forEach(item => {
        item.addEventListener('click', function (e) {
          e.preventDefault();
          const selectedValue = this.getAttribute('data-value');
          document.getElementById('selectedCategoryInput').value = selectedValue;
          searchForm.submit();
        });
      });

      // 📝 Search input feedback & auto-submit
      searchInput.addEventListener("input", () => {
        const term = searchInput.value.trim();
        searchHint.textContent = term ? `Searching for "${term}"` : "";

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          searchForm.submit();
        }, 800); 
      });

      // ⏳ Initial loading effect when page loads
      loadingEl.style.display = "block";
      cardsContainer.style.display = "none";
      paginationContainer.style.display = "none"; 

      setTimeout(() => {
        loadingEl.style.display = "none";
        cardsContainer.style.display = "flex"; // use "flex" or "grid" based on layout
        paginationContainer.style.display = "flex";
      }, 300);

      // 🧭 Show loading spinner when pagination links are clicked
      document.querySelectorAll(".pagination a.page-link").forEach(link => {

        link.addEventListener("click", function () {
          loadingEl.style.display = "block";
          cardsContainer.style.display = "none";

        });
      });
    });

  </script>

