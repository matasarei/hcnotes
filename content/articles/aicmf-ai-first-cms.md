---
title: The CMS Didn't Need to Get Smarter. It Needed to Get Out of the Way.
date: 2026-06-23
description: The operator of a content system is now an AI agent, not a person in a dashboard. Why that makes admin panels and plugin marketplaces obsolete, and AICMF, the framework I built for it.
tags: [aicmf, ai, cms, architecture]
---

> **TL;DR** — AICMF is an AI-first content framework: the filesystem is the editor,
> the database is a throwaway index, and an AI agent is the administrator. There is
> no admin panel and no plugin marketplace — the agent builds exactly what you need,
> directly from files and code. This site is the first one built on it.
> Source: [github.com/matasarei/aicmf](https://github.com/matasarei/aicmf).

I've spent a good part of my career around content systems built for *humans
clicking buttons*. Admin panels, WYSIWYG editors, role matrices, plugin
marketplaces — entire ecosystems designed around the assumption that a person logs
in, navigates a UI, and pokes at a database through a dozen layers of abstraction.

That assumption quietly expired. The primary operator of a modern content system
isn't a person in a dashboard anymore; it's an AI agent with a terminal, a
filesystem, and the ability to read and write code. **AICMF** is my attempt to
build for that operator first.

## What "AI-first" actually means

It's not a chatbot in the corner of your admin panel, and it's not
"smart suggestions". AI-first is an architectural stance, and it comes down to
three decisions:

- Content lives as plain Markdown with YAML frontmatter in `/content`, which makes
  the filesystem the editor. An agent doesn't need a REST API to "create a post" —
  it writes a file, the way it already writes code.
- The database is just an index. SQLite is a derived artifact, rebuilt from the
  source of truth (the files) with a single `app:sync`; nothing precious lives only
  in a table you can't `grep`.
- And the administrator is the AI itself. Schema changes, content migrations, new
  features — these are code and content operations, exactly what agents are good
  at. There is no admin UI to maintain because there is no human admin to serve.

When the system's native interface is *text and code*, the most capable operator on
the planet — an LLM — becomes a first-class citizen instead of a guest squinting at
a UI built for someone else.

## Why this matters now

Three things changed at roughly the same time. Agents got genuinely good at
codebases: they read repos, run commands, and edit files reliably, so a system
whose entire state is files-and-code is a system an agent can fully operate — not
80% of it through an API and the rest through clicks. The economics of a UI
flipped: an admin panel used to *save* time, and now it's a tax — a surface to
build, secure, document, and keep in sync with the data model, all to mediate
access that an agent would rather have directly. And the content work itself moved
upstream: drafting, summarizing, tagging, translating, semantic search — these are
model operations now, and a CMS that treats AI as an add-on is piping the main
event through a side door.

## The old way feels like the stone age

Open a traditional CMS and count the layers between an idea and a published page:
log in, find the right section, open a modal, fill a form, fight the rich-text
editor, set fifteen fields, hit save, hope the cache clears. Every one of those
steps exists to protect a human from a database. None of them help an agent — they
actively get in the way.

The classic CMF isn't much better. It hands you a framework, sure, but the mental
model is still *human-operates-UI-operates-data*. You spend your time wiring
controllers and templates so a person can do by hand what an agent could do in one
shot if you'd just let it touch the files.

It's not that these systems are bad. They're optimized for a world that no longer
exists — the world where the smartest, fastest operator you had was a person with a
mouse. That's the stone age now. We have a better operator, and it speaks code.

## And plugins became a liability

There's a second reason the old model is breaking, and it's about trust. Every plugin
and theme you install is third-party code running inside your site with full access to
your data and your users. The supply chain *is* the attack surface now: a compromised
dependency, or an abandoned extension that quietly changes hands — the way the
[Display Widgets plugin](https://www.wordfence.com/blog/2017/09/display-widgets-malware/)
was sold off and came back to its 200,000 installs carrying spam-injection code.
"Just install a plugin" used to be convenient advice. Today it means blindly running
code you've never read and can't fully vet.

And even when a plugin is perfectly safe, it's rarely what you actually wanted. Paid
extensions survive by trying to be everything for everyone — every toggle, every edge
case, every integration bolted on to justify the licence. You inherit all of that
bloat to use the ten percent you needed, and that ten percent never quite fits,
because it was built for a thousand other sites, not yours.

The AI-first answer sidesteps both problems. You don't shop for the closest-fitting
black box — you describe what you want and the agent writes it: exactly that feature,
exactly your way, as code you own, can read, and can change. The capability you used
to rent from a marketplace is now something you simply ask for and keep.

## What you get when you stop fighting it

Strip out the admin panel and a CMS becomes almost embarrassingly small:

- Content is `git`-versioned Markdown. Diffs are readable. Rollback is `git revert`.
- The index is disposable and reproducible. Lose it? Run `app:sync`.
- Search is hybrid by default — full-text when you need exact matches, semantic
  embeddings when you need meaning.
- Extending it is writing code, not learning a plugin API invented to avoid writing
  code.
- The whole thing is portable: it's just files, a tiny database, and a micro-kernel.

There's no admin CRUD to babysit and no migration theater to sit through. The agent
reads the spec, edits the files, runs the tests, and ships.

## This very site is the proof of concept

You're reading it. **hcnotes.cc is the first site built on AICMF** — not a demo
deployment, the real thing. I built it to answer one question: if the AI is the
primary operator, how fast can you go from a spec to a finished, running site
without giving up quality?

The answer turned out to be *very* fast. The whole stack — the generative animated
background, the redesigned front end, this article, the content pipeline that indexed
it — came together in a fraction of the time a traditional CMS build would take, and
the result isn't a compromise. It's the best version I could have shipped, reached in
the least time, because the agent operated the system directly instead of fighting
through layers built for someone else.

That's the entire claim of AICMF, and this site is the evidence: when the
architecture stops getting in the agent's way, "fast" and "good" stop being a
trade-off.

AICMF is open source — read the spec, clone it, run it:
[github.com/matasarei/aicmf](https://github.com/matasarei/aicmf).

## The bet

AICMF is my bet that the interface to content is converging on the same interface
as the interface to software: a repository an intelligent agent can read, reason
about, and change. Build for that operator and the rest — the panels, the forms,
the plugin economy — falls away as accidental complexity from an older era.

The CMS didn't need to get smarter. It needed to get out of the way.
