# AGENTS.md — Working Rules for AI Agents

hcnotes is the source of **[hcnotes.cc](https://hcnotes.cc)** — a personal site
and blog built on [AICMF](https://github.com/matasarei/aicmf), designed to be
administered and developed by AI agents. This file is the contract for how that
work is done. `CLAUDE.md` is a symlink to this file.

## The one rule that matters most

> ⚠️ **This repository already contains a complete, working, deployed site.**
> Your job is to *run and extend* it — never to rebuild it. Do **not** run
> `composer create-project`, scaffold a fresh Symfony skeleton, or regenerate
> `src/`, `tests/`, `config/`, or `docker/`. If something looks missing, read
> `SPEC.md` first — it is almost certainly already there.

## Container-first: never run PHP on the host

All PHP, Composer, and console commands run inside the `php` container:

```bash
docker compose up -d --build                       # start the stack
docker compose exec php composer install           # install pinned deps (never add new ones casually)
docker compose exec php php bin/console app:sync   # index /content into SQLite
docker compose exec php php vendor/bin/phpunit     # run the test suite
```

- The site is served at http://localhost:8081.
- `data/` is a bind-mounted volume; the SQLite index in it is a **derived
  artifact** — delete `data/app.db` and re-run `app:sync` to rebuild it.
- Rebuild containers after changing `docker/php/Dockerfile`:
  `docker compose build php`.
- `Makefile` wraps the deploy flow (`make update`) and works with both Docker
  and Podman — on the server, `make update` pulls, clears the cache, and
  re-indexes in one run.

## TDD-first: every change starts with a test

1. **Test-first:** express the requirement as a failing test
   (`tests/Command`, `tests/Controller`, `tests/Integration`).
2. **Implement:** write the minimum code to make it pass.
3. **Refactor:** clean up while staying green.
4. A feature without test coverage is not done. The existing suite must stay
   green — run it before you consider any task finished.

## Architecture: what lives where

- **Content** (`content/articles/`): the blog posts — Markdown + YAML
  frontmatter (`title`, `date`, `description`, `tags`). The filesystem is the
  editor. Frontmatter `description` doubles as the SEO meta description.
- **Indexing** (`src/Command/SyncCommand.php`): scans `/content`, parses via
  `src/Service/ContentParser.php`, upserts into the `search_index` table through
  `src/Repository/ArticleRepository.php`. If `AI_KEY` is set, it also stores
  1536-dim embeddings for semantic search.
- **HTTP** (`src/Controller/`): `WebController` (`/`, `/about`,
  `/article/{slug}`), `SearchController` (`GET /api/search?q=` — hybrid
  semantic/FTS), `SitemapController` (`/sitemap.xml`, rendered live from the
  index).
- **Theme** (`src/Themes/default/`): Twig templates (base, index, about,
  article, sitemap). The 404 page override lives in
  `src/Themes/default/bundles/TwigBundle/Exception/`. `base.html.twig` exposes
  `title` and `description` blocks; static assets go through the `asset_v()`
  Twig function (`src/Twig/AssetExtension.php`), which appends an mtime query
  string for cache-busting.
- **Frontend** (`public/assets/`): hand-written, no build step, no npm.
  `css/dedsec.css` is the Watch_Dogs/DedSec-style theme; `js/aleph.js` is the
  generative canvas background (it must keep respecting
  `prefers-reduced-motion` and pausing on hidden tabs); `js/lightbox.js` opens
  article images full-size. About-page imagery lives in `public/shared/img/`.
- **Config** (`config/services.yaml`): explicit wiring for `$databasePath`,
  `$contentDir`, `$siteBaseUrl`. Test overrides live in `services_test.yaml`
  / `when@test` (tests use `data/test.db`).

## How to add or edit content (step by step)

No admin panel, no API calls — content is added by writing files. Do not scan
the project to figure this out; the whole flow is:

1. **Create a Markdown file** under `content/articles/`, e.g.
   `content/articles/my-post.md`:

   ```markdown
   ---
   title: My Post
   date: 2026-08-20
   description: One-line summary (becomes the SEO meta description).
   tags: [tag-a, tag-b]
   ---

   Body in Markdown...
   ```

2. **Re-index** so the site picks it up:

   ```bash
   docker compose exec php php bin/console app:sync
   ```

3. **Verify:** the article appears on `/` and at `/article/<slug>`, and in
   `/sitemap.xml`.

Mechanics you'd otherwise have to dig out of the code:

- **The slug is the file path**, not the title: relative path with `/` replaced
  by `-`, minus `.md`. `content/articles/my-post.md` → slug `articles-my-post`
  → URL `/article/articles-my-post`. Renaming or moving a file changes its
  **public URL on hcnotes.cc** — don't rename published articles.
- **Frontmatter parsing is deliberately minimal**
  (`src/Service/ContentParser.php`): flat `key: value` pairs and inline arrays
  (`tags: [a, b]`) only — no nested YAML, no multi-line values. `date` is
  `YYYY-MM-DD`. All fields are optional; title falls back to the first `# H1`,
  then to the filename.
- **`app:sync` only upserts** — it never deletes. To remove an article, delete
  the `.md` file **and** rebuild the index:
  `rm data/app.db && docker compose exec php php bin/console app:sync`.
- The `date` drives ordering on the index page and `lastmod` in the sitemap
  (dateless articles fall back to their indexed-at time).

## Where to make changes (task → location)

| Task | Where |
| --- | --- |
| Add/edit an article | `content/articles/*.md`, then `app:sync` |
| Change page markup / layout | `src/Themes/default/*.html.twig` |
| Change the visual theme | `public/assets/css/dedsec.css` |
| Change the generative background | `public/assets/js/aleph.js` |
| Change the image lightbox | `public/assets/js/lightbox.js` |
| Change the About page | `src/Themes/default/about.html.twig`, imagery in `public/shared/img/` |
| Change the 404 page | `src/Themes/default/bundles/TwigBundle/Exception/` |
| Change routes or page logic | `src/Controller/WebController.php` |
| Change search behaviour | `src/Controller/SearchController.php`, `src/Repository/ArticleRepository.php` |
| Change indexing / frontmatter handling | `src/Command/SyncCommand.php`, `src/Service/ContentParser.php` |
| Change DB schema | `src/Repository/ArticleRepository.php` (schema is created there) |
| Add a Twig function/filter | `src/Twig/` |
| Change service wiring / parameters | `config/services.yaml` (+ `services_test.yaml` for tests) |
| Change env/config defaults | `.env` (dev), `.env.local.example` (prod template) |
| Change web server / PHP runtime | `docker/nginx/`, `docker/php/`, then rebuild containers |
| SEO surfaces | `src/Controller/SitemapController.php`, `public/robots.txt`, theme `description`/`title` blocks |

Every code change in that table comes with a test in the matching `tests/`
subdirectory (see TDD-first above) — content and pure-CSS changes don't need
tests.

## Content authoring rules

This is a real, indexed personal blog — the articles are the author's own
writing and are **not** MIT-licensed (see README). When generating or editing
articles in `content/`, the text must be **clean, plain human text** — no
artifacts that mark it as AI-generated. Such markers can get pages flagged by
AI-content detectors and ruin SEO ranking.

- **No invisible or special Unicode characters:** zero-width spaces/joiners
  (U+200B–U+200D), word joiners, soft hyphens, byte-order marks, directional
  marks, or any watermark-style characters. Use plain ASCII punctuation:
  straight quotes (`'`, `"`), hyphens, and `...` instead of curly quotes,
  non-breaking spaces/hyphens, or the ellipsis character.
- **No AI-styled formatting tics:** no emoji-decorated headings, no
  "As an AI…" phrasing, no boilerplate disclaimers, no mechanical
  bullet-with-bold-lead-in patterns unless the content genuinely calls for it.
- **Write like a person — this person:** match the voice of the existing
  articles in `content/articles/`; natural sentence rhythm and varied
  structure. The frontmatter `description` doubles as the SEO meta
  description, so it must read naturally too.
- Before indexing, sanity-check a new article, e.g.:
  `grep -nP '[\x{200B}-\x{200D}\x{FEFF}\x{00AD}\x{2060}]' content/articles/<file>.md`
  must return nothing.

## Configuration rules

- `.env` holds committed **dev** defaults (including a dummy `APP_SECRET`).
- `.env.local` (git-ignored) is the **single source of truth** on the server —
  copy it from `.env.local.example` (`APP_ENV=prod`, a real `APP_SECRET`,
  `SITE_BASE_URL=https://hcnotes.cc`).
- **Never** add app config (`APP_ENV`, `APP_SECRET`, `DATABASE_URL`, …) to
  `docker-compose.yml` as container environment variables: real env vars
  override `.env.local` and silently block production configuration.
- `SITE_BASE_URL` must be the public https origin — behind the TLS proxy the
  container only sees plain HTTP, so the request scheme can't be trusted for
  generated absolute URLs (sitemap). In `dev` mode Symfony sends
  `X-Robots-Tag: noindex` on its own, which keeps localhost out of search
  indexes.

## Conventions

- PHP 8.2+, Symfony 7 idioms: attributes for routes (`#[Route]`), constructor
  promotion, `readonly` dependencies, PSR-4 (`App\` → `src/`).
- No frontend build step and no npm — hand-written CSS/JS only, referenced
  through `asset_v()`.
- Keep new dependencies out unless there is no reasonable alternative; versions
  are pinned by `composer.lock`.
- SEO surfaces to preserve when touching themes or routes: `/sitemap.xml`,
  `public/robots.txt`, the `description` meta block, meaningful `<title>`s.
- Commits follow the conventional style visible in `git log`
  (`feat: …`, `fix: …`, `docs: …`, `chore: …`).

## Reference

- `SPEC.md` — full technical specification and the bootstrapping
  `[AGENT INSTRUCTION SET]`.
- [AICMF](https://github.com/matasarei/aicmf) — the upstream framework this
  site is built on. Framework-level improvements (indexing, search, sitemap,
  config handling) should be considered for contribution upstream; the theme,
  frontend, and content are hcnotes-specific.
