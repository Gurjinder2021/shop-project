#!/usr/bin/env python3
from datetime import datetime

PAGE_WIDTH = 612
PAGE_HEIGHT = 792
LEFT = 54
TOP = 738

# Fonts
FONT_REG = "Helvetica"
FONT_BOLD = "Helvetica-Bold"

# Sizes
TITLE_SIZE = 18
H_SIZE = 12
B_SIZE = 11
LEADING = 14
H_LEADING = 16


def esc(text: str) -> str:
    return text.replace('\\', '\\\\').replace('(', '\\(').replace(')', '\\)')


def line_cmd(x, y, font, size, text):
    return f"BT /{font} {size} Tf 1 0 0 1 {x} {y} Tm ({esc(text)}) Tj ET\n"


def build():
    y = TOP
    lines = []

    # Title
    lines.append(line_cmd(LEFT, y, FONT_BOLD, TITLE_SIZE, "Shop Project App Summary"))
    y -= 24

    def header(text):
        nonlocal y
        lines.append(line_cmd(LEFT, y, FONT_BOLD, H_SIZE, text))
        y -= H_LEADING

    def body(text):
        nonlocal y
        lines.append(line_cmd(LEFT, y, FONT_REG, B_SIZE, text))
        y -= LEADING

    def bullet(text):
        nonlocal y
        lines.append(line_cmd(LEFT + 10, y, FONT_REG, B_SIZE, f"- {text}"))
        y -= LEADING

    header("What it is")
    body("A Laravel web app for managing users, their assigned shops, and daily sales collections,")
    body("with role-based admin and user dashboards.")
    y -= 4

    header("Who it is for")
    body("Admins who manage shop assignments and reporting, and shop users who enter daily collection data.")
    y -= 4

    header("What it does")
    bullet("Role-based authentication with admin and user dashboards.")
    bullet("Admin can create, edit, and delete users.")
    bullet("Assign shops to users (single or bulk) with unique shop number checks.")
    bullet("Record daily collections per shop with online/offline amounts and totals.")
    bullet("View shop and collection reports; export shops and collections to Excel.")
    bullet("Manage shops (view, update, delete) and see per-user shops.")
    y -= 4

    header("How it works")
    bullet("Routes in routes/web.php map to User, Admin, and DailyCollection controllers with auth/admin/user middleware.")
    bullet("Eloquent models (User, Shop, DailyCollection) persist data via migrations and defined relationships.")
    bullet("Blade views render UI; Excel exports use maatwebsite/excel package.")
    y -= 4

    header("How to run")
    bullet("composer install")
    bullet("cp .env.example .env (if needed) and set DB config: Not found in repo")
    bullet("php artisan key:generate")
    bullet("php artisan migrate")
    bullet("npm install && npm run dev (assets) and php artisan serve (app)")

    return "".join(lines)


def write_pdf(path):
    content = build().encode("latin1")

    objects = []
    xref = []

    def add_obj(obj_str):
        xref.append(sum(len(o) for o in objects))
        objects.append(obj_str)

    # 1: Catalog
    add_obj("1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n")
    # 2: Pages
    add_obj("2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n")
    # 3: Page
    add_obj(
        "3 0 obj\n"
        f"<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {PAGE_WIDTH} {PAGE_HEIGHT}]\n"
        "   /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >>\n"
        "   /Contents 6 0 R >>\n"
        "endobj\n"
    )
    # 4: Font regular
    add_obj("4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n")
    # 5: Font bold
    add_obj("5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n")
    # 6: Content stream
    add_obj(
        "6 0 obj\n"
        f"<< /Length {len(content)} >>\n"
        "stream\n"
    )
    objects.append(content.decode("latin1") + "\nendstream\nendobj\n")

    # Build xref table
    pdf_header = "%PDF-1.4\n"
    body = "".join(objects)
    xref_start = len(pdf_header.encode("latin1")) + len(body.encode("latin1"))

    xref_table = ["xref\n", f"0 {len(objects)+1}\n", "0000000000 65535 f \n"]
    offset = len(pdf_header)
    for obj in objects:
        xref_table.append(f"{offset:010d} 00000 n \n")
        offset += len(obj)

    trailer = (
        "trailer\n"
        f"<< /Size {len(objects)+1} /Root 1 0 R >>\n"
        "startxref\n"
        f"{xref_start}\n"
        "%%EOF\n"
    )

    with open(path, "wb") as f:
        f.write(pdf_header.encode("latin1"))
        f.write(body.encode("latin1"))
        f.write("".join(xref_table).encode("latin1"))
        f.write(trailer.encode("latin1"))


if __name__ == "__main__":
    write_pdf("output/pdf/shop-project-summary.pdf")
