<?php
include "db.php";
session_start();

<<<<<<< HEAD
if (isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['id'])) {
    header("Location: dashboard.php");
=======
// setcookie("section_c", "section_c_lab", time()+3600, "/");
// echo $_COOKIE["section_c"];

if(isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['id'])){

    header("location: dashboard.php");
>>>>>>> c0e3f1fae2a356ee2d651a6a37b2618bfec6dc7e
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username ");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['status'] == 1) {
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['lastName'] = $user['lastName'];

                $last = new DateTime();
                $last_login = $last->format("Y-m-d H:i:s");
                $stmt2 = $pdo->prepare("UPDATE users SET last_login_date = ? WHERE id = ?");
                $stmt2->execute([$last_login, $user['id']]);

                header("location:dashboard.php");
            } else {
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            $_SESSION['error'] = "This User has been deactivated";
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous"> -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgb(228, 228, 228);
            height: 100vh;
            background: url("bg.png") no-repeat;
            background-size: cover;
        }

        .login {
            background-color: #fff;
            padding: 1rem;
            width: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }

        input {
            border: 1px solid #000;
            border-radius: 5px;
            height: 30px;
            width: 250px;
        }

        .btn {
            color: #fff;
            background-color: #000;
        }
    </style>
</head>

<body>
    <div class="login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h3>Login</h3>
            <?php if ($error): ?>
                <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <label for="username">Username</label><br>
            <input type="text" name="username"><br>
            <label for="password">Password</label><br>
            <input type="password" name="password"><br><br>
            <input type="submit" value="Login" class="btn">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
</body>

</html>