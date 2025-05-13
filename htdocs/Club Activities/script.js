const API_URL = "api.php";
const activityList = document.getElementById("activityList");
const loading = document.getElementById("loading");
const error = document.getElementById("error");
const searchInput = document.getElementById("searchInput");
const clubFilter = document.getElementById("clubFilter");
const sortOption = document.getElementById("sortOption");
const prevPage = document.getElementById("prevPage");
const nextPage = document.getElementById("nextPage");
const updateModal = document.getElementById("updateModal");
const updateForm = document.getElementById("updateForm");

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
            <button onclick="deleteClub(${club.id})">Delete</button>
            <button onclick='openUpdateModal(${JSON.stringify(club)})'>Update</button>
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

async function deleteClub(id) {
    if (!confirm("Are you sure you want to delete this club?")) return;

    try {
        const res = await fetch("deleteClub.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        });

        const result = await res.json();
        if (res.ok && result.success) {
            alert("Club deleted.");
            fetchActivities();
        } else {
            alert("Error deleting: " + result.error);
        }
    } catch (err) {
        alert("Network error.");
    }
}

function openUpdateModal(club) {
    updateModal.style.display = "block";
    updateForm.id.value = club.id;
    updateForm.name.value = club.name;
    updateForm.category.value = club.category;
    updateForm.description.value = club.description;
    updateForm.leader.value = club.leader;
}

async function updateClub() {
    const clubData = {
        id: updateForm.id.value,
        name: updateForm.name.value,
        category: updateForm.category.value,
        description: updateForm.description.value,
        leader: updateForm.leader.value
    };

    try {
        const res = await fetch("updateClub.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(clubData)
        });

        const result = await res.json();
        if (res.ok && result.success) {
            alert("Club updated successfully.");
            updateModal.style.display = "none";
            fetchActivities();
        } else {
            alert("Error updating: " + result.error);
        }
    } catch (err) {
        alert("Network error.");
    }
}

document.getElementById("closeModal").onclick = () => {
    updateModal.style.display = "none";
};

window.onclick = function (event) {
    if (event.target === updateModal) {
        updateModal.style.display = "none";
    }
};

fetchActivities();

