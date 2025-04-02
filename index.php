<?php

setcookie("lab_class", "lab class value", time()+3600, "/");

include "db.php";
session_start();
if(isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['id'])){
    header("location: dashboard.php");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];

        $last_login = date('Y-m-d H:i:s'); // Use a standard format for DATETIME
        $stmt2 = $pdo->prepare("UPDATE users SET last_login_date = ? WHERE id = ?");
        $stmt2->execute([$last_login, $user['id']]);

        header("location:dashboard.php");
         
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            background-color:rgb(228, 228, 228);
            height: 100vh;
            background: url("bg.png") no-repeat;
            background-size: cover;
        }
        .login{
            background-color: #fff;
            padding: 1rem;
            width: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }
        input{
            border: 1px solid #000;
            border-radius: 5px;
            height: 30px;
            width: 250px;
        }
        .btn{
            color: #fff;
            background-color: #000;
        }
    </style>
</head>
<body>
<div class="login">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h3>Login</h3> 
        <label for="username">Username</label><br>
        <input type="text" name="username"><br>
        <label for="password">Password</label><br>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login" class="btn">
    </form>
</div>

    
</body>
</html>