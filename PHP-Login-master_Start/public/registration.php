<?php
require_once('../src/DBconnect.php'); // working DB connection
require_once('../template/newheader.php');
require "../common.php";

// variables and it is set to empty values
$username = $password = "";
$usernameError = $passwordError = $registerSuccess = "";


// checks if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Username"])) {
        $usernameError = "Username is required";
    } else {
        $username = test_input($_POST["Username"]);
    }

    if (empty($_POST["Password"])) {
        $passwordError = "Password is required";
    } else {
        $password = test_input($_POST["Password"]);
    }

    
    if (empty($usernameError) && empty($passwordError)) {
        // checks if the user already exists
        $statement = $connection->prepare("SELECT * FROM users WHERE username = ?");
        $statement->execute([$username]);

        if ($statement->rowCount() > 0) {
            $usernameError = "Username already taken";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $statement = $connection->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

            if ($statement->execute([$username, $hashedPassword])) {
                $registerSuccess = "Registration successful! <a href='login.php'>Click here to login</a>";
                $username = $password = ""; 
            } else {
                $passwordError = "Error registering user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../css/signin.css">
    <title>Register</title>
</head>
<body>
<div class="container">
    <form action="" method="post" name="Register_Form" class="form-signin">
        <h2 class="form-signin-heading">Register a new account</h2>

        <?php if (!empty($registerSuccess)) echo "<p class='success'>$registerSuccess</p>"; ?>

        <label for="inputUsername">Username</label>
        <input name="Username" type="text" id="inputUsername" class="form-control" placeholder="Choose a username" value="<?php echo htmlspecialchars($username); ?>" required autofocus>
        <span class="error"><?php echo $usernameError; ?></span>

        <label for="inputPassword">Password</label>
        <input name="Password" type="password" id="inputPassword" class="form-control" placeholder="Choose a password" required>
        <span class="error"><?php echo $passwordError; ?></span>

        <button name="Submit" value="Register" class="button" type="submit">Register</button>
    </form>
</div>
</body>
</html>
