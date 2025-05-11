 document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById('editBtn');
    const form = document.getElementById("AddItem");
    const categorySelect = document.getElementById("category");
    const customCategoryWrapper = document.getElementById("customCategoryWrapper");
    const customCategoryInput = document.getElementById("customCategory");
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

    // Field validation listeners
    fields.forEach(id => {
      const input = document.getElementById(id);
      input.addEventListener("blur", () => validateInput(input));
      input.addEventListener("input", () => {
        if (input.classList.contains("is-invalid") || input.classList.contains("is-valid")) {
          validateInput(input);
        }
      });
    });

    // Handle Cancel Button
    cancelButton.addEventListener("click", function () {
      fields.forEach(id => {
        const input = document.getElementById(id);
        clearFeedback(input);
        input.value = "";
      });

      // Reset custom category visibility
      customCategoryWrapper.classList.add("d-none");
      customCategoryInput.removeAttribute("required");
      customCategoryInput.value = "";

      form.reset(); // Reset select fields, file inputs, etc.
    });

    // Handle Submit
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      let isFormValid = true;

      document.getElementById("successMessage").classList.add("d-none");
      document.getElementById("errorMessage").classList.add("d-none");

      fields.forEach(id => {
        const input = document.getElementById(id);
        const valid = validateInput(input);
        if (!valid) isFormValid = false;
      });

      if (isFormValid) {
        document.getElementById("successMessage").classList.remove("d-none");

        setTimeout(() => {
        successMessage.classList.add("d-none");
      }, 5000);

        fields.forEach(id => {
          const input = document.getElementById(id);
          clearFeedback(input);
          input.value = "";
        });

        // Also hide custom category after success
        customCategoryWrapper.classList.add("d-none");
        customCategoryInput.removeAttribute("required");

        form.reset();
      } else {
        document.getElementById("errorMessage").classList.remove("d-none");
        
        setTimeout(() => {
        errorMessage.classList.add("d-none");
      }, 5000);
      }
      if (isEditMode) {
        document.getElementById("successMessage").classList.remove("d-none");

        setTimeout(() => {
        successMessage.classList.add("d-none");
      }, 5000);

        fields.forEach(id => {
          const input = document.getElementById(id);
          clearFeedback(input);
          input.value = "";
        });

        // Also hide custom category after success
        customCategoryWrapper.classList.add("d-none");
        customCategoryInput.removeAttribute("required");
      } else {
        document.getElementById("errorMessage").classList.remove("d-none");
        
        setTimeout(() => {
        errorMessage.classList.add("d-none");
      }, 5000);
      }
    });
  });