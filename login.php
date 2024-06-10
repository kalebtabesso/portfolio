<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get the submitted username and password
        $submitted_username = $_POST['username'];
        $submitted_password = $_POST['password'];

        // Connect to the database
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "inventory";
        $port = 8889;

        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query the database for the user
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $submitted_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($submitted_password, $user['password'])) {
                // Password is correct, redirect to the main page
                header('Location: index.html');
                exit;
            } else {
                // Incorrect password
                $error = "Incorrect password";
            }
        } else {
            // User not found
            $error = "User not found";
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    } else {
        $error = "Username or password not set";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Error</title>
</head>
<body>
    <h1><?php echo isset($error) ? $error : ''; ?></h1>
    <a href="index.html">Go back to the login page</a>
</body>
</html>
x       