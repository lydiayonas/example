<?php
include 'db.php';

$user = null;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $user = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $id = $_POST["id"];

    $stmt = $pdo->prepare("UPDATE users SET firstName=?, lastName=?, username=?, password=? WHERE id=?");
    $stmt->execute([$firstName, $lastName, $username, $password, $id]);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id'] ?>" />
        <label for="">First Name</label><br>
        <input type="text" name="firstName" value="<?php echo $user['firstName'] ?>" required><br>
        <label for="">Last Name</label><br>
        <input type="text" name="lastName" value="<?php echo $user['lastName'] ?>" required><br>
        <label for="">Username</label><br>
        <input type="text" name="username" value="<?php echo $user['username'] ?>" required><br>
        <label for="">Password</label><br>
        <input type="text" name="password" required><br><br>
        <input type="submit">
    </form>
</body>

</html>