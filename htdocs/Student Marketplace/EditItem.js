document.addEventListener("DOMContentLoaded", function () {
  const API_BASE = "/api/Student Marketplace/"; // Adjust if necessary
  const form = document.getElementById("EditItem");
  const categorySelect = document.getElementById("itemCategory");
  const customCategoryWrapper = document.getElementById("customCategoryWrapper");
  const customCategoryInput = document.getElementById("customCategory");
  const cancelButton = document.querySelector(".cancel-btn");
  const saveBtn = document.getElementById("saveBtn");
  const itemDetailsEl = document.getElementById("itemDetails");
  const loadingEl = document.getElementById("loading");
  const successMessage = document.getElementById("successMessage");
  const errorMessage = document.getElementById("errorMessage");

  const fields = ["title", "description", "price", "phone", "category", "status", "customCategory"];

  const urlParams = new URLSearchParams(window.location.search);
  const itemId = urlParams.get("id");

  // Show/hide custom category input
  categorySelect.addEventListener("change", function () {
    if (categorySelect.value === "Other") {
      customCategoryWrapper.classList.remove("d-none");
      customCategoryInput.setAttribute("required", "required");
    } else {
      customCategoryWrapper.classList.add("d-none");
      customCategoryInput.removeAttribute("required");
      customCategoryInput.value = "";
    }
  });

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

  function clearFeedback(input) {
    input.classList.remove("is-invalid", "is-valid");
    const existingError = input.parentNode.querySelector(".invalid-feedback");
    if (existingError) existingError.remove();
  }

  function validateInput(input) {
    const value = input.value.trim();
    switch (input.id) {
      case "itemTitle":
        const titleRegex = /^[A-Za-z0-9 ,.?!'-]{3,100}$/;
        if (!titleRegex.test(value)) {
          showError(input, "Title must be 3–100 valid characters.");
          return false;
        }
        break;
      case "itemDescription":
        if (value.length < 5) {
          showError(input, "Description must be at least 5 characters.");
          return false;
        }
        break;
      case "itemPrice":
        if (value === "" || parseFloat(value) < 0) {
          showError(input, "Price must be a positive number.");
          return false;
        }
        break;
      case "itemPhone":
        if (!/^\d{8}$/.test(value)) {
          showError(input, "Phone number must be exactly 8 digits.");
          return false;
        }
        break;
      case "itemCategory":
        if (!value) {
          showError(input, "Please select a category.");
          return false;
        }
        break;
      case "itemStatus":
        if (!value) {
          showError(input, "Please select a status.");
          return false;
        }
        break;
      case "customCategory":
        if (categorySelect.value === "Other") {
          if (value.length < 3 || value.length > 50) {
            showError(input, "Custom category must be 3–50 characters.");
            return false;
          }
        }
        break;
    }
    showValid(input);
    return true;
  }

  fields.forEach(id => {
    const input = document.getElementById(id);
    if (input) {
      input.addEventListener("blur", () => validateInput(input));
      input.addEventListener("input", () => {
        if (input.classList.contains("is-invalid") || input.classList.contains("is-valid")) {
          validateInput(input);
        }
      });
    }
  });

  if (cancelButton) {
    cancelButton.addEventListener("click", function () {
      fields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          clearFeedback(input);
          input.value = "";
        }
      });
      customCategoryWrapper.classList.add("d-none");
      customCategoryInput.removeAttribute("required");
      form.reset();
    });
  }

  if (form) {
    form.addEventListener("submit", async function (e) {
      e.preventDefault();
      let isFormValid = true;

      successMessage.classList.add("d-none");
      errorMessage.classList.add("d-none");

      fields.forEach(id => {
        const input = document.getElementById(id);
        if (input && !validateInput(input)) {
          isFormValid = false;
        }
      });

      if (!isFormValid) {
        errorMessage.classList.remove("d-none");
        return;
      }

      // Gather form data
      const data = {
        title: document.getElementById("itemTitle").value.trim(),
        description: document.getElementById("itemDescription").value.trim(),
        price: parseFloat(document.getElementById("itemPrice").value),
        phoneNumber: document.getElementById("itemPhone").value.trim(),
        category: categorySelect.value === "Other" ? document.getElementById("customCategory").value.trim() : categorySelect.value,
        status: document.getElementById("itemStatus").value.trim()
      };

      try {
        const response = await fetch(`${API_BASE}/Update.php?id=${itemId}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.success) {
          successMessage.textContent = result.success;
          successMessage.classList.remove("d-none");
          form.reset();
          customCategoryWrapper.classList.add("d-none");
        } else {
          throw new Error(result.error || "Update failed.");
        }
      } catch (err) {
        errorMessage.textContent = err.message;
        errorMessage.classList.remove("d-none");
      }
    });
  }

  async function fetchItemDetails() {
    if (!itemId) {
      alert("No item ID provided.");
      return;
    }

    showLoading(true);
    try {
      const res = await fetch(`${API_BASE}/Read.php?id=${itemId}`);
      if (!res.ok) throw new Error("Failed to load item details.");
      const item = await res.json();

      document.getElementById("itemTitle").value = item.title;
      document.getElementById("itemPrice").value = item.price;
      document.getElementById("itemDescription").value = item.description;
      document.getElementById("itemCategory").value = item.category;
      document.getElementById("itemStatus").value = item.status;
      document.getElementById("itemPhone").value = item.phoneNumber;
    } catch (err) {
      if (itemDetailsEl) itemDetailsEl.innerHTML = `<p>Error: ${err.message}</p>`;
    } finally {
      showLoading(false);
    }
  }

  function showLoading(state) {
    if (loadingEl) {
      loadingEl.style.display = state ? "block" : "none";
    }
  }

  fetchItemDetails();
});
