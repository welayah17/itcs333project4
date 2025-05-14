//20198132 FATEMA EBRAHIM ALI SALMAN
const API_URL = "/api/Student%20Marketplace/"; 
const itemDetailsEl = document.getElementById("itemDetails");
const loadingEl = document.getElementById("loading");

async function fetchItemDetails() {
  // Get the ID from the URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const itemId = urlParams.get("id");

  if (!itemId) {
    alert("No item ID provided.");
    return;
  }

  showLoading(true);

  try {
    const res = await fetch(`${API_URL}Read.php?id=${itemId}`);

    if (!res.ok) throw new Error("Failed to load item details.");
    const item = await res.json();

    // Populate the page with the item data
    document.getElementById("itemDate & itemService").textContent = `${item.publishDate} • Marketplace`;
    document.getElementById("itemTitle").textContent = item.title;
    document.getElementById("itemId").textContent = `ID: ${item.id}`;
    document.getElementById("itemPrice").textContent = item.price;
    document.getElementById("itemDescription").textContent = item.description || "No description provided.";
    document.getElementById("itemCategory").textContent = `Category: ${item.category || "Not specified"}`;
    document.getElementById("itemStatus").textContent = `Status: ${item.status || "Not specified"}`;
    document.getElementById("itemPhone").textContent = `Phone Number: ${item.phoneNumber || "Not provided"}`;
    document.getElementById("itemImage").src = item.image || "../Images/placeholder.jpg"; // Default placeholder image

 // ✅ Set dynamic Edit button link
 const editButton = document.querySelector('a[href^="../Student Marketplace/EditItem.html"]');
 if (editButton) {
   editButton.href = `../Student Marketplace/EditItem.html?id=${item.id}`;
 }

} catch (err) {
 itemDetailsEl.innerHTML = `<p>Error: ${err.message}</p>`;
} finally {
 showLoading(false);
}
}


// Show the loading indicator
function showLoading(state) {
  loadingEl.style.display = state ? "block" : "none";
}

// Initialize the page
fetchItemDetails();
