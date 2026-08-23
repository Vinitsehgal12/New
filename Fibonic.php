/*********loginotp.php***********/

<?php

session_start();

include "db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
// echo'<pre>';
// echo $password;
// echo'</pre>';

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: dashboard_otp.php");
            exit();

        } else {

            $error = "Invalid password.";

        }

    } else {

        $error = "Email not found.";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body>

<h2>Login</h2>

<?php

if ($error != "") {
    echo "<p style='color:red;'>$error</p>";
}

?>

<form method="POST">

    <label>Email</label><br>

    <input
        type="email"
        name="email"
        required
    >

    <br><br>

    <label>Password</label><br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>

    <button type="submit" name="login">
        Login
    </button>

</form>

<br>

<a href="forgot_password.php">
    Forgot Password?
</a>

</body>
</html>
/************db.php***********/
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "php_login"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>
/****************logout_otp.php***************/
	<?php

session_start();

session_destroy();

header("Location: loginotp.php");

exit();

?>
/***********forgot_password.php************/
<?php

include "db.php";

$error = "";

if (isset($_POST['forgot'])) {

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $query = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        header(
            "Location: verify_otp.php?email="
            . urlencode($email)
        );

        exit();

    } else {

        $error = "Email not found.";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>

<body>

<h2>Forgot Password</h2>

<?php

if ($error != "") {
    echo "<p style='color:red;'>$error</p>";
}

?>

<form method="POST">

    <label>Enter Email</label><br>

    <input
        type="email"
        name="email"
        required
    >

    <br><br>

    <button type="submit" name="forgot">
        Continue
    </button>

</form>

<br>

<a href="login.php">
    Back to Login
</a>

</body>
</html>

/******************reset_password.php********************/
<?php

include "db.php";

$error = "";

$email = $_GET['email'] ?? $_POST['email'] ?? "";

if (isset($_POST['reset'])) {

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        $error = "Passwords do not match.";

    } elseif (strlen($password) < 8) {

        $error = "Password must be at least 8 characters.";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $query = "
            UPDATE users
            SET password='$hashed_password',
                otp=NULL
            WHERE email='$email'
        ";

        if (mysqli_query($conn, $query)) {

            header("Location: loginotp.php");
            exit();

        } else {

            $error = "Password update failed.";

        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>

<body>

<h2>Reset Password</h2>

<?php

if ($error != "") {
    echo "<p style='color:red;'>$error</p>";
}

?>

<form method="POST">

    <input
        type="hidden"
        name="email"
        value="<?= htmlspecialchars($email) ?>"
    >

    <label>New Password</label><br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>

    <label>Confirm Password</label><br>

    <input
        type="password"
        name="confirm_password"
        required
    >

    <br><br>

    <button type="submit" name="reset">
        Reset Password
    </button>

</form>

</body>
</html>

/***************verify_otp.php*****************/
<?php

include "db.php";

$error = "";

$email = $_GET['email'] ?? $_POST['email'] ?? "";

if (isset($_POST['verify'])) {

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $otp = mysqli_real_escape_string(
        $conn,
        $_POST['otp']
    );

    $query = "
        SELECT *
        FROM users
        WHERE email='$email'
        AND otp='$otp'
    ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        header(
            "Location: reset_password.php?email="
            . urlencode($email)
        );

        exit();

    } else {

        $error = "Invalid OTP.";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
</head>

<body>

<h2>Verify OTP</h2>



<?php

if ($error != "") {
    echo "<p style='color:red;'>$error</p>";
}

?>

<form method="POST">

    <input
        type="hidden"
        name="email"
        value="<?= htmlspecialchars($email) ?>"
    >

    <label>OTP</label><br>

    <input
        type="text"
        name="otp"
        maxlength="6"
        required
    >

    <br><br>

    <button type="submit" name="verify">
        Verify OTP
    </button>

</form>

</body>
</html>
/***************dashboard_otp.php*******************/
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?>!</h1>

<p>
    You are successfully logged in.
</p>

<p>
    Email:
    <?= htmlspecialchars($_SESSION["user_email"]) ?>
</p>

<a href="logout_otp.php">Logout</a>

</body>
</html>
