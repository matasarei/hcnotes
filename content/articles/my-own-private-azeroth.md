---
title: My Own Private Azeroth
date: 2026-08-19
description: Why I play the original WotLK offline on a personal AzerothCore server, and how I built a native macOS launcher that runs a 2010 Windows game at 120 FPS on Apple Silicon — with AI doing the labour and human intuition doing the steering.
tags: [gaming, wow, macos, ai]
---

> **TL;DR** — I play World of Warcraft 3.3.5a — the original Wrath of the Lich King —
> as a single-player RPG, on my own AzerothCore server with bots. To play it on a Mac
> I built [wow-launcher](https://github.com/matasarei/wow-launcher): a fully
> self-contained wrapper that runs the 2010 client at ~120 FPS on Apple Silicon.
> The AI first told me ~30 FPS was the ceiling. It was wrong, and the story of how
> we got past that is the interesting part.

I like World of Warcraft. Specifically, I like *Wrath of the Lich King* — patch
3.3.5a, build 12340, the game as it was in 2010 — and I like playing it offline.
That combination needs some explaining, because every part of it sounds wrong to
somebody.

## Offline, on purpose

I play WoW the way I'd play any offline RPG: at my own pace, no rush, no daily
obligations, no communication for the sake of communication. The world of WotLK is
big enough and dense enough to be a great single-player game — it just was never
shipped as one.

The usual answer is "join a private server," and that's exactly what I don't want.
Public pirate servers are full of questionable customizations, "seasonal" gimmicks,
and sooner or later a cash shop — donate or lose. That's not the game I liked; that's
somebody else's business model wearing its skin.

So I run a **personal AzerothCore server** with bots. My own realm, on my own
hardware, populated by AI-driven characters so the world doesn't feel empty. And I
do mean my own — here it is on a random night: the realm's authserver console up on
the big screen, Northshire on the laptop, the whole of Azeroth answering at
`127.0.0.1`.

<div class="shots">
  <img src="/shared/img/wow335/azerothcore.jpg" alt="AzerothCore authserver console on the big screen, the game running on the laptop below" loading="lazy">
</div>

And to be clear about the ethics: this is not piracy — I'm not even a lapsed customer. I
still play on official servers; my main,
[Magazar of Ravencrest](https://raider.io/characters/eu/ravencrest/Magazar), is
linked right on my [about page](/about). But I also bought World of Warcraft as it
was back then, and I believe that gives me the right to keep playing the version I
paid for — Blizzard just doesn't sell or host 3.3.5a anymore, so there is literally
no legal storefront to give my money to. A personal server for a game I own, with no
players and no profit, is preservation, not theft.

## The Mac problem

There was one real obstacle: I play on a Mac. WotLK-era WoW is a 32-bit x86
Direct3D 9 Windows game — close to the worst possible match for an ARM Mac.

The existing options didn't fit:

- **CrossOver** works, but it isn't cheap — and I'd be paying a subscription-grade
  price to run exactly one game from 2010.
- **WoWSilicon** is a great project — it's actually the upstream that makes the
  performance possible — but it's a different shape of thing: a patcher/launcher that
  manages games living elsewhere on your disk and a shared wine prefix, with profiles
  for several expansions. I wanted a single-game appliance, not a system.

So I built my own: [**wow-launcher**](https://github.com/matasarei/wow-launcher).
It assembles a fully self-contained `WoW.app` — the wine stack, the translation
layers, the prefix, everything baked into one bundle — with a native SwiftUI manager
in front. Nothing needs to be bought or installed first: one `make` downloads the
open-source wine runtime and the patch payloads from WoWSilicon's releases —
checksum-verified — and bakes everything into the bundle. Deleting the app removes
every trace. The result behaves like any other Mac
app: you start it and you play. On my M4 Max it holds **~120 FPS at native
Retina resolution** — limited only by the screen's refresh rate.

<div class="shots">
  <img src="/shared/img/wow335/launcher-play.png" alt="WoW Launcher — Play tab" loading="lazy">
  <img src="/shared/img/wow335/launcher-display.png" alt="WoW Launcher — Display settings" loading="lazy">
</div>

The manager installs and patches a client, verifies its integrity with one-click
repair, manages addons and the server list, and handles window mode, resolution and
Retina matching automatically — with a choice of renderer: DXVK by default, or the
Metal-native MTLd3D with HDR output. No terminal needed — though everything the GUI
does is also scriptable.

## "There is no way" — and then there was

The part worth writing about is *how* it got built, because I did it with AI — two
long Claude Code sessions — and the arc of those sessions says something about what
AI can and cannot do.

**Session one** started with a crashing Wineskin wrapper and ended, after a lot of
genuinely good work — a Warden crash fixed, a five-minute exit hang traced to dead
Blizzard tracker endpoints, settings tuned — at a stable **~30 FPS**. And then the AI
delivered its honest verdict: this is the realistic ceiling; *"Parallels + Windows 11
ARM remains the only path that would meaningfully change that."* In other words:
there is no way to do what you want.

I didn't accept that. Not because I knew better technically — because it *felt*
wrong. An M4 Max stalling at 30 FPS on a 2010 game isn't a hardware limit, it's a
software problem, and software problems have authors.

**Session two** began as a graveyard of clever failures. DXVK builds that showed a
black screen. A build that ran at one frame per second because of a deadlock between
the game's async loader and the translation layer's deferred submissions. A
celebrated "38 FPS milestone" that turned out to be a mirage — we'd most likely been
measuring the old renderer the whole time. A full day of first-principles engineering
that went nowhere.

Then I changed the strategy. Instead of asking the AI to *invent* the solution, I
went looking for proof that one existed — and found it: WoWSilicon's stack ran the
game at 120 FPS on my machine. The moment I saw that number, the task transformed
from research into engineering. I came back with a different instruction: *stop
inventing — this works; understand exactly why it works, then adopt it properly.*

That reframing was everything. The AI took the working stack apart — CrossOver 26's
wine, DXVK async for D3D9→Vulkan→Metal, winerosetta and rosettax87 for fast x87
math under Rosetta 2, libSiliconPatch client hooks — worked out what each piece did
and why, and then rebuilt the whole thing as one self-contained app bundle that
depends on none of the donor tools. Then, on top of it, a native SwiftUI manager:
installer, 41-check verifier with repair, addon manager, display logic, even Cyrillic
chat input solved the way the community solved it years ago, with glyph-remapped
fonts. Together we succeeded — and the whole thing took **a few days**. Before AI,
this was weeks of work, most of it too tedious to ever actually do in the evenings.

But notice where each contribution came from. The AI supplied depth and speed:
tracing a deadlock to a specific polling loop in `Wow.exe`, patching a loader name in
a compiled `ntdll.so` so the Dock says "WoW", grinding through 41 verification
checks. What it could not supply was the refusal. Left alone, it reasoned its way to
"30 FPS is the ceiling, buy Parallels" — a defensible conclusion, honestly argued,
and wrong. The thing that got us past it was experience and, more importantly,
**intuition**: the itch that says a conclusion is wrong before you can prove it, and
the judgement to stop inventing and adopt what already works. That's the part only
the human brings. The AI will out-read you, out-type you, and out-patience you — but
it doesn't refuse to believe.

(Since then the wrapper has shed even the build-time donors: it now builds on
WoWSilicon's open-source wine runtime, so CrossOver is out of the chain entirely.)

## Northrend at 120 frames

So now it just works. One icon in the Dock. Click, play, quest, log off whenever —
no queue, no chat, no shop. The game I paid for, the way I remember it, running
better on this machine than it ever ran on the hardware it was made for.

<div class="shots">
  <img src="/shared/img/wow335/dalaran.jpg" alt="Dalaran at ~120 FPS" loading="lazy">
  <img src="/shared/img/wow335/elwynn.jpg" alt="Flying over Elwynn Forest at ~120 FPS" loading="lazy">
  <img src="/shared/img/wow335/westfall.jpg" alt="Sentinel Hill inn in Westfall" loading="lazy">
</div>

The launcher is open source under MIT — it ships no game data and is built around
the original 3.3.5a client. If you have a Mac, an old love for Northrend, and your
own copy of the game, everything you need is in the
[repo](https://github.com/matasarei/wow-launcher).
