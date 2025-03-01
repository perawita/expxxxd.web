<?php
function convertToFormat($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = (int) floor(log($bytes, 1024));
    return number_format($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'function.php';
    include 'converByite.php';

    $api = new APIClient();
    $productsResponse = json_decode($api->getProduk(), true);

    $utilsConvert = new ConvertByite();
    $products = [];
    if ($productsResponse['status'] && isset($productsResponse['data'])) {
        $products = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'id_produk' => $item['id_produk'] ?? '',
                'nama_paket' => $item['nama_paket'] ?? '',
                'harga' => $item['harga'] ?? 0,
                'stok' => $item['stok'] ?? 0,
                'original_price' => $item['Original_Price'] ?? 0,
                'sisa_slot' => $item['sisa_slot'] ?? 0,
                'jumlah_slot' => $item['jumlah_slot'] ?? 0,
                'slot_terpakai' => $item['slot_terpakai'] ?? 0,
                'quota_allocated' => convertToFormat($item['quota_allocated']),
                'key_access' => $item['key_access'] ?? '',
            ];
        }, $productsResponse['data']);
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'true',
        'message' => 'Data didapatkan!',
        'data' => $products
    ]);
    
} else {
    http_response_code(403);
    echo json_encode([
        'status' => 'false',
        'message' => 'Akses ditolak.'
    ]);
}
