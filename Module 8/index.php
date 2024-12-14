<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steelers Chatbot</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Popup Overlay and Welcome Popup -->
    <div id="popup-overlay"></div>
    <div id="welcome-popup">
        <p><strong>Here we go Steelers, here we go!</strong></p>
        <button id="popup-close">Close</button>
    </div>

    <!-- Chatbot UI -->
    <div class="chat-container">
        <h1>Steelers History Chatbot</h1>
        <div id="chat-window">
            <!-- Placeholder for chat log -->
            <div id="chat-log"></div>
        </div>
        <input type="text" id="user-input" placeholder="Ask me about Steelers history...">
        <button id="send-button">Send</button>
    </div>

    <script>
        // Show the popup when the page loads
        window.addEventListener('load', () => {
            const popupOverlay = document.getElementById('popup-overlay');
            const welcomePopup = document.getElementById('welcome-popup');
            const popupClose = document.getElementById('popup-close');

            // Show popup and overlay
            popupOverlay.style.display = 'block';
            welcomePopup.style.display = 'block';

            // Close popup when the close button is clicked
            popupClose.addEventListener('click', () => {
                popupOverlay.style.display = 'none';
                welcomePopup.style.display = 'none';
            });
        });

        // Chatbot functionality
        const sendButton = document.getElementById('send-button');
        const userInput = document.getElementById('user-input');
        const chatLog = document.getElementById('chat-log');

        // Function to add messages to the chat log
        function addChatMessage(sender, message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chat-message';
            messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
            chatLog.appendChild(messageElement);
            chatLog.scrollTop = chatLog.scrollHeight;
        }

        // Event listener for the "Send" button
        sendButton.addEventListener('click', () => {
            processUserInput();
        });

        // Event listener for the "Enter" key
        userInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                processUserInput();
            }
        });

        // Function to process user input
        function processUserInput() {
            const query = userInput.value.trim();
            if (query) {
                addChatMessage('You', query);
                fetch('chatbot.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `query=${encodeURIComponent(query)}`,
                })
                .then(response => response.json())
                .then(data => {
                    // Always display the response received from chatbot.php
                    addChatMessage('Chatbot', data.response);
                })
                .catch(err => {
                    console.error('Error:', err);
                    addChatMessage('Chatbot', 'An error occurred while processing your request.');
                });
                userInput.value = ''; // Clear input field
            }
        }
    </script>
</body>
</html>

