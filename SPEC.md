# AICMF Specification

## 1. System Architecture
- **Framework:** Symfony Flex (Micro-kernel).
- **Runtime:** PHP 8.2-FPM (Alpine).
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
│       ├── Dockerfile      # PHP 8.2-FPM Alpine image + Composer
│       └── php.ini         # Runtime settings (memory, opcache)
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
1. **Docker First:** Build and start containers before any PHP/Composer work.
   - `docker-compose build && docker-compose up -d`
   - All subsequent commands run via `docker-compose exec php ...`
2. **Bootstrap:** Initialize Symfony Flex inside the container.
   - `docker-compose exec php composer create-project symfony/skeleton . --no-interaction`
3. **Setup:** Install dependencies inside the container.
   - `docker-compose exec php composer require erusev/parsedown symfony/orm-pack twig symfony/twig-bundle`
   - `docker-compose exec php composer require --dev phpunit/phpunit symfony/test-pack`
4. **Environment:** Generate `.env.local` with `APP_SECRET` inside container.
5. **Implementation Path:**
   - [TDD] Create `tests/Integration/DatabaseTest.php` -> Setup SQLite Schema.
   - [TDD] Create `tests/Command/SyncCommandTest.php` -> Implement `app:sync`.
   - [TDD] Create `tests/Controller/SearchControllerTest.php` -> Implement `/api/search`.
6. **UI:** Setup Twig in `src/Themes/default/`.
7. **Demo:** Add `/content/articles/hello.md` and run `app:sync` inside container.

## 6. Container-First Development Rules
- **Never** run `composer`, `php`, or `bin/console` directly on the host.
- Always prefix commands: `docker-compose exec php <command>`.
- The `data/` directory is volume-mounted and persisted outside the container.
- Rebuild containers after `docker/php/Dockerfile` changes: `docker-compose build php`.
