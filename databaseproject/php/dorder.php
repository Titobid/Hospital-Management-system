<?php
session_start();
include 'connection.php';

if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type != "pharmacy") {
        include '../otherpages/phheader.html';

        echo "<script type='text/jscript'>alert('Insufficient previliges')</script>";
    } else {
        include '../otherpages/phheader.html';

        if (isset($_POST["orderbutton"])) {
            $dname = $_POST['dname'];
            $Dose = $_POST['dose'];

            $sql = $mysqli->prepare("SELECT dname FROM drugs WHERE drugs.dname='$dname';");
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows == 0) {
                $sql = $mysqli->prepare("INSERT INTO drugs(dname, amount) VALUES (?, ?)");
                // user name is the name of the patient
                $sql->bind_param('ss', $dname, $Dose);
                $sql->execute();
                echo "<script type='text/jscript'>alert('This Drug is not in the inventory. do you want to add it as a new Drug ?')</script>";
            }

            $sql = $mysqli->prepare("UPDATE drugs SET amount=? WHERE dname='$dname';");
            // user name is the name of the patient
            $sql->bind_param('s', $Dose);
            $sql->execute();

            if ($sql->errno) {
                echo "<script type='text/jscript'>alert('Internal Error')</script>";
                //include '../otherpages/dheader.html';
                include '../otherpages/pharmacy.html';
                include '../otherpages/dorder.html';
            } else {
                echo "<script type='text/jscript'>alert('Order Placed / Drug inventory updated')</script>";
                //include '../otherpages/dheader.html';
                include '../otherpages/pharmacy.html';
                include '../otherpages/dorder.html';

            }
        } else {
            include '../otherpages/pharmacy.html';
            include '../otherpages/dorder.html';

        }
    }


}
$mysqli->close();
?>