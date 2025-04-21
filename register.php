<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

$response = ['success' => false, 'message' => ''];

try {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO hostels 
                  (hostel_name, owner_name, phone, email, address, city_village, landmark, rooms, price_range, description) 
                  VALUES 
                  (:hostel_name, :owner_name, :phone, :email, :address, :city_village, :landmark, :rooms, :price_range, :description)";
        
        $stmt = $db->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':hostel_name', $data->hostelName);
        $stmt->bindParam(':owner_name', $data->ownerName);
        $stmt->bindParam(':phone', $data->phone);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':address', $data->address);
        $stmt->bindParam(':city_village', $data->cityVillage);
        $stmt->bindParam(':landmark', $data->landmark);
        $stmt->bindParam(':rooms', $data->rooms);
        $stmt->bindParam(':price_range', $data->price);
        $stmt->bindParam(':description', $data->description);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Hostel registered successfully!';
        } else {
            $response['message'] = 'Unable to register hostel. Please try again.';
        }
    } else {
        $response['message'] = 'No data received.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>