<?php
//cart count
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_items FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$cart_count = isset($row['total_items']) ? $row['total_items'] : 0;