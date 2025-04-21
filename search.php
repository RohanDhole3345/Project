<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

$response = ['success' => false, 'data' => []];

try {
    $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
    
    if (!empty($searchTerm)) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM hostels 
                  WHERE hostel_name LIKE :search 
                  OR city_village LIKE :search 
                  OR address LIKE :search 
                  OR landmark LIKE :search";
        
        $stmt = $db->prepare($query);
        $searchParam = "%" . $searchTerm . "%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $response['data'][] = [
                    'name' => $row['hostel_name'],
                    'location' => $row['city_village'],
                    'address' => $row['address'],
                    'landmark' => $row['landmark'],
                    'price' => $row['price_range'],
                    'rooms' => $row['rooms'],
                    'contact' => $row['phone']
                ];
            }
        } else {
            $response['message'] = 'No hostels found matching your search.';
        }
    } else {
        $response['message'] = 'Please enter a search term.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>