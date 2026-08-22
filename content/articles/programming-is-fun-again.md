---
title: Programming Is Fun Again
date: 2026-08-21
description: People keep saying AI agents killed the joy of writing code. I think they're mourning a hobby, not a job — and agents are how I got the hobby back.
tags: [ai, agents, burnout, craft]
---

> **TL;DR** — "Programming was fun before AI" is true only if your programming was
> mostly experiments and learning. The everyday reality of an enterprise engineer —
> framework version drift, cross-service bug hunts, the same rituals on repeat — was
> never the fun part; it's the part that burned people out of the profession. Agents
> took that part. What's left over is time and energy, and I'm spending both on
> building things I'd never have attempted before. My contribution graph agrees.

Every week my YouTube feed hands me the same eulogy —
["AI Is Killing The Joy of Coding"](https://www.youtube.com/watch?v=Aa7_WVakGSA),
["Coding isn't fun anymore"](https://www.youtube.com/watch?v=nesGfLhgJdk),
["I Don't Think I'll Ever Enjoy 'AI' Coding"](https://www.youtube.com/watch?v=EqqPOky-Nv8) —
and the written kind exists too, from
[blog posts](https://alexn.org/blog/2025/10/27/ai-sucks-the-joy-out-of-programming/) to a whole
[Ask Slashdot](https://developers.slashdot.org/story/24/11/15/2138239/ask-slashdot-have-ai-coding-tools-killed-the-joy-of-programming)
about it. The message is always the same: the agents took the craft, writing code
by hand was the joy, and now we just review diffs.

I understand the feeling. I even think it's honest. But I think it describes a very
specific kind of programming — the kind where you pick the problem, the stack, and
the pace. Experiments, toys, learning a language by building something small and
pointless. That programming *was* fun, and if agents took it from you, you're allowed
to grieve.

That's just not the programming most of us were doing for a living.

## What the job actually is

Here's the enterprise version of "the craft", the one I've done for years: the same
ceremonies every day, across multiple projects on multiple versions of the same
frameworks, each buried under layers of business logic that made sense to someone,
once, in a meeting nobody minuted. And the bugs — not the fun kind you make and fix
at your own desk, 
but [the floating kind](https://about.roblox.com/newsroom/2022/01/roblox-return-to-service-10-28-10-31-2021/), 
the one that lives in a *different*
microservice and only shows itself under the right traffic at the right moment, so
debugging becomes a stakeout: instrument everything, wait, and hope you're watching
when it surfaces.

None of that is craft. It's boring, frustrating, and hard at the same time — the
worst combination, because hard-and-interesting energizes you while
hard-and-boring just grinds you down. It's exactly why so many good engineers didn't
move to management or product for the money; they *escaped*. Programming is fun
until it's a full-time job plus overtime. After that, the hobby is gone too: you come
home with an empty battery, and the pet project folder becomes a graveyard of
`git init` and one commit.

So when someone tells me the fun ended in 2024, I want to ask: which fun? The
stakeout? The fourth identical migration this quarter? Because from where I sit, the
fun didn't end with agents. It ended years earlier, quietly, when the job ate the
hobby. Agents are how I got it back.

## The heatmap doesn't lie

This is my GitHub graph for the last year:

<div class="shots">
  <img src="/shared/img/funagain/contributions.png" alt="GitHub contribution heatmap: 2,105 contributions in the last year, getting visibly denser from May onward" loading="lazy">
</div>

Watch what happens from May onward. That's not me working more hours — that's the
boring half of the work being delegated. While an agent grinds through the tedious
part of the day job, I have attention left over, and it goes into things I would
simply never have started before: too much setup, too much unfamiliar territory, too
many evenings of debugging between me and the interesting part.

The freshest example is [wow-launcher](https://github.com/matasarei/wow-launcher) —
a native macOS wrapper that runs a 2010 Windows game at full speed on Apple Silicon,
built in a few days, mostly *in the background* while I did other things. I
[wrote up the whole story](/article/articles-my-own-private-azeroth). The honest
alternative wasn't "the same project, slower". The alternative was weeks of wine
debugging I would never have signed up for, because that's exactly the
hard-and-boring work I just spent a career learning to dread. The project wouldn't
exist. That's the real accounting: not "AI wrote my code", but "this thing exists
instead of not existing".

## You're the architect, not the accessory

The other half of the eulogy is the fear of becoming an appendix to the machine —
the human who clicks "approve" on someone else's thinking. That fear gets the roles
backwards.

The agent types faster than me, reads more than me, and never gets tired of yet
another verification check. What it doesn't have is intuition — the quiet certainty that an
answer is wrong before the proof exists — and it doesn't carry responsibility.
Both of those stayed with me. I've [written before](/article/articles-code-got-cheap-the-plan-didnt)
that code got cheap while the plan didn't; this is the same inversion seen from the
inside. The thinking, the taste, the refusal to accept a bad answer — that's the
part of the job that was always the actual craft, and it's the part I now get to do
almost full-time, because the typing is handled.

Because being a developer was never about typing code in the first place. Nobody
hires you to produce lines; they hire you to deliver working software — features
that ship, bugs that stay fixed, systems that hold up under real users. Typing was
just the slowest step between the idea and the delivery, and we romanticized it
because for decades it was the only way through. The developers who measure
themselves by keystrokes were always measuring the wrong thing. Measure the
delivered software, and the agents don't diminish the job — they finally let you do
it at the speed you think.

Put it in military terms: on your own you're a soldier — a good one, maybe, but
one pair of hands, one fight at a time. With AI you're a whole army. And an army
doesn't make the general obsolete; it's the reason the general matters.

Or, in the only format the internet truly respects:

<div class="shots">
  <img src="/shared/img/funagain/two-sides.jpg" alt="'I have two sides' meme: Neo fighting bare-handed labeled WITHOUT, Neo with a helicopter minigun labeled WITH CLAUDE" loading="lazy">
</div>

The bare-handed fight is noble. It's also optional now. I spent enough years at it
to feel no guilt at all about reaching for the minigun — and about enjoying
programming again, on my own terms, for the first time in a long while.

## What to try next

If you're the one writing the eulogy: don't judge agents by a bad diff on your day
job. Dig out the pet project that's been sitting in your folder as `git init` and
one commit, hand the boring half to an agent, and keep the decisions for yourself.
If a dead project comes alive in a weekend and you still feel nothing, fine — the
grief was real. But I don't think that's how it ends.

If you're just starting and know nothing: the agent didn't free you from
understanding — that's now the whole job. Use it as a tutor that never gets tired
of your questions: build something real from day one, and make it explain every
line until you could explain it back. Code you can't read is code you don't own,
and an architect who can't judge the work is just an accessory with a title.

Either way, don't take my word for it — the experiment costs a weekend. Mine gave
me back a hobby I thought the job had eaten for good.
