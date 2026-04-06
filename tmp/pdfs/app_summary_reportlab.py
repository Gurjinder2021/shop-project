#!/usr/bin/env python3
from reportlab.pdfgen import canvas
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch

OUTPUT = "output/pdf/shop-project-summary.pdf"

TITLE = "Shop Project App Summary"

sections = [
    ("What it is", [
        "A Laravel web app for managing users, their assigned shops, and daily sales collections,",
        "with role-based admin and user dashboards.",
    ]),
    ("Who it is for", [
        "Admins who manage shop assignments and reporting, and shop users who enter daily collection data.",
    ]),
    ("What it does", [
        "Role-based authentication with admin and user dashboards.",
        "Admin can create, edit, and delete users.",
        "Assign shops to users (single or bulk) with unique shop number checks.",
        "Record daily collections per shop with online/offline amounts and totals.",
        "View shop and collection reports; export shops and collections to Excel.",
        "Manage shops (view, update, delete) and see per-user shops.",
    ]),
    ("How it works", [
        "Routes in routes/web.php map to User, Admin, and DailyCollection controllers with auth/admin/user middleware.",
        "Eloquent models (User, Shop, DailyCollection) persist data via migrations and defined relationships.",
        "Blade views render UI; Excel exports use maatwebsite/excel package.",
    ]),
    ("How to run", [
        "composer install",
        "cp .env.example .env (if needed)",
        "Set DB credentials in .env: Not found in repo",
        "php artisan key:generate",
        "php artisan migrate",
        "npm install",
        "npm run dev",
        "php artisan serve",
    ]),
]


def draw_wrapped(c, text, x, y, max_width, font, size, leading):
    c.setFont(font, size)
    words = text.split(" ")
    line = ""
    for w in words:
        test = (line + " " + w).strip()
        if c.stringWidth(test, font, size) <= max_width:
            line = test
        else:
            c.drawString(x, y, line)
            y -= leading
            line = w
    if line:
        c.drawString(x, y, line)
        y -= leading
    return y


def build():
    c = canvas.Canvas(OUTPUT, pagesize=letter)
    width, height = letter

    left = 0.75 * inch
    right = 0.75 * inch
    top = height - 0.75 * inch
    max_width = width - left - right

    y = top

    c.setFont("Helvetica-Bold", 18)
    c.drawString(left, y, TITLE)
    y -= 22

    for title, items in sections:
        c.setFont("Helvetica-Bold", 12)
        c.drawString(left, y, title)
        y -= 16

        if title in ("What it is", "Who it is for"):
            for line in items:
                y = draw_wrapped(c, line, left, y, max_width, "Helvetica", 11, 14)
            y -= 4
            continue

        for item in items:
            bullet_text = "- " + item
            y = draw_wrapped(c, bullet_text, left + 10, y, max_width - 10, "Helvetica", 11, 14)
        y -= 4

    c.showPage()
    c.save()


if __name__ == "__main__":
    build()
