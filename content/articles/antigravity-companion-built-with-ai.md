---
title: Two Bugs in 2,000 Lines — A JetBrains Plugin Built by AI
date: 2026-05-21
description: Antigravity Companion is a JetBrains IDE plugin I built almost entirely with an AI agent, in a few spare days. Six releases, ~2,000 lines, two bugs. The numbers say a lot about where development is heading — and what still can't be skipped.
tags: [ai, jetbrains, plugin, antigravity]
---

> **TL;DR** — I built [Antigravity Companion](https://plugins.jetbrains.com/plugin/31899-antigravity-companion),
> a JetBrains IDE plugin, almost entirely with an AI agent — in a few spare days,
> on the side. Six public releases, ~2,000 lines of Kotlin, and exactly **two**
> reported bugs across the whole codebase: roughly one defect per thousand lines.
> AI made it fast. Experience is what made it *work*. Source:
> [github.com/matasarei/antigravity-companion](https://github.com/matasarei/antigravity-companion).

After the [AICMF site](/article/articles-aicmf-ai-first-cms), here's a second data
point — a real, published product this time, not a personal site.

[Antigravity Companion](https://plugins.jetbrains.com/plugin/31899-antigravity-companion)
is a plugin for JetBrains IDEs (IntelliJ IDEA, PhpStorm, WebStorm, PyCharm, GoLand,
and the rest). It bridges the Antigravity CLI — `agy` — with the IDE through a small
local MCP server, so the agent can see your active file, selection, open tabs, and
inspection diagnostics on demand. There's a toolbar button that launches `agy` in an
IDE-embedded terminal wired to the current project, and a tool window that lists the
plans and summaries the agent generates. It's free, MIT-licensed, and at the time of
writing it has been downloaded over 3,700 times.

I built nearly all of it with an AI agent.

## Built in the cracks of other work

This wasn't a project I sat down and dedicated a sprint to. It came together over a
**few days, in spare moments**, while my real attention was on other things. I
described what I wanted, the agent wrote the Kotlin, I reviewed and steered, and we
iterated. The first commit and the first public release landed on the same day.

That cadence used to be unthinkable for a plugin on a platform as particular as the
JetBrains SDK — deprecations, threading rules, tool windows, terminal integration,
settings panels, keymaps. Plumbing that normally eats a weekend each. Here it was a
conversation.

## The numbers

I went back and checked the actual record, because vibes aren't evidence.

- **Six public releases** — `v1.0.1` through `v1.3.2`, all shipped between
  **21 May and 21 June 2026**. A month of steady, small, confident iterations.
- **~2,000 lines of source** — about 1,970 lines of Kotlin and Java (1,785 of them
  non-blank). The whole MCP service is a single 1,210-line file; everything else is
  UI, settings, and glue.
- **Two bugs. Total.** Across the entire public life of the plugin, exactly two
  issues were ever filed: a destructive fallback when `mcp_config.json` was malformed
  (#3), and a blank artifact viewer in PyCharm (#7). Both were real, both were fixed
  in the very next release.

Put the bugs against the code and you get a defect density of roughly **one bug per
thousand lines** — or, if you prefer the optimistic framing, about **99.9% of the
codebase shipped without a single reported defect**. For typical software that ratio
would be a good year. Here it was a side project.

So as a raw proof point, it's blunt: an AI agent can take a non-trivial, platform-
specific product from nothing to thousands of users, at near-professional quality,
in the gaps of a normal work week. That's not a demo. That's the new baseline.

## But the numbers hide the part that matters

Here's what I want to be honest about, because the hype usually isn't.

**This is not "anyone can do it now."** It looks effortless in the changelog, but the
reason it went smoothly is that I knew exactly what I was steering toward. Ten-plus
years of building software is doing quiet work behind every one of those clean
releases:

- I could read the agent's Kotlin and tell good from plausible-but-wrong.
- I knew what an MCP bridge, an IDE tool window, and a login shell actually are — so I
  could describe the *right* thing instead of a vague wish.
- I caught the destructive-config bug being a problem worth a "critical" fix, because
  I've been burned by exactly that class of mistake before.
- I knew what "done" and "safe to ship" look like, which versions to cut, and how the
  release and compatibility ranges work.

Take that context away and the same agent produces something that compiles, demos
once, and quietly corrupts a config file in the field. The AI didn't replace the
experience — it **multiplied** it. It removed the typing, the boilerplate, the
API-spelunking, the deprecation chase. What it couldn't remove was the judgement about
what to build, how it should behave, and when it's actually correct.

## The takeaway

Two articles, two proofs, one conclusion. AI agents have genuinely changed what one
person can ship and how fast — a CMS and a published IDE plugin, both in days, both at
a quality I'd stand behind. But the lever still needs a hand that knows where to push.
The barrier didn't disappear; it moved. It's no longer "can you write all this code?"
It's "do you understand the system well enough to direct something that can?"

I do, because I spent a decade learning to. That part doesn't come from a prompt.
