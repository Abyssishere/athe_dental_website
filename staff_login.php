<?php
// Start session for storing login status
session_start();

// Manual credentials (can be easily changed)
$validUsername = "Michael";   // Change the username here
$validPassword = "1234";      // Change the password here

// Database connection details
$servername = "localhost:3309";  // Database server (adjust port if necessary)
$username = "root";              // Database username
$password = "";                  // Database password (leave blank for default XAMPP)
$dbname = "db_mike";     // Database name

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input values
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);

    // Check if the input matches the manual credentials
    if ($inputUsername === $validUsername && $inputPassword === $validPassword) {
        // If the credentials are correct, set the session variables
        $_SESSION['staff_logged_in'] = true;
        $_SESSION['staff_username'] = $inputUsername; // Store username in session for reference

        // Redirect to the staff dashboard or area
        header("Location: staff.php");
        exit();
    } else {
        // If manual credentials fail, check the database for user login
        if (empty($inputUsername) || empty($inputPassword)) {
            echo "<p style='color:red;'>Please enter both username and password.</p>";
        } else {
            // Prepare SQL statement to fetch staff details by username
            $sql = "SELECT * FROM Staff WHERE Username = ?";
            $stmt = $conn->prepare($sql);

            // Check if the statement was prepared successfully
            if ($stmt === false) {
                die("Error preparing SQL statement: " . $conn->error);
            }

            // Bind parameters to the SQL query and execute
            $stmt->bind_param("s", $inputUsername);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the username exists in the database
            if ($result->num_rows === 1) {
                // Fetch staff details from the database
                $staff = $result->fetch_assoc();

                // Verify the password using bcrypt hash
                if (password_verify($inputPassword, $staff['PasswordHash'])) {
                    // Set session variables to indicate the user is logged in
                    $_SESSION['staff_logged_in'] = true;
                    $_SESSION['staff_username'] = $inputUsername; // Store username in session for reference

                    // Redirect to the staff dashboard or area
                    header("Location: staff.php");
                    exit();
                } else {
                    // Invalid password entered
                    echo "<p style='color:red;'>Invalid password. Please try again.</p>";
                }
            } else {
                // Username not found
                echo "<p style='color:red;'>Invalid username. Please try again.</p>";
            }

            // Close the statement to free up resources
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>
