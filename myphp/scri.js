function loadMessages() {
    let chatBox = document.getElementById("chatBox");

    fetch("messages.php")
        .then(response => response.text())
        .then(data => {
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll vers le bas
        })
        .catch(error => console.error("Erreur lors du chargement des messages :", error));
}
function sendMessage() {
    let chatBox = document.getElementById("chatBox");
    let messageInput = document.getElementById("messageInput");
    let userSelect = document.getElementById("userSelect");

    if (messageInput.value.trim() === "") return; // Prevent empty messages

    let messageText = messageInput.value;
    let _groupId = userSelect.value; // Get the selected group ID
    let groupId = document.querySelector('input[name="group_id"]').value; 
    // Disable button to prevent multiple submissions
    let sendButton = document.querySelector("button");
    sendButton.disabled = true;

    // Prepare form data
    let formData = new FormData();
    formData.append("group_id", groupId);
    formData.append("message", messageText);

    fetch("messages.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Expect a JSON response from PHP
    .then(data => {
        if (data.success) {
            loadMessages(); // Reload messages
        } else {
            console.error("Erreur:", data.error);
        }
    })
    .catch(error => console.error("Error sending message:", error))
    .finally(() => {
        sendButton.disabled = false; // Re-enable button
    });
    let message = document.createElement("div");
    message.classList.add("message", "sent");
    message.innerHTML = `<strong>Vous</strong>: ${messageText} <div class="message-time">${new Date().toLocaleTimeString()}</div>`;
    chatBox.appendChild(message);

    messageInput.value = ""; // Clear input
    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
}
document.getElementById("sendBtn").addEventListener("click", function (event) {
    event.preventDefault(); // Empêche le rechargement de la page
    sendMessage();
});

document.getElementById("userSelect").addEventListener("change", function() {
    this.form.submit();
});

