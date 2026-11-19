"""
Balsamiq-Style Black & White Wireframe Generator
Strictly Monochrome / Grayscale (Black, White, Gray)
Faithful to classic hand-drawn / low-fidelity wireframe aesthetics
"""

import os

OUTPUT_DIR = os.path.dirname(os.path.abspath(__file__))

class WireframeBuilder:
    def __init__(self, title, url, width=1050, height=750):
        self.title = title
        self.url = url
        self.width = width
        self.height = height
        self.elements = []
        self.markers_needed = True

    def rect(self, x, y, w, h, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.5, dash=None):
        dash_attr = f' stroke-dasharray="{dash}"' if dash else ''
        self.elements.append(
            f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{rx}" fill="{fill}" stroke="{stroke}" stroke-width="{stroke_width}"{dash_attr}/>'
        )

    def line(self, x1, y1, x2, y2, stroke="#000000", stroke_width=1.5, dash=None):
        dash_attr = f' stroke-dasharray="{dash}"' if dash else ''
        self.elements.append(
            f'<line x1="{x1}" y1="{y1}" x2="{x2}" y2="{y2}" stroke="{stroke}" stroke-width="{stroke_width}"{dash_attr}/>'
        )

    def text(self, x, y, text, size=12, weight="normal", fill="#000000", align="left", font_family="'Segoe UI', Arial, Helvetica, sans-serif"):
        anchor = "start" if align == "left" else ("middle" if align == "center" else "end")
        clean_text = (str(text).replace("&", "&amp;")
                               .replace("<", "&lt;")
                               .replace(">", "&gt;"))
        self.elements.append(
            f'<text x="{x}" y="{y}" font-family="{font_family}" font-size="{size}" font-weight="{weight}" fill="{fill}" text-anchor="{anchor}">{clean_text}</text>'
        )

    def text_bar(self, x, y, w, h=8, rx=4, fill="#707070"):
        """Gray placeholder text bar like in the Balsamiq reference"""
        self.rect(x, y, w, h, rx=rx, fill=fill, stroke="none", stroke_width=0)

    def image_box(self, x, y, w, h, label="", rx=0, fill="#ffffff", stroke="#000000"):
        """Classic wireframe box with diagonal X cross"""
        self.rect(x, y, w, h, rx=rx, fill=fill, stroke=stroke, stroke_width=1.5)
        self.line(x, y, x + w, y + h, stroke=stroke, stroke_width=1.2)
        self.line(x, y + h, x + w, y, stroke=stroke, stroke_width=1.2)
        if label:
            bg_w = min(w - 8, len(label) * 7 + 14)
            bg_h = 18
            self.rect(x + (w - bg_w)/2, y + (h - bg_h)/2, bg_w, bg_h, rx=2, fill="#ffffff", stroke="#000000", stroke_width=1)
            self.text(x + w/2, y + h/2 + 4, label, size=10, weight="bold", fill="#000000", align="center")

    def button(self, x, y, w, h, label, style="default", size=11, rx=8):
        """Balsamiq-style pill / rounded beveled button in pure B&W"""
        # Outer border
        bg = "#eaeaea" if style == "active" or style == "dark" else "#ffffff"
        self.rect(x, y, w, h, rx=rx, fill=bg, stroke="#000000", stroke_width=1.5)
        # Inner subtle bevel line for that 3D sketch feel
        if h >= 24 and w >= 30:
            self.rect(x + 1.5, y + 1.5, w - 3, h - 3, rx=max(1, rx - 1), fill="none", stroke="#cccccc" if bg == "#ffffff" else "#999999", stroke_width=1)
        
        txt_weight = "bold" if (style in ["primary", "active", "bold"]) else "normal"
        self.text(x + w/2, y + h/2 + 4, label, size=size, weight=txt_weight, fill="#000000", align="center")

    def pill_badge(self, x, y, w, h, label, size=9, is_dark=False):
        """Black and white status badge / tag"""
        fill = "#333333" if is_dark else "#f0f0f0"
        txt_fill = "#ffffff" if is_dark else "#000000"
        self.rect(x, y, w, h, rx=h/2, fill=fill, stroke="#000000", stroke_width=1.2)
        self.text(x + w/2, y + h/2 + 3.5, label, size=size, weight="bold", fill=txt_fill, align="center")

    def input_field(self, x, y, w, h, label="", value="", placeholder="", is_textarea=False, rx=6):
        """Clean input field in black & white"""
        if label:
            self.text(x, y - 5, label, size=10, weight="bold", fill="#000000")
        self.rect(x, y, w, h, rx=rx, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        if value:
            self.text(x + 10, y + (h/2 + 4 if not is_textarea else 18), value, size=11, fill="#000000")
        elif placeholder:
            self.text(x + 10, y + (h/2 + 4 if not is_textarea else 18), placeholder, size=11, fill="#777777")
        if is_textarea:
            # Resize tick marks in bottom right
            self.line(x + w - 10, y + h - 3, x + w - 3, y + h - 10, stroke="#666666", stroke_width=1.2)
            self.line(x + w - 6, y + h - 3, x + w - 3, y + h - 6, stroke="#666666", stroke_width=1.2)

    def select_field(self, x, y, w, h, label="", value=""):
        if label:
            self.text(x, y - 5, label, size=10, weight="bold", fill="#000000")
        self.rect(x, y, w, h, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        self.text(x + 10, y + h/2 + 4, value or "-- Pilih Opsi --", size=11, fill="#000000" if value else "#777777")
        self.text(x + w - 18, y + h/2 + 4, "▼", size=9, fill="#000000")

    def checkbox(self, x, y, label="", checked=False):
        """Balsamiq-style checkbox with X inside"""
        self.rect(x, y, 16, 16, rx=2, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        if checked:
            self.line(x + 2, y + 2, x + 14, y + 14, stroke="#000000", stroke_width=1.5)
            self.line(x + 2, y + 14, x + 14, y + 2, stroke="#000000", stroke_width=1.5)
        if label:
            self.text(x + 24, y + 12, label, size=11, fill="#000000")

    def radio_button(self, x, y, label="", checked=False):
        self.rect(x, y, 16, 16, rx=8, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        if checked:
            self.rect(x + 4, y + 4, 8, 8, rx=4, fill="#000000", stroke="#000000", stroke_width=1)
        if label:
            self.text(x + 24, y + 12, label, size=11, fill="#000000")

    def callout_arrow(self, x1, y1, x2, y2, label="", tag_bg="#333333", tag_color="#ffffff"):
        """Hand-drawn callout arrow with label tag, just like the reference 'Ad.' arrow"""
        cx = (x1 + x2) / 2 + 20
        cy = (y1 + y2) / 2 - 20
        self.elements.append(
            f'<path d="M {x1} {y1} Q {cx} {cy} {x2} {y2}" fill="none" stroke="#000000" stroke-width="2" marker-end="url(#arrowhead)"/>'
        )
        if label:
            lbl_w = len(label) * 7 + 12
            self.rect(x1 - lbl_w/2 if x1 > x2 else x1 - lbl_w - 4, y1 - 10, lbl_w, 20, rx=3, fill=tag_bg, stroke="#000000", stroke_width=1)
            self.text(x1 if x1 > x2 else x1 - lbl_w/2 - 4, y1 + 4, label, size=10, weight="bold", fill=tag_color, align="center")

    def draw_browser_frame(self):
        """Browser frame exactly matching the reference YouTube wireframe layout"""
        # Outer container
        self.rect(4, 4, self.width - 8, self.height - 8, rx=8, fill="#ffffff", stroke="#000000", stroke_width=2)
        
        # Window Title bar
        self.rect(4, 4, self.width - 8, 30, rx=8, fill="#e8e8e8", stroke="#000000", stroke_width=1.5)
        # Cover bottom corners
        self.rect(4, 18, self.width - 8, 16, rx=0, fill="#e8e8e8", stroke="#e8e8e8", stroke_width=0)
        self.line(4, 34, self.width - 4, 34, stroke="#000000", stroke_width=1.5)
        
        # Title in middle
        self.text(self.width / 2, 22, self.title, size=13, weight="bold", fill="#000000", align="center")
        
        # Close button box on top right
        self.rect(self.width - 32, 9, 20, 18, rx=2, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        self.line(self.width - 29, 12, self.width - 15, 24, stroke="#000000", stroke_width=1.5)
        self.line(self.width - 29, 24, self.width - 15, 12, stroke="#000000", stroke_width=1.5)
        
        # Toolbar Row
        self.rect(4, 34, self.width - 8, 38, rx=0, fill="#f8f8f8", stroke="#f8f8f8", stroke_width=0)
        self.line(4, 72, self.width - 4, 72, stroke="#000000", stroke_width=1.5)
        
        # Back/Forward capsule
        self.rect(14, 41, 60, 24, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        self.text(28, 57, "←", size=13, weight="bold", fill="#000000", align="center")
        self.line(44, 41, 44, 65, stroke="#000000", stroke_width=1)
        self.text(58, 57, "→", size=13, weight="bold", fill="#000000", align="center")
        
        # Refresh button box
        self.rect(80, 41, 28, 24, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        self.text(94, 57, "↻", size=13, weight="bold", fill="#000000", align="center")
        
        # URL Address Bar
        url_w = self.width - 134
        self.rect(116, 41, url_w, 24, rx=4, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        self.text(126, 57, self.url, size=11, fill="#000000")

    def draw_app_layout(self, role="peminjam", active_menu="Dashboard", page_title="Dashboard", breadcrumb=""):
        """Draws standard SIPANDA layout with sidebar and top header in pure Black & White"""
        self.draw_browser_frame()
        
        sidebar_w = 175
        content_x = sidebar_w + 12
        content_y = 73
        content_w = self.width - content_x - 12
        content_h = self.height - content_y - 12
        
        # Left Sidebar Outer Container
        self.rect(12, content_y + 8, sidebar_w - 4, content_h - 4, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        
        # Top Logo Image placeholder with X
        self.image_box(20, content_y + 16, sidebar_w - 20, 44, label="SIPANDA", rx=4)
        
        # Role Label
        self.text(20 + (sidebar_w - 20)/2, content_y + 74, f"ROLE: {role.upper()}", size=9, weight="bold", fill="#000000", align="center")
        self.line(20, content_y + 80, sidebar_w, content_y + 80, stroke="#000000", stroke_width=1)
        
        # Navigation Buttons (Pill shaped like reference)
        menus = {
            "peminjam": ["Cari Aset", "Dashboard", "Ajukan Pinjam", "Riwayat Pinjam", "Keluhan Aset", "Notifikasi", "Profil Saya", "Keluar"],
            "pengelola": ["Dashboard", "Pengajuan", "Penilaian DT", "Keluhan Masuk", "Notifikasi", "Profil Saya", "Keluar"],
            "admin": ["Dashboard", "Manajemen User", "Master Aset", "Peminjaman", "Laporan/Export", "Notifikasi", "Profil Saya", "Keluar"]
        }
        current_menus = menus.get(role, menus["peminjam"])
        
        btn_y = content_y + 90
        for item in current_menus:
            is_active = (item.lower() == active_menu.lower() or (item == "Cari Aset" and active_menu == "Cari Aset"))
            style = "active" if is_active else "default"
            self.button(20, btn_y, sidebar_w - 20, 26, item, style=style, size=10, rx=8)
            btn_y += 32
            
        # Subscriptions / Quick Status list with [X] checkboxes and gray text bars (just like reference)
        self.line(20, btn_y + 6, sidebar_w, btn_y + 6, stroke="#000000", stroke_width=1)
        self.text(20, btn_y + 20, "Status Aset", size=10, weight="bold", fill="#000000")
        
        sub_y = btn_y + 28
        for i in range(3):
            self.checkbox(20, sub_y, checked=True)
            self.text_bar(44, sub_y + 4, sidebar_w - 50, h=7, rx=3, fill="#888888")
            sub_y += 20
            
        # Top Header Bar in content
        self.rect(content_x, content_y + 8, content_w, 42, rx=6, fill="#ffffff", stroke="#000000", stroke_width=1.5)
        
        # Search capsule in top bar
        self.rect(content_x + 12, content_y + 15, 260, 26, rx=13, fill="#ffffff", stroke="#000000", stroke_width=1.2)
        self.text(content_x + 26, content_y + 32, "search aset / transaksi...", size=10, fill="#777777")
        
        # Header title
        self.text(content_x + 290, content_y + 32, f"|  {page_title}", size=11, weight="bold", fill="#000000")
        
        # Right action buttons (Upload / Noti. / Sett. style from reference)
        self.button(self.width - 220, content_y + 15, 60, 26, "Notif.", size=10, rx=6)
        self.button(self.width - 154, content_y + 15, 64, 26, "Profil", size=10, rx=6)
        self.button(self.width - 84, content_y + 15, 64, 26, "Keluar", size=10, rx=6)

    def to_svg(self):
        svg = [
            f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {self.width} {self.height}" width="100%" height="100%">',
            '  <defs>',
            '    <marker id="arrowhead" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">',
            '      <path d="M 1 1 L 7 4 L 1 7 Z" fill="#000000" />',
            '    </marker>',
            '    <style>',
            '      text { font-family: "Segoe UI", Arial, Helvetica, sans-serif; user-select: none; }',
            '    </style>',
            '  </defs>'
        ]
        svg.extend(self.elements)
        svg.append('</svg>')
        return '\n'.join(svg)

    def save(self, filename):
        filepath = os.path.join(OUTPUT_DIR, filename)
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(self.to_svg())
        print(f"Generated B&W Wireframe: {filename}")

