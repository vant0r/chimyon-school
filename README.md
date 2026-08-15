# CHIMYON SCHOOL — MASTER AI AGENT INSTRUCTION

> **READ THIS FILE FIRST.** This README is the project's source of truth. Any AI coding agent must read it completely before changing or creating code.

## 1. PROJECT MISSION

Build a premium global-level website for **CHIMYON SCHOOL**.

The target is NOT a generic school template. The experience must feel like a premium international education brand: calm, emotional, editorial, cinematic, precise and highly polished.

The public interface uses an **Apple-inspired light design**: an open white / soft-gray canvas with carefully placed typography, photography and components on top of it. The page must never feel like a collection of repetitive cards.

The website is content-driven. Administrators must be able to control text, images, videos, backgrounds, teachers, results, news, gallery, admission information, contact information and global settings without editing page source code.

---

## 2. NON-NEGOTIABLE TECHNICAL RULES

- Backend: **PHP 8.x**.
- Data/content: **JSON**.
- Frontend markup, CSS and JavaScript may live inside PHP files unless a later architecture decision explicitly requires otherwise.
- Do NOT introduce React, Vue, Angular, Next.js, Nuxt, Laravel, WordPress, Bootstrap, Tailwind or other frameworks.
- Do NOT introduce Node.js build systems.
- Keep dependencies at zero unless explicitly approved.
- Use semantic HTML5, modern CSS3 and vanilla JavaScript.
- Use secure PHP file handling and JSON encoding/decoding.
- Escape output correctly.
- Validate uploaded files and prevent executable uploads in media directories.
- Never hard-code important editable content into templates.
- Never invent real school statistics, teacher information, addresses or achievements. Use existing data or clearly marked placeholders.

---

## 3. CURRENT PROJECT STRUCTURE

```text
chimyon-school/
│
├── index.php
│
├── pages/
│   ├── maktab.php
│   ├── talim.php
│   ├── jamoa.php
│   ├── natijalar.php
│   ├── yangiliklar.php
│   ├── galereya.php
│   ├── qabul.php
│   └── aloqa.php
│
├── admin/
│   ├── index.php
│   ├── maktab.php
│   ├── talim.php
│   ├── jamoa.php
│   ├── natijalar.php
│   ├── yangiliklar.php
│   ├── galereya.php
│   ├── qabul.php
│   ├── aloqa.php
│   ├── media.php
│   └── settings.php
│
├── data/
│   ├── settings.json
│   ├── maktab.json
│   ├── talim.json
│   ├── jamoa.json
│   ├── natijalar.json
│   ├── yangiliklar.json
│   ├── galereya.json
│   └── qabul.json
│
├── media/
│   ├── images/
│   └── videos/
│
└── README.md
```

Do not create a second competing architecture. Extend this structure only when a real requirement demands it.

---

## 4. PAGE PURPOSES

### `/index.php` — HOME
The brand introduction. It must be concise, visual and emotionally strong.

Required direction:
- Separate CHIMYON SCHOOL brand heading above the hero image.
- Large teacher group image from the media system; never bury it inside a small card.
- The teacher image is the primary visual hero.
- No text placed over teachers' faces.
- Light open background.
- Strong editorial typography.
- One-scroll cinematic transition: hero moves/fades/softly blurs away and the next content continues naturally.
- Short brand statement.
- Short preview of strengths.
- Teacher preview.
- Results preview.
- Strong admission CTA.

The homepage must NOT contain the full content of every other page.

### `/pages/maktab.php` — SCHOOL
Explain the school's identity: introduction, mission, vision, values, education philosophy, environment and why Chimyon School.

### `/pages/talim.php` — EDUCATION
Explain subjects, programs, methodology, learning process and groups/classes.

### `/pages/jamoa.php` — TEAM
Large editorial teacher presentation with portrait, name, subject, experience, biography, achievements, social links where available and display order.

### `/pages/natijalar.php` — RESULTS
Only verifiable student achievements, certificates, exams, olympiads, admissions, statistics and student stories.

### `/pages/yangiliklar.php` — NEWS
News list, categories, cover image, date, article content and media.

### `/pages/galereya.php` — GALLERY
Cinematic/editorial school, teachers, students, lessons and events gallery with images/videos.

### `/pages/qabul.php` — ADMISSION
Admission information, programs, requirements, process, schedule, application form and CTA.

### `/pages/aloqa.php` — CONTACT
Address, phone, Telegram, Instagram, email, working hours, map and contact form.

---

## 5. ADMIN PURPOSE

`/admin/` is the content control center.

Admin must eventually control global settings; homepage hero/content/visibility/order; school content; full teacher CRUD; education programs; results; news; gallery; admission; contact; and a central media library. Every major visual asset must be replaceable from Admin.

---

## 6. JSON DATA CONTRACT

Every editable public value must come from `data/*.json`.

Example concept:

```json
{
  "title": "...",
  "description": "...",
  "image": "media/images/example.webp",
  "visible": true,
  "order": 1
}
```

Do not create random JSON formats per page. Keep schemas predictable and documented when complex.

Use safe read/write helpers. When writing JSON: preserve UTF-8, preserve existing data, validate structure, use file locking where appropriate and fail safely.

---

## 7. MEDIA RULES

Media is content, not decoration.

Admin must eventually support upload, replace, delete, alt text, category, ordering and visibility.

Allowed images should be validated by MIME type and extension. Never execute uploaded files. Large images must be optimized and lazy-loaded where appropriate.

The existing teacher banner asset, if present in the repository, must be reused rather than replaced with invented imagery.

---

## 8. DESIGN SYSTEM

### Canvas
Primary background: open white / very soft gray.

Suggested tokens:

```css
--bg: #F5F5F3;
--surface: #FFFFFF;
--surface-soft: #EEEEEC;
--text: #111827;
--text-muted: #6B7280;
--navy: #14213D;
--gold: #C6A15B;
--border: rgba(17,24,39,.08);
```

These are starting tokens, not an excuse to add random colors.

### Component philosophy
Build components on the open canvas: typography, image compositions, editorial blocks, selective glass overlays, floating controls, statistics, teacher profiles, media compositions and CTA. Avoid endless rounded cards.

### Glass
Use glass selectively: translucent white surface, backdrop blur, subtle border/highlight and soft shadow. Glass must support hierarchy, not become the entire design.

### Typography
Typography is a major visual element. Use strong hierarchy, generous spacing and large editorial headings. Do not fill the interface with tiny text.

### Motion
Motion must be subtle, smooth and purposeful: fade, translate, scale, blur, parallax, reveal and hover/focus interaction. Support `prefers-reduced-motion`. Never use noisy bouncing, excessive particle effects or distracting infinite animations.

---

## 9. QUALITY BAR

Every implementation must pass this mental test:

**Would this look credible as the website of a premium international private school?**

Reject anything that looks like a generic Bootstrap/AI landing page, repetitive card grid, cheap glassmorphism, excessive gradients/rounded rectangles, random blobs, weak typography, poor image cropping or crowded sections.

The experience must feel:

**premium + calm + emotional + intelligent + modern + trustworthy.**

---

## 10. RESPONSIVE RULE

Do not simply shrink desktop. Desktop, tablet and mobile must each have intentional composition.

Mobile priorities:
1. brand
2. hero image
3. typography
4. primary CTA
5. navigation
6. readable spacing

Do not let animation damage usability or performance.

---

# 11. AI AGENT EXECUTION PROTOCOL — MANDATORY

Before writing ANY code, the AI agent MUST:

1. Read this entire README.
2. Inspect the repository tree.
3. Inspect the target file(s) that will be changed.
4. Inspect relevant JSON and media assets.
5. Determine what is already implemented.
6. Do NOT rewrite working code merely because a different implementation is preferred.
7. Identify the exact next incomplete task from the progress section below.

The agent must work incrementally.

### NEVER
- blindly overwrite the project
- recreate completed pages
- invent missing content as fact
- change the architecture without a documented reason
- create duplicate files for the same responsibility
- leave dead/unused implementations after replacing something
- claim a task is complete without inspecting the resulting code

---

# 12. README PROGRESS PROTOCOL — MANDATORY

This README is also the project's persistent development memory.

At the beginning of every session:

**READ `CURRENT DEVELOPMENT STATUS` FIRST.**

Then inspect the code and continue from the exact unfinished point.

After completing work, the agent MUST:

1. Update `CURRENT DEVELOPMENT STATUS`.
2. Replace the previous status with the new exact status; do not append contradictory old status.
3. Record what was completed.
4. Record what remains.
5. Record the exact next execution step.
6. Record important files changed.
7. Record any known issue/blocker.
8. Commit the implementation and README update together when practical.

Never leave the README claiming an older state after code has moved forward.

---

# 13. CURRENT DEVELOPMENT STATUS

**Phase:** 1 — Homepage Design + Implementation

**Completed:**
- Full README/master instruction audited before implementation.
- Repository tree, existing homepage implementation, JSON data and teacher banner asset audited.
- `assets/teachers-banner.png` retained as the primary hero/team visual.
- Homepage uses `/index.php` with PHP 8.x, semantic HTML5, CSS3 and vanilla JavaScript.
- Homepage content remains data-driven through `data/home.json` with escaped output and safe JSON loading.
- Implemented Header/Navigation, Hero, Brand Statement, Why Chimyon preview, Teachers preview, Results preview, Admission CTA and Footer.
- Refined the homepage during visual QA: removed the decorative radial hero background, strengthened the open editorial composition, kept the teacher image dominant and added an intentional mobile navigation.
- Hero scroll now uses progressive scale, translate, opacity and subtle blur while respecting `prefers-reduced-motion`.
- Added IntersectionObserver reveal motion for content sections.
- Added mobile/tablet-specific composition rather than simply shrinking desktop.
- Kept unsupported teacher, results, admission and contact facts as explicit data-pending states; no facts were invented.
- Homepage remains framework-free with zero dependencies.

**Files changed:**
- `index.php` — homepage visual QA/refinement, cinematic scroll, responsive navigation and accessibility improvements.
- `data/home.json` — existing homepage data source retained unchanged during this QA pass.
- `README.md` — current development status updated.

**Current status:**
- Homepage implementation + first visual QA/refinement pass complete.
- No other public pages implemented.
- No Admin implemented.
- No separate teacher/result/admission/contact JSON sources exist yet.
- `assets/teachers-banner.png` remains the current source for the hero/team image because the planned media library is not implemented yet.

**Next exact execution step:**
- Perform final browser-level visual QA of `/index.php` on the actual deployment at desktop and mobile widths. Verify image cropping, scroll transition, mobile menu, PHP render, console errors and accessibility. Fix only verified homepage issues. Do not begin other pages or Admin until homepage approval.

**Known issues:**
- Browser screenshot/console automation is unavailable in the current coding environment, so the latest pass was code-level QA only.
- Repository data does not yet contain structured individual teachers, verified results, admission details or contact details; the homepage correctly avoids inventing them.
- The existing teacher asset is under `assets/` rather than the planned future `media/` library.

**After homepage approval:**
1. `/pages/maktab.php`
2. `/pages/jamoa.php`
3. `/pages/talim.php`
4. `/pages/natijalar.php`
5. `/pages/yangiliklar.php`
6. `/pages/galereya.php`
7. `/pages/qabul.php`
8. `/pages/aloqa.php`
9. Admin
10. Media management
11. SEO/settings
12. Final QA and optimization

---

# 14. DEFINITION OF DONE

A task is NOT complete because code was written.

A task is complete only when:
- the intended file exists
- the code is valid
- the page renders
- responsive behavior is considered
- existing functionality was preserved
- editable data uses JSON where required
- media paths work
- no obvious console/PHP errors remain
- the visual hierarchy matches this README
- README progress is updated
- the next exact task is documented

---

# 15. AGENT RESPONSE FORMAT

After each implementation, report briefly:

```text
DONE

Completed:
- ...

Files changed:
- ...

Current status:
- ...

Next exact step:
- ...

Known issues:
- None / ...
```

Do not write long explanations when a concise implementation report is enough.

---

# FINAL DIRECTIVE

**Read this README completely before coding. Inspect first. Plan second. Code third. Verify fourth. Update README fifth.**

Continue from the exact `CURRENT DEVELOPMENT STATUS` point.

The goal is not to produce many files quickly.

The goal is to build a coherent, premium, maintainable Chimyon School product with disciplined incremental execution.
