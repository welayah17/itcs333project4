document.addEventListener("DOMContentLoaded", function () {
const API_URL = "https://6816bea826a599ae7c3885bb.mockapi.io/listings";
const itemForm = document.getElementById("itemForm");
const loadingEl = document.getElementById("loading");
const form = document.getElementById("saveBtn");
const editBtn = document.getElementById("editBtn");
const customCategoryWrapper = document.getElementById("customCategoryWrapper");
const customCategoryInput = document.getElementById("customCategory");
const categorySelect = document.getElementById("category");
const cancelButton = document.querySelector(".cancel-btn");

    function showError(input, message) {
      clearFeedback(input);
      input.classList.add("is-invalid");
      const error = document.createElement("div");
      error.className = "invalid-feedback";
      error.textContent = message;
      input.parentNode.appendChild(error);
    }

  function showValid(input) {
      clearFeedback(input);
      input.classList.add("is-valid");
    }
    
async function fetchItemDetails() {
  const urlParams = new URLSearchParams(window.location.search);
  const itemId = urlParams.get("id");

  if (!itemId) {
    alert("No item ID provided.");
    return;
  }

  showLoading(true);

  try {
    const res = await fetch(`${API_URL}/${itemId}`);
    if (!res.ok) throw new Error("Failed to load item details.");
    const item = await res.json();

    // Fill form fields
    document.getElementById("itemId").textContent = `ID: ${item.id}`;
    document.querySelector(".note-meta").textContent = `${item.publishDate} • Marketplace`;
    document.querySelector(".rating a").innerHTML = `87 rating 4.5 out of 5 <br>
      <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
      <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>`;

    document.getElementById("itemImage").src = item.image || "../Images/placeholder.jpg";
    document.getElementById("itemTitle").value = item.title || "";
    document.getElementById("itemPrice").value = item.price || "";
    document.getElementById("itemStatus").value = item.status || "";
    document.getElementById("itemPhone").value = item.phoneNumber || "";
    document.getElementById("itemDescription").value = item.description || "";

    // Handle category select and custom
    if (["Books", "Electronics", "Clothing", "Furniture", "Other"].includes(item.category)) {
      categorySelect.value = item.category;
      customCategoryWrapper.classList.add("d-none");
    } else {
      categorySelect.value = "Other";
      customCategoryInput.value = item.category;
      customCategoryWrapper.classList.remove("d-none");
    }

    // Store ID for later
    itemForm.dataset.itemId = item.id;
  } catch (err) {
    itemForm.innerHTML = `<p class="text-danger">Error: ${err.message}</p>`;
  } finally {
    showLoading(false);
  }
}

function showLoading(state) {
  if (loadingEl) loadingEl.style.display = state ? "block" : "none";
}

categorySelect.addEventListener("change", () => {
  if (categorySelect.value === "Other") {
    customCategoryWrapper.classList.remove("d-none");
  } else {
    customCategoryWrapper.classList.add("d-none");
    customCategoryInput.value = "";
  }
});

editBtn.addEventListener("click", async () => {
  const inputs = itemForm.querySelectorAll("input, textarea, select");
  const isDisabled = inputs[0].disabled;

  if (isDisabled) {
    // Enable fields
    inputs.forEach(el => el.disabled = false);
    editBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save';
    editBtn.classList.remove('btn-warning');
    editBtn.classList.add('btn-success');
  } else {
    const itemId = itemForm.dataset.itemId;

    // Use custom category if "Other" is selected
    const finalCategory = categorySelect.value === "Other"
      ? customCategoryInput.value.trim()
      : categorySelect.value;

    const updatedItem = {
      title: document.getElementById("itemTitle").value.trim(),
      price: document.getElementById("itemPrice").value.trim(),
      category: finalCategory,
      status: document.getElementById("itemStatus").value.trim(),
      phoneNumber: document.getElementById("itemPhone").value.trim(),
      description: document.getElementById("itemDescription").value.trim(),
    };

    try {
      const res = await fetch(`${API_URL}/${itemId}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(updatedItem)
      });

      if (!res.ok) throw new Error("Failed to save changes.");
      alert("Item updated successfully.");
    } catch (error) {
      alert(`Error: ${error.message}`);
      return;
    }

    // Disable form again
    inputs.forEach(el => el.disabled = true);
    editBtn.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit';
    editBtn.classList.remove('btn-success');
    editBtn.classList.add('btn-warning');
  }
});
});


// Initialize
fetchItemDetails();
