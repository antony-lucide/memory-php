<?php
session_start();

$response = [
    'message' => isset($_SESSION['memory_game']['match_message']) ? $_SESSION['memory_game']['match_message'] : null
];

// Clear the match message after sending it
if (isset($_SESSION['memory_game']['match_message'])) {
    $_SESSION['memory_game']['match_message'] = null;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
