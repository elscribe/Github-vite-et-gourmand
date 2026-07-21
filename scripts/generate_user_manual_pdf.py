from pathlib import Path
import re

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import cm
from reportlab.platypus import (
    SimpleDocTemplate,
    Paragraph,
    Preformatted,
    Spacer,
    Table,
    TableStyle,
)


ROOT = Path(__file__).resolve().parents[1]
MARKDOWN_PATH = ROOT / "docs/manual/user-manual.md"
PDF_PATH = ROOT / "docs/manual/user-manual.pdf"


def build_styles():
    styles = getSampleStyleSheet()
    styles.add(
        ParagraphStyle(
            name="DocTitle",
            parent=styles["Title"],
            fontName="Helvetica-Bold",
            fontSize=20,
            leading=24,
            alignment=TA_CENTER,
            spaceAfter=14,
            textColor=colors.HexColor("#1f2933"),
        )
    )
    styles.add(
        ParagraphStyle(
            name="H1Custom",
            parent=styles["Heading1"],
            fontName="Helvetica-Bold",
            fontSize=16,
            leading=20,
            spaceBefore=14,
            spaceAfter=8,
            textColor=colors.HexColor("#27361f"),
        )
    )
    styles.add(
        ParagraphStyle(
            name="H2Custom",
            parent=styles["Heading2"],
            fontName="Helvetica-Bold",
            fontSize=13,
            leading=16,
            spaceBefore=10,
            spaceAfter=6,
            textColor=colors.HexColor("#5d6b2f"),
        )
    )
    styles.add(
        ParagraphStyle(
            name="H3Custom",
            parent=styles["Heading3"],
            fontName="Helvetica-Bold",
            fontSize=11,
            leading=14,
            spaceBefore=8,
            spaceAfter=4,
            textColor=colors.HexColor("#6b4423"),
        )
    )
    styles.add(
        ParagraphStyle(
            name="BodyCustom",
            parent=styles["BodyText"],
            fontName="Helvetica",
            fontSize=9.2,
            leading=12.5,
            spaceAfter=5,
            alignment=TA_LEFT,
        )
    )
    styles.add(
        ParagraphStyle(
            name="BulletCustom",
            parent=styles["BodyText"],
            fontName="Helvetica",
            fontSize=9.2,
            leading=12.5,
            leftIndent=12,
            firstLineIndent=-8,
            spaceAfter=3,
        )
    )
    styles.add(
        ParagraphStyle(
            name="TableCell",
            parent=styles["BodyText"],
            fontName="Helvetica",
            fontSize=7.7,
            leading=9.4,
            spaceAfter=0,
        )
    )
    styles.add(
        ParagraphStyle(
            name="TableHeader",
            parent=styles["BodyText"],
            fontName="Helvetica-Bold",
            fontSize=7.8,
            leading=9.5,
            textColor=colors.white,
            spaceAfter=0,
        )
    )
    styles.add(
        ParagraphStyle(
            name="CodeCustom",
            parent=styles["Code"],
            fontName="Courier",
            fontSize=7.5,
            leading=9.2,
            leftIndent=6,
            rightIndent=6,
            backColor=colors.HexColor("#f4f4f0"),
            borderPadding=5,
        )
    )
    return styles


def escape_inline(value: str) -> str:
    value = value.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")
    value = re.sub(r"`([^`]+)`", r'<font name="Courier">\1</font>', value)
    value = re.sub(r"\*\*([^*]+)\*\*", r"<b>\1</b>", value)
    return value


def is_table_separator(line: str) -> bool:
    cleaned = line.replace("|", "").replace("-", "").replace(":", "").strip()
    return cleaned == ""


def add_table(story, rows, styles):
    if not rows:
        return

    max_cols = max(len(row) for row in rows)
    data = []
    for row_index, row in enumerate(rows):
        row = row + [""] * (max_cols - len(row))
        style = styles["TableHeader"] if row_index == 0 else styles["TableCell"]
        data.append([Paragraph(escape_inline(cell), style) for cell in row])

    available_width = A4[0] - 3.2 * cm
    col_width = available_width / max_cols
    table = Table(data, colWidths=[col_width] * max_cols, repeatRows=1, hAlign="LEFT")
    table.setStyle(
        TableStyle(
            [
                ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#6b4423")),
                ("TEXTCOLOR", (0, 0), (-1, 0), colors.white),
                ("GRID", (0, 0), (-1, -1), 0.25, colors.HexColor("#d6d3c8")),
                ("VALIGN", (0, 0), (-1, -1), "TOP"),
                ("LEFTPADDING", (0, 0), (-1, -1), 4),
                ("RIGHTPADDING", (0, 0), (-1, -1), 4),
                ("TOPPADDING", (0, 0), (-1, -1), 4),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 4),
                (
                    "ROWBACKGROUNDS",
                    (0, 1),
                    (-1, -1),
                    [colors.white, colors.HexColor("#faf8f2")],
                ),
            ]
        )
    )
    story.append(table)
    story.append(Spacer(1, 8))


def markdown_to_story(text: str, styles):
    story = []
    lines = text.splitlines()
    index = 0
    in_code = False
    code_buffer = []
    first_title = True

    while index < len(lines):
        line = lines[index].rstrip()

        if line.strip().startswith("```"):
            if not in_code:
                in_code = True
                code_buffer = []
            else:
                story.append(Preformatted("\n".join(code_buffer), styles["CodeCustom"]))
                story.append(Spacer(1, 4))
                in_code = False
            index += 1
            continue

        if in_code:
            code_buffer.append(line)
            index += 1
            continue

        if not line.strip():
            index += 1
            continue

        if (
            line.strip().startswith("|")
            and index + 1 < len(lines)
            and is_table_separator(lines[index + 1])
        ):
            table_lines = []
            while index < len(lines) and lines[index].strip().startswith("|"):
                table_lines.append(lines[index].strip())
                index += 1
            rows = []
            for row_index, table_line in enumerate(table_lines):
                if row_index == 1:
                    continue
                rows.append([cell.strip() for cell in table_line.strip("|").split("|")])
            add_table(story, rows, styles)
            continue

        if line.startswith("# "):
            content = line[2:].strip()
            if first_title:
                story.append(Paragraph(escape_inline(content), styles["DocTitle"]))
                first_title = False
            else:
                story.append(Paragraph(escape_inline(content), styles["H1Custom"]))
            index += 1
            continue

        if line.startswith("## "):
            story.append(Paragraph(escape_inline(line[3:].strip()), styles["H1Custom"]))
            index += 1
            continue

        if line.startswith("### "):
            story.append(Paragraph(escape_inline(line[4:].strip()), styles["H2Custom"]))
            index += 1
            continue

        if line.startswith("#### "):
            story.append(Paragraph(escape_inline(line[5:].strip()), styles["H3Custom"]))
            index += 1
            continue

        if line.startswith("- "):
            while index < len(lines) and lines[index].startswith("- "):
                item = lines[index][2:].strip()
                story.append(Paragraph("&bull; " + escape_inline(item), styles["BulletCustom"]))
                index += 1
            story.append(Spacer(1, 3))
            continue

        if re.match(r"^\d+\. ", line):
            while index < len(lines) and re.match(r"^\d+\. ", lines[index]):
                item = lines[index].strip()
                story.append(Paragraph(escape_inline(item), styles["BulletCustom"]))
                index += 1
            story.append(Spacer(1, 3))
            continue

        paragraph_lines = [line]
        next_index = index + 1
        while next_index < len(lines):
            next_line = lines[next_index].rstrip()
            if (
                not next_line.strip()
                or next_line.startswith("#")
                or next_line.startswith("- ")
                or re.match(r"^\d+\. ", next_line)
                or next_line.startswith("|")
                or next_line.startswith("```")
            ):
                break
            paragraph_lines.append(next_line)
            next_index += 1

        paragraph = " ".join(part.strip() for part in paragraph_lines)
        story.append(Paragraph(escape_inline(paragraph), styles["BodyCustom"]))
        index = next_index

    return story


def footer(canvas, doc):
    canvas.saveState()
    canvas.setFont("Helvetica", 7.5)
    canvas.setFillColor(colors.HexColor("#77736a"))
    canvas.drawString(1.6 * cm, 1.0 * cm, "Vite & Gourmand - Manuel utilisateur")
    canvas.drawRightString(A4[0] - 1.6 * cm, 1.0 * cm, f"Page {doc.page}")
    canvas.restoreState()


def main():
    styles = build_styles()
    story = markdown_to_story(MARKDOWN_PATH.read_text(encoding="utf-8"), styles)
    PDF_PATH.parent.mkdir(parents=True, exist_ok=True)
    doc = SimpleDocTemplate(
        str(PDF_PATH),
        pagesize=A4,
        rightMargin=1.6 * cm,
        leftMargin=1.6 * cm,
        topMargin=1.6 * cm,
        bottomMargin=1.5 * cm,
    )
    doc.build(story, onFirstPage=footer, onLaterPages=footer)
    print(PDF_PATH)


if __name__ == "__main__":
    main()
