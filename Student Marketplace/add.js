document.getElementById("AddItem").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form from submitting

    const form = event.target;
    let valid = true;

    // Clear previous errors
    const errorMessages = form.querySelectorAll(".text-danger");
    errorMessages.forEach((msg) => msg.remove());

    // Helper: Show error below a field
    function showError(input, message) {
      const error = document.createElement("div");
      error.className = "text-danger mt-1";
      error.textContent = message;
      input.insertAdjacentElement("afterend", error);
      valid = false;
    }

    // Validate Title
    const title = form.title;
    const titlePattern = /^[A-Za-z0-9 ,.?!'-]{3,100}$/;
    if (!titlePattern.test(title.value.trim())) {
      showError(title, "Title should be 3-100 characters and contain only letters, numbers, and basic punctuation.");
    }

    // Validate Description
    const description = form.description;
    if (description.value.trim().length < 5) {
      showError(description, "Description should be at least 5 characters long.");
    }

    // Validate Price
    const price = form.price;
    if (price.value === "" || parseFloat(price.value) < 0) {
      showError(price, "Price must be a positive number.");
    }

    // Validate Phone
    const phone = form.phone;
    const phonePattern = /^[0-9]{8}$/;
    if (!phonePattern.test(phone.value)) {
      showError(phone, "Phone number must be exactly 8 digits.");
    }

    // Validate Category
    const category = form.category;
    if (category.value === "") {
      showError(category, "Please select a category.");
    }

    // Validate Status
    const status = form.status;
    if (status.value === "") {
      showError(status, "Please select a status.");
    }

    // If all valid, you can show a success message or enable real submission
    if (valid) {
      alert("Item validated successfully. You can now submit the form.");
      // form.submit(); // Uncomment to allow submission
    }
  });
  