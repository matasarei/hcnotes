---
title: I Was Painting, Not Programming
date: 2026-09-11
description: For more than ten years I kept the solution in my head and painted it into the code in cycles of tries and debugging. With AI that stopped working. What a model actually does with your prompt, why a written plan beats a clever one, and why the same trick lets a 30B model on a laptop do real work.
tags: [ai, agents, planning, skills, local-models]
---

> **TL;DR** — Before agents, my way of building software was to hold the
> solution in my head and paint it into the code: try, run, debug, repeat,
> until the picture matched the idea. I tried the same with AI and spent my
> time explaining how I would have done it. It didn't work, and the reason is
> simple: a model can't read what's in your head, only what's in the context.
> A written plan turned guessing into following. Skills turned the plan into
> a habit I don't have to repeat. And once the work is precise and every step
> is verified by a script instead of by faith, even a 30B model running on a
> laptop can do it. That's what
> [opencode-skills](https://github.com/matasarei/opencode-skills) is for.

I want to describe how I used to develop, honestly, because I think a lot of
people who say "AI doesn't work for me" develop the same way and don't know
it yet.

## The old way

I would get a task, think about it, and arrive at a solution. The solution
lived in my head. Not on paper, not in a ticket, not in a design doc, in my
head, as a kind of picture: these classes, that flow, this is where the
state lives. Then I'd open the editor and start painting it in. Write a
piece, run it, see what happens. Write the next piece. Something breaks, so
I debug for an hour and find that the picture was wrong in one corner, fix
the corner, keep going.

It felt like programming. Looking back, it was closer to painting: many
passes over the same canvas, each one correcting the last, with most of the
time spent finding out where my own thinking had been wrong. The code was
the first place the idea was ever written down, so the code was also where
the idea got tested for the first time. Debugging wasn't fixing the
program. It was fixing the plan, late, in the most expensive place possible.

It worked well enough to make a career of. I got fast at it. And fast is
exactly the problem, because a habit that works never gets questioned.

## Same habit, new tool

When agents arrived, I did the obvious thing: I kept the habit and swapped
the typing. Solution in my head, agent on the keyboard. And the conversation
turned into something strange. I was explaining to the model how I would
have done it, step by step, correcting it when it drifted from my picture,
insisting that my way was right.

It usually wasn't. Not because the model was smarter, but because for the
first time I was forced to say the plan out loud, and said out loud it had
holes. The agent would go and do exactly what I'd described, and what I'd
described was the same half-formed picture I used to paint over for a week.
Now it got painted in ten minutes and it was wrong in ten minutes. Then
another ten, then another. The speed didn't help. It just moved the wrong
thing closer.

So the problem was never the model. The problem was that I had skipped the
plan for more than ten years and got away with it, because typing was slow enough
to hide the gap.

## What the model actually does with your prompt

This is the part worth understanding, and it's short.

A language model has one job: given everything currently in its context
window, produce the most likely next piece of text. That's it. It has no
access to your head, no memory of last Tuesday, no picture of the system
you're imagining. Its whole world is the context: the system prompt, the
files it has read, the conversation so far, and whatever you typed last.

When the context is thin, the model doesn't stop. It fills the gaps with the
most typical thing it learned in training, which is the average codebase on
the internet, not yours. Ask for "a service that syncs the content" with
nothing else and you get the median sync service ever written. Every
retry starts from a different point in that same fog, which is why the
painting approach is so maddening with an agent: you're not converging on
anything, you're sampling.

It gets worse. A chat is a sequence, and each turn is generated on top of
all the previous ones. Fill the context with three failed attempts and
corrections, and the most likely continuation is a fourth attempt in the
same style. You've taught it, inside this one session, that this is a
project where things get tried and reverted. It obliges.

A plan fixes both. Write down what the problem is, which files change, in
what order, and how you'll know it worked, and put that in the context
before any code exists. Now the most likely next token isn't the average
anymore. It's the next line of *your* plan. You've replaced guessing with
following. Same model, same weights, completely different odds. That's why
Anthropic's [guidance for Claude Code](https://code.claude.com/docs/en/best-practices)
warns against letting the agent jump straight to code: it isn't a style
preference, it's how the thing works.

## Then I tried planning

I resisted it, for the record. Planning felt like the slow, corporate part
of the job, the thing you do to satisfy a process rather than to build
anything. Two weeks in I couldn't imagine going back.

The cycle became: make the agent investigate first and tell me what it
found. Make it write the plan as a file, with steps and acceptance criteria.
Read the plan. This is where I now do the thinking I used to do in the
debugger, except it costs minutes and I can see all of it at once. Fix the
plan. Only then let it implement, one step at a time, with a test and a
look after each.

The change wasn't that the agent got better. The change was that the plan
finally existed somewhere outside my skull, where both of us could read it,
and where it got tested before the code did. Most of the debugging I used
to do simply stopped being necessary, because the thing it used to catch
got caught a stage earlier.

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
session starts with the same shape: investigate, plan, implement, review,
verify. Different agents, different models, different projects, and the
work comes out looking the same because the method is the same. That's when
it stopped feeling like a tool I was using and started feeling like a way
of working I'd switched to.

## The people it "doesn't work" for

I read the "AI doesn't work" posts, the ones I
[collected before](/article/articles-programming-is-fun-again), with some
sympathy now, because I recognise the workflow underneath most of them. It's
mine from a year ago.
Solution in the head, prompt as a paintbrush, ten quick tries, ten wrong
results, conclusion: the model is bad. They aren't lying about the results.
They're just still in the part of the story where I was.

The rule I'd offer is the one that took me the longest to see: a model gives
good results when the result is *predictable*, and bad results when it's
chaotic. Not predictable to you. Predictable from the context. If the
context contains the plan, the conventions, the files that matter and the
test that decides success, there is one likely answer and the model finds
it. If the context contains a wish and a vibe, there are a thousand, and you
get one at random. The people painting are working in the second mode and
blaming the brush.

## Why a small local model can do it too

This is where the argument stops being about the frontier models and gets
interesting, because it explains something I didn't expect: a model that
fits on a laptop can do real work, as long as the context is precise.

If quality comes from a predictable context rather than from raw
intelligence, then a much smaller model should be able to do real work,
provided two things hold. First, you tell it what to do very precisely: one
step, one file set, one definition of done. Second, every step gets
verified. Not by you reading the whole diff every time; by simple scripts
that check the facts before the model sees them and after it acts on them.

That is what [opencode-skills](https://github.com/matasarei/opencode-skills)
is. It's a set of skills for [OpenCode](https://opencode.ai) written for
local models in the 30B range, the kind that run in LM Studio or Ollama on
a decent laptop: Qwen3 Coder 30B, Prism Bonsai 27B, and so on. The same
cycle as the big-model skills, `dev-plan`, `dev-implement`, `dev-review`,
`dev-fix`, `dev-verify`, `dev-pr`, but with the reasoning the model used to
be trusted with moved into scripts.

A few examples of what that means in practice. Before a plan is written,
[a script](https://github.com/matasarei/opencode-skills/tree/main/lib)
detects the project's stack and caches it, so the model isn't guessing
whether this is PHP or Go. Another sizes each step against the context
window, so a step never exceeds what a small model can hold at once. The
test runner prints a single verdict line, `TEST PASS` or `TEST FAIL`,
instead of dumping a screen of output for the model to misread. The review
skill asks twelve yes/no questions instead of "rate this code", because a
27B model is terrible at scoring and fine at answering yes or no. And when
it quotes a line of code as evidence for a finding, a checker looks that
line up on disk and throws the finding away if the quote doesn't exist.
Hallucinated findings simply don't survive to the next step. A small plugin
blocks force pushes and pushes to the base branch, so the worst a confused
model can do is limited by something other than its own judgement.

None of that is clever. That's the point. It's the same discipline I
arrived at with the big models, plan and small steps and verification,
pushed one level down: not "the agent should verify", but "the skill
verifies, mechanically, and the agent only ever sees checked facts". A 30B
model working that way gets further than a frontier model painting.

It also closes a loop from [last week](/article/articles-the-batteries-are-already-made).
I said open models cost you capability, and they do. But how much they
cost depends on how you work. Hand a local model a chaotic context and the
gap to the frontier is enormous. Hand it a precise step with the facts
computed in advance and a script deciding whether it passed, and the gap
shrinks to something you can live with, on your own hardware, with weights
nobody can cancel.

## The short version

I used to keep the plan in my head and discover its flaws in the debugger.
That worked only because typing was slow enough to hide it. Agents removed
the typing and left the flaw in plain sight. Writing the plan down, where
the model can read it, turned a random walk into a straight line. Writing
the method down, as skills, made the straight line the default. And once
the method is precise enough and every step is checked by a script, the
size of the model matters a lot less than I thought.

Which is the last thing I'd want anyone to take from this: the top model
does not mean the best result. The best result is the one where you can
explain the idea and explain the code that came out of it, and a smaller
model, worked properly, is capable of getting you there.

The paintbrush was never the problem.
[The missing plan was](/article/articles-code-got-cheap-the-plan-didnt). It
just took a tool that couldn't read my mind to make me notice.
