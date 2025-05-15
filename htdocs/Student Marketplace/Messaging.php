<!-- 20198132 FATEMA EBRAHIM ALI SALMAN -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SM: Chat Messaging</title>
  
  <style>
    .chat-container {
        width: 90%;
      max-width: 700px;
      margin: 50px auto;
      border-radius: 10px;
      border: 1px solid #dee2e6;
      height: 600px;
      display: flex;
      flex-direction: column;
      background-color: #fff;
    }

    .chat-header {
      padding: 15px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      font-weight: bold;
      text-align: center;
    }

    .chat-body {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      background-color: #f1f3f5;
    }

    .chat-message {
      margin-bottom: 15px;
      max-width: 75%;
      padding: 10px 15px;
      border-radius: 20px;
    }

    .sent {
      margin-left: auto;
      background-color: #d1e7dd;
    }

    .received {
      margin-right: auto;
      background-color: #ffffff;
      border: 1px solid #dee2e6;
    }

    .chat-footer {
      padding: 15px;
      border-top: 1px solid #dee2e6;
      background-color: #f8f9fa;
    }

    .chat-footer input {
      border-radius: 20px;
    }
    .phone i, .arrow i {
        color: #6a11cb;
    }
    .send-button {
  background: linear-gradient(90deg, #6a11cb, #2575fc);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 30px;
  opacity: 0.85; /* Global opacity */
  transition: opacity 0.3s ease;
}
.arrow {
    border: none;
}

  </style>
</head>
<body>
  <?php include '../Header.php'; ?>

  <main class="container my-5">
    <div class="chat-container shadow">
      <!-- Chat Header -->
      <div class="chat-header d-flex justify-content-between align-items-center p-3 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="arrow text-decoration-none text-dark" onclick="history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <span>ID: 123456</span>
          <span class="text-center">🗨️ Chat with Seller</span>
          <br>
          <div class="phone text-muted ">
            <i class="fa-solid fa-phone"></i> 12345678
          </div>
        </div>
      </div>

      <!-- Chat Body -->
      <div class="chat-body p-3 flex-grow-1 overflow-auto">
        <div class="chat-message received mb-2">
          Hey! Is the textbook still available?
        </div>
        <div class="chat-message sent mb-2">
          Yes, it is! Want to meet on campus?
        </div>
        <div class="chat-message received mb-2">
          Perfect, how about tomorrow at 2 PM?
        </div>
        <div class="chat-message sent mb-2">
          Works for me. See you then!
        </div>
      </div>

      <!-- Chat Footer -->
      <div class="chat-footer p-3 border-top">
        <form class="d-flex gap-2">
          <input type="text" class="form-control" placeholder="Type your message..." />
          <button type="submit" class="btn send-button">Send</button>
        </form>
      </div>

    </div>
  </main>
  <?php include '../Footer.php'; ?>

</body>
</html>
