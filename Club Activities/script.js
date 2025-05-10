const BASE_URL = "https://7evvax.replit.app";
const API_PATH = "/api.php?path=clubs";
const API_URL = `${BASE_URL}${API_PATH}`;

let clubsData = [];
let currentPage = 1;
const itemsPerPage = 6;

// Fetch data from the API
async function fetchClubs() {
    try {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error("Failed to fetch data");
        clubsData = await res.json();
        applyFilters(); 
    } catch (err) {
        console.error("Error fetching clubs:", err);
        document.getElementById("error").style.display = "block";
    }
}

function renderClubs(data) {
    const activityList = document.getElementById("activityList");
    activityList.innerHTML = "";

    if (data.length === 0) {
        activityList.innerHTML = "<p>No activities found.</p>";
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginated = data.slice(start, end);

    paginated.forEach(club => {
        const div = document.createElement("div");
        div.className = "group";
        div.innerHTML = `
            <h2>${club.name}</h2>
            <p><strong>Category:</strong> ${club.category}</p>
            <p><strong>Description:</strong> ${club.description}</p>
            <p><strong>Leader:</strong> ${club.leader}</p>
            <button onclick="alert('More info about ${club.name}')">View Details</button>
        `;
        activityList.appendChild(div);
    });
}

// Filter + Sort
function applyFilters() {
    const searchValue = document.getElementById("searchInput").value.toLowerCase();
    const clubFilter = document.getElementById("clubFilter").value;
    const sortValue = document.getElementById("sortOption").value;

    let filtered = clubsData.filter(club => {
        const matchSearch = club.name.toLowerCase().includes(searchValue);
        const matchClub = clubFilter ? club.name === clubFilter : true;
        return matchSearch && matchClub;
    });

    if (sortValue === "club") {
        filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortValue === "date" && filtered[0]?.date) {
        filtered.sort((a, b) => new Date(b.date) - new Date(a.date));
    }

    renderClubs(filtered);
    renderPagination(filtered);
}

// Pagination buttons
function renderPagination(data) {
    const totalPages = Math.ceil(data.length / itemsPerPage);
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");

    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;

    prevBtn.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            applyFilters();
        }
    };

    nextBtn.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            applyFilters();
        }
    };
}

// Event listeners
document.getElementById("searchInput").addEventListener("input", () => {
    currentPage = 1;
    applyFilters();
});
document.getElementById("clubFilter").addEventListener("change", () => {
    currentPage = 1;
    applyFilters();
});
document.getElementById("sortOption").addEventListener("change", () => {
    currentPage = 1;
    applyFilters();
});

fetchClubs();
