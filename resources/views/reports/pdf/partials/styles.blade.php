<style>
    @page { margin: 0; }
    * { box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10pt;
        color: #1a2648;
        margin: 0;
        padding: 0;
        line-height: 1.45;
    }
    .page {
        padding: 0 0 18mm 0;
    }
    .header {
        background-color: #1a2648;
        color: #fff;
        padding: 14mm 12mm 12mm;
        border-bottom: 4px solid {{ $accent ?? '#3b82f6' }};
    }
    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { vertical-align: middle; border: none; padding: 0; }
    .logo-cell { width: 52px; }
    .logo-cell img { width: 48px; height: 48px; border-radius: 8px; }
    .header-title {
        font-size: 16pt;
        font-weight: bold;
        margin: 0 0 2px;
        letter-spacing: 0.02em;
    }
    .header-sub {
        font-size: 8.5pt;
        opacity: 0.88;
        margin: 0;
    }
    .header-meta {
        text-align: right;
        font-size: 8pt;
        opacity: 0.85;
    }
    .body-pad { padding: 8mm 12mm 0; }
    .hero-table { width: 100%; border-collapse: collapse; margin-bottom: 6mm; }
    .hero-table td { vertical-align: top; border: none; padding: 0; }
    .photo-box {
        width: 62mm;
        border: 1px solid #d7dfed;
        border-radius: 6px;
        overflow: hidden;
        background: #f4f7fb;
    }
    .photo-box img {
        width: 62mm;
        height: 72mm;
        object-fit: cover;
        display: block;
    }
    .hero-info { padding-left: 6mm; }
    .badge-placa {
        display: inline-block;
        background: {{ $accent ?? '#3b82f6' }};
        color: #fff;
        font-size: 14pt;
        font-weight: bold;
        padding: 4px 12px;
        border-radius: 4px;
        margin-bottom: 4px;
    }
    .hero-name {
        font-size: 13pt;
        font-weight: bold;
        margin: 0 0 6px;
        color: #1a2648;
    }
    .badge-status {
        display: inline-block;
        font-size: 8pt;
        font-weight: bold;
        padding: 3px 10px;
        border-radius: 20px;
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .section-title {
        font-size: 9pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: {{ $accent ?? '#3b82f6' }};
        border-bottom: 2px solid {{ $accent ?? '#3b82f6' }};
        padding-bottom: 3px;
        margin: 0 0 4mm;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6mm;
    }
    .data-table tr:nth-child(even) td { background: #f8faff; }
    .data-table td {
        padding: 5px 8px;
        border: 1px solid #e8eef8;
        font-size: 9pt;
    }
    .data-table td.label {
        width: 38%;
        font-weight: bold;
        color: #60708d;
        background: #f3f6fd;
    }
    .notes-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 5px;
        padding: 5mm;
        font-size: 8.5pt;
        color: #78350f;
        margin-bottom: 6mm;
    }
    .pedigree-table { width: 100%; border-collapse: collapse; }
    .pedigree-table > tbody > tr > td {
        width: 50%;
        vertical-align: top;
        padding: 0 3mm;
        border: none;
    }
    .parent-card {
        border: 1px solid #d7dfed;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
    }
    .parent-head {
        background: #f3f6fd;
        padding: 3mm 4mm;
        font-size: 8pt;
        font-weight: bold;
        color: #60708d;
        text-transform: uppercase;
        border-bottom: 1px solid #e8eef8;
    }
    .parent-photo img {
        width: 100%;
        height: 42mm;
        object-fit: cover;
        display: block;
    }
    .parent-body { padding: 3mm 4mm; font-size: 8pt; }
    .parent-placa { font-size: 10pt; font-weight: bold; color: #1a2648; margin-bottom: 2px; }
    .parent-row { margin-bottom: 2px; }
    .parent-row strong { color: #60708d; }
    .empty-pedigree {
        text-align: center;
        padding: 8mm;
        color: #94a3b8;
        font-size: 8.5pt;
        border: 1px dashed #d7dfed;
        border-radius: 6px;
    }
    .footer {
        margin-top: 8mm;
        padding: 4mm 0 0;
        font-size: 7.5pt;
        color: #94a3b8;
        border-top: 1px solid #e8eef8;
    }
    .footer-table { width: 100%; }
    .footer-table td { border: none; padding: 0; }
    .page-break { page-break-after: always; }
    .cover {
        text-align: center;
        padding: 30mm 15mm 10mm;
    }
    .cover h1 { font-size: 22pt; color: #1a2648; margin-bottom: 4mm; }
    .cover p { color: #60708d; font-size: 11pt; }
    .cover-stat {
        display: inline-block;
        margin-top: 8mm;
        padding: 4mm 10mm;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 12pt;
        font-weight: bold;
        color: #1d4ed8;
    }
</style>
