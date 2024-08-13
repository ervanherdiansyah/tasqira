<?php
// Path ke direktori storage/app/public
$storagePath = __DIR__ . '/storage/app/public';
// Path ke direktori public_html/storage
$publicHtmlPath = __DIR__ . '/../public_html/storage';

// Hapus symlink jika sudah ada
if (is_link($publicHtmlPath) || file_exists($publicHtmlPath)) {
    unlink($publicHtmlPath);
}

// Buat symlink baru
if (symlink($storagePath, $publicHtmlPath)) {
    echo "Symlink berhasil dibuat.\n";
} else {
    echo "Gagal membuat symlink.\n";
}