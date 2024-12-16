<?php
// Include file auth.php dan database
require_once '../session.php';
require_once '../service/database.php';

// Periksa apakah sudah login
checkLogin();

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $db->prepare("SELECT username, email, image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// // Jika tidak ada data user, redirect
// if (!$user) {
//     $_SESSION['error_message'] = "User not found.";
//     header("Location: ../login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/BIMBELRAHMA.png" sizes="32x32">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Setting Profiles - Bimbel Rahma</title>
    <style>
        body {
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<?php
// Tampilkan pesan error jika ada
if(isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error_message']) ?>
        <?php unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>

<?php
// Tampilkan pesan sukses jika ada
if(isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>

<section id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <span class="navbar-brand">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>
    </nav>

    <main class="container">
        <h1 class="h3 mb-4 text-gray-800">Setting Profiles</h1>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Full Name</label>
                <input type="text" name="username" class="form-control" id="username" 
                    value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" 
                    value="<?= htmlspecialchars($user['email']); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="image">Profile Picture</label>
                <div class="row">
                    <div class="col-sm-3">
                        <img src="../img/profile/<?= htmlspecialchars($user['image'] ?: 'default.jpg'); ?>" 
                             class="img-thumbnail" alt="Profile Picture">
                    </div>
                    <div class="col-sm-9">
                        <input type="file" name="image" id="image" class="form-control">
                        <small>Supported files: JPG, PNG, GIF. Max size: 2MB.</small>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="user_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </main>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin mengubah profil?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>