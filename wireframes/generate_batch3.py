"""
B&W Wireframe Batch 3: Modul Admin & Shared (Wireframes 20 - 33)
Strictly Monochrome / Grayscale Balsamiq Style
"""

import os
from generator import WireframeBuilder

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

def create_20_admin_dashboard():
    wb = WireframeBuilder("SIPANDA - Dashboard Administrator", "https://sipanda.dispora.palembang.go.id/admin/dashboard", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Dashboard", page_title="Dashboard Administrator")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # 4 Primary Stat Cards
    stat_y = cy
    stat_w = (cw - 30) / 4
    stats = [
        ("Total Peminjam", "128", "User Terdaftar"),
        ("Total Aset Publik", "45", "38 Siap Pakai"),
        ("Pengajuan Baru", "4", "Perlu Approval"),
        ("Keluhan Aktif", "2", "Dalam Penanganan")
    ]
    for i, (lbl, val, sub) in enumerate(stats):
        x = cx + i * (stat_w + 10)
        wb.rect(x, stat_y, stat_w, 65, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        wb.text(x + 10, stat_y + 18, lbl.upper(), size=8, weight="bold", fill="#555555")
        wb.text(x + 10, stat_y + 44, val, size=20, weight="bold", fill="#000000")
        wb.text(x + 10, stat_y + 56, sub, size=7, fill="#777777")
        
    # Secondary 4 Mini Cards
    mini_y = stat_y + 76
    mini_w = (cw - 30) / 4
    minis = [
        ("Aset Dipinjam", "6 Unit"),
        ("Aset Maintenance", "3 Unit"),
        ("Disetujui Bulan Ini", "18 Transaksi"),
        ("Tingkat Kelaikan", "91.2%")
    ]
    for i, (lbl, val) in enumerate(minis):
        x = cx + i * (mini_w + 10)
        wb.rect(x, mini_y, mini_w, 36, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        wb.text(x + 10, mini_y + 15, lbl, size=8, fill="#555555")
        wb.text(x + 10, mini_y + 28, val, size=10, weight="bold", fill="#000000")
        
    # 2 Columns Table Grid
    col_y = mini_y + 46
    col_w = (cw - 14) / 2
    col_h = 240
    
    # Left: Pengajuan Terbaru
    wb.rect(cx, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 12, col_y + 22, "Pengajuan Peminjaman Terbaru", size=11, weight="bold", fill="#000000")
    wb.text(cx + col_w - 70, col_y + 22, "Kelola →", size=9, fill="#000000")
    wb.line(cx, col_y + 32, cx + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    txs = [
        ("Ahmad Fauzi (Bappeda)", "Sound System · 20 Agt", "Diverifikasi"),
        ("Rahmat H. (KNPI)", "Tenda Kerucut · 21 Agt", "Diverifikasi"),
        ("Dinas Pendidikan", "Panggung 6x4 · 15 Agt", "Disetujui")
    ]
    ty = col_y + 42
    for name, sub, st in txs:
        wb.rect(cx + 8, ty, col_w - 16, 54, rx=4, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 14, ty + 22, name, size=10, weight="bold", fill="#000000")
        wb.text(cx + 14, ty + 36, sub, size=8, fill="#555555")
        wb.pill_badge(cx + col_w - 85, ty + 16, 70, 18, st, is_dark=(st=="Disetujui"))
        ty += 60
        
    # Right: Keluhan Terbaru
    rx_col = cx + col_w + 14
    wb.rect(rx_col, col_y, col_w, col_h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 12, col_y + 22, "Keluhan Aset Terkini", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, col_y + 32, rx_col + col_w, col_y + 32, stroke="#000000", stroke_width=1)
    
    kls = [
        ("Mic wireless kresek", "Sound System · Bappeda", "Pending"),
        ("Kaki tenda bengkok", "Tenda Kerucut · KNPI", "Pending"),
        ("Oli genset merembes", "Genset 5KVA · Dispora", "Diproses")
    ]
    ky = col_y + 42
    for name, sub, st in kls:
        wb.rect(rx_col + 8, ky, col_w - 16, 54, rx=4, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(rx_col + 14, ky + 22, name, size=10, weight="bold", fill="#000000")
        wb.text(rx_col + 14, ky + 36, sub, size=8, fill="#555555")
        wb.pill_badge(rx_col + col_w - 80, ky + 16, 65, 18, st, is_dark=(st=="Pending"))
        ky += 60
        
    # Bottom Info Banner
    bot_y = col_y + col_h + 14
    wb.rect(cx, bot_y, cw, 60, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 14, bot_y + 22, "INTEGRASI ALGORITMA SISTEM:", size=9, weight="bold", fill="#000000")
    wb.text(cx + 14, bot_y + 38, "• Decision Tree C4.5: Otomatisasi klasifikasi kelayakan fisik aset (Layak / Servis / Lelang).", size=8, fill="#000000")
    wb.text(cx + 14, bot_y + 50, "• Priority Scheduling: Menentukan antrean peminjam berdasarkan level instansi (1-3).", size=8, fill="#000000")
    
    wb.save("wireframe_20_admin_dashboard.svg")


def create_21_admin_users_index():
    wb = WireframeBuilder("SIPANDA - Manajemen User Admin", "https://sipanda.dispora.palembang.go.id/admin/users", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Manajemen User", page_title="Manajemen User")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.button(cx, cy, 160, 28, "+ Tambah User Baru", style="dark", size=10, rx=6)
    wb.select_field(cx + 170, cy, 150, 28, value="Semua Role (128)")
    wb.input_field(cx + cw - 240, cy, 240, 28, placeholder="search user...")
    
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "NAMA & EMAIL"), (cx + 240, "ROLE"),
        (cx + 340, "INSTANSI / KATEGORI"), (cx + 510, "NO HP"), (cx + 630, "STATUS"), (cx + 720, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "Hendra Saputra", "admin@dispora.go.id", "ADMIN", "Dispora Palembang", "0812-1111", "Aktif"),
        ("2", "Rizki Pratama", "pengelola@dispora.go.id", "PENGELOLA", "Seksi Sarpras", "0813-3333", "Aktif"),
        ("3", "Ahmad Fauzi", "ahmad@bappeda.go.id", "PEMINJAM", "Bappeda (Prioritas 3)", "0812-3456", "Aktif"),
        ("4", "Rahmat Hidayat", "rahmat@knpi.org", "PEMINJAM", "KNPI (Prioritas 2)", "0811-9988", "Aktif"),
        ("5", "Siti Rahma", "siti.rahma@gmail.com", "PEMINJAM", "Umum (Prioritas 1)", "0852-6677", "Aktif")
    ]
    ry = tbl_y + 36
    for no, name, email, role, inst, wa, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.text(cx + 40, ry + 24, name, size=9, weight="bold", fill="#000000")
        wb.text(cx + 40, ry + 38, email, size=8, fill="#555555")
        wb.pill_badge(cx + 240, ry + 18, 70, 18, role, is_dark=(role=="ADMIN"))
        wb.text(cx + 340, ry + 30, inst, size=8, fill="#000000")
        wb.text(cx + 510, ry + 30, wa, size=8, fill="#555555")
        wb.pill_badge(cx + 630, ry + 18, 55, 18, st, is_dark=(st=="Aktif"))
        wb.button(cx + 720, ry + 14, 42, 26, "Edit", style="default", size=8, rx=4)
        wb.button(cx + 768, ry + 14, 48, 26, "Detail", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_21_admin_users_index.svg")


def create_22_admin_users_create_edit():
    wb = WireframeBuilder("SIPANDA - Form Tambah / Edit User", "https://sipanda.dispora.palembang.go.id/admin/users/buat", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Manajemen User", page_title="Form Input User Baru")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.rect(cx, cy, cw, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 20, cy + 24, "Data Akun Pengguna", size=12, weight="bold", fill="#000000")
    wb.line(cx, cy + 34, cx + cw, cy + 34, stroke="#000000", stroke_width=1)
    
    col_w = (cw - 60) / 2
    wb.input_field(cx + 20, cy + 60, col_w, 32, label="Nama Lengkap *", value="Ahmad Fauzi, S.STP")
    wb.input_field(cx + 20, cy + 120, col_w, 32, label="Alamat Email *", value="ahmad@bappeda.palembang.go.id")
    wb.input_field(cx + 20, cy + 180, col_w, 32, label="Nomor Telepon / WhatsApp *", value="0812-3456-7890")
    wb.input_field(cx + 20, cy + 240, col_w, 32, label="Nama Instansi", value="Bappeda Kota Palembang")
    
    rx_col = cx + col_w + 40
    wb.select_field(rx_col, cy + 60, col_w, 32, label="Peran (Role) *", value="Peminjam")
    wb.select_field(rx_col, cy + 120, col_w, 32, label="Kategori Peminjam (Priority Score) *", value="Pemerintah / OPD (Skor 3)")
    wb.input_field(rx_col, cy + 180, col_w, 32, label="Password Baru", placeholder="••••••••")
    wb.input_field(rx_col, cy + 240, col_w, 32, label="Konfirmasi Password", placeholder="••••••••")
    
    wb.text(cx + 20, cy + 310, "Status Akun:", size=9, weight="bold", fill="#000000")
    wb.radio_button(cx + 20, cy + 325, "Aktif", checked=True)
    wb.radio_button(cx + 120, cy + 325, "Nonaktif", checked=False)
    
    wb.button(cx + 20, cy + 380, 160, 38, "Simpan User →", style="dark", size=10, rx=6)
    wb.button(cx + 190, cy + 380, 90, 38, "Batal", style="default", size=10, rx=6)
    
    wb.save("wireframe_22_admin_users_create_edit.svg")


def create_23_admin_users_show():
    wb = WireframeBuilder("SIPANDA - Detail Profil User Admin", "https://sipanda.dispora.palembang.go.id/admin/users/3", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Manajemen User", page_title="Detail User #3")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.38
    col2_w = (cw - 14) * 0.62
    
    # Left: Profile Card
    wb.rect(cx, cy, col1_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.image_box(cx + (col1_w - 70)/2, cy + 20, 70, 70, label="USER", rx=35)
    wb.text(cx + col1_w/2, cy + 110, "Ahmad Fauzi, S.STP", size=12, weight="bold", fill="#000000", align="center")
    wb.text(cx + col1_w/2, cy + 126, "ahmad@bappeda.palembang.go.id", size=8, fill="#555555", align="center")
    
    wb.pill_badge(cx + col1_w/2 - 45, cy + 138, 90, 18, "ROLE: PEMINJAM", is_dark=False)
    wb.pill_badge(cx + col1_w/2 - 60, cy + 160, 120, 18, "Skor Prioritas: 3 / 3", is_dark=True)
    wb.line(cx + 14, cy + 190, cx + col1_w - 14, cy + 190, stroke="#000000", stroke_width=1)
    
    u_details = [
        ("Instansi", "Bappeda Palembang"),
        ("No WhatsApp", "0812-3456-7890"),
        ("Tgl Terdaftar", "10 Jan 2026"),
        ("Status", "Aktif"),
        ("Total Pinjam", "8 Pengajuan")
    ]
    dy = cy + 210
    for k, v in u_details:
        wb.text(cx + 16, dy, k, size=8, fill="#555555")
        wb.text(cx + 100, dy, ": " + v, size=8, weight="bold" if "Aktif" in v else "normal", fill="#000000")
        dy += 26
        
    wb.button(cx + 16, cy + 420, col1_w - 32, 34, "Edit Akun User", style="default", size=9, rx=5)
    
    # Right: Riwayat Transaksi
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.button(rx_col + 12, cy + 12, 140, 26, "Riwayat Pinjaman (8)", style="active", size=9, rx=4)
    wb.button(rx_col + 160, cy + 12, 120, 26, "Riwayat Keluhan (1)", style="default", size=9, rx=4)
    wb.line(rx_col, cy + 44, rx_col + col2_w, cy + 44, stroke="#000000", stroke_width=1)
    
    history = [
        ("TRX-0042", "Sound System Wireless", "20-22 Agt 2026", "Disetujui"),
        ("TRX-0038", "Tenda Kerucut 3x3m", "15-18 Agt 2026", "Disetujui"),
        ("TRX-0029", "Kursi Lipat 50 Unit", "01-03 Agt 2026", "Selesai"),
        ("TRX-0018", "Panggung Portabel", "12-14 Jul 2026", "Selesai")
    ]
    hy = cy + 54
    for c, a, t, s in history:
        wb.rect(rx_col + 8, hy, col2_w - 16, 48, rx=4, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(rx_col + 16, hy + 20, f"{c} · {a}", size=9, weight="bold", fill="#000000")
        wb.text(rx_col + 16, hy + 36, f"Jadwal: {t}", size=8, fill="#555555")
        wb.pill_badge(rx_col + col2_w - 75, hy + 14, 65, 18, s, is_dark=(s=="Disetujui"))
        hy += 56
        
    wb.save("wireframe_23_admin_users_show.svg")


def create_24_admin_aset_index():
    wb = WireframeBuilder("SIPANDA - Manajemen Master Aset Admin", "https://sipanda.dispora.palembang.go.id/admin/aset", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Master Aset", page_title="Manajemen Master Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.button(cx, cy, 140, 28, "+ Tambah Aset", style="dark", size=10, rx=6)
    wb.button(cx + 150, cy, 140, 28, "Kelola Kategori", style="default", size=10, rx=6)
    wb.input_field(cx + cw - 240, cy, 240, 28, placeholder="search aset...")
    
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "FOTO"), (cx + 85, "KODE & NAMA ASET"),
        (cx + 270, "KATEGORI"), (cx + 380, "STOK"), (cx + 450, "KONDISI"),
        (cx + 550, "STATUS"), (cx + 670, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "AST-001", "Sound System Portable", "Audio", "4 Unit", "Baik", "Tersedia"),
        ("2", "AST-002", "Tenda Kerucut 3x3m", "Tenda", "10 Unit", "Baik", "Tersedia"),
        ("3", "AST-003", "Genset Portable 5KVA", "Mesin", "2 Unit", "Servis", "Maintenance"),
        ("4", "AST-004", "Matras Senam Busa", "Olahraga", "6 Unit", "Rusak", "Afkir"),
        ("5", "AST-005", "Kursi Lipat Chitose", "Kursi", "100 Unit", "Baik", "Tersedia")
    ]
    ry = tbl_y + 36
    for no, code, name, cat, stok, cond, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.image_box(cx + 40, ry + 8, 36, 36, label="", rx=2)
        wb.text(cx + 85, ry + 24, name, size=9, weight="bold", fill="#000000")
        wb.text(cx + 85, ry + 38, f"Kode: {code}", size=8, fill="#555555")
        wb.text(cx + 270, ry + 30, cat, size=8, fill="#000000")
        wb.text(cx + 380, ry + 30, stok, size=8, weight="bold", fill="#000000")
        wb.pill_badge(cx + 450, ry + 18, 65, 18, cond, is_dark=(cond=="Baik"))
        wb.pill_badge(cx + 550, ry + 18, 75, 18, st, is_dark=(st=="Tersedia"))
        wb.button(cx + 670, ry + 14, 40, 26, "Show", style="default", size=8, rx=4)
        wb.button(cx + 715, ry + 14, 40, 26, "Edit", style="default", size=8, rx=4)
        wb.button(cx + 760, ry + 14, 45, 26, "Hapus", style="default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_24_admin_aset_index.svg")


def create_25_admin_aset_create_edit():
    wb = WireframeBuilder("SIPANDA - Form Input Master Aset", "https://sipanda.dispora.palembang.go.id/admin/aset/buat", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Master Aset", page_title="Form Tambah Master Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.rect(cx, cy, cw, 530, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 20, cy + 24, "Data Master Aset", size=12, weight="bold", fill="#000000")
    wb.line(cx, cy + 34, cx + cw, cy + 34, stroke="#000000", stroke_width=1)
    
    col_w = (cw - 60) / 2
    wb.input_field(cx + 20, cy + 60, col_w, 32, label="Kode Aset *", value="AST-007")
    wb.input_field(cx + 20, cy + 120, col_w, 32, label="Nama Barang *", value="Sound System Portable Wireless Yamaha")
    wb.select_field(cx + 20, cy + 180, col_w, 32, label="Kategori Aset *", value="Sound & Audio System")
    wb.input_field(cx + 20, cy + 240, col_w, 32, label="Merk / Tahun Pengadaan *", value="Yamaha StagePas 600BT · 2024")
    wb.input_field(cx + 20, cy + 300, col_w, 32, label="Lokasi Gudang *", value="Gudang Sarpras Dispora Gedung B")
    
    rx_col = cx + col_w + 40
    wb.input_field(rx_col, cy + 60, col_w, 32, label="Jumlah Stok Unit *", value="4 Unit")
    wb.select_field(rx_col, cy + 120, col_w, 32, label="Kondisi Awal *", value="Kondisi Baik (Skala 5/5)")
    wb.input_field(rx_col, cy + 180, col_w, 60, label="Spesifikasi & Keterangan", value="Output 680W, 10 channel mixer, 2 mic wireless UHF.", is_textarea=True)
    
    wb.text(rx_col, cy + 270, "Upload Foto Aset", size=9, weight="bold", fill="#000000")
    wb.image_box(rx_col, cy + 280, col_w, 80, label="FOTO ASET", rx=4)
    
    wb.button(cx + 20, cy + 420, 180, 38, "Simpan Data Aset →", style="dark", size=10, rx=6)
    wb.button(cx + 210, cy + 420, 90, 38, "Batal", style="default", size=10, rx=6)
    
    wb.save("wireframe_25_admin_aset_create_edit.svg")


def create_26_admin_aset_show():
    wb = WireframeBuilder("SIPANDA - Detail Master Aset Admin", "https://sipanda.dispora.palembang.go.id/admin/aset/1", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Master Aset", page_title="Detail Master Aset #AST-001")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.40
    col2_w = (cw - 14) * 0.60
    
    # Left Image & Tag Box
    wb.rect(cx, cy, col1_w, 300, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.image_box(cx + 14, cy + 14, col1_w - 28, 140, label="FOTO ASET", rx=4)
    
    wb.rect(cx + 14, cy + 168, col1_w - 28, 64, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(cx + 22, cy + 188, "BARCODE TAG: |||||| |||| |||||", size=9, weight="bold", fill="#000000")
    wb.text(cx + 22, cy + 204, "AST-DISPORA-2024-001", size=8, fill="#555555")
    wb.pill_badge(cx + col1_w - 85, cy + 180, 60, 18, "Tersedia", is_dark=True)
    
    wb.button(cx + 14, cy + 246, (col1_w - 36)/2, 32, "Edit Aset", style="dark", size=9, rx=4)
    wb.button(cx + 22 + (col1_w - 36)/2, cy + 246, (col1_w - 36)/2, 32, "Cetak Barcode", style="default", size=9, rx=4)
    
    # Right Specifications Box
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 300, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Spesifikasi & Metrik Aset", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    specs = [
        ("Nama Aset", "Sound System Portable Wireless Yamaha"),
        ("Kode Aset", "AST-001"),
        ("Kategori", "Sound & Audio System"),
        ("Pengadaan", "2024 (Usia: 2 Tahun)"),
        ("Jumlah Stok", "4 Unit Siap Pakai"),
        ("Lokasi", "Gudang Sarpras Dispora Lt. 1"),
        ("Status DT C4.5", "LAYAK PAKAI (Kelaikan Fisik 94.2%)")
    ]
    sy = cy + 50
    for k, v in specs:
        wb.text(rx_col + 16, sy, k, size=8, fill="#555555")
        wb.text(rx_col + 100, sy, ": " + v, size=8, weight="bold" if "LAYAK" in v else "normal", fill="#000000")
        sy += 26
        
    # Bottom History Log
    bot_y = cy + 314
    wb.rect(cx, bot_y, cw, 220, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, bot_y + 22, "Riwayat Peminjaman Aset Ini", size=11, weight="bold", fill="#000000")
    wb.line(cx, bot_y + 32, cx + cw, bot_y + 32, stroke="#000000", stroke_width=1)
    
    tx_log = [
        ("TRX-0042", "Ahmad Fauzi (Bappeda)", "20-22 Agt 2026", "Disetujui"),
        ("TRX-0029", "Sanggar Seni Sriwijaya", "01-03 Agt 2026", "Selesai"),
        ("TRX-0015", "Dinas Kepemudaan", "10-12 Jul 2026", "Selesai")
    ]
    ly = bot_y + 42
    for c, u, t, s in tx_log:
        wb.rect(cx + 8, ly, cw - 16, 44, rx=4, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 16, ly + 20, c, size=9, weight="bold", fill="#000000")
        wb.text(cx + 100, ly + 20, u, size=9, fill="#000000")
        wb.text(cx + 340, ly + 20, f"Jadwal: {t}", size=8, fill="#555555")
        wb.pill_badge(cx + cw - 90, ly + 12, 70, 18, s, is_dark=(s=="Disetujui"))
        ly += 52
        
    wb.save("wireframe_26_admin_aset_show.svg")


def create_27_admin_aset_kategori():
    wb = WireframeBuilder("SIPANDA - Manajemen Kategori Aset", "https://sipanda.dispora.palembang.go.id/admin/aset/kategori/daftar", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Master Aset", page_title="Kategori Aset")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.40
    col2_w = (cw - 14) * 0.60
    
    # Left: Form Tambah
    wb.rect(cx, cy, col1_w, 480, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Tambah Kategori Baru", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.input_field(cx + 16, cy + 60, col1_w - 32, 32, label="Nama Kategori *", placeholder="Misal: Kendaraan Operasional")
    wb.input_field(cx + 16, cy + 120, col1_w - 32, 60, label="Deskripsi Kategori", placeholder="Cakupan aset...", is_textarea=True)
    wb.button(cx + 16, cy + 210, col1_w - 32, 36, "Simpan Kategori", style="dark", size=10, rx=6)
    
    # Right: Table List
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 480, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Daftar Kategori Tersedia", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    kats = [
        ("1", "Sound & Audio", "Speaker, mic, mixer", "12 Aset"),
        ("2", "Tenda & Terop", "Tenda kerucut, sarnafil", "8 Aset"),
        ("3", "Kursi & Meja", "Kursi lipat, meja bundar", "15 Aset"),
        ("4", "Alat Olahraga", "Matras, bola, net", "6 Aset"),
        ("5", "Panggung & Rigging", "Modul panggung portabel", "4 Aset")
    ]
    ky = cy + 42
    for no, name, desc, cnt in kats:
        wb.rect(rx_col + 8, ky, col2_w - 16, 50, rx=4, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(rx_col + 16, ky + 24, no, size=9, fill="#000000")
        wb.text(rx_col + 40, ky + 20, name, size=9, weight="bold", fill="#000000")
        wb.text(rx_col + 40, ky + 34, desc, size=8, fill="#555555")
        wb.text(rx_col + col2_w - 110, ky + 28, cnt, size=8, weight="bold", fill="#000000")
        wb.button(rx_col + col2_w - 60, ky + 14, 45, 24, "Edit", style="default", size=8, rx=3)
        ky += 58
        
    wb.save("wireframe_27_admin_aset_kategori.svg")


def create_28_admin_peminjaman_index():
    wb = WireframeBuilder("SIPANDA - Persetujuan Peminjaman Admin", "https://sipanda.dispora.palembang.go.id/admin/peminjaman", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Peminjaman", page_title="Persetujuan Akhir Peminjaman")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    tabs = ["Perlu Persetujuan (2)", "Disetujui (14)", "Ditolak (2)", "Semua (18)"]
    tab_x = cx
    for i, t in enumerate(tabs):
        w = len(t) * 7 + 16
        style = "active" if i == 0 else "default"
        wb.button(tab_x, cy, w, 26, t, style=style, size=9, rx=5)
        tab_x += w + 6
        
    wb.input_field(cx + cw - 240, cy, 240, 26, placeholder="search pemohon/surat...")
    
    tbl_y = cy + 36
    wb.rect(cx, tbl_y, cw, 480, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    
    wb.rect(cx, tbl_y, cw, 30, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [
        (cx + 12, "NO"), (cx + 40, "TRX ID"), (cx + 115, "PEMOHON"), (cx + 260, "ASET"),
        (cx + 410, "JADWAL"), (cx + 510, "REKOMENDASI"), (cx + 630, "STATUS"), (cx + 720, "AKSI")
    ]
    for x, h in cols:
        wb.text(x, tbl_y + 20, h, size=9, weight="bold", fill="#000000")
        
    rows = [
        ("1", "TRX-0042", "Ahmad Fauzi (Bappeda)", "Sound System 2 Unit", "20-22 Agt", "Layak Disetujui", "Diverifikasi"),
        ("2", "TRX-0043", "Rahmat H. (KNPI)", "Tenda Kerucut 4 Unit", "21-23 Agt", "Layak Disetujui", "Diverifikasi"),
        ("3", "TRX-0038", "Disdik Sumsel", "Panggung Portabel", "15-18 Agt", "Disetujui Final", "Disetujui"),
        ("4", "TRX-0035", "Karang Taruna", "Kursi Chitose 50 Unit", "10-12 Agt", "Disetujui Final", "Disetujui")
    ]
    ry = tbl_y + 36
    for no, code, pemohon, asset, tgl, rekom, st in rows:
        wb.rect(cx + 4, ry, cw - 8, 54, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 30, no, size=9, fill="#000000")
        wb.text(cx + 40, ry + 30, code, size=9, weight="bold", fill="#000000")
        wb.text(cx + 115, ry + 30, pemohon, size=9, fill="#000000")
        wb.text(cx + 260, ry + 30, asset, size=8, fill="#000000")
        wb.text(cx + 410, ry + 30, tgl, size=8, fill="#555555")
        wb.text(cx + 510, ry + 30, rekom, size=8, fill="#000000")
        wb.pill_badge(cx + 630, ry + 18, 70, 18, st, is_dark=(st=="Disetujui"))
        wb.button(cx + 720, ry + 14, 75, 26, "Setujui →", style="dark" if st=="Diverifikasi" else "default", size=8, rx=4)
        ry += 60
        
    wb.save("wireframe_28_admin_peminjaman_index.svg")


def create_29_admin_peminjaman_show():
    wb = WireframeBuilder("SIPANDA - Approval Peminjaman Admin", "https://sipanda.dispora.palembang.go.id/admin/peminjaman/42", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Peminjaman", page_title="Approval Peminjaman #TRX-0042")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    col1_w = (cw - 14) * 0.52
    col2_w = (cw - 14) * 0.48
    
    # Left: Rincian Permohonan
    wb.rect(cx, cy, col1_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Rincian Permohonan Peminjaman", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col1_w, cy + 32, stroke="#000000", stroke_width=1)
    
    details = [
        ("No Transaksi", "TRX-2026-0042"),
        ("Nama Pemohon", "Ahmad Fauzi, S.STP"),
        ("Instansi", "Bappeda Kota Palembang (Prioritas Skor 3)"),
        ("Aset Diminta", "Sound System Portable Wireless (2 Unit)"),
        ("Jadwal Pinjam", "20 Agustus 2026 s/d 22 Agustus 2026"),
        ("Keperluan", "Turnamen Futsal Pemuda Walikota Cup 2026")
    ]
    dy = cy + 54
    for k, v in details:
        wb.text(cx + 16, dy, k, size=8, fill="#555555")
        wb.text(cx + 100, dy, ": " + v, size=8, weight="bold" if "TRX" in v else "normal", fill="#000000")
        dy += 26
        
    wb.text(cx + 16, dy + 10, "Catatan Verifikasi Pengelola Sarpras:", size=8, fill="#555555")
    wb.rect(cx + 16, dy + 20, col1_w - 32, 54, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(cx + 24, dy + 40, "\"Aset telah diperiksa di gudang, kondisi fisik baik.", size=8, fill="#000000")
    wb.text(cx + 24, dy + 54, "Direkomendasikan untuk disetujui.\"", size=8, fill="#000000")
    
    # Right: Form Approval Admin
    rx_col = cx + col1_w + 14
    wb.rect(rx_col, cy, col2_w, 520, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Persetujuan Kepala Dinas / Admin", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col2_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.input_field(rx_col + 16, cy + 54, col2_w - 32, 32, label="Nomor Surat Izin Keluar *", value="SIPANDA/DISPORA/2026/08/0042")
    wb.input_field(rx_col + 16, cy + 114, col2_w - 32, 32, label="Pejabat Penandatangan *", value="H. Edison, S.Sos., M.Si (Kepala Dinas)")
    wb.input_field(rx_col + 16, cy + 174, col2_w - 32, 60, label="Catatan / Syarat Khusus", value="Disetujui. Pengembalian maksimal tgl 22 Agustus 2026.", is_textarea=True)
    
    wb.checkbox(rx_col + 16, cy + 260, "Terbitkan Surat Izin Resmi & Token Barcode Digital", checked=True)
    wb.button(rx_col + 16, cy + 300, col2_w - 32, 38, "✓ SETUJUI & TERBITKAN SURAT IZIN", style="dark", size=10, rx=6)
    wb.button(rx_col + 16, cy + 348, col2_w - 32, 32, "✕ Tolak Permohonan", style="default", size=9, rx=6)
    
    wb.save("wireframe_29_admin_peminjaman_show.svg")


def create_30_admin_laporan_index():
    wb = WireframeBuilder("SIPANDA - Laporan & Export Data", "https://sipanda.dispora.palembang.go.id/admin/laporan", 1050, 750)
    wb.draw_app_layout(role="admin", active_menu="Laporan/Export", page_title="Laporan & Export Data")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    # Filter Card
    wb.rect(cx, cy, cw, 70, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.select_field(cx + 12, cy + 18, 180, 28, label="Jenis Laporan", value="Rekap Peminjaman")
    wb.input_field(cx + 200, cy + 18, 110, 28, label="Tgl Awal", value="01/08/2026")
    wb.input_field(cx + 320, cy + 18, 110, 28, label="Tgl Akhir", value="31/08/2026")
    wb.select_field(cx + 440, cy + 18, 130, 28, label="Status", value="Semua Status")
    
    wb.button(cx + cw - 180, cy + 24, 80, 30, "📄 PDF", style="dark", size=9, rx=4)
    wb.button(cx + cw - 90, cy + 24, 80, 30, "📊 Excel", style="default", size=9, rx=4)
    
    # Preview Table
    tbl_y = cy + 84
    wb.rect(cx, tbl_y, cw, 430, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 14, tbl_y + 20, "Pratinjau Data Peminjaman (Agustus 2026) · Total: 18 Transaksi", size=10, weight="bold", fill="#000000")
    wb.line(cx, tbl_y + 28, cx + cw, tbl_y + 28, stroke="#000000", stroke_width=1)
    
    wb.rect(cx, tbl_y + 28, cw, 26, rx=0, fill="#eaeaea", stroke="#000000", stroke_width=1)
    cols = [(cx + 12, "NO"), (cx + 40, "TRX ID"), (cx + 115, "TGL PINJAM"), (cx + 210, "PEMOHON"), (cx + 360, "ASET"), (cx + 540, "STATUS")]
    for x, h in cols:
        wb.text(x, tbl_y + 44, h, size=8, weight="bold", fill="#000000")
        
    rekap = [
        ("1", "TRX-0042", "20/08/2026", "Ahmad Fauzi (Bappeda)", "Sound System 2 Unit", "Disetujui"),
        ("2", "TRX-0043", "21/08/2026", "Rahmat H. (KNPI)", "Tenda Kerucut 4 Unit", "Disetujui"),
        ("3", "TRX-0038", "15/08/2026", "Disdik Sumsel", "Panggung Portabel", "Disetujui"),
        ("4", "TRX-0035", "10/08/2026", "Karang Taruna", "Kursi Chitose 50 Unit", "Selesai"),
        ("5", "TRX-0029", "01/08/2026", "Sanggar Seni", "Genset 5KVA", "Selesai")
    ]
    ry = tbl_y + 54
    for no, code, tgl, name, asset, st in rekap:
        wb.rect(cx + 4, ry, cw - 8, 44, rx=2, fill="#ffffff", stroke="#cccccc", stroke_width=1)
        wb.text(cx + 12, ry + 24, no, size=8, fill="#000000")
        wb.text(cx + 40, ry + 24, code, size=8, weight="bold", fill="#000000")
        wb.text(cx + 115, ry + 24, tgl, size=8, fill="#555555")
        wb.text(cx + 210, ry + 24, name, size=8, fill="#000000")
        wb.text(cx + 360, ry + 24, asset, size=8, fill="#000000")
        wb.pill_badge(cx + 540, ry + 12, 65, 18, st, is_dark=(st=="Disetujui"))
        ry += 48
        
    wb.save("wireframe_30_admin_laporan_index.svg")


def create_31_profile_edit():
    wb = WireframeBuilder("SIPANDA - Profil Pengguna", "https://sipanda.dispora.palembang.go.id/profile", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Profil Saya", page_title="Pengaturan Profil Akun")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    col_w = (cw - 14) / 2
    
    # Left: Informasi Profil
    wb.rect(cx, cy, col_w, 360, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, cy + 22, "Informasi Profil", size=11, weight="bold", fill="#000000")
    wb.line(cx, cy + 32, cx + col_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.input_field(cx + 16, cy + 60, col_w - 32, 32, label="Nama Lengkap", value="Ahmad Fauzi, S.STP")
    wb.input_field(cx + 16, cy + 118, col_w - 32, 32, label="Email", value="ahmad@bappeda.palembang.go.id")
    wb.input_field(cx + 16, cy + 176, col_w - 32, 32, label="No. Telepon / WhatsApp", value="0812-3456-7890")
    wb.input_field(cx + 16, cy + 234, col_w - 32, 32, label="Nama Instansi", value="Bappeda Kota Palembang")
    wb.button(cx + 16, cy + 290, 140, 36, "Simpan Perubahan", style="dark", size=10, rx=6)
    
    # Right: Ganti Password
    rx_col = cx + col_w + 14
    wb.rect(rx_col, cy, col_w, 360, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(rx_col + 16, cy + 22, "Perbarui Kata Sandi", size=11, weight="bold", fill="#000000")
    wb.line(rx_col, cy + 32, rx_col + col_w, cy + 32, stroke="#000000", stroke_width=1)
    
    wb.input_field(rx_col + 16, cy + 60, col_w - 32, 32, label="Password Saat Ini", placeholder="••••••••")
    wb.input_field(rx_col + 16, cy + 118, col_w - 32, 32, label="Password Baru", placeholder="••••••••")
    wb.input_field(rx_col + 16, cy + 176, col_w - 32, 32, label="Konfirmasi Password Baru", placeholder="••••••••")
    wb.button(rx_col + 16, cy + 290, 140, 36, "Simpan Password", style="dark", size=10, rx=6)
    
    # Bottom: Hapus Akun
    del_y = cy + 374
    wb.rect(cx, del_y, cw, 100, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
    wb.text(cx + 16, del_y + 22, "Hapus Akun Pengguna", size=11, weight="bold", fill="#000000")
    wb.text(cx + 16, del_y + 40, "Setelah akun dihapus, seluruh data peminjaman dan akses akan dibekukan permanen.", size=8, fill="#555555")
    wb.button(cx + 16, del_y + 56, 150, 32, "Hapus Akun Saya", style="default", size=9, rx=4)
    
    wb.save("wireframe_31_profile_edit.svg")


def create_32_notifikasi_index():
    wb = WireframeBuilder("SIPANDA - Pusat Notifikasi", "https://sipanda.dispora.palembang.go.id/notifikasi", 1050, 750)
    wb.draw_app_layout(role="peminjam", active_menu="Notifikasi", page_title="Pusat Notifikasi")
    
    cx, cy = 195, 126
    cw = 1050 - cx - 14
    
    wb.text(cx, cy + 18, "Semua Notifikasi (2 Belum Dibaca)", size=11, weight="bold", fill="#000000")
    wb.button(cx + cw - 180, cy, 180, 26, "Tandai Semua Dibaca", style="default", size=9, rx=4)
    
    list_y = cy + 36
    notifs = [
        ("Peminjaman Anda Telah Disetujui!", "Pengajuan Sound System (TRX-0042) telah disetujui. Unduh surat izin sekarang.", "10 mnt lalu", True),
        ("Tanggapan Keluhan Aset #15", "Pengelola merespon: Teknisi dijadwalkan datang besok.", "2 jam lalu", True),
        ("Pengajuan Peminjaman Terkirim", "Pengajuan TRX-0042 berhasil masuk antrean verifikasi pengelola.", "1 hari lalu", False),
        ("Pengembalian Aset Selesai", "Peminjaman Kursi Lipat (TRX-0029) dikonfirmasi selesai.", "2 minggu lalu", False)
    ]
    
    for tit, msg, time_diff, unread in notifs:
        wb.rect(cx, list_y, cw, 68, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5 if unread else 1)
        wb.checkbox(cx + 14, list_y + 24, checked=unread)
        wb.text(cx + 40, list_y + 24, tit, size=10, weight="bold" if unread else "normal", fill="#000000")
        wb.text(cx + 40, list_y + 40, msg, size=8, fill="#555555")
        wb.text(cx + cw - 80, list_y + 24, time_diff, size=8, fill="#777777")
        wb.button(cx + cw - 160, list_y + 36, 75, 24, "Lihat →", style="default", size=8, rx=4)
        list_y += 76
        
    wb.save("wireframe_32_notifikasi_index.svg")


def create_33_surat_scan_barcode_result():
    wb = WireframeBuilder("SIPANDA - Validasi Barcode Surat Izin", "https://sipanda.dispora.palembang.go.id/surat/scan/tok123", 1000, 750)
    wb.draw_browser_frame()
    
    card_x, card_y, card_w, card_h = 250, 95, 500, 600
    wb.rect(card_x, card_y, card_w, card_h, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
    
    # Official Valid Badge
    wb.rect(card_x + 24, card_y + 20, card_w - 48, 44, rx=4, fill="#eaeaea", stroke="#000000", stroke_width=1.5)
    wb.text(card_x + card_w/2, card_y + 46, "DOKUMEN RESMI & TERVERIFIKASI SAH", size=11, weight="bold", fill="#000000", align="center")
    
    wb.text(card_x + card_w/2, card_y + 90, "DINAS KEPEMUDAAN DAN OLAHRAGA", size=13, weight="bold", fill="#000000", align="center")
    wb.text(card_x + card_w/2, card_y + 104, "SURAT IZIN PINJAM PAKAI ASET DAERAH", size=10, fill="#555555", align="center")
    wb.line(card_x + 24, card_y + 114, card_x + card_w - 24, card_y + 114, stroke="#000000", stroke_width=1.5)
    
    val_data = [
        ("Nomor Surat Izin", "SIPANDA/DISPORA/2026/08/0042"),
        ("Token Barcode", "TOK-89AB-3C4D-99 (Valid)"),
        ("Nama Peminjam", "Ahmad Fauzi (Bappeda Palembang)"),
        ("Aset yang Dipinjam", "Sound System Portable Wireless (2 Unit)"),
        ("Masa Berlaku Izin", "20 Agustus 2026 s/d 22 Agustus 2026"),
        ("Keperluan Acara", "Turnamen Futsal Pemuda Walikota Cup"),
        ("Status Keabsahan", "AKTIF / DISETUJUI RESMI OLEH KEPALA DINAS")
    ]
    vy = card_y + 140
    for k, v in val_data:
        wb.text(card_x + 24, vy, k, size=8, fill="#555555")
        wb.text(card_x + 150, vy, ": " + v, size=8, weight="bold" if "AKTIF" in v else "normal", fill="#000000")
        vy += 28
        
    wb.rect(card_x + 24, vy + 10, card_w - 48, 60, rx=4, fill="#f8f8f8", stroke="#000000", stroke_width=1)
    wb.text(card_x + 36, vy + 32, "Pejabat: H. Edison, S.Sos., M.Si (Kepala Dinas Dispora Palembang)", size=8, weight="bold", fill="#000000")
    wb.text(card_x + 36, vy + 48, "Divalidasi elektronik pada: 17 Agustus 2026, 20:10 WIB", size=8, fill="#555555")
    
    wb.button(card_x + 24, vy + 84, card_w - 48, 38, "Unduh Salinan Surat Izin (PDF)", style="dark", size=10, rx=6)
    
    wb.save("wireframe_33_surat_scan_barcode_result.svg")


def run_batch3():
    print("Generating B&W Wireframes Batch 3 (20-33)...")
    create_20_admin_dashboard()
    create_21_admin_users_index()
    create_22_admin_users_create_edit()
    create_23_admin_users_show()
    create_24_admin_aset_index()
    create_25_admin_aset_create_edit()
    create_26_admin_aset_show()
    create_27_admin_aset_kategori()
    create_28_admin_peminjaman_index()
    create_29_admin_peminjaman_show()
    create_30_admin_laporan_index()
    create_31_profile_edit()
    create_32_notifikasi_index()
    create_33_surat_scan_barcode_result()

if __name__ == '__main__':
    run_batch3()
