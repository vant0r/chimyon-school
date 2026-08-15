<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$file = $root . '/data/jamoa.json';
$notice = '';
$error = '';
$default = ['intro' => '', 'hero_image' => '', 'team' => []];

function readTeamData(string $file, array $default): array {
    if (!is_file($file)) return $default;
    $raw = file_get_contents($file);
    $data = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($data)) return $default;
    foreach ($default as $key => $value) {
        if (!array_key_exists($key, $data)) $data[$key] = $value;
        if (is_array($value) && !is_array($data[$key])) $data[$key] = $value;
    }
    return $data;
}

function teamRowsToJson(string $value): array {
    $rows = [];
    foreach (preg_split('/\R/', $value) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $decoded = json_decode($line, true);
        if (!is_array($decoded)) throw new RuntimeException('Jamoa qatori JSON object bo‘lishi kerak.');
        $rows[] = $decoded;
    }
    return $rows;
}

function teamToLines(mixed $value): string {
    if (!is_array($value)) return '';
    $lines = [];
    foreach ($value as $member) {
        if (!is_array($member)) continue;
        $encoded = json_encode($member, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded !== false) $lines[] = $encoded;
    }
    return implode("\n", $lines);
}

$data = readTeamData($file, $default);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intro = trim((string)($_POST['intro'] ?? ''));
    $heroImage = trim((string)($_POST['hero_image'] ?? ''));
    $teamRaw = trim((string)($_POST['team'] ?? ''));

    try {
        if ($heroImage !== '' && !preg_match('#^(?:https?://|/|\.\./|media/)#i', $heroImage)) {
            throw new RuntimeException('Hero image path xavfsiz formatda emas.');
        }
        $team = $teamRaw === '' ? [] : teamRowsToJson($teamRaw);
        $data = ['intro' => $intro, 'hero_image' => $heroImage, 'team' => $team];
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false || file_put_contents($file, $json . PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException('JSON faylini saqlash amalga oshmadi.');
        }
        $notice = 'Jamoa kontenti saqlandi.';
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow">
<title>Jamoa — CHIMYON SCHOOL Admin</title>
<style>
:root{--bg:#f5f5f3;--surface:#fff;--text:#111827;--muted:#68707d;--navy:#14213d;--gold:#c6a15b;--line:rgba(17,24,39,.09);--shadow:0 25px 80px rgba(20,33,61,.08)}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1180px,calc(100% - 40px));margin:auto}.top{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{font-size:12px;font-weight:800;letter-spacing:.15em}.back{color:var(--text);text-decoration:none;font-size:13px;font-weight:700}.back:hover{color:var(--gold)}main{padding:70px 0 100px}.eyebrow{margin:0 0 16px;color:var(--gold);font-size:11px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}.intro{display:flex;justify-content:space-between;gap:50px;align-items:end;margin-bottom:55px}.intro h1{margin:0;font-size:clamp(48px,8vw,92px);line-height:.9;letter-spacing:-.065em}.intro p{max-width:390px;margin:0;color:var(--muted);line-height:1.7;font-size:14px}.notice,.error{padding:14px 18px;margin-bottom:22px;border:1px solid var(--line);background:#fff;font-size:13px}.error{border-color:rgba(160,60,50,.18);color:#8b3f36}.form{background:var(--surface);box-shadow:var(--shadow);padding:42px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:34px 44px}.wide{grid-column:1/-1}.field label{display:block;margin-bottom:10px;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.field small{display:block;color:var(--muted);margin-top:7px;font-size:11px;line-height:1.5}.field input,.field textarea{width:100%;border:0;border-bottom:1px solid rgba(17,24,39,.16);background:transparent;border-radius:0;padding:13px 0;font:inherit;font-size:15px;color:var(--text);outline:none}.field textarea{min-height:150px;resize:vertical;border:1px solid var(--line);padding:14px}.field input:focus,.field textarea:focus{border-color:var(--navy)}.actions{display:flex;align-items:center;justify-content:space-between;margin-top:38px;padding-top:25px;border-top:1px solid var(--line);gap:20px}.hint{color:var(--muted);font-size:11px;line-height:1.55;max-width:700px}.save{border:0;background:var(--navy);color:#fff;padding:15px 24px;font:inherit;font-size:12px;font-weight:800;cursor:pointer;letter-spacing:.04em}.save:hover{background:#1b2d4e}.footer{margin-top:25px;color:var(--muted);font-size:11px}code{font-size:11px;color:#4f5968}@media(max-width:760px){.shell{width:min(100% - 26px,600px)}main{padding:48px 0 70px}.intro{display:block}.intro p{margin-top:25px}.form{padding:25px}.grid{grid-template-columns:1fr}.wide{grid-column:auto}.actions{align-items:flex-start;flex-direction:column}.save{width:100%}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto}}
</style>
</head>
<body><div class="shell">
<header class="top"><div class="brand">CHIMYON / ADMIN</div><a class="back" href="index.php">← Control center</a></header>
<main>
<section class="intro"><div><p class="eyebrow">04 / PEOPLE</p><h1>Jamoa.</h1></div><p>O‘qituvchilar va jamoa profil ma’lumotlarini mavjud JSON schema’ni buzmasdan boshqaring.</p></section>
<?php if ($notice): ?><div class="notice" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($error): ?><div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form class="form" method="post" autocomplete="off">
<div class="grid">
<div class="field wide"><label for="intro">Intro</label><textarea id="intro" name="intro" rows="5"><?= htmlspecialchars((string)$data['intro'], ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="field wide"><label for="hero_image">Hero image path</label><input id="hero_image" name="hero_image" value="<?= htmlspecialchars((string)$data['hero_image'], ENT_QUOTES, 'UTF-8') ?>" placeholder="media/images/..."><small>Faqat mavjud yoki keyinchalik media tizimiga ulanadigan xavfsiz path.</small></div>
<div class="field wide"><label for="team">Team records</label><textarea id="team" name="team" rows="14" spellcheck="false" placeholder='Har bir qator bitta JSON object. Masalan: {"name":"...","role":"...","image":"..."}'><?= htmlspecialchars(teamToLines($data['team']), ENT_QUOTES, 'UTF-8') ?></textarea><small>Har bir qator alohida JSON object bo‘lishi kerak. Mavjud obyekt fieldlari o‘zgartirilmay saqlanadi. Uydirma teacher ma’lumotlarini kiritmang.</small></div>
</div>
<div class="actions"><div class="hint">JSON UTF-8 · pretty-print · file locking. Team editor mavjud schema’ni universal JSON object sifatida saqlaydi.</div><button class="save" type="submit">SAVE CHANGES →</button></div>
</form>
<p class="footer">data/jamoa.json · PHP 8.x · zero dependencies · current records: <?= count(is_array($data['team']) ? $data['team'] : []) ?></p>
</main></div></body></html>
