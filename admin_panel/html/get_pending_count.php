<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../connect/connect.php';

$count = 0;
if ($result = mysqli_query($connect, "SELECT COUNT(*) as count FROM reviews WHERE status = 1")) {
    $row = mysqli_fetch_assoc($result);
    $count = (int)$row['count'];
}

echo json_encode([
    'success' => true,
    'count' => $count
]);













// require_once __DIR__ . '/../../connect/connect.php';

// header('Content-Type: application/json');

// try {
//     $result = mysqli_query($connect, "SELECT COUNT(*) as count FROM reviews WHERE status = 1");
//     if (!$result) {
//         throw new Exception(mysqli_error($connect));
//     }
    
//     $row = mysqli_fetch_assoc($result);
//     echo json_encode([
//         'success' => true,
//         'count' => (int)$row['count']
//     ]);
// } catch (Exception $e) {
//     http_response_code(500);
//     echo json_encode([
//         'success' => false,
//         'message' => $e->getMessage()
//     ]);
// }