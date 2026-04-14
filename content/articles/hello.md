---
title: Hello World
date: 2026-04-14
description: A welcome article demonstrating the AICMF content system.
tags: [welcome, demo, aicmf]
---

# Hello, AICMF!

Welcome to the **AI Context-First Micro-Framework** (AICMF). This is a demo article
showing how content is authored in plain Markdown with YAML frontmatter.

## Features

- **Filesystem-as-editor:** Drop `.md` files into `/content` and run `app:sync`.
- **SQLite indexing:** All content is indexed into a lightweight SQLite database.
- **Hybrid Search:** Full-text search with optional AI-powered semantic search.

## How It Works

1. Write your articles in Markdown.
2. Add YAML frontmatter for metadata (title, date, tags, description).
3. Run `bin/console app:sync` to index content.
4. Access via the web UI at `/` or the search API at `/api/search?q=your+query`.

```bash
docker-compose exec php bin/console app:sync
```

That's it — no database migrations, no admin panels, no bloat.
