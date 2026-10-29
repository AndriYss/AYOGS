<?php
// MATIKAN TAMPILAN ERROR AGAR TIDAK MERUSAK FORMAT JSON DASHBOARD
error_reporting(0); 
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$fileJson = 'database_counter.json';

// 1. Ciptakan file jika belum ada sama sekali
if (!file_exists($fileJson)) {
    file_put_contents($fileJson, json_encode([
        "counter_intersect" => 0,
        "counter_konvert" => 0,
        "counter_boq" => 0,
        "counter_hpdb" => 0,
        "counter_kabel" => 0,
        "counter_gabung" => 0,
        "counter_autonaming" => 0 // Inisialisasi Baru
    ], JSON_PRETTY_PRINT));
}

// 2. Baca isi file JSON
$jsonContent = file_get_contents($fileJson);
$data = json_decode($jsonContent, true);

if (!is_array($data)) {
    $data = [
        "counter_intersect" => 0,
        "counter_konvert" => 0,
        "counter_boq" => 0,
        "counter_hpdb" => 0,
        "counter_kabel" => 0,
        "counter_gabung" => 0,
        "counter_autonaming" => 0, // Inisialisasi Baru
        "counter_tagging" => 0
    ];
}

// 3. Proses penambahan counter jika metodenya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fitur = '';
    $raw_input = file_get_contents('php://input');

    // A. Coba tangkap data dari format JSON
    $input_json = json_decode($raw_input, true);
    if (is_array($input_json) && isset($input_json['fitur'])) {
        $fitur = trim($input_json['fitur']);
    } 
    // B. Coba tangkap dari format POST biasa
    elseif (isset($_POST['fitur'])) {
        $fitur = trim($_POST['fitur']);
    }
    // C. Coba tangkap dari format URL Encoded text biasa
    else {
        parse_str($raw_input, $parsed_str);
        if (is_array($parsed_str) && isset($parsed_str['fitur'])) {
            $fitur = trim($parsed_str['fitur']);
        }
    }
    
    // Jika ada fitur yang ditangkap, jalankan penambahan
    if (!empty($fitur)) {
        if (isset($data[$fitur])) {
            $data[$fitur]++;
        } else {
            $data[$fitur] = 1; 
        }
        
        file_put_contents($fileJson, json_encode($data, JSON_PRETTY_PRINT));
    }
}

// 4. Kembalikan data terbaru
echo json_encode($data);
?>