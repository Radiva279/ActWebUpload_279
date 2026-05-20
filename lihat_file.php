<?php
$target_dir = "uploads/";

// Fitur Aksi Hapus File
if (isset($_GET['hapus'])) {
    $file_target = $target_dir . basename($_GET['hapus']);
    
    // Validasi keamanan dasar agar tidak melakukan Directory Traversal (menghapus file di luar folder upload)
    if (file_exists($file_target) && strposRealpath($file_target, $target_dir)) {
        unlink($file_target);
        header("Location: lihat_file.php");
        exit;
    }
}

// Fungsi bantu mengamankan path file
function strposRealpath($target, $base) {
    return strpos(realpath($target), realpath($base)) === 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Berkas Terunggah</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-indigo-50 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl p-6 max-w-3xl w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Daftar Berkas yang Diunggah</h2>

        <div class="overflow-x-auto border border-gray-100 rounded-xl mb-6">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-gray-700 text-left">Pratinjau</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 text-left">Nama Berkas</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 text-left">Tipe</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 text-left">Ukuran</th>
                        <th class="px-4 py-3 font-semibold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <?php
                    // Scan folder uploads untuk mengambil daftar berkas
                    if (is_dir($target_dir)) {
                        $files = array_diff(scandir($target_dir), array('.', '..'));
                        
                        if (count($files) > 0) {
                            foreach ($files as $file) {
                                $file_path = $target_dir . $file;
                                $ext = strtoupper(pathinfo($file_path, PATHINFO_EXTENSION));
                                $size = round(filesize($file_path) / 1024, 2) . ' KB';
                                
                                // Jika ukuran file lebih besar dari 1024 KB, ubah tampilan teks menjadi MB
                                if (filesize($file_path) >= 1048576) {
                                    $size = round(filesize($file_path) / (1024 * 1024), 2) . ' MB';
                                }
                                ?>
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <img src="<?php echo $file_path; ?>" class="h-10 w-10 object-cover rounded border border-gray-200" alt="preview">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-800 font-medium">
                                        <?php echo htmlspecialchars($file); ?>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded"><?php echo $ext; ?></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500"><?php echo $size; ?></td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center space-x-2">
                                        <a href="<?php echo $file_path; ?>" download class="border border-green-500 text-green-600 hover:bg-green-50 text-xs px-3 py-1.5 rounded transition font-medium">Unduh</a>
                                        <a href="lihat_file.php?hapus=<?php echo urlencode($file); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus berkas ini?')" class="border border-red-400 text-red-500 hover:bg-red-50 text-xs px-3 py-1.5 rounded transition font-medium">Hapus</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada berkas yang diunggah.</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada berkas yang diunggah.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a href="index.html" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2.5 px-5 rounded-xl transition">
                Unggah File Baru
            </a>
        </div>
    </div>

</body>
</html>