<?php
header("Content-Type: application/json");

// Ambil JSON dari request
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['status']) || !isset($input['location'])) {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak valid"
    ]);
    exit;
}

// Data yang akan disimpan
$data = [
    "status" => true,
    "location" => $input['location'],
    "created_at" => date("Y-m-d H:i:s"),
    "ip" => $_SERVER['REMOTE_ADDR']
];

// Simpan ke file debug.json
$file = "debug.json";

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
    echo json_encode([
        "success" => true,
        "message" => "File berhasil disimpan"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menulis file"
    ]);
}