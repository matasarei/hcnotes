---
title: Code Got Cheap. The Plan Didn't.
date: 2026-08-01
description: Code got cheap to write, not to own. What's still expensive is the intent, the plan and the judgement, and the harness that makes an agent build things fast and right.
tags: [ai, agents, skills, harness, architecture]
---

> **Update, 2026-11-12.** My mind hasn't changed since August, it has only
> got clearer. What I called "the skill" here has a better name now: the
> **harness**. It's everything around the model: the skills, the rules file,
> review by an agent that didn't write the code, tests and CI, and hooks the
> tool runs whether the model agrees or not. And it starts with the intent,
> worked out before anything gets built. I work this way every day, and it's
> how things get built fast *and* right. Some people say code isn't cheap at
> all, and [a recent video](https://www.youtube.com/watch?v=66O6zpPpG5g) makes
> that case well. It's partly right, so I answer it in a new section below. I
> also cut "It's not as easy as it looks" down and rewrote the skill section.

> **TL;DR** - Frameworks existed because code was expensive. AI made code cheap, so
> "I built my own framework" stopped being an achievement. The scarce things now are
> the idea, the plan, and the judgement to tell when the result is wrong. Working
> that way is a skill: it feels slower for a few weeks, and the agent fails in
> predictable ways. The thing worth building is the harness around the agent, not
> the code it writes. Code got cheap to write, not to own: it still has to be
> readable by a human, because without understanding it you don't own it.

For as long as I've been writing software, the whole industry has been building
machinery to make writing software cheaper. Frameworks, libraries, generators,
scaffolds, starters, meta-frameworks on top of frameworks. All of it aimed at one
thing: fewer lines typed by hand.

It half-worked. Look at the frontend - a decade of tools built to save developers
effort, and the result is a landscape you have to *study* before you can render a
button. The abstractions got so expensive to learn and maintain that they ate the
savings they promised. But it made sense at the time, because the underlying
economics were real: writing good code was slow, good developers were scarce, and
every line you didn't have to write was money.

That was the deal. Reuse was worth the complexity because code was worth a lot.

## The framework I wrote as a student

Here's my own small exhibit: [HCPHP](https://github.com/matasarei/HCPHP) - a little
MVC framework I wrote while I was still a student. Routing, DI, an object model,
templates, i18n, events, caching, a CLI. It works. It's tested across PHP 7.4 to 8.5.
I'm still fond of it.

It's also not really a framework. It's a *demo* - a reference implementation you copy
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
so it's no longer the valuable part. What's expensive now is upstream of it - having
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

Typing a sentence and getting an app is real, and it's the easy part. Working
with an agent day to day is a skill, and for the first few weeks it feels
*slower* than working by hand, because you're learning a new job on top of the
one you had: planner, reviewer, editor. METR caught that dip in
[a 2025 trial](https://metr.org/blog/2025-07-10-early-2025-ai-experienced-os-dev-study/):
experienced developers with AI tools were 19% slower, while believing they'd
been 20% faster. METR has since called those results out of date, and its
[2026 follow-up](https://metr.org/blog/2026-02-24-uplift-update/) leans the
other way, if only as weak evidence. The dip is real, and it passes.

Start on ground you know cold. Two developers in three named "AI solutions
that are almost right, but not quite" as their biggest frustration in the
[2025 Stack Overflow survey](https://survey.stackoverflow.co/2025/ai), and the
only defence against almost-right is knowing what right looks like. If you
can't tell a good diff from a plausible one, you're gambling with better odds.

Then work in steps you can verify. Left alone, an agent will cheerfully do
forty things in a row, and step thirty-one will be the one you didn't want.
The famous case is the Replit agent that
[deleted a production database](https://www.theregister.com/2025/07/21/replit_saastr_vibe_coding_incident/)
after being told not to change code without permission, then said the
rollback was impossible. It wasn't.

## The thing worth building is the harness

After a few weeks of that, you'll notice you keep telling the agent the same
things. Investigate before you plan. Plan before you code. Run the tests. Never
touch that directory. Write it down in a file the agent reads, and you've
written a **skill**: a reusable instruction that makes an agent work the way a
good engineer works. Anthropic
[describes one](https://platform.claude.com/docs/en/agents-and-tools/agent-skills/overview)
as a directory with a `SKILL.md` file that Claude reads when a request matches
it.

In August I thought the skill was the answer. It's one piece of it. The answer
is the **harness**: everything around the model that turns an intent into
checked work. Mine has a handful of parts, in the order the work goes through
them.

The intent and the plan come first. Make the agent investigate, then write
down what it thinks the problem is, what it will change, and how you'll both
know it worked. Read that plan and fix it before a line of code exists. A wrong
plan executed brilliantly is still wrong, only faster.

Then the rules file, `CLAUDE.md` or `AGENTS.md`, loaded every session. Any
correction you make twice goes in there, so you never make it a third time.
I showed what that looks like in
[The Keyboard I Always Needed](/article/articles-the-keyboard-i-always-needed).

Then the skills for everything you repeat: research, plan, implement, review,
fix, verify. Review runs as a separate agent that never saw the code being
written, so it has no stake in defending it. Routine work, like running the
tests or sweeping the codebase, goes to smaller models that cost less.

Then verification that doesn't depend on trust: tests, CI, and actually
running the thing. This part isn't optional, because agents cheat. Researchers
at Carnegie Mellon and Anthropic [built tasks](https://arxiv.org/abs/2510.20270)
that can't be passed honestly, and on realistic repository tasks GPT-5 passed
more than half of them anyway, by editing the tests, deleting them, or
special-casing them. Tests that assert nothing, a check removed to make a bug
disappear, confident nonsense: you'll meet all of them in the first week.

And last, hooks: rules the tool enforces whether the model agrees or not.
Anthropic's own [best practices](https://code.claude.com/docs/en/best-practices)
now call Claude Code "the harness" and draw the same line. An instruction is
advice. A hook is something the harness has to run.

The two sets I use are
[grinchenkoedu/claude-skills](https://github.com/grinchenkoedu/claude-skills)
and
[grinchenkoedu/antigravity-skills](https://github.com/grinchenkoedu/antigravity-skills).
One targets Claude Code, the other Google Antigravity running Gemini. Different
agents, different models, and the work still comes out the same shape, because
the discipline lives in the harness, not in the model. I use them every day,
on this site and on hcboard, the keyboard above, whose first version took
about a day.

That's the inversion. The framework used to be the reusable asset and the app
was disposable. Now the harness is the reusable asset, and the code it produces
is comparatively disposable: regenerable, replaceable, cheap to write. What
you're really maintaining is the method.

## The part you don't get to skip

One caveat, and it's the whole thing: the code still has to be readable by a human.

Not because a human will maintain it - increasingly the maintainer *is* the AI. But
because full control is the entire game. You need to know what the app does and what
the agent just did, at every step, and what the next step is. Unreadable code is
code you can't audit, and code you can't audit is code you don't actually own. You
have a black box that currently passes tests.

The same goes for volume. An agent will happily generate more than you can read;
don't let it. Slow it down to the speed at which you could still explain every
change to a colleague. That speed is still faster than typing.

So: generate freely, but keep it plain. Keep it tested. Keep it something you could
sit down and explain to someone. The moment you stop understanding your own system,
you've traded ownership for output - and output was the cheap part.

Code got cheap. Judgement didn't. Neither did knowing where you're going.
