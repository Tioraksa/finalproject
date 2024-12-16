<?php
require_once '../service/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $kategori = trim($_POST['kategori']);
    $file_url = '';

    // Proses upload file jika ada
    if (!empty($_FILES['file']['name'])) {
        $upload_dir = '../uploads/';
        $file_name = basename($_FILES['file']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_url = $file_name;
        } else {
            die("Gagal mengupload file.");
        }
    }

    // Simpan ke database
    $sql = "INSERT INTO materi (title, content, kategori, file_url) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssss', $title, $content, $kategori, $file_url);

    if ($stmt->execute()) {
        echo "Submateri berhasil ditambahkan!";
        header("Location: materi.php"); // Redirect ke halaman materi
        exit;
    } else {
        die("Gagal menyimpan data: " . $db->error);
    }
}
?>
