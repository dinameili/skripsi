<?php
// Menghubungkan ke database
include 'koneksi.php';

// Query untuk mendapatkan data dari tabel dataset_podcast
$sql = mysqli_query($koneksi, "SELECT * FROM dataset_podcast");

// Simpan hasil query ke dalam array $podcasts
$podcasts = [];
if (mysqli_num_rows($sql) > 0) {
    while ($row = mysqli_fetch_assoc($sql)) {
        $podcasts[] = $row;
    }
} else {
    echo "Tidak ada data ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Podcast</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styles similar to those you provided */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            color: white;
            background-color: #007BFF;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5); 
            overflow: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        textarea {
            height: 150px;
            margin-bottom: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 8px;
            resize: vertical;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            color: #fff;
            background-color: #28a745;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .more-btn {
            color: #007BFF;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .more-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Daftar Podcast</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>URL</th>
            <th>Artist</th>
            <th>Views</th>
            <th>Durasi</th>
            <th>Likes</th>
            <th>Publish</th>
            <th>Deskripsi</th>
            <th>Transkrip</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($podcasts as $podcast): ?>
            <tr>
                <td><?php echo htmlspecialchars($podcast['id_podcast']); ?></td>
                <td><?php echo htmlspecialchars($podcast['title']); ?></td>
                <td><a href="<?php echo htmlspecialchars($podcast['url']); ?>" target="_blank"><?php echo htmlspecialchars($podcast['url']); ?></a></td>
                <td><?php echo htmlspecialchars($podcast['artist']); ?></td>
                <td><?php echo htmlspecialchars($podcast['views']); ?></td>
                <td><?php echo htmlspecialchars($podcast['duration']); ?></td>
                <td><?php echo htmlspecialchars($podcast['likes']); ?></td>
                <td><?php echo htmlspecialchars($podcast['publish']); ?></td>

                <!-- Deskripsi dengan batasan 30 kata -->
                <td>
                    <?php
                    $deskripsi = explode(' ', $podcast['description']);
                    if (count($deskripsi) > 10) {
                        $short_desc = implode(' ', array_slice($deskripsi, 0, 30)) . '...';
                        echo htmlspecialchars($short_desc);
                        echo ' <a href="#" class="more-btn" data-id="' . $podcast['id_podcast'] . '" data-content="' . htmlspecialchars($podcast['description']) . '">Selengkapnya</a>';
                    } else {
                        echo htmlspecialchars($podcast['description']);
                    }
                    ?>
                </td>

                <!-- Transkrip dengan batasan 30 kata -->
                <td>
                    <?php
                    $transkrip = explode(' ', $podcast['transcript']);
                    if (count($transkrip) > 10) {
                        $short_transkrip = implode(' ', array_slice($transkrip, 0, 30)) . '...';
                        echo htmlspecialchars($short_transkrip);
                        echo ' <a href="#" class="more-btn" data-id="' . $podcast['id_podcast'] . '" data-content="' . htmlspecialchars($podcast['transcript']) . '">Selengkapnya</a>';
                    } else {
                        echo htmlspecialchars($podcast['transcript']);
                    }
                    ?>
                </td>

                <td>
                    <button class="btn edit-btn" data-id="<?php echo $podcast['id_podcast']; ?>" data-transkrip="<?php echo htmlspecialchars($podcast['transcript']); ?>">
                        <?php echo empty($podcast['transcript']) ? 'Tambah Transkrip' : 'Edit Transkrip'; ?>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Modal untuk menambah/mengedit transkrip -->
    <div id="transkripModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Edit Transkrip</h2>
            <form id="transkripForm">
                <input type="hidden" id="podcastId" name="podcast_id">
                <label for="transkrip">Transkrip:</label>
                <textarea id="transkrip" name="transcript"></textarea>
                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Modal untuk menampilkan lebih lengkap -->
    <div id="moreModal" class="modal">
        <div class="modal-content">
            <span class="close-more">&times;</span>
            <h2 id="modalMoreTitle">Detail</h2>
            <p id="modalMoreContent"></p>
        </div>
    </div>

    <script>
        // Mendapatkan modal dan elemen modal
        var modal = document.getElementById("transkripModal");
        var moreModal = document.getElementById("moreModal");
        var span = document.getElementsByClassName("close")[0];
        var spanMore = document.getElementsByClassName("close-more")[0];

        // Saat tombol edit diklik, tampilkan modal dengan data yang sesuai
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var transkrip = $(this).data('transkrip');

            $("#podcastId").val(id);
            $("#transkrip").val(transkrip);
            $("#modalTitle").text(transkrip ? "Edit Transkrip" : "Tambah Transkrip");

            modal.style.display = "block";
        });

        // Saat tombol 'selengkapnya' diklik, tampilkan modal dengan deskripsi lengkap atau transkrip lengkap
        $(document).on('click', '.more-btn', function() {
            var content = $(this).data('content');
            var id = $(this).data('id');
            $("#modalMoreContent").text(content);
            moreModal.style.display = "block";
        });

        // Ketika tombol X diklik, tutup modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        spanMore.onclick = function() {
            moreModal.style.display = "none";
        }

        // Jika klik di luar modal, modal akan tertutup
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == moreModal) {
                moreModal.style.display = "none";
            }
        }

        // Submit form menggunakan AJAX
        $('#transkripForm').submit(function(e) {
            e.preventDefault();

            var podcastId = $("#podcastId").val();
            var transkrip = $("#transkrip").val();

            $.ajax({
                url: 'save_transkrip.php',
                type: 'POST',
                data: {
                    podcast_id: podcastId,
                    transcript: transkrip
                },
                success: function(response) {
                    if (response === 'Success') {
                        alert("Transkrip berhasil disimpan!");
                        location.reload(); // Reload halaman setelah berhasil
                    } else {
                        alert(response);
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan. Coba lagi.");
                }
            });
        });

    </script>
</body>
</html>
