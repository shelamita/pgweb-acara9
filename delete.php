<?php
// Menghubungkan ke database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "pgweb-acara8"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan ID yang akan dihapus melalui metode GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Membuat query DELETE
    $sql = "DELETE FROM acara8 WHERE ID = ?";

    // Menyiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Mengeksekusi statement
    if ($stmt->execute()) {
        echo "Data berhasil dihapus. <a href='index.php'>Kembali ke halaman utama</a>";
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }

    // Menutup statement
    $stmt->close();
} else {
    echo "ID tidak ditemukan.";
}

// Menutup koneksi
$conn->close();
?>