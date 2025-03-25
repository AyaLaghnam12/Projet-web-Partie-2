document.addEventListener("DOMContentLoaded", function() {
    loadMessages(); // Charger les messages au chargement de la page

    setInterval(loadMessages, 2000); // Rafraîchir toutes les 2 secondes
});



document.getElementById("invitation-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Empêche le rechargement de la page

    let groupName = document.getElementById("group-name").value.trim();


    let formData = new FormData();
    formData.append("group", groupName);

    fetch("amis.php", { // Envoie la requête à amis.php
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("group-name").value = ""; // Réinitialise le champ après création
    })
    .catch(error => {
        console.error("Erreur :", error);
    });
}); 
document.getElementById("select-group-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    let formData = new FormData(this);

    fetch("amis.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) // Or use .json() if response is JSON
    .then(data => {
        alert("Utilisateur ajouté avec succès !");
        location.reload(); // Reload to update UI
    })
    .catch(error => {
        console.error("Erreur :", error);
        alert("Erreur lors de l'ajout !");
    });
});



    