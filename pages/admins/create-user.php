<?php
include 'app/crudUser.php';
$user = new CrudUser();

$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;
$existingUser = null;

// Jika dalam mode edit, ambil data user berdasarkan ID
if ($id) {
    $existingUser = $user->getUserById($id);
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($id) {
        // Update user
        $updateData = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ];

        // Jika password diisi, update password dengan hash baru
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $response = $user->updateUser($id, $updateData);
        if ($response) {
            echo "<script>alert('User updated successfully!'); window.location.href='?pages=dashboard';</script>";
        } else {
            echo "<script>alert('Failed to update user.');</script>";
        }
    } else {
        // Tambah user baru
        $newUser = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'reseller',
        ];

        $response = $user->addUser($newUser);
        if ($response) {
            echo "<script>alert('User added successfully!'); window.location.href='?pages=dashboard';</script>";
        } else {
            echo "<script>alert('Failed to add user. Email might already exist.');</script>";
        }
    }
}
?>

<div class="col-12 grid-margin stretch-card">
    <?php if ($status === 'creat' || $id) : ?>
    <div class="card">
        <div class="card-body">
            <p class="card-description">
                <?= $id ? 'Edit User' : 'Add New User' ?>
            </p>
            <form class="forms-sample" method="POST">
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Name"
                        value="<?= $existingUser['name'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email"
                        value="<?= $existingUser['email'] ?? '' ?>" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword4">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password"
                        <?= $id ? '' : 'required' ?>>
                    <?php if ($id) : ?>
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary me-2"><?= $id ? 'Update' : 'Submit' ?></button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
