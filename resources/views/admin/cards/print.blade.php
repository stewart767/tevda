<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print ID Card — {{ $member->membership_number }}</title>
    <style>
        @page {
            size: 85.6mm 53.98mm;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f1f5f9;
        }
        .no-print {
            padding: 12px 20px;
            background: #0f172a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
        }
        .btn {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-secondary {
            background: #334155;
            margin-right: 8px;
        }
        .print-canvas {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 20px;
        }
        .card-frame {
            width: 85.6mm;
            height: 53.98mm;
            border-radius: 4mm;
            overflow: hidden;
            background: #091a18;
            color: #ffffff;
            position: relative;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            border: 1px solid #10b981;
            padding: 3mm 4mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Front specific */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 0.5px solid rgba(255,255,255,0.2);
            padding-bottom: 1.5mm;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 2mm;
        }
        .logo-img {
            height: 5.5mm;
            max-width: 12mm;
            object-fit: contain;
        }
        .brand-title {
            font-size: 3.5mm;
            font-weight: 900;
            line-height: 1;
            letter-spacing: 0.2mm;
        }
        .brand-sub {
            font-size: 1.8mm;
            color: #34d399;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .tier-badge {
            font-size: 1.9mm;
            background: #065f46;
            color: #a7f3d0;
            padding: 0.6mm 1.5mm;
            border-radius: 1mm;
            font-weight: bold;
            text-transform: uppercase;
        }

        .card-body {
            display: flex;
            align-items: center;
            gap: 2.5mm;
            margin: auto 0;
        }
        .photo-box {
            width: 15mm;
            height: 19mm;
            border-radius: 1.5mm;
            border: 0.6mm solid #10b981;
            overflow: hidden;
            background: #1e293b;
            flex-shrink: 0;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-col {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.6mm;
        }
        .info-lbl {
            font-size: 1.6mm;
            color: #94a3b8;
            text-transform: uppercase;
            line-height: 1;
        }
        .info-name {
            font-size: 2.8mm;
            font-weight: 900;
            line-height: 1.1;
        }
        .info-num {
            font-size: 2.4mm;
            font-family: monospace;
            font-weight: bold;
            color: #34d399;
            line-height: 1;
        }
        .info-sub {
            font-size: 2mm;
            color: #e2e8f0;
        }
        .qr-box {
            width: 13mm;
            height: 13mm;
            background: #ffffff;
            padding: 0.5mm;
            border-radius: 1mm;
            flex-shrink: 0;
        }
        .qr-img {
            width: 100%;
            height: 100%;
        }

        .card-footer {
            border-top: 0.5px solid rgba(255,255,255,0.2);
            padding-top: 1mm;
            font-size: 1.7mm;
            font-weight: bold;
            color: #fbbf24;
            text-align: center;
            letter-spacing: 0.2mm;
            text-transform: uppercase;
        }

        /* Back specific */
        .magnetic-bar {
            height: 5mm;
            background: #020617;
            margin: -3mm -4mm 2mm -4mm;
            display: flex;
            align-items: center;
            padding: 0 3mm;
            font-family: monospace;
            font-size: 1.6mm;
            color: #94a3b8;
        }
        .back-terms {
            font-size: 1.6mm;
            line-height: 1.3;
            color: #94a3b8;
            text-align: justify;
            margin-bottom: 2mm;
        }
        .back-grid {
            display: flex;
            justify-content: space-between;
            gap: 2mm;
        }
        .sig-col {
            width: 48%;
        }
        .sig-box {
            border-bottom: 0.4mm solid #64748b;
            padding-bottom: 0.5mm;
            margin-bottom: 0.5mm;
            font-family: 'Brush Script MT', cursive, sans-serif;
            font-size: 3mm;
            color: #34d399;
        }
        .sig-name {
            font-size: 1.8mm;
            font-weight: bold;
        }
        .sig-title {
            font-size: 1.5mm;
            color: #94a3b8;
        }
        .contact-box {
            width: 50%;
            background: rgba(255,255,255,0.06);
            border-radius: 1mm;
            padding: 1mm 1.5mm;
            font-size: 1.6mm;
            line-height: 1.25;
            color: #e2e8f0;
        }
        .return-notice {
            font-size: 1.4mm;
            color: #64748b;
            text-align: center;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: none;
            }
            .print-canvas {
                padding: 0;
                gap: 0;
            }
            .card-frame {
                box-shadow: none;
                page-break-after: always;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <div>
            <strong>TEVDA ID Card Print Preview</strong> — {{ $member->full_name }} ({{ $member->membership_number }})
        </div>
        <div>
            <a href="{{ route('admin.cards.show', $card->id) }}" class="btn btn-secondary">&larr; Back to Details</a>
            <button onclick="window.print()" class="btn">Print Plastic ID Card</button>
        </div>
    </div>

    <div class="print-canvas">
        <!-- FRONT -->
        <div class="card-frame">
            <div class="card-header">
                <div class="brand-box">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" class="logo-img" alt="Logo">
                    @else
                        <div style="font-weight: 900; font-size: 3.5mm; color: #10b981;">TEV</div>
                    @endif
                    <div>
                        <div class="brand-title">TEVDA</div>
                        <div class="brand-sub">Tanzania EV Drivers Association</div>
                    </div>
                </div>
                <div class="tier-badge">{{ $member->category->name }}</div>
            </div>

            <div class="card-body">
                <div class="photo-box">
                    @if($member->passport_photo_path)
                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" class="photo-img" alt="{{ $member->full_name }}">
                    @else
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2mm; color: #94a3b8; font-weight: bold;">PHOTO</div>
                    @endif
                </div>

                <div class="info-col">
                    <span class="info-lbl">Member Name</span>
                    <span class="info-name">{{ $member->full_name }}</span>

                    <span class="info-lbl">Membership Number</span>
                    <span class="info-num">{{ $member->membership_number }}</span>

                    <span class="info-lbl">Region / Valid Thru</span>
                    <span class="info-sub">{{ $member->region?->name ?? 'Tanzania' }} • <strong style="color: #fbbf24;">{{ $card->expiry_date ? $card->expiry_date->format('m/Y') : 'ACTIVE' }}</strong></span>
                </div>

                <div class="qr-box">
                    <img src="{{ $qrCodeUri }}" class="qr-img" alt="QR">
                </div>
            </div>

            <div class="card-footer">
                SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
            </div>
        </div>

        <!-- BACK -->
        <div class="card-frame">
            <div class="magnetic-bar">
                CARD ID: {{ $card->card_number }}
            </div>

            <div class="back-terms">
                This official identification card certifies that the cardholder is an authorized member of TEVDA. Non-transferable and must be presented upon official request.
            </div>

            <div class="back-grid">
                <div class="sig-col">
                    <span class="info-lbl" style="margin-bottom: 0.5mm;">Authorized Signature</span>
                    <div class="sig-box">Charles Mwansasu</div>
                    <div class="sig-name">Dr. Charles Mwansasu</div>
                    <div class="sig-title">Founding Chairperson • TEVDA</div>
                </div>

                <div class="contact-box">
                    <strong style="color: #34d399; font-size: 1.7mm;">TEVDA HQ & HELPLINE</strong><br>
                    Dar es Salaam, Tanzania<br>
                    Helpline: +255 700 000 000<br>
                    Email: info@tevda.or.tz
                </div>
            </div>

            <div class="return-notice">
                Property of TEVDA. If found, return to nearest TEVDA branch.
            </div>
        </div>
    </div>

</body>
</html>
