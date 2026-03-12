<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";
$message_type = "success";

// Handle Add Student Form Submission
if(isset($_POST['submit'])) {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $class = $_POST['class'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    
    $sql = "INSERT INTO students (student_id, student_name, class, age, address) 
            VALUES ('$student_id', '$student_name', '$class', '$age', '$address')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Student added successfully!";
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "error";
    }
}

// Handle Delete Student
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM students WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Student deleted successfully!";
    } else {
        $message = "Error deleting record: " . $conn->error;
        $message_type = "error";
    }
}

// Handle Edit Student
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $class = $_POST['class'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    
    $sql = "UPDATE students SET 
            student_id='$student_id', 
            student_name='$student_name', 
            class='$class', 
            age='$age', 
            address='$address' 
            WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Student updated successfully!";
    } else {
        $message = "Error updating record: " . $conn->error;
        $message_type = "error";
    }
}

// Check if editing
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $sql = "SELECT * FROM students WHERE id=$id";
    $result = $conn->query($sql);
    $edit_data = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - View</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">SMS</div>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#add-student">Add Student</a></li>
                <li><a href="#" class="active">View Students</a></li>
                <li><a href="index.html#about">About</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="section section-gray">
        <div class="container">
            
            <?php if($message != "") { ?>
                <div class="message message-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <!-- Students Table -->
            <div class="table-container">
                <div class="table-header">
                    <h2>All Students</h2>
                </div>
                
                <?php
                $sql = "SELECT * FROM students";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='data-table'>";
                    echo "<thead>
                            <tr>
                                <th>ID</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Age</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>";
                    
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td><span class='badge'>" . $row["student_id"] . "</span></td>";
                        echo "<td><strong>" . $row["student_name"] . "</strong></td>";
                        echo "<td>" . $row["class"] . "</td>";
                        echo "<td>" . $row["age"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td class='actions'>
                                <a href='index.php?delete=" . $row["id"] . "' class='btn-icon delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this student?\")'>🗑️</a>
                              </td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                    echo "</div>";
                } else {
                    echo "<div class='empty-state'>
                            <div class='empty-icon'>📭</div>
                            <h3>No Students Found</h3>
                            <p>Start by adding your first student record</p>
                            <a href='index.html#add-student' class='btn btn-primary'>Add Student</a>
                          </div>";
                }
                
                $conn->close();
                ?>
            </div>
        </div>
    </section>


</body>
</html>