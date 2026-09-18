---
title: The Keyboard I Always Needed
date: 2026-09-18
description: hcboard is an Android keyboard for everyday typing with a real Ctrl, Alt and Fn, and no network access. I built it with AI in about a day. What I brought, what the model did, and why the harness mattered most.
tags: [ai, agents, android, keyboard, skills]
---

> **TL;DR** — [hcboard](https://github.com/matasarei/hcboard) is an Android
> keyboard you can type on all day that also has a full 60% layout with a
> real Ctrl, Alt and Fn, and it has no network permission at all. I wanted
> that keyboard for years. The pieces existed, just never in one app. It
> took 218 commits over about a day and a half, 188 of them on a single day.
> The idea, the spec, the testing and the feedback were mine. The routine
> work, the kind that used to take weeks, was the model's. And the reason
> that split worked is not the model. It's the harness around it.

## The keyboard nobody made

Every Android keyboard I have used makes me pick a side.

On one side are the keyboards made for people: Gboard, Samsung's, the
iPhone one I keep comparing everything to. Glide typing, suggestions, a
dozen languages, the phone's own colours. Open a terminal in one of them
and the illusion ends. There is no Esc, no Tab, and "Ctrl" isn't there at all.

On the other side are the keyboards made for tinkerers.
[Hacker's Keyboard](https://github.com/klausw/hackerskeyboard) gives you a
full PC-style layout, and
[Unexpected Keyboard](https://github.com/Julow/Unexpected-Keyboard) puts
Ctrl, Alt and the rest a swipe away on every key. They are good at what
they do, and I respect them. But you give up part of everyday typing to
use them: glide, modern suggestions, the phone's own look.
Writing a message to a friend on a keyboard designed around SSH feels
exactly like that.

So the gap was never "a keyboard with Ctrl on it". Those exist. The gap was
a keyboard that is ordinary until the moment you need it not to be, and
then, without switching apps or keyboards, it is a computer keyboard. The
description I wrote at the start of the first pull request says it better
than I can now: "No existing Android keyboard combined a Gboard-like
Material look, the feel of the iPhone keyboard, and real developer keys".

And one more thing I wanted, which turned out to be the simplest to
deliver: a keyboard that can't talk to anyone. hcboard has no `INTERNET`
permission in its manifest. No analytics, no sync, no account, nothing you
type is logged. The easiest way to prove a keyboard doesn't send your
keystrokes anywhere is to make it unable to.

## What it is

For everyday typing it behaves like the keyboard you already have. You can
type or glide, pick a word from the strip above the keys, and let a tap of
space fix the typo you just made. It speaks nine languages, each with its
own layout and word list, and follows your phone's Material 3 colours in
light or dark.

When you need more, it's already there. Unfold a phone or pick up a tablet
and you get a full 60% board: Esc, Tab, Caps, Ctrl, Alt, Meta, the symbols
printed where a PC keyboard prints them, and F1 to F12 behind Fn. On a
normal phone, one tap on `</>` puts the same keys in a strip above any
layer. Modifiers latch the way you'd expect: tap to arm for one key, tap
twice to lock, or hold and chord. And in a terminal, Ctrl+C is a real
Ctrl+C, a signal, not a copy.

<div class="shots">
  <img src="/shared/img/hcboard/fold-terminal.jpg" alt="The 60% board under a terminal session on a foldable" loading="lazy">
  <img src="/shared/img/hcboard/fold-fn.jpg" alt="The same board with Fn and Shift held: the number row shows F1 to F12 and the letters show arrows, Home, End and brackets" loading="lazy">
  <img src="/shared/img/hcboard/phone-dev-strip.jpg" alt="The phone board in dark mode with a strip of Esc, Tab, Ctrl, Alt, Shift, arrows and Fn above the letters" loading="lazy">
</div>

That's the short version. The
[README](https://github.com/matasarei/hcboard#readme) has the rest,
and every push to main builds a
[dev APK](https://github.com/matasarei/hcboard/releases/tag/dev) you can
install today.

## What I brought

I want to be exact about this part, because "I made it with AI" gets read
as "the AI made it", and the history says otherwise.

The idea came first, and it came as a picture. The very first commit in
the repository, on September 16 at 18:14, is not Kotlin. It's the design
mocks: the phone layers, the dark variants, the 60% board on a foldable,
the modifier states, the password sheet. Before any code existed I knew
what every screen should look like, and so did the model.

Then the spec. The first pull request was built from a written plan,
`.tasks/android-keyboard-v1.md`, with acceptance criteria and an explicit
list of what was out of scope: word suggestions, glide typing, emoji,
clipboard history. Glide typing landed within hours anyway, but as a decision
I made, not something that crept in.

Then the testing a model can't do. The same pull request ends with an
honest list titled "Not tested, needs a phone":

- Termux and the rest of the app matrix
- inline chips from Enpass or Google Password Manager
- a real Fold profile
- haptics
- two-finger chording by touch

An emulator can tell you the keyboard types "hello". It cannot tell you
that the keys feel too small under a thumb, that the dark theme looks
washed out next to every other app on the phone, or that a word you type
every day never shows up in suggestions. Somebody has to hold the phone.

And then the feedback, which is where most of the product actually came
from. All of it came from my own testing on the phone, and the history
shows it: the keys get Samsung's proportions, a Black
theme appears after Samsung's own dark keyboard, the Ukrainian word list
gets a curated overlay because its source corpus is news text and
under-rates the words people actually use in chat, and the English one
gets a list of modern words the old dictionary never heard of. None of
those came from a prompt like "make it better". They came from using the
thing and saying what was wrong.

That's my half: knowing what the keyboard is *for*. It's the part that
can't be delegated, because nobody else has it.

## What the model did

Everything else. And "everything else" is a lot.

The repository today has 5,784 lines of Kotlin and 2,845 lines of tests:
32 unit test classes and 188 test methods. Of the 218 commits, 175 name
their co-author in the message, Claude Fable 5.1 on 100 of them and Claude
Opus 5 on 75. The commits are mine; the typing mostly isn't.

What that typing covers is the part of the job I have never enjoyed and
always had to do. Hosting Jetpack Compose inside an input method service,
which crashes in creative ways if the lifecycle owners aren't set on
exactly the right window. The state machine behind a modifier that can be
armed, locked, chorded, and has to survive a layer switch. Key events with
the right meta state for a terminal, and editor actions instead of key
events for a normal text field. Lint at zero errors. CI that runs the
tests on every pull request and publishes a fresh dev build on every push.
The scripts that build nine word lists and the notices that credit where
they came from.

Each of those is a day or two of reading documentation and fighting the
platform. Together, it's weeks. Here it was one long day: 188 commits on
September 17 alone.

Not all of it was written from scratch, and it shouldn't have been. The
glide typing classifier is [FlorisBoard's](https://github.com/florisboard/florisboard),
Apache-2.0, with its header kept. Most word lists come from the Android
Open Source Project, the Ukrainian one from Helium314's CC BY 4.0 list.
Each is credited in the NOTICE file, under its own licence. That's what
open source is for, and I've
[already said](/article/articles-the-batteries-are-already-made) why I
don't think using AI for this is theft.

## Why it worked: the harness

Here is the thing I most want people to take from this, and it's not the
number of commits.

The same model, with the same keyboard in my head, could have produced a
mess. I know, because I've
[worked that way](/article/articles-i-was-painting-not-programming):
solution in my head, agent on the keyboard, correcting it every time it
drifted from a picture it couldn't see. Fast, and wrong, over and over.
What made this one different is everything around the model.

The first piece is the skills. I use
[GKU](https://github.com/grinchenkoedu/claude-skills), a set of Claude Code
skills for the ordinary development cycle: plan, implement, review, fix,
open the pull request, verify. I wrote about them in
[Code Got Cheap. The Plan Didn't.](/article/articles-code-got-cheap-the-plan-didnt)
as a claim: the skill is the reusable asset now, and the code is
comparatively disposable. hcboard is that claim tested on a real product
instead of a demo. Every feature went through the same loop.

You can see the loop in the history without taking my word for it. 40 pull
requests in about a day and a half. 85 commits whose subject starts with
`Fix:`, each one a single review finding applied on its own, so every
correction is readable and revertable by itself. From the second pull
request on, CI ran the unit tests and lint on every one.

The second piece is the rules file. hcboard has a `CLAUDE.md` that the
agent loads every session: how to build, where things live, which file is
the only one allowed to touch Android's input connection, and the mistakes
that must not happen again. My favourite line in it is about timestamps:
key events use `SystemClock.uptimeMillis()`, never compare them with
`System.currentTimeMillis()`, "that bug has been made twice". That is the
habit from the painting article, made concrete. If you correct the same
thing twice, it belongs in the file, and then you never correct it a third
time.

The third piece is verification by something other than trust. Tests
written with the code, lint that fails the build, CI on the pull requests,
and a list of what the emulator could not check, written down instead of
glossed over. The model never had to be believed about whether something
worked. It had to show it.

None of this is exotic. It's what good teams have always said they do.
The difference is that with an agent it stops being a nice-to-have and
becomes the thing that decides whether you get a product or a pile of
plausible code.

## A day, not weeks

So here is the split, the way I'd put it to anyone who asks. The idea was
mine, and so was the spec. The testing on a real phone was mine, and so was
every "this feels wrong" that turned into a fix. The routine was the
model's: the platform plumbing, the state machines, the tests, the build,
the licences. Work that would have taken me weeks of evenings took a day.

I said [before](/article/articles-antigravity-companion-built-with-ai)
that the barrier didn't disappear, it moved. hcboard is where it moved to.
Writing the code is no longer the hard part. Knowing exactly what you
want, writing it down, and having a method that holds the model to it,
that's the hard part now, and it's also the part that makes the result
yours.

It's the keyboard I always needed. Nobody made it, so I did, with a lot of
help and a very clear idea of what I was asking for.
