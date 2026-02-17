<?php 
include '../config/db.php';

// Menentukan tanggal aktif untuk Tab Input Harian
$tanggal_aktif = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Logika Simpan / Update Absensi Harian
if (isset($_POST['simpan_absen'])) {
    $tanggal_input = $_POST['tanggal_input'];
    $kehadiran = $_POST['status']; 

    foreach ($kehadiran as $id_siswa => $status) {
        $cek = mysqli_query($conn, "SELECT id_absensi FROM absensi WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal_input'");
        
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($conn, "UPDATE absensi SET status = '$status' WHERE id_siswa = '$id_siswa' AND tanggal = '$tanggal_input'");
        } else {
            mysqli_query($conn, "INSERT INTO absensi (id_siswa, tanggal, status) VALUES ('$id_siswa', '$tanggal_input', '$status')");
        }
    }
    $pesan_sukses = "Rekap absensi harian tanggal " . date('d F Y', strtotime($tanggal_input)) . " berhasil disimpan!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Absensi - EduRank</title>
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
                    <a class="nav-link text-white" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="siswa.php"><i class="fas fa-users me-2"></i> Data Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="absensi.php">
                        <i class="fas fa-calendar-check me-2"></i> Manajemen Absensi
                    </a>
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
                    <a class="nav-link text-white" href="ranking.php"><i class="fas fa-trophy me-2"></i> Sistem Ranking</a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <h3 class="fw-bold text-dark"><i class="fas fa-clipboard-list text-primary me-2"></i> Manajemen Absensi Siswa</h3>
            </div>

            <?php if(isset($pesan_sukses)): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $pesan_sukses; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <ul class="nav nav-pills mb-4 shadow-sm bg-white p-2 rounded-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold px-4 rounded-pill" id="pills-harian-tab" data-bs-toggle="pill" data-bs-target="#pills-harian" type="button" role="tab">
                        <i class="fas fa-calendar-day me-2"></i> Input Harian
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold px-4 rounded-pill" id="pills-semester-tab" data-bs-toggle="pill" data-bs-target="#pills-semester" type="button" role="tab">
                        <i class="fas fa-chart-pie me-2"></i> Laporan 1 Semester
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                
                <div class="tab-pane fade show active" id="pills-harian" role="tabpanel">
                    <div class="card shadow border-0 mb-4" style="border-radius: 15px;">
                        <div class="card-body bg-light" style="border-radius: 15px;">
                            <form action="" method="GET" class="d-flex align-items-center">
                                <label class="fw-bold me-3 text-secondary"><i class="fas fa-search me-1"></i> Pilih Tanggal Absen:</label>
                                <input type="date" name="tanggal" class="form-control w-25 shadow-sm border-primary text-primary fw-bold" 
                                       value="<?= $tanggal_aktif; ?>" onchange="this.form.submit()" required>
                            </form>
                        </div>
                    </div>

                    <form action="absensi.php?tanggal=<?= $tanggal_aktif; ?>" method="POST">
                        <input type="hidden" name="tanggal_input" value="<?= $tanggal_aktif; ?>">
                        <div class="card shadow-sm border-0" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3 border-0">
                                <h6 class="m-0 fw-bold text-secondary">
                                    Daftar Hadir - Tanggal: <span class="text-primary"><?= date('d F Y', strtotime($tanggal_aktif)); ?></span>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-primary text-white">
                                            <tr>
                                                <th class="ps-4">No</th>
                                                <th>Nama Siswa</th>
                                                <th class="text-center">Hadir</th>
                                                <th class="text-center text-warning">Sakit</th>
                                                <th class="text-center text-info">Izin</th>
                                                <th class="text-center text-danger">Alfa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $sql_harian = "SELECT s.id_siswa, s.nama_siswa, a.status 
                                                           FROM siswa s 
                                                           LEFT JOIN absensi a ON s.id_siswa = a.id_siswa AND a.tanggal = '$tanggal_aktif' 
                                                           ORDER BY s.nama_siswa ASC";
                                            $queryHarian = mysqli_query($conn, $sql_harian);
                                            
                                            while($row = mysqli_fetch_assoc($queryHarian)) :
                                                $status_saat_ini = $row['status'] ?? 'Hadir';
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                                                <td class="fw-bold text-dark"><?= $row['nama_siswa']; ?></td>
                                                <td class="text-center bg-light">
                                                    <input class="form-check-input border-primary" type="radio" name="status[<?= $row['id_siswa']; ?>]" value="Hadir" <?= ($status_saat_ini == 'Hadir') ? 'checked' : ''; ?>>
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input border-warning" type="radio" name="status[<?= $row['id_siswa']; ?>]" value="Sakit" <?= ($status_saat_ini == 'Sakit') ? 'checked' : ''; ?>>
                                                </td>
                                                <td class="text-center bg-light">
                                                    <input class="form-check-input border-info" type="radio" name="status[<?= $row['id_siswa']; ?>]" value="Izin" <?= ($status_saat_ini == 'Izin') ? 'checked' : ''; ?>>
                                                </td>
                                                <td class="text-center">
                                                    <input class="form-check-input border-danger" type="radio" name="status[<?= $row['id_siswa']; ?>]" value="Alfa" <?= ($status_saat_ini == 'Alfa') ? 'checked' : ''; ?>>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 text-end py-3 rounded-bottom-4">
                                <button type="submit" name="simpan_absen" class="btn btn-primary px-5 shadow">
                                    <i class="fas fa-save me-2"></i> Simpan Rekap Harian
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="pills-semester" role="tabpanel">
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-bar me-2"></i> Rekapitulasi Kehadiran Siswa (Keseluruhan)</h6>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Cetak Laporan
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="text-center text-uppercase small">
                                            <th class="ps-4 text-start">Nama Siswa</th>
                                            <th width="120" class="text-success">Hadir</th>
                                            <th width="120" class="text-warning">Sakit</th>
                                            <th width="120" class="text-info">Izin</th>
                                            <th width="120" class="text-danger">Alfa</th>
                                            <th width="150" class="bg-primary text-white">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // SQL Pivot untuk menjumlahkan masing-masing status kehadiran per siswa
                                        $sql_semester = "
                                            SELECT 
                                                s.nama_siswa,
                                                COUNT(a.id_absensi) as total_pertemuan,
                                                SUM(CASE WHEN a.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir,
                                                SUM(CASE WHEN a.status = 'Sakit' THEN 1 ELSE 0 END) as total_sakit,
                                                SUM(CASE WHEN a.status = 'Izin' THEN 1 ELSE 0 END) as total_izin,
                                                SUM(CASE WHEN a.status = 'Alfa' THEN 1 ELSE 0 END) as total_alfa
                                            FROM siswa s
                                            LEFT JOIN absensi a ON s.id_siswa = a.id_siswa
                                            GROUP BY s.id_siswa
                                            ORDER BY s.nama_siswa ASC
                                        ";
                                        
                                        $querySemester = mysqli_query($conn, $sql_semester);
                                        
                                        while($row = mysqli_fetch_assoc($querySemester)) :
                                            $tot_pertemuan = $row['total_pertemuan'];
                                            $hadir = $row['total_hadir'] ?? 0;
                                            $sakit = $row['total_sakit'] ?? 0;
                                            $izin = $row['total_izin'] ?? 0;
                                            $alfa = $row['total_alfa'] ?? 0;
                                            
                                            // Kalkulasi Persentase
                                            $persen = ($tot_pertemuan > 0) ? round(($hadir / $tot_pertemuan) * 100) : 0;
                                            
                                            // Logika Warna Persentase
                                            if ($persen >= 85) { $warna = "success"; } 
                                            elseif ($persen >= 70) { $warna = "warning"; } 
                                            else { $warna = "danger"; }
                                        ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark fs-6"><?= $row['nama_siswa']; ?></td>
                                            
                                            <td class="text-center">
                                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fs-6"><?= $hadir; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2 fs-6"><?= $sakit; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-subtle text-info border border-info px-3 py-2 fs-6"><?= $izin; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fs-6"><?= $alfa; ?></span>
                                            </td>
                                            
                                            <td class="text-center bg-light">
                                                <?php if($tot_pertemuan > 0): ?>
                                                    <span class="fw-bold text-<?= $warna; ?> fs-5"><?= $persen; ?>%</span>
                                                    <div class="progress mt-1 mx-auto" style="height: 5px; width: 80%;">
                                                        <div class="progress-bar bg-<?= $warna; ?>" role="progressbar" style="width: <?= $persen; ?>%;"></div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div> </main>
    </div>
</div>

<style>
@media print {
    .sidebar, .nav-pills, .btn, .alert { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>