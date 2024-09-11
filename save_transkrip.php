<?php
// Menghubungkan ke database
include 'koneksi.php';

if (isset($_POST['podcast_id']) && isset($_POST['transcript'])) {
    $id_podcast = $_POST['podcast_id'];
    $transkrip = mysqli_real_escape_string($koneksi, $_POST['transcript']);

    // Query untuk memperbarui transkrip di database
    $query = "UPDATE dataset_podcast SET transcript = '$transkrip' WHERE id_podcast = '$id_podcast'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo 'Success';
    } else {
        echo 'Error: ' . mysqli_error($koneksi);
    }
} else {
    echo 'Invalid Request';
}
?>
