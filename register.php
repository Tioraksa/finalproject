<?php 
include "service/database.php";
session_start();

$register_message = "";

// Redirect jika sudah login
if (isset($_SESSION["is_login"])) {
    header("location: dashboard.php");
    exit;
} 

// Proses registrasi
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $class = $_POST["class"];
    $province = $_POST["province"];
    $regency = $_POST["regency"];
    $district = $_POST["district"];
    $address = $_POST["address"] ?? '';
    $image = "default.jpg"; // Default image
    $role_id = 1;
    $is_active = 1; // Status aktif
    $hash_password = hash("sha256", $password); // Hash password

    // Query untuk memasukkan data
    $sql = "INSERT INTO users (username, password, email, phone, class, province, regency, district, address, image, is_active, role_id) 
            VALUES ('$username', '$hash_password', '$email', '$phone', '$class', '$province', '$regency', '$district', '$address', '$image', $is_active, $role_id)";

    try {
        // Eksekusi query
        if (mysqli_query($db, $sql)) {
            $register_message = "Daftar akun berhasil, silahkan login";
        }
    } catch (mysqli_sql_exception $e) {
        // Menangani kesalahan duplikasi username
        if (mysqli_errno($db) == 1062) { // 1062 adalah kode error untuk duplikat entry
            $register_message = "Username sudah digunakan, silahkan ganti";
        } else {
            $register_message = "Daftar akun gagal, silahkan coba lagi. Error: " . $e->getMessage();
        }
    }
}
?>
