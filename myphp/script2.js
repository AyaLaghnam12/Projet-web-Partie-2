// Script for form validation and user interaction
document.addEventListener("DOMContentLoaded", function() {
    // Get the form and the group select element
    const projectForm = document.querySelector(".add-project-form");
    const groupSelect = document.getElementById("group-select");
    const projectNameInput = document.getElementById("project-name");

    // Listen for form submission
    projectForm.addEventListener("submit", function(e) {
        // Check if the project name and group are valid
        if (!projectNameInput.value.trim() || groupSelect.value === "") {
            // Prevent form submission if validation fails
            e.preventDefault();

            // Display an alert or message to the user
            alert("Please provide a valid project name and select a group.");
        } else {
            // If valid, you can show a success message or perform other actions
            alert("Project added successfully!");
        }
    });

    // Optional: You can also add dynamic behaviors to the group selection
    groupSelect.addEventListener("change", function() {
        console.log("Group selected: ", groupSelect.value);
        // You can add additional actions, such as dynamically loading project info or updating the page.
    });
});
