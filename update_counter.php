<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// JIKA ADA REQUEST OPTIONS (CORS Preflight dari Browser), LANGSUNG SETUJUI DAN EXIT
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
        "counter_hpdb" => 0 // <-- DITAMBAHKAN DI SINI
    ], JSON_PRETTY_PRINT));
}

// 2. Baca isi file JSON
$jsonContent = file_get_contents($fileJson);
$data = json_decode($jsonContent, true);

// 3. ANTISIPASI: Jika file JSON ada tetapi kosong atau rusak (null), isi kembali dengan array default
if (!is_array($data)) {
    $data = [
        "counter_intersect" => 0,
        "counter_konvert" => 0,
        "counter_boq" => 0,
        "counter_hpdb" => 0 // <-- DITAMBAHKAN DI SINI JUGA
    ];
}

// 4. Proses penambahan counter jika metodenya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $fitur = isset($input['fitur']) ? trim($input['fitur']) : '';
    
    if (!empty($fitur)) {
        // Solusi Fleksibel: Jika key sudah ada maka ditambah 1, jika belum ada otomatis dibuat baru
        if (isset($data[$fitur])) {
            $data[$fitur]++;
        } else {
            $data[$fitur] = 1; 
        }
        
        // Simpan kembali ke file dengan format rapi
        file_put_contents($fileJson, json_encode($data, JSON_PRETTY_PRINT));
    }
}

// 5. Kembalikan data terbaru ke client (Dashboard)
echo json_encode($data);
?>