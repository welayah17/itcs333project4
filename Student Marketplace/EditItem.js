  document.addEventListener("DOMContentLoaded", function () {
    const API_URL = "https://6816bea826a599ae7c3885bb.mockapi.io/listings"; 
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

    // Show error message
    function showError(input, message) {
      clearFeedback(input);
      input.classList.add("is-invalid");
      const error = document.createElement("div");
      error.className = "invalid-feedback";
      error.textContent = message;
      input.parentNode.appendChild(error);
    }

    // Show valid state
    function showValid(input) {
      clearFeedback(input);
      input.classList.add("is-valid");
    }

    // Clear feedback messages
    function clearFeedback(input) {
      input.classList.remove("is-invalid", "is-valid");
      const existingError = input.parentNode.querySelector(".invalid-feedback");
      if (existingError) existingError.remove();
    }

    // Validate individual input
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
        default:
          break;
      }

      showValid(input);
      return true;
    }

    // Add validation listeners to fields
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

    // Handle Cancel Button
    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        fields.forEach(id => {
          const input = document.getElementById(id);
          if (input) {
            clearFeedback(input);
            input.value = "";
          }
        });

        // Reset custom category visibility
        customCategoryWrapper.classList.add("d-none");
        customCategoryInput.removeAttribute("required");
        customCategoryInput.value = "";

        form.reset(); // Reset select fields, file inputs, etc.
      });
    }

    // Handle Submit
    if (form) {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        let isFormValid = true;

        successMessage.classList.add("d-none");
        errorMessage.classList.add("d-none");

        fields.forEach(id => {
          const input = document.getElementById(id);
          if (input) {
            const valid = validateInput(input);
            if (!valid) isFormValid = false;
          }
        });

        if (isFormValid) {
          successMessage.classList.remove("d-none");

          setTimeout(() => {
            successMessage.classList.add("d-none");
          }, 5000);

          fields.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
              clearFeedback(input);
              input.value = "";
            }
          });

          // Also hide custom category after success
          customCategoryWrapper.classList.add("d-none");
          customCategoryInput.removeAttribute("required");

          form.reset();
        } else {
          errorMessage.classList.remove("d-none");

          setTimeout(() => {
            errorMessage.classList.add("d-none");
          }, 5000);
        }
      });
    }

    // Fetch and display item details
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
        const res = await fetch(`${API_URL}/${itemId}`);
        if (!res.ok) throw new Error("Failed to load item details.");
        const item = await res.json();

        // Populate the page with the item data
        const itemDateServiceEl = document.getElementById("itemDate & itemService");
        const itemTitleEl = document.getElementById("itemTitle");
        const itemIdEl = document.getElementById("itemId");
        const itemPriceEl = document.getElementById("itemPrice");
        const itemDescriptionEl = document.getElementById("itemDescription");
        const itemCategoryEl = document.getElementById("itemCategory");
        const itemStatusEl = document.getElementById("itemStatus");
        const itemPhoneEl = document.getElementById("itemPhone");
        const itemImageEl = document.getElementById("itemImage");

        if (itemDateServiceEl) itemDateServiceEl.textContent = `${item.publishDate} • Marketplace`;
        if (itemTitleEl) itemTitleEl.value = item.title;
        if (itemIdEl) itemIdEl.textContent = `ID: ${item.id}`;
        if (itemPriceEl) itemPriceEl.value = item.price;
        if (itemDescriptionEl) itemDescriptionEl.textContent = item.description || "No description provided.";
        if (itemCategoryEl) itemCategoryEl.value = item.category || "Not specified";
        if (itemStatusEl) itemStatusEl.value = item.status || "Not specified";
        if (itemPhoneEl) itemPhoneEl.value = item.phoneNumber || "Not provided";
        if (itemImageEl) itemImageEl.src = item.image || "../Images/placeholder.jpg"; // Default placeholder image

        // Set dynamic Edit button link
        const editButton = document.querySelector('a[href^="../Student Marketplace/EditItem.html"]');
        if (editButton) {
          editButton.href = `../Student Marketplace/EditItem.html?id=${item.id}`;
        }
      } catch (err) {
        if (itemDetailsEl) itemDetailsEl.innerHTML = `<p>Error: ${err.message}</p>`;
      } finally {
        showLoading(false);
      }
    }

    // Show or hide the loading indicator
    function showLoading(state) {
      if (loadingEl) {
        loadingEl.style.display = state ? "block" : "none";
      }
    }

    // Initialize the page
    fetchItemDetails();
  });

