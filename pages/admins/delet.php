<?php
include 'app/crudUser.php';
include 'app/function.php';

$pages = $_GET['properti'] ?? null;

switch($pages){
    case 'user':
        $user = new CrudUser();
        $id = $_GET['id'] ?? null;
        $response = $user->deleteUser($id);
        if($response){
            echo "<script>alert('User berhasil dihapus!'); window.location.href='?pages=dashboard';</script>";
        }else{
            echo "<script>alert('User gagal dihapus!'); window.history.back();</script>";
        }
    break;

    case 'produk':
        $api = new APIClient();
        
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo "<script>alert('ID produk tidak ditemukan!'); window.history.back();</script>";
            die("ID produk tidak ditemukan.");
        }
        
        $id_produk = $_GET['id'];
        $response = json_decode($api->getProdukBy($id_produk), true);
        
        if (!$response || $response['status'] !== true || !isset($response['data'])) {
            echo "<script>alert('Produk tidak ditemukan!'); window.history.back();</script>";
            die("Produk tidak ditemukan.");
        }
        
        
        $propertiResponse = json_decode($api->getProperti(), true);
        $propertiList = [];
        
        if ($propertiResponse['status'] === "success" && isset($propertiResponse['data'])) {
            $propertiList = $propertiResponse['data'];
        }
        
        $produk = $response['data'];
        
        $updateResponse = json_decode($api->deleteProduct($id_produk), true);
        
        if ($updateResponse['status'] === true) {
            echo "<script>alert('Produk berhasil dihapus!'); window.location.href='?pages=dashboard';</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>alert('Gagal menghapus produk!'); window.history.back();</script>";
        }
    break;

    default:
        echo "<script>alert('Properti dan id tidak valid!'); window.history.back();</script>";
    break;
}