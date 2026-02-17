<?php 
include '../config/db.php';

// Menangkap ID Mata Pelajaran yang dipilih
$mapel_aktif = isset($_GET['mapel']) ? $_GET['mapel'] : '';

// Logika Simpan Nilai Mentah oleh Guru Mapel
if (isset($_POST['simpan_nilai']) && $mapel_aktif != '') {
    $tugas_arr = $_POST['tugas_avg'];
    $uts_arr = $_POST['uts'];
    $uas_arr = $_POST['uas'];

    foreach ($tugas_arr as $id_siswa => $tugas_avg) {
        $uts = $uts_arr[$id_siswa];
        $uas = $uas_arr[$id_siswa];

        // Cek apakah data nilai untuk siswa dan mapel ini sudah ada
        $cek = mysqli_query($conn, "SELECT id_nilai FROM nilai WHERE id_siswa = '$id_siswa' AND id_mapel = '$mapel_aktif'");
        
        if (mysqli_num_rows($cek) > 0) {
            // Update nilai (PERHATIAN: kolom nilai_rapor tidak ikut di-update agar data Wali Kelas tidak tertimpa)
            mysqli_query($conn, "UPDATE nilai SET tugas = '$tugas_avg', uts = '$uts', uas = '$uas' WHERE id_siswa = '$id_siswa' AND id_mapel = '$mapel_aktif'");
        } else {
            // Insert nilai baru
            mysqli_query($conn, "INSERT INTO nilai (id_siswa, id_mapel, tugas, uts, uas) VALUES ('$id_siswa', '$mapel_aktif', '$tugas_avg', '$uts', '$uas')");
        }
    }
    $pesan_sukses = "Data nilai mentah Mata Pelajaran berhasil diperbarui!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Mentah - EduRank</title>
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
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="nilai.php">
                        <i class="fas fa-edit me-2"></i> Input Nilai Mentah
                    </a>
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
                <h3 class="fw-bold text-dark"><i class="fas fa-pen-nib text-primary me-2"></i> Input Nilai Mentah (Guru Mapel)</h3>
                <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCalc">
                    <i class="fas fa-calculator me-1"></i> Asisten Rata-rata Tugas
                </button>
            </div>  
            <?php if(isset($pesan_sukses)): ?>
                <div class="alert alert-primary alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $pesan_sukses; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-body bg-light" style="border-radius: 15px;">
                    <form action="" method="GET" class="d-flex align-items-center">
                        <label class="fw-bold me-3 text-secondary"><i class="fas fa-book-open me-1"></i> Pilih Mapel Anda:</label>
                        <select name="mapel" class="form-select w-25 shadow-sm border-primary" onchange="this.form.submit()" required>
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
                            <span class="ms-3 text-danger small"><i class="fas fa-arrow-left me-1"></i> Wajib pilih mapel untuk memunculkan daftar siswa.</span>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php if($mapel_aktif != ''): ?>
            <form action="nilai.php?mapel=<?= $mapel_aktif; ?>" method="POST">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-list-ol me-2"></i> Daftar Siswa & Kolom Nilai</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">No</th>
                                        <th>Nama Siswa</th>
                                        <th width="180" class="text-center">Rata-rata Tugas</th>
                                        <th width="180" class="text-center">Nilai UTS</th>
                                        <th width="180" class="text-center">Nilai UAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Mengambil data siswa digabung (LEFT JOIN) dengan nilai spesifik mapel terpilih
                                    $sql = "SELECT s.id_siswa, s.nama_siswa, n.tugas, n.uts, n.uas 
                                            FROM siswa s 
                                            LEFT JOIN nilai n ON s.id_siswa = n.id_siswa AND n.id_mapel = '$mapel_aktif' 
                                            ORDER BY s.nama_siswa ASC";
                                    $query = mysqli_query($conn, $sql);

                                    if(!$query) {
                                        die('<strong>Terjadi Kesalahan: </strong>
                                            <strong>'. mysqli_error($conn) .'</strong>');
                                    }
                                    
                                    while($row = mysqli_fetch_assoc($query)) :
                                    ?>
                                    <tr>
                                        <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td>
                                        <td class="fw-bold text-dark fs-6"><?= $row['nama_siswa']; ?></td>
                                        <td>
                                            <div class="input-group input-group-sm px-2">
                                                <span class="input-group-text bg-light text-muted border-secondary"><i class="fas fa-tasks"></i></span>
                                                <input type="number" step="0.01" name="tugas_avg[<?= $row['id_siswa']; ?>]" 
                                                       class="form-control border-secondary text-center fw-bold text-primary" 
                                                       value="<?= $row['tugas'] ?? 0; ?>" min="0" max="100">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm px-2">
                                                <span class="input-group-text bg-light text-muted border-secondary"><i class="fas fa-file-alt"></i></span>
                                                <input type="number" step="0.01" name="uts[<?= $row['id_siswa']; ?>]" 
                                                       class="form-control border-secondary text-center" 
                                                       value="<?= $row['uts'] ?? 0; ?>" min="0" max="100">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm px-2">
                                                <span class="input-group-text bg-light text-muted border-secondary"><i class="fas fa-file-signature"></i></span>
                                                <input type="number" step="0.01" name="uas[<?= $row['id_siswa']; ?>]" 
                                                       class="form-control border-secondary text-center" 
                                                       value="<?= $row['uas'] ?? 0; ?>" min="0" max="100">
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end bg-white py-3 border-0 rounded-bottom-4">
                        <button type="submit" name="simpan_nilai" class="btn btn-primary px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Nilai Mentah
                        </button>
                    </div>
                </div>
            </form>
            <?php endif; ?>

        </main>
    </div>
</div>

<div class="modal fade" id="modalCalc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-primary"><i class="fas fa-calculator me-2"></i> Asisten Rata-Rata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <p class="small text-muted mb-2">Ketikkan semua nilai tugas siswa untuk satu semester, pisahkan dengan tanda koma ( , ).</p>
                <textarea id="raw_values" class="form-control mb-3 border-primary shadow-sm" rows="3" placeholder="Contoh: 80, 85, 90, 75, 100"></textarea>
                <div class="p-3 bg-primary-subtle text-primary rounded-3 border border-primary text-center">
                    <span class="d-block small text-uppercase fw-bold mb-1">Hasil Rata-Rata</span>
                    <h2 class="fw-bold mb-0" id="calc_result">0.00</h2>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4 justify-content-center">
                <button type="button" class="btn btn-light px-4 w-100" data-bs-dismiss="modal">Tutup Asisten</button>
            </div>
        </div>
    </div>
</div>

<script>
// Logic ringan untuk Kalkulator Tugas secara realtime
document.getElementById('raw_values').addEventListener('input', function() {
    let rawStr = this.value.replace(/[^0-9,.]/g, ''); // Hanya boleh angka, koma, titik
    let vals = rawStr.split(',').map(Number).filter(n => n > 0);
    if(vals.length > 0) {
        let sum = vals.reduce((a, b) => a + b, 0);
        let avg = sum / vals.length;
        document.getElementById('calc_result').innerText = avg.toFixed(2);
    } else {
        document.getElementById('calc_result').innerText = "0.00";
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>