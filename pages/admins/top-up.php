<?php
include 'app/crudUser.php';
include 'app/topup.php';

$topup = new Topup();
$users = new CrudUser();

$id = $_GET['id'] ?? null;
$existingUser = null;

if ($id) {
    $existingUser = $users->getUserById($id);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['saldo'] ?? 0;
        $updateData = [
            'saldo' => $name,
        ];

        $response = $topup->topupUser($id, $updateData);
        if ($response) {
            echo "<script>alert('User updated successfully!'); window.location.href='?pages=dashboard';</script>";
        } else {
            echo "<script>alert('Failed to update user.');</script>";
        }
}
?>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <p class="card-description">
                Top up
            </p>
            <form class="forms-sample" method="POST">
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name"
                        value="<?= $existingUser['name'] ?? '' ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Masukan Saldo</label>
                    <input type="number" class="form-control" name="saldo" placeholder="1000" required>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>
        </div>
    </div>
</div>
