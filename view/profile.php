<?php
session_start(); // Memulai sesi

// Mengimpor file konfigurasi database
require_once '../service/database.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika belum login
    header("Location: login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Query untuk mengambil data pengguna berdasarkan username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Ambil data pengguna
} else {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Tutup statement
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="..\img\BIMBELRAHMA.png" sizes="32x32">
	<title>Dashboard Admin - Bimbel Rahma</title>
</head>
<style>
    .dashboard-content {
        padding: 20px;
    }

    .cards {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .card {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        flex: 1;
        margin: 0 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s; /* Smooth transition for hover effect */
    }

    .card:hover {
        transform: scale(1.02); /* Slightly enlarge the card on hover */
    }

    .card-inner {
        text-align: center;
    }

    .card h3 {
        margin-bottom: 10px;
        font-size: 1.5em; /* Increase font size for the title */
        color:rgb(0, 0, 0); /* Change color to primary */
    }

    .card-body {
        text-align: left; /* Align text to the left */
        padding: 15px; /* Add padding for better spacing */
    }

    .card-text {
        margin-bottom: 10px; /* Add margin for spacing between text */
        font-size: 1em; /* Set a consistent font size */
        color: #333; /* Darker text color for better readability */
    }

    .card-text strong {
        color:rgb(0, 0, 0); /* Change strong text color to primary */
    }

    .btn {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 15px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s; /* Smooth transition for button hover */
    }

    .btn:hover {
        background: #0056b3; /* Darker shade on hover */
    }

    .profile-image {
    width: 100px; /* Ukuran gambar profil */
    height: auto; /* Menjaga rasio aspek gambar */
    border-radius: 50%; /* Membuat gambar menjadi bulat */
    border: 3px solid #007bff; /* Border berwarna biru */
    margin-right: 20px; /* Jarak antara gambar dan teks */
}

.typing-container {
    font-size: 1.2em; /* Ukuran font */
    color: #333; /* Warna teks */
    font-weight: bold; /* Teks tebal */
    white-space: nowrap; /* Mencegah teks membungkus ke baris baru */
    overflow: hidden; /* Menyembunyikan bagian teks yang keluar dari kontainer */
    border-right: 2px solid #007bff; /* Garis vertikal di sebelah kanan teks */
    animation: blink 0.7s step-end infinite; /* Efek kedip pada garis */
}

/* Animasi kedip untuk garis */
@keyframes blink {
    0%, 100% {
        border-color: transparent; /* Tidak terlihat */
    }
    50% {
        border-color: #007bff; /* Warna garis */
    }
}



</style>



<body>


<section id="sidebar">
	<a href="#" class="brand">
		<img src="../img/BIMBELRAHMA.png" alt="Logo Bimbel Rahma" style="width: 80%; height: auto; margin-top: auto;">
	</a>
	<ul class="side-menu top">
		<li class="active">
			<a href="../siswa/siswa-dashboard.php" >
				<i class='bx bxs-dashboard'></i>
				<span class="text">Dashboard</span>
			</a>
		</li>
		<li>
			<a href="../siswa/kelasku.php">
				<i class='bx bxs-group'></i>
				<span class="text">My Course</span>
			</a>
		</li>
		<li>
			<a href="../view/data-pengajar.php" >
				<i class='bx bxs-group'></i>
				<span class="text">Report</span>
			</a>
		</li>

	</ul>
	<ul class="side-menu top">
        <li>
            <a href="../view/profile.php">
                <i class='bx bxs-cog'></i>
                <span class="text">Profiles</span>
            </a>
        </li>
		<li>
			<a href="../view/setting-profile.php" >
				<i class='bx bxs-cog'></i>
				<span class="text">Setting Profiles</span>
			</a>
		</li>
		<li>
			<a href="../logout.php" class="logout">
				<i class='bx bxs-log-out-circle'></i>
				<span class="text">Logout</span>
			</a>
		</li>
	</ul>
</section>


	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav style="justify-content:flex-end">
    <div class="nav-right">
        <span class="welcome-message">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</span>
        <a href="#" class="profile">
        <img src="../img/profile/<?= htmlspecialchars($user['image']); ?>" 
                 class="profile-image">
        </a>
    </div>
</nav>


		<!-- NAVBAR -->

		<main>
   



<!-- HTML untuk menampilkan data pengguna -->
<div class="card mb-3" style="max-width: 540px;">
    <div class="row no-gutters">
        <!-- Bagian Gambar -->
        <<div class="col-md-4 d-flex align-items-center">
            <img src="../img/profile/<?= htmlspecialchars($user['image']); ?>" 
                class="profile-image" 
                alt="<?= htmlspecialchars($user['username']); ?>">
                <br></br>
            <div class="typing-container">
                <span class="typing-text"></span>
            </div>
        </div>

        <div class="divider"></div>



        <div class="col-md-8">
            <div class="card-body">
                <h3 class="card-title text-primary fw-bold">Nama : <?= htmlspecialchars($user['username']); ?></h3>
                <p class="card-text mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p class="card-text mb-1"><strong>No Handphone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
                <p class="card-text mb-1"><strong>Class:</strong> <?= htmlspecialchars($user['class']); ?></p>
                <p class="card-text">
                    <small class="text-muted">Member since <?= date('d M Y', strtotime($user['created_id'])); ?></small>
                </p>
                <a href="../view/setting-profile.php" class="btn btn-primary mt-2 w-100">Edit Profile</a>
            </div>
        </div>
    </div>
    <!-- Bagian Detail -->
</div>

        </main>

<script>
    const typingText = "Selamat Datang, <?= htmlspecialchars($username); ?>!";
const typingContainer = document.querySelector('.typing-text');
let index = 0;

function type() {
    if (index < typingText.length) {
        typingContainer.textContent += typingText.charAt(index);
        index++;
        setTimeout(type, 100); // Kecepatan mengetik (100ms per karakter)
    }
}

// Mulai efek mengetik setelah halaman dimuat
window.onload = type;

</script>

	

	
</body>
</html>

