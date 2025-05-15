document.addEventListener("DOMContentLoaded", function () {
  const API_URL = "/api/Student%20Marketplace/";
  const form = document.getElementById("EditItem");
  const fields = ["itemTitle", "itemDescription", "itemPrice", "itemPhone", "itemCategory", "customCategory", "itemStatus"];

  const categorySelect = document.getElementById("itemCategory");
  const customCategoryWrapper = document.getElementById("customCategoryWrapper");
  const customCategoryInput = document.getElementById("customCategory");

  const successMessage = document.getElementById("successMessage");
  const errorMessage = document.getElementById("errorMessage");
  const loadingEl = document.getElementById("loading");
  const toggleButton = document.getElementById("saveBtn");

  let isEditing = false;

  // Enable or disable form inputs
  function setFieldsDisabled(disabled) {
    fields.forEach(id => {
      const input = document.getElementById(id);
      if (input) input.disabled = disabled;
    });
  }

  function clearValidationStates() {
    fields.forEach(id => {
      const input = document.getElementById(id);
      if (input) {
        input.classList.remove("is-valid", "is-invalid");
        const feedback = input.parentNode.querySelector(".invalid-feedback");
        if (feedback) feedback.remove();
      }
    });
  }

  categorySelect.addEventListener("change", function () {
    if (this.value === "Other") {
      customCategoryWrapper.classList.remove("d-none");
      customCategoryInput.required = true;
    } else {
      customCategoryWrapper.classList.add("d-none");
      customCategoryInput.required = false;
      customCategoryInput.value = "";
    }
  });

  function validateInput(input) {
    const value = input.value.trim();
    let valid = true;

    switch (input.id) {
   // Validate itemTitle
case "itemTitle":
  valid = /^[A-Za-z0-9 ,.?!'-]{3,100}$/.test(value);
  break;

// Validate itemDescription
case "itemDescription":
  valid = value.length >= 5;
  break;

// Validate itemPrice
case "itemPrice":
  valid = value !== "" && parseFloat(value) >= 0;
  break;

// Validate itemPhone
case "itemPhone":
  valid = /^\d{8}$/.test(value);
  break;

// Validate itemCategory (must not be empty)
case "itemCategory":
case "itemStatus":
  valid = value !== "";
  break;

// Validate customCategory (only required if "Other" is selected)
case "customCategory":
  if (categorySelect.value === "Other") {
    valid = value.length >= 3 && value.length <= 50;
  }
  break;

    }

    input.classList.remove("is-valid", "is-invalid");
    const feedback = input.parentNode.querySelector(".invalid-feedback");
    if (feedback) feedback.remove();

    if (!valid) {
      input.classList.add("is-invalid");
      const error = document.createElement("div");
      error.className = "invalid-feedback";
      error.textContent = "Please enter a valid value.";
      input.parentNode.appendChild(error);
    } else {
      input.classList.add("is-valid");
    }

    return valid;
  }

  toggleButton.addEventListener("click", async function (e) {
    e.preventDefault();

    if (!isEditing) {
      isEditing = true;
      setFieldsDisabled(false);
      clearValidationStates();

      categorySelect.classList.remove("d-none");
      customCategoryWrapper.classList.add("d-none");

      toggleButton.innerHTML = `<i class="bi bi-save"></i> Save`;
      toggleButton.classList.remove("btn-warning");
      toggleButton.classList.add("btn-success");
      return;
    }

    let isValid = true;
    fields.forEach(id => {
      const input = document.getElementById(id);
      if (input && !validateInput(input)) isValid = false;
    });

    if (!isValid) {
      errorMessage.textContent = "Please correct the errors in the form.";
      errorMessage.classList.remove("d-none");
      return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const itemId = urlParams.get("id");
    if (!itemId) {
      errorMessage.textContent = "Missing item ID.";
      errorMessage.classList.remove("d-none");
      return;
    }

    const categoryValue = categorySelect.value === "Other"
      ? customCategoryInput.value.trim()
      : categorySelect.value;

    const updatedData = {
      title: document.getElementById("itemTitle").value.trim(),
      description: document.getElementById("itemDescription").value.trim(),
      price: parseFloat(document.getElementById("itemPrice").value),
      phoneNumber: document.getElementById("itemPhone").value.trim(),
      status: document.getElementById("itemStatus").value,
      image: "",
      category: categoryValue,
    };

    try {
      const res = await fetch(`${API_URL}Update.php?id=${itemId}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(updatedData),
      });

      if (!res.ok) throw new Error("Failed to update item.");

      successMessage.textContent = "Item updated successfully!";
      successMessage.classList.remove("d-none");
      setTimeout(() => successMessage.classList.add("d-none"), 4000);

      isEditing = false;
      setFieldsDisabled(true);
      clearValidationStates();

      // Show appropriate category input
      if (updatedData.category === "Other") {
        categorySelect.classList.add("d-none");
        customCategoryWrapper.classList.remove("d-none");
        customCategoryInput.value = updatedData.category;
        categorySelect.value = updatedData.category;
        customCategoryInput.disabled = true;
      } else {
        categorySelect.classList.remove("d-none");
        customCategoryWrapper.classList.add("d-none");
        categorySelect.value = updatedData.category;
        customCategoryInput.value = updatedData.category;
        categorySelect.disabled = true;
      }

      toggleButton.innerHTML = `<i class="bi bi-pencil-square"></i> Edit`;
      toggleButton.classList.remove("btn-success");
      toggleButton.classList.add("btn-warning");
    } catch (err) {
      errorMessage.textContent = err.message;
      errorMessage.classList.remove("d-none");
      setTimeout(() => errorMessage.classList.add("d-none"), 5000);
    }
  });

  async function loadCategories() {
    try {
      const res = await fetch(`${API_URL}CategoryList.php?id=${itemId}`);
      if (!res.ok) throw new Error("Failed to fetch listings.");
      const listings = await res.json();

      const uniqueCategories = new Set();
      listings.forEach(item => {
        if (item.category && item.category !== "Other") {
          uniqueCategories.add(item.category);
        }
      });

      categorySelect.innerHTML = '<option value="" disabled selected hidden> </option>';

      uniqueCategories.forEach(cat => {
        const option = document.createElement("option");
        option.value = cat;
        option.textContent = cat;
        categorySelect.appendChild(option);
      });

      const otherOption = document.createElement("option");
      otherOption.value = "Other";
      otherOption.textContent = "Other";
      categorySelect.appendChild(otherOption);
    } catch (err) {
      console.error("Error loading categories:", err);
    }
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
      const res = await fetch(`${API_URL}Read.php?id=${itemId}`);
      if (!res.ok) throw new Error("Failed to load item.");
      const item = await res.json();

      // Now that item is loaded, check if the category exists in select options
      const categoryOptions = Array.from(categorySelect.options).map(opt => opt.value);
      if (!categoryOptions.includes(item.category)) {
        // Add missing category option dynamically
        const newOption = document.createElement("option");
        newOption.value = item.category;
        newOption.textContent = item.category;
        categorySelect.appendChild(newOption);
      }

      // Set form fields values
      document.getElementById("itemTitle").value = item.title;
      document.getElementById("itemDescription").value = item.description;
      document.getElementById("itemPrice").value = item.price;
      document.getElementById("itemPhone").value = item.phoneNumber;
      document.getElementById("itemStatus").value = item.status;

      // Set category value
      if (item.category === "Other") {
        categorySelect.classList.add("d-none");
        customCategoryWrapper.classList.remove("d-none");
        customCategoryInput.value = item.category;
        customCategoryInput.disabled = true;
      } else {
        categorySelect.classList.remove("d-none");
        customCategoryWrapper.classList.add("d-none");
        categorySelect.value = item.category;
        categorySelect.disabled = true;
      }

      setFieldsDisabled(true);

      toggleButton.innerHTML = `<i class="bi bi-pencil-square"></i> Edit`;
      toggleButton.classList.add("btn-warning");

    } catch (err) {
      document.getElementById("itemDetails").innerHTML = `<p>Error: ${err.message}</p>`;
    } finally {
      showLoading(false);
    }
  }


  function showLoading(state) {
    if (loadingEl) loadingEl.style.display = state ? "block" : "none";
  }

  // Init
  loadCategories();
  fetchItemDetails();
});
