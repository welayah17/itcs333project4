<?php 
include '../Header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Club - Campus Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Add a New Club</h1>
    </header>

    <main>
        <form id="clubForm" action="insertClub.php" method="POST" >
            <label for="name">Club Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="leader">Leader:</label>
            <input type="text" id="leader" name="leader" required>

            <button type="submit">Add Club</button>
        </form>

        <p id="formStatus"></p>
    </main>

    <footer>
        <p>Campus Hub &copy; 2025</p>
    </footer>

    <script src="addClub.js"></script>
</body>
</html>

