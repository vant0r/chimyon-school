<?php
declare(strict_types=1);

session_start();

$error = '';
$authenticated = isset($_SESSION['chimyon_admin_authenticated']) && $_SESSION['chimyon_admin_authenticated'] === true;

if ($authenticated) {
    header('Location: index.php', true, 302);
    exit;
}

/*
 * Credentials are intentionally NOT hard-coded here.
 * This first login surface establishes the session contract; credential
 * storage must be connected to the deployment's secure secret mechanism
 * before production authentication is enabled.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = 'Admin credentials are not configured yet. Configure secure credentials before enabling production login.';
}
?>
<!doctype html>
<html lang="uz">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Admin Login — CHIMYON SCHOOL</title>
<style>
:root{--bg:#f4f4f1;--paper:#fff;--ink:#101722;--muted:#6b7380;--navy:#14213d;--gold:#b99758;--line:rgba(16,23,34,.1);--shadow:0 35px 100px rgba(20,33,61,.1)}*{box-sizing:border-box}html,body{min-height:100%;margin:0}body{background:var(--bg);color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;-webkit-font-smoothing:antialiased}.page{min-height:100svh;display:grid;grid-template-columns:minmax(0,1.15fr) minmax(360px,.85fr)}.visual{position:relative;min-height:100svh;background:var(--navy);overflow:hidden;padding:48px}.visual:before{content:"";position:absolute;inset:14%;border:1px solid rgba(255,255,255,.12);border-radius:50%;transform:scaleX(1.25)}.visual:after{content:"";position:absolute;width:380px;height:380px;right:-120px;bottom:-130px;border:1px solid rgba(198,161,91,.35);border-radius:50%}.visual-inner{position:relative;z-index:1;height:100%;display:flex;flex-direction:column;justify-content:space-between;color:#fff}.brand{font-size:12px;font-weight:850;letter-spacing:.18em}.kicker{color:#d5bd8a;font-size:10px;font-weight:850;letter-spacing:.2em;text-transform:uppercase}.visual h1{max-width:700px;margin:18px 0 0;font-size:clamp(58px,7vw,108px);line-height:.86;letter-spacing:-.075em;font-weight:760}.visual-copy{max-width:430px;color:rgba(255,255,255,.62);font-size:13px;line-height:1.75}.side{display:flex;align-items:center;justify-content:center;padding:42px}.panel{width:min(430px,100%)}.eyebrow{margin:0 0 14px;color:var(--gold);font-size:10px;font-weight:850;letter-spacing:.2em;text-transform:uppercase}.panel h2{margin:0;font-size:44px;line-height:.95;letter-spacing:-.06em}.panel-intro{margin:18px 0 34px;color:var(--muted);font-size:13px;line-height:1.7}.notice{padding:14px 16px;margin-bottom:24px;background:#fff;border:1px solid rgba(139,63,54,.18);color:#8b3f36;font-size:12px;line-height:1.6}.field{margin-top:22px}.field label{display:block;margin-bottom:8px;color:var(--muted);font-size:10px;font-weight:850;letter-spacing:.13em;text-transform:uppercase}.field input{width:100%;height:50px;border:0;border-bottom:1px solid rgba(16,23,34,.18);outline:none;background:transparent;color:var(--ink);font:inherit;font-size:15px}.field input:focus{border-color:var(--navy)}.submit{width:100%;margin-top:32px;border:0;background:var(--navy);color:#fff;padding:17px 20px;cursor:pointer;font:inherit;font-size:11px;font-weight:850;letter-spacing:.08em}.submit:hover{background:#1d3154}.back{display:inline-block;margin-top:22px;color:var(--muted);text-decoration:none;font-size:11px;font-weight:700}.back:hover{color:var(--navy)}.foot{margin-top:70px;padding-top:18px;border-top:1px solid var(--line);color:var(--muted);font-size:9px;line-height:1.6;letter-spacing:.04em}@media(max-width:820px){.page{grid-template-columns:1fr}.visual{min-height:45svh;padding:30px}.visual h1{font-size:clamp(52px,14vw,78px)}.visual-copy{display:none}.side{padding:38px 25px 55px}.panel h2{font-size:38px}.foot{margin-top:50px}}@media(prefers-reduced-motion:reduce){*{scroll-behavior:auto}}
</style>
</head>
<body>
<div class="page">
<section class="visual" aria-label="CHIMYON SCHOOL Admin">
<div class="visual-inner"><div class="brand">CHIMYON / SCHOOL</div><div><div class="kicker">Private education · control center</div><h1>Quietly<br>precise.</h1></div><p class="visual-copy">Maktab kontentini boshqarish uchun sokin, xavfsizlikka yo‘naltirilgan admin kirish qatlami.</p></div>
</section>
<main class="side"><div class="panel"><p class="eyebrow">ADMIN ACCESS</p><h2>Control center.</h2><p class="panel-intro">CHIMYON SCHOOL boshqaruv tizimiga kirish.</p>
<?php if($error):?><div class="notice" role="alert"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></div><?php endif;?>
<form method="post" autocomplete="off">
<div class="field"><label for="username">Username</label><input id="username" name="username" autocomplete="username" required></div>
<div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
<button class="submit" type="submit">AUTHENTICATE →</button>
</form>
<a class="back" href="../index.php">← Back to website</a>
<div class="foot">Security note: credentials must be supplied through a secure deployment secret, never committed into the repository.</div>
</div></main>
</div>
</body>
</html>
