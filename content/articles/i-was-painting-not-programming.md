---
title: I Was Painting, Not Programming
date: 2026-09-11
description: For more than ten years I turned business wishes into code by hand, weighing every detail as I typed. With AI that stopped working. Why a written plan beats vibe-coding, and why it lets a 30B model on a laptop do real work.
tags: [ai, agents, planning, skills, local-models]
---

> **TL;DR** - A ticket brings a wish from the business, not a plan. Before
> agents, I turned that wish into working software by hand and painted it
> into the code: write, run, debug, rename, repeat, thinking through every
> detail as I typed it, down to the variable names. That's why it worked. I
> tried the same with AI, using the agent as an editor and explaining every
> step as I went, the thing now called vibe-coding. It didn't work, and the
> reason is simple: a model can't read what's in your head, only what's in
> the context, and without the typing nobody weighs the details. A written
> plan turned guessing into following. Skills made planning part of every
> session without me repeating it. And once the work is precise, the test
> comes before the code, and every step is verified by a script instead of
> by faith, even a 30B model running on a laptop can do it. That's what
> [pi-dev-skills](https://github.com/matasarei/pi-dev-skills) is for.

I want to describe how I used to develop, honestly, because I think a lot of
people who say "AI doesn't work for me" develop the same way and don't know
it yet.

## The old way

A task never arrived empty. At work there was always a ticket, often with
docs around it. But a ticket carries what the business wants, and the
business rarely knows exactly what to build, let alone how. It has wishes,
sometimes precise, usually not. Turning a wish into a working system was
the job, and I did it like everyone around me: step by step, by hand.

So I'd open the editor and start painting it in. Write a piece, run it,
write the next. Something breaks, I debug for an hour, find the picture
was wrong in one corner, fix the corner, keep going.

What made it work was the hand. When you write every line yourself, you
think about every line. Every name, every condition, every edge case gets
weighed on the way out of your fingers. Sometimes too much: I've lost an
afternoon to naming a class, renamed it three times, and ended up with
something only a little better than the first. But that slowness is why
most problems showed up early. Ten minutes on a variable name is ten
minutes asking what it really holds, and what happens when it's empty.

It felt like programming. Looking back, it was closer to painting: many
passes over the same canvas, each correcting the last, with the details
worked out and tested inside the code. At the speed of typing, that was
affordable.

It worked, and not by accident: it's how a lot of good software got built,
mine included. I got fast at it. And that's exactly the problem, because a
habit that works never gets questioned.

## Same habit, new tool

When agents arrived, I kept the habit and swapped the typing. Ticket in
one window, agent in the other, and me in between, using the agent as an
editor: explaining each step, sharing thoughts as they came, correcting it
when it drifted from my picture. Today that's called vibe-coding, and it's
how a lot of us started.

It works badly, for a reason I had never noticed. The old way had a
reviewer built in: me, weighing every detail because I had to type it. The
agent took the typing away, and the thinking went with it. Details now
arrived a screen at a time, faster than I could weigh them. The agent built
exactly what I described, and what I described was a half-formed picture I
used to complete line by line. Now it got painted in ten minutes and was
wrong in ten minutes. Then another ten, then another. The speed just moved
the wrong thing closer.

And wrong doesn't always mean a failing test. The holes are the cases
nobody spelled out: the empty list, the retry that charges a customer
twice, the rounding that runs on every invoice. By hand, I would have
tripped over each one while typing. With an agent filling the gaps, they
compile, pass the happy path, and ship. That's how vibe-coding turns into
serious bugs, and sometimes into lost money.

So the problem was never the model. The business still hands over wishes,
and somebody still has to turn them into a plan. For more than ten years I
did that while typing. Now the plan has to be written down before the
code, where the model can read it.

## What the model actually does with your prompt

This part is short, and worth understanding.

A language model has one job: given everything in its context window,
produce the most likely next piece of text. It has no access to your head,
no memory of last Tuesday, no picture of the system you're imagining. Its
whole world is the context: the system prompt, the files it has read, the
conversation so far, and what you typed last.

When the context is thin, the model doesn't stop. It fills the gaps with
the most typical thing it learned in training: the average codebase on the
internet, not yours. Ask for "a service that syncs the content" and you get
the median sync service ever written. Every retry starts from a different
spot in the same fog, which is why painting with an agent is so maddening:
you're not converging, you're sampling.

It gets worse. Each turn of a chat is generated on top of the ones before
it. Fill the context with three failed attempts, and the most likely
continuation is a fourth. You've taught it that this is a project where
things get tried and reverted, and it obliges.

A plan fixes both. Write down the problem, which files change, in what
order, and how you'll know it worked, and put it in the context before any
code exists. Now the most likely next token is the next line of *your*
plan. Guessing becomes following. Same model, same weights, completely
different odds. That's why Anthropic's
[guidance for Claude Code](https://code.claude.com/docs/en/best-practices)
warns against letting the agent jump straight to code: it isn't a style
preference, it's how the thing works.

## Then I tried planning

I resisted it, for the record. There was already a ticket, and writing a
plan on top of it, file by file and step by step, felt like doing the work
twice. Two weeks in I couldn't imagine going back.

The cycle became: make the agent investigate first and tell me what it
found. Make it write the plan as a file, with steps and acceptance criteria.
Read the plan. This is where I now do the thinking I used to do at the
keyboard and in the debugger, except it costs minutes and I can see all of
it at once. Fix the plan. Only then let it implement, one step at a time,
and each step starts with the test, not the code: write the test that
fails, then the code that makes it pass, then look at both.

The change wasn't that the agent got better. The change was that the plan
finally existed as a file instead of as a side effect of typing, where
both of us could read it, and where it got tested before the code did.
Most of the debugging I used to do simply stopped being necessary, because
the thing it used to catch got caught a stage earlier.

## Then skills, and the world changed

After a few weeks of planning I noticed I was typing the same instructions
every session. Investigate before planning. Write the plan to a file. One
step at a time. Run the tests. Don't touch that directory. So I wrote them
down once, in a file the agent loads when the task calls for it, and that
was a skill.

I've [written about this before](/article/articles-code-got-cheap-the-plan-didnt),
so I won't repeat the argument, only the effect. A skill is the plan for
making plans. It means the discipline doesn't depend on my mood, my
attention, or whether it's the first task of the day or the ninth. Every
session starts with the same shape. Different agents, different models,
different projects, and the work comes out looking the same because the
method is the same. That's when I stopped treating it as a tool and
started treating it as the process.

## The people it "doesn't work" for

I watch the videos about AI ruining coding, the ones I
[collected before](/article/articles-programming-is-fun-again), with more
sympathy now, because under a lot of them I recognise the workflow. It's
mine from a year ago. Solution in the head, prompt as a paintbrush, ten
quick tries, ten wrong results, conclusion: the model is bad. They aren't
lying about the results. They're just still in the part of the story where
I was.

The rule I'd offer is the one that took me the longest to see: a model gives
good results when the result is *predictable*, and bad results when it's
chaotic. Not predictable to you. Predictable from the context. If the
context contains the plan, the conventions, the files that matter and the
test that decides success, there is one likely answer and the model finds
it. If the context contains a wish and a vibe, there are a thousand, and you
get one at random. The people painting are working in the second mode and
blaming the brush.

## Why a small local model can do it too

This is where it gets interesting, because it explains something I didn't
expect: a model that fits on a laptop can do real work.

If quality comes from a predictable context rather than raw intelligence,
a much smaller model should manage, as long as two things hold. You tell it
exactly what to do: one step, one set of files, one definition of done. And
every step gets verified, not by you reading every diff, but by simple
scripts that check the facts before the model sees them and after it acts.

That is what [pi-dev-skills](https://github.com/matasarei/pi-dev-skills)
is: a harness for the [Pi coding agent](https://pi.dev), built for local
models in the 30B range, the kind that run in LM Studio or Ollama on a
decent laptop. Skills, the scripts behind them, and Pi extensions that
guard and repair what the model does. The same cycle as the big-model
skills, `dev-plan`, `dev-implement`, `dev-review`, `dev-fix`, `dev-verify`,
`dev-pr`, with the reasoning the model used to be trusted with moved into
scripts.

In practice, [a script](https://github.com/matasarei/pi-dev-skills/tree/main/lib)
detects the project's stack and caches it, so the model isn't guessing
whether this is PHP or Go. Another sizes each step to fit the context
window. The test runner prints one verdict line, `TEST PASS` or
`TEST FAIL`, instead of a screen of output to misread. The review asks
twelve yes/no questions instead of "rate this code", because a 27B model is
terrible at scoring and fine at yes or no. When it quotes a line of code as
evidence, a checker looks the line up on disk and drops the finding if the
quote doesn't exist. And an extension blocks force pushes and pushes to the
base branch, so a confused model is stopped by something other than its own
judgement.

None of that is clever, and that's the point. It's the same discipline I
arrived at with the big models, pushed one level down: not "the agent
should verify", but "the harness verifies, and the agent only sees checked
facts". A 30B model working that way gets further than a frontier model
painting.

It also closes a loop from [last week](/article/articles-the-batteries-are-already-made).
I said open models cost you capability, and they do. How much depends on
how you work. Hand a local model a chaotic context and the gap to the
frontier is enormous. Hand it a precise step, facts computed in advance and
a script deciding whether it passed, and the gap shrinks to something you
can live with, on your own hardware, with weights nobody can cancel.

## What makes it work

Strip away the tools and the model names, and what's left is a way of
working. None of it is new: it's what good teams always said they did and
rarely had time for. With an agent, it stops being optional.

It starts with the cycle: investigate, plan, implement, review, fix what
the review found, and verify by actually running the thing. Each phase
leaves a file the next one reads, and nobody skips ahead, not the agent and
not you. The plan is read before the code exists, the review before the
push.

Inside each step, the test is written first and fails first (TDD), and
only then comes the code that makes it pass. A failing test is the most
precise instruction a model can get: a definition of done it can't argue
with. It also blocks the most common cheat. An agent that writes the code
first will happily write a test that asserts whatever the code does. A test
written first has no code to flatter.

Keep the steps small: one step, one diff short enough to read in full, one
run. A step too big for your head is too big for the model's context, and
the same mistakes creep in on both sides.

The conventions live in a file the agent loads every time, not in your
memory of the last session. Correct the same thing twice, and it belongs
there.

Verification is done by script, not by trust. Whether a test passed,
whether a quoted line exists, whether it's the right branch: these are
facts, and facts get checked mechanically before the model reasons about
them.

And you read everything, at the speed of your own understanding. I've
[made that case already](/article/articles-code-got-cheap-the-plan-didnt):
code you can't explain is code you don't own, and no script can check that
for you.

That's the last thing I'd want anyone to take from this: the top model
doesn't mean the best result. The best result is the one where you can
explain the idea and the code that came out of it, and a smaller model,
worked properly, can get you there.

The paintbrush was never the problem.
[The unwritten plan was](/article/articles-code-got-cheap-the-plan-didnt). It
just took a tool that couldn't read my mind to make me notice.

> **Update, 2026-11-14.** When I wrote this, the skills for local models
> ran on OpenCode, as opencode-skills. They have since moved to the
> [Pi coding agent](https://pi.dev) and grown from a set of skills into a
> harness, [pi-dev-skills](https://github.com/matasarei/pi-dev-skills). The
> reason is Pi's extensions: plain code that runs around every command and
> every tool call, so the process is driven by code, not by the model. Facts
> are computed before the model sees the prompt, one step per run is
> enforced rather than asked for, and a broken edit or a loop of tool calls
> is caught before it does any damage.
