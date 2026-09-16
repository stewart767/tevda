<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TEVDA Member ID Cards — Printable Sheet</title>
    <style>
        @page {
            margin: 15mm 10mm;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #0f172a;
            font-size: 8px;
        }
        .header-doc {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 1.5px solid #064e3b;
            padding-bottom: 6px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 900;
            color: #064e3b;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .doc-sub {
            font-size: 8px;
            color: #64748b;
        }

        .cards-grid {
            width: 100%;
            margin-bottom: 15px;
        }
        .pair-row {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .cut-guide-container {
            border: 1px dashed #94a3b8;
            padding: 5px;
            background: #f8fafc;
            border-radius: 6px;
        }

        /* Exact CR80 dimensions in points: 242.64pt x 153.07pt (85.6mm x 54mm) */
        .card-preview-box {
            width: 242.64pt;
            height: 153.07pt;
            border-radius: 7px;
            padding: 7px 8px;
            position: relative;
            background: #091a18;
            border: 1px solid #10b981;
            color: #ffffff;
            overflow: hidden;
            display: inline-block;
            vertical-align: top;
        }

        /* Front side inner */
        .header-table {
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            padding-bottom: 3px;
            margin-bottom: 4px;
        }
        .brand-title {
            font-size: 10px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1;
        }
        .brand-sub {
            font-size: 5px;
            color: #34d399;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.1;
        }
        .tier-badge {
            font-size: 5.5px;
            background: #065f46;
            color: #a7f3d0;
            padding: 1.5px 4px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .photo-frame {
            width: 42px;
            height: 52px;
            border-radius: 4px;
            border: 1.2px solid #10b981;
            overflow: hidden;
            background: #1e293b;
            text-align: center;
        }
        .photo-img {
            width: 42px;
            height: 52px;
            object-fit: cover;
            display: block;
        }
        .photo-placeholder {
            line-height: 52px;
            font-size: 6.5px;
            font-weight: bold;
            color: #94a3b8;
        }

        .info-cell {
            padding-left: 6px;
            vertical-align: top;
        }
        .info-label {
            font-size: 4.8px;
            color: #94a3b8;
            text-transform: uppercase;
            line-height: 1;
        }
        .info-name {
            font-size: 8px;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 2px;
            line-height: 1.1;
        }
        .info-number {
            font-size: 7px;
            font-weight: bold;
            font-family: 'Courier', monospace;
            color: #34d399;
            margin-bottom: 2px;
        }
        .info-sub {
            font-size: 5.5px;
            color: #cbd5e1;
            line-height: 1.1;
        }

        .qr-frame {
            width: 36px;
            height: 36px;
            background: #ffffff;
            padding: 1.5px;
            border-radius: 3px;
            display: inline-block;
        }
        .qr-img {
            width: 33px;
            height: 33px;
            display: block;
        }

        .footer-bar {
            margin-top: 3px;
            padding-top: 2px;
            border-top: 1px solid rgba(255,255,255,0.15);
            font-size: 4.8px;
            font-weight: bold;
            color: #fbbf24;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            text-align: center;
        }

        /* Back side inner */
        .magnetic-stripe {
            height: 14px;
            background: #020617;
            margin: -7px -8px 5px -8px;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }
        .stripe-text {
            color: #94a3b8;
            font-size: 4.5px;
            line-height: 14px;
            padding-left: 8px;
            font-family: 'Courier', monospace;
        }
        .terms-box {
            font-size: 4.5px;
            line-height: 1.25;
            color: #94a3b8;
            margin-bottom: 4px;
            text-align: justify;
        }
        .signature-box {
            border-bottom: 1px solid #64748b;
            padding-bottom: 2px;
            margin-bottom: 2px;
            text-align: center;
        }
        .sig-name {
            font-size: 5px;
            font-weight: bold;
            color: #ffffff;
        }
        .sig-title {
            font-size: 4.2px;
            color: #94a3b8;
        }
        .helpline-box {
            background: rgba(255,255,255,0.06);
            border-radius: 3px;
            padding: 3px 4px;
            font-size: 4.5px;
            color: #e2e8f0;
            line-height: 1.2;
        }
        .property-notice {
            font-size: 4px;
            color: #64748b;
            text-align: center;
            margin-top: 3px;
            text-transform: uppercase;
        }

        .guide-label {
            font-size: 6px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

    <div class="header-doc">
        <div class="doc-title">Tanzania EV Drivers Association (TEVDA)</div>
        <div class="doc-sub">Official Member ID Cards • Standard ISO/IEC 7810 ID-1 CR80 Format (85.6mm × 54mm) • Cut Along Guides</div>
    </div>

    @foreach($preparedMembers as $index => $item)
        @php
            $m = $item['member'];
            $qrCodeUri = $item['qrCodeUri'];
            $photoDataUri = $item['photoDataUri'];
        @endphp

        <div class="pair-row">
            <div class="cut-guide-container">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; text-align: center; padding-right: 5px;">
                            <div class="guide-label">FRONT SIDE (CARD {{ $index + 1 }})</div>
                            <!-- FRONT -->
                            <div class="card-preview-box">
                                <table class="header-table" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                                <tr>
                                                    @if(!empty($logoDataUri))
                                                        <td style="vertical-align: middle; padding-right: 4px;">
                                                            <img src="{{ $logoDataUri }}" style="height: 14px; max-width: 30px; object-fit: contain; display: block;" alt="Logo">
                                                        </td>
                                                    @endif
                                                    <td style="vertical-align: middle;">
                                                        <div class="brand-title">TEVDA</div>
                                                        <div class="brand-sub">Tanzania EV Drivers Association</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="text-align: right; vertical-align: middle;">
                                            <span class="tier-badge">{{ $m->category->name }}</span>
                                        </td>
                                    </tr>
                                </table>

                                <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="width: 44px; vertical-align: top;">
                                            <div class="photo-frame">
                                                @if(!empty($photoDataUri))
                                                    <img src="{{ $photoDataUri }}" class="photo-img" alt="{{ $m->full_name }}">
                                                @else
                                                    <div class="photo-placeholder">PHOTO</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="info-cell">
                                            <div class="info-label">Member Name</div>
                                            <div class="info-name">{{ $m->full_name }}</div>

                                            <div class="info-label">Membership Number</div>
                                            <div class="info-number">{{ $m->membership_number }}</div>

                                            <div class="info-label">Region / Valid</div>
                                            <div class="info-sub">{{ $m->region?->name ?? 'Tanzania' }} • <strong style="color:#fbbf24;">{{ $m->expiry_date ? $m->expiry_date->format('m/Y') : 'ACTIVE' }}</strong></div>
                                        </td>
                                        <td style="width: 38px; text-align: right; vertical-align: bottom;">
                                            <div class="qr-frame">
                                                <img src="{{ $qrCodeUri }}" class="qr-img" alt="QR">
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <div class="footer-bar">
                                    SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ
                                </div>
                            </div>
                        </td>

                        <!-- Fold/Cut Line Separator -->
                        <td style="width: 1px; border-left: 1px dashed #cbd5e1;"></td>

                        <td style="width: 50%; vertical-align: top; text-align: center; padding-left: 5px;">
                            <div class="guide-label">BACK SIDE (CARD {{ $index + 1 }})</div>
                            <!-- BACK -->
                            <div class="card-preview-box">
                                <div class="magnetic-stripe">
                                    <div class="stripe-text">CARD ID: {{ $m->card?->card_number ?? ('CARD-' . $m->membership_number) }}</div>
                                </div>

                                <div class="terms-box">
                                    This official identification card certifies that the cardholder is an authorized TEVDA member. 
                                    Card is non-transferable and must be presented on official demand.
                                </div>

                                <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="width: 48%; vertical-align: top; padding-right: 4px;">
                                            <div class="info-label" style="margin-bottom: 2px;">Authorized Signature</div>
                                            <div class="signature-box">
                                                <div style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 8.5px; color: #34d399;">
                                                    Charles Mwansasu
                                                </div>
                                            </div>
                                            <div class="sig-name">Dr. Charles Mwansasu</div>
                                            <div class="sig-title">Founding Chairperson</div>
                                        </td>
                                        <td style="width: 52%; vertical-align: top;">
                                            <div class="helpline-box">
                                                <strong style="color: #34d399; font-size: 4.5px;">TEVDA HELPLINE</strong><br>
                                                Dar es Salaam, Tanzania<br>
                                                +255 700 000 000<br>
                                                info@tevda.or.tz
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <div class="property-notice">
                                    Property of TEVDA. If found, please return to any TEVDA Branch or Police Station.
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if(($index + 1) % 3 == 0 && ($index + 1) < count($preparedMembers))
            <div style="page-break-before: always;"></div>
        @endif
    @endforeach

</body>
</html>
