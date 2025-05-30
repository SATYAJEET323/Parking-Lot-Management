<?php
$conn = new mysqli('localhost', 'root', '', 'mini-project');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Check if the form is submitted for updating the Exit Date and Exit Time
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updateID = $_POST['updateID'];
    $exitDate = $_POST['exitDate'];
    $exitTime = $_POST['exitTime'];

    $stmt = $conn->prepare("UPDATE slot SET ExitDate = ?, ExitTime = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $exitDate, $exitTime, $updateID);
    $stmt->execute();
    $stmt->close();
}

$query = "SELECT * FROM slot WHERE ExitTime IS NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <style>
        body {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            font-family: 'Arial', sans-serif;
            color: #fff;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #fff;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #45a049;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Parking Check Out</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Vehicle Number</th>
            <th>Vehicle Type</th>
            <th>Owner Name</th>
            <th>Contact Number</th>
            <th>Parking Slot</th>
            <th>Entry Date</th>
            <th>Entry Time</th>
            <th>Exit Date</th>
            <th>Exit Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['VehicleNumber'] ?></td>
                <td><?= $row['VehicleType'] ?></td>
                <td><?= $row['OwnerName'] ?></td>
                <td><?= $row['ContactNumber'] ?></td>
                <td><?= $row['ParkingSlot'] ?></td>
                <td><?= $row['EntryDate'] ?></td>
                <td><?= $row['EntryTime'] ?></td>
                <td><?= $row['ExitDate'] ?></td>
                <td><?= $row['ExitTime'] ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="updateID" value="<?= $row['ID'] ?>">
                        <label for="exitDate">Exit Date:</label>
                        <input type="date" name="exitDate" required>
                        <label for="exitTime">Exit Time:</label>
                        <input type="time" name="exitTime" required>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>

<?php
$conn = new mysqli('localhost', 'root', '', 'mini-project');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$query = "SELECT * FROM slot WHERE ExitTime IS NOT NULL";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment/Bill</title>
    <style>
        body {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            font-family: 'Arial', sans-serif;
            color: #fff;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #fff;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        #sideNav {
            width: 250px;
            height: 100vh;
            position: fixed;
            right: -250px;
            top: 0;
            background: #c67ace;
            z-index: 2;
            transition: 0.5s;
        }

        nav ul li {
            list-style: none;
            margin: 50px 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
        }

        #menuBtn {
            width: 50px;
            height: 50px;
            text-align: center;
            position: fixed;
            right: 30px;
            top: 20px;
            z-index: 3;
            cursor: pointer;
        }

        #menuBtn img {
            width: 20px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<h2>Payment/Bill</h2>
<div id="sideNav">
            <nav>
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#feature">FEATURES</a></li>
                    <li><a href="#gallery">GALLERY</a></li>
                    <li><a href="./book.html">Book Now</a></li>
                    <li><a href="./contactUs.html">Contact Us</a></li>
                    <li><a href="./exit.php">Parking Check Out</a></li>
                    <li><a href="./show.php">Booking Summary</a></li>
                    <li><a href="./index.html">Log Out</a></li>
                </ul>
            </nav>
        </div>
        <div id="menuBtn">
            <img src="./images/menu.png" id="menu">
        </div>

<table>
    <thead>
        <tr>
            <th>Vehicle Number</th>
            <th>Vehicle Type</th>
            <th>Owner Name</th>
            <th>Contact Number</th>
            <th>Parking Slot</th>
            <th>Entry Date</th>
            <th>Entry Time</th>
            <th>Exit Date</th>
            <th>Exit Time</th>
            <th>Amount/Price</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['VehicleNumber'] ?></td>
                <td><?= $row['VehicleType'] ?></td>
                <td><?= $row['OwnerName'] ?></td>
                <td><?= $row['ContactNumber'] ?></td>
                <td><?= $row['ParkingSlot'] ?></td>
                <td><?= $row['EntryDate'] ?></td>
                <td><?= $row['EntryTime'] ?></td>
                <td><?= $row['ExitDate'] ?></td>
                <td><?= $row['ExitTime'] ?></td>
                <!-- Include logic to calculate and display amount/price -->
                <td><?= calculateAmount($row['EntryDate'], $row['EntryTime'], $row['ExitDate'], $row['ExitTime']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<script>
    var menuBtn = document.getElementById("menuBtn")
        var sideNav = document.getElementById("sideNav")
        var menu = document.getElementById("menu")

        sideNav.style.right = "-250px";

        menuBtn.onclick = function () {
            if (sideNav.style.right == "-250px") {
                sideNav.style.right = "0";
                menu.src = "images/cancel.png";
            } else {
                sideNav.style.right = "-250px";
                menu.src = "images/menu.png";
            }
        }
</script>
</body>
</html>

<?php
$conn->close();

function calculateAmount($entryDate, $entryTime, $exitDate, $exitTime) {
    $entryTimestamp = strtotime("$entryDate $entryTime");
    $exitTimestamp = strtotime("$exitDate $exitTime");

    // Calculate the duration in hours
    $durationInHours = ceil(($exitTimestamp - $entryTimestamp) / 3600);

    // Minimum charge for the first hour
    $minimumCharge = 20;

    // Additional charge for every subsequent hour
    $additionalChargePerHour = 5;

    // Calculate the total amount
    $totalAmount = $minimumCharge + ($additionalChargePerHour * max(0, $durationInHours - 1));

    return "₹" . number_format($totalAmount, 2);
}
?>
