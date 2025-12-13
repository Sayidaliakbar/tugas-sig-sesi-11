<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "gps_tracking");

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["status" => "error", "message" => "Koneksi DB gagal"]);
  exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$lat = $data['lat'];
$lng = $data['lng'];
$speed = $data['speed'];
$time = date("Y-m-d H:i:s");

$stmt = $conn->prepare(
  "INSERT INTO gps_data (latitude, longitude, speed, created_at)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ddds", $lat, $lng, $speed, $time);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
} else {
  http_response_code(500);
  echo json_encode(["status" => "error"]);
}

$stmt->close();
$conn->close();
?>
