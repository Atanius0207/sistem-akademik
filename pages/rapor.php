<?php 
include '../config/db.php';

// Menangkap ID Mata Pelajaran yang dipilih
$mapel_aktif = isset($_GET['mapel']) ? $_GET['mapel'] : '';

// Logika Simpan Nilai Rapor oleh Wali Kelas
if (isset($_POST['simpan_rapor']) && $mapel_aktif != '') {
    $rapor_arr = $_POST['nilai_rapor'];

    foreach ($rapor_arr as $id_siswa => $rapor) {
        // Cek apakah data nilai sudah ada
        $cek = mysqli_query($conn, "SELECT id_nilai FROM nilai WHERE id_siswa = '$id_siswa' AND id_mapel = '$mapel_aktif'");
        
        if (mysqli_num_rows($cek) > 0) {
            // Update khusus kolom nilai_rapor
            mysqli_query($conn, "UPDATE nilai SET nilai_rapor = '$rapor' WHERE id_siswa = '$id_siswa' AND id_mapel = '$mapel_aktif'");
        } else {
            // Jika Guru Mapel belum input apa-apa, tapi Wali Kelas ingin input rapor duluan
            mysqli_query($conn, "INSERT INTO nilai (id_siswa, id_mapel, nilai_rapor) VALUES ('$id_siswa', '$mapel_aktif', '$rapor')");
        }
    }
    $pesan_sukses = "Nilai Rapor final berhasil divalidasi dan disimpan!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Rapor (Wali Kelas) - EduRank</title>
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
                    <a class="nav-link text-white" href="absensi.php"><i class="fas fa-calendar-check me-2"></i> Rekap Absensi</a>
                </li>

                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Guru Mapel</small></li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="nilai.php"><i class="fas fa-edit me-2"></i> Input Nilai Mentah</a>
                </li>
                
                <li class="nav-item mt-3 mb-1"><small class="text-white-50 fw-bold ms-3 text-uppercase border-bottom border-secondary pb-1 d-block">Wali Kelas</small></li>
                <li class="nav-item">
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="rapor.php">
                        <i class="fas fa-book text-primary me-2"></i> Input Nilai Rapor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="ranking.php"><i class="fas fa-trophy me-2"></i> Sistem Ranking</a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <h3 class="fw-bold text-dark"><i class="fas fa-user-shield text-success me-2"></i> Validasi Nilai Rapor (Wali Kelas)</h3>
            </div>

            <?php if(isset($pesan_sukses)): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $pesan_sukses; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body bg-light" style="border-radius: 15px;">
                    <form action="" method="GET" class="d-flex align-items-center">
                        <label class="fw-bold me-3 text-secondary"><i class="fas fa-filter me-1"></i> Pilih Mapel:</label>
                        <select name="mapel" class="form-select w-25 shadow-sm border-success" onchange="this.form.submit()" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            <?php
                            $qMapel = mysqli_query($conn, "SELECT * FROM mapel ORDER BY nama_mapel ASC");
                            while($m = mysqli_fetch_assoc($qMapel)){
                                $selected = ($mapel_aktif == $m['id_mapel']) ? 'selected' : '';
                                echo "<option value='{$m['id_mapel']}' $selected>{$m['nama_mapel']}</option>";
                            }
                            ?>
                        </select>
                        <?php if($mapel_aktif == ''): ?>
                            <span class="ms-3 text-danger small"><i class="fas fa-arrow-left me-1"></i> Silakan pilih mapel untuk memvalidasi nilai.</span>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if($mapel_aktif != ''): ?>
            <form action="rapor.php?mapel=<?= $mapel_aktif; ?>" method="POST">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="m-0 fw-bold text-success"><i class="fas fa-clipboard-check me-2"></i> Panel Penilaian Wali Kelas</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-success">
                                    <tr>
                                        <th class="ps-4">Nama Siswa</th>
                                        <th class="text-center" width="130">Kehadiran <br><small class="text-success">(10%)</small></th>
                                        <th class="text-center text-muted" width="100"><small>Rata Tugas</small><br><small>(20%)</small></th>
                                        <th class="text-center text-muted" width="100"><small>UTS</small><br><small>(30%)</small></th>
                                        <th class="text-center text-muted" width="100"><small>UAS</small><br><small>(40%)</small></th>
                                        <th class="text-center bg-warning-subtle text-dark" width="130"><i class="fas fa-robot me-1"></i> Sistem</th>
                                        <th width="150" class="bg-success text-white text-center">RAPOR FINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query Super: Menggabungkan Siswa, Nilai Akademik, dan Kalkulasi Absensi secara Real-time
                                    $sql = "SELECT s.id_siswa, s.nama_siswa, n.tugas, n.uts, n.uas, n.nilai_rapor,
                                            COALESCE(ab.total_hadir, 0) as total_hadir,
                                            COALESCE(ab.total_pertemuan, 0) as total_pertemuan
                                            FROM siswa s 
                                            LEFT JOIN nilai n ON s.id_siswa = n.id_siswa AND n.id_mapel = '$mapel_aktif' 
                                            LEFT JOIN (
                                                SELECT id_siswa, 
                                                       COUNT(*) as total_pertemuan, 
                                                       SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir 
                                                FROM absensi 
                                                GROUP BY id_siswa
                                            ) ab ON s.id_siswa = ab.id_siswa
                                            ORDER BY s.nama_siswa ASC";
                                    
                                    $query = mysqli_query($conn, $sql);
                                    
                                    while($row = mysqli_fetch_assoc($query)) :
                                        // 1. Kalkulasi Kehadiran
                                        $tot_pertemuan = $row['total_pertemuan'];
                                        $tot_hadir = $row['total_hadir'];
                                        
                                        $persen_absen = ($tot_pertemuan > 0) ? round(($tot_hadir / $tot_pertemuan) * 100) : 0;

                                        // 2. Kalkulasi Sistem (Akademik + Kehadiran)
                                        $tugas = $row['tugas'] ?? 0;
                                        $uts = $row['uts'] ?? 0;
                                        $uas = $row['uas'] ?? 0;
                                        
                                        // RUMUS BARU: Tugas(20%) + UTS(30%) + UAS(40%) + Absen(10%)
                                        // Jika persentase absen 0 (belum ada absen), kita asumsikan 100% sementara agar nilai anak tidak hancur di awal semester
                                        $absen_hitung = ($tot_pertemuan > 0) ? $persen_absen : 100; 
                                        
                                        $kalkulasi = ($tugas * 0.2) + ($uts * 0.3) + ($uas * 0.4) + ($absen_hitung * 0.1);

                                        // 3. Penentuan Warna Indikator Kehadiran
                                        if ($persen_absen >= 85) { $warna_absen = "success"; } 
                                        elseif ($persen_absen >= 70) { $warna_absen = "warning"; } 
                                        else { $warna_absen = "danger"; }
                                    ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark fs-6"><?= $row['nama_siswa']; ?></td>
                                        
                                        <td class="text-center">
                                            <?php if($tot_pertemuan > 0): ?>
                                                <span class="badge bg-<?= $warna_absen; ?>-subtle text-<?= $warna_absen; ?> border border-<?= $warna_absen; ?> px-2 py-1 rounded" title="Hadir <?= $tot_hadir; ?> dari <?= $tot_pertemuan; ?> hari">
                                                    <i class="fas fa-user-clock me-1"></i> <?= $persen_absen; ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border px-2 py-1 rounded" title="Belum ada data absen. Dianggap 100% sementara.">N/A</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center text-muted"><?= $tugas; ?></td>
                                        <td class="text-center text-muted"><?= $uts; ?></td>
                                        <td class="text-center text-muted"><?= $uas; ?></td>
                                        
                                        <td class="text-center bg-warning-subtle fw-bold text-dark fs-6" title="Tgs(20%) + UTS(30%) + UAS(40%) + Absen(10%)">
                                            <?= number_format($kalkulasi, 1); ?>
                                        </td>
                                        
                                        <td class="bg-success-subtle px-3 py-2">
                                            <input type="number" step="0.01" name="nilai_rapor[<?= $row['id_siswa']; ?>]" 
                                                   class="form-control border-success fw-bold text-center text-success shadow-sm" 
                                                   value="<?= $row['nilai_rapor'] ?? number_format($kalkulasi, 0); ?>" min="0" max="100">
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 rounded-bottom-4 d-flex justify-content-between align-items-center">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-info-circle text-primary me-1"></i> <strong>Sistem</strong> menghitung nilai final dengan bobot: Tugas (20%) + UTS (30%) + UAS (40%) + Kehadiran (10%).
                        </p>
                        <button type="submit" name="simpan_rapor" class="btn btn-success px-5 shadow-sm">
                            <i class="fas fa-check-double me-2"></i> Validasi & Simpan Rapor
                        </button>
                    </div>
                </div>
            </form>
            <?php endif; ?>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>