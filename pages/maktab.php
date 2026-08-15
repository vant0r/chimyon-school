<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function load_json(string $path): array
{
    if (!is_file($path) || !is_readable($path)) {
        return [];
    }

    $raw = file_get_contents($path);
    if ($raw === false) {
        return [];
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

$data = load_json(__DIR__ . '/../data/maktab.json');
$brand = load_json(__DIR__ . '/../data/home.json');
$school = is_array($data['school'] ?? null) ? $data['school'] : [];
$homeBrand = is_array($brand['brand'] ?? null) ? $brand['brand'] : [];
$name = (string)($homeBrand['name'] ?? 'CHIMYON SCHOOL');
$title = (string)($school['title'] ?? 'Maktab haqida');
$intro = (string)($school['intro'] ?? 'Maktabning asosiy ma’lumotlari bu yerda ko‘rsatiladi.');
$image = (string)($school['image'] ?? '');
$values = is_array($school['values'] ?? null) ? $school['values'] : [];
$philosophy = is_array($school['philosophy'] ?? null) ? $school['philosophy'] : [];
$placeholder = empty($data);
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#f5f5f3">
<meta name="description" content="<?= e($intro) ?>">
<title><?= e($title) ?> — <?= e($name) ?></title>
<style>
:root{--bg:#f5f5f3;--paper:#fff;--ink:#111827;--navy:#14213d;--gold:#c6a15b;--muted:#6b7280;--line:rgba(17,24,39,.09);--ease:cubic-bezier(.16,1,.3,1);--max:1240px}*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"SF Pro Display","SF Pro Text","Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}a{color:inherit;text-decoration:none}.nav{position:fixed;z-index:20;top:20px;left:50%;width:min(var(--max),calc(100% - 40px));transform:translateX(-50%);display:flex;align-items:center;justify-content:space-between;padding:10px 10px 10px 20px;background:rgba(255,255,255,.78);border:1px solid rgba(255,255,255,.9);border-radius:17px;box-shadow:0 18px 55px rgba(17,24,39,.08);backdrop-filter:blur(22px);-webkit-backdrop-filter:blur(22px)}.logo{font-size:11px;font-weight:950;letter-spacing:.04em}.logo small{display:block;color:var(--muted);font-size:6px;letter-spacing:2.5px;margin-top:2px}.links{display:flex;gap:28px;color:#656c75;font-size:10px}.links a{transition:color .3s}.links a:hover{color:var(--navy)}.cta{padding:12px 16px;background:var(--navy);color:#fff;border-radius:12px;font-size:9px;font-weight:800}.hero{min-height:100svh;padding:160px max(24px,calc((100% - var(--max))/2)) 90px;display:grid;grid-template-columns:1.15fr .65fr;align-items:end;gap:80px}.eyebrow{font-size:8px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);font-weight:900}.hero h1{font-size:clamp(70px,9vw,132px);line-height:.82;letter-spacing:-.09em;margin:16px 0 0;font-weight:950}.hero h1 span{color:var(--gold)}.hero-copy{max-width:390px;color:var(--muted);font-size:14px;line-height:1.8;padding-bottom:8px}.hero-copy strong{display:block;color:var(--navy);font-size:10px;letter-spacing:2px;text-transform:uppercase;margin-bottom:12px}.visual{position:relative;min-height:390px;overflow:hidden;background:var(--navy)}.visual img{width:100%;height:100%;min-height:390px;display:block;object-fit:cover}.visual:after{content:"";position:absolute;inset:0;background:linear-gradient(120deg,rgba(20,33,61,.12),transparent 60%)}.intro{background:var(--paper);padding:150px max(24px,calc((100% - var(--max))/2));display:grid;grid-template-columns:1fr .75fr;gap:100px}.intro h2,.philosophy h2,.values h2{font-size:clamp(50px,6.8vw,96px);line-height:.86;letter-spacing:-.085em;margin:12px 0;font-weight:950}.intro h2 span{color:var(--gold)}.copy{color:var(--muted);font-size:13px;line-height:1.8;max-width:470px}.label{font-size:8px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);font-weight:900}.philosophy{padding:150px max(24px,calc((100% - var(--max))/2));background:var(--bg)}.philosophy-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:100px;align-items:start}.philosophy-list{border-top:1px solid var(--line)}.item{padding:28px 0;border-bottom:1px solid var(--line);display:grid;grid-template-columns:45px 1fr;gap:18px}.item b{font-size:9px;color:#90959b;letter-spacing:2px}.item h3{margin:0;font-size:25px;letter-spacing:-.05em}.item p{grid-column:2;margin:8px 0 0;color:var(--muted);font-size:11px;line-height:1.7}.values{padding:150px max(24px,calc((100% - var(--max))/2));background:var(--navy);color:#fff}.values .label{color:#d8bd7d}.values h2{margin-bottom:70px}.value-row{display:grid;grid-template-columns:70px 1fr 1fr;gap:25px;padding:27px 0;border-top:1px solid rgba(255,255,255,.12)}.value-row:last-child{border-bottom:1px solid rgba(255,255,255,.12)}.value-row b{font-size:8px;color:#8f9aaa;letter-spacing:2px}.value-row h3{margin:0;font-size:25px;letter-spacing:-.05em}.value-row p{margin:0;color:#aeb7c5;font-size:11px;line-height:1.7}.pending{padding:20px 24px;border:1px dashed rgba(198,161,91,.45);color:var(--muted);font-size:10px;line-height:1.7}.footer{padding:45px max(24px,calc((100% - var(--max))/2));display:flex;justify-content:space-between;color:#7c8289;font-size:9px;border-top:1px solid var(--line)}.footer strong{color:var(--navy)}@media(max-width:760px){.nav{top:10px;width:calc(100% - 20px);padding-left:15px}.links{display:none}.hero{display:block;padding:130px 20px 70px}.hero h1{font-size:18vw}.hero-copy{margin-top:35px}.visual{margin-top:55px;min-height:52svh}.visual img{min-height:52svh}.intro,.philosophy{display:block;padding:100px 20px}.intro .copy{margin-top:35px}.philosophy-grid{display:block}.philosophy-list{margin-top:55px}.values{padding:100px 20px}.value-row{grid-template-columns:40px 1fr}.value-row p{grid-column:2}.footer{display:block;line-height:2}}@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}}
</style>
</head>
<body>
<nav class="nav" aria-label="Asosiy navigatsiya"><a class="logo" href="../index.php"><?= e($name) ?><small>PRIVATE EDUCATION</small></a><div class="links"><a href="../index.php">Bosh sahifa</a><a href="#about">Maktab</a><a href="#philosophy">Yondashuv</a><a href="#values">Qadriyatlar</a></div><a class="cta" href="../index.php#admission">Qabul ↗</a></nav>
<main>
<section class="hero"><div><div class="eyebrow">01 / SCHOOL</div><h1><?= e($title) ?></h1><div class="hero-copy"><strong><?= e($name) ?></strong><?= e($intro) ?></div></div><div class="visual"><?php if($image!==''): ?><img src="../<?= e(ltrim($image,'/')) ?>" alt="<?= e($title) ?>"><?php else: ?><div class="pending">IMAGE PENDING — mavjud media ma’lumotlari aniqlanmaguncha uydirma rasm ishlatilmaydi.</div><?php endif; ?></div></section>
<section class="intro" id="about"><div><div class="label">01 — Identity</div><h2>Ta’limga<br><span>boshqacha</span> qarash.</h2></div><div class="copy"><?= e($intro) ?></div></section>
<section class="philosophy" id="philosophy"><div class="philosophy-grid"><div><div class="label">02 — Philosophy</div><h2>Har bir kun — o‘sish.</h2></div><div class="philosophy-list"><?php if($philosophy): foreach($philosophy as $i=>$item): ?><article class="item"><b><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></b><div><h3><?= e($item['title']??'') ?></h3><p><?= e($item['text']??'') ?></p></div></article><?php endforeach; else: ?><div class="pending">PHILOSOPHY CONTENT PENDING — haqiqiy maktab ma’lumoti mavjud bo‘lganda JSON orqali kiritiladi.</div><?php endif; ?></div></div></section>
<section class="values" id="values"><div class="label">03 — Values</div><h2>Biz nimaga<br>ishonamiz.</h2><?php if($values): foreach($values as $i=>$item): ?><article class="value-row"><b><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></b><h3><?= e($item['title']??'') ?></h3><p><?= e($item['text']??'') ?></p></article><?php endforeach; else: ?><div class="pending">VALUES CONTENT PENDING — uydirma qadriyatlar qo‘shilmagan.</div><?php endif; ?></section>
</main>
<footer class="footer"><strong><?= e($name) ?></strong><span>School / Identity</span><a href="../index.php">← Bosh sahifa</a></footer>
</body>
</html>
