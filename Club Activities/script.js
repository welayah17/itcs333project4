const API_URL = "https://7evvax.replit.app/api.php?path=clubs";

let clubsData = [];
let currentPage = 1;
const itemsPerPage = 6;

// Fetch data from PHP API
async function fetchClubs() {
    try {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error("Failed to fetch data");
        clubsData = await res.json();
        renderClubs();
        renderPagination();
    } catch (err) {
        console.error("Error loading data:", err);
        document.querySelector(".group-list").innerHTML = "<p>Error loading club data.</p>";
    }
}


function renderClubs(data = clubsData) {
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const currentItems = data.slice(start, end);

    const groupList = document.querySelector(".group-list");
    groupList.innerHTML = "";

    if (currentItems.length === 0) {
        groupList.innerHTML = "<p>No clubs found.</p>";
        return;
    }

    currentItems.forEach(club => {
        const div = document.createElement("div");
        div.className = "group";
        div.innerHTML = `
            <h2>${club.name}</h2>
            <p><strong>Category:</strong> ${club.category}</p>
            <p><strong>Description:</strong> ${club.description}</p>
            <p><strong>Leader:</strong> ${club.leader}</p>
            <button onclick="alert('More info about ${club.name}')">View Details</button>
        `;
        groupList.appendChild(div);
    });
}


function applyFilters() {
    const searchValue = document.getElementById("search").value.toLowerCase();
    const categoryValue = document.getElementById("category").value;

    const filtered = clubsData.filter(club => {
        const matchSearch = club.name.toLowerCase().includes(searchValue);
        const matchCategory = categoryValue ? club.category === categoryValue : true;
        return matchSearch && matchCategory;
    });

    currentPage = 1;
    renderClubs(filtered);
    renderPagination(filtered);
}


function renderPagination(data = clubsData) {
    const totalPages = Math.ceil(data.length / itemsPerPage);
    const pagContainer = document.querySelector(".pag");
    pagContainer.innerHTML = "";

    const prevBtn = document.createElement("button");
    prevBtn.textContent = "Prev";
    prevBtn.disabled = currentPage === 1;
    prevBtn.onclick = () => {
        currentPage--;
        renderClubs(data);
        renderPagination(data);
    };

    const nextBtn = document.createElement("button");
    nextBtn.textContent = "Next";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.onclick = () => {
        currentPage++;
        renderClubs(data);
        renderPagination(data);
    };

    pagContainer.appendChild(prevBtn);
    pagContainer.appendChild(document.createTextNode(` Page ${currentPage} of ${totalPages} `));
    pagContainer.appendChild(nextBtn);
}

// Event listeners
document.getElementById("search").addEventListener("input", applyFilters);
document.getElementById("category").addEventListener("change", applyFilters);

fetchClubs();

