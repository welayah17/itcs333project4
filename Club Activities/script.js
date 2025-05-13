document.addEventListener("DOMContentLoaded", () => {
    const list = document.getElementById("activityList");
    if (!list) return;

    fetch("getClub.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to load clubs");
            }
            return response.json();
        })
        .then(data => {
            list.innerHTML = ""; 

            if (!Array.isArray(data) || data.length === 0) {
                list.innerHTML = "<p>No clubs found.</p>";
                return;
            }

            data.forEach(club => {
                const div = document.createElement("div");
                div.className = "group";
                div.innerHTML = `
                    <h2>${club.name}</h2>
                    <p><strong>Category:</strong> ${club.category}</p>
                    <p><strong>Description:</strong> ${club.description}</p>
                    <p><strong>Leader:</strong> ${club.leader}</p>
                `;
                list.appendChild(div);
            });
        })
        .catch(err => {
            console.error("Fetch error:", err);
            list.innerHTML = "<p>Error loading clubs.</p>";
        });
});
