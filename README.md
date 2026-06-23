# hcnotes

The source for **[hcnotes.cc](https://hcnotes.cc)** — my personal site and blog.

It's a black, minimalist take on the *Watch_Dogs / DedSec* aesthetic: toxic
cyan/white/pink accents, render glitches, and a fully generative background. The site
is intentionally small and completely open.

![License: MIT](https://img.shields.io/badge/license-MIT-2de2e6)
![PHP 8.2](https://img.shields.io/badge/php-8.2-777bb4)
![Symfony 7](https://img.shields.io/badge/symfony-7.x-000000)

## The generative background

The header used to ship a ~MB MP4 of Max Cooper's *Aleph 2* (visualised by
[Martin Krzywinski](https://web.archive.org/web/20230606143351/https://mkweb.bcgsc.ca/infinity/math.of.infinity.mhtml)).
It's now a dependency-free `<canvas>` reimagining: a field of natural numbers counting
upward that glitch and decay into aleph numbers (ℵ₀, ℵ₁) and set-theory symbols
(∈ ∪ ∩ ∅ ℝ ℤ ℕ ∞), then crumble into dots — the same idea, generated live in the
browser. See [`public/assets/js/aleph.js`](public/assets/js/aleph.js). It respects
`prefers-reduced-motion` and pauses on hidden tabs.

## Built on AICMF

This site runs on **[AICMF](https://github.com/matasarei/aicmf)** — an AI-first
"Context-First" content framework. The short version: the filesystem is the editor,
the database is a throwaway index, and an AI agent is the administrator. There's no
admin panel and no plugin marketplace — content is plain Markdown in `/content`, a
`bin/console app:sync` command indexes it into SQLite, and everything else is code.

hcnotes is a separate project that *uses* AICMF as its foundation and adds the theme,
the generative background, the pages, and the writing. If you want the framework
itself, go to the AICMF repo; this repo is the website. The first article,
[*Why AI-First Content Management Changes Everything*](https://hcnotes.cc/article/articles-aicmf-ai-first-cms),
explains the philosophy — and notes that this very site is its proof of concept.

## Tech stack

- **Backend:** PHP 8.2, [Symfony 7](https://symfony.com) Flex micro-kernel
- **Content:** Markdown + YAML frontmatter, parsed with Parsedown
- **Index/search:** SQLite (rebuildable from files via `app:sync`)
- **Templating:** Twig (theme in `src/Themes/default`)
- **Frontend:** hand-written CSS + vanilla JS `<canvas>` — **no build step, no npm**
- **Infra:** Docker (PHP-FPM Alpine + Nginx)

## Quick start

```bash
git clone git@github.com:matasarei/hcnotes.git && cd hcnotes
docker compose up -d --build
docker compose exec php composer install
docker compose exec php php bin/console app:sync   # index the articles
```

The site is served at **http://localhost:8081**. It boots in `dev` out of the box —
`.env` ships a dummy secret, so no extra setup is needed locally.

## Configuration & production

Configuration is read by Symfony from `.env` (committed dev defaults) and `.env.local`
(your overrides, git-ignored). `docker-compose` does **not** inject `APP_ENV`,
`APP_SECRET`, or `DATABASE_URL` as container env vars — real environment variables
would take precedence over `.env.local` and prevent switching to production. So
`.env.local` is the single source of truth on the server.

To deploy in production mode, create `.env.local` from the template and warm the cache:

```bash
cp .env.local.example .env.local      # then set APP_ENV=prod and a real APP_SECRET
docker compose up -d --build
docker compose exec php composer install --no-dev --optimize-autoloader
docker compose exec php php bin/console cache:clear --env=prod
docker compose exec php php bin/console app:sync
```

Run the tests with:

```bash
docker compose exec php php vendor/bin/phpunit
```

## Project structure

```
content/articles/     # the blog posts (Markdown + frontmatter)
public/
  assets/css/          # dedsec.css — the theme
  assets/js/           # aleph.js  — the generative background
  shared/img/          # About-page imagery
src/
  Controller/          # WebController — /, /about, /article/{slug}
  Themes/default/      # base / index / about / article Twig templates
  Command/SyncCommand  # content → SQLite indexer
  Service / Repository # parser + index access
```

## Writing a post

Drop a Markdown file into `content/articles/` with frontmatter, then re-index:

```markdown
---
title: My Post
date: 2026-06-24
description: One-line summary.
tags: [tag-a, tag-b]
---

Body in Markdown…
```

```bash
docker compose exec php php bin/console app:sync
```

The index is a derived artifact — delete `data/app.db` and re-sync any time to rebuild
from the files.

## Credits

- Background concept: *Aleph 2* by [Martin Krzywinski](https://web.archive.org/web/20230606143351/https://mkweb.bcgsc.ca/infinity/math.of.infinity.mhtml)
  & [Max Cooper](https://www.youtube.com/watch?v=tNYfqklRehM).
- Framework: [AICMF](https://github.com/matasarei/aicmf).

## License

[MIT](LICENSE) © Yevhen Matasar
