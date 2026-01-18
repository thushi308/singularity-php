<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");

header("Pragma: no-cache");

$valid_username="adminstalin";
$valid_password="Alohomora";
$valid_name="Comrade";
$credentials=file("credentials.txt");
$length=count($credentials);
$count=0;
$flag=0;
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username == $valid_username && $password == $valid_password) {
        $flag=1;
        $_SESSION["username"] = $valid_name;
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
    }
    while($count<$length) {
        $valid_name= trim($credentials[$count]);
        $count=$count+1;
        $valid_username = trim($credentials[$count]);
        $count=$count+1;
        $valid_password =trim( $credentials[$count]);
        $count=$count+1;
        if ($username == $valid_username && $password == $valid_password) {
            $flag=1;
            $_SESSION["username"] = $valid_name;
            header("Location: welcome.php"); // Redirect to a welcome page
            exit();
        } 
        
    }
    if($flag==0){
        $_SESSION["error"] = "Invalid username or password";
    }
}
$error="";
if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
    unset($_SESSION["error"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (isset($error)) {
            echo "<p style='color: red;'>".$error."</p>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

