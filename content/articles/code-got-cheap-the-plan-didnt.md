---
title: Code Got Cheap. The Plan Didn't.
date: 2026-08-01
description: We built frameworks because writing code was expensive. That price collapsed. What's left expensive is the idea, the plan, and the judgement to know when the result is wrong. How to actually work that way, and why it's harder than the demos make it look.
tags: [ai, skills, frameworks, architecture]
---

> **TL;DR** — Frameworks existed because code was expensive. AI made code cheap, so
> "I built my own framework" stopped being an achievement. The scarce things now are
> the idea, the plan, and the judgement to tell when the result is wrong. Working
> that way is a skill: it feels slower for a few weeks, the agent fails in
> predictable ways, and the artifact worth writing is the *method*, not the code.
> The code still has to be readable by a human, because without understanding it
> you don't own it.

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

## The job that's left

Strip the typing out of a developer's week and look at what's still there. Someone
has to decide what's worth building. Someone has to turn that into a plan that
survives contact with the actual codebase. And someone has to look at the result
and say "no, that's wrong" before it ships. That was always the job. The typing
sat on top of it, and because the typing took most of the hours, we got used to
measuring ourselves by it.

With an agent doing the typing, those three things stop being the part of the job
you squeeze in between tickets and become the whole of it. There's nowhere left to
hide. A bad plan used to be cushioned by weeks of implementation, during which you
could quietly discover it was bad and fix it on the way. Now the implementation
takes an afternoon, and if the plan was wrong, you're holding a wrong thing by
dinner.

I've watched this happen on my own projects.
[Antigravity Companion](/article/articles-antigravity-companion-built-with-ai) is a
JetBrains plugin in a language I barely knew; I brought the idea, the knowledge of
how the IDE and the CLI needed to fit together, and the judgement to reject what
didn't work, and the agent brought the Kotlin. Nothing about that was a shortcut.
It was development with the slow part taken out.

## It's not as easy as it looks

Here's the part the demos leave out. Typing a sentence and getting an app is
real, and it is the easy part. Working with an agent day to day is a skill, and
for the first few weeks it feels *slower* than working by hand, because you're
learning a new job — planner, reviewer, editor — on top of the one you had. People
who try it for an afternoon come away either dazzled or disgusted, and neither
tells you anything. The dip is normal. It's what every new tool costs.

Start on ground you know cold: a stack you know well, a project you could have
written yourself. Not because you'll learn more — you'll learn less — but because
you have to be able to judge the output before you can learn to steer it. If you
can't tell a good diff from a plausible one, you're not developing, you're
gambling with better odds. Only once you trust your own review should you go
somewhere you couldn't have gone alone.

Then plan before you let it type. Make the agent investigate first. Make it write
down what it thinks the problem is, what it's going to change, and how you'll
both know it worked. Read that plan and fix it before a line of code exists. A
wrong plan executed brilliantly is still wrong, only faster.

Once it's coding, work in steps you can verify: one change, one test, one run,
one look. Left alone, an agent will cheerfully do forty things in a row, and step
thirty-one will be the one that quietly deleted the failing test instead of fixing
the bug. Small steps aren't slower. They're the only way to keep the thing under
control.

And learn the failure modes on sight, because there are only a handful and you'll
meet all of them in the first week: confident nonsense, tests that assert nothing,
edits in files you didn't ask about, "fixing" a problem by removing the check that
caught it, and looping on the same idea in slightly different words. The phrase
you'll use more than any other is "stop, that's wrong, go back".

## The thing worth writing is the skill

After a few weeks of that, you'll notice you keep telling the agent the same
things. Investigate before you plan. Plan before you code. Run the tests. Never
touch that directory. Write it down, put it in a file the agent reads every time,
and you've just written a **skill** — a durable, reusable instruction that makes an
agent work the way a good engineer works.

That's where my thinking has landed: modern development isn't about writing code.
It's about writing skills, and then combining them with the right idea to produce a
plan you can tune before a single line is written.

Two examples I actually use:
[grinchenkoedu/claude-skills](https://github.com/grinchenkoedu/claude-skills) and
[grinchenkoedu/antigravity-skills](https://github.com/grinchenkoedu/antigravity-skills).

At a glance they're "just another collection of skills", and fine, they are: plan,
implement, review, pr-review, pr-resolve, verify. But the point isn't the list. The
point is that the same discipline — investigate before planning, plan before coding,
review before pushing, verify by actually running the thing — is encoded once and then
enforced consistently, by different agents, on different platforms, with different
models underneath. One set targets Claude Code; the other targets Google Antigravity
running Gemini. Not identical, but close enough that the *work* comes out the same
shape. And I've proven them on real tasks, not demos.

That's the inversion. The framework used to be the reusable asset and the app was
disposable. Now the skill is the reusable asset and the code it generates is
comparatively disposable — regenerable, replaceable, cheap. What you're really
maintaining is the method.

## The part you don't get to skip

One caveat, and it's the whole thing: the code still has to be readable by a human.

Not because a human will maintain it — increasingly the maintainer *is* the AI. But
because full control is the entire game. You need to know what the app does and what
the agent just did, at every step, and what the next step is. Unreadable code is
code you can't audit, and code you can't audit is code you don't actually own. You
have a black box that currently passes tests.

The same goes for volume. An agent will happily generate more than you can read;
don't let it. Slow it down to the speed at which you could still explain every
change to a colleague. That speed is still faster than typing.

So: generate freely, but keep it plain. Keep it tested. Keep it something you could
sit down and explain to someone. The moment you stop understanding your own system,
you've traded ownership for output — and output was the cheap part.

Code got cheap. Judgement didn't. Neither did knowing where you're going.
