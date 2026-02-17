<?php 
include '../config/db.php';

// Logika Tambah Siswa Baru
if (isset($_POST['tambah'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama_siswa'];
    $jk = $_POST['jk'];

    // Validasi NIS agar tidak duplikat
    $cek_nis = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$nis'");
    if(mysqli_num_rows($cek_nis) > 0) {
        header("Location: siswa.php?status=gagal_nis");
        exit();
    } else {
        $query = mysqli_query($conn, "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin) VALUES ('$nis', '$nama', '$jk')");
        if ($query) {
            header("Location: siswa.php?status=sukses");
            exit();
        }
    }
}

// Logika Hapus Siswa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = '$id'");
    header("Location: siswa.php?status=terhapus");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - EduRank</title>
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
                    <a class="nav-link active bg-white text-primary rounded shadow-sm" href="siswa.php">
                        <i class="fas fa-users me-2"></i> Data Siswa
                    </a>
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
                    <a class="nav-link text-white" href="ranking.php"><i class="fas fa-trophy me-2"></i> Sistem Ranking</a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                <h3 class="fw-bold text-dark"><i class="fas fa-user-graduate text-primary me-2"></i> Manajemen Data Siswa</h3>
                <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-2"></i> Tambah Siswa Baru
                </button>
            </div>

            <?php if(isset($_GET['status'])): ?>
                <?php if($_GET['status'] == 'sukses'): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> Data siswa berhasil <strong>ditambahkan</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif($_GET['status'] == 'terhapus'): ?>
                    <div class="alert alert-primary alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Data siswa berhasil <strong>dihapus</strong> beserta seluruh riwayat nilainya.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif($_GET['status'] == 'gagal_nis'): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Gagal!</strong> NIS tersebut sudah terdaftar di sistem.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">L/P</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dataSiswa = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama_siswa ASC");
                                
                                if(mysqli_num_rows($dataSiswa) > 0) {
                                    while($row = mysqli_fetch_assoc($dataSiswa)) :
                                ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold"><?= $no++; ?></td>
                                    <td><span class="badge bg-light text-dark border px-3 py-2"><?= $row['nis']; ?></span></td>
                                    <td class="fw-bold text-dark fs-6"><?= $row['nama_siswa']; ?></td>
                                    <td class="text-center">
                                        <?php if($row['jenis_kelamin'] == 'L'): ?>
                                            <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3"><i class="fas fa-mars me-1"></i> Laki-laki</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3"><i class="fas fa-venus me-1"></i> Perempuan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="siswa.php?hapus=<?= $row['id_siswa']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('PERINGATAN! Menghapus data <?= $row['nama_siswa']; ?> akan menghapus seluruh data nilai dan absensinya. Anda yakin?')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted'>Belum ada data siswa yang terdaftar.</td></tr>";
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <form action="" method="POST">
                <div class="modal-header border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-user-plus text-primary me-2"></i> Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nomor Induk Siswa (NIS)</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                            <input type="text" name="nis" class="form-control border-start-0 ps-0" placeholder="Contoh: 100234" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Lengkap</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="nama_siswa" class="form-control border-start-0 ps-0" placeholder="Masukkan nama lengkap siswa" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Jenis Kelamin</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-venus-mars text-muted"></i></span>
                            <select name="jk" class="form-select border-start-0 ps-0" required>
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary px-5 shadow-sm">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>