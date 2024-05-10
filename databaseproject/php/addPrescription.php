<?php
session_start();
include 'connection.php';

if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type != "doctors") {
        include '../otherpages/pheader.html';
        print("<p>Insufficient privileges</p>");
    } else {
        include '../otherpages/dheader.html';

        if (isset($_POST["addprescriptionbutton"])) {
            $Date = $_POST['date'];
            $Dose = $_POST['dose'];
            $Drugname = $_POST['dname'];
            $Patientname = $_POST['username'];

            $sql = $mysqli->prepare("INSERT INTO prescribe(date, dose, dname, username) VALUES (?, ?, ?, ?)");
            // user name is the name of the patient
            $sql->bind_param('ssss', $Date, $Dose, $Drugname, $Patientname);
            $sql->execute();

            if ($sql->errno) {
                echo "<script type='text/jscript'>alert('Internal Error')</script>";
                //include '../otherpages/dheader.html';
                include '../otherpages/addprescription.html';
                include '../otherpages/doctors.html';
            } else {
                echo "<script type='text/jscript'>alert('Prescription Added')</script>";
                //include '../otherpages/dheader.html';
                include '../otherpages/doctors.html';
                include '../otherpages/addprescription.html';

            }
        } else {
            include '../otherpages/doctors.html';
            include '../otherpages/addprescription.html';

        }
    }
}
$mysqli->close();
?>