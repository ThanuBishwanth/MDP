<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Homepage</title>
    <style>
        /* General Styles */
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-image: url("images/home.jpeg");
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover; /* Ensures the image fills the entire page */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
   
    color: #2d3748;
}
body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Change color and opacity */
    z-index: -1;
}


/* Navbar Styles */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 90vw;
    padding: 15px 30px;
    color: #e2e8f0;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
}

.navbar h1 {
    font-size: 28px;
    margin: 0;
    color: white;
    
}

.nav-right a {
    color: #e2e8f0;
    text-decoration: none;
    padding: 8px 16px;
    margin: 0 8px;
    border-radius: 8px;
    transition: background 0.3s ease;
    font-weight: bold;
}

.nav-right a:hover {
    background-color: #63b3ed;
    color: #1a202c;
}

/* Prediction Links */
.prediction-links {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    margin-top: 40px;
    justify-content: center;
}

/* Prediction Card Styles */
.prediction-card {
    background: linear-gradient(white,lightblue);
    border-radius: 15px;
    padding: 20px;
    width: 200px;
    height: 220px;
    box-shadow: 0px 8px 16px rgba(66, 153, 225, 0.3);
    text-align: center;
    text-decoration: none;
    color: black;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    position: relative;
}

.prediction-card::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.prediction-card:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0px 12px 24px rgba(66, 153, 225, 0.4);
}

.prediction-card:hover::before {
    opacity: 1;
}

.logo {
    font-size: 48px;
    margin-bottom: 10px;
}

.prediction-card span {
    font-size: 18px;
    font-weight: bold;
}

/* Add media query for mobile responsiveness */
@media (max-width: 600px) {
    .prediction-links {
        flex-direction: column;
        align-items: center;
    }

    .prediction-card {
        width: 90%;
    }
}
  /* Chatbot Icon */
  #chatbot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            text-align: center;
            line-height: 50px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Chatbot Container */
        #chatbot-container {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 320px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            max-height: 500px;
        }

        /* Messages Section */
        #chatbot-messages {
            padding: 15px;
            max-height: 350px;
            overflow-y: auto;
            background-color: #f9fafb;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        /* Messages Styling */
        .chat-message {
            padding: 10px;
            margin: 6px 0;
            border-radius: 8px;
            width: fit-content;
            max-width: 80%;
            font-size: 14px;
        }

        .user-question {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }

        .bot-reply {
            background-color: #e2e8f0;
            color: #333;
            align-self: flex-start;
        }

        /* Questions Section */
        #chatbot-questions {
            padding: 10px;
            border-top: 1px solid #ccc;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        /* Input Section */
        #user-input-container {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #f3f4f6;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        #user-question {
            flex: 1;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            outline: none;
        }

        #send-button {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 8px;
            transition: background-color 0.2s;
        }

        #send-button:hover {
            background-color: #0056b3;
        }
</style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="nav-left">
            
        </div>
        <div class="nav-right">
            <a href="homepage.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <!-- Prediction Links -->
    <div class="prediction-links">
        <a href="verify_patient.php?predictor=heart" class="prediction-card">
            <div class="logo">❤️</div>
            <span>Heart Disease Prediction</span>
        </a>
        <a href="verify_patient.php?predictor=diabetes" class="prediction-card">
            <div class="logo">🩸</div>
            <span>Diabetes Prediction (For Females Only)</span>
        </a>
        <a href="verify_patient.php?predictor=liver" class="prediction-card">
            <div class="logo">🦾</div>
            <span>Liver Disease Prediction</span>
        </a>
    </div>
     <!-- Chatbot Icon -->
     <div id="chatbot-icon" onclick="toggleChatbot()">💬</div>

<!-- Chatbot Container -->
<div id="chatbot-container">
    <div id="chatbot-messages"></div>
    <div id="chatbot-questions"></div>
    
    <!-- User Input Section -->
    <div id="user-input-container">
        <input type="text" id="user-question" placeholder="Ask a question..." />
        <button id="send-button" onclick="sendUserQuestion()">Send</button>
    </div>
</div>

<script>
    // Function to toggle chatbot visibility
    function toggleChatbot() {
        const chatbotContainer = document.getElementById('chatbot-container');
        chatbotContainer.style.display = chatbotContainer.style.display === 'none' ? 'flex' : 'none';
        
        if (chatbotContainer.style.display === 'flex') {
            loadQuestions();
        }
    }

    // Load initial questions
    function loadQuestions() {
        fetch('chatbot.php')
            .then(response => response.json())
            .then(data => {
                const questionsContainer = document.getElementById('chatbot-questions');
                questionsContainer.innerHTML = ''; // Clear previous questions
                
                data.questions.forEach(question => {
                    const questionButton = document.createElement('button');
                    questionButton.textContent = question;
                    questionButton.onclick = () => sendQuestion(question);
                    questionsContainer.appendChild(questionButton);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Process typed question
    function sendUserQuestion() {
        const userQuestion = document.getElementById('user-question').value;
        displayMessage(userQuestion, 'user-question');
        document.getElementById('user-question').value = ''; // Clear input box

        // Send user question to chatbot backend
        fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'question=' + encodeURIComponent(userQuestion)
        })
        .then(response => response.json())
        .then(data => {
            const messagesContainer = document.getElementById('chatbot-messages');

            if (data.matches) {
                data.matches.forEach(match => {
                    const questionButton = document.createElement('button');
                    questionButton.className = 'chat-message bot-reply';
                    questionButton.textContent = match.question;
                    questionButton.onclick = () => displayMessage(match.response, 'bot-reply');
                    messagesContainer.appendChild(questionButton);
                });
            } else {
                displayMessage(data.reply, 'bot-reply');
            }

            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => console.error('Error:', error));
    }

    // Display messages in the chat
    function displayMessage(text, className) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageElement = document.createElement('div');
        messageElement.className = 'chat-message ' + className;
        messageElement.textContent = text;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
</script>

</body>
</html>
