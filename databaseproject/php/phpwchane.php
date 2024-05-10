<?php
session_start();
include 'connection.php';

if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type == "pharmacy") {
        $table = "pharmacy";
        include '../otherpages/phheader.html';
    }

    if (isset($_POST["cpButton"])) {
        $oldpassword = md5($_POST['oldpassword']);
        $newpassword = md5($_POST['newpassword']);

        if ($oldpassword != $password){
        echo "<script type='text/jscript'>alert('Old password does not match')</script>";
        //include '../otherpages/dheader.html';
        include '../otherpages/pharmacy.html';
        include '../otherpages/changePasswordForm.html';}
        else {
            $sql = $mysqli->prepare("UPDATE $table SET password=? WHERE username=?");
            $sql->bind_param('ss', $newpassword, $username);
            $sql->execute();

            if ($sql->errno)
                print("<p>Query failed</p>");
            else {
                echo "<script type='text/jscript'>alert('Password successfully changed')</script>";
                //include '../otherpages/dheader.html';
                include '../otherpages/pharmacy.html';
                
                $_SESSION['password'] = $newpassword;
            }
        }
    } else {
        include '../otherpages/pharmacy.html';
        include '../otherpages/changePasswordForm.html';

    }
}
$mysqli->close();
?>