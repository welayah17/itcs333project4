document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("AddItem");
  const categorySelect = document.getElementById("category");
  const customCategoryWrapper = document.getElementById("customCategoryWrapper");
  const customCategoryInput = document.getElementById("customCategory");
  const cancelButton = document.querySelector(".cancel-btn");
  const successMessage = document.getElementById("successMessage");
  const errorMessage = document.getElementById("errorMessage");

  const API_URL = "/api/Student Marketplace/Create.php"; 

  const fields = ["title", "description", "price", "phone", "category", "status", "customCategory"];

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

    if (input.id === "title") {
      const regex = /^[A-Za-z0-9 ,.?!'-]{3,100}$/;
      if (!regex.test(value)) {
        showError(input, "Title must be 3–100 valid characters.");
        return false;
      }
    }

    if (input.id === "description") {
      if (value.length < 5) {
        showError(input, "Description must be at least 5 characters.");
        return false;
      }
    }

    if (input.id === "price") {
      if (value === "" || parseFloat(value) < 0) {
        showError(input, "Price must be a positive number.");
        return false;
      }
    }

    if (input.id === "phone") {
      if (!/^\d{8}$/.test(value)) {
        showError(input, "Phone number must be exactly 8 digits.");
        return false;
      }
    }

    if (input.id === "category") {
      if (!value) {
        showError(input, "Please select a category.");
        return false;
      }
    }

    if (input.id === "status") {
      if (!value) {
        showError(input, "Please select a status.");
        return false;
      }
    }

    if (input.id === "customCategory") {
      if (categorySelect.value === "Other") {
        if (value.length < 3 || value.length > 50) {
          showError(input, "Custom category must be 3–50 characters.");
          return false;
        }
      }
    }

    showValid(input);
    return true;
  }

  // Show/hide custom category input based on selection
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

  fields.forEach(id => {
    const input = document.getElementById(id);
    input.addEventListener("blur", () => validateInput(input));
    input.addEventListener("input", () => {
      if (input.classList.contains("is-invalid") || input.classList.contains("is-valid")) {
        validateInput(input);
      }
    });
  });

  cancelButton.addEventListener("click", function () {
    fields.forEach(id => {
      const input = document.getElementById(id);
      clearFeedback(input);
      input.value = "";
    });

    customCategoryWrapper.classList.add("d-none");
    customCategoryInput.removeAttribute("required");
    customCategoryInput.value = "";
    form.reset();
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    let isFormValid = true;
    successMessage.classList.add("d-none");
    errorMessage.classList.add("d-none");

    fields.forEach(id => {
      const input = document.getElementById(id);
      if (!validateInput(input)) {
        isFormValid = false;
      }
    });

    if (!isFormValid) {
      errorMessage.textContent = "Please correct the errors before saving.";
      errorMessage.classList.remove("d-none");
      setTimeout(() => errorMessage.classList.add("d-none"), 5000);
      return;
    }

    // Prepare formData from form
    const formData = new FormData(form);

    // Override category if "Other"
    if (categorySelect.value === "Other") {
      formData.set("category", customCategoryInput.value.trim());
    }

    // POST the formData to your API
    fetch(API_URL, {
      method: "POST",
      body: formData,
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        successMessage.classList.remove("d-none");

        setTimeout(() => {
          successMessage.classList.add("d-none");
        }, 5000);

        fields.forEach(id => {
          const input = document.getElementById(id);
          clearFeedback(input);
          input.value = "";
        });

        customCategoryWrapper.classList.add("d-none");
        customCategoryInput.removeAttribute("required");
        form.reset();
      } else {
        throw new Error(data.error || "API error");
      }
    })
    .catch(err => {
      console.error("Submission failed:", err);
      errorMessage.textContent = "Failed to submit data. Please try again.";
      errorMessage.classList.remove("d-none");
      setTimeout(() => errorMessage.classList.add("d-none"), 5000);
    });
  });
});