# TEVDA Mobile Application

**Chama cha Madereva wa Vyombo vya Usafiri vya Umeme Tanzania (TEVDA)**  
*SMART DRIVERS • SMART MOBILITY*

This application is the official mobile application frontend for TEVDA drivers, built based on the design mockup provided. It is a standalone, installable Progressive Web Application (PWA) and Capacitor-ready mobile app.

---

## 📱 Features & Screens Implemented

The application implements all 6 screens from the official design specification with 100% visual and functional fidelity:

1. **Home Dashboard (`screen-home`)**:
   - Official TEVDA brand logo & unread notification counter.
   - Green motto banner: `SMART DRIVERS • SMART MOBILITY`.
   - Driver greeting (`Habari, Amani`) with `✓ ACTIVE MEMBER` badge and `Member No. TEVDA-2026-000128`.
   - Interactive Digital ID Card preview with driver avatar, registration plate (`Electric Bajaji • T 482 EVM`), and scannable QR Code.
   - 4 Quick Action tiles: `My ID`, `Payments`, `Training`, `Report an Issue`.
   - 4-tab native Bottom Navigation Bar (`Home`, `ID`, `Services`, `Profile`).

2. **Join TEVDA Registration (`screen-register_step1`)**:
   - 3-step progress stepper (1. Personal Details, 2. Payment, 3. Vehicle Details).
   - Form inputs with leading icons: Full Legal Name, Mobile Phone Number, Region (*Mkoa*), District (*Wilaya*), Ward (*Kata*), Operating Stage (*Kituo cha Bajaji/Gari*), Category.
   - Dynamic Region -> District -> Ward administrative filtering for Tanzania.
   - `Continue →` button transitioning to Payment.

3. **Membership Payment (`screen-register_step2`)**:
   - Membership Fee card: `TZS 15,000` (Registration + Membership).
   - Electronic Payment Control Number card: `482173056194` with one-tap copy button and toast alert.
   - Mobile Money gateway logos & bank transfer options (M-Pesa, Tigo Pesa, Airtel Money, HaloPesa, Banks).
   - Real-time `Status: Awaiting payment` status box.
   - `✓ I Have Paid` verification trigger.

4. **Digital Smart ID (`screen-smart_id`)**:
   - Full high-resolution smart card with gold `TEVDA MEMBER` tag, member photo, validity date, driving licence, and scannable QR code.
   - `⬇ Download Certificate` button generating authentic membership credentials.
   - `✓ Your TEVDA membership is verified and active.` confirmation banner.

5. **Grow with TEVDA (`screen-services`)**:
   - Hero banner: *Build skills. Access opportunities. Drive a greener Tanzania.*
   - 4 specialized service cards with custom artwork:
     - **EV Battery Safety**: Safe battery handling and thermal runaway prevention (`Start Course →`).
     - **High-Voltage Maintenance**: Inverter and motor diagnostics (`Learn More →`).
     - **Green Mobility Jobs**: Active commercial fleet openings in Dar es Salaam (`Browse Jobs →`).
     - **Vehicle Financing**: Subsidized clean e-mobility loans via CRDB / NMB (`Apply Now →`).

6. **Report an Issue (`screen-report_issue`)**:
   - Confidentiality banner: *Your report is confidential - We value your safety and protect your identity.*
   - 4 issue categories: `Extortion`, `Police Harassment`, `Battery Theft`, `Unsafe Charging`.
   - Detailed incident description textarea.
   - `Report Anonymously` toggle switch.
   - `Submit Securely` button issuing tracking references (e.g. `TEVDA-CMP-2026-XXXXXX`).

7. **Driver Profile & Settings (`screen-profile`)**:
   - Driver bio, verified status, contact details, registration plate, and shortcuts to register additional vehicles.

---

## 🚀 How to Run and Test

### Option 1: Directly via Apache (XAMPP)
Since this repository is inside `c:\xampp\htdocs\tevda\tevdaapp`, if XAMPP Apache is running:
1. Open your browser and navigate to:
   ```text
   http://localhost/tevda/tevdaapp/
   ```
2. On desktop browsers, you can use the top-right button (`🖥️ Fullscreen` / `📱 Frame Mode`) to switch between a realistic iPhone mockup frame and full-window mode.

### Option 2: Run with any local server (Node / Vite / Python / PHP)
From inside the `tevdaapp` folder:
```bash
# Using PHP built-in server:
php -S 127.0.0.1:8080

# Using Node npx serve:
npx serve .
```

---

## 📲 Installing as a PWA on Android / iPhone

1. Open the URL on your mobile phone (e.g., via your local Wi-Fi IP or production URL).
2. **Android (Chrome)**: Tap the 3 dots menu -> **Install app** or **Add to Home Screen**.
3. **iPhone (Safari)**: Tap the Share button -> **Add to Home Screen**.
4. The application will launch full screen with its own native icon, splash screen, and offline service worker support.

---

## 🛠️ Building a Native Android APK (Capacitor)

If you wish to compile an installable `.apk` file:
```bash
# 1. Install Capacitor dependencies
npm install @capacitor/core @capacitor/cli @capacitor/android

# 2. Add Android platform
npx cap add android

# 3. Open in Android Studio to build APK
npx cap open android
```
