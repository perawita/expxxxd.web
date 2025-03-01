<?php
class ConvertByite {
    // Fungsi untuk mengubah byte ke format terbaik
    public function convertToFormat($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = (int) floor(log($bytes, 1024));
        return number_format($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
    }
    
    
    // Merubah menjadi satuan angka 
    function formatSize($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = (int) floor(log($bytes, 1024));
    
        $size = $bytes / pow(1024, $factor);
        
        return rtrim(rtrim(number_format($size, $precision, '.', ''), '0'), '.');
    }

    // Fungsi untuk mengubah nilai dengan satuan tertentu ke bytes
    public function convertToBytes($value, $unit) {
        $units = [
            "B" => 1,
            "KB" => 1024,
            "MB" => 1024 * 1024,
            "GB" => 1024 * 1024 * 1024,
            "TB" => 1024 * 1024 * 1024 * 1024
        ];
        return isset($units[$unit]) ? $value * $units[$unit] : null;
    }
    
    
    function formatSizeUnit($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = (int) floor(log($bytes, 1024));
        return $units[$factor] ?? 'B';
    }
}
?>
