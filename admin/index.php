<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$dataDir = $root . '/data';

function adminReadJson(string $path): array
{
    if (!is_file($path)) return [];
    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') return [];
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function adminCountItems(array $data): int
{
    if (array_is_list($data)) return count($data);
    foreach (['items','teachers','programs','results','posts','images','steps'] as $key) {
        if (isset($data[$key]) && is_array($data[$key])) return count($data[$key]);
    }
    return 0;
}

$modules = [
    ['key'=>'maktab','label'=>'Maktab','path'=>'maktab.php','description'=>'School identity and philosophy'],
    ['key'=>'talim','label'=>'Ta’lim','path'=>'talim.php','description'=>'Programs and methodology'],
    ['key'=>'jamoa','label'=>'Jamoa','path'=>'jamoa.php','description'=>'Teachers and team profiles'],
    ['key'=>'natijalar','label'=>'Natijalar','path'=>'natijalar.php','description'=>'Verified achievements'],
    ['key'=>'yangiliklar','label'=>'Yangiliklar','path'=>'yangiliklar.php','description'=>'News and editorial content'],
    ['key'=>'galereya','label'=>'Galereya','path'=>'galereya.php','description'=>'School media archive'],
    ['key'=>'qabul','label'=>'Qabul','path'=>'qabul.php','description'=>'Admission information'],
    ['key'=>'aloqa','label'=>'Aloqa','path'=>'aloqa.php','description'=>'Contact and social information'],
];

$stats = [];
foreach ($modules as $module) {
    $stats[$module['key']] = adminCountItems(adminReadJson($dataDir . '/' . $module['key'] . '.json'));
}

$settings = adminReadJson($dataDir . '/settings.json');
$schoolName = (string)($settings['site_name'] ?? $settings['title'] ?? 'CHIMYON SCHOOL');
$totalSignals = array_sum($stats);
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Admin — <?= htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8') ?></title>
<style>
:root{--bg:#f3f3f0;--paper:#fff;--ink:#101722;--muted:#69717d;--navy:#14213d;--gold:#b99758;--line:rgba(16,23,34,.1);--shadow:0 32px 90px rgba(20,33,61,.09)}*{box-sizing:border-box}html{background:var(--bg);scroll-behavior:smooth}body{margin:0;color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1440px,calc(100% - 56px));margin:auto}.top{height:88px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{display:flex;align-items:center;gap:14px}.mark{width:40px;height:40px;display:grid;place-items:center;border:1px solid rgba(255,255,255,.1);border-radius:50%;background:var(--navy);color:#fff;font-size:11px;font-weight:850;letter-spacing:.08em}.brand strong{display:block;font-size:13px;letter-spacing:.14em}.brand span{display:block;margin-top:3px;color:var(--muted);font-size:9px;font-weight:700;letter-spacing:.14em}.back{color:var(--ink);text-decoration:none;font-size:12px;font-weight:750}.back:hover{color:var(--gold)}main{padding:78px 0 90px}.hero{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(300px,.6fr);gap:60px;align-items:end;margin-bottom:64px}.eyebrow{margin:0 0 17px;color:var(--gold);font-size:10px;font-weight:850;letter-spacing:.2em;text-transform:uppercase}.hero h1{margin:0;max-width:900px;font-size:clamp(54px,8vw,112px);line-height:.86;letter-spacing:-.075em;font-weight:760}.hero-copy{margin:0 0 5px;max-width:390px;color:var(--muted);font-size:14px;line-height:1.75}.meta{display:flex;gap:28px;margin-top:28px;color:var(--muted);font-size:10px;text-transform:uppercase;letter-spacing:.12em}.meta strong{color:var(--ink);font-size:12px}.modules{display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid var(--line);border-left:1px solid var(--line);box-shadow:var(--shadow)}.module{position:relative;min-height:250px;padding:27px 25px 23px;background:rgba(255,255,255,.72);border-right:1px solid var(--line);border-bottom:1px solid var(--line);text-decoration:none;color:inherit;display:flex;flex-direction:column;justify-content:space-between;overflow:hidden;transition:background .35s ease,transform .35s ease,box-shadow .35s ease}.module:after{content:"";position:absolute;left:25px;right:25px;bottom:0;height:2px;background:var(--gold);transform:scaleX(0);transform-origin:left;transition:transform .4s ease}.module:hover{z-index:2;background:#fff;transform:translateY(-4px);box-shadow:0 18px 45px rgba(20,33,61,.1)}.module:hover:after{transform:scaleX(1)}.number{color:var(--gold);font-size:10px;font-weight:850;letter-spacing:.18em}.module h2{margin:32px 0 8px;font-size:25px;line-height:1;letter-spacing:-.045em}.module p{margin:0;color:var(--muted);font-size:12px;line-height:1.55;max-width:190px}.module-bottom{display:flex;justify-content:space-between;align-items:end;gap:10px;margin-top:28px}.count{color:var(--muted);font-size:9px;text-transform:uppercase;letter-spacing:.1em}.edit{color:var(--navy);font-size:10px;font-weight:850;letter-spacing:.06em;white-space:nowrap}.footer{display:flex;justify-content:space-between;gap:20px;margin-top:34px;padding-top:20px;border-top:1px solid var(--line);color:var(--muted);font-size:10px;letter-spacing:.04em}.status{display:inline-flex;align-items:center;gap:7px}.dot{width:6px;height:6px;border-radius:50%;background:#4b8069}@media(max-width:1050px){.modules{grid-template-columns:repeat(2,1fr)}}@media(max-width:720px){.shell{width:min(100% - 28px,600px)}.top{height:74px}.brand span{display:none}.hero{grid-template-columns:1fr;gap:30px;margin-bottom:46px}main{padding:52px 0 70px}.hero h1{font-size:clamp(52px,16vw,82px)}.meta{margin-top:22px;gap:20px}.modules{grid-template-columns:1fr}.module{min-height:205px}.footer{flex-direction:column}.back{font-size:11px}}@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}.module,.module:after{transition:none}.module:hover{transform:none}}
</style>
</head>
<body>
<div class="shell">
<header class="top">
<div class="brand"><div class="mark">CS</div><div><strong>CHIMYON SCHOOL</strong><span>CONTENT CONTROL CENTER</span></div></div>
<a class="back" href="../index.php">← Saytga qaytish</a>
</header>
<main>
<section class="hero" aria-labelledby="admin-title">
<div><p class="eyebrow">01 / CONTROL CENTER</p><h1 id="admin-title">School,<br>precisely managed.</h1><div class="meta"><span><strong><?= count($modules) ?></strong> modules</span><span><strong><?= $totalSignals ?></strong> content signals</span></div></div>
<p class="hero-copy">CHIMYON SCHOOL kontentini yagona, sokin va aniq boshqaruv qatlamidan nazorat qilish uchun admin markaz.</p>
</section>
<section aria-label="Content modules"><div class="modules">
<?php foreach($modules as $index=>$module): ?>
<a class="module" href="<?= htmlspecialchars($module['path'],ENT_QUOTES,'UTF-8') ?>">
<div><span class="number"><?= str_pad((string)($index+1),2,'0',STR_PAD_LEFT) ?></span><h2><?= htmlspecialchars($module['label'],ENT_QUOTES,'UTF-8') ?></h2><p><?= htmlspecialchars($module['description'],ENT_QUOTES,'UTF-8') ?></p></div>
<div class="module-bottom"><span class="count"><?= $stats[$module['key']] ?> data signal</span><span class="edit">Open editor →</span></div>
</a>
<?php endforeach; ?>
</div></section>
<footer class="footer"><span>JSON content architecture · PHP 8.x · zero dependencies</span><span class="status"><i class="dot"></i> Content system online</span></footer>
</main>
</div>
</body>
</html>
