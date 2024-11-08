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
    background-color: #4A3AFF; /* Primary color */
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

/* General overlay styling */
.overlay {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6); /* Darker overlay */
    z-index: 999; /* Above all content */
}

/* Create panel (modal) styling */
.create-panel {
    visibility: hidden; /* Initially hidden */
    opacity: 0; /* Fully transparent */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Show the panel when needed */
.create-panel.show {
    visibility: visible;
    opacity: 1;
    transform: translate(-50%, -50%);
}

/* Close button for the modal */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 28px;
    cursor: pointer;
    background: none;
    border: none;
    color: #333;
}

/* Styling for the authorized-info section */
#authorized-info {
    display: none;
    opacity: 0;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Show the authorized-info section when necessary */
#authorized-info.show {
    display: block;
    opacity: 1;
    transform: translate(-50%, -50%);
}

/* Heading for authorized info */
#authorized-info h3 {
    font-size: 20px;
    color: #333;
    font-weight: bold;
    margin-bottom: 20px; /* Add more space between heading and inputs */
}

/* Form Inputs */
input, select, textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

/* Focus effect for inputs */
input:focus, select:focus, textarea:focus {
    border-color: black;
    outline: none;
}

/* Submit button styling */
button[type="submit"] {
    padding: 14px 20px;
    font-size: 16px;
    cursor: pointer;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    width: 100%; /* Full-width */
    margin-top: 15px;
}

/* Hover effect for submit button */
button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Media query for mobile responsiveness */
@media (max-width: 768px) {
    .create-panel, #authorized-info {
        width: 95%;
        padding: 20px;
    }

    .close-btn {
        font-size: 26px;
    }

    #authorized-info h3 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    input, select, textarea {
        padding: 12px;
    }

    button[type="submit"] {
        padding: 12px 18px;
    }
}

/* General container for the table */
.table-container {
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    position: relative;
    overflow-x: auto;
}

/* Container for the search bar and button */
.table-container .search-bar-container {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

/* Button styling */
.table-container .search-bar-container .create-btn {
    background-color: #4A3AFF;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
}

/* Search bar container */
.table-container .search-bar-container .search-bar {
    display: flex;
    align-items: center;
    background-color: #f5f5f5;
    padding: 10px; /* Padding set to 10px */
    border-radius: 20px;
    flex-grow: 1;
}

/* Search input field */
.table-container .search-bar-container .search-bar input {
    border: none;
    background: none;
    outline: none;
    font-size: 14px;
    flex-grow: 1;
    padding: 1px 2px; /* Padding set to 1px 2px */
}

/* Placeholder styling */
.table-container .search-bar-container .search-bar input::placeholder {
    color: #888;
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
        <a href="events.php">Events</a>
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
                
                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span><br>
                <span><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                </div>
            </div>
        </div>
        <div class="table-container">
        <div class="search-bar-container">
        <button class="create-btn" id="create-btn">CREATE</button>
<!-- Parent form modal and Authorized Info form -->
<div class="overlay" id="overlay"></div>

<!-- Parent Form Modal -->
<div class="create-panel" id="create-panel">
    <button class="close-btn" id="close-btn">&times;</button>
    <form id="parent-form" action="submit_form.php" method="post" enctype="multipart/form-data">
        <h2>Parent Account Creation</h2>
        <!-- Form Fields -->
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required>
        <label for="contactnumber">Contact Number:</label>
        <input type="tel" id="contactnumber" name="contact_number" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <label for="parentimage">Parent's Image:</label>
        <input type="file" id="parentimage" name="parent_image" accept="image/*" required>
        <button type="submit">Submit</button>
    </form>
</div>

<!-- Authorized Info Form -->
<div id="authorized-info" class="authorized-info" style="display: none;">
    <form id="authorized-form" action="authorized_submit_form.php" method="post" enctype="multipart/form-data">
        <h3>Authorized Pick-Up Person</h3>
        <!-- Form Fields -->
        <label for="authorized_fullname">Full Name:</label>
        <input type="text" id="authorized_fullname" name="authorized_fullname" required>
        <label for="authorized_address">Address:</label>
        <input type="text" id="authorized_address" name="authorized_address" required>
        <label for="authorized_age">Age:</label>
        <input type="number" id="authorized_age" name="authorized_age" required>
        <label for="authorized_image">Upload Authorized Image:</label>
        <input type="file" id="authorized_image" name="authorized_image" accept="image/*" required>
        <button type="submit">Submit Authorized Pick-Up</button>
    </form>
</div>


<script>
// Function to show the loader
function showLoader() {
    document.getElementById('loader').style.display = 'flex';
}

// Function to hide the loader
function hideLoader() {
    document.getElementById('loader').style.display = 'none';
}

// Event listener for the "CREATE" button (for showing the parent form modal)
document.getElementById('create-btn')?.addEventListener('click', function() {
    // Show the overlay and create panel when "CREATE" button is clicked
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('create-panel').classList.add('show');
});

// Event listener for the "CLOSE" button (to close the parent form modal)
document.getElementById('close-btn')?.addEventListener('click', function() {
    // Hide the overlay and create panel when close button is clicked
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('create-panel').classList.remove('show');
    
    // Reset the form when closing
    document.getElementById('parent-form').reset();
});

// Handle the parent form submission
document.getElementById('parent-form')?.addEventListener('submit', async function(event) {
    event.preventDefault();  // Prevent the default form submission

    showLoader();  // Show loader during form submission

    const formData = new FormData(this);  // Get form data

    try {
        const response = await fetch('submit_form.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const contentType = response.headers.get("content-type");
        let data;

        // Handle JSON response
        if (contentType && contentType.includes("application/json")) {
            data = await response.json();
        } else {
            throw new Error("Expected JSON response, but got something else.");
        }

        console.log('Parent Form Response:', data);

        if (data.success) {
            // Hide the parent form and show the authorized pick-up form
            document.getElementById('parent-form').style.display = 'none';
            document.getElementById('authorized-info').style.display = 'block';

            // Append the parent ID to the authorized form
            const parentIdInput = document.createElement('input');
            parentIdInput.type = 'hidden';
            parentIdInput.name = 'parent_id';
            parentIdInput.value = data.parent_id;
            document.getElementById('authorized-form').appendChild(parentIdInput);

            // Show the authorized form with smooth transition
            document.getElementById('authorized-info').classList.add('show');

            // Optionally reset the parent form and hide the modal after successful submission
            this.reset();
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('create-panel').classList.remove('show');

            // Automatically show the authorized form for completion
        } else {
            alert(data.message || 'An error occurred. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while submitting the parent form. Please try again.');
    } finally {
        hideLoader();  // Hide the loader after the process is complete
    }
});

// Handle the authorized form submission
document.getElementById('authorized-form')?.addEventListener('submit', async function(event) {
    event.preventDefault();  // Prevent the default form submission

    showLoader();  // Show loader during form submission

    const formData = new FormData(this);  // Get form data

    try {
        const response = await fetch('authorized_submit_form.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const contentType = response.headers.get("content-type");
        let data;

        // Handle JSON response
        if (contentType && contentType.includes("application/json")) {
            data = await response.json();
        } else {
            throw new Error("Expected JSON response, but got something else.");
        }

        console.log('Authorized Form Response:', data);

        if (data.success) {
                    alert(data.message); // Show success message
                    closeModal();  // Close the modal after success
                    // Reload the page after a short delay
                    setTimeout(() => {
                        location.reload();  // Reload the page
                    }, 1000);  // 1-second delay for smooth experience
                } else {
            alert(data.message || 'An error occurred while adding the authorized pick-up person.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while submitting the authorized form. Please try again.');
    } finally {
        hideLoader();  // Hide the loader after the process is complete
    }
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
$currentPage = max(1, $currentPage); // Ensure current page is at least 1
$offset = ($currentPage - 1) * $itemsPerPage; // Offset for SQL query

// Prepared statement for selecting records with LIMIT for pagination
$stmt = $conn->prepare("SELECT id, fullname, contact_number, email, address FROM parent_acc LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

// Count total records for pagination
$totalResult = $conn->query("SELECT COUNT(*) as total FROM parent_acc");
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

// HTML Table
echo '<table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Email Address</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['fullname']) . '</td>
                <td>' . htmlspecialchars($row['contact_number']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['address']) . '</td>
                <td>
                    <i class="fas fa-pen" title="Edit" onclick="location.href=\'edit_staff.php?id=' . $row['id'] . '\'"></i>
                    <i class="fas fa-trash" title="Delete" onclick="location.href=\'delete_staff.php?id=' . $row['id'] . '\'"></i>
                    <i class="fas fa-eye" title="View" data-parent-id="' . $row['id'] . '" onclick="showChildInfo(this.dataset.parentId)"></i>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="5">No records found</td></tr>';
}

echo '  </tbody>
      </table>';



// Pagination controls


// Close the database connection
$stmt->close();
$conn->close();
?>

<!-- Modal Structure -->
<div id="childInfoModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Student Information</h3>
        <div class="student-info">
            <div class="left-section">
                <div class="student-photo">
                    <img id="child-image" src="" alt="Student photo">
                </div>
                <div class="info-field">
                    <label for="child-name">Child Name</label>
                    <input type="text" id="child-name" readonly>
                </div>
                <div class="info-field">
                    <label for="child-id">Student ID</label>
                    <input type="text" id="child-id" readonly>
                </div>
                <div class="info-field">
                    <label for="child-section">Section</label>
                    <input type="text" id="child-section" readonly>
                </div>
                <div class="info-field">
                    <label for="child-grade">Grade</label>
                    <input type="text" id="child-grade" readonly>
                </div>
                <div class="info-field">
                    <label for="child-age">Age</label>
                    <input type="text" id="child-age" readonly>
                </div>
                <div class="info-field">
                    <label for="child-address">Address</label>
                    <input type="text" id="child-address" readonly>
                </div>
                <div class="info-field">
                    <label for="child-teacher">Adviser</label>
                    <input type="text" id="child-teacher" readonly>
                </div>
                <div class="button-group">
                    <button class="edit-btn">Click to Edit Info</button>
                    <button class="qr-btn">Download QR Code</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showChildInfo(parentId) {
    fetch('child_info.php?parent_id=' + parentId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text(); // Get raw HTML instead of JSON
        })
        .then(html => {
            // Populate the modal with the raw HTML content
            document.querySelector('.student-info').innerHTML = html; // Use the correct selector to set the HTML
            showModal(); // Show the modal
        })
        .catch(error => {
            console.error('Error fetching child information:', error);
            alert('Failed to fetch child information. Please try again.');
        });
}

function showModal() {
    document.getElementById('childInfoModal').style.display = "block";
}

function closeModal() {
    document.getElementById('childInfoModal').style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById('childInfoModal');
    if (event.target == modal) {
        closeModal();
    }
}

</script>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 800px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 20px;
}

.close-btn:hover,
.close-btn:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.student-info {
    display: flex;
    justify-content: space-between;
}

.left-section, .right-section {
    width: 48%;
}

.student-photo {
    text-align: center;
    margin-bottom: 10px;
}

.student-photo img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.info-field {
    margin-bottom: 10px;
}

.info-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.info-field input {
    width: 100%;
    padding: 8px;
    border: none;
    border-radius: 4px;
    background-color: #7c3aed;
    color: white;
}

.button-group {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

button {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.edit-btn {
    background-color: #7c3aed;
    color: white;
}

.qr-btn {
    background-color: #10b981;
    color: white;
}

.add-btn {
    background-color: #e0e7ff;
    color: #4338ca;
    width: 100%;
    margin-top: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-btn i {
    margin-left: 5px;
}

#authorized-persons-list {
    margin-bottom: 10px;
}

.person {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 5px;
    background-color: #f3f4f6;
    border-radius: 4px;
}

.person img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.remove-btn {
    background-color: #ef4444;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}
</style>

</style>


            <hr>
            <div class="pagination" id="pagination"></div>
            <script>
const totalItems = <?php echo $totalItems; ?>; 
const itemsPerPage = <?php echo $itemsPerPage; ?>; 
let currentPage = <?php echo $currentPage; ?>; 

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
            </div>
        </div>
    </div>

    <script src="script/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
/* Loader style */
.loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}



@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
<!-- Loading Spinner -->
<div id="loader" class="loader" style="display: none;">
    <div class="spinner"></div>
</div>



</body>
</html>
