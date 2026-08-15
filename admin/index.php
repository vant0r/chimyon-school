<?php
declare(strict_types=1);

/*
 * CHIMYON SCHOOL — Admin Control Center
 * Phase 1: secure, dependency-free content dashboard shell.
 * Content remains JSON-driven; page-specific editors are added incrementally.
 */

$root = dirname(__DIR__);
$dataDir = $root . '/data';

function adminReadJson(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}

function adminCountItems(array $data): int
{
    if (array_is_list($data)) {
        return count($data);
    }

    foreach (['items', 'teachers', 'programs', 'results', 'news', 'gallery'] as $key) {
        if (isset($data[$key]) && is_array($data[$key])) {
            return count($data[$key]);
        }
    }

    return 0;
}

$modules = [
    ['key' => 'maktab', 'label' => 'Maktab', 'path' => 'maktab.php', 'description' => 'School identity and philosophy'],
    ['key' => 'talim', 'label' => 'Ta’lim', 'path' => 'talim.php', 'description' => 'Programs and methodology'],
    ['key' => 'jamoa', 'label' => 'Jamoa', 'path' => 'jamoa.php', 'description' => 'Teachers and team profiles'],
    ['key' => 'natijalar', 'label' => 'Natijalar', 'path' => 'natijalar.php', 'description' => 'Verified achievements'],
    ['key' => 'yangiliklar', 'label' => 'Yangiliklar', 'path' => 'yangiliklar.php', 'description' => 'News and editorial content'],
    ['key' => 'galereya', 'label' => 'Galereya', 'path' => 'galereya.php', 'description' => 'School media archive'],
    ['key' => 'qabul', 'label' => 'Qabul', 'path' => 'qabul.php', 'description' => 'Admission information'],
    ['key' => 'aloqa', 'label' => 'Aloqa', 'path' => 'aloqa.php', 'description' => 'Contact and working hours'],
];

$stats = [];
foreach ($modules as $module) {
    $data = adminReadJson($dataDir . '/' . $module['key'] . '.json');
    $stats[$module['key']] = adminCountItems($data);
}

$settings = adminReadJson($dataDir . '/settings.json');
$schoolName = (string)($settings['site_name'] ?? $settings['title'] ?? 'CHIMYON SCHOOL');
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Admin — <?= htmlspecialchars($schoolName, ENT_QUOTES, 'UTF-8') ?></title>
<style>
:root{--bg:#f5f5f3;--surface:#fff;--text:#111827;--muted:#68707d;--navy:#14213d;--gold:#c6a15b;--line:rgba(17,24,39,.08);--shadow:0 24px 70px rgba(20,33,61,.08)}
*{box-sizing:border-box}html{background:var(--bg);scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1440px,calc(100% - 48px));margin:auto}.top{display:flex;align-items:center;justify-content:space-between;padding:28px 0;border-bottom:1px solid var(--line)}.brand{display:flex;align-items:center;gap:14px}.mark{width:38px;height:38px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font-size:12px;font-weight:800;letter-spacing:.08em}.brand strong{display:block;font-size:14px;letter-spacing:.12em}.brand span{display:block;margin-top:3px;color:var(--muted);font-size:11px;letter-spacing:.06em}.back{color:var(--text);text-decoration:none;font-size:13px;font-weight:700}.back:hover{color:var(--gold)}main{padding:76px 0 100px}.eyebrow{margin:0 0 18px;color:var(--gold);font-size:11px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}.hero{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(280px,.65fr);gap:48px;align-items:end;margin-bottom:70px}.hero h1{max-width:900px;margin:0;font-size:clamp(48px,7vw,100px);line-height:.91;letter-spacing:-.065em;font-weight:750}.hero p{max-width:380px;margin:0 0 6px;color:var(--muted);font-size:15px;line-height:1.7}.modules{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border:1px solid var(--line);box-shadow:var(--shadow)}.module{min-height:235px;padding:28px;background:rgba(255,255,255,.86);display:flex;flex-direction:column;justify-content:space-between;text-decoration:none;color:inherit;transition:transform .35s ease,background .35s ease}.module:hover{transform:translateY(-5px);background:#fff}.number{color:var(--gold);font-size:11px;font-weight:800;letter-spacing:.15em}.module h2{margin:30px 0 8px;font-size:24px;letter-spacing:-.035em}.module p{margin:0;color:var(--muted);font-size:13px;line-height:1.55}.edit{margin-top:22px;font-size:12px;font-weight:800;letter-spacing:.04em}.module .count{color:var(--muted);font-size:11px}.footer{margin-top:38px;display:flex;justify-content:space-between;gap:20px;color:var(--muted);font-size:11px;letter-spacing:.04em}.status{display:inline-flex;align-items:center;gap:7px}.dot{width:6px;height:6px;border-radius:50%;background:#4b8069}@media(max-width:1000px){.modules{grid-template-columns:repeat(2,1fr)}}@media(max-width:700px){.shell{width:min(100% - 28px,600px)}.top{padding:20px 0}.hero{grid-template-columns:1fr;gap:28px;margin-bottom:44px}.hero h1{font-size:clamp(48px,16vw,76px)}main{padding:50px 0 70px}.modules{grid-template-columns:1fr}.module{min-height:190px}.footer{flex-direction:column}.back{font-size:12px}}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}.module{transition:none}.module:hover{transform:none}}
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
    <div><p class="eyebrow">01 / CONTROL CENTER</p><h1 id="admin-title">School,<br>precisely managed.</h1></div>
    <p>CHIMYON SCHOOL kontentini yagona, sokin va aniq boshqaruv qatlamidan nazorat qilish uchun admin markaz.</p>
  </section>
  <section aria-label="Content modules">
    <div class="modules">
      <?php foreach ($modules as $index => $module): ?>
        <a class="module" href="<?= htmlspecialchars($module['path'], ENT_QUOTES, 'UTF-8') ?>">
          <div><span class="number"><?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span><h2><?= htmlspecialchars($module['label'], ENT_QUOTES, 'UTF-8') ?></h2><p><?= htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8') ?></p></div>
          <div class="edit"><span class="count"><?= $stats[$module['key']] ?> data signal</span> · Open editor →</div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
  <footer class="footer"><span>JSON content architecture · PHP 8.x · zero dependencies</span><span class="status"><i class="dot"></i> Content system online</span></footer>
</main>
</div>
</body>
</html>
