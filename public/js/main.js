/* =====================================================================
   Coin_Nex · NextGen BudgetBee — main.js (global helpers)
   ===================================================================== */
(function () {
  'use strict';

  const $  = (sel, ctx) => (ctx || document).querySelector(sel);
  const $$ = (sel, ctx) => Array.from((ctx || document).querySelectorAll(sel));

  /* ---------- Theme Management (Dark, Light, Eye Protection) ---------- */
  const rootEl = document.documentElement;
  const themeKey = 'cc_theme';

  window.applyTheme = function (theme, persist) {
    if (!theme) theme = 'dark';
    rootEl.setAttribute('data-theme', theme);
    if (persist) {
      localStorage.setItem(themeKey, theme);
      if (window.CSRF_TOKEN && window.CC_BASE) {
        const scale = localStorage.getItem('cc_font_scale') || '1.0';
        fetch(window.CC_BASE + '/profile/theme', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
          },
          body: 'csrf_token=' + encodeURIComponent(window.CSRF_TOKEN) + '&theme=' + encodeURIComponent(theme) + '&font_scale=' + encodeURIComponent(scale)
        }).catch(() => {});
      }
    }
    $$('[data-theme-switch]').forEach(el => {
      el.checked = theme === 'light';
    });
    $$('[data-set-theme]').forEach(btn => {
      const match = btn.getAttribute('data-set-theme') === theme;
      btn.classList.toggle('active', match);
      if (match) {
        btn.style.borderColor = 'var(--brand)';
        btn.style.background = 'rgba(247,183,51,0.18)';
        btn.style.color = 'var(--brand)';
        btn.style.fontWeight = '700';
      } else {
        btn.style.borderColor = '';
        btn.style.background = '';
        btn.style.color = '';
        btn.style.fontWeight = '';
      }
    });
    const sun = $('[data-theme-sun]');
    const moon = $('[data-theme-moon]');
    if (sun) sun.classList.toggle('hide', theme !== 'light');
    if (moon) moon.classList.toggle('hide', theme !== 'dark');
  };

  $$('[data-theme-switch]').forEach(el => {
    el.addEventListener('change', () => applyTheme(el.checked ? 'light' : 'dark', true));
  });

  const initialTheme = localStorage.getItem(themeKey) || rootEl.getAttribute('data-theme') || 'dark';
  applyTheme(initialTheme);

  document.addEventListener('click', (e) => {
    const themeBtn = e.target.closest('[data-set-theme]');
    if (themeBtn) {
      const t = themeBtn.getAttribute('data-set-theme');
      applyTheme(t, true);
      if (window.toast) {
        const msg = t === 'eye-care' ? '👓 Eye Protection Mode active (Warm amber filter)' : (t === 'light' ? '☀️ Light Mode active' : '🌙 Dark Mode active');
        window.toast(msg, 'info');
      }
      return;
    }
    if (e.target.closest && e.target.closest('[data-theme-toggle]')) {
      const cur = rootEl.getAttribute('data-theme');
      const next = cur === 'dark' ? 'light' : (cur === 'light' ? 'eye-care' : 'dark');
      applyTheme(next, true);
    }
  });

  /* ---------- Toasts ---------- */
  const toastWrap = (() => {
    let w = $('.toast-wrap');
    if (!w) {
      w = document.createElement('div');
      w.className = 'toast-wrap';
      document.body.appendChild(w);
    }
    return w;
  })();

  window.toast = function (message, type) {
    type = type || 'info';
    const el = document.createElement('div');
    el.className = 'toast ' + type;
    el.innerHTML = '<span></span><div style="flex:1">' + message + '</div><button class="toast-close" aria-label="close">✕</button>';
    function dismiss() {
      el.classList.add('leaving');
      setTimeout(() => el.remove(), 300);
    }
    el.querySelector('button').addEventListener('click', dismiss);
    toastWrap.appendChild(el);
    setTimeout(dismiss, 4200);
    return el;
  };

  /* ---------- Auto-hide flash alerts ---------- */
  $$('.alert').forEach(a => {
    if (a.dataset.autoDismiss === 'false') return;
    setTimeout(() => {
      a.style.transition = 'opacity .5s, transform .5s';
      a.style.opacity = '0';
      a.style.transform = 'translateY(-6px)';
      setTimeout(() => a.remove(), 500);
    }, 4200);
  });

  /* ---------- Dropdowns ---------- */
  document.addEventListener('click', (e) => {
    $$('.dropdown.open').forEach(d => { if (!d.contains(e.target)) d.classList.remove('open'); });
    const trig = e.target.closest('[data-dropdown-trigger]');
    if (trig) {
      e.preventDefault();
      const d = trig.closest('.dropdown');
      if (d) d.classList.toggle('open');
    }
  });

  /* ---------- Sidebar (mobile) ---------- */
  $$('[data-sidebar-toggle]').forEach(btn => {
    btn.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
  });
  document.addEventListener('click', (e) => {
    if (document.body.classList.contains('sidebar-open') && !e.target.closest('.sidebar') && !e.target.closest('[data-sidebar-toggle]')) {
      document.body.classList.remove('sidebar-open');
    }
  });

  /* ---------- Collapsible icon-rail sidebar (desktop) ---------- */
  const railKey = 'cc_rail_collapsed';
  function applyRail(collapsed) {
    document.body.classList.toggle('sidebar-collapsed', collapsed);
    const expand = $('[data-rail-expand]');
    const collapse = $('[data-rail-collapse-ico]');
    if (expand) expand.classList.toggle('hide', !collapsed);
    if (collapse) collapse.classList.toggle('hide', collapsed);
    localStorage.setItem(railKey, collapsed ? '1' : '0');
  }
  $$('[data-rail-collapse]').forEach(btn => {
    btn.addEventListener('click', () => applyRail(!document.body.classList.contains('sidebar-collapsed')));
  });
  if (localStorage.getItem(railKey) === '1') applyRail(true);
  window.matchMedia('(max-width: 1100px)').addEventListener('change', (e) => {
    if (e.matches) document.body.classList.remove('sidebar-collapsed');
  });

  /* ---------- Modals ---------- */
  window.openModal = function (id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.add('show');
    document.body.style.overflow = 'hidden';
    const f = $('input,select,textarea', m);
    if (f) setTimeout(() => f.focus(), 120);
  };
  window.closeModal = function (id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.remove('show');
    document.body.style.overflow = '';
  };
  document.addEventListener('click', (e) => {
    const closeBtn = e.target.closest ? e.target.closest('[data-modal-close]') : null;
    if (closeBtn) {
      const mask = closeBtn.closest('.modal-mask');
      if (mask) {
        mask.classList.remove('show');
        document.body.style.overflow = '';
      }
      return;
    }
    const mask = e.target.closest ? e.target.closest('.modal-mask') : null;
    if (mask && e.target === mask) {
      mask.classList.remove('show');
      document.body.style.overflow = '';
    }
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      $$('.modal-mask.show').forEach(m => { m.classList.remove('show'); document.body.style.overflow = ''; });
    }
  });

  /* ---------- Confirm delete ---------- */
  $$('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const msg = el.dataset.confirm || 'Are you sure?';
      if (window.confirm(msg)) window.location.href = el.href;
    });
  });

  /* ---------- Pill selects ---------- */
  $$('.pill-select').forEach(group => {
    const inputs = $$('input[type="radio"], input[type="checkbox"]', group);
    const sync = () => inputs.forEach(inp => inp.closest('.pill').classList.toggle('active', inp.checked));
    inputs.forEach(inp => inp.addEventListener('change', sync));
    sync();
  });

  /* ---------- Numeric amount input formatting ---------- */
  $$('[data-money]').forEach(el => {
    el.addEventListener('input', () => {
      const val = el.value.replace(/[^0-9.]/g, '');
      el.value = val;
    });
  });

  /* ---------- Fetch helper ---------- */
  window.postJSON = function (url, data) {
    data = data || {};
    data.csrf_token = window.CSRF_TOKEN || '';
    return fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    }).then(r => r.json().catch(() => ({})));
  };

  /* ---------- Web Audio Coin Synthesizer & Sound Effects ---------- */
  const SoundEngine = {
    ctx: null,
    enabled: localStorage.getItem('cc_sound') !== 'muted',

    getCtx: function () {
      if (!this.ctx && (window.AudioContext || window.webkitAudioContext)) {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        this.ctx = new AudioCtx();
      }
      if (this.ctx && this.ctx.state === 'suspended') {
        this.ctx.resume();
      }
      return this.ctx;
    },

    playSingleClink: function (ctx, time, freq, decay, gainLevel) {
      try {
        const osc1 = ctx.createOscillator();
        const osc2 = ctx.createOscillator();
        const filter = ctx.createBiquadFilter();
        const gain = ctx.createGain();

        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(freq, time);
        osc1.frequency.exponentialRampToValueAtTime(freq * 0.98, time + decay);

        osc2.type = 'triangle';
        osc2.frequency.setValueAtTime(freq * 1.48, time);
        osc2.frequency.exponentialRampToValueAtTime(freq * 1.42, time + decay * 0.7);

        filter.type = 'bandpass';
        filter.frequency.setValueAtTime(freq * 1.15, time);
        filter.Q.setValueAtTime(3.8, time);

        gain.gain.setValueAtTime(0.0001, time);
        gain.gain.linearRampToValueAtTime(gainLevel || 0.22, time + 0.002);
        gain.gain.exponentialRampToValueAtTime(0.0001, time + decay);

        osc1.connect(filter);
        osc2.connect(filter);
        filter.connect(gain);
        gain.connect(ctx.destination);

        osc1.start(time);
        osc2.start(time);
        osc1.stop(time + decay + 0.05);
        osc2.stop(time + decay + 0.05);
      } catch (err) {}
    },

    playCoinDrop: function () {
      if (localStorage.getItem('cc_sound') === 'muted') return;
      const ctx = this.getCtx();
      if (!ctx) return;

      const now = ctx.currentTime;
      const drops = [
        { t: 0.00, f: 2200, d: 0.22, g: 0.22 },
        { t: 0.07, f: 2950, d: 0.18, g: 0.20 },
        { t: 0.14, f: 1980, d: 0.28, g: 0.26 },
        { t: 0.22, f: 3300, d: 0.16, g: 0.18 },
        { t: 0.30, f: 2500, d: 0.35, g: 0.28 },
        { t: 0.42, f: 1720, d: 0.45, g: 0.30 },
        { t: 0.54, f: 1046.5, d: 0.50, g: 0.18 },
        { t: 0.60, f: 1318.5, d: 0.55, g: 0.22 }
      ];

      drops.forEach(d => {
        this.playSingleClink(ctx, now + d.t, d.f, d.d, d.g);
      });
    }
  };

  function playCoinWavFile() {
    if (localStorage.getItem('cc_sound') === 'muted') return;
    try {
      const base = (window.CC_BASE || '').replace(/\/$/, '');
      const audioUrl = base + '/public/audio/coins.wav';
      const audio = new Audio(audioUrl);
      audio.volume = 0.85;
      const promise = audio.play();
      if (promise !== undefined) {
        promise.catch(() => {
          SoundEngine.playCoinDrop();
        });
      }
    } catch (e) {
      SoundEngine.playCoinDrop();
    }
  }

  // Play coin sound strictly ONCE per session trigger (website open or panel open)
  function playCoinSoundOnce(reason) {
    if (localStorage.getItem('cc_sound') === 'muted') return;
    const sessionKey = 'cc_sound_played_' + reason;
    if (sessionStorage.getItem(sessionKey)) {
      return; // Already played for this session
    }
    sessionStorage.setItem(sessionKey, '1');
    playCoinWavFile();
  }

  window.playCoin_NexSound = function (force) {
    if (force) {
      playCoinWavFile();
    }
  };

  window.toggleCoinSound = function () {
    const isMuted = localStorage.getItem('cc_sound') === 'muted';
    const newMuted = !isMuted;
    localStorage.setItem('cc_sound', newMuted ? 'muted' : 'active');
    updateSoundBtn();
    if (!newMuted) {
      playCoinWavFile();
      if (window.toast) window.toast('Coin sound effects active', 'success');
    } else {
      if (window.toast) window.toast('Coin sound effects muted', 'info');
    }
  };

  function updateSoundBtn() {
    const onIcon = document.getElementById('sound-icon-on');
    const offIcon = document.getElementById('sound-icon-off');
    const isMuted = localStorage.getItem('cc_sound') === 'muted';
    if (onIcon && offIcon) {
      onIcon.classList.toggle('hide', isMuted);
      offIcon.classList.toggle('hide', !isMuted);
    }
    document.querySelectorAll('[data-sound-status]').forEach(el => {
      el.textContent = isMuted ? 'Muted 🔇' : 'Active 🔊';
    });
  }

  /* ---------- Number Odometer / Count-up Animation ---------- */
  function animateCountUps() {
    const els = document.querySelectorAll('[data-count-up]');
    els.forEach(el => {
      const target = parseFloat(el.getAttribute('data-count-up') || '0');
      const prefix = el.getAttribute('data-count-prefix') || '';
      const duration = 1500;
      const startTime = performance.now();

      function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        const currentVal = Math.round(target * ease);
        el.textContent = prefix + currentVal.toLocaleString('en-US');

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          el.textContent = prefix + target.toLocaleString('en-US');
        }
      }
      requestAnimationFrame(update);
    });
  }

  /* ---------- Command Palette (Ctrl+K / Cmd+K) ---------- */
  window.openCommandPalette = function () {
    const p = document.getElementById('cmd-palette');
    if (p) {
      p.style.display = 'flex';
      p.classList.add('open');
      const input = p.querySelector('.cmd-search-input');
      if (input) {
        input.value = '';
        input.focus();
        filterCommands('');
      }
    }
  };

  window.closeCommandPalette = function () {
    const p = document.getElementById('cmd-palette');
    if (p) {
      p.classList.remove('open');
      p.style.display = 'none';
    }
  };

  function filterCommands(query) {
    const items = document.querySelectorAll('.cmd-item');
    const q = query.toLowerCase().trim();
    items.forEach(it => {
      const text = it.textContent.toLowerCase();
      it.style.display = (!q || text.includes(q)) ? 'flex' : 'none';
    });
  }

  document.addEventListener('input', (e) => {
    if (e.target.matches && e.target.matches('.cmd-search-input')) {
      filterCommands(e.target.value);
    }
  });

  /* ---------- Keyboard Shortcuts ---------- */
  const BASE = (window.CC_BASE || '').replace(/\/$/, '');
  const SHORTCUTS = {
    n: '/transactions/add',
    c: '/categories',
    r: '/reports',
    b: '/budgets',
    i: '/insights',
    t: '/tips',
    a: '/admin'
  };

  document.addEventListener('keydown', (e) => {
    // Ctrl + K or Cmd + K
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      const p = document.getElementById('cmd-palette');
      if (p && p.classList.contains('open')) {
        window.closeCommandPalette();
      } else {
        window.openCommandPalette();
      }
      return;
    }

    if (e.key === 'Escape') {
      window.closeCommandPalette();
      return;
    }

    if (e.ctrlKey || e.metaKey || e.altKey) return;
    const tag = (e.target.tagName || '').toLowerCase();
    const typing = tag === 'input' || tag === 'textarea' || tag === 'select' || e.target.isContentEditable;
    const key = e.key.toLowerCase();
    if (key === '/' && !typing) {
      const q = document.querySelector('input[type="search"], input[name="search"], input[name="q"], #txn-search');
      if (q) { e.preventDefault(); q.focus(); q.select(); }
      return;
    }
    if (SHORTCUTS[key] && !typing) {
      e.preventDefault();
      window.location.href = BASE + SHORTCUTS[key];
    }
  });

  /* =====================================================================
     Ambient Background Falling Coins on Scroll Engine (Silent & Butter-Smooth)
     ===================================================================== */
  function initAmbientBackgroundCoins() {
    // Avoid double creation
    if (document.getElementById('cc-ambient-coins-canvas')) return;

    const canvas = document.createElement('canvas');
    canvas.id = 'cc-ambient-coins-canvas';
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100vw';
    canvas.style.height = '100vh';
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = '0';
    canvas.style.opacity = '0.55';
    canvas.style.transition = 'opacity 0.6s ease';
    document.body.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let width = (canvas.width = window.innerWidth);
    let height = (canvas.height = window.innerHeight);

    window.addEventListener('resize', () => {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    }, { passive: true });

    const base = (window.CC_BASE || '').replace(/\/$/, '');
    const coinImg = new Image();
    coinImg.src = base + '/public/images/gold-coin.png';

    const COIN_COUNT = window.innerWidth < 768 ? 14 : 24;
    const coins = [];

    for (let i = 0; i < COIN_COUNT; i++) {
      coins.push({
        x: Math.random() * width,
        y: Math.random() * height,
        size: Math.floor(Math.random() * 20 + 16), // 16px to 36px
        speedY: Math.random() * 0.45 + 0.35, // gentle base float
        speedX: (Math.random() - 0.5) * 0.3,
        rot: Math.random() * Math.PI * 2,
        rotSpeed: (Math.random() - 0.5) * 0.035,
        wobble: Math.random() * Math.PI * 2,
        wobbleSpeed: Math.random() * 0.04 + 0.02,
        opacity: Math.random() * 0.4 + 0.25
      });
    }

    // Scroll tracking for momentum
    let lastScrollY = window.scrollY || window.pageYOffset || 0;
    let scrollVelocity = 0;
    let scrollTimeout = null;

    window.addEventListener('scroll', () => {
      const currentScrollY = window.scrollY || window.pageYOffset || 0;
      const delta = currentScrollY - lastScrollY;
      lastScrollY = currentScrollY;

      // Impart downward momentum when scrolling down (or upward when scrolling up)
      scrollVelocity += delta * 0.08;
      // Cap max scroll velocity
      scrollVelocity = Math.max(Math.min(scrollVelocity, 12), -6);

      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        scrollVelocity = 0;
      }, 140);
    }, { passive: true });

    let isImageLoaded = false;
    coinImg.onload = () => { isImageLoaded = true; };

    function render() {
      ctx.clearRect(0, 0, width, height);

      // Dampen scroll velocity smoothly toward 0
      scrollVelocity *= 0.94;
      if (Math.abs(scrollVelocity) < 0.01) scrollVelocity = 0;

      if (isImageLoaded) {
        for (let i = 0; i < coins.length; i++) {
          const c = coins[i];

          // Dynamic velocity: base gentle fall + scroll impulse
          c.y += c.speedY + scrollVelocity;
          c.x += c.speedX;
          c.rot += c.rotSpeed + (scrollVelocity * 0.005);
          c.wobble += c.wobbleSpeed;

          // Wrap boundaries
          if (c.y > height + 60) {
            c.y = -50;
            c.x = Math.random() * width;
          } else if (c.y < -60) {
            c.y = height + 50;
            c.x = Math.random() * width;
          }
          if (c.x > width + 40) c.x = -30;
          if (c.x < -40) c.x = width + 30;

          // 3D tumble flip factor using Math.sin
          const scaleX = Math.sin(c.wobble);

          ctx.save();
          ctx.globalAlpha = c.opacity;
          ctx.translate(c.x, c.y);
          ctx.rotate(c.rot);
          ctx.scale(scaleX, 1);

          ctx.drawImage(coinImg, -c.size / 2, -c.size / 2, c.size, c.size);
          ctx.restore();
        }
      }

      requestAnimationFrame(render);
    }

    requestAnimationFrame(render);
  }

  /* ---------- Sound Policy: Only ONCE per session on open or panel enter ---------- */
  function initCoinSoundPolicy() {
    const path = window.location.pathname.toLowerCase();
    let triggerReason = 'initial_open';

    if (path.includes('/admin')) {
      triggerReason = 'admin_panel';
    } else if (path.includes('/dashboard') || document.querySelector('.app-shell')) {
      triggerReason = 'user_panel';
    }

    const sessionKey = 'cc_sound_played_' + triggerReason;
    if (sessionStorage.getItem(sessionKey)) {
      return; // Already played for this trigger in this session
    }

    // Try playing immediately
    try {
      playCoinWavFile();
      sessionStorage.setItem(sessionKey, '1');
    } catch (e) {
      // Autoplay blocked: wait for first interaction
      const playOnFirstTouch = () => {
        if (!sessionStorage.getItem(sessionKey)) {
          sessionStorage.setItem(sessionKey, '1');
          try {
            const ctx = SoundEngine.getCtx();
            if (ctx && ctx.state === 'suspended') ctx.resume();
          } catch (err) {}
          playCoinWavFile();
        }
      };
      window.addEventListener('click', playOnFirstTouch, { once: true, capture: true });
      window.addEventListener('keydown', playOnFirstTouch, { once: true, capture: true });
      window.addEventListener('touchstart', playOnFirstTouch, { once: true, capture: true });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    updateSoundBtn();
    animateCountUps();
    initAmbientBackgroundCoins();
    initCoinSoundPolicy();
  });

})();