document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("clubForm");
    const message = document.getElementById("message");
    if (!form || !message) return;

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch("insertClub.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (res.ok) {
                message.textContent = "Club added successfully!";
                form.reset();
                setTimeout(() => window.location.href = "page1.html", 1500);
            } else {
                message.textContent = "Error: " + (result.error || "Something went wrong");
            }
        } catch (err) {
            console.error("Submission error:", err);
            message.textContent = "Network error";
        }
    });
});
