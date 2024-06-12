<?php
// Initialize session
session_start();

// Include database connection settings
include('../connection/dbconn.php');

if (isset($_POST['login'])) {
    // Capture values from HTML form
    $USER_username = trim($_POST['USER_username']); // Trim to remove extra spaces
    $USER_password = $_POST['USER_password'];

    $hashed_password = sha1($USER_password);

    // Prepare and execute SQL query
    $sql = "SELECT USER_username, USER_password, USER_type FROM user WHERE USER_username = ?";
    $stmt = $dbconn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $USER_username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($USER_username_db, $USER_password_db, $USER_type);
        $stmt->fetch();

        // Check if username exists
        if ($stmt->num_rows > 0) {
            // Verify password
            if ($hashed_password === $USER_password_db) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['USER_username'] = $USER_username_db;

                // Redirect based on user type
                if ($USER_type == 1) {
                    header('Location: ../admin/admin.html');
                    exit();
                } elseif ($USER_type == 2) {
                    header('Location: ../user/booking.php');
                    exit();
                }
            } else {
                // Show prompt if password is incorrect
                echo '<script>alert("Incorrect password. Please try again."); window.location.href = "login.html";</script>';
				exit();
            }
        } else {
            // Show prompt if username does not exist
            echo '<script>alert("Username does not exist. Please try again."); window.location.href = "login.html";</script>';
            exit();
        }
    } else {
        // Handle SQL preparation error
        die("Error preparing statement: " . $dbconn->error);
    }
}

$dbconn->close();
?>
