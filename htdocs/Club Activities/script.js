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
const updateIdInput = document.getElementById("update-id");

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
        const groupDiv = document.createElement("div");
        groupDiv.className = "group";
        groupDiv.innerHTML = `
            <h2>${club.name}</h2>
            <p><strong>Category:</strong> ${club.category}</p>
            <p><strong>Description:</strong> ${club.description}</p>
            <p><strong>Leader:</strong> ${club.leader}</p>
            <div class="action-buttons">
                <button class="add-group" onclick="editClub('${club.id}')">Edit</button>
                <button class="add-group" onclick="deleteClub('${club.id}')">Delete</button>
            </div>
        `;
        activityList.appendChild(groupDiv);
    });
}

function populateClubFilter() {
    const clubNames = [...new Set(activities.map(c => c.name))];
    clubFilter.innerHTML = `<option value="">Filter by Club</option>` +
        clubNames.map(name => `<option value="${name}">${name}</option>`).join('');
}

// Event Listeners
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
    const maxPages = Math.ceil(activities.length / itemsPerPage);
    if (currentPage < maxPages) {
        currentPage++;
        renderActivities();
    }
});

async function deleteClub(id) {
    if (!confirm("Are you sure you want to delete this club?")) return;

    try {
        const res = await fetch("deleteClub.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
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
        console.error("Delete error:", err);
        alert("Network error.");
    }
}

// Edit Club
window.editClub = function (id) {
    const club = activities.find(c => String(c.id) === String(id));
    if (!club) {
        alert("Club not found.");
        return;
    }

    updateIdInput.value = club.id;
    updateForm.name.value = club.name;
    updateForm.category.value = club.category;
    updateForm.description.value = club.description;
    updateForm.leader.value = club.leader;

    updateModal.style.display = "block";
    window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
};

function cancelEdit() {
    updateForm.reset();
    updateModal.style.display = "none";
}

async function updateClub() {
    const id = updateIdInput.value;
    const name = updateForm.name.value.trim();
    const category = updateForm.category.value.trim();
    const description = updateForm.description.value.trim();
    const leader = updateForm.leader.value.trim();

    if (!id || !name || !category || !description || !leader) {
        alert("All fields are required!");
        return;
    }

    try {
        const res = await fetch("updateClub.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, name, category, description, leader })
        });

        const result = await res.json();

        if (res.ok && result.success) {
            alert("Club updated successfully!");
            cancelEdit();
            fetchActivities();
        } else {
            alert("Error updating: " + (result.error || "Unknown error"));
        }
    } catch (error) {
        console.error("Update error:", error);
        alert("Network error occurred.");
    }
}

// Attach cancel function
document.querySelector("#updateForm button[type='button']").addEventListener("click", cancelEdit);

// Initial fetch
fetchActivities();

