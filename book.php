<?php
    date_default_timezone_set('Asia/Kolkata');
    $VehicleNumber = $_POST['VehicleNumber'];
    $VehicleType = $_POST['VehicleType'];
    $OwnerName = $_POST['OwnerName'];
    $ContactNumber = $_POST['ContactNumber'];
    $City = $_POST['City'];
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i");

    $conn = new mysqli('localhost', 'root', '', 'mini-project');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Define total slots
    $allSlots = range(1, 100);
    
    // Fetch used slots
    $query = "SELECT DISTINCT ParkingSlot FROM slot";
    $result = $conn->query($query);
    $usedSlots = array();
    while ($row = $result->fetch_assoc()) {
        $usedSlots[] = $row['ParkingSlot'];
    }
    
    // Find remaining slots
    $remainingSlots = array_diff($allSlots, $usedSlots);

    // Check for available slots in database
    $queryAvailableSlot = "SELECT MIN(ParkingSlot) AS availableSlot FROM slot WHERE ExitTime IS NOT NULL";
    $resultAvailableSlot = $conn->query($queryAvailableSlot);
    $row = $resultAvailableSlot->fetch_assoc();
    $availableSlot = $row['availableSlot'];

    // If no available slot from exited vehicles, pick the next free slot
    if ($availableSlot === null || empty($availableSlot)) {
        if (!empty($remainingSlots)) {
            $availableSlot = min($remainingSlots);
        } else {
            echo '<script>alert("No available parking slots at the moment. Please try later."); window.location.href = "./book.html";</script>';
            exit();
        }
    }

    // Insert the booking record
    $stmt = $conn->prepare("INSERT INTO slot (VehicleNumber, VehicleType, OwnerName, ContactNumber, ParkingSlot, EntryDate, EntryTime, City) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisss", $VehicleNumber, $VehicleType, $OwnerName, $ContactNumber, $availableSlot, $currentDate, $currentTime, $City);

    if ($stmt->execute()) {
        echo '<script>alert("Your slot has been booked. Parking Slot: ' . $availableSlot . '"); window.location.href = "./show1.php";</script>';
    } else {
        echo '<script>alert("Error booking slot. Please try again later."); window.location.href = "./book.html";</script>';
    }

    $stmt->close();
    $conn->close();
?>
