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
		print("<p>Insufficient privileges to delete books from catalogue.</p>");
	} else {
		include '../otherpages/dheader.html';

		if (isset($_POST["deleteprescriptionbutton"])) {
			$dprescriptions = $_POST['dprescriptions'];
			$count = count($dprescriptions);

			for ($i = 0; $i < $count; $i++) {
				$sql = $mysqli->prepare("DELETE FROM prescribe WHERE username=?");
				$sql->bind_param('s', $dprescriptions[$i]);
				$sql->execute();
				if ($sql->errno)
					print("Delete query failed");
			}
			if ($count == 1) {
				echo "<script type='text/jscript'>alert('$count prescription removed')</script>";
				include '../otherpages/doctors.html';
			} else {
				echo "<script type='text/jscript'>alert('$count prescriptions removed')</script>";
				include '../otherpages/doctors.html';
			}
		} else {
			$sql = "SELECT * FROM prescribe";
			$result = $mysqli->query($sql);
			if (!$result)
				print("<p>Select query failed</p>");
			else {
				if ($result->num_rows == 0)
					print("<p>There are no registered prescriptions</p>");
				else {
					include '../otherpages/doctors.html';
					?>
					<style>
						h1 {
							text-align: center;
						}
					</style>
					<h1>Select prescriptions to delete</h1>


					<form name="deleteBooks" method="post" action="<?php $PHP_SELF ?>">



						<style>
							table {

								text-align: center;


								padding: 5px;
								margin-left: 37%;
								margin-right: 37%;
							}
						</style>
						<table required>
							<tr>
								<th></th>
								<th>Date</th>
								<th>     Dose</th>
								<th>     DrugName</th>
								<th>      PatientName</th>
							</tr><br>
							<?php
							while ($row = $result->fetch_object()) {
								echo '<tr>';
								$username = $row->username;
								// <td><input type="checkbox" name="dprescriptions[]" value="$username" ></td>
								print("<td><input type=\"checkbox\" name=\"dprescriptions[]\" value=\"$username\"></td>");

								echo '<td>' . $row->date . '</td>';
								echo '<td>' . $row->dose . '</td>';
								echo '<td>' . $row->dname . '</td>';
								echo '<td>' . $row->username . '</td>';
								echo '</tr>';
								print("\n");
							}

							?>
							<style>
								#buttons {
									margin-left: 45%;
									margin-right: 45%;
									width: 220px;
									height: 30px;
									border: 0.5px solid black;
									outline: none;
									border-radius: 10px;


								}

								#buttons:hover {
									cursor: pointer;
									background: whitesmoke;
									font-weight: bold;
								}
							</style>
						</table><br /><input id="buttons" type="submit" value="Delete prescriptions"
							name="deleteprescriptionbutton">
					</form>
					<?php
				}
			}
		}
	}

}
$mysqli->close();
?>