/**
 * TEVDA Mobile Application Controller
 * Handles screen transitions, tab navigation, registration flow,
 * QR code rendering, issue reporting, and interactive notifications.
 */

class TevdaApp {
  constructor() {
    this.currentScreen = 'home';
    this.data = window.TEVDA_DATA;
    this.selectedIssueCategory = 'extortion';
    this.init();
  }

  init() {
    this.bindEvents();
    this.populateRegions();
    this.renderCurrentMember();
    this.updateClock();
    setInterval(() => this.updateClock(), 30000);

    // Register service worker if available
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('./sw.js').catch(() => {});
    }
  }

  updateClock() {
    const now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();
    if (minutes < 10) minutes = '0' + minutes;
    const timeStr = `${hours}:${minutes}`;
    document.querySelectorAll('.status-bar .time').forEach(el => {
      el.textContent = timeStr;
    });
  }

  // --------------------------------------------------------------------------
  // Navigation & Screen Management
  // --------------------------------------------------------------------------
  switchScreen(screenId, backHistory = true) {
    const screens = document.querySelectorAll('.app-screen');
    screens.forEach(s => s.classList.remove('active'));

    const target = document.getElementById(`screen-${screenId}`);
    if (target) {
      target.classList.add('active');
      this.currentScreen = screenId;
      document.querySelector('.app-viewport').scrollTop = 0;
    }

    this.updateBottomNav(screenId);
    this.updateStatusBarTheme(screenId);
  }

  updateBottomNav(screenId) {
    const tabs = document.querySelectorAll('.bottom-nav-bar .nav-tab');
    tabs.forEach(tab => tab.classList.remove('active'));

    let activeTabId = null;
    if (screenId === 'home') activeTabId = 'tab-home';
    else if (screenId === 'smart_id') activeTabId = 'tab-id';
    else if (screenId === 'services') activeTabId = 'tab-services';
    else if (screenId === 'profile') activeTabId = 'tab-profile';

    if (activeTabId) {
      const activeEl = document.getElementById(activeTabId);
      if (activeEl) activeEl.classList.add('active');
    }
  }

  updateStatusBarTheme(screenId) {
    const statusBar = document.querySelector('.status-bar');
    if (!statusBar) return;

    if (screenId === 'home') {
      statusBar.className = 'status-bar light-theme';
    } else {
      statusBar.className = 'status-bar dark-theme';
    }
  }

  // --------------------------------------------------------------------------
  // Member Data Rendering
  // --------------------------------------------------------------------------
  renderCurrentMember() {
    const m = this.data.currentMember;

    // Home Screen
    const homeGreeting = document.getElementById('home-greeting');
    if (homeGreeting) homeGreeting.textContent = `Habari, ${m.greetingName}`;

    const homeMemberNo = document.getElementById('home-member-no');
    if (homeMemberNo) homeMemberNo.textContent = `Member No. ${m.memberNo}`;

    const homeDriverName = document.getElementById('home-driver-name');
    if (homeDriverName) homeDriverName.textContent = m.fullName;

    const homeDriverVehicle = document.getElementById('home-driver-vehicle');
    if (homeDriverVehicle) homeDriverVehicle.textContent = `${m.vehicleType} • ${m.vehicleReg}`;

    // Smart ID Screen
    const idMemberNo = document.getElementById('id-card-member-no');
    if (idMemberNo) idMemberNo.textContent = m.memberNo;

    const idName = document.getElementById('id-card-name');
    if (idName) idName.textContent = m.fullName.toUpperCase();

    const idVehicle = document.getElementById('id-card-vehicle');
    if (idVehicle) idVehicle.textContent = m.vehicleType.toUpperCase();

    const idValidity = document.getElementById('id-card-validity');
    if (idValidity) idValidity.textContent = m.validThrough;
  }

  // --------------------------------------------------------------------------
  // Registration Form Logic
  // --------------------------------------------------------------------------
  populateRegions() {
    const regionSelect = document.getElementById('reg-region');
    if (!regionSelect) return;

    regionSelect.innerHTML = '<option value="">Select Region</option>';
    this.data.regions.forEach(r => {
      const opt = document.createElement('option');
      opt.value = r.id;
      opt.textContent = r.name;
      regionSelect.appendChild(opt);
    });
  }

  onRegionChange(regionId) {
    const districtSelect = document.getElementById('reg-district');
    const wardSelect = document.getElementById('reg-ward');
    if (!districtSelect || !wardSelect) return;

    districtSelect.innerHTML = '<option value="">Select District</option>';
    wardSelect.innerHTML = '<option value="">Select Ward</option>';

    if (!regionId) return;

    const region = this.data.regions.find(r => r.id === regionId);
    if (region && region.districts) {
      region.districts.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = d.name;
        districtSelect.appendChild(opt);
      });
    }
  }

  onDistrictChange(districtId) {
    const regionSelect = document.getElementById('reg-region');
    const wardSelect = document.getElementById('reg-ward');
    if (!regionSelect || !wardSelect || !districtSelect) return;

    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    if (!districtId) return;

    const region = this.data.regions.find(r => r.id === regionSelect.value);
    if (region) {
      const district = region.districts.find(d => d.id === districtId);
      if (district && district.wards) {
        district.wards.forEach(w => {
          const opt = document.createElement('option');
          opt.value = w;
          opt.textContent = w;
          wardSelect.appendChild(opt);
        });
      }
    }
  }

  handleStep1Submit() {
    const name = document.getElementById('reg-name')?.value.trim();
    const phone = document.getElementById('reg-phone')?.value.trim();
    const region = document.getElementById('reg-region')?.value;
    const stage = document.getElementById('reg-stage')?.value.trim();

    if (!name || !phone) {
      this.showToast('⚠️ Tafadhali jaza Jina Kamili na Namba ya Simu.');
      return;
    }

    this.data.registrationDraft.fullName = name;
    this.data.registrationDraft.phone = phone;
    this.data.registrationDraft.operatingStage = stage || 'Sinza Mori';

    // Move to Step 2: Payment
    this.switchScreen('register_step2');
    this.showToast('✅ Taarifa zimepokelewa! Hatua ya 2: Malipo.');
  }

  copyControlNumber() {
    const code = document.getElementById('control-number-code')?.textContent || '482173056194';
    if (navigator.clipboard) {
      navigator.clipboard.writeText(code).then(() => {
        this.showToast('📋 Control Number imenakiliwa: ' + code);
      });
    } else {
      this.showToast('📋 Namba ya Malipo: ' + code);
    }
  }

  handlePaymentConfirm() {
    this.showToast('⏳ Inathibitisha malipo ya TZS 15,000...');
    setTimeout(() => {
      this.data.currentMember.fullName = this.data.registrationDraft.fullName || "Amani Juma";
      this.data.currentMember.phone = this.data.registrationDraft.phone || "+255 712 345 678";
      this.renderCurrentMember();
      this.switchScreen('smart_id');
      this.showToast('🎉 Malipo yamethibitishwa! Kitambulisho kiko tayari.');
    }, 1200);
  }

  // --------------------------------------------------------------------------
  // Issue Reporting
  // --------------------------------------------------------------------------
  selectIssueCategory(catId) {
    this.selectedIssueCategory = catId;
    document.querySelectorAll('.issue-category-tile').forEach(tile => {
      if (tile.dataset.category === catId) {
        tile.classList.add('selected');
      } else {
        tile.classList.remove('selected');
      }
    });
  }

  handleIssueSubmit() {
    const desc = document.getElementById('issue-desc')?.value.trim();
    const isAnonymous = document.getElementById('anonymous-toggle')?.checked;

    if (!desc) {
      this.showToast('⚠️ Tafadhali toa maelezo mafupi ya tukio.');
      return;
    }

    const ticketNo = 'TEVDA-CMP-' + Math.floor(100000 + Math.random() * 900000);
    const anonText = isAnonymous ? '(Bila Kujitambulisha)' : '';

    this.showToast(`🛡️ Ripoti imepokewa salama! Namba ya Tiketi: ${ticketNo} ${anonText}`);
    
    // Clear & Return home
    if (document.getElementById('issue-desc')) document.getElementById('issue-desc').value = '';
    setTimeout(() => {
      this.switchScreen('home');
    }, 1500);
  }

  // --------------------------------------------------------------------------
  // Certificate Download Simulation
  // --------------------------------------------------------------------------
  downloadCertificate() {
    this.showToast('📄 Cheti cha Uanachama kinazalishwa rasmi...');
    setTimeout(() => {
      window.open('https://tevda.or.tz/verify/certificate?number=TEVDA-CERT-2026-000128', '_blank');
      this.showToast('✅ Cheti kimepakuliwa kikamilifu!');
    }, 1000);
  }

  // --------------------------------------------------------------------------
  // Toast Helper
  // --------------------------------------------------------------------------
  showToast(message) {
    const toast = document.getElementById('app-toast');
    if (!toast) return;

    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(this.toastTimeout);
    this.toastTimeout = setTimeout(() => {
      toast.classList.remove('show');
    }, 3200);
  }

  // --------------------------------------------------------------------------
  // Event Listeners Binding
  // --------------------------------------------------------------------------
  bindEvents() {
    // Bottom Nav Tabs
    document.getElementById('tab-home')?.addEventListener('click', (e) => {
      e.preventDefault();
      this.switchScreen('home');
    });

    document.getElementById('tab-id')?.addEventListener('click', (e) => {
      e.preventDefault();
      this.switchScreen('smart_id');
    });

    document.getElementById('tab-services')?.addEventListener('click', (e) => {
      e.preventDefault();
      this.switchScreen('services');
    });

    document.getElementById('tab-profile')?.addEventListener('click', (e) => {
      e.preventDefault();
      this.switchScreen('profile');
    });

    // Quick Action Tiles on Home
    document.getElementById('action-my-id')?.addEventListener('click', () => {
      this.switchScreen('smart_id');
    });

    document.getElementById('action-payments')?.addEventListener('click', () => {
      this.switchScreen('register_step2');
    });

    document.getElementById('action-training')?.addEventListener('click', () => {
      this.switchScreen('services');
    });

    document.getElementById('action-report')?.addEventListener('click', () => {
      this.switchScreen('report_issue');
    });

    // Home Digital ID Preview Card -> opens full Smart ID
    document.getElementById('home-id-card-preview')?.addEventListener('click', () => {
      this.switchScreen('smart_id');
    });

    // Back Buttons
    document.querySelectorAll('.back-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const backTo = btn.dataset.back || 'home';
        this.switchScreen(backTo);
      });
    });

    // Dynamic Selects
    document.getElementById('reg-region')?.addEventListener('change', (e) => {
      this.onRegionChange(e.target.value);
    });

    document.getElementById('reg-district')?.addEventListener('change', (e) => {
      this.onDistrictChange(e.target.value);
    });

    // Form Submissions
    document.getElementById('btn-continue-step1')?.addEventListener('click', () => {
      this.handleStep1Submit();
    });

    document.getElementById('btn-copy-control')?.addEventListener('click', () => {
      this.copyControlNumber();
    });

    document.getElementById('btn-confirm-payment')?.addEventListener('click', () => {
      this.handlePaymentConfirm();
    });

    document.getElementById('btn-download-cert')?.addEventListener('click', () => {
      this.downloadCertificate();
    });

    document.getElementById('btn-submit-issue')?.addEventListener('click', () => {
      this.handleIssueSubmit();
    });

    // Issue Categories Selection
    document.querySelectorAll('.issue-category-tile').forEach(tile => {
      tile.addEventListener('click', () => {
        this.selectIssueCategory(tile.dataset.category);
      });
    });

    // Frame Toggle (Mobile simulator frame vs Fullscreen)
    document.getElementById('btn-toggle-frame')?.addEventListener('click', () => {
      document.body.classList.toggle('fullscreen-mode');
      const isFullscreen = document.body.classList.contains('fullscreen-mode');
      document.getElementById('btn-toggle-frame').innerHTML = isFullscreen
        ? '<span>📱 Frame Mode</span>'
        : '<span>🖥️ Fullscreen</span>';
    });
  }
}

// Instantiate on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  window.app = new TevdaApp();
});
