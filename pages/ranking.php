<?php 
include '../config/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Ranking - EduRank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
    <style>
        .rank-1 { background-color: #fff9c4 !important; } /* Emas */
        .rank-2 { background-color: #f5f5f5 !important; } /* Perak */
        .rank-3 { background-color: #ffe0b2 !important; } /* Perunggu */
        .medal-1 { color: #fbc02d; }
        .medal-2 { color: #9e9e9e; }
        .medal-3 { color: #ff9800; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="text-center mb-4 text-white">
                <i class="fas fa-graduation-cap fa-3x mb-2"></i>
                <h5 class="fw-bold">EduRank</h5>
                <hr>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="siswa.php"><i class="fas fa-users me-2"></i> Data Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="absensi.php"><i class="fas fa-calendar-check me-2"></i> Rekap Absensi</a>
                </li>

                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Guru Mapel</small></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="nilai.php"><i class="fas fa-edit me-2"></i> Input Nilai Mentah</a>
                </li>
                
                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Wali Kelas</small></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="rapor.php"><i class="fas fa-book text-white me-2"></i> Input Nilai Rapor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="ranking.php"><i class="fas fa-trophy me-2"></i> Sistem Ranking</a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <h3 class="fw-bold text-dark"><i class="fas fa-award text-warning me-2"></i> Peringkat Kelas (Berdasarkan Rapor)</h3>
                
                <div class="btn-group shadow-sm">
                    <a href="export_excel.php" class="btn btn-success px-4">
                        <i class="fas fa-file-excel me-2"></i> Export Excel
                    </a>
                    <a href="export_pdf.php" class="btn btn-danger px-4">
                        <i class="fas fa-file-pdf me-2"></i> Export PDF
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-secondary text-uppercase small">
                                    <th class="ps-4 py-3">Peringkat</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center" width="200">Rata-rata Mapel</th>
                                    <th class="text-center">Predikat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil rata-rata nilai_rapor dari semua mapel, dikelompokkan per siswa
                                $sql = "SELECT s.nis, s.nama_siswa, AVG(n.nilai_rapor) as rata_rapor 
                                        FROM siswa s 
                                        JOIN nilai n ON s.id_siswa = n.id_siswa 
                                        GROUP BY s.id_siswa 
                                        HAVING rata_rapor > 0 
                                        ORDER BY rata_rapor DESC";
                                
                                $query = mysqli_query($conn, $sql);
                                $no = 1;

                                if(mysqli_num_rows($query) > 0) {
                                    while($row = mysqli_fetch_assoc($query)) :
                                        $rata = $row['rata_rapor'];
                                        
                                        // Logika Predikat
                                        if($rata >= 85) { $predikat = "Sangat Baik"; $warna = "success"; }
                                        elseif($rata >= 75) { $predikat = "Baik"; $warna = "primary"; }
                                        elseif($rata >= 60) { $predikat = "Cukup"; $warna = "warning"; }
                                        else { $predikat = "Perlu Bimbingan"; $warna = "danger"; }

                                        // Highlight UI untuk Top 3
                                        $kelas_baris = "";
                                        $ikon_medali = "";
                                        if ($no == 1) { $kelas_baris = "rank-1"; $ikon_medali = "<i class='fas fa-medal medal-1 fs-5 me-2'></i>"; }
                                        elseif ($no == 2) { $kelas_baris = "rank-2"; $ikon_medali = "<i class='fas fa-medal medal-2 fs-5 me-2'></i>"; }
                                        elseif ($no == 3) { $kelas_baris = "rank-3"; $ikon_medali = "<i class='fas fa-medal medal-3 fs-5 me-2'></i>"; }
                                ?>
                                <tr class="<?= $kelas_baris; ?>">
                                    <td class="ps-4">
                                        <?= $ikon_medali; ?>
                                        <span class="badge bg-dark rounded-pill px-3 fs-6"><?= $no; ?></span>
                                    </td>
                                    <td><small class="text-muted border px-2 py-1 rounded bg-white"><?= $row['nis']; ?></small></td>
                                    <td class="fw-bold text-dark fs-6"><?= $row['nama_siswa']; ?></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="fw-bold text-dark me-2"><?= number_format($rata, 2); ?></span>
                                            <div class="progress w-50" style="height: 8px;">
                                                <div class="progress-bar bg-<?= $warna; ?>" role="progressbar" 
                                                     style="width: <?= $rata; ?>%;" aria-valuenow="<?= $rata; ?>" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $warna; ?>-subtle text-<?= $warna; ?> border border-<?= $warna; ?> rounded-pill px-3 py-2">
                                            <?= $predikat; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php 
                                        $no++;
                                    endwhile; 
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i class='fas fa-box-open fs-2 d-block mb-3'></i>Belum ada data nilai rapor untuk di-ranking. Silakan input nilai di menu Wali Kelas.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-white shadow-sm rounded-4 border-start border-4 border-primary">
                <p class="mb-0 text-muted small">
                    <strong><i class="fas fa-info-circle me-1"></i> Informasi:</strong> Sistem Ranking ini dihitung secara otomatis dengan mencari <strong>Nilai Rata-rata</strong> dari seluruh mata pelajaran yang telah divalidasi dan diinput oleh Wali Kelas pada kolom <em>Nilai Rapor</em>. Siswa yang belum memiliki nilai rapor tidak akan masuk ke dalam daftar peringkat.
                </p>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>