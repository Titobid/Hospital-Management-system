<?php
session_start();
include 'connection.php';
if (empty($_SESSION['username']) || empty($_SESSION['password']))
    print("Access to database denied");
else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];
    if ($type == "patient") {
    }
    include '../otherpages/pheader.html';
    include '../otherpages/patients.html';
    if (isset($_POST["searchButton"])) {
        $sql = $mysqli->prepare("SELECT prescribe.date, prescribe.dose, prescribe.dname FROM prescribe WHERE prescribe.username='$username';");
        $sql->execute();
        $result = $sql->get_result();

        if (!$result)
            print("<p>Select query failed</p>");
        else {
            if ($result->num_rows == 0) {
                include '../otherpages/pheader.html';
                echo "<script type='text/jscript'>alert('No prescriptions are issued')</script>";
                include '../otherpages/patients.html';
            } else {

                ?>

                <style>
                    table {

                        text-align: center;
                        width: 500px;


                        margin-left: 35%;
                        margin-right: 37%;
                    }
                </style>

                <br><br>

                <form name="deleteBooks" method="post" action="<?php $PHP_SELF ?>">
                    <table>
                        <?php
                        print("<tr><th>Date</th><th>Dose</th><th><b>Drug Name</b></th></tr>");
                        while ($row = $result->fetch_object()) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row->date; ?>
                                </td>
                                <td>
                                    <?php echo $row->dose; ?>
                                </td>
                                <td>
                                    <?php echo $row->dname; ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <?php
            }
        }

    } else {
        include '../otherpages/searchForm.html';

    }
}
$mysqli->close();
?>