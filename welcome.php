<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Chào mừng</title>
    <link rel="stylesheet" href="assets/bootstrap.css">
    <style type="text/css">
    body {
        font: 14px sans-serif;
    }
    </style>
</head>

<body>
    <div class="page-header">
        <h1>Chào mừng <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <input type="text" name="search" value="<?php if (isset($_GET["search"])) {
                                                            echo $_GET["search"];
                                                        } ?>" class="form-control" placeholder="tên người dùng"> <br>
                <button type="submit" class="btn btn-primary center-block">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <br><br><br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">userame</th>
                <th scope="col">email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET["search"]) && !empty($_GET["search"])) {
                $value = $_GET["search"];
                $sql = "SELECT id,username,email FROM users WHERE username LIKE '%$value%'";
                $result = mysqli_query($link, $sql);

                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $items) {
            ?>
            <tr>
                <th scope="row"><?= $items["id"]; ?></th>
                <td><?= $items["username"]; ?></td>
                <td><?= $items["email"]; ?></td>
            </tr>
            <?php
                    }
                } else {
                    ?>
            <tr>
                <td colspan="3">Không tìm thấy</td>

            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>

    <p>
        <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
    </p>
</body>

</html>