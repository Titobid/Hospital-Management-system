<?php
session_start();
include("connection.php");


if (empty($_POST['username']) || empty(trim($_POST['password']))) {
    header('Location: ../index.html');

} else {
    if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        $sql = $mysqli->prepare("SELECT * FROM doctors WHERE username=? AND password=?");
        $sql->bind_param('ss', $username, $password);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows == 1) {
            $_SESSION['type'] = "doctors";
            include '../otherpages/dheader.html';
            //print("<p>Welcome $username</p>");
            include '../otherpages/doctors.html';


        } else {
            $sql = $mysqli->prepare("SELECT * FROM patients WHERE username=? AND password=?");
            $sql->bind_param('ss', $username, $password);
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows == 1) {
                $_SESSION['type'] = "patients";
                include '../otherpages/pheader.html';
                //print("<p class="info" >Welcome $username</p>");
                include '../otherpages/patients.html';


            } else {
                $sql = $mysqli->prepare("SELECT * FROM pharmacy WHERE username=? AND password=?");
                $sql->bind_param('ss', $username, $password);
                $sql->execute();
                $result = $sql->get_result();

                if ($result->num_rows == 1) {
                    $_SESSION['type'] = "pharmacy";
                    include '../otherpages/phheader.html';
                    //print("<p class="info" >Welcome $username</p>");
                    include '../otherpages/pharmacy.html';


                } else {
                    header('Location: ../index.html');
                }
            }

        }





    }

}

$mysqli->close();


?>