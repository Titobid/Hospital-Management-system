<?php
session_start();
include 'connection.php';

if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type == "patients") {
        $table = "patients";
        include '../otherpages/pheader.html';
    }

    if (isset($_POST["cpButton"])) {
        $oldpassword = md5($_POST['oldpassword']);
        $newpassword = md5($_POST['newpassword']);

        if ($oldpassword != $password)
            print("<p>Old password does not match</p>");
        else {
            $sql = $mysqli->prepare("UPDATE $table SET password=? WHERE username=?");
            $sql->bind_param('ss', $newpassword, $username);
            $sql->execute();

            if ($sql->errno)
                print("<p>Query failed</p>");
            else {
                print("<p>Password successfully changed</p>");
                $_SESSION['password'] = $newpassword;
            }
        }
    } else {
        include '../otherpages/changePasswordForm.html';
        include '../otherpages/patients.html';
    }
}
$mysqli->close();
?>