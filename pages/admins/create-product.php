<?php
include 'app/function.php';
include 'app/crudUser.php';
include 'app/converByite.php';

$utilsConvert = new ConvertByite();
$api = new APIClient();

$id_produk = $_GET['id'] ?? null;
$produk = null;

$propertiResponse = json_decode($api->getProperti(), true);
$propertiList = [];

if ($propertiResponse['status'] === "success" && isset($propertiResponse['data'])) {
    $propertiList = $propertiResponse['data'];
}


if ($id_produk) {
    $response = json_decode($api->getProdukBy($id_produk), true);
    if ($response && $response['status'] === true && isset($response['data'])) {
        $produk = $response['data'];
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.history.back();</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "id_produk" => $_POST['id_produk'],
        "nama_paket" => $_POST['nama_paket'] ?? '',
        "harga" => $_POST['harga'],
        "stok" => $_POST['stok'] ?? 0,
        "Original_Price" => $_POST['original_price'],
        "sisa_slot" => $_POST['sisa_slot'] ?? 0,
        "jumlah_slot" => $_POST['jumlah_slot'] ?? 0,
        "slot_terpakai" => $_POST['slot_terpakai'] ?? 0,
        "quota_allocated" => $utilsConvert->convertToBytes($_POST['quota_allocated'], $_POST['quota_unit'])
    ];

    if ($produk) {
        $updateResponse = json_decode($api->updateProduct($id_produk, $data), true);
        if ($updateResponse['status'] === true) {
            echo "<script>alert('Produk berhasil diperbarui!'); window.location.href = '?pages=dashboard';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal memperbarui produk!'); window.history.back();</script>";
        }
    } else {
        $addResponse = json_decode($api->addProduct($data), true);
        if ($addResponse['status'] === true) {
            echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href = '?pages=dashboard';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menambah produk! Cek kembali inputan.'); window.history.back();</script>";
        }
    }
}
?>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <p class="card-description">
                <?= $produk ? 'Edit Produk' : 'Tambah Produk Baru' ?>
            </p>
            <form class="forms-sample" method="POST">
                <div class="form-group">
                    <label for="id_produk">ID Produk</label>
                    <input type="text" class="form-control" name="id_produk" value="<?= $produk['id_produk'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_paket">Nama Paket</label>
                    <select name="nama_paket" id="nama_paket" class="form-select" required>
                        <option value="">Pilih Paket</option>
                        <?php foreach ($propertiList as $id_produk => $nama_paket) : ?>
                            <option value="<?= htmlspecialchars($nama_paket) ?>">
                                <?= htmlspecialchars($nama_paket) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" name="harga" value="<?= $produk['harga'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="original_price">Harga Original</label>
                    <input type="number" class="form-control" name="original_price" value="<?= $produk['Original_Price'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="sisa_slot">Stok</label>
                    <input type="number" class="form-control" name="sisa_slot" value="<?= $produk['sisa_slot'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="quota_allocated" class="form-label">Quota Allocated</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="quota_allocated" name="quota_allocated" 
                               value="<?= $utilsConvert->formatSize($produk['quota_allocated'] ?? 0) ?>" required>
                        <?php
                        $defaultUnit = $utilsConvert->formatSizeUnit($produk['quota_allocated'] ?? 0);
                        $unitOptions = ["B", "KB", "MB", "GB", "TB"];
                        ?>
                        <select class="form-select" id="quota_unit" name="quota_unit" required>
                            <?php foreach ($unitOptions as $unit) : ?>
                                <option value="<?= $unit; ?>" <?= ($defaultUnit === $unit) ? 'selected' : ''; ?>>
                                    <?= $unit; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">
                    <?= $produk ? 'Update' : 'Submit' ?>
                </button>
            </form>
        </div>
    </div>
</div>
