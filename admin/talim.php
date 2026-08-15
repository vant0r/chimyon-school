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

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function arrayToEditor(mixed $value): string
{
    if (!is_array($value)) return '';

    $lines = [];
    foreach ($value as $item) {
        if (is_array($item)) {
            $encoded = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($encoded !== false) $lines[] = $encoded;
        } elseif (is_scalar($item)) {
            $lines[] = (string)$item;
        }
    }

    return implode("\n", $lines);
}

function editorToArray(string $value, string $label): array
{
    $items = [];

    foreach (preg_split('/\R/', $value) ?: [] as $line) {
        $line = trim($line);
        if ($line === '') continue;

        if ($line[0] === '{' || $line[0] === '[') {
            $decoded = json_decode($line, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException("$label: JSON qatori noto‘g‘ri.");
            }
            $items[] = $decoded;
        } else {
            $items[] = $line;
        }
    }

    return $items;
}

$data = readEducation($file, $default);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim((string)($_POST['title'] ?? ''));
    $intro = trim((string)($_POST['intro'] ?? ''));
    $heroImage = trim((string)($_POST['hero_image'] ?? ''));

    try {
        if ($title === '') {
            throw new RuntimeException('Sarlavha bo‘sh bo‘lishi mumkin emas.');
        }

        if (
            $heroImage !== '' &&
            !preg_match('#^(?:https?://|/|\.\./|media/)#i', $heroImage)
        ) {
            throw new RuntimeException('Hero image path xavfsiz formatda emas.');
        }

        $data['title'] = $title;
        $data['intro'] = $intro;
        $data['hero_image'] = $heroImage;
        $data['method'] = editorToArray((string)($_POST['method'] ?? ''), 'Method');
        $data['programs'] = editorToArray((string)($_POST['programs'] ?? ''), 'Programs');
        $data['principles'] = editorToArray((string)($_POST['principles'] ?? ''), 'Principles');

        $json = json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if (
            $json === false ||
            file_put_contents($file, $json . PHP_EOL, LOCK_EX) === false
        ) {
            throw new RuntimeException('JSON faylini saqlash amalga oshmadi.');
        }

        $notice = 'Ta’lim kontenti saqlandi.';
    } catch (RuntimeException $ex) {
        $error = $ex->getMessage();
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
:root{--bg:#f4f4f1;--paper:#fff;--ink:#101722;--muted:#6b7380;--navy:#14213d;--gold:#b99758;--line:rgba(16,23,34,.1);--shadow:0 28px 90px rgba(20,33,61,.08)}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1180px,calc(100% - 40px));margin:auto}.top{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{font-size:12px;font-weight:850;letter-spacing:.15em}.back{color:var(--ink);text-decoration:none;font-size:12px;font-weight:750}.back:hover{color:var(--gold)}main{padding:72px 0 100px}.heading{display:flex;align-items:end;justify-content:space-between;gap:50px;margin-bottom:58px}.eyebrow{margin:0 0 15px;color:var(--gold);font-size:10px;font-weight:850;letter-spacing:.2em;text-transform:uppercase}.heading h1{margin:0;font-size:clamp(56px,8vw,96px);line-height:.86;letter-spacing:-.075em}.heading p{max-width:390px;margin:0;color:var(--muted);font-size:13px;line-height:1.75}.notice,.error{padding:14px 18px;margin-bottom:22px;background:#fff;border:1px solid var(--line);font-size:13px}.error{color:#8b3f36;border-color:rgba(139,63,54,.18)}.form{background:var(--paper);box-shadow:var(--shadow);padding:44px}.identity{display:grid;grid-template-columns:1fr 1fr;gap:30px 44px;padding-bottom:38px;border-bottom:1px solid var(--line)}.wide{grid-column:1/-1}.field label{display:block;margin-bottom:9px;font-size:10px;font-weight:850;letter-spacing:.13em;text-transform:uppercase}.field input,.field textarea{width:100%;border:0;border-bottom:1px solid rgba(16,23,34,.16);background:transparent;color:var(--ink);outline:none;padding:13px 0;font:inherit;font-size:15px}.field textarea{min-height:150px;resize:vertical;border:1px solid var(--line);padding:14px;line-height:1.65}.field input:focus,.field textarea:focus{border-color:var(--navy)}.field small{display:block;margin-top:8px;color:var(--muted);font-size:10px;line-height:1.55}.section{padding:38px 0;border-bottom:1px solid var(--line)}.section:last-of-type{border-bottom:0}.section-head{display:flex;align-items:baseline;justify-content:space-between;gap:20px;margin-bottom:18px}.section-head h2{margin:0;font-size:22px;letter-spacing:-.04em}.section-head span{color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.12em}.section textarea{min-height:180px;font-family:"SFMono-Regular",Consolas,"Liberation Mono",monospace;font-size:12px;line-height:1.75}.section-note{margin:0 0 14px;color:var(--muted);font-size:11px;line-height:1.6}.actions{display:flex;align-items:center;justify-content:space-between;gap:24px;padding-top:28px;margin-top:8px;border-top:1px solid var(--line)}.hint{max-width:650px;color:var(--muted);font-size:10px;line-height:1.6}.save{border:0;background:var(--navy);color:#fff;padding:15px 24px;cursor:pointer;font:inherit;font-size:11px;font-weight:850;letter-spacing:.06em}.save:hover{background:#1d3154}.footer{margin-top:24px;color:var(--muted);font-size:10px;display:flex;justify-content:space-between;gap:20px}@media(max-width:720px){.shell{width:min(100% - 26px,600px)}main{padding:48px 0 70px}.heading{display:block}.heading p{margin-top:24px}.form{padding:26px}.identity{grid-template-columns:1fr}.wide{grid-column:auto}.section-head{display:block}.section-head span{display:block;margin-top:6px}.actions{align-items:stretch;flex-direction:column}.save{width:100%}.footer{display:block}.footer span{display:block;margin-bottom:6px}}@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
</style>
</head>
<body>
<div class="shell">
<header class="top"><div class="brand">CHIMYON / ADMIN</div><a class="back" href="index.php">← Control center</a></header>
<main>
<section class="heading"><div><p class="eyebrow">03 / EDUCATION SYSTEM</p><h1>Ta’lim.</h1></div><p>Ta’lim metodikasi, dasturlari va prinsiplarini bitta editorial boshqaruv qatlamidan tahrirlang. Mavjud JSON schema saqlanadi.</p></section>
<?php if($notice):?><div class="notice" role="status"><?=e($notice)?></div><?php endif;?>
<?php if($error):?><div class="error" role="alert"><?=e($error)?></div><?php endif;?>
<form class="form" method="post" autocomplete="off">
<section class="identity">
<div class="field"><label for="title">Sarlavha</label><input id="title" name="title" value="<?=e($data['title'])?>" required></div>
<div class="field"><label for="hero_image">Hero image path</label><input id="hero_image" name="hero_image" value="<?=e($data['hero_image'])?>" placeholder="media/images/..."><small>Faqat mavjud media path yoki ishonchli URL.</small></div>
<div class="field wide"><label for="intro">Intro</label><textarea id="intro" name="intro" rows="5"><?=e($data['intro'])?></textarea></div>
</section>
<section class="section"><div class="section-head"><h2>Method</h2><span>01 / Methodology</span></div><p class="section-note">Har bir oddiy matn yangi element bo‘ladi. Agar mavjud schema object ishlatsa, har bir qatorda valid JSON object saqlang.</p><div class="field"><label for="method">Metodika</label><textarea id="method" name="method" spellcheck="false" aria-describedby="method-note"><?=e(arrayToEditor($data['method']))?></textarea></div></section>
<section class="section"><div class="section-head"><h2>Programs</h2><span>02 / Curriculum</span></div><p class="section-note">Dasturlarni faqat mavjud, tasdiqlangan ma’lumotlar asosida kiriting.</p><div class="field"><label for="programs">Dasturlar</label><textarea id="programs" name="programs" spellcheck="false"><?=e(arrayToEditor($data['programs']))?></textarea></div></section>
<section class="section"><div class="section-head"><h2>Principles</h2><span>03 / Principles</span></div><p class="section-note">Maktabning real ta’lim prinsiplarini saqlang; uydirma claims yoki statistikalar kiritmang.</p><div class="field"><label for="principles">Prinsiplar</label><textarea id="principles" name="principles" spellcheck="false"><?=e(arrayToEditor($data['principles']))?></textarea></div></section>
<div class="actions"><div class="hint">JSON UTF-8 · pretty-print · LOCK_EX · existing schema preserved · PHP 8.x · zero dependencies.</div><button class="save" type="submit">SAVE CHANGES →</button></div>
</form>
<p class="footer"><span>data/talim.json</span><span><?=count(is_array($data['method'])?$data['method']:[])?> method · <?=count(is_array($data['programs'])?$data['programs']:[])?> programs · <?=count(is_array($data['principles'])?$data['principles']:[])?> principles</span></p>
</main>
</div>
</body>
</html>
