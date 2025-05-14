<?php
include '../Header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Campus Hub - Club Activities</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Club Activities</h1>
    <div class="container">
        <a href="page2.html" class="add-group">Add a New Club</a>
    </div>
</header>

<main>
    <div class="search-filter">
        <input type="text" id="searchInput" placeholder="Search for clubs...">
        <select id="clubFilter">
            <option value="">Filter by Club</option>
        </select>
        <select id="sortOption">
            <option value="name">Sort by Name</option>
            <option value="category">Sort by Category</option>
        </select>
    </div>

    <div id="loading" style="display: none;">Loading...</div>
    <div id="error" style="display: none; color: red;">Error fetching data.</div>

    <div id="activityList" class="group-list">

    </div>

    <div class="pag">
        <button id="prevPage" class="add-group">Previous</button>
        <button id="nextPage" class="add-group">Next</button>
    </div>

</main>


<div id="updateModal" style="display: none;" class="modal">
    <div class="modal-content">
        <div style="text-align: right;">
            <button id="closeModal" class="add-group">X</button>
        </div>
        <h3>Edit Club</h3>
        <form id="updateForm" onsubmit="event.preventDefault(); updateClub();">
            <input type="hidden" id="update-id" name="id">
            <input type="text" name="name" placeholder="Club Name" required><br>
            <input type="text" name="category" placeholder="Category" required><br>
            <textarea name="description" placeholder="Description" required></textarea><br>
            <input type="text" name="leader" placeholder="Leader" required><br>
            <button type="submit">Update Club</button>
            <button type="button" onclick="cancelEdit()">Cancel</button>
        </form>
    </div>
</div>

<footer>
    <p>Campus Hub &copy; 2025</p>
</footer>

<script src="script.js"></script>
</body>
</html>

