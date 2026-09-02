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
 * field with the glitch effects off, so the text is what the eye settles on.
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
      fps: 5,         // slow enough to read past, not so slow it looks stuck
      dprCap: 1.5,    // same crispness as the index; the savings come from density and fps
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

  function rgba(c, a) {
    return 'rgba(' + c[0] + ',' + c[1] + ',' + c[2] + ',' + (Math.round(a * 1000) / 1000) + ')';
  }

  /* ---- radial fade ---------------------------------------------------- */
  // The centre of the field is dimmed so foreground text stays legible. This
  // used to be a CSS mask-image on the canvas, which costs the compositor a
  // full-canvas pass on every frame. Token positions are fixed at build time,
  // so the same ellipse (80% x 75%, centred at 50%/45%, 0.35 at the centre,
  // 0.78 at 55% of the radius, 1 at the edge) is folded into each token's
  // alpha once instead.
  function fadeAt(x, y) {
    var nx = (x - W * 0.5) / (W * 0.8);
    var ny = (y - H * 0.45) / (H * 0.75);
    var d = Math.sqrt(nx * nx + ny * ny);
    if (d >= 1) return 1;
    if (d <= 0.55) return 0.35 + (0.78 - 0.35) * (d / 0.55);
    return 0.78 + (1 - 0.78) * ((d - 0.55) / 0.45);
  }
  // Below this effective alpha a glyph is not visible over the background at
  // all, so the token is never created and never drawn.
  var MIN_ALPHA = 0.05;

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
        var t = newToken(c * cell + cell / 2, r * cell + cell / 2);
        if (t.bright * t.fade >= MIN_ALPHA) tokens.push(t);
      }
    }
  }

  function baseBright() {
    return (Math.random() < 0.07 ? 0.85 : 0.10 + Math.random() * 0.22) * scene.brightness;
  }

  // Colour strings are built only here, on a state change, never per frame:
  // fill is what every state draws with, fillL/fillR are the pink/cyan halves
  // of the chromatic split an aleph glyph gets in the full scene.
  function paint(t) {
    var f = t.fade;
    if (t.state === COUNT) {
      t.fill = rgba(t.hot ? WHITE : CYAN, t.bright * f);
    } else if (t.state === ALEPH && scene.glitch) {
      t.fill  = rgba(WHITE, 0.95 * f);
      t.fillL = rgba(PINK, 0.85 * f);
      t.fillR = rgba(CYAN, 0.85 * f);
    } else if (t.state === ALEPH) {
      t.fill = rgba(PINK, 0.6 * scene.brightness * f);
    } else {
      t.fill = rgba(PINK, (0.45 + Math.random() * 0.2) * f);
    }
  }

  function newToken(x, y) {
    var t = {
      x: x, y: y,
      fade: fadeAt(x, y),
      state: COUNT,
      value: 1 + ((Math.random() * 40) | 0),
      step: 1,
      glyph: '',
      // base dimness gives the field depth; a few cells run hot (favourite numbers)
      bright: baseBright(),
      hot: Math.random() < scene.hotShare,
      fill: '', fillL: '', fillR: '',
      timer: 200 + Math.random() * 1400, // ms until next state event
      life: 0
    };
    paint(t);
    return t;
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
          paint(t);
        } else if (t.timer <= 0) {
          t.timer = 300 + Math.random() * 1600;
        }
      } else if (t.state === ALEPH) {
        if (t.timer <= 0) {
          t.state = DECAY_S;
          t.glyph = pick(DECAY);
          t.timer = 180 + Math.random() * 420;
          paint(t);
        }
      } else { // DECAY_S — crumble to dots, then respawn as a fresh count
        if (t.timer <= 0) {
          t.state = COUNT;
          t.value = 1 + ((Math.random() * 30) | 0);
          t.bright = baseBright();
          t.timer = 400 + Math.random() * 1800;
          paint(t);
        }
      }
    }
  }

  /* ---- render --------------------------------------------------------- */
  // Aleph glyphs in the full scene are drawn additively. They are collected
  // during the main pass and drawn together afterwards, so the composite mode
  // is switched twice per frame rather than twice per glyph.
  var alephs = [];

  function draw() {
    ctx.fillStyle = BG;
    ctx.fillRect(0, 0, W, H);
    alephs.length = 0;

    for (var i = 0; i < tokens.length; i++) {
      var t = tokens[i];
      if (t.state === COUNT) {
        ctx.fillStyle = t.fill;
        ctx.fillText('' + t.value, t.x, t.y);
      } else if (t.state === ALEPH && scene.glitch) {
        alephs.push(t);
      } else { // calm aleph, or decay
        ctx.fillStyle = t.fill;
        ctx.fillText(t.glyph, t.x, t.y);
      }
    }

    if (alephs.length) {
      // hot glyphs — chromatic RGB split, additive
      ctx.globalCompositeOperation = 'lighter';
      for (var a = 0; a < alephs.length; a++) {
        var g = alephs[a];
        var dx = 1.4 + Math.random() * 1.8 * intensity * 4;
        ctx.fillStyle = g.fillL;
        ctx.fillText(g.glyph, g.x - dx, g.y);
        ctx.fillStyle = g.fillR;
        ctx.fillText(g.glyph, g.x + dx, g.y);
        ctx.fillStyle = g.fill;
        ctx.fillText(g.glyph, g.x, g.y);
      }
      ctx.globalCompositeOperation = 'source-over';
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
  // Frame cap per scene. The field is discrete glyph swaps, not smooth
  // motion, so a cap well under the display rate is visually indistinguishable
  // but cuts the draw work proportionally. Real elapsed time still drives the
  // simulation, so the counting cadence is unchanged.
  var FRAME_MS = 1000 / scene.fps;
  // A frame normally covers about FRAME_MS of real time; anything much longer
  // is a return from a hidden tab, and the simulation must not jump to catch
  // up.
  var MAX_DT = FRAME_MS * 2;

  // A low frame cap does not need a display-rate wake-up just to return
  // early: below 20fps the next frame is scheduled with a timer that fires
  // when it is due, so the tab can idle in between.
  var useTimer = scene.fps < 20;
  function schedule() {
    if (useTimer) {
      return setTimeout(function () { frame(performance.now()); }, FRAME_MS);
    }
    return requestAnimationFrame(frame);
  }
  function unschedule(id) {
    if (useTimer) clearTimeout(id); else cancelAnimationFrame(id);
  }

  function frame(now) {
    raf = schedule();
    var since = now - lastTick;
    if (since < FRAME_MS - 1) return;   // not time to draw yet — skip
    lastTick = now;
    update(Math.min(since, MAX_DT));    // cap dt so a tab-return doesn't jump
    draw();
  }

  var raf = null;
  function start() {
    build();
    if (reduceMotion) { update(0); draw(); return; }  // single static frame
    lastTick = performance.now();
    raf = schedule();
  }

  // Truly pause when the tab is hidden, and resume cleanly on return.
  document.addEventListener('visibilitychange', function () {
    if (reduceMotion) return;
    if (document.hidden) {
      if (raf) { unschedule(raf); raf = null; }
    } else if (!raf) {
      lastTick = performance.now();
      raf = schedule();
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
