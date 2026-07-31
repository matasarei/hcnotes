---
title: Code Got Cheap. The Plan Didn't.
date: 2026-08-01
description: We built frameworks because writing code was expensive. That price collapsed. What's left expensive is the idea, the plan, and knowing exactly what your code does — and now the artifact worth writing is the skill, not the framework.
tags: [ai, skills, frameworks, architecture]
---

> **TL;DR** — Frameworks existed because code was expensive. AI made code cheap, so
> "I built my own framework" stopped being an achievement. The scarce things now are
> the idea, the plan, and understanding — and the artifact worth writing is the
> *skill*, not the boilerplate it produces. The code still has to be readable by a
> human, because without understanding it you don't own it.

For as long as I've been writing software, the whole industry has been building
machinery to make writing software cheaper. Frameworks, libraries, generators,
scaffolds, starters, meta-frameworks on top of frameworks. All of it aimed at one
thing: fewer lines typed by hand.

It half-worked. Look at the frontend — a decade of tools built to save developers
effort, and the result is a landscape you have to *study* before you can render a
button. The abstractions got so expensive to learn and maintain that they ate the
savings they promised. But it made sense at the time, because the underlying
economics were real: writing good code was slow, good developers were scarce, and
every line you didn't have to write was money.

That was the deal. Reuse was worth the complexity because code was worth a lot.

## The framework I wrote as a student

Here's my own small exhibit: [HCPHP](https://github.com/matasarei/HCPHP) — a little
MVC framework I wrote while I was still a student. Routing, DI, an object model,
templates, i18n, events, caching, a CLI. It works. It's tested across PHP 7.4 to 8.5.
I'm still fond of it.

It's also not really a framework. It's a *demo* — a reference implementation you copy
rather than a package you depend on. There's no Composer requirement, no version to
bump. Every app built on it forked the foundation and drifted: its own fixes, its own
little improvements, its own special reasons why file X can't be touched. After a
while, syncing anything back to the original repo is archaeology. That's not a bug in
HCPHP specifically; it's what happens to any codebase where the shared part is
copied instead of depended on.

For years that divergence was simply the cost of the approach. Reconciling three
forks by hand is a week nobody funds.

Now? It's a prompt. Point an agent at both trees, tell it exactly what "in sync"
means, and it does the boring reconciliation without complaining. The problem didn't
get solved by better architecture. It got solved by the labour becoming free.

## So the question changed

If code is cheap, you can generate as much of it as you want. You can have an agent
write you a framework this afternoon and an app on top of it by evening.

The question is: **why?**

Nothing about that is impressive anymore. Producing code is no longer the hard part,
so it's no longer the valuable part. What's expensive now is upstream of it — having
an idea worth building, and turning it into a plan that's actually right. That part
didn't get automated. If anything it got harder, because the cost of building the
wrong thing dropped to almost nothing, which means you'll build it faster and further
before you notice.

## The thing worth writing is the skill

This is where my thinking has landed: modern development isn't about writing code.
It's about writing **skills** — durable, reusable instructions that make an agent work
the way a good engineer works — and then combining those skills with the right idea to
produce a plan you can tune before a single line is written.

Two examples I actually use:
[grinchenkoedu/claude-skills](https://github.com/grinchenkoedu/claude-skills) and
[grinchenkoedu/antigravity-skills](https://github.com/grinchenkoedu/antigravity-skills).

At a glance they're "just another collection of skills," and fine, they are: plan,
implement, review, pr-review, pr-resolve, verify. But the point isn't the list. The
point is that the same discipline — investigate before planning, plan before coding,
review before pushing, verify by actually running the thing — is encoded once and then
enforced consistently, by different agents, on different platforms, with different
models underneath. One set targets Claude Code; the other targets Google Antigravity
running Gemini. Not identical, but close enough that the *work* comes out the same
shape. And they've been proven on real tasks, not demos.

That's the inversion. The framework used to be the reusable asset and the app was
disposable. Now the skill is the reusable asset and the code it generates is
comparatively disposable — regenerable, replaceable, cheap. What you're really
maintaining is the method.

## The part you don't get to skip

One caveat, and it's the whole thing: the code still has to be readable by a human.

Not because a human will maintain it — increasingly the maintainer *is* the AI. But
because full control is the entire game. You need to know what the app does and what
the agent just did, at every step, and what the next step is. Unreadable code is code
you can't audit, and code you can't audit is code you don't actually own. You have a
black box that currently passes tests.

So: generate freely, but keep it plain. Keep it tested. Keep it something you could
sit down and explain to someone. The moment you stop understanding your own system,
you've traded ownership for output — and output was the cheap part.

Code got cheap. Judgement didn't. Neither did knowing where you're going.
