# CHIMYON SCHOOL — MASTER AI AGENT INSTRUCTION

> **READ THIS FILE FIRST.** This README is the project's source of truth. Any AI coding agent must read it completely before changing or creating code.

## PROJECT MISSION

Build a premium global-level website for **CHIMYON SCHOOL** with an Apple-inspired light, editorial and cinematic interface. The project is content-driven and must remain maintainable with PHP 8.x, JSON, semantic HTML5, modern CSS3 and vanilla JavaScript with zero frontend frameworks.

## NON-NEGOTIABLE TECHNICAL RULES

- Backend: **PHP 8.x**.
- Data/content: **JSON**.
- Frontend: semantic HTML5, CSS3 and vanilla JavaScript.
- No React, Vue, Angular, Next.js, Nuxt, Laravel, WordPress, Bootstrap, Tailwind or Node.js build system.
- Dependencies remain zero unless explicitly approved.
- Escape output correctly.
- Validate uploaded files and prevent executable uploads.
- Never invent real school statistics, teacher information, addresses or achievements.
- **Do not add `.htaccess`.** Server configuration is outside this repository's architecture.

## PROJECT STRUCTURE

```text
chimyon-school/
├── index.php
├── pages/
│   ├── maktab.php
│   ├── talim.php
│   ├── jamoa.php
│   ├── natijalar.php
│   ├── yangiliklar.php
│   ├── galereya.php
│   ├── qabul.php
│   ├── aloqa.php
│   └── oquvchilar.php
├── admin/
│   ├── index.php
│   ├── login.php
│   ├── auth.php
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
├── config/
│   └── admin_secret.example.php
├── data/
├── assets/
│   └── teachers-banner.png
├── media/
│   ├── images/
│   └── videos/
└── README.md
```

## DESIGN SYSTEM

Primary direction: premium + calm + emotional + intelligent + modern + trustworthy.

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

Avoid repetitive card grids, excessive rounded rectangles, cheap glassmorphism, random gradients/blobs, noisy animation and weak typography. Motion must support hierarchy and respect `prefers-reduced-motion`.

## DATA AND MEDIA RULES

Editable public values must come from `data/*.json`. Preserve UTF-8, validate structures and use file locking for writes where appropriate. Media must be validated by MIME type and extension. The existing `assets/teachers-banner.png` is a real project asset and should be reused where appropriate.

## ADMIN RULES

`/admin/` is the content control center. Admin pages require authentication and CSRF protection. Login is key-only. The access key is never stored in plaintext in JSON or Git. A server-side `config/admin_secret.php` may be created by the settings key-rotation flow and stores only a password hash. The repository intentionally contains only `config/admin_secret.example.php`.

The admin editor set covers school, education, team, results, news, gallery, admission, contact, media and global settings.

## CURRENT DEVELOPMENT STATUS

**Phase:** 11 — Final code-level production audit

**Completed:**
- Homepage `index.php` implemented.
- Public internal pages implemented: `maktab.php`, `talim.php`, `jamoa.php`, `natijalar.php`, `yangiliklar.php`, `galereya.php`, `qabul.php`, `aloqa.php`, `oquvchilar.php`.
- Admin control center and content editors implemented.
- Admin key-only authentication implemented.
- Session hardening, CSRF protection and password-hash based access-key verification implemented.
- Settings key rotation implemented without storing the plaintext key in the repository.
- Central media upload/delete manager implemented with extension + MIME validation and 25 MB upload limit.
- JSON content contracts established for the existing data files.
- Existing teacher banner asset retained.
- `.htaccess` intentionally not added.
- Production-oriented security hardening applied to the admin editors.

**Important security deployment note:**
- `config/admin_secret.php` is intentionally **not committed**. Before first admin login on a new host, the server must have a valid `config/admin_secret.php` containing a `password_hash()` value. The settings key-rotation page can then rotate the key after successful authentication.
- The example file is `config/admin_secret.example.php`; never put a real key in Git.

**Current status:**
- Codebase is structurally complete for the current requested scope.
- No `.htaccess` is required or included.
- Repository-level inspection cannot prove the hosting server's PHP version, permissions, upload limits, SSL state or runtime behavior.

**Exact final deployment checks:**
1. Host must run PHP 8.x.
2. Ensure `config/admin_secret.php` exists on the server with a valid password hash before attempting `/admin/login.php`.
3. Ensure `data/` and `media/` are writable by PHP where admin editing/upload requires it.
4. Open the homepage and every public page once after upload.
5. Test admin login, one JSON save, media upload/delete and access-key rotation.
6. Confirm PHP error logs are clean.
7. Do not upload the real access key or any secret to GitHub.

**Known limitation:** browser-level screenshot/console automation is unavailable in the current coding environment, so final host runtime QA must be performed on the actual server.

## DEFINITION OF DONE

A task is complete only when the intended file exists, code is valid, responsive behavior is considered, existing functionality is preserved, editable data uses JSON, media paths work, obvious PHP/console issues are addressed, the visual hierarchy follows this README, and the development status is updated.

## AGENT EXECUTION PROTOCOL

Read this README first. Inspect before changing code. Reuse working implementations. Never blindly overwrite the project, invent facts, create duplicate responsibilities or claim runtime verification that was not performed. After implementation, update this status and record remaining blockers.
