<?php

// add Edit, Delete, Status functionality, Bootstrap, correct alerts
include 'db.php';
session_start();

if (isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['id'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $last = new DateTime();
        $lastLoginDate = $last->format("Y-m-d H:i:s");
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, username, 
    password, last_login_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $username, $password, $lastLoginDate]);
        $success = "Data Inserted Successfully";
    }

    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll();

    foreach ($users as $user) {
        $loginDate = new DateTime($user['last_login_date']);
        $today = new DateTime();
        $interval = $loginDate->diff($today);

        $status = ($interval->days <= 7) ? 1 : 0;

        if ($user['status'] != $status) {
            $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
            $stmt->execute([$status, $user['id']]);
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-DQvkBjpPgn7RC31MCQoOeC9TI2kdqa4+BSgNMNj8v77fdC77Kj5zpWFTJaaAoMbC" crossorigin="anonymous">
        <style>
            .container {
                margin: 1rem;
                padding: 1rem;
                border: 1px solid #333;
                border-radius: 10px;
            }

            .user {
                float: right;
            }

            .alert p {
                color: green;

            }
        </style>
    </head>

    <body>
        <div class="container">
            <p class="user">Hello: <?php if (isset($_SESSION['firstName']) && isset($_SESSION['lastName'])) {
                                        echo $_SESSION['firstName'] . " " . $_SESSION['lastName'];
                                    } ?>
                <button class="btn btn-primary" onclick="location.href='logout.php';">Logout</button>
            </p>
            <br>
            <div class="content">
                <h3>Welcome to the Dashboard</h3>
            </div>
            <br>

            <?php
            if (isset($success)) {
            ?>
                <div class="alert">
                    <p><?php echo $success; ?></p>
                </div>

            <?php
            }
            ?><br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="border container">
                <label for="">First Name</label><br>
                <input type="text" name="firstName" required><br>
                <label for="">Last Name</label><br>
                <input type="text" name="lastName" required><br>
                <label for="">Username</label><br>
                <input type="text" name="username" required><br>
                <label for="">Password</label><br>
                <input type="text" name="password" required><br><br>
                <input type="submit" class="btn btn-primary">
            </form>
            <br>
            <table class="table">
                <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Status</th>
                </thead>
                <?php foreach ($users as $user): ?>
                    <tbody>
                        <td><?php echo $user['firstName']; ?></td>
                        <td><?php echo $user['lastName']; ?> </td>
                        <td><?php echo $user['username']; ?> </td>
                        <td><?= $user['status'] == 1 ? 'Active' : 'Not Active'; ?> </td>
                        <td>
                            <button class="btn btn-primary" onclick="location.href='edit.php?id=<?php echo $user['id']; ?>';">Edit</button>
                            <button class="btn btn-primary" onclick="location.href='delete.php?id=<?php echo $user['id']; ?>';">Delete</button>
                        </td>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-YUe2LzesAfftltw+PEaao2tjU/QATaW/rOitAq67e0CT0Zi2VVRL0oC4+gAaeBKu" crossorigin="anonymous"></script>
    </body>

    </html>


<?php
} else {
    header("Location: index.php");
}
