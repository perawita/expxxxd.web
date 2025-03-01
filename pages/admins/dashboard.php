<?php
include 'app/function.php';
include 'app/crudUser.php';
include 'app/converByite.php';

$utilsConvert = new ConvertByite();
$userData = new CrudUser();
$users = $userData->getUsers();

$api = new APIClient();
$response = json_decode($api->getProduk(), true);

$products = isset($response['status'], $response['data']) && $response['status'] 
    ? array_map('formatProduk', $response['data']) 
    : [];

/**
 * Fungsi untuk memformat produk
 */
function formatProduk($item) {
    return [
        'id' => htmlspecialchars($item['id']),
        'id_produk' => htmlspecialchars($item['id_produk'] ?? ''),
        'nama_paket' => htmlspecialchars($item['nama_paket'] ?? ''),
        'harga' => htmlspecialchars($item['harga'] ?? 0),
        'stok' => htmlspecialchars($item['stok'] ?? 0),
        'original_price' => htmlspecialchars($item['Original_Price'] ?? 0),
        'sisa_slot' => htmlspecialchars($item['sisa_slot'] ?? 0),
        'jumlah_slot' => htmlspecialchars($item['jumlah_slot'] ?? 0),
        'slot_terpakai' => htmlspecialchars($item['slot_terpakai'] ?? 0),
        'quota_allocated' => htmlspecialchars($item['quota_allocated']),
        'key_access' => htmlspecialchars($item['key_access'] ?? ''),
    ];
}
?>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Produk</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> No </th>
                                <th> Nama Paket </th>
                                <th> Harga </th>
                                <th> Harga Asli </th>
                                <th> Stok </th>
                                <th> Quota Allocated </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($products): ?>
                                <?php foreach ($products as $index => $product): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $product['nama_paket'] ?></td>
                                        <td>Rp <?= $product['harga'] ?></td>
                                        <td>Rp <?= $product['original_price'] ?></td>
                                        <td><?= $product['sisa_slot'] ?></td>
                                        <td><?= $utilsConvert->convertToFormat($product['quota_allocated']) ?></td>
                                        <td>
                                            <a href="?pages=create-product&id=<?= $product['id'] ?>" class="badge badge-outline-warning">Edit</a>
                                            <a href="?pages=delet&properti=produk&id=<?= $product['id'] ?>" class="badge badge-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data produk</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Users</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> No </th>
                                <th> Username </th>
                                <th> Roles </th>
                                <th> Saldo </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users): ?>
                                <?php foreach ($users as $index => $user): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><span class="ps-2"><?= htmlspecialchars($user['name']) ?></span></td>
                                        <td><span class="ps-2"><?= htmlspecialchars($user['role']) ?></span></td>
                                        <td><?= number_format($user['saldo'] ?? 0, 2) ?></td>
                                        <td>
                                            <a href="?pages=top-up&id=<?= $user['id'] ?>" class="badge badge-outline-success">Topup</a>
                                            <a href="?pages=create-user&id=<?= $user['id'] ?>" class="badge badge-outline-warning">Edit</a>
                                            <a href="?pages=delet&properti=user&id=<?= $user['id'] ?>" class="badge badge-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data user</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
