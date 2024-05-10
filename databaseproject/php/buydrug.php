<?php
session_start();
include 'connection.php';
$con = new mysqli("localhost", "prescriptions", "", "prescriptions");
if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];
    if ($type != "patients") {
        include '../otherpages/pheader.html';
        echo "<script type='text/jscript'>alert('Insufficient previliges')</script>";
    } else {
        include '../otherpages/pheader.html';
        include '../otherpages/patients.html';
        include '../otherpages/buydrug.html';
        echo "<script type='text/jscript'>console.log('Hello world!')</script>";

        if (isset($_POST['buybutton'])) {
            $buydrug = $_POST['buydrug'];
            $buydose = $_POST['buydose'];
            $sql = $mysqli->prepare("SELECT * FROM drugs WHERE drugs.dname=?");
            $sql->bind_param('s', $buydrug);
            $sql->execute();
            $result = $sql->get_result();

            echo "<script type='text/jscript'>console.log('button pressed')</script>";
            if ($result->num_rows == 1) {
                echo "<script type='text/jscript'>console.log('button2 pressed')</script>";
                $sql2 = "SELECT amount FROM drugs WHERE drugs.dname='$buydrug'";
                $drugname = $con->query($sql2);
                while ($row = $drugname->fetch_assoc()) {
                    $sda = $row["amount"];
                    if ($buydose > $sda) {
                       
                echo "<script type='text/jscript'>alert('Not enough stocks in the Pharmay. Chaeck back later !')</script>";
                    }else{
                        $sda= $sda-$buydose;
                        
                        $sql = $mysqli->prepare("UPDATE `drugs` SET `amount`='$sda' WHERE drugs.dname='$buydrug';");
                        //$sql->bind_param('s', $newpassword, $username);
                        $sql->execute();
                        
                echo "<script type='text/jscript'>alert('You just bought the drug')</script>";
                    }

                }

            }else{
                
                echo "<script type='text/jscript'>alert('This Drug is Not available in the Pharmacy yet !')</script>";
            }
        }
    }
}


$mysqli->close();
?>