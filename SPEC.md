# AICMF Specification

## 1. System Architecture
- **Framework:** Symfony Flex (Micro-kernel).
- **Runtime:** PHP 8.4-FPM (Alpine).
- **Concept:** A "Context-First" CMF (alternative to traditional CMS) where the filesystem is the editor.
- **Data Source:** `/content` folder (Markdown + YAML Frontmatter).

## 2. Mandatory File Structure
```plaintext
/
├── bin/console             # Symfony CLI
├── config/                 # Service & Route definitions
├── content/                # Article Markdown files
├── data/                   # SQLite databases (writeable, gitignored)
├── docker/
│   ├── nginx/
│   │   ├── Dockerfile      # Nginx Alpine image
│   │   └── nginx.conf      # Virtual host config (try_files → index.php)
│   └── php/
│       ├── Dockerfile      # PHP 8.4-FPM Alpine image + Composer
│       └── php.ini         # Runtime settings (memory, opcache, request hardening)
├── src/
│   ├── Command/            # app:sync
│   ├── Controller/         # API & Web routes
│   ├── Modules/            # PSR-4 dynamic extensions
│   ├── Repository/         # SQLite access
│   ├── Service/            # ContentParser (frontmatter + Markdown)
│   └── Themes/
│       └── default/        # Twig templates (base, index, article)
├── tests/
│   ├── Command/            # SyncCommandTest
│   ├── Controller/         # SearchControllerTest
│   └── Integration/        # DatabaseTest
├── public/                 # Web root
│   ├── index.php
│   └── .htaccess           # Apache fallback rewrite rules
├── docker-compose.yml
└── composer.json
```

## 3. Technical Requirements

### A. Indexing Engine (`app:sync`)
1. **Recursion:** Scan `/content` for `.md` files.
2. **Parsing:** Extract YAML Frontmatter (title, date, tags, description) and Markdown body.
3. **Database:** Upsert into `search_index` table (slug, title, content, tags, embedding).
4. **Embeddings:** If `AI_KEY` exists, generate 1536-dim vectors for the content.

### B. Hybrid Search API (`GET /api/search?q=`)
1. **Semantic:** If embeddings are present, use Cosine Similarity.
2. **Fallback:** SQL `LIKE` or FTS5 on `title` and `content`.
3. **Output:** JSON array of matching article metadata.

### C. Infrastructure & Hosting
- **Nginx:** Config lives in `docker/nginx/nginx.conf`. Serves `public/` as root with `try_files $uri /index.php$is_args$args`.
- **Apache:** `public/.htaccess` redirects all non-file requests to `index.php`.
- **Permissions:** `data/` and `content/` must be writeable by `www-data`.

## 4. Development Workflow: TDD-First
AICMF follows a TDD-First workflow for all functional logic, commands, and APIs:
1. **Test-First:** Define the requirement via a failing test (Unit, Integration, or Functional).
2. **Implement:** Write the minimum code required to pass the test.
3. **Refactor:** Optimize for readability and performance while maintaining "Green" status.
4. **Validation:** Features are considered complete only when their behavior is verified by the test suite.

## 5. [AGENT INSTRUCTION SET]
> ⚠️ **This repository already contains a complete, working implementation.**
> Your task is to **clone and run it**, *not* to regenerate it. Do **NOT** run
> `composer create-project`, scaffold a fresh Symfony skeleton, or rewrite the
> existing `src/`, `tests/`, `config/`, or `docker/` files. Cloning ≠ rebuilding
> from scratch — rebuilding wastes time and diverges from the reference code.

1. **Obtain the code:** Clone this repository (or use the working copy provided).
   All source, `composer.json`, and `composer.lock` are already present.
   - `git clone https://github.com/matasarei/aicmf.git && cd aicmf`
2. **Docker First:** Build and start containers before any PHP/Composer work.
   - `docker-compose build && docker-compose up -d`
   - All subsequent commands run via `docker-compose exec php ...`
3. **Install dependencies (do not add new ones):** Install the *pinned* versions
   from the committed lockfile.
   - `docker-compose exec php composer install`
4. **Environment:** If `.env.local` does not exist, generate one with an `APP_SECRET`:
   - `docker-compose exec php php -r "file_put_contents('.env.local','APP_SECRET='.bin2hex(random_bytes(16)).PHP_EOL);"`
5. **Build the index & demo:** `content/articles/hello.md` already ships with the repo.
   - `docker-compose exec php php bin/console app:sync`
6. **Verify:** Run the existing suite — it must stay green.
   - `docker-compose exec php php bin/phpunit`

> When **extending** the project (new features, APIs, commands), follow the
> TDD-First workflow in §4. When **bootstrapping**, you only install and run —
> the implementation, tests, Twig theme, and demo content already exist.

## 6. Container-First Development Rules
- **Never** run `composer`, `php`, or `bin/console` directly on the host.
- Always prefix commands: `docker-compose exec php <command>`.
- The `data/` directory is volume-mounted and persisted outside the container.
- Rebuild containers after `docker/php/Dockerfile` changes: `docker-compose build --pull php`.
