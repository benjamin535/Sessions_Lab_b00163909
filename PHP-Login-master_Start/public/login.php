<?php
require_once('../template/newheader.php');
require_once('../src/DBconnect.php');
require "../common.php";  

// variables
$username = $password = "";
$usernameError = $passwordError = "";

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
        try {
            // SQL query to find the user by username
            $sql = "SELECT * FROM users WHERE username = :username";
            $statement = $connection->prepare($sql);
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Correct password, user is authenticated
                $_SESSION['Username'] = $username;
                $_SESSION['Active'] = true;
                header("Location: index.php");
                exit;
            } else {
                $passwordError = "Incorrect Username or Password";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>
?>

<!DOCTYPE html>
<html lang="en">

<div class="container">
    <form action="" method="post" name="Login_Form" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        
        <label for="inputUsername">Username</label><br>
        <input name="Username" type="text" id="inputUsername" class="form-control" placeholder="Username" required autofocus><br>
        <span class="error"><?php echo $usernameError; ?></span><br><br>

        <label for="inputPassword">Password</label><br>
        <input name="Password" type="password" id="inputPassword" class="form-control" placeholder="Password" required><br>
        <span class="error"><?php echo $passwordError; ?></span><br><br>
        
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        
        <button name="Submit" value="Login" class="button" type="submit">Sign in</button>
    </form>

    <p>Don't have an account? <a href="registration.php">Register here</a></p>
</div>
</body>
</html>
