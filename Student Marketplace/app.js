const API_URL = "https://6816bea826a599ae7c3885bb.mockapi.io/listings";
const loadingEl = document.getElementById("loading");
const studentListEl = document.getElementById("studentList");
const paginationEl = document.getElementById("pagination");
const searchInput = document.getElementById("searchInput");
const sortSelect = document.getElementById("sortSelect");
const studentForm = document.getElementById("studentForm");
const formErrors = document.getElementById("formErrors");

let listings = [];
let currentPage = 1;
const itemsPerPage = 6;

// Fetch the listings data from the API
async function fetchListings() {
  showLoading(true);
  try {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error("Failed to load listings.");
    listings = await res.json();
    renderListings();
    renderPagination();
  } catch (err) {
    studentListEl.innerHTML = `<p>Error: ${err.message}</p>`;
  } finally {
    showLoading(false);
  }
}

// Render the listings to the page
function renderListings() {
    const term = searchInput.value.toLowerCase();
    const sort = sortSelect.value;
  
    // Step 1: Filter listings
    let filteredListings = listings.filter(listing =>
      (listing.title && listing.title.toLowerCase().includes(term)) ||
      (listing.description && listing.description.toLowerCase().includes(term))
    );
  
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
      }
      
      
  
    // Step 3: Pagination
    const start = (currentPage - 1) * itemsPerPage;
    const paginated = filteredListings.slice(start, start + itemsPerPage);
  
    // Step 4: Render listings
    studentListEl.innerHTML = paginated.map(listing => `
      <div class="col">
        <div class="note-card p-3 p-sm-4 h-100 d-flex flex-column">
          <img src="${listing.image}" class="note-img img-fluid mb-3" alt="${listing.title}">
          <div class="note-meta">${new Date(listing.publishDate).toLocaleDateString()} • ${listing.service || 'Marketplace'}</div>
          <div class="note-title">${listing.title}</div>
          <div class="note-price">BD ${parseFloat(listing.price).toFixed(2)}</div>
          <div class="note-body mb-3">
            ${listing.description?.substring(0, 100) || 'No description provided.'}
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
  

// Render pagination buttons
function renderPagination() {
    const totalPages = Math.ceil(listings.length / itemsPerPage);
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
        renderListings();       // refresh items
        renderPagination();     // refresh pagination UI
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
        renderListings();
        renderPagination();
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
        renderListings();
        renderPagination();
      }
    });
    nextBtn.appendChild(nextLink);
    paginationEl.appendChild(nextBtn);
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
searchInput.addEventListener("input", () => {
  currentPage = 1;
  renderListings();
});

sortSelect.addEventListener("change", () => {
  currentPage = 1;
  renderListings();
});

// Form Validation
studentForm.addEventListener("submit", e => {
  e.preventDefault();
  formErrors.innerHTML = "";

  const title = document.getElementById("title").value.trim();
  const phoneNumber = document.getElementById("phoneNumber").value.trim();
  const description = document.getElementById("description").value.trim();
  const price = parseFloat(document.getElementById("price").value.trim());

  const errors = [];
  if (title.length < 3) errors.push("Title must be at least 3 characters.");
  if (!/^\d{1}-\d{3}-\d{3}-\d{4}$/.test(phoneNumber)) errors.push("Invalid phone number format.");
  if (!description) errors.push("Description is required.");
  if (isNaN(price) || price < 1) errors.push("Price must be a positive number.");

  if (errors.length) {
    formErrors.innerHTML = errors.map(e => `<p>${e}</p>`).join("");
  } else {
    alert("Form validated. Listing ready to post.");
  }
});

// Initialize
fetchListings();
