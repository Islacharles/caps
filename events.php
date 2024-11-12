<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #4A3AFF;
            color: white;
            padding: 10px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 60px;
        }

        .sidebar h1 {
            font-size: 24px;
            margin: 10px 0;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            margin-bottom: 50px;
            display: block;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .sidebar .bottom-links a {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .sidebar .bottom-links a i {
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            background-color: #F5F5F5;
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 1000px;
        }

        .header .user-info {
            display: flex;
            align-items: left;
        }

        .header .user-info .notification {
            display: flex;
            align-items: center;
        }

        .header .user-info .vertical-rule {
            border-left: 1px solid #E0E0E0;
            height: 40px;
            margin: 0 20px;
        }

        .header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .header .user-info span {
            font-size: 16px;
        }
        .create-btn {
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.create-btn:hover {
    background-color: #0056b3;
}

/* Popup overlay */
.overlay {
    display: none; /* Hide by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
    backdrop-filter: blur(8px); /* Blurring effect */
    z-index: 999; /* Ensure it's above other content */
}

/* Popup window */
.create-panel {
    display: none; /* Hide by default */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    background-color: #ffffff;
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000; /* Ensure it's above the overlay */
    transition: opacity 0.3s ease;
    opacity: 0;
}

.create-panel.show {
    display: block;
    opacity: 1;
}

.create-panel h2 {
    margin-top: 0;
    font-size: 24px;
    color: #333;
}

.date-label {
    font-size: 1.2em;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

.date-input {
    padding: 10px;
    border: 2px solid #4a90e2;
    border-radius: 5px;
    width: 200px;
    transition: border-color 0.3s;
    font-size: 1em;
}

.date-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.create-panel input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.create-panel button[type="submit"] {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: black;
    color: white;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.create-panel button[type="submit"]:hover {
    background-color: #4A3AFF;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    background: none;
    border: none;
    color: #333;
}
        .table-container {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            position: relative;
            overflow-x: auto;
        }

        .table-container .search-bar-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-container .search-bar-container .create-btn {
            margin-right: 20px;
            background-color: #4A3AFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .table-container .search-bar-container .search-bar {
            display: flex;
            align-items: center;
            background-color: #EDEDED;
            padding: 10px;
            border-radius: 5px;
            flex-grow: 1;
            cursor: text;
        }

        .table-container .search-bar-container .search-bar i {
            margin-right: 10px;
        }

        .table-container .search-bar-container .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 16px;
            flex-grow: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #E0E0FF;
        }

        table tr:nth-child(even) {
            background-color: #F9F9F9;
        }

        table tr:hover {
            background-color: #F1F1F1;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #4A3AFF;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }

        .pagination .page-number {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #E0E0FF;
            border-radius: 50%;
            margin: 0 5px;
        }

        .pagination .page-number.active {
            background-color: #4A3AFF;
            color: white;
        }

        hr {
            border: 0;
            height: 1px;
            background: #E0E0E0;
            margin: 20px 0;
        }

        /* Responsive styles */
        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .header .user-info {
                margin-right: 20px; /* Adjust if needed */
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .header .user-info {
                margin-right: 0;
            }

            .table-container {
                padding: 10px;
            }

            .table-container .search-bar-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-container .search-bar-container .create-btn {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .table-container .search-bar-container .search-bar {
                width: 100%;
            }

            table th, table td {
                padding: 10px;
                font-size: 14px;
            }

            .pagination a, .pagination .page-number {
                font-size: 14px;
                margin: 0 5px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .sidebar img {
                width: 60px;
                height: 60px;
            }

            .sidebar h1 {
                font-size: 20px;
            }

            .header .user-info span {
                font-size: 14px;
            }

            .table-container {
                padding: 5px;
            }

            .table-container .search-bar-container .create-btn {
                padding: 8px 16px;
            }

            table th, table td {
                padding: 8px;
            }

            .pagination a, .pagination .page-number {
                font-size: 12px;
                margin: 0 3px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <img src="logo/logo.png" alt="Logo">
        <a href="#">Attendance</a>
        <a href="student.php">Student Records</a>
        <a href="parent.php">Parent Records</a>
        <a href="staff.php">Admin/Staff Records</a>
        <a href="#">Pick-Up Records</a>
        <a href="#">Events</a>
        <div class="bottom-links">
            <a href="#">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="#">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="user-info">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="vertical-rule"></div>
                <div class="profile">
                <img alt="User profile picture" height="40" src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-Hh5RPsKhtBPsWCFSiEKnUJ6x/user-8qgiVpCV0U0b7zDjfFInHgjl/img-UaqtED2tWdF8Jj5BdVptvrvZ.png?st=2024-09-08T03%3A49%3A50Z&amp;se=2024-09-08T05%3A49%3A50Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-07T23%3A47%3A39Z&amp;ske=2024-09-08T23%3A47%3A39Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=18zzyGV2lWaM/BQ7/LoacKemQW7r9eD1vJOq3I7Ssss%3D" width="40"/>
                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span><br>
                <span><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                </div>
            </div>
        </div>
        <div class="table-container">
        <div class="search-bar-container">
        <button class="create-btn">CREATE</button>

    <!-- Popup overlay -->
    <div class="overlay"></div>

    <!-- Popup window -->
    <div class="create-panel">
        <button class="close-btn">&times;</button>
        <form id="create-form" action ="submit_events.php" method="post">
            <h2>Admin/Staff Account Creation</h2>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required><br><br>

            <label for="byline">Byline:</label>
            <input type="tel" id="byline" name="byline" required><br><br>

            <label for="paragraph">Paragraph:</label>
            <input type="text" id="paragraph" name="paragraph" required><br><br>

            <label for="date" class="date-label">Date</label>
            <input type="date" id="date" name="date" class="date-input" required>


            <button type="submit">Submit</button>
        </form> 
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
    const createBtn = document.querySelector('.create-btn');
    const overlay = document.querySelector('.overlay');
    const createPanel = document.querySelector('.create-panel');
    const closeBtn = document.querySelector('.close-btn');

    // Function to open the popup
    function openPopup() {
        overlay.style.display = 'block';
        createPanel.classList.add('show');
    }

    // Function to close the popup
    function closePopup() {
        overlay.style.display = 'none';
        createPanel.classList.remove('show');
    }

    // Event listener for the "CREATE" button
    createBtn.addEventListener('click', openPopup);

    // Event listener for the close button
    closeBtn.addEventListener('click', closePopup);

    // Event listener for the overlay
    overlay.addEventListener('click', closePopup);
});

    </script> 
            <div class="search-bar">
            
            <input type="text" id="search" placeholder="Search..." onkeyup="performSearch(event)">
            
            
            </div>
        </div>
        
        <?php
include 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$itemsPerPage = 10; // Items per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from the URL
$offset = ($currentPage - 1) * $itemsPerPage; // Offset for SQL query

// Query to select records from the events table with LIMIT for pagination
$sql = "SELECT id, title, byline, paragraph, date FROM events LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

// Count total records for pagination
$totalSql = "SELECT COUNT(*) as total FROM events";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];

echo '<table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Byline</th>
                <th>Paragraph</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['title']) . '</td>
                <td>' . htmlspecialchars($row['byline']) . '</td>
                <td>' . htmlspecialchars($row['paragraph']) . '</td>
                <td>' . htmlspecialchars($row['date']) . '</td>
                <td>
                    <i class="fas fa-pen" title="Edit" onclick="location.href=\'edit_event.php?id=' . $row['id'] . '\'"></i>
                    <i class="fas fa-trash" title="Delete" onclick="location.href=\'delete_event.php?id=' . $row['id'] . '\'"></i>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="5">No records found</td></tr>';
}

echo '  </tbody>
      </table>';

// Close the database connection
$conn->close();
?>


            <hr>
            <div class="pagination" id="pagination"></div>
        </div>
    </div>
<script>
    const totalItems = <?php echo $totalItems; ?>; // Total number of items from PHP
const itemsPerPage = <?php echo $itemsPerPage; ?>; // Items per page from PHP
let currentPage = <?php echo $currentPage; ?>; // Current page from PHP

function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = ''; // Clear previous pagination

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Previous button
    const prevLink = document.createElement('a');
    prevLink.innerHTML = '«';
    prevLink.className = currentPage === 1 ? 'disabled' : '';
    prevLink.onclick = function() {
        if (currentPage > 1) {
            currentPage--;
            updatePage();
        }
    };
    pagination.appendChild(prevLink);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageNumber = document.createElement('div');
        pageNumber.innerHTML = i;
        pageNumber.className = `page-number ${i === currentPage ? 'active' : ''}`;
        pageNumber.onclick = function() {
            currentPage = i;
            updatePage();
        };
        pagination.appendChild(pageNumber);
    }

    // Next button
    const nextLink = document.createElement('a');
    nextLink.innerHTML = '»';
    nextLink.className = currentPage === totalPages ? 'disabled' : '';
    nextLink.onclick = function() {
        if (currentPage < totalPages) {
            currentPage++;
            updatePage();
        }
    };
    pagination.appendChild(nextLink);
}

function updatePage() {
    window.location.href = '?page=' + currentPage; // Redirect to the correct page
}

// Initial rendering
renderPagination();
</script>
    <script src="script/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>

