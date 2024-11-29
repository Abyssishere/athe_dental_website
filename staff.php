<?php 
// Database connection details
$servername = "localhost:3309";  // Database server
$username = "root";              // Database username
$password = "";                  // Database password
$dbname = "db_mike";             // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $patient_name = trim(filter_input(INPUT_POST, 'patient_name', FILTER_SANITIZE_STRING));
    $patient_email = trim(filter_input(INPUT_POST, 'patient_email', FILTER_SANITIZE_EMAIL));
    $appointment_date = trim(filter_input(INPUT_POST, 'appointment_date', FILTER_SANITIZE_STRING));
    $appointment_time = trim(filter_input(INPUT_POST, 'appointment_time', FILTER_SANITIZE_STRING));
    $reason_for_visit = trim(filter_input(INPUT_POST, 'reason_for_visit', FILTER_SANITIZE_STRING));
    $doctor_name = trim(filter_input(INPUT_POST, 'doctor_name', FILTER_SANITIZE_STRING));

    // Validate form data
    if (empty($patient_name) || empty($patient_email) || empty($appointment_date) || empty($appointment_time) || empty($reason_for_visit) || empty($doctor_name)) {
        echo "<p style='color:red;'>All fields are required. Please fill in all the information.</p>";
    } elseif (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format. Please enter a valid email.</p>";
    } else {
        // Insert appointment into the database
        $sql = "INSERT INTO Appointments (PatientName, PatientEmail, AppointmentDate, AppointmentTime, ReasonForVisit, DoctorName) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("Error preparing SQL statement: " . $conn->error);
        }

        // Bind parameters to the SQL query
        $stmt->bind_param("ssssss", $patient_name, $patient_email, $appointment_date, $appointment_time, $reason_for_visit, $doctor_name);

        // Execute and check if insertion was successful
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Appointment successfully booked!</p>";
        } else {
            echo "<p style='color:red;'>Error booking appointment. Please try again later.</p>";
        }

        // Close the prepared statement
        $stmt->close();
    }
}

// Fetch all appointments to display
$sql = "SELECT PatientName, PatientEmail, AppointmentDate, AppointmentTime, ReasonForVisit, DoctorName FROM Appointments ORDER BY AppointmentDate, AppointmentTime";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Appointment Booking</title>
    <link rel="stylesheet" href="staffarea.css">
</head>
<body>
<nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="contact1.html">Contact</a></li>
            <li><a href="appointments.html">Appointments</a></li>
            <li><a href="staff.html">Staff Area</a></li>
        </ul>
    </nav>

<!-- Display List of Booked Appointments -->
<h3>Booked Appointments</h3>
<table>
    <thead>
        <tr>
            <th>Patient Name</th>
            <th>Patient Email</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Reason for Visit</th>
            <th>Doctor's Name</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['PatientName']) ?></td>
                    <td><?= htmlspecialchars($row['PatientEmail']) ?></td>
                    <td><?= htmlspecialchars($row['AppointmentDate']) ?></td>
                    <td><?= htmlspecialchars($row['AppointmentTime']) ?></td>
                    <td><?= htmlspecialchars($row['ReasonForVisit']) ?></td>
                    <td><?= htmlspecialchars($row['DoctorName']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No appointments found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
