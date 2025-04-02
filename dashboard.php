<?php 
include 'db.php'; 
session_start();
if(isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['id'])){

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $firstName = $_POST['firstName']; 
        $lastName = $_POST['lastName']; 
        $username = $_POST['username']; 
        $password = $_POST['password']; 
        $password = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, username, 
    password) VALUES (?, ?, ?, ?)"); 
        $stmt->execute([$firstName, $lastName, $username, $password]); 
        $success = "Data Inserted Successfully";    
    } 

    $stmt = $pdo->query("SELECT * FROM users"); 
    $users = $stmt->fetchAll(); 
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container{
            margin: 1rem;
            padding: 1rem;
            border: 1px solid #333;
            border-radius: 10px;
        }
        .user{
            float: right;
        }
        .alert p{
            color: green;

        }
    </style>
</head>
<body>
    <div class="container">
        <p class="user">Hello: <?php if(isset($_SESSION['firstName']) && isset($_SESSION['lastName'])){
            echo $_SESSION['firstName'] . " ".$_SESSION['lastName'];
        } ?>
    <a href="logout.php">Logout</a>    
    </p>
        <br>
        <div class="content">
            <h3>Welcome to the Dashboard</h3>
        </div>
        <br>

        <?php 
            if(isset($success)){
                ?>
            <div class="alert">
                <p><?php echo $success; ?></p>
            </div>

            <?php
            }
        ?><br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="">First Name</label><br>
            <input type="text" name="firstName" required><br>
            <label for="">Last Name</label><br>
            <input type="text" name="lastName" required><br>
            <label for="">Username</label><br>
            <input type="text" name="username" required><br>
            <label for="">Password</label><br>
            <input type="text" name="password" required><br><br>
            <input type="submit">
        </form>
        <br>
        <table> 
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
                <td><?=  $user['status'] == 1 ? 'Active' : 'Not Active'; ?> </td>
                
                
            </tbody> 
            <?php endforeach; ?> 
        </table> 
    </div>
    
</body>
</html>

<?php 
}else{
    header("location: index.php");
}


?>