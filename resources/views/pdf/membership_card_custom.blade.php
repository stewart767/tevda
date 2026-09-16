<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TEVDA Member ID Card — {{ $member->membership_number }}</title>
    <style>
        @page {
            margin: 0;
            size: 242.64pt 153.07pt; /* CR80 Standard ISO/IEC 7810 ID-1 */
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }
        html, body {
            width: 242.64pt;
            height: 153.07pt;
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            background: #022c22;
            color: #ffffff;
            font-size: 7.5px;
        }

        .card-page {
            width: 242.64pt;
            height: 153.07pt;
            position: relative;
            page-break-after: always;
            overflow: hidden;
            background: #ffffff;
        }
        .card-page.last-page {
            page-break-after: avoid;
        }

        .bg-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 242.64pt;
            height: 153.07pt;
            z-index: 1;
        }
        .bg-layer img {
            width: 242.64pt;
            height: 153.07pt;
            display: block;
        }

        .content-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 242.64pt;
            height: 153.07pt;
            z-index: 10;
        }

        .item-box {
            position: absolute;
        }
    </style>
</head>
<body>
    @php
        $cfg = $template->placeholders_config ?? \App\Models\IdCardTemplate::getDefaultPlaceholdersConfig();
        $front = $cfg['front'] ?? [];
        $back = $cfg['back'] ?? [];
    @endphp

    <!-- ==================== FRONT SIDE (PAGE 1) ==================== -->
    <div class="card-page {{ empty($showBack) ? 'last-page' : '' }}">
        @if(!empty($frontBackgroundDataUri))
            <div class="bg-layer">
                <img src="{{ $frontBackgroundDataUri }}" alt="Front Background">
            </div>
        @else
            <div class="bg-layer" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #0f172a 100%);">
                <div style="position: absolute; top: 4pt; left: 4pt; right: 4pt; bottom: 4pt; border: 1.2pt solid #10b981; border-radius: 6pt;"></div>
            </div>
        @endif

        <div class="content-layer">
            <!-- Header / Logo -->
            @if(!empty($front['header']) && ($front['header']['enabled'] ?? true))
                @php
                    $hdrTop = $front['header']['top'] ?? 5.0;
                    $hdrLeft = $front['header']['left'] ?? 5.0;
                    $hdrColor = $front['header']['color'] ?? '#ffffff';
                    $hdrFontSize = $front['header']['font_size'] ?? 10.0;
                    $showLogo = $front['header']['show_logo'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $hdrTop }}%; left: {{ $hdrLeft }}%;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            @if(!empty($logoDataUri) && $showLogo)
                                <td style="vertical-align: middle; padding-right: 4pt;">
                                    <img src="{{ $logoDataUri }}" style="height: {{ $front['header']['logo_height'] ?? 16 }}pt; max-width: 32pt; display: block;" alt="Logo">
                                </td>
                            @endif
                            @if(!empty($front['header']['title']))
                                <td style="vertical-align: middle;">
                                    <div style="font-size: {{ $hdrFontSize }}pt; font-weight: 900; color: {{ $hdrColor }}; line-height: 1; letter-spacing: 0.5pt;">
                                        {{ $front['header']['title'] }}
                                    </div>
                                    @if(!empty($front['header']['subtitle']))
                                        <div style="font-size: 4.8pt; font-weight: bold; color: #34d399; text-transform: uppercase; letter-spacing: 0.3pt; line-height: 1.1;">
                                            {{ $front['header']['subtitle'] }}
                                        </div>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Category Tier Badge -->
            @if(!empty($front['category']) && ($front['category']['enabled'] ?? true))
                @php
                    $catTop = $front['category']['top'] ?? 5.5;
                    $catLeft = $front['category']['left'] ?? 68.0;
                    $catColor = $front['category']['color'] ?? '#a7f3d0';
                    $catBg = $front['category']['bg_color'] ?? '#065f46';
                    $catBorder = $front['category']['border_color'] ?? '#10b981';
                    $catFontSize = $front['category']['font_size'] ?? 6.5;
                    $hasBg = $front['category']['has_bg'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $catTop }}%; left: {{ $catLeft }}%;">
                    <span style="display: inline-block; font-size: {{ $catFontSize }}pt; font-weight: 900; color: {{ $catColor }}; @if($hasBg) background: {{ $catBg }}; border: 0.5pt solid {{ $catBorder }}; padding: 1.5pt 4.5pt; border-radius: 2.5pt; @endif text-transform: uppercase; letter-spacing: 0.4pt; white-space: nowrap;">
                        {{ $member->category->name }}
                    </span>
                </div>
            @endif

            <!-- Passport Photo -->
            @if(!empty($front['photo']) && ($front['photo']['enabled'] ?? true))
                @php
                    $pTop = $front['photo']['top'] ?? 22.0;
                    $pLeft = $front['photo']['left'] ?? 5.5;
                    $pWidth = $front['photo']['width'] ?? 21.0;
                    $pHeight = $front['photo']['height'] ?? 42.0;
                    $pBorderColor = $front['photo']['border_color'] ?? '#10b981';
                    $pBorderRadius = $front['photo']['border_radius'] ?? 4.0;
                    $pBorderWidth = $front['photo']['border_width'] ?? 1.5;
                @endphp
                <div class="item-box" style="top: {{ $pTop }}%; left: {{ $pLeft }}%; width: {{ $pWidth }}%; height: {{ $pHeight }}%;">
                    <div style="width: 100%; height: 100%; border-radius: {{ $pBorderRadius }}pt; border: {{ $pBorderWidth }}pt solid {{ $pBorderColor }}; overflow: hidden; background: #0f172a;">
                        @if(!empty($photoDataUri))
                            <img src="{{ $photoDataUri }}" style="width: 100%; height: 100%; display: block;" alt="{{ $member->full_name }}">
                        @else
                            <div style="width: 100%; height: 100%; text-align: center; line-height: 52pt; font-size: 6pt; font-weight: bold; color: #94a3b8; background: #1e293b;">
                                PHOTO
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Full Name -->
            @if(!empty($front['full_name']) && ($front['full_name']['enabled'] ?? true))
                @php
                    $fnTop = $front['full_name']['top'] ?? 22.0;
                    $fnLeft = $front['full_name']['left'] ?? 29.5;
                    $fnWidth = $front['full_name']['width'] ?? 45.0;
                    $fnColor = $front['full_name']['color'] ?? '#ffffff';
                    $fnFontSize = $front['full_name']['font_size'] ?? 9.0;
                    $fnAlign = $front['full_name']['text_align'] ?? 'left';
                    $showLabel = $front['full_name']['show_label'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $fnTop }}%; left: {{ $fnLeft }}%; width: {{ $fnWidth }}%; text-align: {{ $fnAlign }};">
                    @if($showLabel)
                        <div style="font-size: 4.2pt; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.3pt; line-height: 1; margin-bottom: 0.5pt;">
                            {{ $front['full_name']['label_text'] ?? 'Member Name / Jina' }}
                        </div>
                    @endif
                    <div style="font-size: {{ $fnFontSize }}pt; font-weight: 900; color: {{ $fnColor }}; line-height: 1.1; text-transform: uppercase; letter-spacing: 0.2pt;">
                        {{ $member->full_name }}
                    </div>
                </div>
            @endif

            <!-- Membership Number -->
            @if(!empty($front['membership_number']) && ($front['membership_number']['enabled'] ?? true))
                @php
                    $mnTop = $front['membership_number']['top'] ?? 38.0;
                    $mnLeft = $front['membership_number']['left'] ?? 29.5;
                    $mnColor = $front['membership_number']['color'] ?? '#f59e0b';
                    $mnBg = $front['membership_number']['bg_color'] ?? '#022c22';
                    $mnBorder = $front['membership_number']['border_color'] ?? '#f59e0b';
                    $mnFontSize = $front['membership_number']['font_size'] ?? 7.8;
                    $mnHasBg = $front['membership_number']['has_bg'] ?? true;
                    $mnShowLabel = $front['membership_number']['show_label'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $mnTop }}%; left: {{ $mnLeft }}%;">
                    @if($mnShowLabel)
                        <div style="font-size: 4.2pt; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.3pt; line-height: 1; margin-bottom: 0.5pt;">
                            {{ $front['membership_number']['label_text'] ?? 'Member ID / Namba' }}
                        </div>
                    @endif
                    <div style="display: inline-block; font-family: 'Courier', monospace; font-size: {{ $mnFontSize }}pt; font-weight: bold; color: {{ $mnColor }}; @if($mnHasBg) background: {{ $mnBg }}; border: 0.5pt solid {{ $mnBorder }}; padding: 1pt 3pt; border-radius: 2pt; @endif">
                        {{ $member->membership_number }}
                    </div>
                </div>
            @endif

            <!-- Region & Territory -->
            @if(!empty($front['region']) && ($front['region']['enabled'] ?? true))
                @php
                    $rgTop = $front['region']['top'] ?? 53.0;
                    $rgLeft = $front['region']['left'] ?? 29.5;
                    $rgWidth = $front['region']['width'] ?? 45.0;
                    $rgColor = $front['region']['color'] ?? '#cbd5e1';
                    $rgFontSize = $front['region']['font_size'] ?? 6.5;
                    $rgShowLabel = $front['region']['show_label'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $rgTop }}%; left: {{ $rgLeft }}%; width: {{ $rgWidth }}%;">
                    @if($rgShowLabel)
                        <div style="font-size: 4.2pt; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.3pt; line-height: 1; margin-bottom: 0.5pt;">
                            {{ $front['region']['label_text'] ?? 'Region & Territory' }}
                        </div>
                    @endif
                    <div style="font-size: {{ $rgFontSize }}pt; color: {{ $rgColor }}; line-height: 1.2;">
                        <strong>{{ $member->region?->name ?? 'Tanzania' }}</strong>
                        @if($member->district) • {{ $member->district->name }} @endif
                    </div>
                </div>
            @endif

            <!-- Validity -->
            @if(!empty($front['expiry_date']) && ($front['expiry_date']['enabled'] ?? true))
                @php
                    $expTop = $front['expiry_date']['top'] ?? 66.5;
                    $expLeft = $front['expiry_date']['left'] ?? 29.5;
                    $expColor = $front['expiry_date']['color'] ?? '#f59e0b';
                    $expFontSize = $front['expiry_date']['font_size'] ?? 6.5;
                    $expShowLabel = $front['expiry_date']['show_label'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $expTop }}%; left: {{ $expLeft }}%;">
                    @if($expShowLabel)
                        <div style="font-size: 4.2pt; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.3pt; line-height: 1; margin-bottom: 0.5pt;">
                            {{ $front['expiry_date']['label_text'] ?? 'Validity / Hali' }}
                        </div>
                    @endif
                    <div style="font-size: {{ $expFontSize }}pt; font-weight: bold; color: {{ $expColor }};">
                        {{ $member->expiry_date ? 'EXP: ' . $member->expiry_date->format('m/Y') : 'ACTIVE' }}
                        <span style="color: #34d399; margin-left: 2pt;">• VERIFIED</span>
                    </div>
                </div>
            @endif

            <!-- Verification QR Code -->
            @if(!empty($front['qr_code']) && ($front['qr_code']['enabled'] ?? true))
                @php
                    $qrTop = $front['qr_code']['top'] ?? 22.0;
                    $qrLeft = $front['qr_code']['left'] ?? 77.0;
                    $qrSize = $front['qr_code']['size'] ?? 40.0;
                    $qrBg = $front['qr_code']['bg_color'] ?? '#ffffff';
                    $qrHasBg = $front['qr_code']['has_bg'] ?? true;
                    $qrShowLabel = $front['qr_code']['show_label'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $qrTop }}%; left: {{ $qrLeft }}%; text-align: center;">
                    <div style="display: inline-block; @if($qrHasBg) background: {{ $qrBg }}; padding: 2pt; border-radius: 3pt; border: 0.5pt solid #cbd5e1; @endif">
                        <img src="{{ $qrCodeUri }}" style="width: {{ $qrSize }}pt; height: {{ $qrSize }}pt; display: block;" alt="QR Code">
                    </div>
                    @if($qrShowLabel)
                        <div style="font-size: 4.2pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.3pt; margin-top: 1.5pt;">
                            {{ $front['qr_code']['label_text'] ?? 'Scan to Verify' }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Bottom Motto / Footer -->
            @if(!empty($front['motto']) && ($front['motto']['enabled'] ?? true))
                @php
                    $mtTop = $front['motto']['top'] ?? 88.5;
                    $mtLeft = $front['motto']['left'] ?? 50.0;
                    $mtWidth = $front['motto']['width'] ?? 90.0;
                    $mtColor = $front['motto']['color'] ?? '#f59e0b';
                    $mtFontSize = $front['motto']['font_size'] ?? 5.2;
                    $mtAlign = $front['motto']['text_align'] ?? 'center';
                    $mtActualLeft = $mtAlign === 'center' ? ((100 - $mtWidth) / 2) : $mtLeft;
                @endphp
                <div class="item-box" style="top: {{ $mtTop }}%; left: {{ $mtActualLeft }}%; width: {{ $mtWidth }}%; text-align: {{ $mtAlign }};">
                    <div style="font-size: {{ $mtFontSize }}pt; font-weight: bold; color: {{ $mtColor }}; letter-spacing: 0.5pt; text-transform: uppercase;">
                        {{ $front['motto']['text'] ?? ($member->card?->card_data['motto'] ?? 'SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ==================== BACK SIDE (PAGE 2) ==================== -->
    @if(!empty($showBack))
    <div class="card-page last-page">
        @if(!empty($backBackgroundDataUri))
            <div class="bg-layer">
                <img src="{{ $backBackgroundDataUri }}" alt="Back Background">
            </div>
        @else
            <div class="bg-layer" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #0f172a 100%);">
                <div style="position: absolute; top: 4pt; left: 4pt; right: 4pt; bottom: 4pt; border: 1.2pt solid #10b981; border-radius: 6pt;"></div>
            </div>
        @endif

        <div class="content-layer">
            <!-- Magnetic Stripe -->
            @if(!empty($back['magnetic_stripe']) && ($back['magnetic_stripe']['enabled'] ?? true))
                @php
                    $msTop = $back['magnetic_stripe']['top'] ?? 0.0;
                    $msHeight = $back['magnetic_stripe']['height'] ?? 14.0;
                    $msBg = $back['magnetic_stripe']['bg_color'] ?? '#020617';
                    $msColor = $back['magnetic_stripe']['color'] ?? '#cbd5e1';
                    $msFontSize = $back['magnetic_stripe']['font_size'] ?? 5.5;
                    $showCardNum = $back['magnetic_stripe']['show_card_number'] ?? true;
                @endphp
                <div class="item-box" style="top: {{ $msTop }}%; left: 0; width: 100%; height: {{ $msHeight }}pt; background: {{ $msBg }};">
                    <table style="width: 100%; height: 100%; padding: 0 6pt;" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align: middle; font-family: 'Courier', monospace; font-size: {{ $msFontSize }}pt; font-weight: bold; color: {{ $msColor }}; letter-spacing: 0.5pt;">
                                @if($showCardNum)
                                    CARD ID: {{ $member->card?->card_number ?? ('CARD-' . $member->membership_number) }}
                                @endif
                            </td>
                            <td style="vertical-align: middle; text-align: right; font-size: 4.8pt; font-weight: bold; color: #10b981; text-transform: uppercase;">
                                OFFICIAL SMART BADGE
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Terms & Conditions -->
            @if(!empty($back['terms']) && ($back['terms']['enabled'] ?? true))
                @php
                    $tmTop = $back['terms']['top'] ?? 20.0;
                    $tmLeft = $back['terms']['left'] ?? 6.0;
                    $tmWidth = $back['terms']['width'] ?? 88.0;
                    $tmColor = $back['terms']['color'] ?? '#94a3b8';
                    $tmFontSize = $back['terms']['font_size'] ?? 4.5;
                @endphp
                <div class="item-box" style="top: {{ $tmTop }}%; left: {{ $tmLeft }}%; width: {{ $tmWidth }}%;">
                    <div style="font-size: {{ $tmFontSize }}pt; line-height: 1.25; color: {{ $tmColor }}; text-align: left; word-wrap: break-word;">
                        {{ $back['terms']['text'] ?? 'This smart ID card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.' }}
                    </div>
                </div>
            @endif

            <!-- Signatory on Left -->
            @if(!empty($back['signatory']) && ($back['signatory']['enabled'] ?? true))
                @php
                    $sgTop = $back['signatory']['top'] ?? 48.0;
                    $sgLeft = $back['signatory']['left'] ?? 6.0;
                    $sgWidth = $back['signatory']['width'] ?? 42.0;
                    $sgColor = $back['signatory']['color'] ?? '#ffffff';
                    $sgFontSize = $back['signatory']['font_size'] ?? 5.5;
                @endphp
                <div class="item-box" style="top: {{ $sgTop }}%; left: {{ $sgLeft }}%; width: {{ $sgWidth }}%; text-align: center;">
                    <div style="font-size: 4.2pt; text-transform: uppercase; color: #94a3b8; margin-bottom: 2pt;">Authorized Signatory</div>
                    <div style="border-bottom: 0.6pt solid #475569; padding-bottom: 1pt; margin-bottom: 1.5pt;">
                        <span style="font-family: 'DejaVu Sans', cursive, sans-serif; font-style: italic; font-size: 8.5pt; font-weight: bold; color: #34d399;">
                            {{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}
                        </span>
                    </div>
                    <div style="font-size: {{ $sgFontSize }}pt; font-weight: bold; color: {{ $sgColor }}; line-height: 1.1;">
                        {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                    </div>
                    <div style="font-size: 4.2pt; color: #94a3b8; line-height: 1;">
                        {{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • {{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}
                    </div>
                </div>
            @endif

            <!-- Helpline on Right -->
            @if(!empty($back['helpline']) && ($back['helpline']['enabled'] ?? true))
                @php
                    $hlTop = $back['helpline']['top'] ?? 48.0;
                    $hlLeft = $back['helpline']['left'] ?? 52.0;
                    $hlWidth = $back['helpline']['width'] ?? 42.0;
                    $hlColor = $back['helpline']['color'] ?? '#cbd5e1';
                    $hlFontSize = $back['helpline']['font_size'] ?? 4.5;
                @endphp
                <div class="item-box" style="top: {{ $hlTop }}%; left: {{ $hlLeft }}%; width: {{ $hlWidth }}%;">
                    <div style="background: rgba(0,0,0,0.3); border-radius: 3pt; padding: 3pt 4pt; border: 0.5pt solid rgba(255,255,255,0.08); font-size: {{ $hlFontSize }}pt; line-height: 1.2; color: {{ $hlColor }};">
                        <strong style="color: #34d399; font-size: 5pt; display: block; margin-bottom: 1pt;">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                        {{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}<br>
                        <strong>Helpline:</strong> {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}<br>
                        <strong>Support:</strong> {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}
                    </div>
                </div>
            @endif

            <!-- Barcode -->
            @if(!empty($back['barcode']) && ($back['barcode']['enabled'] ?? true))
                @php
                    $bcTop = $back['barcode']['top'] ?? 77.0;
                    $bcLeft = $back['barcode']['left'] ?? 50.0;
                    $bcWidth = $back['barcode']['width'] ?? 60.0;
                    $bcColor = $back['barcode']['color'] ?? '#94a3b8';
                    $bcFontSize = $back['barcode']['font_size'] ?? 6.5;
                    $bcActualLeft = (100 - $bcWidth) / 2;
                @endphp
                <div class="item-box" style="top: {{ $bcTop }}%; left: {{ $bcActualLeft }}%; width: {{ $bcWidth }}%; text-align: center;">
                    <div style="font-family: 'Courier', monospace; font-size: {{ $bcFontSize }}pt; font-weight: bold; color: {{ $bcColor }}; letter-spacing: 0.8pt;">
                        ||| | |||| | ||| |||| | || ||| |||| | || | |||
                    </div>
                    <div style="font-family: 'Courier', monospace; font-size: 4.2pt; color: {{ $bcColor }}; letter-spacing: 0.5pt;">
                        *{{ $member->membership_number }}*
                    </div>
                </div>
            @endif

            <!-- Return Notice -->
            @if(!empty($back['return_notice']) && ($back['return_notice']['enabled'] ?? true))
                @php
                    $rnTop = $back['return_notice']['top'] ?? 90.0;
                    $rnLeft = $back['return_notice']['left'] ?? 50.0;
                    $rnWidth = $back['return_notice']['width'] ?? 90.0;
                    $rnColor = $back['return_notice']['color'] ?? '#94a3b8';
                    $rnFontSize = $back['return_notice']['font_size'] ?? 4.0;
                    $rnActualLeft = (100 - $rnWidth) / 2;
                @endphp
                <div class="item-box" style="top: {{ $rnTop }}%; left: {{ $rnActualLeft }}%; width: {{ $rnWidth }}%; text-align: center;">
                    <div style="font-size: {{ $rnFontSize }}pt; font-weight: bold; color: {{ $rnColor }}; text-transform: uppercase; letter-spacing: 0.3pt;">
                        {{ $back['return_notice']['text'] ?? 'Property of TEVDA. If found, please return to any TEVDA Regional Office or Police Station.' }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

</body>
</html>
