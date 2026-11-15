<?php
// Обработка на контактната форма (праща имейл до собственика)
session_start();
$cfg = require __DIR__ . '/magazin/config.php';
require __DIR__ . '/magazin/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $go = function($q){ header('Location: kontakti.php?'.$q.'#form'); exit; };
  if (!empty($_POST['website'])) { $go('sent=1'); }
  $clean = function($v,$n){ return mv_cut(trim(preg_replace('/[\r\n\t]+/',' ',(string)$v)),$n); };
  $name=$clean($_POST['name']??'',100); $email=$clean($_POST['email']??'',120); $phone=$clean($_POST['phone']??'',25);
  $msg=mv_cut(trim((string)($_POST['message']??'')),2000);
  $_SESSION['cnt']=array_filter($_SESSION['cnt']??[],function($t){return $t>time()-3600;}); 
  if(count($_SESSION['cnt'])>=5) $go('err=2');
  if (mv_len($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL)) $go('err=1');
  $_SESSION['cnt'][]=time();
  $body="Ново съобщение от сайта\n\nИме: $name\nИмейл: $email\nТелефон: ".($phone?:'—')."\n\n".($msg?:'(без текст)')."\n";
  mv_mail($cfg['owner_email'],'Ново съобщение от '.$name,$body,$cfg['from_email'],$email);
  $go('sent=1');
}
?><!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Контакти – Вила Монтевино</title>
<link rel="icon" href="https://villamontevino.com/wp-content/uploads/2023/03/cropped-%D0%9B%D0%BE%D0%B3%D0%BE-Winery-Estate-270x270.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Sofia+Sans+Semi+Condensed:wght@300;400;500;600&display=swap">
<style>
:root{--bg:#fff;--surface:#f6f2ec;--ink:#1f1f1f;--muted:#6b6b6b;--wine:#6b7c5e;--wine2:#1e211c;--gold:#b89a6e;--line:#e6e0d6;--on-dark:#fff;--sage:#6b7c5e;
box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
html{scroll-padding-top:env(safe-area-inset-top,0px);scroll-behavior:smooth}
*,*::before,*::after{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font:300 16px/1.7 "Sofia Sans Semi Condensed",system-ui,sans-serif}
h1,h2,h3{font-family:"Cormorant Garamond",Georgia,serif;font-weight:500;line-height:1.1;margin:0 0 .5em}
h1{font-size:clamp(2.4rem,6vw,4.6rem)}h2{font-size:clamp(1.9rem,4vw,3rem)}h3{font-size:1.6rem}
a{color:inherit;text-decoration:none}
p{margin:0 0 1em;max-width:62ch}
.wrap{max-width:1200px;margin:0 auto;padding:0 24px}
section{padding:96px 0}
.kicker{font-family:"Cormorant Garamond",serif;font-style:italic;font-size:1.25rem;color:var(--gold);margin-bottom:.6rem;display:block}
.btn{display:inline-block;padding:13px 30px;border:1px solid var(--wine);color:var(--wine);font-size:.85rem;letter-spacing:.08em;transition:.25s}
.btn:hover,.btn.solid{background:var(--wine);color:var(--on-dark)}
.btn.solid:hover{background:var(--wine2)}
:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
/* top bar */
.topbar{background:var(--wine2);color:var(--on-dark);font-size:.8rem;padding:8px 0}
.topbar .wrap{display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap}
/* header */
header{position:sticky;top:env(safe-area-inset-top,0px);z-index:20;background:var(--bg);border-bottom:1px solid var(--line)}
header .wrap{display:flex;align-items:center;justify-content:space-between;height:78px}
.logo{font-family:"Cormorant Garamond",serif;font-size:1.7rem;color:var(--wine);letter-spacing:.04em}
.logo small{display:block;font:400 .6rem "Sofia Sans Semi Condensed",system-ui,sans-serif;letter-spacing:.3em;color:var(--gold);margin-top:-4px}
nav ul{list-style:none;display:flex;gap:26px;margin:0;padding:0;font-size:.85rem}
nav li{position:relative}
nav li>ul{position:absolute;top:100%;left:-14px;display:none;flex-direction:column;gap:0;background:var(--bg);border:1px solid var(--line);min-width:200px;padding:8px 0}
nav li:hover>ul,nav li:focus-within>ul{display:flex}
nav li>ul a{display:block;padding:8px 18px}
nav a:hover{color:var(--gold)}
.burger{display:none;background:none;border:0;font-size:1.8rem;color:var(--ink);cursor:pointer}
@media(max-width:960px){.burger{display:block}.cta-h{display:none}
nav{position:absolute;top:78px;left:0;right:0;background:var(--bg);border-bottom:1px solid var(--line);display:none;max-height:75vh;overflow:auto}
nav.open{display:block}nav ul{flex-direction:column;gap:0;padding:12px 24px}nav li>ul{display:flex;position:static;border:0;padding:0 0 0 16px}nav li>a{display:block;padding:10px 0}}
/* hero */
.hero{position:relative;height:min(88vh,780px);min-height:520px;color:var(--on-dark);overflow:hidden;padding:0}
.slide{position:absolute;inset:0;opacity:0;transition:opacity 1.2s}
.slide.on{opacity:1}
.s1{background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-lozia-0002_preview.jpeg') center/cover no-repeat,linear-gradient(160deg,#6b2437,#2a0d16 70%)}
.s2{background:url('https://villamontevino.com/wp-content/uploads/2023/03/img-5-780x780.jpg') center/cover no-repeat,linear-gradient(160deg,#7a5a2a,#2f2410 75%)}
.s3{background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-%D1%85%D1%80%D0%B0%D0%BD%D0%B0-1-0235_preview-600x902.jpeg') center/cover no-repeat,linear-gradient(160deg,#3f4a2c,#161d10 75%)}
.s4{background:url('https://villamontevino.com/wp-content/uploads/2023/03/Villa-Monte-vino-1-5-e1741532180622.jpg') center/cover no-repeat,linear-gradient(160deg,#8a3b2c,#2c120e 75%)}
.s5{background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-%D1%85%D1%80%D0%B0%D0%BD%D0%B0-1-0002_preview-600x902.jpeg') center/cover no-repeat,linear-gradient(160deg,#4a2a45,#170d17 75%)}
.hero::after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(0,0,0,.45),transparent 60%)}
.hero .wrap{position:absolute;z-index:2;left:0;right:0;bottom:90px}
.hero .kicker{color:#e0c58f}
.hero h1{max-width:15ch}
.count{position:absolute;z-index:3;right:24px;bottom:40px;font-family:"Cormorant Garamond",serif;font-size:1.3rem;display:flex;gap:10px;align-items:center}
.count button{background:none;border:1px solid rgba(255,255,255,.5);color:inherit;width:38px;height:38px;border-radius:50%;cursor:pointer}
/* philosophy */
.split{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}
.ph{position:relative;aspect-ratio:4/5;background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-lozia-0002_preview.jpeg') center/cover no-repeat,linear-gradient(140deg,#5d6b3a,#2a3319);}
.ph.small{position:absolute;width:42%;aspect-ratio:1;right:-6%;bottom:-6%;background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-%D1%85%D1%80%D0%B0%D0%BD%D0%B0-1-0235_preview-600x902.jpeg') center/cover no-repeat,linear-gradient(140deg,#a0563a,#4a2016);border:8px solid var(--bg)}
.ph span{position:absolute;left:14px;bottom:10px;font-size:.7rem;color:rgba(255,255,255,.7)}
@media(max-width:860px){.split{grid-template-columns:1fr;gap:40px}.ph.small{right:0}}
/* marquee */
.marquee{overflow:hidden;background:var(--wine);color:var(--on-dark);padding:20px 0;white-space:nowrap}
.track{display:inline-flex;gap:40px;animation:mv 40s linear infinite;font-family:"Cormorant Garamond",serif;font-size:1.5rem}
.track i{color:var(--gold);font-style:normal}
@keyframes mv{to{transform:translateX(-50%)}}
@media(prefers-reduced-motion:reduce){.track{animation:none}html{scroll-behavior:auto}.slide{transition:none}}
/* discover */
.discover{background:var(--surface)}
.dgrid{display:grid;grid-template-columns:1.1fr 1fr;gap:56px;margin-top:40px}
.sunset{background:url('https://villamontevino.com/wp-content/uploads/2023/03/img-5-780x780.jpg') center/cover no-repeat,linear-gradient(180deg,#e9a35b,#b34a3a 55%,#3d0f1c);min-height:480px}
.list a,.list div{display:block;padding:22px 0;border-bottom:1px solid var(--line)}
.list h3{margin-bottom:.2em}.list p{color:var(--muted);margin:0}
.list a:hover h3{color:var(--wine)}
@media(max-width:860px){.dgrid{grid-template-columns:1fr}.sunset{min-height:280px}}
/* services */
.svc{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:40px}
.svc a{border:1px solid var(--line);padding:34px 24px;min-height:200px;display:flex;flex-direction:column;justify-content:space-between;transition:.25s}
.svc a:hover{background:var(--wine);color:var(--on-dark);border-color:var(--wine)}
.svc b{font:500 2.4rem "Cormorant Garamond",serif;color:var(--gold)}
@media(max-width:860px){.svc{grid-template-columns:1fr 1fr}}
/* offers */
.offers{background:var(--wine2);color:var(--on-dark)}
.offers h2{text-align:center;margin-bottom:56px}
.ogrid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px}
.card{background:rgba(255,255,255,.05);display:flex;flex-direction:column}
.card .img{aspect-ratio:4/3}
.o1{background:url('https://villamontevino.com/wp-content/uploads/elementor/thumbs/%D0%9A%D0%B0%D1%80%D1%82%D0%B8%D1%87%D0%BA%D0%B0-3-%D0%B2%D0%B8%D0%BD%D0%B0-rj5an19j3si92mpu4q08j3eo27mg1pr3qwebe37tf8.png') center/cover no-repeat,linear-gradient(135deg,#a43a52,#4a1424)}.o2{background:url('https://villamontevino.com/wp-content/uploads/2025/03/img-59-255x170.jpg') center/cover no-repeat,linear-gradient(135deg,#c79a4d,#5a3a14)}.o3{background:url('https://villamontevino.com/wp-content/uploads/2025/03/young-couple-celebrating-valentine-s-day-while-having-lunch-wine-together_23-2149216151-600x400.jpg') center/cover no-repeat,linear-gradient(135deg,#6c8a52,#26361c)}
.card .body{padding:26px;display:flex;flex-direction:column;gap:6px;flex:1}
.price{font:500 2rem "Cormorant Garamond",serif;color:#e0c58f}
.card small{color:#cbb9a8;margin-top:auto;padding-top:12px}
.tag{font-size:.8rem;color:#cbb9a8}
@media(max-width:960px){.ogrid{grid-template-columns:1fr}}
/* events */
.gal{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.gal div{aspect-ratio:1}
.e1{background:url('https://villamontevino.com/wp-content/uploads/2023/03/Villa-Monte-vino-1-5-e1741532180622.jpg') center/cover no-repeat,linear-gradient(135deg,#d8b78a,#8a5a3a)}.e2{background:url('https://villamontevino.com/wp-content/uploads/2025/03/wine-2921784_640-1.jpg') center/cover no-repeat,linear-gradient(135deg,#9a6a7a,#42202e)}.e3{background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-%D1%85%D1%80%D0%B0%D0%BD%D0%B0-1-0002_preview-600x902.jpeg') center/cover no-repeat,linear-gradient(135deg,#8ea27a,#3d4a2c)}.e4{background:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-%D1%85%D1%80%D0%B0%D0%BD%D0%B0-1-0235_preview-600x902.jpeg') center/cover no-repeat,linear-gradient(135deg,#c2a07a,#5a3a24)}
/* shop */
.shop{background:var(--surface);text-align:center}
.sgrid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin:40px 0}
.sgrid a{background:var(--bg);padding:24px;border:1px solid var(--line)}
.sgrid img{display:block;width:100%;aspect-ratio:2/3;object-fit:cover;margin:0 0 18px}
.logo img{height:54px;display:block}
footer .flogo{height:70px;margin-bottom:16px}
.ph span{display:none}
.sgrid h3{font-size:1.3rem}.sgrid span{font-size:.8rem;color:var(--gold)}
.shop p{margin-left:auto;margin-right:auto}
@media(max-width:860px){.sgrid{grid-template-columns:1fr 1fr}}
/* contact */
.contact{background:linear-gradient(rgba(30,33,28,.84),rgba(30,33,28,.92)),url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-lozia-0002_preview.jpeg') center/cover no-repeat;color:var(--on-dark)}
.contact ul{list-style:none;padding:0;margin:0 0 24px}
.contact li{padding:6px 0}
form{display:grid;gap:14px}
input,textarea{width:100%;font:inherit;padding:13px 14px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.3);color:inherit;border-radius:0}
input::placeholder,textarea::placeholder{color:#cdbdb0}
.row{display:flex;gap:12px;flex-wrap:wrap}.row button{flex:1;min-width:140px;cursor:pointer;font:inherit}
.btn.light{border-color:var(--on-dark);color:var(--on-dark);background:none}.btn.light:hover{background:var(--on-dark);color:var(--wine2)}
.ok{display:none;color:#e0c58f}
/* footer */
footer{background:#1e211c;color:#cdbdb0;padding:72px 0 28px;font-size:.9rem}
.fgrid{display:grid;grid-template-columns:1.6fr 1fr 1fr;gap:48px}
footer h3{color:var(--on-dark);font-size:1.3rem}
footer ul{list-style:none;padding:0;margin:0}footer li{padding:4px 0}
footer a:hover{color:#e0c58f}
.legal{border-top:1px solid #33382f;margin-top:48px;padding-top:20px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;font-size:.8rem}
.legal span a{margin-left:16px}
@media(max-width:860px){.fgrid{grid-template-columns:1fr}}
/* cookie */
.cookie{position:fixed;left:16px;right:16px;bottom:calc(16px + env(safe-area-inset-bottom,0px));z-index:30;background:var(--wine2);color:var(--on-dark);padding:14px 18px;display:flex;gap:16px;align-items:center;justify-content:space-between;font-size:.85rem;max-width:560px}
.cookie button{background:var(--gold);border:0;padding:8px 22px;cursor:pointer;font:inherit}

/* === match Lovable design === */
body{font-weight:400}
h1,h2,h3{font-weight:300}
.kicker{font:500 .8rem/1.4 "Sofia Sans Semi Condensed",system-ui,sans-serif;letter-spacing:.32em;text-transform:uppercase;color:#6b6b6b;font-style:normal;margin-bottom:1.1rem}
h2{font-size:clamp(2.4rem,5vw,4.2rem);line-height:1.05}
.btn{border:1px solid var(--gold);color:var(--gold);font:500 .72rem "Sofia Sans Semi Condensed",system-ui,sans-serif;letter-spacing:.25em;text-transform:uppercase;padding:16px 32px}
.btn:hover{background:var(--gold);color:#fff}
.btn.solid{background:var(--gold);color:#fff}.btn.solid:hover{background:#a3865b}
.btn.light{color:#fff;border-color:#fff}.btn.light:hover{background:#fff;color:#1e211c}
.topbar{background:#1e211c}
header{background:rgba(107,124,94,.97);border:0;color:#fff}
header .wrap.hw{display:grid;grid-template-columns:1fr auto 1fr;height:92px;max-width:1440px;padding:0 48px}
.hl{display:flex;align-items:center;gap:36px}
.hr{display:flex;align-items:center;gap:24px;justify-content:flex-end}
.logo img{height:64px}
nav ul{gap:34px}
nav>ul>li>a{font:500 1.02rem "Cormorant Garamond",serif;letter-spacing:.2em;text-transform:uppercase;color:#fff}
nav>ul>li>a::after{content:" ⌄";font-size:.8em}
nav>ul>li>a:only-child::after{content:""}
nav li>ul{background:var(--sage);border:0;top:calc(100% + 18px)}
nav li>ul a{color:#fff;font-size:.9rem}
nav a:hover{color:#e6d3ad}
.burger{color:#fff;padding:0}
.cta-h{padding:14px 26px}
.cart{color:#fff}
@media(max-width:960px){header .wrap.hw{padding:0 20px;height:76px}.hl{gap:0}.logo img{height:52px}
nav{top:76px;background:var(--sage)}nav>ul>li>a::after{content:""}nav li>ul{background:transparent}}
.hero::after{background:linear-gradient(0deg,rgba(18,18,18,.7),rgba(18,18,18,.35))}
.hero h1{font-weight:300;font-size:clamp(2.8rem,6.4vw,5.6rem);max-width:17ch;margin-bottom:.9em}
.hero .kicker{color:#e6d3ad}
.hero .wrap{bottom:130px;max-width:1440px;padding:0 48px}
.counter{position:absolute;z-index:3;left:max(48px,calc((100vw - 1440px)/2 + 48px));bottom:48px;display:flex;gap:14px;align-items:center;color:#fff;font:400 1.2rem "Cormorant Garamond",serif}
.segs{display:flex;gap:10px}
.segs i{display:block;width:clamp(30px,8vw,104px);height:2px;background:rgba(255,255,255,.3)}
.segs i.on{background:var(--gold)}
.arrows{position:absolute;z-index:3;right:max(48px,calc((100vw - 1440px)/2 + 48px));bottom:36px;display:flex;gap:14px}
.arrows button{width:60px;height:60px;border-radius:50%;border:1px solid rgba(255,255,255,.55);background:rgba(0,0,0,.2);color:#fff;font-size:1.3rem;cursor:pointer}
.arrows button:hover{background:var(--gold);border-color:var(--gold)}
@media(max-width:700px){.hero .wrap{padding:0 20px}.counter{left:20px}.arrows{right:20px}.arrows button{width:46px;height:46px}.segs i{width:20px}}
.frame::before{content:"";position:absolute;left:-25px;top:-25px;width:160px;height:160px;border-top:1px solid var(--gold);border-left:1px solid var(--gold);z-index:1}
.marquee{background:var(--sage)}
.price,.svc b{color:var(--gold)}
.offers{background:#1e211c}
.discover,.shop{background:#f6f2ec}

/* === transparent header over full-screen hero === */
.topbar{position:absolute;top:0;left:0;right:0;z-index:25;background:rgba(40,55,32,.6);backdrop-filter:blur(3px);border-bottom:1px solid rgba(255,255,255,.12);padding:0;height:50px;display:flex;align-items:center;transition:opacity .3s}
.topbar .wrap{max-width:1440px;width:100%;padding:0 48px;display:flex;justify-content:space-between;align-items:center}
.ti{display:inline-flex;align-items:center;gap:10px;font:500 .82rem "Sofia Sans Semi Condensed",system-ui,sans-serif;color:#fff}
.tr{display:flex;gap:32px}
.topbar a:hover{color:#e6d3ad}
header{position:fixed;top:50px;left:0;right:0;background:transparent;border:0;transition:top .3s,background .3s}
header .wrap.hw{height:140px;align-items:center;transition:height .3s}
.logo img{height:96px;transition:height .3s}
body.scrolled .topbar{opacity:0;pointer-events:none}
body.scrolled header{top:0;background:rgba(107,124,94,.97);box-shadow:0 2px 14px rgba(0,0,0,.18)}
body.scrolled header .wrap.hw{height:84px}
body.scrolled .logo img{height:60px}
nav li>ul{background:rgba(107,124,94,.97)}
.hero{height:100vh;height:100svh;min-height:620px}
.hero::after{background:linear-gradient(180deg,rgba(15,20,12,.45) 0%,rgba(15,20,12,.15) 35%,rgba(15,20,12,.72) 100%)}
.hero .wrap{bottom:150px}
.hero .kicker{display:flex;align-items:center;gap:20px;color:#fff;font-weight:500;letter-spacing:.32em}
.hero .kicker::before{content:"";width:60px;height:1px;background:var(--gold)}
@media(max-width:960px){.topbar .wrap,header .wrap.hw{padding:0 20px}header .wrap.hw{height:96px}.logo img{height:64px}.tr{gap:14px}}
@media(max-width:700px){.addr{display:none}.topbar .wrap{justify-content:center}.ti{font-size:.7rem}.tr{gap:12px}}

/* === menu like original: burger + 3 items, centered logo === */
.burger{display:block;background:none;border:0;color:#fff;cursor:pointer;padding:0;line-height:0}
header .wrap.hw{position:relative;display:flex;justify-content:space-between}
.logo{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%)}
.hl{gap:40px}
nav{position:static;display:block!important;background:none;border:0;max-height:none;overflow:visible}
nav ul{flex-direction:row;padding:0;gap:34px}
nav>ul>li>a{display:inline;padding:0}
nav li>ul{position:absolute;flex-direction:column;padding:8px 0;min-width:220px}
@media(max-width:1250px){nav ul{gap:22px}nav>ul>li>a{font-size:.92rem;letter-spacing:.14em}.hl{gap:26px}}
@media(max-width:1100px){nav{display:none!important}}
.backdrop{position:fixed;inset:0;background:rgba(0,0,0,.5);opacity:0;pointer-events:none;transition:opacity .3s;z-index:40}
.panel{position:fixed;top:0;bottom:0;left:0;width:min(420px,88vw);background:#1e211c;color:#fff;z-index:50;transform:translateX(-100%);transition:transform .35s;padding:90px 40px 40px;overflow:auto;visibility:hidden}
body.menu .backdrop{opacity:1;pointer-events:auto}
body.menu .panel{transform:none;visibility:visible}
.panel ul{list-style:none;margin:0;padding:0}
.panel>ul>li{border-bottom:1px solid rgba(255,255,255,.12);padding:12px 0}
.panel>ul>li>a{font:500 1.15rem "Cormorant Garamond",serif;letter-spacing:.2em;text-transform:uppercase}
.panel li ul{padding:8px 0 4px 14px}
.panel li li a{display:block;padding:5px 0;color:#cfc8b8;font-size:.9rem}
.panel a:hover{color:#e6d3ad}
.panel .x{position:absolute;top:26px;right:28px;background:none;border:0;color:#fff;font-size:1.3rem;cursor:pointer}
@media(prefers-reduced-motion:reduce){.panel,.backdrop{transition:none}}

/* === slider text like original === */
.hero h1{font-weight:400;font-size:clamp(2.4rem,4.8vw,5.8rem);line-height:1.22;letter-spacing:0;max-width:13.2em;margin:0;text-shadow:0 2px 24px rgba(0,0,0,.35)}
.hero .kicker{margin-bottom:28px;font-size:.78rem;font-weight:500;letter-spacing:.34em}
@media(max-width:700px){.hero h1{font-size:2.3rem;line-height:1.2}.hero .wrap{bottom:130px}.hero .kicker{letter-spacing:.2em;font-size:.7rem}.hero .kicker::before{width:30px}}

/* === services carousel === */
.svcs{--edge:max(24px,calc((100vw - 1560px)/2));padding:110px 0 120px;overflow:hidden}
.sh{display:flex;justify-content:space-between;align-items:flex-end;padding:0 var(--edge);margin-bottom:56px;gap:24px}
.sh h2{margin:0}
.sa{display:flex;gap:14px}
.sa button{width:60px;height:60px;border-radius:50%;border:1px solid var(--line);background:#fff;color:var(--ink);font-size:1.2rem;cursor:pointer;transition:.25s}
.sa button:hover:not(:disabled){background:var(--gold);border-color:var(--gold);color:#fff}
.sa button:disabled{opacity:.4;cursor:default}
.st{display:flex;gap:30px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding:0 var(--edge);scroll-padding-left:var(--edge)}
.st::-webkit-scrollbar{display:none}
.sc{position:relative;flex:0 0 min(567px,82vw);aspect-ratio:3/4;scroll-snap-align:start;background:linear-gradient(0deg,rgba(15,15,12,.72),rgba(15,15,12,0) 45%),var(--img) center/cover no-repeat,#3a3f33;color:#fff;overflow:hidden}
.sc .n{position:absolute;left:50px;bottom:112px;font:400 1.1rem "Cormorant Garamond",serif;color:#d4b98a}
.sc h3{position:absolute;left:50px;bottom:44px;margin:0;font-size:clamp(1.9rem,2.6vw,2.7rem);font-weight:500}
.sc::after{content:"";position:absolute;inset:30px;border:1px solid rgba(255,255,255,.65);opacity:0;transition:opacity .4s;pointer-events:none}
.sc .go{position:absolute;right:50px;bottom:40px;width:60px;height:60px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:1.2rem;opacity:0;transform:translateY(10px);transition:.4s}
.sc:hover::after,.sc:focus-visible::after{opacity:1}
.sc:hover .go,.sc:focus-visible .go{opacity:1;transform:none}
@media(max-width:700px){.sh{flex-direction:column;align-items:flex-start}.sc .n,.sc h3{left:28px}.sc .go{right:28px;width:48px;height:48px}}

/* === events split slider === */
body{font-family:"Sofia Sans Semi Condensed",system-ui,sans-serif}
.ev{padding:0;display:grid;grid-template-columns:5fr 7fr;min-height:min(100vh,860px);background:#44543f;color:#fff}
.evt{padding:110px clamp(24px,6.3vw,130px) 110px clamp(24px,6.3vw,120px);display:flex;flex-direction:column;justify-content:center;align-items:flex-start}
.ev .kicker{color:var(--gold)}
.ev h2{font-weight:400;font-size:clamp(3.2rem,5.4vw,5.8rem);margin-bottom:.45em}
.ev p{font-size:1.1rem;line-height:1.85;color:#eef0ea;max-width:34em;margin-bottom:1.7em}
.ev .btn{margin-top:1.2rem}
.evs{position:relative;overflow:hidden;min-height:420px}
.es{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0) 70%,rgba(0,0,0,.35)),var(--img) center/cover no-repeat,#566650;opacity:0;transform:scale(1.04);transition:opacity 1.2s,transform 6s linear}
.es.on{opacity:1;transform:scale(1)}
.ets{position:absolute;left:0;right:0;bottom:0;display:flex;gap:14px;padding:22px 28px;justify-content:center;z-index:2}
.et{width:clamp(64px,8vw,116px);aspect-ratio:3/2;border:2px solid rgba(255,255,255,.5);background:var(--img) center/cover no-repeat;padding:0;cursor:pointer;opacity:.75;transition:.3s}
.et:hover{opacity:1}.et.on{border-color:var(--gold);opacity:1}
@media(max-width:900px){.ev{grid-template-columns:1fr}.evs{height:70vw;min-height:320px}.evt{padding:72px 24px}}
@media(prefers-reduced-motion:reduce){.es{transition:opacity .3s;transform:none}}

/* === side menu panel like original === */
.backdrop{background:rgba(10,12,8,.62);backdrop-filter:blur(8px)}
.panel{width:min(542px,92vw);padding:36px 60px 40px;background:linear-gradient(180deg,#5d7050 0%,#46573e 55%,#3a4833 100%);display:flex;flex-direction:column}
.pt{display:flex;justify-content:space-between;align-items:flex-start;margin:-20px 0 44px;position:relative}
.pt img{height:84px;display:block}
.panel .x{position:static;width:55px;height:55px;border-radius:50%;border:1px solid rgba(255,255,255,.45);display:flex;align-items:center;justify-content:center;margin-right:-18px;margin-top:20px;transition:.25s}
.panel .x:hover{background:var(--gold);border-color:var(--gold)}
.pm{list-style:none;margin:0;padding:0}
.pm>li{border-bottom:1px solid rgba(255,255,255,.18)}
.pm>li>a,.pm .row{display:flex;justify-content:space-between;align-items:center;width:100%;padding:18px 0;background:none;border:0;color:#fff;font:500 clamp(2rem,2.8vw,2.75rem)/1.2 "Cormorant Garamond",serif;text-transform:uppercase;letter-spacing:.02em;text-align:left;cursor:pointer}
.pm>li>a.act{color:var(--gold)}
.pm>li>a:hover,.pm .row:hover{color:#e6d3ad}
.row svg{transition:transform .3s;flex:none}
.has.open .row svg{transform:rotate(180deg)}
.pm li ul{display:none;list-style:none;margin:0;padding:0 0 16px 4px}
.has.open>ul{display:block}
.pm li li a{display:block;padding:7px 0;color:#e8e6dc;font-size:1rem;font-family:"Sofia Sans Semi Condensed",system-ui,sans-serif;text-transform:none;letter-spacing:.02em}
.pm li li a:hover{color:#e6d3ad}
.pc{margin-top:auto;padding-top:48px;display:flex;flex-direction:column;gap:18px}
.pc::before{content:"";border-top:1px solid rgba(255,255,255,.18);margin-bottom:12px}
.pc>a{display:flex;align-items:center;gap:14px;font:400 .98rem "Sofia Sans Semi Condensed",system-ui,sans-serif;color:#e8e6dc}
.pc a:hover{color:#e6d3ad}
.ps{display:flex;gap:15px;margin-top:6px}
.ps a{width:50px;height:50px;border:1px solid rgba(255,255,255,.4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;transition:.25s}
.ps a:hover{background:var(--gold);border-color:var(--gold);color:#fff}
@media(max-width:520px){.panel{padding:28px 28px 32px}}

/* === discover grid === */
.disc{background:#f7f3ec;padding:110px 0 120px}
.dw{max-width:1440px;margin:0 auto;padding:0 24px}
.dh{display:flex;justify-content:space-between;align-items:flex-start;gap:40px;margin-bottom:80px}
.dh h2{font-weight:400;font-size:clamp(2.8rem,5.2vw,5.4rem);max-width:9em;margin:0}
.dimg{flex:0 0 min(543px,40%);aspect-ratio:543/300;background:var(--img) center/cover no-repeat,linear-gradient(180deg,#e9a35b,#3d0f1c)}
.dgr{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--line);border-left:1px solid var(--line)}
.dc{position:relative;display:block;padding:50px 50px 56px;min-height:418px;background:#fff;border-right:1px solid var(--line);border-bottom:1px solid var(--line);transition:background .35s,color .35s}
.dc .dn{position:absolute;top:36px;right:50px;font:400 .95rem "Cormorant Garamond",serif;color:var(--gold)}
.dc .di{width:80px;height:80px;border:1px solid #e3d5bd;border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gold);margin-bottom:54px;transition:.35s}
.dc h3{font-size:clamp(1.9rem,2.4vw,2.4rem);font-weight:400;margin-bottom:.5em}
.dc p{color:var(--muted);font-size:1.02rem;line-height:1.85;margin:0;transition:color .35s}
.dc:hover{background:#4f6048;color:#fff}
.dc:hover p{color:#f0efe8}.dc:hover .di{border-color:rgba(255,255,255,.5);color:#fff}.dc:hover .dn{color:#e6d3ad}
@media(max-width:1000px){.dgr{grid-template-columns:1fr 1fr}.dh{flex-direction:column}.dimg{flex:none;width:100%}}
@media(max-width:640px){.dgr{grid-template-columns:1fr}.dc{min-height:0;padding:36px 28px}.dc .dn{right:28px}.dc .di{margin-bottom:30px}}

/* === footer like original === */
.ft{padding:0;background:#1a1c18;color:#e8e6dc;font-size:1.05rem}
.fm{background:linear-gradient(rgba(22,24,20,.74),rgba(22,24,20,.86)),var(--fbg) center/cover no-repeat;padding:140px 0 150px}
.fw{max-width:1560px;margin:0 auto;padding:0 24px}
.fg3{display:grid;grid-template-columns:1.25fr 1fr .8fr;gap:48px}
.ft .flogo{height:104px;display:block;margin-bottom:18px}
.fbrand{font:500 clamp(2rem,3.2vw,3.2rem)/1.1 "Cormorant Garamond",serif;letter-spacing:.2em;text-transform:uppercase;color:#fff;margin-bottom:28px}
.ft p{color:#e8e6dc;max-width:34em;line-height:1.85;margin-bottom:34px}
.ft h3{font-size:clamp(1.8rem,2.2vw,2.2rem);font-weight:400;color:#fff;margin:6px 0 34px}
.fl{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:22px}
.fl a,.fl span{display:flex;align-items:center;gap:14px}
.fl svg{color:var(--gold);flex:none}
.fl a:hover,.fdir:hover,.fln a:hover{color:#e6d3ad}
.fdir{display:inline-block;margin-top:38px;font:500 .8rem "Sofia Sans Semi Condensed",sans-serif;letter-spacing:.3em;text-transform:uppercase;color:#fff;padding-bottom:6px;border-bottom:1px solid var(--gold)}
.ft .ps a{color:#fff}
.fb{background:#1a1c18;border-top:1px solid #33382f}
.fb .fw{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;min-height:112px}
.cp{font:400 1.15rem "Cormorant Garamond",serif}
.fln{display:flex;align-items:center;gap:30px;flex-wrap:wrap}
.fln a:not(.up){font:500 1rem "Cormorant Garamond",serif;letter-spacing:.18em;text-transform:uppercase}
.fln .up{width:50px;height:50px;border:1px solid rgba(255,255,255,.4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;transition:.25s}
.fln .up:hover{background:var(--gold);border-color:var(--gold);color:#fff}
@media(max-width:900px){.fg3{grid-template-columns:1fr}.fm{padding:90px 0}}

.lang{display:flex;align-items:center;gap:8px;color:#fff;font:500 .95rem "Sofia Sans Semi Condensed",sans-serif;letter-spacing:.15em}
.lang button{background:none;border:0;color:inherit;font:inherit;letter-spacing:inherit;cursor:pointer;padding:4px 2px;opacity:.7;border-bottom:1px solid transparent}
.lang button:hover{opacity:1}
.lang button.on{opacity:1;border-bottom-color:var(--gold)}
.hr{gap:24px}
@media(max-width:560px){.hr{gap:14px}}

.vid{position:absolute;top:50%;left:50%;width:max(100%,calc(100vh*1.7778));height:max(100%,calc(100vw*.5625));transform:translate(-50%,-50%);border:0;pointer-events:none;opacity:0;transition:opacity 1s}
.vid.ready{opacity:1}
@media(max-width:1024px){.vid{display:none}}

/* === light offers === */
.of{background:#f7f3ec;padding:110px 0 130px}
.ow{max-width:1560px;margin:0 auto;padding:0 24px}
.of h2{text-align:center;font-weight:400;font-size:clamp(2.4rem,4.4vw,4.2rem);margin:0 0 72px}
.of h2::before,.of h2::after{content:"~";color:var(--gold);margin:0 .5em}
.og{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;align-items:stretch}
.oc{background:#fff;display:flex;flex-direction:column;box-shadow:0 10px 40px rgba(60,50,30,.1);color:var(--ink)}
.oc:nth-child(2){transform:translateY(-10px)}
.oi{position:relative;aspect-ratio:493/370;background:var(--img) center/cover no-repeat,linear-gradient(135deg,#c79a4d,#5a3a14)}
.oi::after{content:"";position:absolute;inset:15px;border:1px solid rgba(255,255,255,.75);pointer-events:none}
.op{position:absolute;z-index:1;top:29px;left:30px;background:#fff;padding:10px 20px;font:400 1.7rem/1.2 "Cormorant Garamond",serif;color:var(--ink)}
.ob{flex:1;display:flex;flex-direction:column;align-items:center;text-align:center;padding:44px 38px 40px}
.ok{font:500 .82rem "Sofia Sans Semi Condensed",sans-serif;letter-spacing:.3em;text-transform:uppercase;color:var(--sage);margin-bottom:14px}
.ob h3{font-size:clamp(1.9rem,2.5vw,2.6rem);font-weight:400;margin-bottom:.35em}
.ol{display:block;width:50px;height:1px;background:var(--gold);margin:12px 0 26px}
.ob p{color:#555;line-height:1.9;margin:0 0 1em;max-width:none;font-size:1.02rem}
.od{margin-top:auto;padding-top:36px;display:flex;align-items:center;gap:10px;font:500 .95rem "Sofia Sans Semi Condensed",sans-serif;letter-spacing:.18em;text-transform:uppercase;color:var(--ink)}
.od svg{color:var(--gold)}
@media(max-width:1000px){.og{grid-template-columns:1fr;max-width:560px;margin:0 auto}.oc:nth-child(2){transform:none}}
.cartl{display:flex;color:#fff;transition:color .25s}.cartl:hover{color:#e6d3ad}

.ph0{position:relative;min-height:64vh;display:flex;align-items:flex-end;padding:190px 0 72px;color:#fff;background:linear-gradient(180deg,rgba(15,20,12,.6),rgba(15,20,12,.78)),var(--img) center/cover no-repeat,#44543f}
.ph0 .in{max-width:1440px;width:100%;margin:0 auto;padding:0 48px}.ph0 h1{font-weight:400;font-size:clamp(2.8rem,6vw,5.4rem);margin:0}.ph0 .kicker{color:#e6d3ad}
.sec{padding:96px 0}.sec.alt{background:#f7f3ec}.sw{max-width:1200px;margin:0 auto;padding:0 24px}.sw.w{max-width:1560px}
.sec h2{font-weight:400;font-size:clamp(2.2rem,4vw,3.6rem);margin-bottom:.5em}.sec p{max-width:44em;line-height:1.85}
.two{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center}.two.r .im{order:2}
.im{aspect-ratio:4/3;background:var(--img) center/cover no-repeat,#8ea27a;position:relative}.im::after{content:"";position:absolute;inset:15px;border:1px solid rgba(255,255,255,.7)}
.c3{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;margin-top:40px}.c2{display:grid;grid-template-columns:repeat(2,1fr);gap:28px;margin-top:40px}
.bx{background:#fff;border:1px solid var(--line);padding:38px 34px}.sec.alt .bx{background:#fff}.bx h3{font-size:1.9rem;margin-bottom:.4em}.bx p{color:var(--muted);margin:0}
.bx .n{font:400 1rem "Cormorant Garamond",serif;color:var(--gold);display:block;margin-bottom:12px}
.cta{background:#44543f;color:#fff;text-align:center;padding:90px 24px}.cta h2{font-weight:400;font-size:clamp(2rem,3.6vw,3.2rem);margin-bottom:.4em}.cta p{margin:0 auto 28px;color:#eef0ea}
.cta .btn{margin:0 8px 10px}.cta .btn.o{background:none;border-color:#fff}.cta .btn.o:hover{background:#fff;color:#44543f}
.ct{display:grid;grid-template-columns:1fr 1.2fr;gap:64px}.ct ul{list-style:none;margin:20px 0;padding:0}.ct li{padding:8px 0;display:flex;gap:12px}
.fm2 label{display:block;font-size:.85rem;color:var(--muted);margin-top:14px}.fm2 input,.fm2 textarea{width:100%;padding:14px;border:1px solid var(--line);font:inherit;background:#fff}
.fm2 input:focus,.fm2 textarea:focus{outline:0;border-color:var(--gold)}.fm2 .btn{margin-top:18px;cursor:pointer}.msg{padding:14px 18px;margin-bottom:20px}.msg.ok2{background:#e6efe0}.msg.er2{background:#f6ded9}
.hp2{position:absolute;left:-9999px}
@media(max-width:900px){.two,.ct{grid-template-columns:1fr;gap:36px}.two.r .im{order:0}.c3,.c2{grid-template-columns:1fr}.ph0 .in{padding:0 20px}}
</style>
</head>
<body>
<div class="topbar"><div class="wrap"><a class="ti addr" href="https://maps.app.goo.gl/aa8MFqLGWN8XoLM68"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 21s7-6.2 7-11.5A7 7 0 005 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>общ. Кочериново, между Благоевград и Рилски манастир</a><span class="tr"><a class="ti" href="tel:+359883733344"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>Телефон: +359 88 373 3344</a><a class="ti" href="mailto:office@villamontevino.com"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>office@villamontevino.com</a></span></div></div>

<header><div class="wrap hw"><div class="hl"><button class="burger" aria-label="Меню" aria-controls="panel" onclick="tp(true)"><svg width="46" height="22" viewBox="0 0 46 22" stroke="currentColor" stroke-width="1.6"><path d="M0 2h46M0 11h32M0 20h46"/></svg></button><nav aria-label="Основно меню"><ul><li><a href="vinarna.php">Винарна</a><ul><li><a href="vinarna.php#degustacii">Дегустации</a></li><li><a href="vinarna.php#proizvodstvo">Производство</a></li><li><a href="vinarna.php#lozia">Вино &amp; Лозя</a></li><li><a href="https://villamontevino.com/magazin/">Магазин</a></li></ul></li><li><a href="restorant.php">Ресторант</a><ul><li><a href="restorant.php#menu">Меню</a></li><li><a href="restorant.php#info">Информация</a></li></ul></li><li><a href="subitia.php">Събития</a><ul><li><a href="subitia.php#vasheto">Вашето събитие</a></li><li><a href="subitia.php#kuverti">Куверти за събитие</a></li><li><a href="subitia.php#kalendar">Календар</a></li></ul></li></ul></nav></div><a class="logo" href="index.php" aria-label="Вила Монтевино"><img src="https://villamontevino.com/wp-content/uploads/2023/03/%D0%9B%D0%BE%D0%B3%D0%BE-Winery-Estate.png" alt="Вила Монтевино Winery Estate"></a><div class="hr"><div class="lang" role="group" aria-label="Език / Language"><button type="button" data-l="bg" class="on">BG</button><span aria-hidden="true">/</span><button type="button" data-l="en">EN</button></div><a class="btn solid cta-h" href="#magazin">Онлайн магазин &nbsp;→</a><a class="cartl" href="https://villamontevino.com/kolichka/" aria-label="Количка"><svg class="cart" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5.5 8.5h13l1 11a1.5 1.5 0 01-1.5 1.6H6a1.5 1.5 0 01-1.5-1.6l1-11z"/><path d="M9 10.5V7.5a3 3 0 016 0v3"/></svg></a></div></div></header><div class="backdrop" onclick="tp(false)"></div>
<aside class="panel" id="panel" aria-label="Меню"><div class="pt"><a href="index.php"><img src="https://villamontevino.com/wp-content/uploads/2023/03/%D0%9B%D0%BE%D0%B3%D0%BE-Winery-Estate.png" alt="Вила Монтевино"></a><button class="x" aria-label="Затвори" onclick="tp(false)"><svg width="20" height="20" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path d="M5 5l14 14M19 5L5 19"/></svg></button></div>
<ul class="pm"><li><a class="" href="index.php">Начало</a></li><li class="has"><button class="row" aria-expanded="false"><span>Винарна</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 9l7 7 7-7"/></svg></button><ul><li><a href="vinarna.php#degustacii">Дегустации</a></li><li><a href="vinarna.php#proizvodstvo">Производство</a></li><li><a href="vinarna.php#lozia">Вино &amp; Лозя</a></li><li><a href="https://villamontevino.com/magazin/">Магазин</a></li></ul></li><li class="has"><button class="row" aria-expanded="false"><span>Ресторант</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 9l7 7 7-7"/></svg></button><ul><li><a href="restorant.php#menu">Меню</a></li><li><a href="restorant.php#info">Информация</a></li></ul></li><li><a class="" href="https://villamontevino.com/magazin/">Магазин</a></li><li class="has"><button class="row" aria-expanded="false"><span>Събития</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 9l7 7 7-7"/></svg></button><ul><li><a href="subitia.php#vasheto">Вашето събитие</a></li><li><a href="subitia.php#kuverti">Куверти за събитие</a></li><li><a href="subitia.php#kalendar">Календар</a></li></ul></li><li><a class="" href="novini.php">Новини</a></li><li class="has"><button class="row" aria-expanded="false"><span>За нас</span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 9l7 7 7-7"/></svg></button><ul><li><a href="za-nas.php#istoria">История</a></li></ul></li><li><a class="act" href="kontakti.php">Контакти</a></li><li><a class="" href="zabelezhitelnosti.php">Забележителности</a></li></ul>
<div class="pc"><a href="tel:+359883733344"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>+359 88 373 3344</a><a href="mailto:office@villamontevino.com"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>office@villamontevino.com</a>
<div class="ps"><a href="https://www.facebook.com/villamontevino" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 8h3V4h-3a4 4 0 00-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/></svg></a><a href="https://www.instagram.com/villamontevino.bulgaria" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17" cy="7" r=".6"/></svg></a><a href="https://www.tiktok.com/@villa.montevino" aria-label="TikTok"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 3v11.5a3.5 3.5 0 11-3.5-3.5M14 3c.4 2.6 2.2 4.4 5 4.6"/></svg></a></div></div></aside>

<main id="top"><section class="ph0" style="--img:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-lozia-0002_preview.jpeg')"><div class="in"><span class="kicker">Изпратете ни съобщение</span><h1>Контакти</h1></div></section><section class="sec "><div class="sw"><div class="ct"><div><span class="kicker">Свържете се с нас</span><h2>Ще се радваме да чуем от вас</h2><ul><li>📍 Кочериново, Област Кюстендил</li><li>📞 <a href="tel:+359883733344">+359 88 373 3344</a></li><li>✉️ <a href="mailto:office@villamontevino.com">office@villamontevino.com</a></li></ul><a class="btn" href="https://g.co/kgs/ADHWbFt">Вземи упътване</a></div><div id="form"><?php if(isset($_GET["sent"])) echo '<div class="msg ok2" role="status">Благодарим! Ще се свържем с вас скоро.</div>'; elseif(($_GET["err"]??"")==="2") echo '<div class="msg er2" role="alert">Твърде много съобщения. Опитайте по-късно или се обадете.</div>'; elseif(isset($_GET["err"])) echo '<div class="msg er2" role="alert">Моля, въведете име и валиден имейл.</div>'; ?><form class="fm2" method="post" action="kontakti.php"><label>Име *</label><input name="name" required maxlength="100"><label>Имейл *</label><input type="email" name="email" required maxlength="120"><label>Телефон</label><input type="tel" name="phone" maxlength="25"><label>Съобщение</label><textarea name="message" rows="5" maxlength="2000"></textarea><input class="hp2" name="website" tabindex="-1" autocomplete="off" aria-hidden="true"><button class="btn solid">Изпрати</button></form></div></div></div></section></main>

<footer class="ft" style="--fbg:url('https://villamontevino.com/wp-content/uploads/2025/06/Monitevino-lozia-0002_preview.jpeg')"><div class="fm"><div class="fw fg3">
<div><img class="flogo" src="https://villamontevino.com/wp-content/uploads/2023/03/%D0%9B%D0%BE%D0%B3%D0%BE-Winery-Estate.png" alt="Вила Монтевино"><div class="fbrand">Вила Монтевино</div>
<p>Вила Монтевино е бутикова винарна, разположена в подножието на Рила планина на фона на невероятната панорама. Ние вярваме, че доброто вино не само разказва история, но и създава незабравими моменти.</p>
<div class="ps"><a href="https://www.facebook.com/villamontevino" aria-label="Facebook"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 8h3V4h-3a4 4 0 00-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8z"/></svg></a><a href="https://www.instagram.com/villamontevino.bulgaria" aria-label="Instagram"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="4.5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17" cy="7" r=".6"/></svg></a><a href="https://www.tiktok.com/@villa.montevino" aria-label="TikTok"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v11.5a3.5 3.5 0 11-3.5-3.5M14 3c.4 2.6 2.2 4.4 5 4.6"/></svg></a></div></div>
<div><h3>Контакти</h3><ul class="fl"><li><a href="mailto:office@villamontevino.com"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>Имейл: office@villamontevino.com</a></li><li><a href="tel:+359883733344"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></svg>Телефон: +359 88 373 3344</a></li><li><span><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11.5A7 7 0 005 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>Кочериново, Област Кюстендил</span></li></ul><a class="fdir" href="https://g.co/kgs/ADHWbFt">Вземи упътване</a></div>
<div><h3>Информация</h3><ul class="fl"><li><a href="vinarna.php"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3h8l-.5 6a3.5 3.5 0 01-7 0L8 3z"/><path d="M12 12.5V20M8.5 21h7"/></svg>Винарна</a></li><li><a href="restorant.php"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3v6a2 2 0 004 0V3M8 3v18M17 3c-2 2-2.500 5-2 8h3.5V3M17.500 11V21"/></svg>Ресторант</a></li><li><a href="zabelezhitelnosti.php"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20l6-12 3 5 3-4 6 11H3z"/></svg>Околности</a></li></ul></div>
</div></div>
<div class="fb"><div class="fw"><span class="cp">© Copyright 2026 | Вила Монтевино</span><span class="fln"><a href="https://villamontevino.com/biskivtki/">Бисквитки</a><a href="https://villamontevino.com/politika-za-poveritelnost/">Поверителност</a><a href="https://villamontevino.com/obshti-uslovia">Условия за ползване</a><a class="up" href="#top" aria-label="Нагоре"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg></a></span></div></div></footer>

<div class="cookie" id="ck"><span>Този сайт използва бисквитки, за да осигури по-добро потребителско изживяване.</span><button onclick="document.getElementById('ck').remove()">Добре</button></div>

<script>
function sc(){document.body.classList.toggle('scrolled',window.scrollY>60)}
window.addEventListener('scroll',sc,{passive:true});sc();
function tp(o){document.body.classList.toggle('menu',o)}
document.querySelectorAll('.panel .row').forEach(function(b){b.onclick=function(){var l=b.parentNode,o=l.classList.toggle('open');b.setAttribute('aria-expanded',o)}});
document.querySelectorAll('.panel a').forEach(function(a){a.addEventListener('click',function(){tp(false)})});
document.addEventListener('keydown',function(e){if(e.key==='Escape')tp(false)});

var EN={"общ. Кочериново, между Благоевград и Рилски манастир": "Kocherinovo, between Blagoevgrad and Rila Monastery", "Телефон: +359 88 373 3344": "Phone: +359 88 373 3344", "Винарна": "Winery", "Дегустации": "Tastings", "Производство": "Production", "Вино & Лозя": "Wine & Vineyards", "Магазин": "Shop", "Ресторант": "Restaurant", "Меню": "Menu", "Информация": "Information", "Събития": "Events", "Вашето събитие": "Your event", "Куверти за събитие": "Event menus", "Календар": "Calendar", "Онлайн магазин →": "Online shop →", "Начало": "Home", "Новини": "News", "За нас": "About us", "История": "Our story", "Контакти": "Contacts", "Забележителности": "Attractions", "Бутикова винарна и ресторант": "Boutique winery and restaurant", "Вила Монтевино –": "Villa Montevino –", "където всяка капка вино разказва история": "where every drop of wine tells a story", "Лозовите масиви на Вила Монтевино": "The vineyards of Villa Montevino", "Философия в чаша вино": "A philosophy in a glass of wine", "От земята до виното – история за смисъл и удоволствие": "From the land to the wine – a story of meaning and pleasure", "Зад винарско имение „Вила Монтевино“ стоят двама приятели и една мечта – родена от убеждението, че отглеждането на лозя и създаването на вино не са просто бизнес и никога не са бърз процес. Това е философия, която отразява връзката ни със земята и ни учи да живеем по-бавно, по-смислено и с истинско усещане за наслада и удовлетворение.": "Behind the Villa Montevino winery estate stand two friends and one dream – born of the belief that growing vines and making wine is not just a business and never a quick process. It is a philosophy that reflects our bond with the land and teaches us to live more slowly, more meaningfully, and with a true sense of enjoyment and fulfilment.", "Виж повече": "Read more", "Традиция, вкус и уют": "Tradition, taste and comfort", "Открийте Вила Монтевино": "Discover Villa Montevino", "Бутикови вина": "Boutique wines", "Селекция, която носи духа на земята, слънцето и грижата на нашите винари.": "A selection that carries the spirit of the land, the sun and the care of our winemakers.", "Винени дегустации": "Wine tastings", "Незабравимо пътешествие из вкусовете и ароматите на нашите най-добри реколти.": "An unforgettable journey through the flavours and aromas of our best vintages.", "Изискана кухня": "Fine cuisine", "Ресторант „Фамозо“ предлага кулинарно преживяване, което съчетава местни традиции и продукти със световни кулинарни техники.": "Restaurant “Famoso” offers a culinary experience that combines local traditions and produce with world-class cooking techniques.", "Природа и релакс": "Nature and relaxation", "Парк „Феличе“ предлага бягство от ежедневието с кошница за пикник и чаша вино в ръка, на тревата или вечер под звездите.": "Park “Felice” is an escape from everyday life, with a picnic basket and a glass of wine in hand, on the grass or in the evening under the stars.", "Специални събития": "Special events", "Във Вила Монтевино всяко събитие става специално, защото всеки момент се превръща в незабравим спомен.": "At Villa Montevino every event becomes special, because every moment turns into an unforgettable memory.", "Охраняем паркинг": "Secure parking", "Две паркинг зони и зарядни за електрически автомобили.": "Two parking areas and charging stations for electric cars.", "Релакс и гурме изживяване, съчетани с уникални вина": "Relaxation and gourmet experiences paired with unique wines", "Услуги и удобства": "Services and amenities", "Лозя": "Vineyards", "Специални предложения": "Special offers", "дегустация на 3 вина": "tasting of 3 wines", "Трио тенори": "Trio of Tenors", "Опитайте селекция от вина, произведени от лозовите масиви на Вила Монтевино.": "Try a selection of wines produced from the vineyards of Villa Montevino.", "Ще се насладите на бяло, розе и червено вино, поднесени с гризини, изпечени в нашата фурна.": "You will enjoy a white, a rosé and a red wine, served with breadsticks baked in our oven.", "Времетраене: 30 минути": "Duration: 30 minutes", "професионална дегустация": "professional tasting", "Пътят на виното": "The Wine Path", "Опитайте 5 вина, докато нашият технолог Ви разкрива тайни за производството. Тръгнете по пътя на виното от лозовите масиви, през производствените помещения до залата за отлежаване. Опитайте вино директно от съдовете. Насладете се на селекция от сирена и колбаси от местни производители, брускети и прясно изпечен хляб с масло.": "Taste 5 wines while our winemaker shares the secrets of production. Follow the path of the wine from the vineyards, through the production rooms to the ageing hall. Taste wine straight from the tanks. Enjoy a selection of cheeses and cured meats from local producers, bruschettas and freshly baked bread with butter.", "Времетраене: 60 – 80 минути": "Duration: 60 – 80 minutes", "дегустация на 5 вина": "tasting of 5 wines", "Палитрата на Вила Монтевино": "The Villa Montevino Palette", "Представяме Ви 2 от нашите бели вина, 1 розе и 2 червени.": "We present 2 of our white wines, 1 rosé and 2 reds.", "Поднасяме ги със селекция от сирена и колбаси от местни производители, масло и прясно изпечен хляб.": "Served with a selection of cheeses and cured meats from local producers, butter and freshly baked bread.", "Времетраене: 40 – 50 минути": "Duration: 40 – 50 minutes", "Организиране на частни и корпоративни събития": "Private and corporate event planning", "Вила Монтевино предлага романтична атмосфера, изискана кухня и бутикови вина, които ще направят вашия ден наистина незабравим.": "Villa Montevino offers a romantic atmosphere, fine cuisine and boutique wines that will make your day truly unforgettable.", "Представете си как казвате „Да“ сред живописни лозови масиви, с панорамна гледка към природата и чаша от нашето най-добро вино в ръка. Вила Монтевино предлага уникална комбинация от елегантност, уют и природа, идеална за сватбени церемонии и тържества по всякакъв повод.": "Imagine saying “I do” among picturesque vineyards, with a panoramic view of nature and a glass of our finest wine in hand. Villa Montevino offers a unique combination of elegance, comfort and nature, ideal for wedding ceremonies and celebrations of any kind.", "Виж повече →": "Read more →", "Открий селекция от вина и подаръчни комплекти с отличителен вкус.": "Discover a selection of wines and gift sets with a distinctive taste.", "Подаръчни комплекти с вино": "Wine gift sets", "Онлайн магазин": "Online shop", "Изпратете ни съобщение и ние ще се свържем с вас скоро!": "Send us a message and we will get back to you soon!", "Кочериново, област Кюстендил": "Kocherinovo, Kyustendil Region", "Изпрати": "Send", "Резервация": "Reservation", "Благодарим! Ще се свържем с вас скоро.": "Thank you! We will get in touch soon.", "Вила Монтевино": "Villa Montevino", "Вила Монтевино е бутикова винарна, разположена в подножието на Рила планина на фона на невероятната панорама. Ние вярваме, че доброто вино не само разказва история, но и създава незабравими моменти.": "Villa Montevino is a boutique winery at the foot of the Rila Mountain, set against an incredible panorama. We believe that good wine not only tells a story, but also creates unforgettable moments.", "Имейл: office@villamontevino.com": "Email: office@villamontevino.com", "Кочериново, Област Кюстендил": "Kocherinovo, Kyustendil Region", "Вземи упътване": "Get directions", "Околности": "Surroundings", "© Copyright 2026 | Вила Монтевино": "© Copyright 2026 | Villa Montevino", "Бисквитки": "Cookies", "Поверителност": "Privacy", "Условия за ползване": "Terms of use", "Този сайт използва бисквитки, за да осигури по-добро потребителско изживяване.": "This website uses cookies to ensure a better user experience.", "Добре": "OK"},PH={"Име": "Name", "Имейл": "Email", "Съобщение": "Message"},TT='Villa Montevino – boutique winery and restaurant',TB=document.title,orig=new WeakMap(),lang='bg';
function nz(t){return t.replace(/[\s\u00a0]+/g,' ').trim()}
function walk(en){var w=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT,{acceptNode:function(n){var p=n.parentNode.nodeName;return(p==='SCRIPT'||p==='STYLE')?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT}}),n,list=[];
while(n=w.nextNode())list.push(n);
list.forEach(function(n){var o=orig.has(n)?orig.get(n):n.nodeValue;
if(en){var k=nz(o);if(EN[k]){orig.set(n,o);n.nodeValue=o.match(/^[\s\u00a0]*/)[0]+EN[k]+o.match(/[\s\u00a0]*$/)[0]}}
else if(orig.has(n)){n.nodeValue=orig.get(n)}})}
function sl(l){lang=l;var en=l==='en';walk(en);
document.querySelectorAll('input[placeholder],textarea[placeholder]').forEach(function(i){if(!i.dataset.p)i.dataset.p=i.placeholder;i.placeholder=en?(PH[i.dataset.p]||i.dataset.p):i.dataset.p;i.setAttribute('aria-label',i.placeholder)});
document.documentElement.lang=l;document.title=en?TT:TB;
document.querySelectorAll('.lang button').forEach(function(b){b.classList.toggle('on',b.dataset.l===l)});
try{localStorage.setItem('mv-lang',l)}catch(e){}}
document.querySelectorAll('.lang button').forEach(function(b){b.onclick=function(){sl(b.dataset.l)}});
try{if(localStorage.getItem('mv-lang')==='en')sl('en')}catch(e){}
</script>
</body>
</html>
