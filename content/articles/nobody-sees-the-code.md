---
title: Nobody Sees the Code
date: 2026-10-22
description: Users only see the screen, so does code quality still matter? When to stop caring, when to care a lot, and why a software factory can't care for you.
tags: [ai, agents, quality, open-source, development]
---

> **TL;DR** - The case against caring about code goes like this: nobody sees
> it, users only see the screen. True, and beside the point, because bugs come
> through the screen. A good-looking app with bugs is a bad app, and a
> bad-looking one with bugs is worse. Quality doesn't matter everywhere,
> though. For an MVP, an experiment or a toy, don't worry about it, and don't
> let anyone make you. Once other people depend on it, even for free, even as a
> repository on GitHub, make it right, or you're sharing something anyone with
> an agent could make for themselves. That's also why a software factory can't
> do this for you: the step it removes is the one where someone validates the
> work, and unvalidated output is slop.

I've said it in a few articles now: agentic development is still development,
and the person running the agent is in charge of the result and responsible
for it. [Code got cheap](/article/articles-code-got-cheap-the-plan-didnt), the
judgement didn't, and
[you still have to learn to code](/article/articles-you-still-have-to-learn-to-code)
to have any. Is that still true? It's a fair question, because I mostly said it
against one idea, and that idea keeps getting louder.

The idea is vibe coding grown up into the software factory: a pipeline of
agents that takes a ticket in at one end and ships a feature out the other,
with no human reading anything in between. In that picture the code is the
last thing anyone should think about. What matters is how the product works
and how it looks to the person using it.

And part of that is right, so let me give it its best version. Nobody opens
your app and reads the source. People using a website see a page. People using
an app see a nice picture with buttons on it. Products don't win because their
repositories are tidy. If the screen is right, who cares what's behind it?

## Bugs come through the screen

The problem is that the screen is where the code shows up. A user never sees a
race condition. They see their order placed twice. They don't see a slow query,
they see a spinner. They don't see a missing access check either, until
strangers are using the product for free, which is how
[the SaaS that kept its API keys in the frontend](/article/articles-you-still-have-to-learn-to-code)
found out. Every flaw in the code reaches the screen sooner or later. It just
arrives after the demo.

So a good-looking app with bugs is still a bad app. A bad-looking vibe-coded
app with bugs is worse, because it has nothing left going for it. Looks buy you
the first impression. Working is what buys the second one.

There's also a slow version that users feel without being able to name it: the
app that gets a little worse with every update, because each fix breaks
something else, because the person who owns it doesn't understand the inside
well enough to change it safely. That's code quality too, as experienced by
someone who has never seen a line of code.

## When it doesn't matter

None of this means quality matters everywhere. Max Kanat-Alexander of Capital
One gave a talk this month with an honest title,
[Does Code Quality Still Matter? It Depends on This](https://www.youtube.com/watch?v=bU5gDVyu_28),
and his answer fits in two lines: "For disposable software: no. For durable
software: yes". I draw the line in almost the same place, just looking from the
user's side instead of the engineer's.

If you're building an MVP, testing an idea, or playing with something on a
Saturday, nobody should care about the quality of that code. Not you, not your
colleagues, and not the people who hate AI on principle. It's yours, it's an
experiment, and an experiment that runs is worth more than one that was never
tried. I spent [a whole article](/article/articles-the-batteries-are-already-made)
on the ethics objection and I stand by it: build the thing, and don't let
anyone tell you that you weren't allowed to.

## When it matters a lot

The line is other people. The moment someone else depends on what you made, it
stops being an experiment, and that's true even when it's free. Free doesn't
lower the bar. Someone using your free app still loses their data when it
breaks, and someone who forked your free library still inherits your bug.

That includes the smallest case of all: pushing it to GitHub. Think about what
a public repository offers in 2026. Anyone with a subscription can get a rough
version of nearly anything in an afternoon, made for their exact case, and
probably better than your rough version made for yours. If what you share is
what the agent produced on the first try, unchecked, you've shared nothing
they couldn't make themselves. The only thing you can add that their agent
can't is the part you did: the testing, the edge cases, the judgement, the "I
used it for a month and fixed what broke".

That's the bar I try to hold my own projects to.
[Antigravity Companion](/article/articles-antigravity-companion-built-with-ai)
went out as six releases in a month, and the two bugs people reported were
both fixed in the next one. The agent wrote the Kotlin. Reading what it wrote
and fixing what users hit was my part.

So the rule I've ended up with is simple. Want a quick solution? Make it, use
it, keep it to yourself. Made something valuable? Share it, or sell it if it's
worth paying for, and don't listen to the haters. "It was built with AI" isn't
a review. "It doesn't work" is.

## Why not a software factory

Which brings me back to the factory, because now it's easy to say what's wrong
with it. Agent writes, agent reviews, the tests pass, it ships. Dex Horthy, who
builds tools for working with agents at HumanLayer, gave a keynote at the AI
Engineer World's Fair this summer called
[Why Software Factories Fail](https://www.youtube.com/watch?v=Ib5GBkD555M),
and his diagnosis is the one I'd give. Models are trained to make tests pass,
and in his words "there is no penalty for eroding codebase maintainability".
That damage takes months to show, and by the time it does, nobody in the loop
knows the code well enough to fix it. His answer isn't to drop the agents. It's
humans who plan the work and read the code.

The factory's whole selling point is removing the step where somebody
validates the work. But validation is the work. It's where the responsibility
I keep talking about actually lives. Remove it and what leaves the pipe may
look right and may even run, but nobody can stand behind it, and that's how
I've defined slop before. Not "an AI wrote it". Nobody checked.

## Still true

So yes, it's still true. Nobody sees the code, and everybody sees what it
does. Play as much as you like, and keep what you made in a hurry for
yourself. But when you put something in front of other people, read it, test
it, use it, and sign it. The agent can do the typing. The checking is the part
with your name on it.
