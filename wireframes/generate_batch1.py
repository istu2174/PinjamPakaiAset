"""
B&W Wireframe Batch 1: Auth & Peminjam (Wireframes 01 - 12)
Strictly Monochrome / Grayscale Balsamiq Style
"""

import os
from generator import WireframeBuilder

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def create_01_auth_login():
    wb = WireframeBuilder("SIPANDA - Login", "https://sipanda.dispora.palembang.go.id/login", 1000, 720)
    wb.draw_browser_frame()
    
    # Centered Card Container
    card_x, card_y, card_w, card_h = 320, 110, 360, 520
    wb.rect(card_x, card_y, card_w, card_h, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
    
    # App Logo box with X
    wb.image_box(card_x + 150, card_y + 24, 60, 54, label="LOGO", rx=4)
    
    wb.text(card_x + 180, card_y + 98, "SIPANDA DISPORA", size=16, weight="bold", fill="#000000", align="center")
    wb.text(card_x + 180, card_y + 114, "Sistem Peminjaman Aset Dinas", size=10, fill="#555555", align="center")
    wb.line(card_x + 24, card_y + 128, card_x + card_w - 24, card_y + 128, stroke="#000000", stroke_width=1)
    
    wb.text(card_x + 24, card_y + 152, "Selamat Datang", size=15, weight="bold", fill="#000000")
    wb.text_bar(card_x + 24, card_y + 164, 200, h=6, fill="#888888")
    
    # Inputs
    wb.input_field(card_x + 24, card_y + 200, card_w - 48, 36, label="Email", placeholder="nama@dispora.palembang.go.id")
    wb.input_field(card_x + 24, card_y + 270, card_w - 48, 36, label="Password", placeholder="••••••••")
    wb.text(card_x + card_w - 24, card_y + 265, "Lupa password?", size=10, fill="#000000", align="end")
    
    # Checkbox
    wb.checkbox(card_x + 24, card_y + 326, "Ingat saya di perangkat ini", checked=True)
    
    # Button
    wb.button(card_x + 24, card_y + 364, card_w - 48, 40, "Masuk Sekarang", style="dark", size=12, rx=8)
    
    wb.line(card_x + 24, card_y + 426, card_x + card_w - 24, card_y + 426, stroke="#000000", stroke_width=1)
    wb.text(card_x + 180, card_y + 450, "Belum punya akun peminjam?", size=10, fill="#555555", align="center")
    wb.button(card_x + 60, card_y + 464, card_w - 120, 28, "Daftar Akun Baru", style="default", size=10, rx=6)
    
    # Callout Arrow
    wb.callout_arrow(730, 290, card_x + card_w - 5, 220, label="Role Login: Admin / Pengelola / Peminjam")
    
    wb.save("wireframe_01_auth_login.svg")


def create_02_auth_register():
    wb = WireframeBuilder("SIPANDA - Register", "https://sipanda.dispora.palembang.go.id/register", 1000, 750)
    wb.draw_browser_frame()
    
    card_x, card_y, card_w, card_h = 280, 95, 440, 610
    wb.rect(card_x, card_y, card_w, card_h, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
    
    # Logo
    wb.image_box(card_x + 190, card_y + 16, 60, 46, label="LOGO", rx=4)
    wb.text(card_x + 220, card_y + 80, "Daftar Akun Peminjam", size=15, weight="bold", fill="#000000", align="center")
    wb.line(card_x + 24, card_y + 96, card_x + card_w - 24, card_y + 96, stroke="#000000", stroke_width=1)
    
    # Form fields
    wb.input_field(card_x + 24, card_y + 124, card_w - 48, 32, label="Nama Lengkap", placeholder="Ahmad Fauzi, S.STP")
    wb.input_field(card_x + 24, card_y + 182, card_w - 48, 32, label="Email", placeholder="ahmad@bappeda.palembang.go.id")
    wb.input_field(card_x + 24, card_y + 240, card_w - 48, 32, label="No. Telepon / WhatsApp", placeholder="0812-3456-7890")
    wb.select_field(card_x + 24, card_y + 298, card_w - 48, 32, label="Kategori Instansi", value="Pemerintah / OPD (Prioritas 3)")
    
    pw_w = (card_w - 60) / 2
    wb.input_field(card_x + 24, card_y + 356, pw_w, 32, label="Password", placeholder="••••••••")
    wb.input_field(card_x + 36 + pw_w, card_y + 356, pw_w, 32, label="Konfirmasi Password", placeholder="••••••••")
    
    wb.checkbox(card_x + 24, card_y + 412, "Saya menyetujui syarat & ketentuan peminjaman aset", checked=True)
    wb.button(card_x + 24, card_y + 446, card_w - 48, 40, "Daftar Sekarang", style="dark", size=12, rx=8)
    
    wb.line(card_x + 24, card_y + 508, card_x + card_w - 24, card_y + 508, stroke="#000000", stroke_width=1)
    wb.text(card_x + 220, card_y + 532, "Sudah memiliki akun?", size=10, fill="#555555", align="center")
    wb.button(card_x + 120, card_y + 546, 200, 28, "Masuk ke Akun", style="default", size=10, rx=6)
    
    wb.callout_arrow(770, 315, card_x + card_w - 5, 315, label="Priority Scheduling (1-3)")
    
    wb.save("wireframe_02_auth_register.svg")


def create_03_auth_forgot_password():
    wb = WireframeBuilder("SIPANDA - Lupa Password", "https://sipanda.dispora.palembang.go.id/forgot-password", 1000, 700)
    wb.draw_browser_frame()
    
    card_x, card_y, card_w, card_h = 320, 130, 360, 420
    wb.rect(card_x, card_y, card_w, card_h, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
    
    wb.image_box(card_x + 150, card_y + 24, 60, 50, label="ICON", rx=4)
    wb.text(card_x + 180, card_y + 98, "Lupa Password", size=16, weight="bold", fill="#000000", align="center")
    wb.text(card_x + 180, card_y + 118, "Masukkan email Anda untuk menerima", size=10, fill="#555555", align="center")
    wb.text(card_x + 180, card_y + 132, "link reset kata sandi baru.", size=10, fill="#555555", align="center")
    wb.line(card_x + 24, card_y + 148, card_x + card_w - 24, card_y + 148, stroke="#000000", stroke_width=1)
    
    wb.input_field(card_x + 24, card_y + 184, card_w - 48, 36, label="Email Terdaftar", placeholder="ahmad@bappeda.palembang.go.id")
    wb.button(card_x + 24, card_y + 250, card_w - 48, 40, "Kirim Link Reset Password", style="dark", size=11, rx=8)
    
    wb.line(card_x + 24, card_y + 320, card_x + card_w - 24, card_y + 320, stroke="#000000", stroke_width=1)
    wb.button(card_x + 60, card_y + 348, card_w - 120, 30, "← Kembali ke Login", style="default", size=10, rx=6)
    
    wb.save("wireframe_03_auth_forgot_password.svg")


def create_04_auth_reset_password():
    wb = WireframeBuilder("SIPANDA - Reset Password", "https://sipanda.dispora.palembang.go.id/reset-password/tok123", 1000, 700)
    wb.draw_browser_frame()
    
    card_x, card_y, card_w, card_h = 320, 120, 360, 460
    wb.rect(card_x, card_y, card_w, card_h, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
    
    wb.image_box(card_x + 150, card_y + 20, 60, 50, label="LOCK", rx=4)
    wb.text(card_x + 180, card_y + 92, "Atur Ulang Password", size=16, weight="bold", fill="#000000", align="center")
    wb.line(card_x + 24, card_y + 110, card_x + card_w - 24, card_y + 110, stroke="#000000", stroke_width=1)
    
    wb.input_field(card_x + 24, card_y + 140, card_w - 48, 34, label="Email", value="ahmad@bappeda.palembang.go.id")
    wb.input_field(card_x + 24, card_y + 204, card_w - 48, 34, label="Password Baru", placeholder="••••••••")
    wb.input_field(card_x + 24, card_y + 268, card_w - 48, 34, label="Konfirmasi Password Baru", placeholder="••••••••")
    
    wb.button(card_x + 24, card_y + 334, card_w - 48, 40, "Simpan Password Baru", style="dark", size=11, rx=8)
    wb.button(card_x + 80, card_y + 394, card_w - 160, 26, "← Batal", style="default", size=10, rx=4)
    
    wb.save("wireframe_04_auth_reset_password.svg")


def create_05_peminjam_cari_aset():
    wb = WireframeBuilder("SIPANDA - Cari Aset (Halaman Utama Peminjam)", "https://sipanda.dispora.palembang.go.id/peminjam/cari", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Cari Aset", page_title="Cari Aset Publik")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Big Ad / Hero banner like YouTube reference
    wb.image_box(cx, cy, cw, 100, label="BANNER / HERO SEARCH", rx=4)
    
    # Pill Category Tabs under Hero (Home, Trending, Subscription style)
    chip_y = cy + 110
    chips = ["Semua Aset", "Sound System", "Tenda & Panggung", "Kursi & Meja", "Alat Olahraga", "Genset / Mesin"]
    chip_x = cx
    for i, c in enumerate(chips):
        w = len(c) * 7 + 22
        style = "active" if i == 0 else "default"
        wb.button(chip_x, chip_y, w, 26, c, style=style, size=10, rx=8)
        chip_x += w + 8
        
    # Section Heading 1 (Recommended style)
    sec_y = chip_y + 36
    wb.text(cx, sec_y + 12, "Aset Tersedia (Direkomendasikan)", size=12, weight="bold", fill="#000000")
    
    # Grid of Cards (Row 1)
    grid_y = sec_y + 20
    card_w = (cw - 30) / 4
    card_h = 195
    
    assets = [
        ("Sound System Portable", "Audio Yamaha · 2 Unit", "Layak Pakai"),
        ("Tenda Kerucut 3x3m", "Sarnafil Besi · 4 Unit", "Layak Pakai"),
        ("Genset Portable 5KVA", "Honda 5000W · 1 Unit", "Perlu Servis"),
        ("Kursi Lipat Chitose", "Kursi Besi · 50 Unit", "Layak Pakai")
    ]
    
    for i, (name, sub, cond) in enumerate(assets):
        x = cx + i * (card_w + 10)
        wb.rect(x, grid_y, card_w, card_h, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        
        # Thumbnail with X
        wb.image_box(x + 6, grid_y + 6, card_w - 12, 85, label="", rx=2)
        
        # Text details
        wb.text(x + 8, grid_y + 106, name, size=10, weight="bold", fill="#000000")
        wb.text(x + 8, grid_y + 120, sub, size=8, fill="#555555")
        
        # Gray placeholder text bars (like reference)
        wb.text_bar(x + 8, grid_y + 130, card_w - 40, h=6, fill="#888888")
        wb.text_bar(x + 8, grid_y + 140, card_w - 60, h=6, fill="#aaaaaa")
        
        # Tag & Action Button
        wb.pill_badge(x + 8, grid_y + 154, 70, 16, cond, is_dark=(cond=="Layak Pakai"))
        wb.button(x + card_w - 75, grid_y + 152, 68, 22, "Pinjam →", style="default", size=9, rx=4)
        
    # Section Heading 2 (Recently Uploaded style)
    sec2_y = grid_y + card_h + 14
    wb.text(cx, sec2_y + 12, "Peminjaman Sering Digunakan", size=12, weight="bold", fill="#000000")
    
    # Grid Row 2
    grid2_y = sec2_y + 20
    assets2 = [
        ("Panggung Rigging 6x4m", "Modul Portabel · 1 Set", "Layak Pakai"),
        ("Matras Senam Busa", "Matras Standard · 6 Unit", "Perlu Servis"),
        ("Meja Bundar Acara", "Kayu Lapis · 10 Unit", "Layak Pakai"),
        ("Wireless Mic Clip-on", "Receiver UHF · 4 Set", "Layak Pakai")
    ]
    for i, (name, sub, cond) in enumerate(assets2):
        x = cx + i * (card_w + 10)
        wb.rect(x, grid2_y, card_w, 100, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        wb.image_box(x + 6, grid2_y + 6, 75, 88, label="", rx=2)
        wb.text(x + 88, grid2_y + 24, name, size=9, weight="bold", fill="#000000")
        wb.text(x + 88, grid2_y + 38, sub, size=8, fill="#555555")
        wb.text_bar(x + 88, grid2_y + 48, card_w - 96, h=5, fill="#888888")
        wb.pill_badge(x + 88, grid2_y + 62, 65, 16, cond, is_dark=(cond=="Layak Pakai"))
        
    wb.callout_arrow(cx + cw - 20, cy + 50, cx + cw - 120, cy + 50, label="Klasifikasi DT C4.5")
    
    wb.save("wireframe_05_peminjam_cari_aset.svg")


def create_06_peminjam_dashboard():
    wb = WireframeBuilder("SIPANDA - Dashboard Peminjam", "https://sipanda.dispora.palembang.go.id/peminjam/dashboard", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Dashboard", page_title="Dashboard Peminjam")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Priority Score Banner Card
    wb.rect(cx, cy, cw, 60, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.image_box(cx + 10, cy + 8, 44, 44, label="⭐", rx=4)
    wb.text(cx + 64, cy + 26, "STATUS PRIORITAS PEMINJAM: PEMERINTAH / OPD (SKOR 3/3)", size=11, weight="bold", fill="#000000")
    wb.text(cx + 64, cy + 42, "Pengajuan Anda mendapatkan antrean prioritas dalam Priority Scheduling Dispora", size=9, fill="#555555")
    wb.pill_badge(cx + cw - 90, cy + 18, 75, 24, "SKOR 3 / 3", is_dark=True)
    
    # 4 Stat KPI Cards
    stat_y = cy + 72
    stat_w = (cw - 30) / 4
    stats = [
        ("Total Pengajuan", "8", "📋"),
        ("Sedang Dipinjam", "1", "📦"),
        ("Menunggu Proses", "2", "⏳"),
        ("Selesai & Kembali", "5", "✓")
    ]
    for i, (lbl, val, ic) in enumerate(stats):
        x = cx + i * (stat_w + 10)
        wb.rect(x, stat_y, stat_w, 65, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        wb.text(x + 10, stat_y + 20, lbl.upper(), size=8, weight="bold", fill="#555555")
        wb.text(x + 10, stat_y + 48, val, size=20, weight="bold", fill="#000000")
        wb.rect(x + stat_w - 32, stat_y + 12, 22, 22, rx=4, fill="#eaeaea", stroke="#000000", stroke_width=1)
        wb.text(x + stat_w - 21, stat_y + 27, ic, size=11, align="center")
        
    # 2 Columns Layout
    col_y = stat_y + 78
    col_w = (cw - 14) / 2
    col_h = 360
    
    # Left: Peminjaman Aktif
    wb.rect(cx, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 12, col_y + 22, "Peminjaman Aktif", size=11, weight="bold", fill="#000000")
    wb.text(cx + col_w - 80, col_y + 22, "Lihat Semua →", size=9, fill="#000000")
    wb.line(cx, col_y + 32, cx + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    p_items = [
        ("Sound System Portable", "Kembali: 22 Agt 2026", "Dipinjam"),
        ("Tenda Kerucut 3x3m", "Jadwal: 25-28 Agt 2026", "Diverifikasi"),
        ("Kursi Lipat Chitose", "Jadwal: 01-03 Sep 2026", "Disetujui")
    ]
    py = col_y + 42
    for name, sub, st in p_items:
        wb.rect(cx + 8, py, col_w - 16, 68, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        wb.image_box(cx + 14, py + 8, 50, 52, label="", rx=2)
        wb.text(cx + 72, py + 24, name, size=10, weight="bold", fill="#000000")
        wb.text(cx + 72, py + 38, sub, size=8, fill="#555555")
        wb.text_bar(cx + 72, py + 48, 120, h=5, fill="#888888")
        wb.pill_badge(cx + col_w - 80, py + 14, 65, 18, st, is_dark=(st=="Disetujui"))
        py += 76
        
    # Right: Notifikasi & Aktivitas
    rx_col = cx + col_w + 14
    wb.rect(rx_col, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 12, col_y + 22, "Notifikasi Terbaru", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, col_y + 32, rx_col + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    notifs = [
        ("Pengajuan Disetujui Admin", "Pengajuan Tenda Kerucut TRX-0038 telah disetujui.", "10 mnt"),
        ("Tanggapan Keluhan #15", "Pengelola: Teknisi dijadwalkan servis besok.", "2 jam"),
        ("Pengajuan Terkirim", "TRX-0042 masuk antrean verifikasi pengelola.", "1 hari")
    ]
    ny = col_y + 42
    for tit, msg, time_diff in notifs:
        wb.rect(rx_col + 8, ny, col_w - 16, 68, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        wb.checkbox(rx_col + 16, ny + 12, checked=True)
        wb.text(rx_col + 40, ny + 24, tit, size=10, weight="bold", fill="#000000")
        wb.text(rx_col + 40, ny + 38, msg[:35] + "...", size=8, fill="#555555")
        wb.text_bar(rx_col + 40, ny + 48, col_w - 110, h=5, fill="#888888")
        wb.text(rx_col + col_w - 40, ny + 24, time_diff, size=8, fill="#777777")
        ny += 76
        
    wb.save("wireframe_06_peminjam_dashboard.svg")


def create_07_peminjam_peminjaman_create():
    wb = WireframeBuilder("SIPANDA - Form Pengajuan Pinjaman", "https://sipanda.dispora.palembang.go.id/peminjam/peminjaman/buat", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Ajukan Pinjam", page_title="Form Permohonan Peminjaman Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.58
    col2_w = (cw - 14) * 0.42
    
    # Left Form Card
    wb.rect(cx, cy, col1_w, 530, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Data Peminjaman Aset", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    # Selected Asset Card
    wb.rect(cx + 14, cy + 42, col1_w - 28, 54, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
    wb.image_box(cx + 20, cy + 48, 42, 42, label="", rx=2)
    wb.text(cx + 70, cy + 64, "Sound System Portable Wireless (Yamaha)", size=10, weight="bold", fill="#000000")
    wb.text(cx + 70, cy + 78, "Kode: AST-001 · Kondisi: Layak Pakai · Tersedia: 4 Unit", size=8, fill="#555555")
    
    dw = (col1_w - 38) / 2
    wb.input_field(cx + 14, cy + 120, dw, 32, label="Tanggal Mulai Pinjam *", value="20/08/2026")
    wb.input_field(cx + 24 + dw, cy + 120, dw, 32, label="Tanggal Selesai Pinjam *", value="22/08/2026")
    
    wb.input_field(cx + 14, cy + 178, col1_w - 28, 32, label="Jumlah Unit yang Dipinjam *", value="2 Unit")
    wb.input_field(cx + 14, cy + 236, col1_w - 28, 54, label="Tujuan & Nama Kegiatan *", value="Turnamen Futsal Pemuda Walikota Cup 2026", is_textarea=True)
    wb.input_field(cx + 14, cy + 314, col1_w - 28, 36, label="Lokasi Pemakaian Aset *", value="GOR Ranau Jakabaring Sport City Palembang")
    wb.input_field(cx + 14, cy + 376, col1_w - 28, 46, label="Catatan Tambahan (Opsional)", placeholder="Kebutuhan mic wireless tambahan...", is_textarea=True)
    
    wb.button(cx + 14, cy + 450, 160, 36, "Kirim Pengajuan →", style="dark", size=11, rx=6)
    wb.button(cx + 184, cy + 450, 90, 36, "Batal", style="default", size=10, rx=6)
    
    # Right Column: Document Upload & Terms
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 530, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Dokumen & Ketentuan", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.text(rx_col + 14, cy + 50, "Upload Surat Permohonan (PDF/JPG) *", size=9, weight="bold", fill="#000000")
    wb.image_box(rx_col + 14, cy + 60, col2_w - 28, 85, label="UPLOAD DOKUMEN", rx=4)
    
    wb.text(rx_col + 14, cy + 166, "Syarat & Ketentuan Peminjaman:", size=9, weight="bold", fill="#000000")
    wb.rect(rx_col + 14, cy + 176, col2_w - 28, 160, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
    wb.text(rx_col + 24, cy + 196, "1. Bertanggung jawab penuh atas keutuhan aset.", size=8, fill="#000000")
    wb.text(rx_col + 24, cy + 214, "2. Kerusakan/kehilangan wajib diganti sesuai aturan.", size=8, fill="#000000")
    wb.text(rx_col + 24, cy + 232, "3. Pengambilan wajib membawa Barcode Token.", size=8, fill="#000000")
    wb.text(rx_col + 24, cy + 250, "4. Pengembalian tepat waktu sesuai jadwal.", size=8, fill="#000000")
    wb.text_bar(rx_col + 24, cy + 268, col2_w - 60, h=6, fill="#888888")
    wb.text_bar(rx_col + 24, cy + 280, col2_w - 80, h=6, fill="#888888")
    
    wb.checkbox(rx_col + 14, cy + 356, "Saya setuju mematuhi seluruh ketentuan di atas.", checked=True)
    
    wb.save("wireframe_07_peminjam_peminjaman_create.svg")


def create_08_peminjam_peminjaman_index():
    wb = WireframeBuilder("SIPANDA - Riwayat Peminjaman Peminjam", "https://sipanda.dispora.palembang.go.id/peminjam/peminjaman", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Riwayat Pinjam", page_title="Riwayat & Daftar Peminjaman")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Filter Tabs
    tabs = ["Semua (8)", "Menunggu (2)", "Disetujui (1)", "Dipinjam (1)", "Selesai (3)", "Ditolak (1)"]
    tab_x = cx
    for i, t in enumerate(tabs):
        w = len(t) * 7 + 16
        style = "active" if i == 0 else "default"
        wb.button(tab_x, cy, w, 26, t, style=style, size=9, rx=6)
        tab_x += w + 6
        
    list_y = cy + 36
    txs = [
        ("TRX-2026-0042", "Sound System Portable Wireless (2 Unit)", "20-22 Agt 2026", "Turnamen Futsal Walikota Cup", "Menunggu Verifikasi", False, True),
        ("TRX-2026-0038", "Tenda Kerucut 3x3m (4 Unit)", "15-18 Agt 2026", "Pameran Kreatif Pemuda Palembang", "Disetujui Admin", True, False),
        ("TRX-2026-0034", "Genset Portable 5KVA (1 Unit)", "10-12 Agt 2026", "Pelatihan Kepemudaan", "Sedang Dipinjam", False, False),
        ("TRX-2026-0029", "Kursi Lipat Chitose (50 Unit)", "01-03 Agt 2026", "Rapat Koordinasi OKP", "Selesai", False, False)
    ]
    
    for code, asset, date_str, purpose, status_lbl, can_download, can_cancel in txs:
        wb.rect(cx, list_y, cw, 86, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        wb.image_box(cx + 10, list_y + 10, 65, 66, label="IMG", rx=2)
        
        wb.text(cx + 85, list_y + 26, f"{code} · {asset}", size=10, weight="bold", fill="#000000")
        wb.text(cx + 85, list_y + 42, f"Jadwal: {date_str}   |   Keperluan: {purpose}", size=8, fill="#555555")
        wb.text_bar(cx + 85, list_y + 50, 220, h=5, fill="#888888")
        
        wb.pill_badge(cx + 85, list_y + 60, 120, 18, status_lbl, is_dark=(status_lbl=="Disetujui Admin"))
        
        btn_x = cx + cw - 210
        wb.button(btn_x, list_y + 26, 80, 32, "Detail →", style="default", size=9, rx=4)
        if can_download:
            wb.button(btn_x + 88, list_y + 26, 114, 32, "📄 Unduh Surat", style="dark", size=9, rx=4)
        elif can_cancel:
            wb.button(btn_x + 88, list_y + 26, 114, 32, "Batalkan", style="default", size=9, rx=4)
        else:
            wb.button(btn_x + 88, list_y + 26, 114, 32, "Arsip", style="default", size=9, rx=4)
            
        list_y += 94
        
    wb.save("wireframe_08_peminjam_peminjaman_index.svg")


def create_09_peminjam_peminjaman_show():
    wb = WireframeBuilder("SIPANDA - Detail Peminjaman & Barcode", "https://sipanda.dispora.palembang.go.id/peminjam/peminjaman/42", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Riwayat Pinjam", page_title="Detail Peminjaman #TRX-2026-0042")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Progress Stepper Bar
    step_y = cy
    wb.rect(cx, step_y, cw, 50, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    steps = ["1. Diajukan [X]", "2. Diverifikasi [X]", "3. Disetujui [X]", "4. Pengambilan [ ]", "5. Selesai [ ]"]
    sw = cw / 5
    for i, st in enumerate(steps):
        wb.text(cx + i * sw + sw/2, step_y + 28, st, size=9, weight="bold" if "[X]" in st else "normal", fill="#000000", align="center")
        if i < 4:
            wb.line(cx + (i + 1) * sw, step_y + 8, cx + (i + 1) * sw, step_y + 42, stroke="#000000", stroke_width=1)
            
    col_y = step_y + 60
    col1_w = (cw - 14) * 0.60
    col2_w = (cw - 14) * 0.40
    
    # Left Detail Box
    wb.rect(cx, col_y, col1_w, 460, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, col_y + 24, "Informasi Peminjaman", size=11, weight="bold", fill="#000000")
    wb.line(cx, col_y + 34, cx + col1_w, col_y + 34, stroke="#000000", stroke_width=1)
    
    details = [
        ("Nomor Transaksi", "TRX-2026-0042"),
        ("Nama Pemohon", "Ahmad Fauzi (Bappeda Kota Palembang)"),
        ("Kategori Instansi", "Pemerintah / OPD (Prioritas Skor 3)"),
        ("Aset Dipinjam", "Sound System Portable Wireless Yamaha (2 Unit)"),
        ("Jadwal Pinjam", "20 Agustus 2026 s/d 22 Agustus 2026"),
        ("Keperluan Acara", "Turnamen Futsal Pemuda Walikota Cup 2026"),
        ("Lokasi Acara", "GOR Ranau Jakabaring Sport City"),
        ("Status Permohonan", "DISETUJUI ADMIN DISPORA")
    ]
    dy = col_y + 54
    for k, v in details:
        wb.text(cx + 16, dy, k, size=9, fill="#555555")
        wb.text(cx + 130, dy, ": " + v, size=9, weight="bold" if "TRX" in v or "DISETUJUI" in v else "normal", fill="#000000")
        dy += 32
        
    wb.pill_badge(cx + 16, dy + 10, 240, 22, "Rekomendasi Pengelola: LAYAK & DISETUJUI", is_dark=False)
    
    # Right Column: QR Code Barcode Box
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, col_y, col2_w, 460, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, col_y + 24, "Barcode & Surat Izin", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, col_y + 34, rx_col + col2_w, col_y + 34, stroke="#000000", stroke_width=1)
    
    # QR Code placeholder box
    wb.image_box(rx_col + (col2_w - 140)/2, col_y + 50, 140, 140, label="QR CODE", rx=4)
    wb.text(rx_col + col2_w/2, col_y + 208, "Token: TOK-89AB-3C4D-99", size=10, weight="bold", fill="#000000", align="center")
    wb.text(rx_col + col2_w/2, col_y + 224, "Tunjukkan barcode saat serah terima aset", size=8, fill="#555555", align="center")
    
    wb.button(rx_col + 16, col_y + 250, col2_w - 32, 40, "📄 Unduh Surat Izin (PDF)", style="dark", size=10, rx=6)
    wb.button(rx_col + 16, col_y + 300, col2_w - 32, 34, "💬 Lapor Keluhan Aset", style="default", size=9, rx=6)
    wb.button(rx_col + 16, col_y + 344, col2_w - 32, 32, "Batalkan Peminjaman", style="default", size=9, rx=6)
    
    wb.save("wireframe_09_peminjam_peminjaman_show.svg")


def create_10_peminjam_keluhan_index():
    wb = WireframeBuilder("SIPANDA - Daftar Keluhan Peminjam", "https://sipanda.dispora.palembang.go.id/peminjam/keluhan", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Keluhan Aset", page_title="Daftar Keluhan Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.button(cx, cy, 180, 28, "+ Buat Keluhan Baru", style="dark", size=10, rx=6)
    wb.input_field(cx + cw - 240, cy, 240, 28, placeholder="search keluhan...")
    
    tbl_y = cy + 40
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    # Table Header
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    wb.text(cx + 12, tbl_y + 20, "NO", size=9, weight="bold", fill="#000000")
    wb.text(cx + 46, tbl_y + 20, "TANGGAL", size=9, weight="bold", fill="#000000")
    wb.text(cx + 140, tbl_y + 20, "ASET TERKAIT", size=9, weight="bold", fill="#000000")
    wb.text(cx + 310, tbl_y + 20, "RINCIAN KELUHAN", size=9, weight="bold", fill="#000000")
    wb.text(cx + 560, tbl_y + 20, "STATUS", size=9, weight="bold", fill="#000000")
    wb.text(cx + 690, tbl_y + 20, "AKSI", size=9, weight="bold", fill="#000000")
    
    rows = [
        ("1", "16 Agt 2026", "Sound System Portable", "Mikrofon wireless kresek & baterai drop", "Menunggu Respon"),
        ("2", "12 Agt 2026", "Tenda Kerucut 3x3m", "Rangka tiang sudut kiri bengkok", "Sedang Ditangani"),
        ("3", "02 Agt 2026", "Genset Portable 5KVA", "Oli mesin merembes saat dioperasikan", "Selesai Diperbaiki")
    ]
    ry = tbl_y + 36
    for no, tgl, aset, judul, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.text(cx + 46, ry + 30, tgl, size=9, fill="#555555")
        wb.text(cx + 140, ry + 24, aset, size=9, weight="bold", fill="#000000")
        wb.text(cx + 140, ry + 38, "TRX-2026-0042", size=8, fill="#555555")
        wb.text(cx + 310, ry + 30, judul[:34] + "...", size=9, fill="#000000")
        wb.pill_badge(cx + 560, ry + 18, 100, 18, st, is_dark=(st=="Selesai Diperbaiki"))
        wb.button(cx + 690, ry + 14, 75, 26, "Detail", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_10_peminjam_keluhan_index.svg")


def create_11_peminjam_keluhan_create():
    wb = WireframeBuilder("SIPANDA - Form Buat Keluhan", "https://sipanda.dispora.palembang.go.id/peminjam/keluhan/buat", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Keluhan Aset", page_title="Form Laporan Keluhan Kerusakan")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.rect(cx, cy, cw, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 20, cy + 24, "Formulir Laporan Kerusakan Aset", size=12, weight="bold", fill="#000000")
    wb.line(cx, cy + 34, cx + cw, cy + 34, stroke="#000000", stroke_width=1)
    
    wb.select_field(cx + 20, cy + 60, cw - 40, 32, label="Pilih Transaksi / Aset Terkait *", value="TRX-2026-0042 — Sound System Portable Wireless (Yamaha)")
    wb.input_field(cx + 20, cy + 120, cw - 40, 32, label="Judul Keluhan Utama *", value="Mikrofon wireless kresek & baterai drop cepat")
    wb.input_field(cx + 20, cy + 178, cw - 40, 80, label="Deskripsi Kerusakan Rinci *", value="Mic wireless nomor 2 berisik dan mati mendadak setelah 15 menit pemakaian.", is_textarea=True)
    
    wb.text(cx + 20, cy + 280, "Upload Foto Bukti Kerusakan (Opsional)", size=9, weight="bold", fill="#000000")
    wb.image_box(cx + 20, cy + 290, cw - 40, 90, label="FOTO BUKTI KERUSAKAN", rx=4)
    
    wb.button(cx + 20, cy + 410, 180, 38, "Kirim Keluhan →", style="dark", size=11, rx=6)
    wb.button(cx + 210, cy + 410, 90, 38, "Batal", style="default", size=10, rx=6)
    
    wb.save("wireframe_11_peminjam_keluhan_create.svg")


def create_12_peminjam_keluhan_show():
    wb = WireframeBuilder("SIPANDA - Detail Keluhan Peminjam", "https://sipanda.dispora.palembang.go.id/peminjam/keluhan/15", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Keluhan Aset", page_title="Detail Keluhan #KEL-0015")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.52
    col2_w = (cw - 14) * 0.48
    
    # Left: Laporan Peminjam
    wb.rect(cx, cy, col1_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Rincian Laporan Anda", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.text(cx + 16, cy + 52, "Judul:", size=9, fill="#555555")
    wb.text(cx + 16, cy + 66, "Mikrofon wireless kresek & baterai drop cepat", size=10, weight="bold", fill="#000000")
    
    wb.text(cx + 16, cy + 92, "Aset Terkait:", size=9, fill="#555555")
    wb.text(cx + 16, cy + 106, "Sound System Portable Wireless (TRX-0042)", size=9, weight="bold", fill="#000000")
    
    wb.text(cx + 16, cy + 132, "Tanggal Lapor:", size=9, fill="#555555")
    wb.text(cx + 16, cy + 146, "16 Agustus 2026, 14:30 WIB", size=9, fill="#000000")
    
    wb.text(cx + 16, cy + 172, "Deskripsi Masalah:", size=9, fill="#555555")
    wb.rect(cx + 16, cy + 182, col1_w - 32, 60, rx=4, fill="#f8f8f8", stroke="#cccccc", stroke_width=1)
    wb.text(cx + 24, cy + 200, "Mic wireless nomor 2 kresek dan mati setelah 15 menit.", size=8, fill="#000000")
    
    wb.text(cx + 16, cy + 260, "Foto Bukti:", size=9, fill="#555555")
    wb.image_box(cx + 16, cy + 272, 130, 95, label="FOTO BUKTI", rx=4)
    
    # Right: Respon Pengelola
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Respon Petugas Pengelola", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.pill_badge(rx_col + 16, cy + 46, 120, 20, "STATUS: DIPROSES", is_dark=False)
    wb.text(rx_col + 16, cy + 86, "Petugas: Rizki Pratama (Seksi Sarpras Dispora)", size=9, weight="bold", fill="#000000")
    wb.text(rx_col + 16, cy + 102, "Waktu Respon: 16 Agustus 2026, 16:45 WIB", size=8, fill="#555555")
    
    wb.text(rx_col + 16, cy + 130, "Tanggapan & Solusi:", size=9, fill="#555555")
    wb.rect(rx_col + 16, cy + 140, col2_w - 32, 110, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(rx_col + 24, cy + 160, "\"Teknisi rekanan telah dijadwalkan datang", size=8, fill="#000000")
    wb.text(rx_col + 24, cy + 174, "tgl 17 Agt untuk servis modul receiver & baterai.", size=8, fill="#000000")
    wb.text(rx_col + 24, cy + 188, "Aset dialihkan ke status Maintenance sementara.\"", size=8, fill="#000000")
    
    wb.pill_badge(rx_col + 16, cy + 270, 220, 22, "Status Aset: Dalam Pemeliharaan / Servis", is_dark=True)
    
    wb.save("wireframe_12_peminjam_keluhan_show.svg")


def run_batch1():
    print("Generating B&W Wireframes Batch 1 (01-12)...")
    create_01_auth_login()
    create_02_auth_register()
    create_03_auth_forgot_password()
    create_04_auth_reset_password()
    create_05_peminjam_cari_aset()
    create_06_peminjam_dashboard()
    create_07_peminjam_peminjaman_create()
    create_08_peminjam_peminjaman_index()
    create_09_peminjam_peminjaman_show()
    create_10_peminjam_keluhan_index()
    create_11_peminjam_keluhan_create()
    create_12_peminjam_keluhan_show()

if __name__ == '__main__':
    run_batch1()
