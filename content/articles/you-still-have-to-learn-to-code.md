---
title: You Still Have to Learn to Code
date: 2026-09-24
description: AI can build the app, and it will probably even work. But if you can't see how it works inside, you own a black box you can't judge, scale or maintain. Why you still have to learn to code.
tags: [ai, agents, learning, craft]
---

> **TL;DR** — AI didn't take away the need to learn to code. Agents do the
> typing, but development is still development. Hand a good plan to an agent and
> it will build the thing, and it will probably even work. The question is what's
> behind the screen: a machine with parts that each do one job, or a room of
> monkeys at typewriters that happened to produce something that runs today. From
> the outside you can't tell the difference, and if you can't tell from the
> inside either, you own a black box you can't judge, scale or maintain. Learn to
> program, and practise until what runs under the hood stops being magic.
> Without that, whatever you produce is slop, even when it works.

There's a belief I keep running into, and it's getting stronger every month:
that you describe the product, press enter, and an agent hands you the finished
thing. Working, deployed, done. One prompt, or ten, but the idea is the same. The
code is a detail the machine takes care of, so learning to write it is a waste of
time.

I understand where it comes from. When Andrej Karpathy named "vibe coding" in
[February 2025](https://x.com/karpathy/status/1886192184808149383), the line
everyone remembers is "forget that the code even exists". The part people forget
is that he was talking about throwaway weekend projects. A year later he was
the one pushing a different name for serious work, and I'll come back to what
he said.

I've written this down twice already, so briefly: agentic development is the
same development, with the typing done by a model.
[Code got cheap](/article/articles-code-got-cheap-the-plan-didnt), the plan and
the judgement didn't. And a model only follows what's in its context, which is
why [a written plan beats the one in your head](/article/articles-i-was-painting-not-programming).
The result still depends entirely on three things: your prompts, your skills,
both with AI and as a programmer, and your ability to judge what the model just
did for you. Take the middle one away and the third goes with it.

## The repository I was shown

A few days ago I talked to someone who wants to build an AI video studio. It's
a genuinely complex product, the kind that takes a team, and he has never
written code by hand. He showed me his repository: dozens of documents
describing what the product should do, how it should be organised, and how
several AI models would split the work of building it between them.

There's no code yet. There doesn't need to be at this stage, and that isn't
what bothered me. A lot of the plan is sensible. The product side is clearly
his own, thought through for a while. The technical side reads well too, in the
vocabulary a senior engineer would use.

What bothered me is who wrote the technical side, and who checks it. It was
written by a model. It gets reviewed by a model. And the final word belongs to
the one person in the chain who can't read it. The plan says what the product
will be. Nobody involved can say what the machinery behind it will be like.

I don't think he did anything stupid. He did exactly what every demo, every ad
and every "I built a SaaS in a weekend" thread told him to do. The problem is
the promise, not the person.

## What you'd be getting

Let me be fair to the plan, because it's the obvious objection. Can an agent
build this? Yes. Point it at those documents, let it work for a few weeks, and
you'll get something that runs. You'll type a story into a box and a video will
come out. It might even be a good one.

That's not the question. The question is what's behind the screen.

Every working app is one of two things inside. It's either a machine: parts
that each do one job, data moving along paths you could draw on a whiteboard,
a place where every problem naturally belongs. Or it's a room of monkeys at
typewriters that happened to produce something that works today: logic copied
into five places, three ways of doing the same thing, a fix for one bug that is
quietly holding up another. From the outside they look identical. Both answer
the demo. Both pass a quick test.

The difference shows up later, and it shows up as questions. What happens with
ten times the users? Which part falls over first? What will the next feature
touch? When something breaks at night, where do you even look? If you can open
the thing and see the machine, those questions have answers. If you can't, you
own a black box. You can't judge how well it works, you can't tell how far it
will scale, and you can't maintain it. Every fix is another prompt into the
dark, and every answer comes back with the same confidence, whether it's right
or not.

And the confidence is the trap. A language model produces the most likely text
for its context. I went through this in
[the painting article](/article/articles-i-was-painting-not-programming), so
only the short version: ask it for a professional architecture and you get text
that sounds like one, every time. Fluency is the one thing a model always
delivers, which is exactly why you can't judge the result by it. Good documents
and working code are both things it can hand you. Knowing which kind of machine
you've got is the one thing it can't.

## What's under the hood

When I say "you need to understand what runs underneath", here's what I mean,
using the ordinary shape of a product like this one.

Generating one video clip takes minutes and costs real money per call. A web
request can't wait for minutes, so the request only creates a job and returns.
Something has to hold that job in a queue. A separate worker process picks it
up, calls the provider, waits, downloads the result, stores it somewhere. The
browser has to find out it's done, which means either asking over and over or
keeping a connection open. The final film is rendered by yet another process,
on a machine that can actually handle video.

That's the picture when everything goes right. Now the questions only that
picture can answer. The worker dies in the middle: did the paid request go out
before it died, and if you send it again, do you pay twice? Two workers wake up
at once and both think the job is theirs. A hundred people press "generate" in
the same minute, and one queue, one database or one provider limit becomes the
wall everything piles up against. Which one? The disk with the half-rendered
films fills up at three in the morning.

An agent will write code for all of it, and each piece will look fine on its
own. Whether together they form a machine or a pile is something you can only
see if you know what a good one looks like. That knowledge doesn't come from
documents. You learn what a race condition is by chasing one for two days, and
you learn what a bottleneck is by watching your own system choke on one. It's
exactly what you need when an agent gives you three hundred lines and says
"done".

## What happens next, in public

This isn't a hypothetical. Here's what it looks like when the code gets
generated, it works, and the person who owns it can't see inside.

In March 2025 the founder of a small SaaS called Enrichlead posted that it had
been built with Cursor with "zero hand-written code". It worked. About two
days later: "Guys, I'm under attack". API keys maxed out, people
bypassing the subscription, junk in the database. Then, honestly: "I'm not
technical so this is taking me longer than usual to figure out". The keys had
been sitting in the frontend, and there was no real authentication. When he
asked the AI to fix it, it kept breaking other things, which is what a black box
does when you poke it. [He shut it down](https://vibegraveyard.ai/story/enrichlead-vibe-coded-saas-shutdown/).

A few weeks later, apps generated by Lovable turned out to ship without
row-level security on their databases, a setting you'd only know to check if
you knew it existed. A researcher scanned 1,645 of them and found
[170 with data anyone could read or write](https://www.superblocks.com/blog/lovable-vulnerabilities),
enough to earn a CVE. That summer Veracode tested more than a hundred models on
security-sensitive tasks, and in
[45% of cases](https://www.veracode.com/blog/genai-code-security-report/) the
model chose the insecure way when a secure one was available. The code worked.
It just wasn't safe, and working is all you can see from the outside.

And there's now a job title for the aftermath:
[vibe code cleanup specialist](https://www.indeed.com/career-advice/news/vibe-code-cleanup-specialist).
Someone who takes an AI-built prototype and turns the pile into a machine that
survives real users. It's not a junior role, because it needs exactly the
understanding the prototype was built without. (The
[Replit database story](/article/articles-code-got-cheap-the-plan-didnt)
belongs here too; I told it before.)

The common thread isn't that the AI was bad, or that the apps didn't work. They
worked. Nobody on the building side could see inside them.

## So what are the chances?

This is the question I was asked, so here's my honest answer. The odds that
someone who has never written code gets a product like this built: decent,
actually, and better every year. The odds that he ends up with something he can
trust with paying users and their files, grow, and keep alive for years,
without being able to look inside it: close to zero.

Not because the model can't type it. It can type all of it, today. The problem
is that some part of it will be wrong, or merely fragile, and nobody in the loop
will know which part. Addy Osmani called this
[the 70% problem](https://addyo.substack.com/p/the-70-problem-hard-truths-about)
back in 2024: AI gets you seventy percent of the way surprisingly fast, and the
last thirty, the edge cases, security, making it work with the real world, is
as hard as it ever was. That last thirty is also exactly the work that turns a
junior into a senior. Skip it and you never build the judgement you need for it.

This is also where I have to be precise about a word I've defended before. In
[The Alternative Is Nothing](/article/articles-the-alternative-is-nothing) I
argued against calling AI-built work slop, and I still mean it. Slop isn't "an
AI wrote it". Action Retro's Haiku port wasn't slop, because he knew the
platform, knew what booting meant, and could tell when the model was wrong. The
code was borrowed, the understanding was his. Slop is output that nobody
involved understands well enough to stand behind. A system its owner can't see
inside is slop, even when it runs. So is a working app whose owner can't tell
you where the API keys live.

## Learn to code

So here's the thesis, as plainly as I can put it: AI didn't take away the need
to learn to code. It took away the typing. What's left is understanding, and
there's still only one way to get that.

Learn to program. By hand, on small things, until the background stops being
magic: what a request is, what a process is, where state lives, what a queue is
for, what happens when something dies halfway. Then practise, because reading
about a race condition and having debugged one are different kinds of knowing,
and only the second helps when it happens for real. The goal isn't to type
faster than the agent. It's to be able to open what it built and tell a machine
from a pile.

Then use the agent, and use it a lot. But start on ground you can judge, as I
said [before](/article/articles-code-got-cheap-the-plan-didnt), and widen the
ground as your judgement grows. The agent will make you faster at everything you
understand. At everything you don't, it will only make you wrong faster.

Karpathy, the same person who coined vibe coding, calls the serious version
agentic engineering now, and he put the point better than I can
[this spring](https://karpathy.bearblog.dev/sequoia-ascent-2026/): "You can
outsource your thinking, but you can't outsource your understanding". And in
the same talk: "You are still responsible for your software, just as before".

As for the video studio: the idea may well be good, and the plan isn't wasted.
An agent may well build it, and it may well work. But without the understanding
he'll own a machine he can't open, and the first serious problem will be the one
he can't fix. The shortest road to that product goes through learning enough to
look behind the screen, or through a partner who already can.

The typing is free now. The understanding never was. You still have to learn to
code.
