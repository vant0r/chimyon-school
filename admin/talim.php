<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$file = $root . '/data/talim.json';
$notice = '';
$error = '';

$default = [
    'title' => 'Ta’lim',
    'intro' => '',
    'hero_image' => '',
    'method' => [],
    'programs' => [],
    'principles' => [],
];

function readEducation(string $file, array $default): array
{
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

function linesToArray(string $value): array
{
    $items = [];
    foreach (preg_split('/\R/', $value) ?: [] as $line) {
        $line = trim($line);
        if ($line !== '') $items[] = $line;
    }
    return $items;
}

function arrayToLines(mixed $value): string
{
    if (!is_array($value)) return '';
    $out = [];
    foreach ($value as $item) {
        if (is_scalar($item)) $out[] = (string)$item;
        elseif (is_array($item)) {
            // Preserve complex existing objects instead of flattening them.
            $encoded = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($encoded !== false) $out[] = $encoded;
        }
    }
    return implode("\n", $out);
}

$data = readEducation($file, $default);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $intro = trim((string)($_POST['intro'] ?? ''));
    $heroImage = trim((string)($_POST['hero_image'] ?? ''));

    if ($title === '') {
        $error = 'Sarlavha bo‘sh bo‘lishi mumkin emas.';
    } elseif ($heroImage !== '' && !preg_match('#^(?:https?://|/|\.\./|media/)#i', $heroImage)) {
        $error = 'Hero image path xavfsiz formatda emas.';
    } else {
        $data['title'] = $title;
        $data['intro'] = $intro;
        $data['hero_image'] = $heroImage;
        $data['method'] = linesToArray((string)($_POST['method'] ?? ''));
        $data['programs'] = linesToArray((string)($_POST['programs'] ?? ''));
        $data['principles'] = linesToArray((string)($_POST['principles'] ?? ''));

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false || file_put_contents($file, $json . PHP_EOL, LOCK_EX) === false) {
            $error = 'JSON faylini saqlash amalga oshmadi.';
        } else {
            $notice = 'Ta’lim kontenti saqlandi.';
        }
    }
}
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Ta’lim — CHIMYON SCHOOL Admin</title>
<style>
:root{--bg:#f5f5f3;--surface:#fff;--text:#111827;--muted:#68707d;--navy:#14213d;--gold:#c6a15b;--line:rgba(17,24,39,.09);--shadow:0 25px 80px rgba(20,33,61,.08)}
*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1180px,calc(100% - 40px));margin:auto}.top{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{font-size:12px;font-weight:800;letter-spacing:.15em}.back{color:var(--text);text-decoration:none;font-size:13px;font-weight:700}.back:hover{color:var(--gold)}main{padding:70px 0 100px}.eyebrow{margin:0 0 16px;color:var(--gold);font-size:11px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}.intro{display:flex;justify-content:space-between;gap:50px;align-items:end;margin-bottom:55px}.intro h1{margin:0;font-size:clamp(48px,8vw,92px);line-height:.9;letter-spacing:-.065em}.intro p{max-width:390px;margin:0;color:var(--muted);line-height:1.7;font-size:14px}.notice,.error{padding:14px 18px;margin-bottom:22px;border:1px solid var(--line);background:#fff;font-size:13px}.error{border-color:rgba(160,60,50,.18);color:#8b3f36}.form{background:var(--surface);box-shadow:var(--shadow);padding:42px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:34px 44px}.wide{grid-column:1/-1}.field label{display:block;margin-bottom:10px;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.field small{display:block;color:var(--muted);margin-top:7px;font-size:11px;line-height:1.5}.field input,.field textarea{width:100%;border:0;border-bottom:1px solid rgba(17,24,39,.16);background:transparent;border-radius:0;padding:13px 0;font:inherit;font-size:15px;color:var(--text);outline:none}.field textarea{min-height:150px;resize:vertical;border:1px solid var(--line);padding:14px}.field input:focus,.field textarea:focus{border-color:var(--navy)}.section{grid-column:1/-1;padding-top:8px;border-top:1px solid var(--line)}.section-title{display:flex;align-items:baseline;justify-content:space-between;gap:20px;margin-bottom:18px}.section-title strong{font-size:18px;letter-spacing:-.02em}.section-title span{color:var(--muted);font-size:11px}.actions{display:flex;align-items:center;justify-content:space-between;margin-top:38px;padding-top:25px;border-top:1px solid var(--line);gap:20px}.hint{color:var(--muted);font-size:11px;line-height:1.5}.save{border:0;background:var(--navy);color:#fff;padding:15px 24px;font:inherit;font-size:12px;font-weight:800;cursor:pointer;letter-spacing:.04em}.save:hover{background:#1b2d4e}.footer{margin-top:25px;color:var(--muted);font-size:11px}@media(max-width:760px){.shell{width:min(100% - 26px,600px)}main{padding:48px 0 70px}.intro{display:block}.intro p{margin-top:25px}.form{padding:25px}.grid{grid-template-columns:1fr}.wide,.section{grid-column:auto}.section-title{display:block}.section-title span{display:block;margin-top:5px}.actions{align-items:flex-start;flex-direction:column}.save{width:100%}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto}}
</style>
</head>
<body>
<div class="shell">
<header class="top"><div class="brand">CHIMYON / ADMIN</div><a class="back" href="index.php">← Control center</a></header>
<main>
<section class="intro"><div><p class="eyebrow">03 / EDUCATION SYSTEM</p><h1>Ta’lim.</h1></div><p>Ta’lim sahifasining metodikasi, dasturlari va prinsiplarini JSON orqali boshqaring. Tasdiqlanmagan faktlarni kiritmang.</p></section>
<?php if ($notice): ?><div class="notice" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<?php if ($error): ?><div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
<form class="form" method="post" autocomplete="off">
<div class="grid">
<div class="field"><label for="title">Sarlavha</label><input id="title" name="title" value="<?= htmlspecialchars((string)$data['title'], ENT_QUOTES, 'UTF-8') ?>" required></div>
<div class="field"><label for="hero_image">Hero image path</label><input id="hero_image" name="hero_image" value="<?= htmlspecialchars((string)$data['hero_image'], ENT_QUOTES, 'UTF-8') ?>" placeholder="media/images/..."><small>Faqat mavjud yoki keyinchalik media tizimiga ulanadigan xavfsiz path kiriting.</small></div>
<div class="field wide"><label for="intro">Intro</label><textarea id="intro" name="intro" rows="5"><?= htmlspecialchars((string)$data['intro'], ENT_QUOTES, 'UTF-8') ?></textarea></div>
<div class="section"><div class="section-title"><strong>Method</strong><span>Har bir element — yangi qator</span></div><div class="field"><textarea id="method" name="method" aria-label="Ta’lim metodikasi" placeholder="Masalan: ..."><?= htmlspecialchars(arrayToLines($data['method']), ENT_QUOTES, 'UTF-8') ?></textarea></div></div>
<div class="section"><div class="section-title"><strong>Programs</strong><span>Har bir element — yangi qator</span></div><div class="field"><textarea id="programs" name="programs" aria-label="Ta’lim dasturlari" placeholder="Masalan: ..."><?= htmlspecialchars(arrayToLines($data['programs']), ENT_QUOTES, 'UTF-8') ?></textarea></div></div>
<div class="section"><div class="section-title"><strong>Principles</strong><span>Har bir element — yangi qator</span></div><div class="field"><textarea id="principles" name="principles" aria-label="Ta’lim prinsiplari" placeholder="Masalan: ..."><?= htmlspecialchars(arrayToLines($data['principles']), ENT_QUOTES, 'UTF-8') ?></textarea></div></div>
</div>
<div class="actions"><div class="hint">JSON UTF-8 · pretty-print · file locking. Mavjud schema saqlanadi.</div><button class="save" type="submit">SAVE CHANGES →</button></div>
</form>
<p class="footer">data/talim.json · PHP 8.x · zero dependencies</p>
</main>
</div>
</body>
</html>
