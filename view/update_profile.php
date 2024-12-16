<?php
session_start();
require_once '../service/database.php';

// // Pastikan user sudah login
// if (!isset($_SESSION['user_id'])) {
//     $_SESSION['error_message'] = "Anda harus login terlebih dahulu.";
//     header("Location: ../login.php");
//     exit();
// }

$user_id = $_SESSION['user_id'];

// Ambil data dari form
$username = trim($_POST['username']);
$email = trim($_POST['email']);

// Validasi input
if (empty($username)) {
    $_SESSION['error_message'] = "Nama tidak boleh kosong.";
    header("Location: setting-profile.php");
    exit();
}

// Proses upload gambar
$image_name = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $file_type = mime_content_type($_FILES['image']['tmp_name']);
    $file_size = $_FILES['image']['size'];

    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error_message'] = "Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF.";
        header("Location: setting-profile.php");
        exit();
    }

    if ($file_size > $max_size) {
        $_SESSION['error_message'] = "Ukuran file terlalu besar. Maksimal 2MB.";
        header("Location: setting-profile.php");
        exit();
    }

    // Generate nama file unik
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = uniqid('profile_') . '.' . $file_extension;
    $upload_path = '../img/profile/' . $image_name;

    // Pindahkan file
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
        $_SESSION['error_message'] = "Gagal mengunggah gambar.";
        header("Location: setting-profile.php");
        exit();
    }
}

// Mulai transaksi database
$db->begin_transaction();

try {
    // Query untuk update
    if ($image_name) {
        // Jika ada gambar baru, update termasuk gambar
        $stmt = $db->prepare("UPDATE users SET username = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $image_name, $user_id);
    } else {
        // Jika tidak ada gambar baru, update tanpa gambar
        $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $user_id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        // Commit transaksi
        $db->commit();
        
        // Set pesan sukses
        $_SESSION['success_message'] = "Profil berhasil diperbarui.";
        
        // Redirect
        header("Location: setting-profile.php");
    } else {
        // Rollback jika gagal
        $db->rollback();
        
        $_SESSION['error_message'] = "Gagal memperbarui profil.";
        header("Location: setting-profile.php");
    }

    // Tutup statement
    $stmt->close();
} catch (Exception $e) {
    // Rollback jika terjadi error
    $db->rollback();
    
    $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
    header("Location: setting-profile.php");
}

// Tutup koneksi database
$db->close();
exit();
?>