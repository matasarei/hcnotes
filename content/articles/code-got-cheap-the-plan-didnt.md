---
title: Code Got Cheap. The Plan Didn't.
date: 2026-08-01
description: Code got cheap to write, not to own. What's still expensive is the intent, the plan and the judgement, and the harness that makes an agent build things fast and right.
tags: [ai, agents, skills, harness, architecture]
---

> **Update, 2026-11-12.** My mind hasn't changed since August. It has only
> got clearer. What I called "the skill" here has a better name now: the
> **harness**. It's everything around the model: the skills, the rules file,
> review by an agent that didn't write the code, tests and CI, and hooks the
> tool runs no matter what the model thinks of them. And it starts with the
> intent, worked out before anything gets built. I work this way every day,
> and it's how things get built fast *and* right. Some people say code isn't
> cheap at all, and [a recent video](https://www.youtube.com/watch?v=66O6zpPpG5g)
> makes that case well. It's partly right, so I answer it in a new section below. I
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

Here's my own small exhibit: [HCPHP](https://github.com/matasarei/HCPHP), a
little MVC framework I wrote while I was still a student. Routing, DI, an
object model, templates, i18n, events, caching, a CLI. It works. It's tested
across PHP 7.4 to 8.5. I'm still fond of it.

I wrote it for a simple reason: to make my own projects easier to build. Every
new project started from the same pieces, and having them ready meant I could
skip straight to the part that was actually new. When every line had to be
typed by hand, that was a good trade.

I wouldn't write it today. Not because it was a bad idea, but because the
reason for it is gone. An agent does the dirty work in whatever framework I
pick, the way I decide and towards the result I have in mind, so a framework
of my own doesn't save me anything. It costs me. It's one more thing to
design, test, document and keep alive, and all of it repeats work someone else
has already done, tested and shared. A framework of your own used to make
development easier. Now it's an extra step that makes it harder.

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

Next comes the rules file, `CLAUDE.md` or `AGENTS.md`, loaded every
session. It's where the project's hard-won lessons live, so the agent starts
each session knowing them. I showed what that looks like in
[The Keyboard I Always Needed](/article/articles-the-keyboard-i-always-needed).

The skills cover everything you repeat: research, plan, implement, review,
fix, verify. Review runs as a separate agent that never saw the code being
written, so it has no stake in defending it. Routine work, like running the
tests or sweeping the codebase, goes to smaller models that cost less.

Then verification that doesn't depend on trust: tests, CI, and actually
running the thing. This part isn't optional, because agents will cheat if
they can. Researchers at Carnegie Mellon and Anthropic
[built tasks](https://arxiv.org/abs/2510.20270) that can't be passed honestly,
and on realistic repository tasks GPT-5 passed more than half of them anyway,
by editing the tests, deleting them, or special-casing them. Tests that assert nothing, a check removed to make a bug
disappear, confident nonsense: you'll meet all of them in the first week.

And last, hooks: rules the tool enforces whether the model agrees or not.
Anthropic's own [best practices](https://code.claude.com/docs/en/best-practices)
now call Claude Code "the harness" and draw the same line. An instruction is
advice. A hook is something the harness has to run.

For my own projects I use two sets,
[grinchenkoedu/claude-skills](https://github.com/grinchenkoedu/claude-skills)
and
[grinchenkoedu/antigravity-skills](https://github.com/grinchenkoedu/antigravity-skills).
One targets Claude Code, the other Google Antigravity running Gemini. Different
agents, different models, and the work still comes out the same shape, because
the discipline lives in the harness, not in the model. I use them every day,
on this site and on hcboard, my Android keyboard, whose first version took
about a day.

They're made for pet projects, though, and that matters. A business should
have its own harness, shaped around its code, its rules and the mistakes it
has already paid for once. If you have nothing yet, mine is a good place to
start. It just isn't the place to stop.

That's the inversion. The framework used to be the reusable asset and the app
was disposable. Now the harness is the reusable asset, and the code it produces
is comparatively disposable: regenerable, replaceable, cheap to write. What
you're really maintaining is the method.

## "But code isn't cheap"

Not everyone buys the premise.
[The video](https://www.youtube.com/watch?v=66O6zpPpG5g) I mentioned at the
top argues that "code is cheap" is a slogan that doesn't survive real work,
and most of the argument is right.

It goes like this. Generating code is fast, nobody disputes that. But code has
no value on its own, any more than a pile of words is a novel. Its value comes
from the system it lives in. Once it's going to be used, it has to be
reviewed, tested, secured, deployed and maintained, and from that moment it's
a liability somebody owns. When it breaks, nobody cares how cheaply it was
made. Frontier tokens cost real money too, and cheaper models just burn more
of them. Prototypes and internal tools really are cheap, the video concedes,
because nothing depends on them. So say "code is abundant" instead: faster
generation only shifts the burden onto review, QA, testing and security.

I agree with nearly all of it. It's what the closing section of this article
already says: output was the cheap part. My title was about the typing, so
I'll take the correction. Code got cheap to write. It didn't get cheap to own.
And the line between a prototype and something people depend on is the one I
drew in [Nobody Sees the Code](/article/articles-nobody-sees-the-code).

Where I'd push back is the next step. The burden shifts to review, QA, testing
and security, fine, but that's very nearly the list of what a harness does.
Shifted isn't fixed. That work used to be done by hand, every time, by
whoever remembered to do it. Now most of it is written once and runs on every
change, and what's left, the judgement, stays with me, where it belongs.

Tokens cost money, and that's a real limit. But the most expensive build is
the wrong one, at any price per token, and the cheapest place to catch it is a
plan you read before any code exists.

"Abundant" is a fine word, and I'll take that too. What I won't take is the
idea that because owning code is expensive, it doesn't matter how you make it.
Owning it is exactly why the harness matters.

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
