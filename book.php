<?php
    date_default_timezone_set('Asia/Kolkata');

    $VehicleNumber = strtoupper(trim($_POST['VehicleNumber']));
    $VehicleType = $_POST['VehicleType'];
    $OwnerName = $_POST['OwnerName'];
    $ContactNumber = trim($_POST['ContactNumber']);
    $City = $_POST['City'];
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i");

    // Validation
    $vehiclePattern = "/^[A-Z]{2}[0-9]{2}[A-Z]{1,2}[0-9]{4}$/";
    $contactPattern = "/^[7-9][0-9]{9}$/";

    if (!preg_match($vehiclePattern, $VehicleNumber)) {
        echo '<script>alert("Invalid Vehicle Number. It should be like MH12AB1234"); window.history.back();</script>';
        exit();
    }

    if (!preg_match($contactPattern, $ContactNumber)) {
        echo '<script>alert("Invalid Contact Number. It should be 10 digits"); window.history.back();</script>';
        exit();
    }

    $conn = new mysqli('localhost','root','','mini-project');

    $allSlots = range(1, 100);
    $query = "SELECT DISTINCT ParkingSlot FROM slot";
    $result = $conn->query($query);
    $usedSlots = array();

    while ($row = $result->fetch_assoc()) {
        $usedSlots[] = $row['ParkingSlot'];
    }

    $remainingSlots = array_diff($allSlots, $usedSlots);

    if($conn->connect_error){
        die('Connection Failed :  '.$conn->connect_error);
    } else {
        $queryAvailableSlot = "SELECT MIN(ParkingSlot) AS availableSlot FROM slot WHERE ExitTime IS NOT NULL";
        $resultAvailableSlot = $conn->query($queryAvailableSlot);
        $row = $resultAvailableSlot->fetch_assoc();
        $availableSlot = $row['availableSlot'];

        if ($availableSlot === null) {
            $queryNextSlot = "SELECT MAX(ParkingSlot) + 1 AS nextSlot FROM slot";
            $resultNextSlot = $conn->query($queryNextSlot);
            $rowNextSlot = $resultNextSlot->fetch_assoc();
            $availableSlot = $rowNextSlot['nextSlot'];
        }

        $stmt = $conn->prepare("INSERT INTO slot (VehicleNumber, VehicleType, OwnerName, ContactNumber, ParkingSlot, EntryDate, EntryTime, City) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $VehicleNumber, $VehicleType, $OwnerName, $ContactNumber, $availableSlot, $currentDate, $currentTime, $City);

        if ($stmt->execute()) {
            $conn->commit();
            echo '<script>alert("Your slot has been booked. Parking Slot: ' . $availableSlot . '");
                window.location.href = "./show1.php";</script>';
        } else {
            throw new Exception("Error booking slot. Please try again later.");
        }
    }

    $conn->close();
?>
