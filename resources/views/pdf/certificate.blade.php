<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $certificate->certificate_number }}</title>
    <style>
        @font-face {
            font-family: 'Berkshire Swash';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path('fonts/BerkshireSwash-Regular.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'Great Vibes';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path('fonts/GreatVibes-Regular.ttf') }}') format('truetype');
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 0;
            padding: 24px;
            background-color: #ffffff;
            color: #0f172a;
        }
        .outer-frame {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px 36px 20px 36px;
            height: 95%;
            box-sizing: border-box;
            text-align: center;
            position: relative;
            background: #ffffff;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            font-size: 200pt;
            font-weight: 900;
            color: #065f46;
            z-index: 0;
            pointer-events: none;
        }
        .content {
            position: relative;
            z-index: 1;
        }
        .logo-img {
            max-height: 85px;
            max-width: 260px;
            margin: 0 auto;
            display: inline-block;
        }
        .logo-box {
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 32px;
            font-weight: 900;
            color: #065f46;
            letter-spacing: 2px;
        }
        .pill-badge {
            font-family: 'Times New Roman', 'Times', serif;
            font-weight: bold;
            background-color: #ecfdf5;
            border: 1.5px solid #a7f3d0;
            color: #065f46;
            font-size: 16pt;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 5px 28px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 5px;
        }
        .tagline {
            font-family: 'Times New Roman', 'Times', serif;
            font-weight: bold;
            color: #d97706;
            font-size: 11.5pt;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-top: 5px;
            margin-bottom: 12px;
        }
        .cert-title {
            font-family: 'Berkshire Swash', 'Georgia', serif;
            font-size: 36pt;
            color: #0f2744;
            margin: 4px 0 4px 0;
            letter-spacing: 0.5px;
        }
        .cert-subtitle {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: bold;
            font-size: 10.5pt;
            letter-spacing: 3px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .recipient-name {
            font-family: 'Berkshire Swash', 'Georgia', serif;
            font-size: 40pt;
            color: #065f46;
            border-bottom: 4.5px solid #fbbf24;
            display: inline-block;
            padding: 0 40px 6px 40px;
            margin-bottom: 12px;
        }
        .cert-text {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 15.5pt;
            color: #334155;
            line-height: 1.52;
            max-width: 800px;
            margin: 0 auto 5px auto;
        }
        .course-highlight {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 14.5pt;
            color: #1e293b;
            margin-top: 5px;
        }
        .grade-highlight {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 13pt;
            font-weight: bold;
            color: #065f46;
            margin-top: 3px;
        }
        .footer-table {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
        }
        .footer-col {
            vertical-align: bottom;
        }
        .sig-name {
            font-family: 'Great Vibes', 'Alex Brush', 'Dancing Script', cursive;
            font-size: 24pt;
            color: #0f172a;
            border-bottom: 2px solid #94a3b8;
            padding-bottom: 2px;
            display: inline-block;
            min-width: 190px;
            line-height: 1.2;
        }
        .sig-title {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11.5pt;
            font-weight: bold;
            color: #334155;
            margin-top: 4px;
        }
        .sig-dept {
            font-family: 'Times New Roman', 'Times', serif;
            font-weight: bold;
            font-size: 10pt;
            color: #64748b;
            margin-top: 2px;
        }
        .qr-card {
            display: inline-block;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 6px;
        }
        .qr-img {
            width: 120px;
            height: 120px;
            display: block;
        }
        .qr-label {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 9pt;
            font-weight: bold;
            color: #059669;
            margin-top: 3px;
        }
        .meta-line {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11.5pt;
            color: #64748b;
            margin-bottom: 3px;
            line-height: 1.55;
        }
        .meta-label {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: bold;
            color: #334155;
        }
        .meta-val {
            font-family: 'Arial', 'Helvetica', sans-serif;
            color: #0f172a;
        }
        .meta-id {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            font-weight: normal;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="outer-frame">
        <div class="content">
            
            {{-- Logo --}}
            @if(!empty($logoDataUri))
                <div>
                    <img src="{{ $logoDataUri }}" class="logo-img" alt="TEVDA Official Logo">
                </div>
            @else
                <div class="logo-box">TEVDA</div>
            @endif

            {{-- Institution Name: Times New Roman Bold --}}
            <div>
                <span class="pill-badge">Tanzania Electric Vehicle Drivers Association</span>
            </div>

            {{-- Government Headings: Times New Roman Bold --}}
            <div class="tagline">SMART DRIVERS &bull; SMART MOBILITY</div>

            {{-- Main Title: Berkshire Swash --}}
            <div class="cert-title">{{ $certificate->title }}</div>

            {{-- Labels: Arial Bold --}}
            <div class="cert-subtitle">THIS IS OFFICIALLY PRESENTED TO</div>

            {{-- Recipient Name: Berkshire Swash --}}
            <div>
                <span class="recipient-name">{{ $certificate->recipient_name }}</span>
            </div>

            {{-- Body/Details: Arial --}}
            <div class="cert-text">
                In recognition of meeting all constitutional requirements, driver qualifications, safety standards and curriculum benchmarks as prescribed under TEVDA governance framework.
            </div>

            @if($certificate->course_name)
                <div class="course-highlight">
                    <span style="font-weight: bold;">Specialization / Course:</span> <strong>{{ $certificate->course_name }}</strong>
                </div>
            @endif

            @if($certificate->grade)
                <div class="grade-highlight">
                    <span style="font-weight: bold;">Graded Result:</span> <strong>{{ $certificate->grade }}</strong>
                </div>
            @endif

            {{-- 3-Column Footer Layout: Signatures (Handwritten script), QR Code, Dates & Cert Number (Arial) --}}
            <table class="footer-table">
                <tr>
                    {{-- Left: Authorized Signatory --}}
                    <td class="footer-col" style="width: 33%; text-align: left;">
                        <div class="sig-name">{{ $certificate->authorized_person_name ?? 'Dr. Charles Mwansasu' }}</div>
                        <div class="sig-title">{{ $certificate->authorized_person_title ?? 'Founding Chairperson' }}</div>
                        <div class="sig-dept">TEVDA Executive Council</div>
                    </td>

                    {{-- Center: Vector QR Code Card & Verification Action --}}
                    <td class="footer-col" style="width: 34%; text-align: center;">
                        <div class="qr-card">
                            <img src="{{ $qrCodeUri }}" class="qr-img" alt="Verification QR Code">
                        </div>
                        <div class="qr-label">Scan or Click to Verify</div>
                    </td>

                    {{-- Right: Issue Date, Expiry Date & Registry ID --}}
                    <td class="footer-col" style="width: 33%; text-align: right;">
                        <div class="meta-line">
                            <span class="meta-label">Issue Date:</span> <strong class="meta-val">{{ $certificate->issue_date ? (is_string($certificate->issue_date) ? date('d M Y', strtotime($certificate->issue_date)) : $certificate->issue_date->format('d M Y')) : 'N/A' }}</strong>
                        </div>
                        @if($certificate->expiry_date)
                            <div class="meta-line">
                                <span class="meta-label">Expiry Date:</span> <strong class="meta-val">{{ is_string($certificate->expiry_date) ? date('d M Y', strtotime($certificate->expiry_date)) : $certificate->expiry_date->format('d M Y') }}</strong>
                            </div>
                        @endif
                        <div class="meta-line">
                            <span class="meta-label">Registry ID:</span> <span class="meta-id">{{ $certificate->certificate_number }}</span>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
