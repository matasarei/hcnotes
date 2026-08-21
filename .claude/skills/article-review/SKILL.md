---
name: article-review
description: Review a blog article the way /code-review reviews code — structure, SEO, paragraph flow, start-to-end arc, missing pieces, and the project's content authoring rules. Reviews the current article (the one being worked on, or the most recently changed) or one named in the prompt. Review-only, short verdict right in the chat; never edits the file.
---

# Article Review

Review one article from `content/articles/` and report findings in chat, the way a
code review reports on a diff. Review only — do not edit the article, do not
commit, do not re-index.

## Resolve the target

1. If the prompt names an article (slug, filename, or title), review that file in
   `content/articles/`.
2. Otherwise use the "current" article: the one being worked on in this
   conversation; failing that, the most recently modified `.md` under
   `content/articles/` (check `git status` for uncommitted articles first, then
   file mtime).
3. If nothing resolves, ask which article to review — do not pick one at random.

Read the article file in full before judging anything. If it is already indexed,
also fetch the rendered page (`http://localhost:8081/article/<slug>`) to catch
rendering problems the Markdown hides (broken images, dead internal links); skip
this quietly if the stack is not running.

## Review dimensions

Judge each dimension against the existing published articles in
`content/articles/` — they are the house style, not an external standard.

1. **Structure** — is there a clear arc from hook to close? TL;DR blockquote where
   the length warrants one; headings that tell the story on their own; sections in
   an order that builds; an ending that lands rather than trails off.
2. **Flow and connection** — does each paragraph pick up from the previous one?
   Flag jarring transitions, orphaned paragraphs, repeated points, analogies that
   stack up without paying off, and anything that breaks the read from start to
   end.
3. **SEO** — frontmatter `title` (specific, not clickbait), `description` (reads
   naturally, would work as the snippet in search results), `tags`, `date`.
   A single H1-equivalent title, sensible heading hierarchy, descriptive link
   text (no bare URLs, no "here" as the only link text unless the voice calls for
   it), `alt` text on every image, a slug that won't need renaming later.
4. **Completeness** — what's missing: an unanswered question the article raises, a
   claim with no example, an image that's referenced but absent, a "so what" the
   reader is left without. Also the reverse: sections that add length but no value.
5. **Grounding** — claims about what "people say", trends, or common opinions must
   point at something real (a linked video, post, or discussion), not a vague
   "somebody somewhere". If the article gestures at a phenomenon without a source,
   flag it: either a link exists and should be found, or the claim should be
   softened to the author's own observation.
6. **Cross-article hygiene** — distinctive details, numbers, phrases, and examples
   from other articles on the site must not be reused by accident. A deliberate
   callback is a link; an unexplained repeat of another article's specific detail
   reads as a copy-paste artifact and should be made generic or made an explicit
   reference.
7. **Reads human** (from AGENTS.md — treat as a blocker, this is what keeps pages
   out of AI-content detectors) — the text must be indistinguishable from the
   author's own hand:
   - No invisible or AI-watermark Unicode: run the grep from AGENTS.md; it must
     return nothing. Plain ASCII punctuation only — straight quotes, hyphens,
     `...` — no curly quotes, non-breaking anything, or the ellipsis character.
   - Punctuation sits outside closing quotation marks (`"the craft", not
     "the craft,"`) — and whichever convention the article uses, it must be
     consistent throughout.
   - No AI-styled tics: emoji headings, mechanical bullet-with-bold-lead
     patterns, boilerplate disclaimers, "As an AI" phrasing, symmetric
     rule-of-three sentences stamped out in series, or hedging filler.
   - Voice matches the existing articles: natural sentence rhythm, varied
     structure, first person that sounds like this author.
   Also verify internal links point at real slugs and image paths exist under
   `public/`.

## Report format

Short and in the chat — no files, no artifacts. Structure the report as:

- **Verdict** — one sentence: ready to publish, needs small fixes, or needs
  rework.
- **Findings** — a ranked list, most important first, each one line or two:
  `severity (blocker / should-fix / nit)` — what and where (quote a few words so
  the spot is findable), and why it matters. Skip dimensions with nothing to say;
  never pad with praise-per-section.
- **Missing** — bullet(s) only if something genuinely is.

Keep the whole report under ~25 lines. If the article is fine, say so in two
sentences and stop — a clean review is a valid result.
