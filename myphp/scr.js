document.addEventListener("DOMContentLoaded", function () {
    // Handle Role Switching
    document.querySelectorAll('.toggle-role').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default anchor behavior

            let userId = this.getAttribute('data-id');
            let newRole = this.getAttribute('data-role');

            if (confirm("Voulez-vous vraiment changer le rôle de cet utilisateur en " + newRole + " ?")) {
                fetch('admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + userId + '&role=' + newRole
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display response from server
                    location.reload(); // Reload the page to update UI
                })
                .catch(error => console.error('Erreur:', error));
            }
        });
    });

    // Handle User Deletion
    document.querySelectorAll('.delete-btn').forEach(link => {
        link.addEventListener('click', function (event) {
            let userId = this.getAttribute('href').split('=')[1]; // Extract user ID from href
            if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) {
                event.preventDefault(); // Stop deletion if canceled
            }
        });
    });

    // Handle Group Deletion
    document.getElementById("delete-group-btn").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission

        let groupId = new URLSearchParams(window.location.search).get("group_Id");
        if (!groupId) {
            alert("Sélectionnez un groupe d'abord !");
            return;
        }

        if (confirm("Voulez-vous vraiment supprimer ce groupe ? Cette action est irréversible.")) {
            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'group_id=' + groupId
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Display response from server
                location.reload(); // Reload to reflect changes
            })
            .catch(error => console.error('Erreur:', error));
        }
    });
});


document.querySelectorAll('delete-group-btn').forEach(button => {
    button.addEventListener('click', function() {
        const groupItem = this.closest('li'); // Find the closest list item
        if (confirm('Voulez-vous vraiment supprimer ce groupe ?')) {
            groupItem.remove(); // Remove the group from the list
            console.log('Groupe supprimé:', groupItem.dataset.id);
        }
    });
});
