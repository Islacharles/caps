<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$user_name = $_SESSION['fullname'];
$user_role = $_SESSION['role'];
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
                <img alt="User profile picture" height="40" src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-Hh5RPsKhtBPsWCFSiEKnUJ6x/user-8qgiVpCV0U0b7zDjfFInHgjl/img-UaqtED2tWdF8Jj5BdVptvrvZ.png?st=2024-09-08T03%3A49%3A50Z&amp;se=2024-09-08T05%3A49%3A50Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-07T23%3A47%3A39Z&amp;ske=2024-09-08T23%3A47%3A39Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=18zzyGV2lWaM/BQ7/LoacKemQW7r9eD1vJOq3I7Ssss%3D" width="40"/>
                <span><?php echo htmlspecialchars($_SESSION['fullname']); ?></span><br>
                <span><?php echo htmlspecialchars($_SESSION['role']); ?></span>
                </div>
            </div>
        </div>
        <hr/>
        <?php
// Include database connection
include 'connection.php';

// Fetch teachers from admin_staff table where the role is "teacher"
$teachers_sql = "SELECT id, fullname FROM admin_staff WHERE role = 'teacher'";
$teachers_result = $conn->query($teachers_sql);

// Fetch parent names from parent_acc table
$parents_sql = "SELECT id, fullname FROM parent_acc";
$parents_result = $conn->query($parents_sql);

// Fetch authorized persons from authorized_persons table
$authorized_persons_sql = "SELECT id, fullname FROM authorized_persons";
$authorized_persons_result = $conn->query($authorized_persons_sql);

// Check for database connection errors
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . htmlspecialchars($conn->connect_error)]));
}
?>
        <div class="table-container">
            <div class="search-bar-container">
            <button class="create-btn" id="create-btn">CREATE</button>

<!-- Overlay -->
<div class="overlay" id="overlay"></div>

<!-- Child Form Modal -->
<div class="create-panel" id="create-panel">
    <button class="close-btn" id="close-btn">&times;</button>
    
    <form id="child-form" action="child_submit_form.php" method="post" enctype="multipart/form-data">
    <h2>Create Child Record</h2>

    <!-- Parent ID -->
    <div>
        <label for="parent_id">Parent ID:</label>
        <select name="parent_id" id="parent_id" required>
            <option value="">Select Parent</option>
            <?php while ($row = $parents_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['fullname']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Child Name -->
    <div>
        <label for="child_name">Child Name:</label>
        <input type="text" name="child_name" id="child_name" required>
    </div>

    <!-- Student ID -->
    <div>
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" id="student_id" required>
    </div>

    <!-- Child Teacher -->
    <div>
        <label for="child_teacher">Teacher Name:</label>
        <select name="child_teacher" id="child_teacher" required>
            <option value="">Select Teacher</option>
            <?php while ($row = $teachers_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['fullname']; ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Child Age -->
    <div>
        <label for="child_age">Child Age:</label>
        <input type="number" name="child_age" id="child_age" required>
    </div>

    <!-- Grade -->
    <div>
        <label for="child_grade">Grade:</label>
        <input type="text" name="child_grade" id="child_grade" required>
    </div>

    <!-- Section -->
    <div>
        <label for="child_section">Section:</label>
        <input type="text" name="child_section" id="child_section" required>
    </div>

    <!-- Address -->
    <div>
        <label for="child_address">Address:</label>
        <textarea name="child_address" id="child_address" required></textarea>
    </div>

    <!-- Image Upload -->
    <div>
        <label for="child_image">Child Image:</label>
        <input type="file" name="child_image" id="child_image" accept="image/*" required>
    </div>

    <!-- Authorized Person -->
    <div>
        <label for="authorized_person">Authorized Person:</label>
        <select name="authorized_person" id="authorized_person" required>
            <option value="">Select Authorized Person</option>
            <?php while ($row = $authorized_persons_result->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['fullname']; ?></option>
            <?php } ?>
        </select>
    </div>
    <!-- Submit Button -->
    <button type="submit" id="submit" class="submit-btn">Create</button>
</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const createBtn = document.getElementById('create-btn');
    const closeBtn = document.getElementById('close-btn');
    const overlay = document.getElementById('overlay');
    const createPanel = document.getElementById('create-panel');
    const childForm = document.getElementById('child-form');
    const submitBtn = document.getElementById('submit');
    const loadingOverlay = document.getElementById('loading-overlay'); 
    
    // Open the modal when 'CREATE' button is clicked
    createBtn.addEventListener('click', () => {
        createPanel.style.display = 'block';
        overlay.style.display = 'block';
    });
    
    // Close the modal when 'close' button or overlay is clicked
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    function closeModal() {
        createPanel.style.display = 'none';
        overlay.style.display = 'none';
    }
    
    // Handle the form submission using AJAX
    childForm.addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent default form submission

        const formData = new FormData(childForm);
        
        // Disable the submit button to prevent multiple submissions
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        // Show the loading overlay
        loadingOverlay.style.display = 'flex';

        // Use the Fetch API to send the form data
        fetch(childForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Read response as text first for debugging
        .then(text => {
            console.log('Raw server response:', text);  // Log raw response to check its content
            try {
                const data = JSON.parse(text);  // Attempt to parse JSON
                if (data.success) {
                    alert(data.message); // Show success message
                    closeModal();  // Close the modal after success
                    location.reload();  // Reload the page to reflect the new record
                } else {
                    alert(data.message); // Show error message
                }
            } catch (error) {
                alert('Invalid JSON response from server');
                console.error(error);
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create';
            alert('An error occurred: ' + error.message);
        })
        .finally(() => {
            // Hide the loading overlay when the AJAX request is complete
            loadingOverlay.style.display = 'none';
            // Re-enable the submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create';
        });
    });
});

</script>

<style>
/* Overlay styles */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;  /* Hidden by default */
    z-index: 999;
}

/* Create panel (modal) styles */
.create-panel {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    max-width: 90%;
    max-height: 80vh; /* Ensures the modal does not exceed 80% of the viewport height */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: none;  /* Hidden by default */
    z-index: 1000;
    overflow-y: auto; /* Enables vertical scrolling */
}

/* Add some padding to prevent content from sticking to the edges */
.create-panel form {
    padding-right: 10px; /* Prevents the scrollbar from hiding content */
}

/* Close button (×) styles */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 30px;
    color: #333;
    cursor: pointer;
}

/* Form input styles */
form input, form select, form textarea, form button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Form header styling */
form h2 {
    margin-bottom: 20px;
    text-align: center;
}

/* Button styles */
.create-btn {
    background-color: blue;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
}

.create-btn:hover {
    background-color: blue;
}

/* Submit button styles */
.submit-btn {
    background-color: blue;
    color: white;
    font-size: 16px;
    cursor: pointer;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: lightblue;
}

/* Hover styles for inputs */
input:hover, select:hover, textarea:hover {
    border-color: black;
}

/* Custom Scrollbar Styling (Optional) */
.create-panel::-webkit-scrollbar {
    width: 8px;  /* Set the width of the scrollbar */
}

.create-panel::-webkit-scrollbar-thumb {
    background-color: gray;
    border-radius: 10px;
}

.create-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
</style>
            
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

// Query to select records from the admin_staff table with LIMIT for pagination
$sql = "SELECT id, child_name, student_id, child_grade, child_section, child_address FROM child_acc LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

// Count total records for pagination
$totalSql = "SELECT COUNT(*) as total FROM parent_acc";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

$userRole = $_SESSION['role'];
// Start the HTML table
echo '<table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Grade</th>
                <th>Section</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['student_id']) . '</td>
                        <td>' . htmlspecialchars($row['child_name']) . '</td>
                        <td>' . htmlspecialchars($row['child_grade']) . '</td>
                        <td>' . htmlspecialchars($row['child_section']) . '</td>
                        <td>' . htmlspecialchars($row['child_address']) . '</td>
                        <td>';
        
                // Check if the user is a super admin
                if ($userRole === 'Super Admin') {
                    echo '<i class="fas fa-pen" title="Edit" onclick="location.href=\'edit_staff.php?id=' . $row['id'] . '\'"></i>
                          <i class="fas fa-trash" title="Delete" onclick="location.href=\'delete_staff.php?id=' . $row['id'] . '\'"></i>';
                }
        
                // Always show the View button
                echo '<i class="fas fa-eye" title="View" data-parent-id="' . $row['id'] . '" onclick="showChildInfo(this.dataset.parentId)"></i>';
                
                echo '</td>
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
        <hr/>
        <div class="pagination" id="pagination"></div>
        </div>
    </div>
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
    <script src="script/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Loading Overlay (for showing when the page is reloading or submitting) -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loader"></div>
</div>

<style>
    /* Overlay for loading screen */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none; /* Hidden by default */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* Loader animation (a simple spinner) */
.loader {
    border: 5px solid #f3f3f3; /* Light background */
    border-top: 5px solid blue; 
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

/* Keyframes for spinning loader */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>

</body>
</html>
