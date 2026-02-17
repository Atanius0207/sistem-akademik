<?php
// Panggil koneksi database
include '../config/db.php';

// 1. Deklarasi Header PHP untuk memaksa unduhan file Excel
header("Content-Type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Ranking_Kelas_" . date('d-m-Y') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th colspan="5" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #4e73df; color: #ffffff;">
                LAPORAN PERINGKAT KELAS - EDURANK<br>
                Tanggal Cetak: <?= date('d F Y'); ?>
            </th>
        </tr>
        <tr style="background-color: #f8f9fc; font-weight: bold;">
            <th style="width: 100px; text-align: center;">Peringkat</th>
            <th style="width: 150px; text-align: center;">NIS</th>
            <th style="width: 300px;">Nama Lengkap Siswa</th>
            <th style="width: 150px; text-align: center;">Rata-rata Rapor</th>
            <th style="width: 200px; text-align: center;">Predikat</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 3. Ambil data dengan query yang persis sama dengan di ranking.php
        $sql = "SELECT s.nis, s.nama_siswa, AVG(n.nilai_rapor) as rata_rapor 
                FROM siswa s 
                JOIN nilai n ON s.id_siswa = n.id_siswa 
                GROUP BY s.id_siswa 
                HAVING rata_rapor > 0 
                ORDER BY rata_rapor DESC";
        
        $query = mysqli_query($conn, $sql);
        $no = 1;

        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
                $rata = $row['rata_rapor'];
                
                // Logika Predikat (Sama seperti di ranking.php)
                if($rata >= 85) { $predikat = "Sangat Baik"; }
                elseif($rata >= 75) { $predikat = "Baik"; }
                elseif($rata >= 60) { $predikat = "Cukup"; }
                else { $predikat = "Perlu Bimbingan"; }

                // Memberikan warna khusus untuk Juara 1, 2, 3 di Excel
                $style_baris = "";
                if ($no == 1) { $style_baris = "background-color: #fff9c4;"; } // Emas
                if ($no == 2) { $style_baris = "background-color: #f5f5f5;"; } // Perak
                if ($no == 3) { $style_baris = "background-color: #ffe0b2;"; } // Perunggu

                echo "<tr style='{$style_baris}'>
                        <td style='text-align: center;'>{$no}</td>
                        <td style='text-align: center;'>'{$row['nis']}</td>
                        <td>{$row['nama_siswa']}</td>
                        <td style='text-align: center; font-weight: bold;'>" . number_format($rata, 2) . "</td>
                        <td style='text-align: center;'>{$predikat}</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='5' style='text-align: center;'>Belum ada data nilai yang diinput.</td></tr>";
        }
        ?>
    </tbody>
</table>