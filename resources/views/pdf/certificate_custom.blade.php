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
            size: A4 {{ $template->orientation ?? 'landscape' }};
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            position: relative;
            background-color: #ffffff;
            color: #0f172a;
        }
        .bg-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .bg-layer img {
            width: 100%;
            height: 100%;
            display: block;
        }
        .default-bg-layer {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            z-index: 1;
            box-sizing: border-box;
        }
        .default-outer-border {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            height: 100%;
            box-sizing: border-box;
            background: #ffffff;
        }
        .content-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
        }
        .item-box {
            position: absolute;
        }
    </style>
</head>
<body>
    @if(!empty($backgroundDataUri))
        <div class="bg-layer">
            <img src="{{ $backgroundDataUri }}" alt="Certificate Background">
        </div>
    @else
        <div class="default-bg-layer">
            <div class="default-outer-border"></div>
        </div>
    @endif

    <div class="content-layer">
        @php
            $cfg = $template->placeholders_config ?? \App\Models\CertificateTemplate::getDefaultPlaceholdersConfig($template->orientation ?? 'landscape');
        @endphp

        {{-- Top-Right Slogan (Over Tanzania Map) --}}
        <div class="item-box" style="top: 6.5%; right: 4.8%; width: 18%; text-align: right;">
            <div style="font-family: 'Times New Roman', 'Times', serif; font-style: italic; font-size: 11.5pt; line-height: 1.22; color: #1e293b;">
                Driving<br>
                Clean Energy<br>
                for a Better<br>
                Tanzania
            </div>
        </div>

        {{-- Left Watermark Slogan (Next to Leaf Artwork) --}}
        <div class="item-box" style="top: 52.5%; left: 5.2%; width: 12%; text-align: left;">
            <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-weight: bold; font-size: 8.5pt; letter-spacing: 1.5px; line-height: 1.4; text-transform: uppercase; color: #7d968b;">
                CLEAN<br>
                DRIVERS<br>
                GREENER<br>
                TOMORROW
            </div>
        </div>

        {{-- Top Center Logo & Association Header --}}
        @if(!empty($cfg['header']) && ($cfg['header']['enabled'] ?? true))
            @php
                $hdrAlign = $cfg['header']['text_align'] ?? 'center';
                $hdrWidth = $cfg['header']['width'] ?? 84;
                $hdrLeft = $hdrAlign === 'center' ? ((100 - $hdrWidth) / 2) : ($cfg['header']['left'] ?? 8.0);
            @endphp
            <div class="item-box" style="
                top: {{ $cfg['header']['top'] ?? 5.2 }}%;
                left: {{ $hdrLeft }}%;
                width: {{ $hdrWidth }}%;
                text-align: {{ $hdrAlign }};
            ">
                @if(!empty($logoDataUri) && ($cfg['header']['show_logo'] ?? true))
                    <div style="margin-bottom: 4px;">
                        <img src="{{ $logoDataUri }}" style="max-height: 76px; max-width: 250px; margin: 0 auto; display: inline-block;" alt="TEVDA Official Logo">
                    </div>
                @endif
                <div style="font-family: 'Times New Roman', 'Times', serif; font-weight: bold; font-size: {{ $cfg['header']['font_size'] ?? 19.5 }}pt; letter-spacing: 1.5px; text-transform: uppercase; color: #0a3d31; line-height: 1.18;">
                    Tanzania Electric Vehicle Drivers Association
                </div>
                <div style="font-family: 'Times New Roman', 'Times', serif; font-weight: bold; font-size: 12.5pt; letter-spacing: 3.5px; text-transform: uppercase; color: #b45309; margin-top: 4px;">
                    SMART DRIVERS &bull; SMART MOBILITY
                </div>
            </div>
        @endif

        {{-- Certificate Title (Centered inside the Dark Green Ribbon Banner) --}}
        @if(!empty($cfg['title']) && ($cfg['title']['enabled'] ?? true))
            @php
                $titleAlign = $cfg['title']['text_align'] ?? 'center';
                $titleWidth = $cfg['title']['width'] ?? 70;
                $titleLeft = $titleAlign === 'center' ? ((100 - $titleWidth) / 2) : ($cfg['title']['left'] ?? 15.0);
            @endphp
            <div class="item-box" style="
                top: {{ $cfg['title']['top'] ?? 32.2 }}%;
                left: {{ $titleLeft }}%;
                width: {{ $titleWidth }}%;
                text-align: {{ $titleAlign }};
            ">
                <div style="
                    font-family: 'Times New Roman', 'Times', 'Georgia', serif;
                    font-weight: bold;
                    font-size: {{ $cfg['title']['font_size'] ?? 27 }}pt;
                    color: {{ $cfg['title']['color'] ?? '#fef08a' }};
                    letter-spacing: 0.5px;
                ">
                    {{ $certificate->title }}
                </div>
            </div>
        @endif

        {{-- Subtitle Label --}}
        <div class="item-box" style="
            top: 42.5%;
            left: 10%;
            width: 80%;
            text-align: center;
        ">
            <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-weight: bold; font-size: 9pt; letter-spacing: 2.5px; text-transform: uppercase; color: #475569;">
                THIS IS OFFICIALLY PRESENTED TO
            </div>
        </div>

        {{-- Recipient Name (Sitting above the gold divider line) --}}
        @if(!empty($cfg['recipient_name']) && ($cfg['recipient_name']['enabled'] ?? true))
            @php
                $nameAlign = $cfg['recipient_name']['text_align'] ?? 'center';
                $nameWidth = $cfg['recipient_name']['width'] ?? 80;
                $nameLeft = $nameAlign === 'center' ? ((100 - $nameWidth) / 2) : ($cfg['recipient_name']['left'] ?? 10.0);
            @endphp
            <div class="item-box" style="
                top: {{ $cfg['recipient_name']['top'] ?? 45.2 }}%;
                left: {{ $nameLeft }}%;
                width: {{ $nameWidth }}%;
                text-align: {{ $nameAlign }};
            ">
                <div style="
                    font-family: 'Times New Roman', 'Times', serif;
                    font-style: italic;
                    font-weight: bold;
                    font-size: {{ $cfg['recipient_name']['font_size'] ?? 32 }}pt;
                    color: {{ $cfg['recipient_name']['color'] ?? '#0f172a' }};
                    letter-spacing: 0.5px;
                ">
                    {{ $certificate->recipient_name }}
                </div>
            </div>
        @endif

        {{-- Body Paragraph & Specialization / Course Details --}}
        @if(!empty($cfg['body_text']) && ($cfg['body_text']['enabled'] ?? true))
            @php
                $bodyAlign = $cfg['body_text']['text_align'] ?? 'center';
                $bodyWidth = $cfg['body_text']['width'] ?? 72;
                $bodyLeft = $bodyAlign === 'center' ? ((100 - $bodyWidth) / 2) : ($cfg['body_text']['left'] ?? 14.0);
            @endphp
            <div class="item-box" style="
                top: {{ $cfg['body_text']['top'] ?? 54.5 }}%;
                left: {{ $bodyLeft }}%;
                width: {{ $bodyWidth }}%;
                text-align: {{ $bodyAlign }};
                font-family: 'Arial', 'Helvetica', sans-serif;
                font-size: {{ $cfg['body_text']['font_size'] ?? 10.5 }}pt;
                font-weight: normal;
                color: {{ $cfg['body_text']['color'] ?? '#334155' }};
                line-height: 1.45;
            ">
                <div>
                    In recognition of meeting all constitutional requirements, driver qualifications, safety standards and curriculum benchmarks as prescribed under TEVDA governance framework.
                </div>
                @if ($certificate->course_name)
                    <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 12pt; margin-top: 5px;">
                        <span style="color: #1e293b;">Specialization / Course:</span> <strong style="font-style: italic; font-weight: bold; color: #064e3b;">{{ $certificate->course_name }}</strong>
                    </div>
                @endif
                @if ($certificate->grade)
                    <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 11pt; color: #065f46; margin-top: 2px;">
                        <span style="font-weight: bold;">Graded Result:</span> <strong>{{ $certificate->grade }}</strong>
                    </div>
                @endif
            </div>
        @endif

        {{-- Bottom Left: Authorized Signatory --}}
        @if(!empty($cfg['signatory']) && ($cfg['signatory']['enabled'] ?? true))
            @php
                $sigAlign = $cfg['signatory']['text_align'] ?? 'center';
                $sigLeft = $cfg['signatory']['left'] ?? 11.0;
                $sigWidth = $cfg['signatory']['width'] ?? 22.0;
            @endphp
            <div class="item-box" style="
                top: {{ $cfg['signatory']['top'] ?? 65.5 }}%;
                left: {{ $sigLeft }}%;
                width: {{ $sigWidth }}%;
                text-align: {{ $sigAlign }};
            ">
                @if(!empty($signatureDataUri))
                    <div style="min-height: 38px; display: inline-block; margin-bottom: 2px;">
                        <img src="{{ $signatureDataUri }}" style="max-height: 44px; max-width: 170px; object-fit: contain; display: block; margin: 0 auto;" alt="Signature">
                    </div>
                @else
                    <div style="font-family: 'Great Vibes', 'Alex Brush', cursive; font-size: 23pt; color: {{ $cfg['signatory']['color'] ?? '#0f172a' }}; line-height: 1.1; margin-bottom: 2px;">
                        {{ $certificate->authorized_person_name ?? \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                    </div>
                @endif
                <div style="border-top: 1.5px solid #475569; width: 170px; margin: 0 auto 3px auto;"></div>
                <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-weight: bold; font-size: 10.5pt; color: #0f172a;">
                    {{ $certificate->authorized_person_name ?? \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                </div>
                <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 9pt; color: #334155; margin-top: 1px;">
                    {{ $certificate->authorized_person_title ?? \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }}
                </div>
                <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 8.5pt; color: #64748b; margin-top: 1px;">
                    TEVDA Executive Council
                </div>
            </div>
        @endif

        {{-- Bottom Center: Gold Seal Medallion (Kept clean without overlay text as requested) --}}

        {{-- Bottom Right: Metadata (Dates & Registry ID) --}}
        @if((!empty($cfg['issue_date']) && ($cfg['issue_date']['enabled'] ?? true)) || (!empty($cfg['certificate_number']) && ($cfg['certificate_number']['enabled'] ?? true)))
            <div class="item-box" style="
                top: 63.0%;
                right: 7.5%;
                width: 23%;
                text-align: right;
                font-family: 'Arial', 'Helvetica', sans-serif;
                font-size: 9pt;
                line-height: 1.45;
            ">
                <table style="width: 100%; border-collapse: collapse; font-family: 'Arial', 'Helvetica', sans-serif; font-size: 9pt;">
                    <tr>
                        <td style="text-align: right; color: #475569; padding-right: 6px; white-space: nowrap;">Issue Date:</td>
                        <td style="text-align: left; font-weight: bold; color: #0f172a; white-space: nowrap;">{{ $certificate->issue_date ? (is_string($certificate->issue_date) ? date('d M Y', strtotime($certificate->issue_date)) : $certificate->issue_date->format('d M Y')) : 'N/A' }}</td>
                    </tr>
                    @if($certificate->expiry_date)
                    <tr>
                        <td style="text-align: right; color: #475569; padding-right: 6px; white-space: nowrap;">Expiry Date:</td>
                        <td style="text-align: left; font-weight: bold; color: #0f172a; white-space: nowrap;">{{ is_string($certificate->expiry_date) ? date('d M Y', strtotime($certificate->expiry_date)) : $certificate->expiry_date->format('d M Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="text-align: right; color: #475569; padding-right: 6px; white-space: nowrap;">Registry ID:</td>
                        <td style="text-align: left; font-weight: normal; color: #0f172a; font-size: 8.5pt; white-space: nowrap;">{{ $certificate->certificate_number }}</td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- Bottom Right: QR Code Card & Verification Action --}}
        @if(!empty($cfg['qr_code']) && ($cfg['qr_code']['enabled'] ?? true))
            @php
                $qrSize = $cfg['qr_code']['size'] ?? 65;
            @endphp
            <div class="item-box" style="
                top: 71.5%;
                right: 10.0%;
                width: 18%;
                text-align: center;
            ">
                <div style="display: inline-block; background-color: #ffffff; padding: 2px; border-radius: 4px; border: 1px solid #cbd5e1;">
                    <img src="{{ $qrCodeUri }}" style="width: {{ $qrSize }}px; height: {{ $qrSize }}px; display: block;" alt="QR Code">
                </div>
                <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 7pt; font-weight: bold; color: #334155; margin-top: 2px;">
                    Scan to Verify
                </div>
            </div>
        @endif

        {{-- Bottom Footer Bar (Across Dark Green Wave) --}}
        <div class="item-box" style="top: 93.0%; left: 19.0%; width: 52.0%; text-align: left;">
            <div style="font-family: 'Arial', 'Helvetica', sans-serif; font-size: 7.2pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: #fae8b2;">
                PEOPLE &nbsp;|&nbsp; INNOVATION &nbsp;|&nbsp; CLEAN TRANSPORT &nbsp;|&nbsp; A GREENER TANZANIA
            </div>
        </div>
        <div class="item-box" style="top: 92.0%; right: 4.0%; width: 22.0%; text-align: right;">
            <div style="font-family: 'Great Vibes', 'Alex Brush', cursive; font-size: 12pt; color: #fae8b2;">
                Electric Mobility for a Bright Future
            </div>
        </div>

    </div>
</body>
</html>

