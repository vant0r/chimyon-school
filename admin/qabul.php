<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$file = $root . '/data/qabul.json';
$notice = '';
$error = '';
$default = ['title'=>'Qabul','intro'=>'','hero_image'=>'','steps'=>[],'contact'=>[]];

function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function readAdmission(string $file, array $default): array {
    if (!is_file($file)) return $default;
    $raw = file_get_contents($file);
    $data = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($data)) return $default;
    foreach ($default as $key=>$value) {
        if (!array_key_exists($key,$data)) $data[$key]=$value;
        if (is_array($value) && !is_array($data[$key])) $data[$key]=$value;
    }
    return $data;
}
function recordsToLines(mixed $value): string {
    if (!is_array($value)) return '';
    $lines=[];
    foreach ($value as $item) {
        if (!is_array($item)) continue;
        $json=json_encode($item,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if ($json!==false) $lines[]=$json;
    }
    return implode("\n",$lines);
}
function linesToRecords(string $value,string $label): array {
    $records=[];
    foreach (preg_split('/\R/',$value) ?: [] as $line) {
        $line=trim($line);
        if ($line==='') continue;
        $decoded=json_decode($line,true);
        if (!is_array($decoded)) throw new RuntimeException("$label: har bir qator JSON object bo‘lishi kerak.");
        $records[]=$decoded;
    }
    return $records;
}
function contactToText(mixed $value): string {
    if (!is_array($value) || $value===[]) return '';
    $json=json_encode($value,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    return $json===false?'':$json;
}
function parseContact(string $value): array {
    $value=trim($value);
    if ($value==='') return [];
    $decoded=json_decode($value,true);
    if (!is_array($decoded)) throw new RuntimeException('Contact JSON object yoki array bo‘lishi kerak.');
    return $decoded;
}

$data=readAdmission($file,$default);
if ($_SERVER['REQUEST_METHOD']==='POST') {
    try {
        $title=trim((string)($_POST['title']??''));
        $intro=trim((string)($_POST['intro']??''));
        $hero=trim((string)($_POST['hero_image']??''));
        if ($title==='') throw new RuntimeException('Sarlavha bo‘sh bo‘lishi mumkin emas.');
        if ($hero!=='' && !preg_match('#^(?:https?://|/|\.\./|media/)#i',$hero)) throw new RuntimeException('Hero image path xavfsiz formatda emas.');
        $data=[
            'title'=>$title,
            'intro'=>$intro,
            'hero_image'=>$hero,
            'steps'=>linesToRecords((string)($_POST['steps']??''),'Steps'),
            'contact'=>parseContact((string)($_POST['contact']??''))
        ];
        $json=json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if ($json===false || file_put_contents($file,$json.PHP_EOL,LOCK_EX)===false) throw new RuntimeException('JSON faylini saqlash amalga oshmadi.');
        $notice='Qabul kontenti saqlandi.';
    } catch (RuntimeException $ex) { $error=$ex->getMessage(); }
}
?>
<!doctype html><html lang="uz"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Qabul — CHIMYON SCHOOL Admin</title>
<style>
:root{--bg:#f4f4f1;--paper:#fff;--ink:#101722;--muted:#69717d;--navy:#14213d;--gold:#b99758;--line:rgba(16,23,34,.1);--shadow:0 30px 90px rgba(20,33,61,.08)}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.shell{width:min(1180px,calc(100% - 40px));margin:auto}.top{height:82px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between}.brand{font-size:12px;font-weight:850;letter-spacing:.15em}.back{color:var(--ink);text-decoration:none;font-size:12px;font-weight:750}.back:hover{color:var(--gold)}main{padding:72px 0 100px}.heading{display:flex;align-items:end;justify-content:space-between;gap:50px;margin-bottom:56px}.eyebrow{margin:0 0 15px;color:var(--gold);font-size:10px;font-weight:850;letter-spacing:.2em;text-transform:uppercase}.heading h1{margin:0;font-size:clamp(56px,8vw,96px);line-height:.86;letter-spacing:-.075em}.heading p{max-width:390px;margin:0;color:var(--muted);font-size:13px;line-height:1.75}.notice,.error{padding:14px 18px;margin-bottom:22px;background:#fff;border:1px solid var(--line);font-size:13px}.error{color:#8b3f36;border-color:rgba(139,63,54,.18)}.form{background:var(--paper);box-shadow:var(--shadow);padding:44px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:34px 44px}.wide,.guide{grid-column:1/-1}.field label{display:block;margin-bottom:9px;font-size:10px;font-weight:850;letter-spacing:.13em;text-transform:uppercase}.field input,.field textarea{width:100%;border:0;border-bottom:1px solid rgba(16,23,34,.16);background:transparent;color:var(--ink);outline:none;padding:13px 0;font:inherit;font-size:15px}.field textarea{min-height:160px;resize:vertical;border:1px solid var(--line);padding:14px;line-height:1.65}.field input:focus,.field textarea:focus{border-color:var(--navy)}.field small{display:block;margin-top:8px;color:var(--muted);font-size:10px;line-height:1.55}.guide{padding:20px 0 0;border-top:1px solid var(--line)}.guide strong{display:block;margin-bottom:8px;font-size:13px}.guide p{margin:0;color:var(--muted);font-size:11px;line-height:1.7}.actions{display:flex;align-items:center;justify-content:space-between;gap:24px;padding-top:28px;margin-top:38px;border-top:1px solid var(--line)}.hint{max-width:650px;color:var(--muted);font-size:10px;line-height:1.6}.save{border:0;background:var(--navy);color:#fff;padding:15px 24px;cursor:pointer;font:inherit;font-size:11px;font-weight:850;letter-spacing:.06em}.save:hover{background:#1d3154}.footer{margin-top:24px;color:var(--muted);font-size:10px}@media(max-width:720px){.shell{width:min(100% - 26px,600px)}main{padding:48px 0 70px}.heading{display:block}.heading p{margin-top:24px}.form{padding:26px}.grid{grid-template-columns:1fr}.wide,.guide{grid-column:auto}.actions{align-items:stretch;flex-direction:column}.save{width:100%}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto}}
</style></head><body><div class="shell"><header class="top"><div class="brand">CHIMYON / ADMIN</div><a class="back" href="index.php">← Control center</a></header><main>
<section class="heading"><div><p class="eyebrow">08 / ADMISSIONS</p><h1>Qabul.</h1></div><p>Qabul jarayoni va aloqa ma’lumotlarini boshqaring. Faqat tasdiqlangan maktab ma’lumotlarini kiriting.</p></section>
<?php if($notice):?><div class="notice" role="status"><?=e($notice)?></div><?php endif;?><?php if($error):?><div class="error" role="alert"><?=e($error)?></div><?php endif;?>
<form class="form" method="post" autocomplete="off"><div class="grid">
<div class="field"><label for="title">Sarlavha</label><input id="title" name="title" value="<?=e($data['title'])?>" required></div>
<div class="field"><label for="hero_image">Hero image path</label><input id="hero_image" name="hero_image" value="<?=e($data['hero_image'])?>" placeholder="media/images/..."><small>Faqat mavjud media path yoki ishonchli URL.</small></div>
<div class="field wide"><label for="intro">Intro</label><textarea id="intro" name="intro" rows="5"><?=e($data['intro'])?></textarea></div>
<div class="guide"><strong>Admission steps</strong><p>Har bir qator bitta JSON object. Mavjud fieldlarni saqlang; uydirma qabul talablari yaratmang.</p></div>
<div class="field wide"><label for="steps">Steps — JSONL</label><textarea id="steps" name="steps" rows="17" spellcheck="false" placeholder='{"title":"...","description":"..."}'><?=e(recordsToLines($data['steps']))?></textarea><small>Har qatorda bitta JSON object. Bo‘sh qoldirish — <code>[]</code>.</small></div>
<div class="guide"><strong>Contact</strong><p>Mavjud contact strukturasini JSON sifatida saqlang. Haqiqiy aloqa ma’lumotlarisiz placeholder kiritmang.</p></div>
<div class="field wide"><label for="contact">Contact — JSON</label><textarea id="contact" name="contact" rows="11" spellcheck="false" placeholder='{"phone":"...","email":"...","address":"..."}'><?=e(contactToText($data['contact']))?></textarea><small>Faqat valid JSON object yoki array.</small></div>
</div><div class="actions"><div class="hint">JSON UTF-8 · pretty-print · LOCK_EX · existing schema preserved · PHP 8.x · zero dependencies.</div><button class="save" type="submit">SAVE CHANGES →</button></div></form>
<p class="footer">data/qabul.json · <?=count(is_array($data['steps'])?$data['steps']:[])?> step records · contact <?=is_array($data['contact'])&&!empty($data['contact'])?'configured':'empty'?></p></main></div></body></html>
