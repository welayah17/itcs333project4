document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("clubForm");
    const message = document.getElementById("message");

    if (!form || !message) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        // Gather form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Log submitted data
        console.log("Submitted data:", data);

        // Validate form data
        if (!data.name || !data.category || !data.description || !data.leader) {
            message.textContent = "Please fill in all fields.";
            message.style.color = "red";
            return;
        }

        try {
            const response = await fetch("/Club Activities/insertClub.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            console.log("Server response:", result);

            if (response.ok && result.success) {
                message.textContent = "Club added successfully!";
                message.style.color = "green";
                form.reset();
                setTimeout(() => window.location.href = "page1.html", 1500);
            } else {
                message.textContent = "Error: " + (result.error || "Something went wrong");
                message.style.color = "red";
            }
        } catch (err) {
            console.error("Submission error:", err);
            message.textContent = "Network error, please try again later.";
            message.style.color = "red";
        }
    });
});
