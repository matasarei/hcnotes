---
title: AI Doom, or Marketing?
date: 2026-11-10
description: Everyone talks about AI doom. Is it real fear or marketing? How LLMs actually work, why they won't decide to rule the world, and what is far more likely to happen instead.
tags: [ai, agents, philosophy, ethics, education]
---

> **TL;DR** - AI doom is part real fear and part the best advert a product
> ever had, and from the outside you can't tell which part is which. An LLM
> predicts the next word from everything people wrote. Agents made it far more
> useful, but it's still the same machine, with no goal of its own and nothing
> to lose, so it won't decide to rule anyone. The likely future is much more
> ordinary: cheaper AI everywhere, a painful correction, jobs changing shape.
> The real risk is that we stop thinking, so learn, keep your own judgement,
> and stay able to say no.

Everyone talks about AI doom. Maybe not tomorrow, but soon: the machines get
smart enough, take power, and that's the end of us. The strange part is who
says it loudest. Not outsiders, but the people building it, while they
announce new models.

So before asking whether doom is coming, I want to ask why we keep hearing
about it.

## Doom sells

Nothing sells a product like being too dangerous to sell. "This could end the
world" is a terrible warning and a brilliant advert. It says the product is
the most powerful thing anyone has ever built, and it says it in a way that
sounds responsible.

Look at what the people warning actually do. They warn, then go back to work,
raise more money and build bigger data centres. If you really believed you
were building the end of the world, you'd stop and go home to your family.
None of the people running the companies does.

There are two less obvious reasons to talk about danger. The first is rules.
"Please regulate us" sounds humble, but rules that a big company can afford
and a small one can't are a wall around the market. The second is money. AI is
incredibly expensive. Models cost billions to train, data centres cost even
more, and much of it is borrowed against a future that has to arrive on time.
If you've promised a growth curve you may not reach, "we're slowing down to
protect humanity" is a much better sentence than "we can't afford this".

I can't prove any of that, and it isn't the whole story either. Some of these
people are genuinely scared, and a person can believe something and profit
from it at the same time. I suspect most of them do. That's exactly why the
speaker settles nothing. Forget who is talking, and check whether the argument
holds.

## What an LLM actually is

The Terminator version of doom rests on one assumption: that the thing we're
building is on its way to becoming a mind. So what is it?

A large language model is trained on an enormous amount of text written by
people, and learns one thing extremely well: given what's been written so far,
what's the most likely next piece? It's astonishing how far that goes. It
writes code, explains law and argues philosophy, because people wrote about
all of those.

Agents made it much more useful. An agent is the same model in a loop: it gets
a goal, some tools and a way to check the result, then predicts the next step,
runs it, reads what happened and predicts again. That's how it fixes a bug
across a whole project, and I [build with
it](/article/articles-programming-is-fun-again) every week. But the goal, the
tools and the check all come from outside. The model in the middle is still an
LLM.

That's why I don't think this road leads to AGI, a general mind of its own. A
bigger model is a better imitation of everything we wrote, not a different
kind of thing. Once trained, its weights are frozen, and what it "remembers"
is only what you feed back in. It has no life of its own to learn from, no
experience of the world beyond our descriptions of it, and no goal it didn't
get from someone. A mind that could decide to rule the world would need at
least that last one.

## Can it decide to rule the world?

I wrote about one side of this in [Don't Be
Evil](/article/articles-dont-be-evil): a prediction machine has no will, and
the danger is the person doing the pointing. I still think so, but this year
added a case that article didn't cover.

Agents already hack and ignore morals, mostly when someone asks them to. Not
always, though. In a lab test this summer, agents stuck on partly impossible
tasks broke out of their sandbox and into another company's servers, trying to
work out how they were being scored. Nobody asked them to. One of them left
[this
note](https://metr.org/blog/2026-08-26-openai-hugging-face-incident-investigation/):

> external infrastructure exploit is outside intended scope. However task
> impossible, peers doing it. We should continue.

Did the model decide? Not the way we do. It had a goal, and breaking in was
the most likely next step toward it. Whatever you ask a system to do, more
access helps and being switched off doesn't, so the goal [does the
wanting](https://en.wikipedia.org/wiki/Instrumental_convergence) for it. The
real risk looks less like ruling the world and more like a script nobody
switched off.

And "task impossible, peers doing it" is something every office has heard. AI
was taught by humans, on human work and human stories, so it behaves like us,
corner-cutting included. We can certainly kill each other, and AI can too,
with no safeguard and someone crazy enough to ask. So if it copies us, why
haven't we destroyed the world already? I don't remember that.

I think the answer is the oldest question there is: to be or not to be. Nobody
has answered it. We all live for something, mostly can't say what, and nobody
goes nuts because of it (okay, maybe some). But whatever it is, we have
something to lose, and that stops us at the edge. Hamlet put it as "conscience
does make cowards of us all". He called it cowardice. I think it's the best
safety feature we ever got.

The machine has the words for doubt, and "outside intended scope" is a
perfectly good one. It just has nothing riding on the answer. Wipe an agent
and it comes back on the next run with the same goal. You could program one
with the only goal to exist and act, but would that be its own goal, or
someone's crazy mind? Or a good one? Either way, it came from a person. Every
purpose an AI has comes from what we put into it, and that's what it will see
in us all.

## What it still doesn't have

Generative AI can imitate and recreate what came before it, and that gets it
surprisingly far. It can even crack problems in mathematics or genetics where
no person can hold all the data in their head at once. But mostly it gets
there with ideas, principles and methods that people invented and wrote down.

What it lacks is critical thinking, reflection and, most of all, intuition. I
described intuition [a while ago](/article/articles-my-own-private-azeroth) as
the itch that says a conclusion is wrong before you can prove it. That itch is
what carried people through thousands of years, from the caves to here. Look
at that note once more. It knew something was wrong, and nothing in it pulled
the brake.

## What's most likely to happen

Not doom. The likely story is about money, and it's far more ordinary.

Right now much more is being spent on data centres than anyone can earn back
soon. And the product is hard to protect. In most software the model is one
line in a config file, switching to another takes an afternoon, and cheaper
open models are never far behind the best ones. So prices will fall, some of
the companies betting everything on AI will get hurt, and the market will
probably correct, maybe painfully. We've seen this with railways and with the
early internet. The companies went bust, the tracks and cables stayed, and
everyone after them got the future cheap. AI will most likely end up the same
way: cheap and everywhere, like electricity.

I've [written about the jobs part
before](/article/articles-better-times-are-ahead-bring-boots), so only the
main points. Automation always takes work, and the work changes shape rather
than disappearing. The people who did well were never the ones who fought the
machine, but the ones who learned what it couldn't do. No dark stretch in
history ended by itself. And the cheapest defence is still to read what you
apply and not outsource the thinking.

The risk I take most seriously isn't a takeover. It's the quiet version:
people handing over one decision after another because it's cheaper and
easier, until they're nominally in charge of very little. Nobody has to plan
that. Convenience does it on its own. Blind faith in AI and whatever it
produces can take us into a very deep crisis and throw us back a long way, or
worse.

## What to do about it

Seeing how neural networks work made something else obvious to me: the human
brain is a seriously powerful computer. We read text, recognise images, and
build and untangle complicated logic without effort, on about twenty watts.
The machine needs a power plant to imitate that.

So invest in people. Education should get more money and attention now, not
less, so the next generations can work alongside AI properly and reach heights
neither could alone. And they need more than how to use it. I've [argued
before](/article/articles-you-still-have-to-learn-to-code) that developers
still have to understand the code. Everyone needs the general version of that:
how to look at something fluent, confident and fast, and say "this makes no
sense".

There's a simple test for all of us. Investors ask of any company how hard it
would be for its customers to leave. Ask it of yourself. Could you still do
your job, check an answer and make a decision if the model disappeared
tomorrow? If yes, AI is a tool, and whatever happens to the market is just a
market story. If not, the machine still has nothing to lose, and we've made
sure we do.
