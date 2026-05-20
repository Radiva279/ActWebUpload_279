<?php
// Bypass konfigurasi php.ini lokal agar mengizinkan upload hingga 6MB (di atas batas 5MB)
@ini_set('upload_max_filesize', '6M');
@ini_set('post_max_size', '6M');

$target_dir = "uploads/";

// Buat folder uploads otomatis jika belum ada
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// 1. Periksa apakah berkas benar-benar gambar
if(isset($_POST["submit"])) {
    $check = @getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('Maaf, berkas bukan gambar valid.'); window.history.back();</script>";
        $uploadOk = 0;
        exit;
    }
}

// 2. Periksa apakah berkas sudah ada
if (file_exists($target_file)) {
    echo "<script>alert('Maaf, berkas dengan nama tersebut sudah ada.'); window.history.back();</script>";
    $uploadOk = 0;
    exit;
}

// 3. Periksa ukuran berkas (Maks 5MB = 5242880 byte)
if ($_FILES["fileToUpload"]["size"] > 5242880) {
    echo "<script>alert('Maaf, berkas Anda terlalu besar (Maks 5MB).'); window.history.back();</script>";
    $uploadOk = 0;
    exit;
}

// 4. Validasi Ekstensi Gambar
if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif" ) {
    echo "<script>alert('Maaf, hanya berkas JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.history.back();</script>";
    $uploadOk = 0;
    exit;
}

// 5. Eksekusi Upload Berkas
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Jika sukses langsung pindah ke halaman daftar file
        header("Location: lihat_file.php");
        exit;
    } else {
        echo "<script>alert('Maaf, terjadi kesalahan internal saat mengunggah.'); window.history.back();</script>";
    }
}
?>