<?php

require_once "config.php";

$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["username"]))) {
        $username_err = "Tài khoản không được để trống";
    } else {

        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_username);


            $param_username = trim($_POST["username"]);


            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Tài khoản đã tồn tại";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Lỗi phía server!";
            }


            mysqli_stmt_close($stmt);
        }
    }


    if (empty(trim($_POST["password"]))) {
        $password_err = "Mật khẩu không được để trống";
    } else {
        $password = trim($_POST["password"]);
    }


    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Hãy xác nhận lại mật khẩu";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Mật khẩu không trùng khớp";
        }
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Email không được để trống";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {

        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);

            $param_username = $username;
            $param_password = md5($password);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {

                header("location: login.php");
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/bootstrap.css">
    <style type="text/css">
    body {
        font: 14px sans-serif;
    }

    .wrapper {
        width: 350px;
        padding: 20px;
    }
    </style>
</head>

<body>
    <div class="wrapper center-block">
        <h2>Đăng ký</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Tài khoản</label>
                <input type="text" name="username" autocomplete="off" class="form-control"
                    value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" autocomplete="off" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Mật khẩu</label>
                <input type="password" name="password" autocomplete="off" class="form-control"
                    value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Xác nhận lại mật khẩu</label>
                <input type="password" name="confirm_password" autocomplete="off" class="form-control"
                    value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Đăng ký">
            </div>
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a>.</p>
        </form>
    </div>
</body>

</html>