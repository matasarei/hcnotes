---
title: The Alternative Is Nothing
date: 2026-09-01
description: A retro-computing YouTuber used Claude to get Haiku booting on PowerPC Macs after twenty years of the official port never getting there. Critics called it slop but never named the alternative. What he had that nobody else did, and why that is what development is now.
tags: [ai, agents, haiku, open-source, development]
---

> **TL;DR** — [Action Retro](https://www.youtube.com/@ActionRetro) forked Haiku,
> pointed Claude at the PowerPC branch, and in a few weeks had a desktop running
> on real G3 and G4 Macs — a thing the official port never achieved in two
> decades. Part of the community said AI code has "no chance" of meeting the
> bar; elsewhere it got called slop. Nobody named the alternative, because
> there wasn't one. He had the idea, the domain knowledge, and the judgement,
> and borrowed the one thing he lacked: the code. That isn't a shortcut around
> development. That is what development is now.

My YouTube feed keeps delivering, so here's another one. This weekend Action
Retro posted [Installing Haiku Beta 6 Until I Explode](https://www.youtube.com/watch?v=7cLTjyLKSa4)
— a pile of one-dollar laptops, the new Haiku beta, the usual good time. Buried
in the links is the part I actually care about: his own fork of Haiku for
PowerPC, called [Tabby](https://github.com/ActionRetro/Tabby-PPC), which boots
to the desktop on a PowerBook G4, an iBook G4, an iMac G3, and a Power Mac G4.
Sound, WiFi, ethernet, USB, native NVIDIA modesetting, OpenGL.

Some context for anyone who hasn't followed Haiku: the PowerPC port has existed
as a branch for [roughly twenty years](https://www.desktoponfire.com/haikuos/hardware/947/haiku-powerpc-port/)
and never once reached a working desktop. The blockers were the boring kind —
missing PCI host drivers, atomics, page tables, an ATA driver for the Mac I/O
chip. The kind of work that needs someone with the old hardware on the desk,
the patience to boot it a thousand times, and the ability to write kernel
code for it. Nobody with all three ever showed up.

Then someone with two of the three showed up, and borrowed the third.

## What he actually did

Sean Malseed is not a kernel developer. He's a retro-computing YouTuber with a
shelf full of old Macs, six years of poking at PowerPC hardware in public, and a
couple of PHP projects for vintage machines to his name. Not nothing — but not
someone who writes OpenFirmware boot code for fun either.

In July he [posted on the Haiku forum](https://discuss.haiku-os.org/t/i-have-made-some-progress-on-the-powerpc-port/19578)
that he'd gotten the port to a desktop in an emulator in five days, and that he
had used Claude "a pretty fair amount". Then to real hardware. Then to a
downloadable image other people booted on their own iBooks. "At 550MHz G4, the
desktop is shockingly useable", he wrote, and posted the screenshots.

<div class="shots">
  <img src="https://discuss.haiku-os.org/uploads/default/original/2X/9/97c45f1c4f65d3d328f27c387da85dafa2e27f39.png" alt="Haiku desktop with Tracker and the Deskbar running inside the DingusPPC emulator, the first time the PowerPC port reached a desktop" loading="lazy">
  <img src="https://discuss.haiku-os.org/uploads/default/optimized/2X/c/c75059fcd7767425eb662c45be90442d23d304f3_2_1332x1000.jpeg" alt="An open Power Mac G4 on a desk next to a monitor showing Haiku's About this system window on a 1.87 GHz Motorola PowerPC" loading="lazy">
</div>

Photos from [his forum thread](https://discuss.haiku-os.org/t/i-have-made-some-progress-on-the-powerpc-port/19578):
the first desktop in the emulator, and the first boot on a real Power Mac G4
three days later.

He did this for free. His own time, his own machines, his own AI subscription.
He's not asking Haiku to merge it — their [AGENTS.md](https://github.com/haiku/haiku/blob/master/AGENTS.md)
says no AI-generated code, full stop, and he's respecting that by shipping
Tabby as a separate distribution with its own name. The README calls it
"extremely premature". It probably is. It also boots.

## "Slop"

And still. The forum discussion got heated enough that the moderators
[split the argument into its own thread](https://discuss.haiku-os.org/t/what-are-the-ai-usage-guidelines-for-core-os-contributions/19606),
where one long-time Haiku developer declared "there is no chance that LLM
generated code would meet Haiku's high standards", and another worried that
this "makes it harder to verify that the upstream port remains untainted".
Outside the forum, in the comments and the usual threads, the word I keep
seeing is the one everyone reaches for now: slop. The upstream concern is
fair, for upstream. It's beside the point for a fork that was never going
there, and its author said so in the same thread.

I keep asking the same question every time this happens, and I've never once
gotten an answer: **what was the alternative?**

Not "what would be nicer". Not "how would a real kernel engineer have done it".
What was actually going to happen to Haiku on PowerPC if this guy had put the
laptop down? We have twenty years of data on it. Nothing. The branch would sit
there, the G4s would keep running Leopard or a museum copy of OS 9, and there
is no hidden volunteer who was about to write those PCI drivers by hand and
got scared off by an AI-assisted fork.

So the honest comparison is not "AI code versus expert code". It's "imperfect
running code versus no code". And when that's the comparison, calling the
running one slop isn't a review. It's a mood.

## Everything but the code

Look at what he brought, and what the agent did not supply.

**The idea** — that Haiku on a G4 is worth having at all, and that this was
the moment to try. **The domain knowledge** — a shelf of test machines and
knowing where the OpenFirmware and Mac I/O documentation lives, which is half
the battle on hardware this old. **The judgement** — knowing when a boot hang
is a driver problem versus a page table problem, and when to stop trusting the
model's theory and try the other one. Plus the patience to keep going through
the part where nothing boots.

For twenty years, nobody who had those could also write the low-level code,
and nobody who could write the code cared enough about a G4. The code turned
out to be the one part you can now borrow — the way I borrow a compiler
instead of writing assembly — and he was the first to show up with
everything else once that was true. That's why it happened in 2026 and not in
2010.

I've had smaller versions of the same thing.
[wow-launcher](https://github.com/matasarei/wow-launcher) runs a 2010 Windows
game at full speed on Apple Silicon, and I'm a web developer, not a Wine or
Metal person; [the whole thing](/article/articles-my-own-private-azeroth) took
less than a week of evenings.
[Antigravity Companion](https://github.com/matasarei/antigravity-companion) is
a JetBrains plugin in Kotlin, a language I'd barely touched, with
[two reported bugs in two thousand lines](/article/articles-antigravity-companion-built-with-ai).
Both times the idea, the domain knowledge, and the judgement were mine, and
the Wine, the Metal, and the Kotlin were borrowed.

## This is what development is now

Here's the thing the slop crowd gets wrong, and I think it's the whole
disagreement. They look at Tabby and see someone who skipped the development
and kept the result. I see someone who did the development and skipped the
typing.

Development was never the typing. Nobody ever paid for lines of code; they
paid for a thing that works, and typing was just the slowest step between the
idea and the thing. It ate most of the day, so we confused it with the job.
Take it away and what's left is the actual job: finding an idea worth
building, turning it into a plan that's right, and having the judgement to
tell when the result is wrong. The agent doesn't make that part easier. It
makes it the *entire* job, with nothing left to hide behind: either the plan
was right or it wasn't, and you find out the same day.

It also changes who gets to build. When building was expensive, the person
with the idea had to hand it to someone who could build it, and half the "who
really invented X" arguments in history are about what happened next. Now the
person with the idea makes the MVP themselves and negotiates from a position
of having a thing rather than a pitch. I find that genuinely beautiful, and I
suspect it's most of why people who spent a career on the building side find
it threatening.

Coding as a job is going, and the cheap end — the personal site, the
small shop, the landing page — goes first. Development is not going anywhere.
It's about to be the whole job instead of the part that fit between the
typing, and how you learn to do it that way is its own subject. I've
[already started on it](/article/articles-code-got-cheap-the-plan-didnt).

## Worth existing

Tabby is probably not good software yet. Its own author calls it premature,
and I'll take his word. Its About window says, in the first line, that it is
not the official distribution. I don't care, and neither should he. A fork
that runs is worth existing and worth working on, and shipping it as a fork
under its own name is how half the software we use got started. He should be
proud of it, and "it's slop" is not a counter-argument to "it boots".

<div class="shots">
  <img src="https://discuss.haiku-os.org/uploads/default/optimized/2X/f/f843bbe5e53bba954e57e306f8e1b4c536ee05ad_2_1332x1000.jpeg" alt="A 600 MHz iMac G3 showing the Tabby 0.1 About window with a paw logo and a tabby/ppc wallpaper" loading="lazy">
</div>

Someone will do this to a problem you care about, soon, if they haven't
already. You can call it slop from the sidelines, or you can bring the idea,
the knowledge, and the judgement, and borrow the rest. Only one of those has
ever built anything.
