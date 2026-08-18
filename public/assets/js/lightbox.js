/*
 * lightbox.js — minimal on-theme image pop-up for article pages.
 *
 * Every image inside `.prose .content` becomes clickable and opens
 * full-size in an overlay. Close with a click, Esc, or the ✕;
 * cycle through the article's images with ← / →.
 *
 * Vanilla, dependency-free.
 */
(function () {
  'use strict';

  var imgs = [].slice.call(document.querySelectorAll('.prose .content img'));
  if (!imgs.length) return;

  var overlay = null, big = null, caption = null, current = -1;

  function build() {
    overlay = document.createElement('div');
    overlay.className = 'lightbox';
    overlay.innerHTML =
      '<button class="lightbox__close" aria-label="Close">✕</button>' +
      '<img class="lightbox__img" alt="">' +
      '<div class="lightbox__caption"></div>';
    document.body.appendChild(overlay);

    big = overlay.querySelector('.lightbox__img');
    caption = overlay.querySelector('.lightbox__caption');

    /* a click anywhere (image included) closes; arrows are keyboard-only */
    overlay.addEventListener('click', close);
  }

  function show(i) {
    current = (i + imgs.length) % imgs.length;
    big.src = imgs[current].currentSrc || imgs[current].src;
    big.alt = imgs[current].alt;
    caption.textContent = imgs[current].alt || '';
  }

  function open(i) {
    if (!overlay) build();
    show(i);
    overlay.classList.add('is-open');
    document.body.classList.add('lightbox-open');
    document.addEventListener('keydown', onKey);
  }

  function close() {
    overlay.classList.remove('is-open');
    document.body.classList.remove('lightbox-open');
    document.removeEventListener('keydown', onKey);
  }

  function onKey(e) {
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowRight') show(current + 1);
    else if (e.key === 'ArrowLeft') show(current - 1);
  }

  imgs.forEach(function (img, i) {
    img.classList.add('is-zoomable');
    img.addEventListener('click', function () { open(i); });
  });
})();
