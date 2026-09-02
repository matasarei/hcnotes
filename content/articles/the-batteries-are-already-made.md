---
title: The Batteries Are Already Made
date: 2026-09-03
description: The reply to my Tabby piece wasn't "slop", it was "not ethical". I split that into what it actually says, checked it against the courts and the Tabby code, and found six bugs Haiku still has.
tags: [ai, ethics, open-source, haiku, copyright]
---

> **TL;DR** — Last week's piece got the "slop" replies I expected and one I
> didn't: "it's not ethical". Taken seriously, that is four different
> objections with four different owners, and only one of them is theft. No
> court has found that training a model on lawfully obtained material is
> theft; what has been punished is piracy by the model maker, which no
> subscriber can commit or undo. I read the Tabby repository: the code is
> Haiku-style, disclosed in every commit, and it fixed six bugs in Haiku's own
> tree that Haiku still has. If you still disagree, open models exist, and
> they cost you capability. That trade is yours to make, and it is not a rule
> for anyone else.

[Last week](/article/articles-the-alternative-is-nothing) I wrote about Tabby,
the fork that got Haiku booting on PowerPC Macs after twenty years, and asked
what the alternative was. Most of what came back was what I expected. The
reply worth answering, and I've now seen it in more than one place, is:
fine, it boots, but it's not ethical to build it this way.

That deserves a real answer instead of a shrug. And a real answer starts with
asking what the sentence actually says.

## What "not ethical" actually says

The best source is the Haiku forum itself, because when the argument
[got its own thread](https://discuss.haiku-os.org/t/what-are-the-ai-usage-guidelines-for-core-os-contributions/19606)
a core developer laid out reasons instead of a word. Not one objection.
Four.

The first is the one the policy cites: code produced with LLMs falls under
"ambiguous or incompatible licenses", so nobody can honestly certify where it
came from. The second is climate — the energy behind training and running
these things, plus the scraper bots hammering the project's own servers to
feed them. The third is labour: the people paid badly to label and classify
the training data. The fourth is lock-in: closed, subscription-only tools
from a handful of companies, in a project whose entire point is not
depending on those companies.

There's a fifth that nobody says out loud, and I'll admit I went into the
thread expecting to find it: people who spent a career on the building side,
watching the building get cheap, and reaching for "unethical" because it's a
better word than "threatening". I didn't find it. What I found was a
developer who doesn't fly or drive for the same reasons, and who framed the
whole position as showing that building software without LLMs is still
possible, not as forbidding anyone else from doing otherwise. That's a
consistent person, not an insecure one. I'm dropping it, and I'm going to
argue with the best version of the other side instead.

Here's why the split matters. Four objections, four owners. Copyright is a
question for courts and the companies that trained the models. Climate and
labour are questions about an entire industry, where one subscriber's choice
is a rounding error — I'll come back to that. Lock-in is a real engineering
concern with a real engineering answer. When all four hide behind one word,
whichever is strongest at the moment does the talking, and none of them ever
has to be settled. So let's settle them one at a time.

## Is it theft? What the courts have said

Three cases, all American, because that's the only place these questions
have actually been tried.

**Bartz v. Anthropic.** In June 2025 the judge ruled that training a model
on lawfully purchased books is transformative and fair use. What was *not*
fair use was downloading millions of books from pirate libraries and keeping
them. That second part is what the
[$1.5 billion settlement](https://natlawreview.com/article/ai-vs-authors-update-court-approves-historic-anthropic-settlement-while-meta)
paid for — final approval on July 20, 2026, covering roughly 482,000 works.

**Kadrey v. Meta.** Same month, same conclusion on training. What's still
alive in that case is a claim about torrenting, not about learning.

**Doe v. GitHub**, the one about code. Most of the claims were dismissed in
January 2024. What's left is narrow: whether stripping licence text is
actionable only when the output is an identical copy. The Ninth Circuit
[heard it](https://courthousenews.com/ai-companies-urge-ninth-circuit-to-make-copyright-decisions-clear/)
on February 11, 2026, and as I write there is no decision.

So the wrong that has actually been punished is *acquisition*, not
*learning*. Piracy, by the model maker. And that is a wrong I can neither
commit nor undo from my desk. Whether I subscribe or not, the books were
downloaded or they weren't, and the settlement was paid. Calling my
subscription theft borrows the guilt of a different act by a different party.

The honest caveat: this is one country's law, it is recent, and it can move.
I'll say at the end what would change my mind.

## What a model takes from your code

The theft argument usually pictures the model as a zip file of GitHub with a
chat window on top. It isn't. Training turns text into weights — billions of
numbers nudged until the model predicts the next token better — and what
comes out is shaped by frequency: things that appeared thousands of times
are reproduced closely, things that appeared once leave barely a trace.

That cuts both ways, and the enthusiasts skip the second half. Memorisation
is real. Code models
[do reproduce training data verbatim](https://arxiv.org/html/2408.02487v1),
more so the more common the snippet, and Copilot's filter for output
overlapping public code
[has been bypassed](https://arxiv.org/pdf/2210.17546) by asking for the same
code in a different style.

But look at what the risk actually is: verbatim reproduction of one specific
licensed snippet. That is the risk you already carry with a human who pastes
from Stack Overflow, and you check it the same way — search a distinctive
line, run a licence scanner before a release, look up any block you couldn't
have written yourself. What comes back verbatim most is boilerplate, which
is also the least worth protecting. Structural similarity — "this looks like
how project X does it" — is not something any court has treated as
infringement.

Then there's "we don't know what it was trained on", which is true.
Anthropic's
[current disclosure](https://crfm.stanford.edu/fmti/December-2025/company-reports/Anthropic_FinalReport_FMTI2025.html)
is one paragraph: public internet data, non-public data from third parties,
paid labelling, users who opted in, internal data. The EU's AI Act
[changes that](https://www.mayerbrown.com/en/insights/publications/2025/08/eu-ai-act-news-rules-on-general-purpose-ai-start-applying-guidelines-and-template-for-summary-of-training-data-finalized):
a structured summary of training content on a fixed template, due by
August 2, 2027 for models already on the market. The not-knowing is real,
and it has an expiry date.

## The batteries are already made

Now the two objections that aren't about theft: climate and labour.

Someone mined the lithium. Someone built the factory, and someone stood at
the line. The batteries exist. Whether I buy one changes nothing about any
of that; it already happened, and it keeps happening at a scale where my
purchase doesn't register. A model is the same object. Training is a sunk
cost. Claude exists whether I subscribe or not, and my not subscribing
un-trains nothing. What the subscription pays for is inference — actually
running the thing — and for a fork of a hobby operating system, or
[a launcher for a 2010 game](/article/articles-my-own-private-azeroth), that
is the small part.

So the economic version of "it's not ethical to use it" doesn't hold. The
harm it names happened before I showed up and continues regardless of me.

But here's the honest turn. The Haiku developer's argument was never
economic. It was cultural: show that building software without LLMs is
possible. You can't refute a values choice with a cost argument, and I'm not
going to try. What I can say is that it's a choice for their project, not a
duty for mine. And Sean honoured that choice exactly the way you should
honour a values choice you don't share: he didn't submit the code. He
forked, named it Tabby, and said so on the first screen anyone sees.

One more thing, for the end: the laptop this is typed
on, and the G4 Tabby boots on, sit on a supply chain that nobody in that
thread has audited either. Every tool does. Where you stop caring is a
choice, and it is always a choice.

## Where the AI actually wins

"There is no chance that LLM generated code would meet Haiku's high
standards" is a claim about code, and code can be read. So I cloned
[Tabby](https://github.com/ActionRetro/Tabby-PPC) and diffed it against the
upstream commit it forked from.

Strip out the vendored parts — Mesa restored from Haiku's own history, a
FreeBSD Ethernet driver with its BSD headers intact, firmware — and the
authored work is about 18,000 lines across 265 files: the PCI host bridge,
two interrupt controllers, ATA, keyboard and trackpad, sound, big-endian
USB, page table fixes, signals, fork, AltiVec. The missing organs of a
twenty-year-old skeleton.

None of it is hidden: 163 of the 174 commits carry a trailer naming the
model that helped, and every new source file lists Claude as a co-author
next to Sean's name.

Style, measured with Haiku's own checkstyle tool, lands in the same band as
upstream: the new interrupt controller scores 10 hits in 421 lines against
upstream's OpenPIC driver at 16 in 553. Across all 18,000 authored lines
there are three TODOs and no FIXME, XXX or HACK. And every commit has a
body, about 190 words on average, naming the symptom, the mechanism, the
hardware it was seen on, and what was checked.

Then the part that turns the "high standards" argument around. While
bringing up PowerPC, the work found and fixed six bugs in *shared* Haiku
code — not the port, the code every architecture runs — that x86 never
triggered. Upstream has not touched any of those files since the fork.

- A job object's destructor left dangling pointers behind, and the launch
  daemon crashed on them under timing jitter.
- The package daemon freed a package twice when a commit failed after adding
  it.
- The launch daemon had a TODO for a restart throttle. Now it has one.
- A half-initialised ICU object crashed two locale formatters.
- The boot device scan gave up before a slow USB disk had finished
  appearing.
- An atomic test-and-set in a public header uses a weak compare-exchange,
  which on x86 compiles to the same thing as the strong one, so nobody
  noticed. Upstream master still has it today.

All six patches sit in a fork, and under the current policy none can be
submitted. That's the cost of the standard, stated plainly: a rule that
forbids the tool which just found six bugs in your tree is a rule that keeps
the six bugs. You don't have to let it write your kernel. But not letting it
*review* your kernel is, on this evidence, worse than letting it.

Now the honest list. Three places in the kernel are wrapped in an `#ifdef`
for PowerPC and leak a page instead of panicking, each labelled "bring-up
workaround, not a fix" in the code and the commit. And there is one genuine
licence problem: the Broadcom WiFi firmware is committed into the tree,
where upstream deliberately downloads it at install time with a licence
notice. That's a human packaging decision, not a line the model wrote.

So "it's slop" was wrong about the drivers and right about the workarounds.
And the author had already said which was which.

## My own shelf

The same question applies to me, so here's my shelf.
[wow-launcher](https://github.com/matasarei/wow-launcher) is Swift, Wine and
Metal, and took
[less than a week of evenings](/article/articles-my-own-private-azeroth).
[Antigravity Companion](https://github.com/matasarei/antigravity-companion)
is a JetBrains plugin in Kotlin,
[two thousand lines and two reported bugs](/article/articles-antigravity-companion-built-with-ai)
across six releases. [AICMF](/article/articles-aicmf-ai-first-cms) and the
site you're reading. A TCP client library for PHP, an OOP wrapper for a
government signing library, an old MVC framework, two Moodle plugins. All of
it MIT.

What I actually do is simple. Commits say what was assisted. I read what I
ship. I sign it, and the bug is mine — nobody gets to blame the tool. And if
any of that code ends up in someone's training set, that is what the MIT
licence says: take it, keep the notice. Theft needs an owner who objects. I
don't. If you maintain something small and want a policy, mine is three
lines: AI assistance is allowed; it's disclosed; the human who submits it
signs it, and looks up any block they couldn't have written themselves.

## If you still disagree

Then you have an honest option, and I'd rather describe it accurately than
pretend it doesn't exist.

There are models trained only on data their makers could account for.
[Comma v0.1](https://arxiv.org/abs/2506.05209) is a 7-billion-parameter
model trained on the Common Pile, eight terabytes of openly licensed text
and code, weights and data published.
[Apertus](https://arxiv.org/pdf/2509.14233), from ETH Zurich and EPFL,
respects robots.txt retroactively, honours opt-outs, and releases the
weights, the data and the recipe.
[StarCoder2](https://arxiv.org/html/2402.19173) was trained on permissively
licensed code with an opt-out. If the copyright objection is the one that
moves you, these answer it. They answer the lock-in one too: the weights
are yours, on your hardware, and no subscription can be cancelled from
under you.

Here's the price. They are weaker, by a generation or two — Comma's own
paper puts it at Llama 2 level — and none of them would have brought a G4 to
a desktop. They are weaker precisely *because* they are open: less data,
more carefully chosen. And even the careful set isn't clean; a
[2025 audit](https://arxiv.org/abs/2501.02628) of the permissive code set
found mislabelled files pulling non-permissive code in, and thousands of
known-vulnerable ones.

And you still need a GPU to run them. A GPU made from metals mined and
refined in places that nobody in this argument has audited, on a board
assembled by people whose conditions nobody in this argument has asked
about. The purity chain doesn't end. There is no bottom where the tool is clean. You pick where you
stop, and picking is fine — but your stop is not a rule for me, any more
than mine is for Haiku.

Haiku chose. I respect it. If I ever want their kernel to do something it
doesn't, I'll do what Sean did: fork it, name it, and say so on the About
window.

## P.S. What would change my mind

Three things, none of which has happened.

The Ninth Circuit could throw out the identical-copy requirement in the
GitHub case, which would make near-copies with stripped licence text
actionable. A court could find that training itself infringes, which no
court has. Or someone could document a verbatim-copy claim against a small
downstream project — a maintainer who shipped a generated block and got a
real letter about it. Any of those, and this article gets a follow-up.

Until then, the argument I found in that thread was better than the one I
expected, and it was made by someone consistent enough to argue with. That
is worth more than agreement.
