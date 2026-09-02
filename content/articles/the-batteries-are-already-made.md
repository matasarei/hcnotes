---
title: The Batteries Are Already Made
date: 2026-09-03
description: Last week I argued the Tabby code was worth having. The other objection, that building it with AI is not ethical, taken seriously: what it claims, what the courts and the code say, and what is better.
tags: [ai, ethics, open-source, haiku, copyright]
---

> **TL;DR** — Last week's piece dealt with "slop" and skipped the harder
> objection: that building this way is not ethical. Taken seriously, that is
> four different objections with four different owners, and only one of them
> is theft. No court has found that training a model on lawfully obtained
> material is theft; what has been punished is piracy by the model maker,
> which no subscriber can commit or undo. I read the Tabby repository: the
> code is Haiku-style, disclosed in the commits, and it fixed six bugs in
> Haiku's own tree that Haiku still has. If you still disagree, open models
> exist, and they cost you capability. That trade is yours to make, and it
> is not a rule for anyone else.

[Last week](/article/articles-the-alternative-is-nothing) I wrote about Tabby,
the fork that got Haiku booting on PowerPC Macs after twenty years, and asked
what the alternative was. That dealt with "slop". It skipped the harder
objection, the one I've seen in more than one place and left out because it
needs more than a paragraph: fine, it boots, but it's not ethical to build
it this way.

That deserves thinking through instead of a shrug. And thinking it through
starts with asking what the sentence actually says.

## What "not ethical" actually says

The best source is the Haiku forum itself, because when the argument
[got its own thread](https://discuss.haiku-os.org/t/what-are-the-ai-usage-guidelines-for-core-os-contributions/19606)
a core developer laid out reasons instead of a word. Four of them.
Copyright: generated code has "ambiguous or incompatible licenses", so
nobody can honestly certify where it came from. Climate: the energy behind
training and running the models, and the scraper bots hammering the
project's own servers to feed them. Labour: the people paid badly to label
the training data. Lock-in: closed, subscription-only tools from a handful
of companies, in a project whose whole point is not depending on them.

I went in expecting a fifth that nobody says out loud — people who spent a
career on the building side reaching for "unethical" because it's a better
word than "threatening". I didn't find it. I found a developer who doesn't
fly or drive for the same reasons, and who framed the position as showing
that building without LLMs is still possible, not as forbidding anyone else.
That's a consistent person, and the best version of the other side is the
one worth arguing with.

The split matters because the four have different owners. Copyright is for
courts and the companies that trained the models. Climate and labour are
about an entire industry, where one subscriber is a rounding error. Lock-in
is an engineering concern with an engineering answer. Behind one word,
whichever is strongest at the moment does the talking and none of them ever
gets settled. So let's settle them one at a time.

## Is it theft? What the courts have said

Three cases, all American, because that's where these questions have
actually been tried. In
[Bartz v. Anthropic](https://natlawreview.com/article/ai-vs-authors-update-court-approves-historic-anthropic-settlement-while-meta)
the judge ruled in June 2025 that training on lawfully purchased books is
fair use; what was not fair use was downloading millions of them from pirate
libraries, and that is what the $1.5 billion settlement, approved in July
2026, paid for. Kadrey v. Meta reached the same conclusion on training the
same month. And Doe v. GitHub, the one about code, has been narrowed to a
single question — whether stripping licence text is actionable only for
identical copies — which the Ninth Circuit
[heard](https://courthousenews.com/ai-companies-urge-ninth-circuit-to-make-copyright-decisions-clear/)
in February 2026 and has not yet decided.

So the wrong that has actually been punished is *acquisition*, not
*learning*: piracy, by the model maker. That is a wrong I can neither commit
nor undo from my desk. Whether I subscribe or not, the books were downloaded
or they weren't, and the settlement was paid. Calling my subscription theft
borrows the guilt of a different act by a different party.

The caveat: this is one country's law, it is recent, and it can move. I'll
say at the end what would change my mind.

## What a model takes from your code

The theft argument pictures the model as a zip file of GitHub with a chat
window on top. It isn't. Training turns text into weights — billions of
numbers nudged until the model predicts the next token better — and what
comes out is shaped by frequency: things that appeared thousands of times
are reproduced closely, things that appeared once leave barely a trace.

That cuts both ways. Memorisation is real: code models
[do reproduce training data verbatim](https://arxiv.org/html/2408.02487v1),
more so the more common the snippet, and Copilot's overlap filter
[has been bypassed](https://arxiv.org/pdf/2210.17546). But the risk that
leaves is a specific one — verbatim reproduction of one licensed snippet —
and it is the same risk you carry with a human who pastes from Stack
Overflow. You check it the same way: search a distinctive line, run a
licence scanner, look up any block you couldn't have written yourself. What
comes back verbatim most is boilerplate, which is also the least worth
protecting, and structural similarity has never been treated as
infringement by any court.

"We don't know what it was trained on" is true. Anthropic's
[current disclosure](https://crfm.stanford.edu/fmti/December-2025/company-reports/Anthropic_FinalReport_FMTI2025.html)
is one paragraph. The EU's AI Act
[changes that](https://www.mayerbrown.com/en/insights/publications/2025/08/eu-ai-act-news-rules-on-general-purpose-ai-start-applying-guidelines-and-template-for-summary-of-training-data-finalized):
a structured summary of training content on a fixed template, due by
August 2027 for models already on the market. The not-knowing has an expiry
date.

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

Strip out the vendored parts and the authored work is about 18,000 lines
across 265 files — PCI, interrupt controllers, ATA, input, sound, USB, page
tables, signals, fork, AltiVec — the missing organs of a twenty-year-old
skeleton. Run Haiku's own checkstyle tool over it and it lands in the same
band as upstream's drivers. There are three TODOs in the whole thing and no
FIXME, XXX or HACK, and every commit has a body explaining the symptom, the
mechanism, the hardware it was seen on, and what was checked.

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

And none of it is hidden or dressed up as something else: 163 of the 174
commits carry a trailer naming the model that helped, and every new source
file lists Claude as a co-author next to Sean's name. Anyone who wants to
judge the code can see exactly how it was made.

## My own shelf

The same question applies to me. Everything I've written about here —
[the launcher](/article/articles-my-own-private-azeroth),
[the plugin](/article/articles-antigravity-companion-built-with-ai),
[the CMS](/article/articles-aicmf-ai-first-cms) — was built with AI and
published under MIT, and so were the libraries and plugins that predate it.

What I actually do is simple. Commits say what was assisted. I read what I
ship. I sign it, and the bug is mine — nobody gets to blame the tool. And if
any of that code ends up in someone's training set, that is what the MIT
licence says: take it, keep the notice. Theft needs an owner who objects. I
don't. If you maintain something small and want a policy, mine is three
lines: AI assistance is allowed; it's disclosed; the human who submits it
signs it, and looks up any block they couldn't have written themselves.

## If you still disagree

Then you have an honest option. There are models trained only on data their
makers can account for — [Comma v0.1](https://arxiv.org/abs/2506.05209),
[Apertus](https://arxiv.org/pdf/2509.14233) and
[StarCoder2](https://arxiv.org/html/2402.19173) — built on openly licensed
or opt-out-respecting data, with the weights and the data published. They
answer the copyright objection, and the lock-in one too: the weights are
yours, on your hardware, and nobody can cancel them from under you.

Here's the price. They are weaker by a generation or two, and none of them
would have produced a Tabby-level result. They are weaker precisely
*because* they are open: less data, more carefully chosen. And even the
careful data isn't clean; a [2025 audit](https://arxiv.org/abs/2501.02628)
of the permissive code set found mislabelled files and thousands of
known-vulnerable ones.

And you still need a GPU to run them, made from metals mined and refined in
places nobody in this argument has audited. The purity chain doesn't end.
You pick where you stop, and picking is fine — but your stop is not a rule
for me, any more than mine is for Haiku. They chose. I respect it. If I ever
want their kernel to do something it doesn't, I'll do what Sean did: fork
it, name it, and say so on the About window.

## The score

Using AI is better than not using it. Not as a slogan — as arithmetic.

On one side: things that exist now and didn't before, built in weeks by
people who had the idea and the judgement but not the years to type it out.
Bugs found in code that had been read by humans for decades and shipped
anyway. Fixes that sit in forks because a rule keeps the tool out and, with
it, the fixes. On the other side: a theft that no court has found, a
training cost that was sunk before anyone subscribed, and a supply chain
that every tool on every desk already shares. If a court ever rules that
learning itself is infringement, I'll write the follow-up. Until then, the
alternative is still nothing, and nothing has never fixed a bug.
