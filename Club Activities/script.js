const API_URL = "data.json";
let currentPage = 1;
const itemsPerPage = 3;

const activityList = document.getElementById("activityList");
const loading = document.getElementById("loading");
const error = document.getElementById("error");
const searchInput = document.getElementById("searchInput");
const clubFilter = document.getElementById("clubFilter");
const sortOption = document.getElementById("sortOption");
const prevPage = document.getElementById("prevPage");
const nextPage = document.getElementById("nextPage");

function renderActivities() {
  let filtered = activities.filter(activity => {
    const matchesSearch = activity.title.toLowerCase().includes(searchInput.value.toLowerCase());
    const matchesClub = !clubFilter.value || activity.club === clubFilter.value;
    return matchesSearch && matchesClub;
  });

  if (sortOption.value === "date") {
    filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
  } else if (sortOption.value === "club") {
    filtered.sort((a, b) => a.club.localeCompare(b.club));
  }

  const start = (currentPage - 1) * itemsPerPage;
  const paginated = filtered.slice(start, start + itemsPerPage);

  activityList.innerHTML = "";
  if (paginated.length === 0) {
    activityList.innerHTML = "<p>No activities found.</p>";
    return;
  }

  paginated.forEach(activity => {
    const article = document.createElement("article");
    article.className = "group";
    article.innerHTML = `
      <h2>${activity.title}</h2>
      <p><strong>Club:</strong> ${activity.club}</p>
      <p><strong>Date:</strong> ${activity.date}</p>
      <p>${activity.description}</p>
      <button onclick="alert('Details for: ${activity.title}')">View Details</button>
    `;
    activityList.appendChild(article);
  });
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

renderActivities();
