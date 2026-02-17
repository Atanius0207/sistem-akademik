<?php 
include '../config/db.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru & Wali Kelas - EduRank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
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
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="siswa.php">
                        <i class="fas fa-users me-2"></i> Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="absensi.php">
                        <i class="fas fa-calendar-check me-2"></i> Rekap Absensi
                    </a>
                </li>

                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Guru Mapel</small></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="nilai.php">
                        <i class="fas fa-edit me-2"></i> Input Nilai Mentah
                    </a>
                </li>
                
                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Wali Kelas</small></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="rapor.php">
                        <i class="fas fa-book text-white me-2"></i> Input Nilai Rapor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="ranking.php">
                        <i class="fas fa-trophy me-2"></i> Sistem Ranking
                    </a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="welcome-banner d-flex justify-content-between align-items-center mt-4 bg-white p-4 rounded-4 shadow-sm border-start border-primary border-5">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Selamat Datang di EduRank! 👋</h3>
                    <p class="text-muted mb-0">Sistem Informasi Akademik Lokal Terpadu.</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <p class="mb-0 fw-bold text-primary fs-5"><i class="fas fa-calendar-alt me-2"></i><?php echo date('l, d F Y'); ?></p>
                </div>
            </div>

            <div class="row mt-4">
                <?php
                // 1. Hitung Total Siswa
                $qSiswa = mysqli_query($conn, "SELECT COUNT(id_siswa) as total FROM siswa");
                $totalSiswa = mysqli_fetch_assoc($qSiswa)['total'] ?? 0;

                // 2. Hitung Absensi Hari Ini (Persentase Kehadiran)
                $hari_ini = date('Y-m-d');
                $qAbsen = mysqli_query($conn, "SELECT COUNT(id_absensi) as hadir FROM absensi WHERE tanggal = '$hari_ini' AND status = 'Hadir'");
                $jmlHadir = mysqli_fetch_assoc($qAbsen)['hadir'] ?? 0;
                $persenHadir = ($totalSiswa > 0) ? round(($jmlHadir / $totalSiswa) * 100) : 0;

                // 3. Hitung Rata-rata Kelas (Dari nilai rapor final)
                $qRata = mysqli_query($conn, "SELECT AVG(nilai_rapor) as rerata FROM nilai WHERE nilai_rapor > 0");
                $rataKelas = mysqli_fetch_assoc($qRata)['rerata'] ?? 0;
                ?>
                
                <div class="col-md-4 mb-4">
                    <div class="card card-stats shadow-sm border-0 p-3 h-100" style="border-left: 5px solid #4e73df !important;">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-primary text-white p-3 rounded-circle me-3 shadow-sm">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small fw-bold text-uppercase">Total Siswa Terdaftar</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?php echo $totalSiswa; ?> <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card card-stats shadow-sm border-0 p-3 h-100" style="border-left: 5px solid #1cc88a !important;">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-success text-white p-3 rounded-circle me-3 shadow-sm">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small fw-bold text-uppercase">Kehadiran Hari Ini</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?php echo $persenHadir; ?>%</h3>
                                <small class="text-success"><i class="fas fa-users me-1"></i> <?= $jmlHadir ?> Siswa Hadir</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card card-stats shadow-sm border-0 p-3 h-100" style="border-left: 5px solid #f6c23e !important;">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-warning text-white p-3 rounded-circle me-3 shadow-sm">
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small fw-bold text-uppercase">Rata-rata Rapor Kelas</h6>
                                <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($rataKelas, 1); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-2 mb-5" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-medal text-warning me-2 fs-5"></i> 5 Besar Peringkat Kelas (Berdasarkan Nilai Rapor)</h6>
                    <a href="ranking.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Rank</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Rata-rata Rapor</th>
                                    <th class="text-center">Status Akademik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mendapatkan rata-rata nilai rapor dari seluruh mapel per siswa
                                $queryRank = mysqli_query($conn, "
                                    SELECT s.nis, s.nama_siswa, AVG(n.nilai_rapor) as rata_rapor 
                                    FROM siswa s 
                                    JOIN nilai n ON s.id_siswa = n.id_siswa 
                                    GROUP BY s.id_siswa 
                                    HAVING rata_rapor > 0
                                    ORDER BY rata_rapor DESC 
                                    LIMIT 5
                                ");
                                
                                $no = 1;
                                if(mysqli_num_rows($queryRank) > 0) {
                                    while($row = mysqli_fetch_assoc($queryRank)){
                                        // Tentukan label status kelulusan
                                        $rata = $row['rata_rapor'];
                                        $status = ($rata >= 75) ? "<span class='badge bg-success-subtle text-success border border-success'>Sangat Baik</span>" : "<span class='badge bg-warning-subtle text-warning border border-warning'>Perlu Bimbingan</span>";
                                        
                                        // Visual mahkota untuk peringkat 1
                                        $ikon_rank = ($no == 1) ? "<i class='fas fa-crown text-warning me-1'></i>" : "";

                                        echo "<tr>
                                                <td class='ps-4'>$ikon_rank<span class='badge bg-primary rounded-pill px-3'>$no</span></td>
                                                <td><small class='text-muted'>{$row['nis']}</small></td>
                                                <td class='fw-bold text-dark'>{$row['nama_siswa']}</td>
                                                <td class='text-center'><h6 class='text-primary fw-bold mb-0'>" . number_format($rata, 2) . "</h6></td>
                                                <td class='text-center'>$status</td>
                                              </tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i class='fas fa-folder-open fs-3 d-block mb-2'></i>Belum ada data nilai rapor yang diinput oleh Wali Kelas.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>