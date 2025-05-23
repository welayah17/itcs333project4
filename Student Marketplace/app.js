//20198132 FATEMA EBRAHIM ALI SALMAN
const API_URL = "https://6816bea826a599ae7c3885bb.mockapi.io/listings";
const loadingEl = document.getElementById("loading");
const studentListEl = document.getElementById("studentList");
const paginationEl = document.getElementById("pagination");
const searchInput = document.getElementById("searchInput");
const sortSelect = document.getElementById("sortSelect");
const studentForm = document.getElementById("studentForm");
const formErrors = document.getElementById("formErrors");
const categoryDropdown = document.getElementById("categoryDropdown");


let listings = [];
let currentPage = 1;
const itemsPerPage = 6;
let selectedCategory = null;
function goToDetails(id) {
  window.location.href = `ItemDetailView.html?id=${id}`;
}
async function handleCardClick(id) {
  await incrementPopularity(id);
  goToDetails(id);
}


// Fetch the listings data from the API
async function fetchListings() {
  showLoading(true);
  try {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error("Failed to load listings.");
    listings = await res.json();

       // Read category and page from the URL
       const urlParams = new URLSearchParams(window.location.search);
       selectedCategory = urlParams.get('category') || null;
       currentPage = parseInt(urlParams.get('page')) || 1;

    renderListings();
    renderPagination();
    renderCategoryFilter(); 
  } catch (err) {
    studentListEl.innerHTML = `<p>Error: ${err.message}</p>`;
  } finally {
    showLoading(false);
  }
}

// Increment the popularity count of an item when clicked
async function incrementPopularity(id) {
  console.log("Current listings:", listings);
  console.log("Trying to increment popularity for ID:", id);

  if (!Array.isArray(listings) || listings.length === 0) {
    console.error("Listings array is empty or not loaded yet.");
    return;
  }

  const listing = listings.find(item => item.id === String(id));
  if (!listing) {
    console.error("Listing not found.");
    return;
  }

  const newPopularity = (listing.popularity || 0) + 1;

  try {
    const response = await fetch(`${API_URL}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ popularity: newPopularity }),
    });

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    console.log("Popularity updated");
  } catch (error) {
    console.error("Error during update:", error);
  }
}









// Render the listings to the page
function renderListings() {
    const term = searchInput.value.toLowerCase();
    const sort = sortSelect.value;
  
    // Step 1: Filter listing
    let filteredListings = listings.filter(listing => {
      const matchesSearch = 
        (listing.title && listing.title.toLowerCase().includes(term)) ||
        (listing.description && listing.description.toLowerCase().includes(term));
      const matchesCategory = !selectedCategory || listing.category === selectedCategory;
      return matchesSearch && matchesCategory;
    });
  
    // Step 2: Sort listings
    if (sort === "p.asc") {
        filteredListings.sort((a, b) => a.price - b.price);
      } else if (sort === "p.desc") {
        filteredListings.sort((a, b) => b.price - a.price);
      } else if (sort === "t.asc") {
        filteredListings.sort((a, b) => a.title.localeCompare(b.title));
      } else if (sort === "t.desc") {
        filteredListings.sort((a, b) => b.title.localeCompare(a.title));
      } else if (sort === "d.new") {
        filteredListings.sort((a, b) => new Date(b.publishDate) - new Date(a.publishDate));
      } else if (sort === "d.old") {
        filteredListings.sort((a, b) => new Date(a.publishDate) - new Date(b.publishDate));
      } else if (sort === "popularity") {
        filteredListings.sort((a, b) => b.popularity - a.popularity);
      }
      
      
  
    // Step 3: Pagination
    const start = (currentPage - 1) * itemsPerPage;
    const paginated = filteredListings.slice(start, start + itemsPerPage);
  
    // Step 4: Render listings
    studentListEl.innerHTML = paginated.map(listing => `
      <div class="col">
        <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column" onclick="handleCardClick(${listing.id})">
          <img src="${listing.image}" class="note-img img-fluid mb-3" alt="${listing.title}">
          <div class="note-meta">${new Date(listing.publishDate).toLocaleDateString()} • ${listing.category}</div>
          <div class="note-title">${listing.title}</div>
          <div class="note-price">BD ${parseFloat(listing.price).toFixed(2)}</div>
          <div class="note-body mb-3">
            ${listing.description?.substring(0, 100)|| 'No description provided.'}
          </div>
          <div class="mt-auto d-flex flex-column flex-md-row gap-2">
            <a href="ItemDetailView.html?id=${listing.id}" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
            <a href="MessagesTable.html" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
          </div>
        </div>
      </div>
    `).join("");
  
    // Step 5: Update pagination based on filtered/sorted list
    renderPagination(filteredListings.length);
  }
  
// Render the category filter dropdown
function renderCategoryFilter() {
  const categories = [...new Set(listings.map(listing => listing.category))];

  categoryDropdown.innerHTML = `
    <li><a class="dropdown-item" href="#" onclick="filterByCategory(null)">All Categories</a></li>
    ${categories.map(category => `
      <li><a class="dropdown-item" href="#" onclick="filterByCategory('${category}')">${category}</a></li>
    `).join('')}
  `;
}



// Filter listings by category
function filterByCategory(category) {
  const term = searchInput.value.toLowerCase();
  const sort = sortSelect.value;

  let filteredListings = listings.filter(listing =>
    listing.category === category &&
    ((listing.title && listing.title.toLowerCase().includes(term)) ||
    (listing.description && listing.description.toLowerCase().includes(term)))
  );

  // Render the filtered listings
  studentListEl.innerHTML = filteredListings.map(listing => `
    <div class="col">
      <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column" onclick="handleCardClick(${listing.id})">
        <img src="${listing.image}" class="note-img img-fluid mb-3" alt="${listing.title}">
        <div class="note-meta">${new Date(listing.publishDate).toLocaleDateString()} • ${listing.category}</div>
        <div class="note-title">${listing.title}</div>
        <div class="note-price">BD ${parseFloat(listing.price).toFixed(2)}</div>
        <div class="note-body mb-3">
          ${listing.description?.substring(0, 100)|| 'No description provided.'}
        </div>
        <div class="mt-auto d-flex flex-column flex-md-row gap-2">
          <a href="ItemDetailView.html?id=${listing.id}" class="note-button w-100 w-md-auto flex-fill">📄 View Details</a>
          <a href="MessagesTable.html" class="note-button note-button-secondary w-100">✉️ Contact Seller</a>
        </div>
      </div>
    </div>
  `).join("");
  
   // Set selected category
   selectedCategory = category;

   // Set current page to 1 when category changes
   currentPage = 1;
 
   // Update the URL with the category and page number
   const url = new URL(window.location);
   url.searchParams.set('category', category);
   url.searchParams.set('page', currentPage);
   window.history.pushState({}, '', url);
 
   renderListings();
}

// Render pagination buttons
function renderPagination(filteredCount) {
 const totalAllPages = Math.ceil(listings.length / itemsPerPage); 
 const totalFilteredPages = Math.ceil(filteredCount / itemsPerPage);
   // Set total pages depending on whether we're showing all listings or filtered listings
   const totalPages = filteredCount ? totalFilteredPages : totalAllPages;
  paginationEl.innerHTML = "";
  
    // Previous button
    const prevBtn = document.createElement("li");
    prevBtn.className = `page-item ${currentPage === 1 ? "disabled" : ""}`;
    const prevLink = document.createElement("a");
    prevLink.className = "page-link";
    prevLink.href = "#";
    prevLink.innerHTML = `<i class="fa-solid fa-backward"></i>`;
    prevLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage > 1) {
        currentPage--;
        updatePageInURL();
        renderListings();       // refresh items
        renderPagination(filteredCount);     // refresh pagination UI
      }
    });
    prevBtn.appendChild(prevLink);
    paginationEl.appendChild(prevBtn);
  
    // Page number buttons
    for (let i = 1; i <= totalPages; i++) {

      const li = document.createElement("li");
      li.className = `page-item ${currentPage === i ? "active" : ""}`;
      const a = document.createElement("a");
      a.className = "page-link";
      a.href = "#";
      a.innerText = i;
      a.addEventListener("click", (e) => {
        e.preventDefault();
        currentPage = i;
        updatePageInURL();
        renderListings();
        renderPagination(filteredCount);
      });
      li.appendChild(a);
      paginationEl.appendChild(li);
    }
  
    // Next button
    const nextBtn = document.createElement("li");
    nextBtn.className = `page-item ${currentPage === totalPages ? "disabled" : ""}`;
    const nextLink = document.createElement("a");
    nextLink.className = "page-link";
    nextLink.href = "#";
    nextLink.innerHTML = `<i class="fa-solid fa-forward"></i>`;
    nextLink.addEventListener("click", (e) => {
      e.preventDefault();
      if (currentPage < totalPages) {
        currentPage++;
        updatePageInURL();
        renderListings();
        renderPagination(filteredCount);
      }
    });
    nextBtn.appendChild(nextLink);
    paginationEl.appendChild(nextBtn);
  }
  
  // Update the page number in the URL
function updatePageInURL() {
  const url = new URL(window.location);
  url.searchParams.set('page', currentPage);
  window.history.pushState({}, '', url);
}

// Show loading indicator
function showLoading(state) {
  loadingEl.style.display = state ? "block" : "none";
}

// Show detailed view of a listing
function showDetail(id) {
  const listing = listings.find(l => l.id === id);
  alert(`
    Title: ${listing.title} - ${listing.category}
    Price: $${listing.price}
    Description: ${listing.description}
    Contact: ${listing.phoneNumber}
  `);
}

// Search and Sort functionality
const searchHint = document.getElementById("searchHint");

searchInput.addEventListener("input", () => {
  const term = searchInput.value.trim();
  currentPage = 1;
  renderListings();
  searchHint.textContent = term ? `Searching for "${term}"` : "";
});


sortSelect.addEventListener("change", () => {
  currentPage = 1;
  renderListings();
});

// Initialize
fetchListings();
