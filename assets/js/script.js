document.addEventListener("DOMContentLoaded", function() {
    
    // 1. UX: Auto-Dismiss Notifikasi (Alert) setelah 3.5 detik
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            // Menggunakan fungsi bawaan Bootstrap untuk menutup alert dengan animasi
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 3500); 
    });

    // 2. UX: Fitur Pencarian Cepat (Real-time Filter) di Tabel
    const searchInput = document.getElementById('searchSiswa');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");

            rows.forEach(function(row) {
                // Ambil semua teks dalam satu baris tabel
                let text = row.textContent.toLowerCase();
                // Jika teks cocok, tampilkan baris. Jika tidak, sembunyikan.
                if(text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
});