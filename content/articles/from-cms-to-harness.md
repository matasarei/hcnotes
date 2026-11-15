---
title: From CMS to Harness: How Web Development Changed, 2008-2026
date: 2026-11-15
description: How web development changed from 2008 to 2026: CMS sites and FTP, frameworks, no-code, AI chat, vibe-coding and the harness, as I remember it.
tags: [history, development, ai, agents, harness]
---

> **TL;DR** - Eighteen years of building websites on one page. We started
> inside a CMS and deployed over FTP, moved into frameworks and pipelines,
> then to AI in a chat window, then to vibe-coding. Now we build inside a
> harness: a development environment built around agents, with a developer
> steering it. Each card below is how I remember the era.

I started writing down how we used to work, and it turned out to be easier
to draw than to tell.

<div class="timeline-bar" role="img" aria-label="Timeline from 2008 to 2026 in seven eras, the last three are the AI eras">
<span class="timeline-bar__seg" style="flex-grow: 6"><b>2008</b> CMS</span>
<span class="timeline-bar__seg" style="flex-grow: 4"><b>2014</b> Frameworks</span>
<span class="timeline-bar__seg" style="flex-grow: 2"><b>2018</b> Mature</span>
<span class="timeline-bar__seg" style="flex-grow: 2"><b>2020</b> No-code</span>
<span class="timeline-bar__seg timeline-bar__seg--ai" style="flex-grow: 3"><b>2022</b> Chat</span>
<span class="timeline-bar__seg timeline-bar__seg--ai" style="flex-grow: 1"><b>2025</b> Vibe</span>
<span class="timeline-bar__seg timeline-bar__seg--ai" style="flex-grow: 1"><b>2026</b> Harness</span>
</div>
<ol class="timeline">
<li class="era">
<h2 class="era__years">2008-2013 <span class="era__name">Inside the CMS</span></h2>
<p class="era__felt">Most sites were a CMS, some ready-made, some our own. Frameworks existed, but they felt slow and heavy for the work we had. The frontend was HTML and jQuery. Deploying meant opening an FTP client and uploading the changed files. Version control was rare: the live site was backed up every night and synced, and that was the history.</p>
<div class="era__stack"><span class="badge b-php">PHP</span><span class="badge badge--w">CMS</span><span class="badge b-js">jQuery</span><span class="badge badge--w">FTP</span><span class="badge b-sql">MySQL</span></div>
<ul class="era__facts">
<li class="era__fact">2006: jQuery 1.0. By 2008 it was simply how we wrote JavaScript.</li>
<li class="era__fact">Other stacks were already here: <a href="https://en.wikipedia.org/wiki/Ruby_on_Rails">Rails</a> 1.0 in Dec 2005, Django 1.0 in Sep 2008, Node 0.1 in Jun 2009 and Express 1.0 in Nov 2010.</li>
<li class="era__fact">2010: among Eclipse users, Subversion 58.3%, Git 6.8% (<a href="https://blogs.eclipse.org/post/anonymous/trends-eclipse-community-survey">Eclipse survey</a>). Git only reached 12.8% a year later.</li>
<li class="era__fact">2011-2013: the next era was already here. Symfony 2.0 in Jul 2011, Composer in Mar 2012, AngularJS 1.0 in Jun 2012, then Ansible 1.0, Docker 0.1 and React 0.3 in the spring of 2013. We caught up later.</li>
</ul>
<p class="era__more">More on that: <a href="/article/articles-code-got-cheap-the-plan-didnt">why I wrote my own framework as a student, and why I wouldn't now</a>.</p>
</li>
<li class="era">
<h2 class="era__years">2014-2017 <span class="era__name">Frameworks and a pipeline</span></h2>
<p class="era__felt">Real projects moved to a framework like Symfony, with Composer, tests, CI and the first deployment tools. On the frontend AngularJS was the one we reached for. Node showed up on the backend, still rarely in the projects I saw.</p>
<div class="era__stack"><span class="badge b-symfony">Symfony</span><span class="badge b-php">Composer</span><span class="badge badge--w">AngularJS</span><span class="badge badge--w">CI</span><span class="badge b-docker">Docker</span><span class="badge b-node">Node</span><span class="badge b-mongo">MongoDB</span></div>
<ul class="era__facts">
<li class="era__fact">2014: webpack 1.0 (Feb) and Docker 1.0 (Jun), and "it works on my machine" slowly became "ship the machine".</li>
<li class="era__fact">NoSQL went mainstream. MongoDB, released in Feb 2009, reached 3.0 in Feb 2015 and <a href="https://en.wikipedia.org/wiki/MongoDB">went public</a> on NASDAQ in Oct 2017. That year Stack Overflow first asked about databases, and MongoDB was already fifth, behind MySQL, SQL Server, SQLite and PostgreSQL (<a href="https://survey.stackoverflow.co/2017#technology-_-databases">survey</a>).</li>
<li class="era__fact">2015: Node.js was used by 13.3% of developers, against PHP 29.7%, Python 23.8% and Ruby 8.0% (<a href="https://survey.stackoverflow.co/2015#tech-lang">Stack Overflow survey</a>).</li>
<li class="era__fact">2015: 69.3% of developers used Git, 36.9% SVN, and 9.3% no source control at all (<a href="https://survey.stackoverflow.co/2015#tech-sourcecontrol">Stack Overflow survey</a>).</li>
</ul>
<p class="era__more">For more than ten years this is <a href="/article/articles-i-was-painting-not-programming">how we turned a ticket into code by hand</a>.</p>
</li>
<li class="era">
<h2 class="era__years">2018-2019 <span class="era__name">Mature on both ends</span></h2>
<p class="era__felt">Framework-based development became the default on both sides: a framework on the backend, React or Angular on the frontend, and new backend stacks next to PHP.</p>
<div class="era__stack"><span class="badge b-react">React</span><span class="badge badge--w">Angular</span><span class="badge b-ts">TypeScript</span><span class="badge b-node">Node</span><span class="badge b-go">Go</span><span class="badge b-docker">Kubernetes</span></div>
<ul class="era__facts">
<li class="era__fact">2016: Angular 2.0 (a rewrite, not an update) and Next.js 1.0. Kubernetes 1.0 had shipped a year before.</li>
<li class="era__fact">2018: about 88% of developers on Git. Node.js and Angular were the most used, React was the one people wanted to try (<a href="https://www.freecodecamp.org/news/stack-overflow-2018-developer-survey-faac8d3eb357/">Stack Overflow survey</a>).</li>
<li class="era__fact">2019: jQuery was still used by 48.7% of developers, ahead of React (31.3%) and Angular (30.7%) (<a href="https://survey.stackoverflow.co/2019#technology-_-web-frameworks">Stack Overflow survey</a>). The old code doesn't leave.</li>
<li class="era__fact">Nov 2019: <a href="https://en.wikipedia.org/wiki/GitHub">GitHub Actions</a> launches, and CI moves into the repository.</li>
</ul>
<p class="era__more">What all that machinery ended up costing: <a href="/article/articles-code-got-cheap-the-plan-didnt">a decade of tools built to save effort</a>.</p>
</li>
<li class="era">
<h2 class="era__years">2020-2021 <span class="era__name">Frameworks everywhere, and no-code</span></h2>
<p class="era__felt">More frameworks, more intense. No-code tools took some of the simple sites, and new projects needed a CMS less often. The legacy ones stayed, and someone had to keep them alive.</p>
<div class="era__stack"><span class="badge b-react">React</span><span class="badge b-symfony">Symfony</span><span class="badge badge--w">No-code</span><span class="badge badge--w">Legacy CMS</span></div>
<ul class="era__facts">
<li class="era__fact">2021: Gartner expected the low-code market to grow 23% to $13.8B, pushed by the pandemic (<a href="https://www.gartner.com/en/newsroom/press-releases/2021-02-15-gartner-forecasts-worldwide-low-code-development-technologies-market-to-grow-23-percent-in-2021">Gartner</a>).</li>
<li class="era__fact">Jan 2015: Joomla ran 3.3% of all websites and Drupal 2.0%, second and third after WordPress. By Jan 2022 Shopify, Wix and Squarespace had all passed both (<a href="https://w3techs.com/technologies/history_overview/content_management/all/y">W3Techs</a>).</li>
</ul>
<p class="era__more">Where it went from here: <a href="/article/articles-aicmf-ai-first-cms">why a CMS is now harder and slower than no CMS at all</a>.</p>
</li>
<li class="era era--ai">
<h2 class="era__years">2022-2024 <span class="era__name">Autocomplete and a chat window</span></h2>
<p class="era__felt">The start of the AI era, and it was early. The editor suggested the next line, a chat window answered questions, and we pasted the answers in by hand. The framework work went on as before.</p>
<div class="era__stack"><span class="badge b-llm">Copilot</span><span class="badge b-llm">ChatGPT</span><span class="badge badge--w">Copy-paste</span></div>
<ul class="era__facts">
<li class="era__fact">I remembered this as starting in 2024. It started earlier: GitHub Copilot was previewed in Jun 2021 and <a href="https://github.blog/2022-06-21-github-copilot-is-generally-available-to-all-developers/">opened to everyone</a> on Jun 21, 2022.</li>
<li class="era__fact">Nov 2022: ChatGPT, and everyone outside the industry heard about it too.</li>
</ul>
<p class="era__more">Under the hood: <a href="/article/articles-ai-doom-or-marketing">what a language model actually does with your prompt</a>.</p>
</li>
<li class="era era--ai">
<h2 class="era__years">2025 <span class="era__name">Vibe-coding</span></h2>
<p class="era__felt">The agent wrote the code, and we described what we wanted and took what came back. It felt like magic for a week and like a slot machine after that.</p>
<div class="era__stack"><span class="badge b-llm">Claude Code</span><span class="badge b-llm">Agents</span><span class="badge badge--w">Prompts</span></div>
<ul class="era__facts">
<li class="era__fact">Feb 2, 2025: Andrej Karpathy calls it "vibe coding". By November it was Collins' <a href="https://www.theregister.com/2025/11/06/vibe_coding_escape_the_clutches/">word of the year</a>.</li>
<li class="era__fact">Claude Code: research preview on Feb 24, generally available on May 22, 2025.</li>
</ul>
<p class="era__more">Read: <a href="/article/articles-i-was-painting-not-programming">why vibe-coding didn't work for me</a>, and <a href="/article/articles-you-still-have-to-learn-to-code">why you still have to learn to code</a>.</p>
</li>
<li class="era era--ai">
<h2 class="era__years">2026 <span class="era__name">The harness</span></h2>
<p class="era__felt">Skills came first, then the harness: development environments built around agents and the way they work. A developer manages and supervises, but far more of the work is automated than ever before.</p>
<div class="era__stack"><span class="badge b-skills">Skills</span><span class="badge b-skills">Harness</span><span class="badge b-mcp">MCP</span><span class="badge b-llm">Agents</span></div>
<ul class="era__facts">
<li class="era__fact">Oct 16, 2025: Anthropic ships Agent Skills, and on Dec 18 it becomes an <a href="https://siliconangle.com/2025/12/18/anthropic-makes-agent-skills-open-standard/">open standard</a>.</li>
<li class="era__fact">Feb 11, 2026: OpenAI publishes <a href="https://openai.com/index/harness-engineering/">"Harness engineering"</a>, and the practice gets a name.</li>
</ul>
<p class="era__more">The parts: <a href="/article/articles-code-got-cheap-the-plan-didnt">what goes into a harness</a>, <a href="/article/articles-the-keyboard-i-always-needed">a rules file in practice</a>, and <a href="/article/articles-aicmf-ai-first-cms">the CMS this site runs on, built for agents</a>.</p>
</li>
</ol>

## What actually changed

Line the eras up and the thing we build inside keeps changing. In 2008 it
was a CMS: it decided where the content lived, what a page could be, and
what you could change without breaking it. Then came a framework, then a
framework on both ends. Now it's a harness, and the CMS this site runs on
has shrunk to an index next to it. The frame never went away, it moved from
the product to the way we work.

The unit of work grew along with it. A file you uploaded became a commit,
then a pipeline run, then a prompt, and now a plan with a harness to check
it. The typing kept moving further away from the person.

What I decide hasn't moved at all. In 2008 I decided which files went up to
the server. Now I decide what the agent may build and how we'll both know it
works. The FTP client is gone, and the responsibility for what goes live is
exactly where it always was.
