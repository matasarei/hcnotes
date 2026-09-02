/*
 * aleph.js — generative "Aleph 2" background.
 *
 * A JavaScript reimagining of Max Cooper's "Aleph 2" / Martin Krzywinski's
 * "Story of Infinity" video that hcnotes.cc used to ship as an .mp4:
 * a field of natural numbers counting upward that periodically glitches and
 * decays into aleph numbers and set-theory symbols, then crumbles into dots.
 *
 * Vanilla, dependency-free. Honours prefers-reduced-motion and tab visibility.
 *   <canvas id="aleph-bg" data-scene="full|calm"></canvas>
 *
 * The page picks a scene through data-scene. "full" is the living field the
 * index shows; "calm" is what article pages ask for: a sparser, dimmer, slower
 * field with the glitch effects off, so the text is the only thing moving.
 */
(function () {
  'use strict';

  var canvas = document.getElementById('aleph-bg');
  if (!canvas || !canvas.getContext) return;
  var ctx = canvas.getContext('2d', { alpha: false });

  var reduceMotion = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---- scenes --------------------------------------------------------- */
  var SCENES = {
    full: {
      density: 0.58,  // share of grid cells that carry a token
      fps: 30,        // the field is discrete glyph swaps; 30 reads like 60
      dprCap: 1.5,    // dim field behind overlays: 1.5 is indistinguishable from 2
      glitch: true,   // chromatic split on aleph glyphs + horizontal slice tears
      hotShare: 0.07, // share of tokens that run bright white
      brightness: 1
    },
    calm: {
      density: 0.20,
      fps: 10,
      dprCap: 1,
      glitch: false,
      hotShare: 0,
      brightness: 0.6
    }
  };
  var scene = SCENES[canvas.getAttribute('data-scene')] || SCENES.full;

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
    // Cap DPR: this is a dim field behind scanlines/vignette/content, so a
    // lower cap rasterises far fewer pixels per glyph without a visible cost.
    DPR = Math.min(window.devicePixelRatio || 1, scene.dprCap);
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
        // sparse field — only some cells carry a token
        if (Math.random() > scene.density) continue;
        tokens.push(newToken(c * cell + cell / 2, r * cell + cell / 2));
      }
    }
  }

  function baseBright() {
    return (Math.random() < 0.07 ? 0.85 : 0.10 + Math.random() * 0.22) * scene.brightness;
  }

  function newToken(x, y) {
    var hot = Math.random() < scene.hotShare;
    var bright = baseBright();
    return {
      x: x, y: y,
      state: COUNT,
      value: 1 + ((Math.random() * 40) | 0),
      step: 1,
      glyph: '',
      // base dimness gives the field depth; a few cells run hot (favourite numbers)
      bright: bright,
      hot: hot,
      // precomputed COUNT colour — only changes on respawn, so we cache it
      // instead of rebuilding the rgba() string for every token every frame
      fill: rgba(hot ? WHITE : CYAN, bright),
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
          t.bright = baseBright();
          t.fill = rgba(t.hot ? WHITE : CYAN, t.bright);
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
        ctx.fillStyle = t.fill;
        ctx.fillText('' + t.value, t.x, t.y);
      } else if (t.state === ALEPH && !scene.glitch) {
        // calm scene: a single quiet glyph, no additive split
        ctx.fillStyle = rgba(PINK, 0.6 * scene.brightness);
        ctx.fillText(t.glyph, t.x, t.y);
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
    if (scene.glitch && !reduceMotion && Math.random() < 0.06) {
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
  // Frame cap per scene (30 for full, 10 for calm). The field is discrete
  // glyph swaps, not smooth motion, so a cap well under the display rate is
  // visually indistinguishable but cuts the draw work proportionally. Real
  // elapsed time still drives the simulation, so the counting cadence is
  // unchanged.
  var FRAME_MS = 1000 / scene.fps;

  function frame(now) {
    raf = requestAnimationFrame(frame);
    var since = now - lastTick;
    if (since < FRAME_MS - 1) return;   // not time to draw yet — skip
    lastTick = now;
    update(Math.min(since, 60));        // cap dt so a tab-return doesn't jump
    draw();
  }

  var raf = null;
  function start() {
    build();
    if (reduceMotion) { update(0); draw(); return; }  // single static frame
    lastTick = performance.now();
    raf = requestAnimationFrame(frame);
  }

  // Truly pause when the tab is hidden, and resume cleanly on return.
  document.addEventListener('visibilitychange', function () {
    if (reduceMotion) return;
    if (document.hidden) {
      if (raf) { cancelAnimationFrame(raf); raf = null; }
    } else if (!raf) {
      lastTick = performance.now();
      raf = requestAnimationFrame(frame);
    }
  });

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
