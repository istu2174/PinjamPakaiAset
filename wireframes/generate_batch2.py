"""
B&W Wireframe Batch 2: Modul Pengelola (Wireframes 13 - 19)
Strictly Monochrome / Grayscale Balsamiq Style
"""

import os
from generator import WireframeBuilder

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def create_13_pengelola_dashboard():
    wb = WireframeBuilder("SIPANDA - Dashboard Pengelola", "https://sipanda.dispora.palembang.go.id/pengelola/dashboard", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Dashboard", page_title="Dashboard Pengelola Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # 4 KPI Stat Cards
    stat_y = cy
    stat_w = (cw - 30) / 4
    stats = [
        ("Pengajuan Baru", "4", "Perlu Verifikasi"),
        ("Aset Siap Pakai", "24", "Kondisi Layak"),
        ("Sedang Dipinjam", "6", "Dalam Peminjaman"),
        ("Keluhan Aktif", "3", "Belum Ditangani")
    ]
    for i, (lbl, val, sub) in enumerate(stats):
        x = cx + i * (stat_w + 10)
        wb.rect(x, stat_y, stat_w, 65, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        wb.text(x + 10, stat_y + 18, lbl.upper(), size=8, weight="bold", fill="#555555")
        wb.text(x + 10, stat_y + 44, val, size=20, weight="bold", fill="#000000")
        wb.text(x + 10, stat_y + 56, sub, size=7, fill="#777777")
        
    # 2 Columns Split
    col_y = stat_y + 78
    col_w = (cw - 14) / 2
    col_h = 360
    
    # Left: Pengajuan Perlu Verifikasi
    wb.rect(cx, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 12, col_y + 22, "Perlu Verifikasi Pengelola", size=11, weight="bold", fill="#000000")
    wb.text(cx + col_w - 80, col_y + 22, "Lihat Semua →", size=9, fill="#000000")
    wb.line(cx, col_y + 32, cx + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    reqs = [
        ("Ahmad Fauzi (Bappeda)", "Sound System Wireless (2 Unit)", "Prioritas: 3/3 (Pemerintah)"),
        ("Rahmat Hidayat (KNPI)", "Tenda Kerucut 3x3m (4 Unit)", "Prioritas: 2/3 (Organisasi)"),
        ("Siti Rahma (Umum)", "Kursi Chitose (30 Unit)", "Prioritas: 1/3 (Umum)")
    ]
    ry = col_y + 42
    for peminjam, aset, prior in reqs:
        wb.rect(cx + 8, ry, col_w - 16, 76, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        wb.text(cx + 16, ry + 22, peminjam, size=10, weight="bold", fill="#000000")
        wb.text(cx + 16, ry + 36, aset, size=8, fill="#555555")
        wb.text_bar(cx + 16, ry + 44, 140, h=5, fill="#888888")
        wb.pill_badge(cx + 16, ry + 54, 150, 16, prior, is_dark=("3/3" in prior))
        wb.button(cx + col_w - 95, ry + 22, 80, 28, "Verifikasi →", style="default", size=8, rx=4)
        ry += 84
        
    # Right: Keluhan Masuk
    rx_col = cx + col_w + 14
    wb.rect(rx_col, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 12, col_y + 22, "Laporan Keluhan Aset Masuk", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, col_y + 32, rx_col + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    kls = [
        ("Mic wireless kresek & drop", "Sound System · Bappeda", "Pending"),
        ("Tiang tenda sudut kiri bengkok", "Tenda Kerucut · KNPI", "Pending"),
        ("Oli genset merembes", "Genset 5KVA · Dispora", "Diproses")
    ]
    ky = col_y + 42
    for judul, sub, st in kls:
        wb.rect(rx_col + 8, ky, col_w - 16, 76, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        wb.text(rx_col + 16, ky + 22, judul, size=10, weight="bold", fill="#000000")
        wb.text(rx_col + 16, ky + 36, sub, size=8, fill="#555555")
        wb.text_bar(rx_col + 16, ky + 44, 140, h=5, fill="#888888")
        wb.pill_badge(rx_col + 16, ky + 54, 70, 16, st, is_dark=(st=="Pending"))
        wb.button(rx_col + col_w - 85, ky + 22, 70, 28, "Respon", style="default", size=8, rx=4)
        ky += 84
        
    wb.save("wireframe_13_pengelola_dashboard.svg")


def create_14_pengelola_peminjaman_index():
    wb = WireframeBuilder("SIPANDA - Daftar Pengajuan Pinjaman Pengelola", "https://sipanda.dispora.palembang.go.id/pengelola/peminjaman", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Pengajuan", page_title="Daftar Pengajuan Pinjaman")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Filter Tabs
    tabs = ["Semua (12)", "Pending (4)", "Terverifikasi (3)", "Disetujui (2)", "Dipinjam (2)", "Selesai (1)"]
    tab_x = cx
    for i, t in enumerate(tabs):
        w = len(t) * 7 + 16
        style = "active" if i == 0 else "default"
        wb.button(tab_x, cy, w, 26, t, style=style, size=9, rx=5)
        tab_x += w + 6
        
    wb.input_field(cx + cw - 240, cy, 240, 26, placeholder="search pemohon/aset...")
    
    # Table List
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "TRX ID"), (cx + 115, "PEMOHON"), (cx + 260, "ASET"),
        (cx + 410, "JADWAL"), (cx + 510, "PRIORITAS"), (cx + 620, "STATUS"), (cx + 720, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "TRX-0042", "Ahmad Fauzi (Bappeda)", "Sound System 2 Unit", "20-22 Agt", "Skor 3 (Pemerintah)", "Pending"),
        ("2", "TRX-0043", "Rahmat H. (KNPI)", "Tenda Kerucut 4 Unit", "21-23 Agt", "Skor 2 (Organisasi)", "Pending"),
        ("3", "TRX-0045", "Siti Rahma (Umum)", "Kursi Chitose 30 Unit", "25-26 Agt", "Skor 1 (Umum)", "Pending"),
        ("4", "TRX-0038", "Disdik Sumsel", "Panggung Portabel", "15-18 Agt", "Skor 3 (Pemerintah)", "Diverifikasi"),
        ("5", "TRX-0034", "KONI Palembang", "Matras Senam 6 Unit", "10-12 Agt", "Skor 2 (Organisasi)", "Disetujui"),
        ("6", "TRX-0029", "Sanggar Seni", "Genset 5KVA", "01-03 Agt", "Skor 1 (Umum)", "Selesai")
    ]
    ry = tbl_y + 36
    for no, code, name, asset, tgl, prior, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.text(cx + 40, ry + 30, code, size=9, weight="bold", fill="#000000")
        wb.text(cx + 115, ry + 30, name, size=9, fill="#000000")
        wb.text(cx + 260, ry + 30, asset, size=9, fill="#000000")
        wb.text(cx + 410, ry + 30, tgl, size=8, fill="#555555")
        wb.pill_badge(cx + 510, ry + 18, 100, 18, prior, is_dark=("Skor 3" in prior))
        wb.pill_badge(cx + 620, ry + 18, 70, 18, st, is_dark=(st=="Disetujui"))
        wb.button(cx + 720, ry + 14, 60, 26, "Periksa", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_14_pengelola_peminjaman_index.svg")


def create_15_pengelola_peminjaman_show():
    wb = WireframeBuilder("SIPANDA - Detail & Verifikasi Pengelola", "https://sipanda.dispora.palembang.go.id/pengelola/peminjaman/42", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Pengajuan", page_title="Verifikasi Pengajuan #TRX-0042")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.50
    col2_w = (cw - 14) * 0.50
    
    # Left: Data Permohonan
    wb.rect(cx, cy, col1_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Data Permohonan Peminjam", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    fields = [
        ("Nama Pemohon", "Ahmad Fauzi (Bappeda Palembang)"),
        ("Skor Prioritas", "Skor 3 / 3 (Pemerintah - Tertinggi)"),
        ("Aset Diminta", "Sound System Portable Wireless (2 Unit)"),
        ("Kode Aset", "AST-001 (Kondisi: Layak Pakai)"),
        ("Jadwal Pinjam", "20 Agustus 2026 s/d 22 Agustus 2026"),
        ("Keperluan Acara", "Turnamen Futsal Pemuda Walikota Cup"),
        ("Lokasi Acara", "GOR Ranau Jakabaring Palembang")
    ]
    dy = cy + 54
    for k, v in fields:
        wb.text(cx + 16, dy, k, size=9, fill="#555555")
        wb.text(cx + 110, dy, ": " + v, size=9, weight="bold" if "AST" in v or "Skor 3" in v else "normal", fill="#000000")
        dy += 28
        
    wb.text(cx + 16, dy + 10, "Dokumen Surat Permohonan:", size=9, fill="#555555")
    wb.rect(cx + 16, dy + 20, col1_w - 32, 50, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(cx + 26, dy + 42, "📄 Surat_Permohonan_Bappeda_No042.pdf", size=9, weight="bold", fill="#000000")
    wb.button(cx + col1_w - 85, dy + 28, 60, 24, "Lihat", style="default", size=8, rx=3)
    
    # Right: Panel Tindakan Pengelola
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Tindakan Verifikasi Pengelola", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.text(rx_col + 16, cy + 50, "1. Verifikasi Catatan Pengelola:", size=9, weight="bold", fill="#000000")
    wb.input_field(rx_col + 16, cy + 60, col2_w - 32, 46, label="", value="Aset telah diperiksa di gudang, kondisi fisik baik.", is_textarea=True)
    wb.button(rx_col + 16, cy + 114, 150, 32, "✓ Verifikasi & Kirim Admin", style="dark", size=9, rx=5)
    wb.button(rx_col + 172, cy + 114, 90, 32, "✕ Tolak", style="default", size=9, rx=5)
    
    wb.line(rx_col + 16, cy + 158, rx_col + col2_w - 16, cy + 158, stroke="#000000", stroke_width=1)
    
    wb.text(rx_col + 16, cy + 174, "2. Konfirmasi Serah Terima / Scan Barcode:", size=9, weight="bold", fill="#000000")
    wb.input_field(rx_col + 16, cy + 186, col2_w - 130, 30, placeholder="Scan token barcode...")
    wb.button(rx_col + col2_w - 106, cy + 186, 90, 30, "Konfirmasi Ambil", style="dark", size=8, rx=4)
    
    wb.line(rx_col + 16, cy + 230, rx_col + col2_w - 16, cy + 230, stroke="#000000", stroke_width=1)
    
    wb.text(rx_col + 16, cy + 246, "3. Konfirmasi Pengembalian & Kondisi:", size=9, weight="bold", fill="#000000")
    wb.select_field(rx_col + 16, cy + 264, col2_w - 32, 30, label="Kondisi Fisik Saat Kembali *", value="Kondisi Baik (Lengkap)")
    wb.input_field(rx_col + 16, cy + 314, col2_w - 32, 40, label="Catatan Pengembalian", placeholder="Barang kembali lengkap...", is_textarea=True)
    wb.button(rx_col + 16, cy + 368, col2_w - 32, 36, "✓ KONFIRMASI SELESAI PENGEMBALIAN", style="dark", size=10, rx=6)
    
    wb.save("wireframe_15_pengelola_peminjaman_show.svg")


def create_16_pengelola_aset_index():
    wb = WireframeBuilder("SIPANDA - Daftar Aset & Evaluasi DT", "https://sipanda.dispora.palembang.go.id/pengelola/aset", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Penilaian DT", page_title="Daftar Aset & Status Decision Tree")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Status Badges Bar
    wb.pill_badge(cx, cy, 120, 26, "Layak Pakai: 22", is_dark=True)
    wb.pill_badge(cx + 128, cy, 130, 26, "Perlu Servis: 3", is_dark=False)
    wb.pill_badge(cx + 266, cy, 130, 26, "Layak Lelang: 1", is_dark=False)
    wb.input_field(cx + cw - 240, cy, 240, 26, placeholder="search aset / kode...")
    
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "FOTO"), (cx + 90, "KODE & NAMA ASET"),
        (cx + 290, "KATEGORI"), (cx + 400, "KONDISI"), (cx + 490, "USIA"),
        (cx + 560, "HASIL DECISION TREE"), (cx + 720, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "AST-001", "Sound System Portable Yamaha", "Audio", "4/5 (Baik)", "2 Thn", "Layak Pakai"),
        ("2", "AST-004", "Tenda Kerucut 3x3m Sarnafil", "Tenda", "5/5 (Sangat Baik)", "1 Thn", "Layak Pakai"),
        ("3", "AST-005", "Genset Portable 5KVA Honda", "Mesin", "2/5 (Kurang)", "5 Thn", "Perlu Pemeliharaan"),
        ("4", "AST-009", "Matras Senam Busa Standard", "Olahraga", "1/5 (Rusak)", "7 Thn", "Layak Lelang / Afkir"),
        ("5", "AST-012", "Kursi Lipat Chitose", "Kursi", "4/5 (Baik)", "3 Thn", "Layak Pakai")
    ]
    ry = tbl_y + 36
    for no, code, name, cat, cond, age, dt_res in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.image_box(cx + 40, ry + 8, 38, 36, label="", rx=2)
        wb.text(cx + 90, ry + 24, name, size=9, weight="bold", fill="#000000")
        wb.text(cx + 90, ry + 38, f"Kode: {code}", size=8, fill="#555555")
        wb.text(cx + 290, ry + 30, cat, size=8, fill="#000000")
        wb.text(cx + 400, ry + 30, cond, size=8, fill="#000000")
        wb.text(cx + 490, ry + 30, age, size=8, fill="#555555")
        wb.pill_badge(cx + 560, ry + 18, 140, 18, dt_res, is_dark=(dt_res=="Layak Pakai"))
        wb.button(cx + 720, ry + 14, 80, 26, "Evaluasi DT", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_16_pengelola_aset_index.svg")


def create_17_pengelola_aset_penilaian_dt():
    wb = WireframeBuilder("SIPANDA - Penilaian Decision Tree Aset", "https://sipanda.dispora.palembang.go.id/pengelola/aset/5/penilaian", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Penilaian DT", page_title="Evaluasi Decision Tree C4.5")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Asset Info Top Box
    wb.rect(cx, cy, cw, 42, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 14, cy + 20, "ASET: GENSET PORTABLE 5KVA HONDA", size=10, weight="bold", fill="#000000")
    wb.text(cx + 14, cy + 34, "Kode: AST-005 · Kategori: Mesin / Elektronik · Lokasi: Gudang Sarpras Dispora", size=8, fill="#555555")
    
    col_y = cy + 50
    col1_w = (cw - 14) * 0.55
    col2_w = (cw - 14) * 0.45
    
    # Left: Form Parameter
    wb.rect(cx, col_y, col1_w, 470, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, col_y + 22, "Parameter Input Decision Tree", size=11, weight="bold", fill="#000000")
    wb.line(cx, col_y + 32, cx + col1_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    wb.text(cx + 16, col_y + 50, "1. Kondisi Fisik Barang * (Skala 1-5)", size=9, weight="bold", fill="#000000")
    rx_pos = cx + 16
    for n in [1, 2, 3, 4, 5]:
        style = "active" if n == 2 else "default"
        wb.button(rx_pos, col_y + 60, 42, 26, str(n), style=style, size=10, rx=4)
        rx_pos += 48
        
    wb.input_field(cx + 16, col_y + 110, col1_w - 32, 30, label="2. Usia Penggunaan Aset (Tahun) *", value="5")
    
    wb.text(cx + 16, col_y + 164, "3. Riwayat Servis / Perawatan Rutin *", size=9, weight="bold", fill="#000000")
    wb.button(cx + 16, col_y + 174, 120, 28, "[ ] Ya, Rutin", style="default", size=9, rx=4)
    wb.button(cx + 144, col_y + 174, 120, 28, "[X] Tidak / Jarang", style="active", size=9, rx=4)
    
    wb.text(cx + 16, col_y + 224, "4. Frekuensi Intensitas Pemakaian * (Skala 1-5)", size=9, weight="bold", fill="#000000")
    rx_pos = cx + 16
    for n in [1, 2, 3, 4, 5]:
        style = "active" if n == 4 else "default"
        wb.button(rx_pos, col_y + 234, 42, 26, str(n), style=style, size=10, rx=4)
        rx_pos += 48
        
    wb.input_field(cx + 16, col_y + 284, col1_w - 32, 30, label="5. Jumlah Riwayat Kerusakan *", value="3 Kali dalam 6 bulan")
    
    wb.button(cx + 16, col_y + 340, col1_w - 32, 38, "PROSES KLASIFIKASI POHON KEPUTUSAN →", style="dark", size=10, rx=6)
    
    # Right: Decision Tree Structure & Result
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, col_y, col2_w, 470, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, col_y + 22, "Pohon Keputusan (Rule Tree C4.5)", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, col_y + 32, rx_col + col2_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    # Recommendation Box
    wb.rect(rx_col + 16, col_y + 44, col2_w - 32, 60, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 24, col_y + 64, "HASIL REKOMENDASI SISTEM:", size=8, weight="bold", fill="#555555")
    wb.text(rx_col + 24, col_y + 82, "PERLU PEMELIHARAAN / SERVIS", size=11, weight="bold", fill="#000000")
    
    # Rule Tree Text Graphic
    wb.rect(rx_col + 16, col_y + 114, col2_w - 32, 200, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(rx_col + 24, col_y + 134, "Kondisi Fisik <= 2?", size=9, weight="bold", fill="#000000")
    wb.text(rx_col + 36, col_y + 152, "├── Ya: Usia Aset >= 5 Tahun?", size=8, fill="#000000")
    wb.text(rx_col + 50, col_y + 170, "├── Ya: Servis Rutin = Tidak?", size=8, fill="#000000")
    wb.text(rx_col + 64, col_y + 188, "└──► [ KEPUTUSAN: PERLU PERAWATAN ] ★", size=8, weight="bold", fill="#000000")
    wb.text(rx_col + 36, col_y + 210, "└── Tidak: Kondisi Fisik >= 4?", size=8, fill="#000000")
    wb.text(rx_col + 50, col_y + 228, "└──► [ KEPUTUSAN: LAYAK PAKAI ]", size=8, fill="#555555")
    wb.text(rx_col + 24, col_y + 254, "Confidence Level: 92.4%   |   Entropy: 0.18", size=8, fill="#555555")
    
    wb.button(rx_col + 16, col_y + 340, col2_w - 32, 38, "Simpan Hasil Klasifikasi", style="default", size=10, rx=6)
    
    wb.save("wireframe_17_pengelola_aset_penilaian_dt.svg")


def create_18_pengelola_keluhan_index():
    wb = WireframeBuilder("SIPANDA - Daftar Keluhan Masuk Pengelola", "https://sipanda.dispora.palembang.go.id/pengelola/keluhan", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Keluhan Masuk", page_title="Daftar Keluhan Masuk")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    tabs = ["Semua (6)", "Menunggu Respon (3)", "Sedang Diproses (2)", "Selesai (1)"]
    tab_x = cx
    for i, t in enumerate(tabs):
        w = len(t) * 7 + 16
        style = "active" if i == 0 else "default"
        wb.button(tab_x, cy, w, 26, t, style=style, size=9, rx=5)
        tab_x += w + 6
        
    wb.input_field(cx + cw - 240, cy, 240, 26, placeholder="search keluhan...")
    
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "TGL"), (cx + 120, "PELAPOR"), (cx + 260, "ASET TERKAIT"),
        (cx + 430, "JUDUL KELUHAN"), (cx + 630, "STATUS"), (cx + 720, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "16 Agt", "Ahmad Fauzi (Bappeda)", "Sound System Yamaha", "Mic wireless kresek & drop", "Pending"),
        ("2", "14 Agt", "Rahmat H. (KNPI)", "Tenda Kerucut 3x3m", "Tiang tenda sudut kiri bengkok", "Pending"),
        ("3", "12 Agt", "Disdik Sumsel", "Panggung Portabel", "Baut pengunci lantai hilang", "Pending"),
        ("4", "10 Agt", "Staff Dispora", "Genset Portable 5KVA", "Oli mesin merembes", "Diproses"),
        ("5", "05 Agt", "KONI Palembang", "Matras Senam", "Cover matras sobek 15cm", "Diproses")
    ]
    ry = tbl_y + 36
    for no, tgl, pelapor, aset, judul, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.text(cx + 40, ry + 30, tgl, size=8, fill="#555555")
        wb.text(cx + 120, ry + 30, pelapor, size=9, weight="bold", fill="#000000")
        wb.text(cx + 260, ry + 30, aset, size=8, fill="#000000")
        wb.text(cx + 430, ry + 30, judul[:30] + "...", size=8, fill="#000000")
        wb.pill_badge(cx + 630, ry + 18, 70, 18, st, is_dark=(st=="Pending"))
        wb.button(cx + 720, ry + 14, 70, 26, "Tanggapi", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_18_pengelola_keluhan_index.svg")


def create_19_pengelola_keluhan_show():
    wb = WireframeBuilder("SIPANDA - Respon Keluhan Pengelola", "https://sipanda.dispora.palembang.go.id/pengelola/keluhan/15", 1050, 750)
    wb.draw_app_layout(role="pengelola", active_menu="Keluhan Masuk", page_title="Respon Keluhan #KEL-0015")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.50
    col2_w = (cw - 14) * 0.50
    
    # Left: Laporan Peminjam
    wb.rect(cx, cy, col1_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Laporan dari Peminjam", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    fields = [
        ("Nama Pelapor", "Ahmad Fauzi (Bappeda Palembang)"),
        ("Nomor Kontak", "0812-3456-7890"),
        ("Aset Terkait", "Sound System Portable Wireless (AST-001)"),
        ("No Transaksi", "TRX-2026-0042"),
        ("Tanggal Lapor", "16 Agustus 2026, 14:30 WIB"),
        ("Judul Masalah", "Mikrofon wireless kresek & baterai drop")
    ]
    dy = cy + 54
    for k, v in fields:
        wb.text(cx + 16, dy, k, size=9, fill="#555555")
        wb.text(cx + 110, dy, ": " + v, size=9, weight="bold" if "Masalah" in k else "normal", fill="#000000")
        dy += 26
        
    wb.text(cx + 16, dy + 10, "Deskripsi Kerusakan:", size=9, fill="#555555")
    wb.rect(cx + 16, dy + 20, col1_w - 32, 60, rx=4, fill="#f8f8f8", stroke="#cccccc", stroke_width=1)
    wb.text(cx + 24, dy + 38, "Mic wireless nomor 2 berisik dan mati mendadak.", size=8, fill="#000000")
    
    wb.text(cx + 16, dy + 90, "Foto Bukti:", size=9, fill="#555555")
    wb.image_box(cx + 16, dy + 102, 130, 95, label="FOTO BUKTI", rx=4)
    
    # Right: Form Tindak Lanjut Pengelola
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Form Tindak Lanjut Pengelola", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.select_field(rx_col + 16, cy + 54, col2_w - 32, 32, label="Status Penanganan *", value="Sedang Ditangani (Teknisi Dijadwalkan)")
    wb.input_field(rx_col + 16, cy + 114, col2_w - 32, 32, label="Petugas Penanggung Jawab *", value="Rizki Pratama (Seksi Sarpras)")
    wb.input_field(rx_col + 16, cy + 174, col2_w - 32, 32, label="Estimasi Selesai Servis", value="18 Agustus 2026")
    wb.input_field(rx_col + 16, cy + 234, col2_w - 32, 80, label="Pesan Tanggapan / Solusi *", value="Tim teknisi rekanan telah dijadwalkan datang tgl 17 Agt untuk servis modul mic & baterai pack.", is_textarea=True)
    
    wb.checkbox(rx_col + 16, cy + 334, "Ubah status aset menjadi Maintenance sementara di katalog", checked=True)
    wb.button(rx_col + 16, cy + 380, col2_w - 32, 38, "SIMPAN RESPON & UPDATE STATUS →", style="dark", size=10, rx=6)
    
    wb.save("wireframe_19_pengelola_keluhan_show.svg")


def run_batch2():
    print("Generating B&W Wireframes Batch 2 (13-19)...")
    create_13_pengelola_dashboard()
    create_14_pengelola_peminjaman_index()
    create_15_pengelola_peminjaman_show()
    create_16_pengelola_aset_index()
    create_17_pengelola_aset_penilaian_dt()
    create_18_pengelola_keluhan_index()
    create_19_pengelola_keluhan_show()

if __name__ == '__main__':
    run_batch2()
