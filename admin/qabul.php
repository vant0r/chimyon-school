<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$file = $root . '/data/qabul.json';
$notice = '';
$error = '';
$default = ['title' => 'Qabul', 'intro' => '', 'hero_image' => '', 'steps' => [], 'contact' => []];

function readAdmission(string $file, array $default): array {
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

function recordsToLines(mixed $value): string {
    if (!is_array($value)) return '';
    $lines = [];
    foreach ($value as $item) {
        if (!is_array($item)) continue;
        $encoded = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encoded !== false) $lines[] = $encoded;
    }
    return implode("\n", $lines);
}

function linesToRecords(string $value, string $label): array {
    $records = [];
    foreach (preg_split('/\R/', $value) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') continue;
        $decoded = json_decode($line, true);
        if (!is_array($decoded)) throw new RuntimeException("$label: har bir qator JSON object bo‘lishi kerak.");
        $records[] = $decoded;
    }
    return $records;
}

function contactToText(mixed $value): string {
    if (!is_array($value) || $value === []) return '';
    $encoded = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $encoded === false ? '' : $encoded;
}

function parseContact(string $value): array {
    $value = trim($value);
    if ($value === '') return [];
    $decoded = json_decode($value, true);
    if (!is_array($decoded)) throw new RuntimeException('Contact JSON object yoki array bo‘lishi kerak.');
    return $decoded;
}

$data = readAdmission($file, $default);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $intro = trim((string)($_POST['intro'] ?? ''));
    $heroImage = trim((string)($_POST['hero_image'] ?? ''));
    $stepsRaw = trim((string)($_POST['steps'] ?? ''));
    $contactRaw = trim((string)($_POST['contact'] ?? ''));

    try {
        if ($title === '') throw new RuntimeException('Sarlavha bo‘sh bo‘lishi mumkin emas.');
        if ($heroImage !== '' && !preg_match('#^(?:https?://|/|\.\./|media/)#i', $heroImage)) {
            throw new RuntimeException('Hero image path xavfsiz formatda emas.');
        }

        $data = [
            'title' => $title,
            'intro' => $intro,
            'hero_image' => $heroImage,
            'steps' => $stepsRaw === '' ? [] : linesToRecords($stepsRaw, 'Steps'),
            'contact' => parseContact($contactRaw)
        ];

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false || file_put_contents($file, $json . PHP_EOL, LOCK_EX) === false) {
            throw new RuntimeException('JSON faylini saqlash amalga oshmadi.');
        }
        $notice = 'Qabul kontenti saqlandi.';
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow">
<title>Qabul — CHIMYON SCHOOL Admin</title>
<style>
:root{--bg:#f5f5f3;--surface:#fff;--text:#111827;--muted:#68707d;--navy:#14213d;--gold:#c6a15b;--line:rgba(17,24,39,.09);--shadow:0 25px 80px rgba(20,33,61,.08)}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1180px,calc(100% - 40px));margin:auto}.top{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{font-size:12px;font-weight:800;letter-spacing:.15em}.back{color:var(--text);text-decoration:none;font-size:13px;font-weight:700}.back:hover{color:var(--gold)}main{padding:70px 0 100px}.eyebrow{margin:0 0 16px;color:var(--gold);font-size:11px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}.intro{display:flex;justify-content:space-between;gap:50px;align-items:end;margin-bottom:55px}.intro h1{margin:0;font-size:clamp(48px,8vw,92px);line-height:.9;letter-spacing:-.065em}.intro p{max-width:390px;margin:0;color:var(--muted);line-height:1.7;font-size:14px}.notice,.error{padding:14px 18px;margin-bottom:22px;border:1px solid var(--line);background:#fff;font-size:13px}.error{border-color:rgba(160,60,50,.18);color:#8b3f36}.form{background:var(--surface);box-shadow:var(--shadow);padding:42px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:34px 44px}.wide{grid-column:1/-1}.field label{display:block;margin-bottom:10px;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.field small{display:block;color:var(--muted);margin-top:7px;font-size:11px;line-height:1.55}.field input,.field textarea{width:100%;border:0;border-bottom:1px solid rgba(17,24,39,.16);background:transparent;border-radius:0;padding:13px 0;font:inherit;font-size:15px;color:var(--text);outline:none}.field textarea{min-height:160px;resize:vertical;border:1px solid var(--line);padding:14px}.field input:focus,.field textarea:focus{border-color:var(--navy)}.guide{grid-column:1/-1;padding:20px 0 0;border-top:1px solid var(--line)}.guide strong{display:block;margin-bottom:8px;font-size:13px}.guide p{margin:0;color:var(--muted);font-size:11px;line-height:1.7}.actions{display:flex;align-items:center;justify-content:space-between;margin-top:38px;padding-top:25px;border-top:1px solid var(--line);gap:20px}.hint{color:var(--muted);font-size:11px;line-height:1.55;max-width:700px}.save{border:0;background:var(--navy);color:#fff;padding:15px 24px;font:inherit;font-size:12px;font-weight:800;cursor:pointer;letter-spacing:.04em}.save:hover{background:#1b2d4e}.footer{margin-top:25px;color:var(--muted);font-size:11px}@media(max-width:760px){.shell{width:min(100% - 26px,600px)}main{padding:48px 0 70px}.intro{display:block}.intro p{margin-top:25px}.form{padding:25px}.grid{grid-template-columns:1fr}.wide,.guide{grid-column:auto}.actions{align-items:flex-start;flex-direction:column}.save{width:100%}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto}}
</style>
</head>
<body><div class="shell">
<header class="top"><div class="brand">CHIMYON / ADMIN</div><a class="back" href="index.php">← Control center</a></header>
<main>
<section class="intro"><div><p class="eyebrow">08 / ADMISSIONS</p><h1>Qabul.</h1></div><p>Qabul jarayoni va aloqa ma’lumotlarini boshqaring. Faqat tasdiqlangan maktab ma’lumotlarini kiriting.</p></section>
<?php if($notice):?><div class="notice" role="status"><?=htmlspecialchars($notice,ENT_QUOTES,'UTF-8')?></div><?php endif;?><?php if($error):?><div class="error" role="alert"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></div><?php endif;?>
<form class="form" method="post" autocomplete="off"><div class="grid">
<div class="field"><label for="title">Sarlavha</label><input id="title" name="title" value="<?=htmlspecialchars((string)$data['title'],ENT_QUOTES,'UTF-8')?>" required></div>
<div class="field"><label for="hero_image">Hero image path</label><input id="hero_image" name="hero_image" value="<?=htmlspecialchars((string)$data['hero_image'],ENT_QUOTES,'UTF-8')?>" placeholder="media/images/..."><small>Faqat mavjud media path yoki ishonchli URL.</small></div>
<div class="field wide"><label for="intro">Intro</label><textarea id="intro" name="intro" rows="5"><?=htmlspecialchars((string)$data['intro'],ENT_QUOTES,'UTF-8')?></textarea></div>
<div class="guide"><strong>Admission steps</strong><p>Har bir qator bitta JSON object. Masalan: <code>{"title":"...","description":"..."}</code>. Mavjud fieldlarni saqlang va uydirma qabul talablari yaratmang.</p></div>
<div class="field wide"><label for="steps">Steps</label><textarea id="steps" name="steps" rows="16" spellcheck="false" placeholder='{"title":"...","description":"..."}'><?=htmlspecialchars(recordsToLines($data['steps']),ENT_QUOTES,'UTF-8')?></textarea><small>Har bir qatorda alohida JSON object.</small></div>
<div class="guide"><strong>Contact</strong><p>Contact mavjud schema bo‘yicha JSON object yoki array sifatida saqlanadi. Bo‘sh qoldirish mumkin.</p></div>
<div class="field wide"><label for="contact">Contact JSON</label><textarea id="contact" name="contact" rows="10" spellcheck="false" placeholder='{"phone":"...","email":"...","address":"..."}'><?=htmlspecialchars(contactToText($data['contact']),ENT_QUOTES,'UTF-8')?></textarea><small>Valid JSON object/array kiriting. Oddiy matn qabul qilinmaydi.</small></div>
</div><div class="actions"><div class="hint">JSON UTF-8 · pretty-print · file locking · existing schema preserved.</div><button class="save" type="submit">SAVE CHANGES →</button></div></form>
<p class="footer">data/qabul.json · PHP 8.x · zero dependencies · current steps: <?=count(is_array($data['steps'])?$data['steps']:[]) ?></p>
</main></div></body></html>
