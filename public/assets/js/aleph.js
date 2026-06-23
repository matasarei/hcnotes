/*
 * aleph.js — generative "Aleph 2" background.
 *
 * A JavaScript reimagining of Max Cooper's "Aleph 2" / Martin Krzywinski's
 * "Story of Infinity" video that hcnotes.cc used to ship as an .mp4:
 * a field of natural numbers counting upward that periodically glitches and
 * decays into aleph numbers and set-theory symbols, then crumbles into dots.
 *
 * Vanilla, dependency-free. Honours prefers-reduced-motion and tab visibility.
 *   <canvas id="aleph-bg"></canvas>
 */
(function () {
  'use strict';

  var canvas = document.getElementById('aleph-bg');
  if (!canvas || !canvas.getContext) return;
  var ctx = canvas.getContext('2d', { alpha: false });

  var reduceMotion = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- toxic palette on near-black ------------------------------------ */
  var BG    = '#03050a';
  var CYAN  = [45, 226, 230];
  var PINK  = [255, 46, 151];
  var WHITE = [234, 253, 255];

  function rgba(c, a) { return 'rgba(' + c[0] + ',' + c[1] + ',' + c[2] + ',' + a + ')'; }

  /* ---- the vocabulary of infinity ------------------------------------- */
  var ALEPHS = ['ℵ₀', 'ℵ₁', 'ℵ₂', 'ℶ₀', 'ℶ₁'];
  var SETSYM = ['∈', '∉', '∪', '∩', '∅', '⊂', '⊆', 'ℕ',
                'ℤ', 'ℚ', 'ℝ', 'ℙ', '∞', '↦', '∀', '∃',
                '¬', '×', '→', '⊕', '≠', '≅', '2ᴿ'];
  var DECAY  = ['·', '.', '˙', '‥', '…'];

  function pick(a) { return a[(Math.random() * a.length) | 0]; }

  /* ---- token states --------------------------------------------------- */
  var COUNT = 0, ALEPH = 1, DECAY_S = 2;

  var W, H, DPR, cell, cols, rows;
  var tokens = [];
  var intensity = 0.15;   // global glitch pressure, swells then resets
  var lastTick = 0;

  function build() {
    DPR = Math.min(window.devicePixelRatio || 1, 2);
    W = canvas.clientWidth;
    H = canvas.clientHeight;
    canvas.width = Math.floor(W * DPR);
    canvas.height = Math.floor(H * DPR);
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);

    cell = W < 600 ? 25 : 31;
    cols = Math.ceil(W / cell);
    rows = Math.ceil(H / cell);

    ctx.font = (cell - 10) + 'px "JetBrains Mono","Fira Code",ui-monospace,Menlo,monospace';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    tokens = [];
    for (var r = 0; r < rows; r++) {
      for (var c = 0; c < cols; c++) {
        // sparse field — ~58% of cells carry a token
        if (Math.random() > 0.58) continue;
        tokens.push(newToken(c * cell + cell / 2, r * cell + cell / 2));
      }
    }
  }

  function newToken(x, y) {
    return {
      x: x, y: y,
      state: COUNT,
      value: 1 + ((Math.random() * 40) | 0),
      step: 1,
      glyph: '',
      // base dimness gives the field depth; a few cells run hot (favourite numbers)
      bright: Math.random() < 0.07 ? 0.85 : 0.10 + Math.random() * 0.22,
      hot: Math.random() < 0.07,
      timer: 200 + Math.random() * 1400, // ms until next state event
      life: 0,
      jitter: 0
    };
  }

  /* ---- simulation tick (slow cadence; numbers don't need 60fps) ------- */
  function update(dt) {
    // glitch pressure breathes: swells, then snaps back like the snare hits
    intensity += dt * 0.00003;
    if (intensity > 0.9 || Math.random() < 0.0006) intensity = 0.12 + Math.random() * 0.1;

    for (var i = 0; i < tokens.length; i++) {
      var t = tokens[i];
      t.timer -= dt;

      if (t.state === COUNT) {
        // count upward
        t.life += dt;
        if (t.life > 90) { t.value += t.step; t.life = 0; }
        // chance to glitch into an aleph, scaled by global pressure
        if (t.timer <= 0 && Math.random() < intensity) {
          t.state = ALEPH;
          t.glyph = Math.random() < 0.45 ? pick(ALEPHS) : pick(SETSYM);
          t.timer = 260 + Math.random() * 900;
          t.jitter = 1;
        } else if (t.timer <= 0) {
          t.timer = 300 + Math.random() * 1600;
        }
      } else if (t.state === ALEPH) {
        if (t.timer <= 0) {
          t.state = DECAY_S;
          t.glyph = pick(DECAY);
          t.timer = 180 + Math.random() * 420;
        }
      } else { // DECAY_S — crumble to dots, then respawn as a fresh count
        if (t.timer <= 0) {
          t.state = COUNT;
          t.value = 1 + ((Math.random() * 30) | 0);
          t.bright = Math.random() < 0.07 ? 0.85 : 0.10 + Math.random() * 0.22;
          t.timer = 400 + Math.random() * 1800;
          t.jitter = 0;
        }
      }
    }
  }

  /* ---- render --------------------------------------------------------- */
  function draw() {
    ctx.fillStyle = BG;
    ctx.fillRect(0, 0, W, H);

    for (var i = 0; i < tokens.length; i++) {
      var t = tokens[i];

      if (t.state === COUNT) {
        ctx.fillStyle = rgba(t.hot ? WHITE : CYAN, t.bright);
        ctx.fillText('' + t.value, t.x, t.y);
      } else if (t.state === ALEPH) {
        // hot glyph — chromatic RGB split, additive
        var dx = 1.4 + Math.random() * 1.8 * intensity * 4;
        ctx.globalCompositeOperation = 'lighter';
        ctx.fillStyle = rgba(PINK, 0.85);
        ctx.fillText(t.glyph, t.x - dx, t.y);
        ctx.fillStyle = rgba(CYAN, 0.85);
        ctx.fillText(t.glyph, t.x + dx, t.y);
        ctx.fillStyle = rgba(WHITE, 0.95);
        ctx.fillText(t.glyph, t.x, t.y);
        ctx.globalCompositeOperation = 'source-over';
      } else { // decay
        ctx.fillStyle = rgba(PINK, 0.45 + Math.random() * 0.2);
        ctx.fillText(t.glyph, t.x, t.y);
      }
    }

    // occasional horizontal slice displacement — a render glitch
    if (!reduceMotion && Math.random() < 0.06) {
      var bands = 1 + ((Math.random() * 3) | 0);
      for (var b = 0; b < bands; b++) {
        var by = (Math.random() * H) | 0;
        var bh = 6 + ((Math.random() * 26) | 0);
        var shift = ((Math.random() - 0.5) * 40) | 0;
        ctx.drawImage(canvas,
          0, by * DPR, canvas.width, bh * DPR,
          shift, by, W, bh);
      }
    }
  }

  /* ---- loops ---------------------------------------------------------- */
  function frame(now) {
    if (document.hidden) { lastTick = now; raf = requestAnimationFrame(frame); return; }
    var dt = Math.min(now - lastTick, 60);
    lastTick = now;
    update(dt);
    draw();
    raf = requestAnimationFrame(frame);
  }

  var raf = null;
  function start() {
    build();
    if (reduceMotion) { update(0); draw(); return; }  // single static frame
    lastTick = performance.now();
    raf = requestAnimationFrame(frame);
  }

  // debounced resize
  var rt;
  window.addEventListener('resize', function () {
    clearTimeout(rt);
    rt = setTimeout(function () {
      if (reduceMotion) { build(); update(0); draw(); }
      else build();
    }, 180);
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
