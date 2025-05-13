const API_URL = "api.php";
const activityList = document.getElementById("activityList");
const loading = document.getElementById("loading");
const error = document.getElementById("error");
const searchInput = document.getElementById("searchInput");
const clubFilter = document.getElementById("clubFilter");
const sortOption = document.getElementById("sortOption");
const prevPage = document.getElementById("prevPage");
const nextPage = document.getElementById("nextPage");

let activities = [];
let currentPage = 1;
const itemsPerPage = 3;

async function fetchActivities() {
    loading.style.display = "block";
    error.style.display = "none";
    try {
        const res = await fetch(API_URL);
        if (!res.ok) throw new Error("Failed to fetch");
        activities = await res.json();
        renderActivities();
        populateClubFilter();
    } catch (err) {
        console.error(err);
        error.style.display = "block";
    } finally {
        loading.style.display = "none";
    }
}

function renderActivities() {
    const filtered = activities.filter(activity => {
        const matchSearch = activity.name.toLowerCase().includes(searchInput.value.toLowerCase());
        const matchClub = !clubFilter.value || activity.name === clubFilter.value;
        return matchSearch && matchClub;
    });

    if (sortOption.value === "name") {
        filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortOption.value === "category") {
        filtered.sort((a, b) => a.category.localeCompare(b.category));
    }

    const start = (currentPage - 1) * itemsPerPage;
    const paginated = filtered.slice(start, start + itemsPerPage);

    activityList.innerHTML = "";
    if (paginated.length === 0) {
        activityList.innerHTML = "<p>No activities found.</p>";
        return;
    }

    paginated.forEach(club => {
        const article = document.createElement("article");
        article.className = "group";
        article.innerHTML = `
            <h2>${club.name}</h2>
            <p><strong>Category:</strong> ${club.category}</p>
            <p><strong>Leader:</strong> ${club.leader}</p>
            <p>${club.description}</p>
        `;
        activityList.appendChild(article);
    });
}

function populateClubFilter() {
    const clubNames = [...new Set(activities.map(c => c.name))];
    clubFilter.innerHTML = `<option value="">All Clubs</option>` + 
        clubNames.map(name => `<option value="${name}">${name}</option>`).join('');
}

searchInput.addEventListener("input", () => {
    currentPage = 1;
    renderActivities();
});
clubFilter.addEventListener("change", () => {
    currentPage = 1;
    renderActivities();
});
sortOption.addEventListener("change", renderActivities);
prevPage.addEventListener("click", () => {
    if (currentPage > 1) {
        currentPage--;
        renderActivities();
    }
});
nextPage.addEventListener("click", () => {
    currentPage++;
    renderActivities();
});

fetchActivities();
