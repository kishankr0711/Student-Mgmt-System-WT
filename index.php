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
<html>
<head>
    <title>Student Management System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student Management System</h1>
        
        <?php if($message != "") { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        
        <?php if($edit_mode) { ?>
        <!-- Edit Form -->
        <form method="POST" action="">
            <h2>Edit Student</h2>
            
            <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
            
            <label>Student ID</label>
            <input type="text" name="student_id" value="<?php echo $edit_data['student_id']; ?>" required>
            
            <label>Student Name</label>
            <input type="text" name="student_name" value="<?php echo $edit_data['student_name']; ?>" required>
            
            <label>Class</label>
            <input type="text" name="class" value="<?php echo $edit_data['class']; ?>" required>
            
            <label>Age</label>
            <input type="number" name="age" value="<?php echo $edit_data['age']; ?>" required>
            
            <label>Address</label>
            <textarea name="address" rows="3"><?php echo $edit_data['address']; ?></textarea>
            
            <div class="button-group">
                <input type="submit" name="update" value="Update Student">
                <a href="index.php" class="button">Cancel</a>
            </div>
        </form>
        <?php } ?>
        
        <!-- Display All Students -->
        <h2>All Students</h2>
        <?php
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Actions</th>
                  </tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["student_id"] . "</td>";
                echo "<td>" . $row["student_name"] . "</td>";
                echo "<td>" . $row["class"] . "</td>";
                echo "<td>" . $row["age"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>";
                echo "<a href='index.php?edit=" . $row["id"] . "' class='edit-btn'>Edit</a>";
                echo "<a href='index.php?delete=" . $row["id"] . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='empty-state'>No students found.</div>";
        }
        
        $conn->close();
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.html" class="button">Add New Student</a>
        </div>
    </div>
</body>
</html>