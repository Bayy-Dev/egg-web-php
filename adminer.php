<?php
// BAYYZ DB Manager - Cyber Neon Theme
// NO class extension - pure CSS injection via output buffer

$core = sys_get_temp_dir() . "/adminer_latest.php";
if (!file_exists($core) || (time() - filemtime($core)) > 86400) {
    $dl = @file_get_contents("https://www.adminer.org/latest.php");
    if ($dl) file_put_contents($core, $dl);
}
if (!file_exists($core)) {
    die("<h2 style='color:red;font-family:monospace'>Gagal load Adminer. Cek koneksi.</h2>");
}

ob_start();
require $core;
$html = ob_get_clean();

$css = '<style>
@import url("https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap");
:root{--c:#0ff;--p:#f0f;--g:#0f0;--bg:#020010;--ti:#e0e0ff;--td:#8888aa}
body{background:var(--bg)!important;color:var(--ti)!important;font-family:"Share Tech Mono",monospace!important;min-height:100vh;background-image:radial-gradient(ellipse at 20% 50%,rgba(0,50,100,.3) 0,transparent 50%),radial-gradient(ellipse at 80% 20%,rgba(100,0,100,.2) 0,transparent 50%)!important}
body::before{content:"";position:fixed;top:0;left:0;right:0;bottom:0;background-image:linear-gradient(rgba(0,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(0,255,255,.03) 1px,transparent 1px);background-size:40px 40px;pointer-events:none;z-index:0}
h1{font-family:"Orbitron",monospace!important;color:var(--c)!important;text-shadow:0 0 10px var(--c),0 0 20px var(--c)!important;letter-spacing:4px!important}
h2,h3{font-family:"Orbitron",monospace!important;color:var(--p)!important;text-shadow:0 0 8px var(--p)!important;letter-spacing:2px!important}
#menu{background:rgba(0,0,20,.9)!important;border-right:1px solid rgba(0,255,255,.15)!important}
#menu a{color:var(--c)!important;font-family:"Share Tech Mono",monospace!important;font-size:13px!important;padding:6px 15px!important;display:block!important;border-left:2px solid transparent!important;transition:all .2s!important;text-decoration:none!important}
#menu a:hover{color:var(--p)!important;border-left-color:var(--p)!important;background:rgba(255,0,255,.05)!important;text-shadow:0 0 8px var(--p)!important;padding-left:20px!important}
table{background:rgba(0,5,20,.8)!important;border:1px solid rgba(0,255,255,.15)!important;width:100%!important;border-collapse:collapse!important}
thead th,th{background:rgba(0,255,255,.08)!important;color:var(--c)!important;font-family:"Orbitron",monospace!important;font-size:11px!important;letter-spacing:2px!important;padding:12px 15px!important;border-bottom:1px solid rgba(0,255,255,.3)!important;text-transform:uppercase!important;text-shadow:0 0 5px var(--c)!important}
tbody tr{border-bottom:1px solid rgba(0,255,255,.07)!important;transition:background .2s!important}
tbody tr:hover{background:rgba(0,255,255,.05)!important}
tbody td{color:var(--ti)!important;padding:9px 15px!important;font-family:"Share Tech Mono",monospace!important;border-right:1px solid rgba(0,255,255,.05)!important}
input[type=submit],button{background:transparent!important;color:var(--c)!important;border:1px solid var(--c)!important;padding:7px 18px!important;font-family:"Orbitron",monospace!important;font-size:11px!important;font-weight:700!important;letter-spacing:2px!important;cursor:pointer!important;text-transform:uppercase!important;transition:all .2s!important;box-shadow:0 0 8px rgba(0,255,255,.2)!important;text-shadow:0 0 5px var(--c)!important}
input[type=submit]:hover,button:hover{background:rgba(0,255,255,.1)!important;box-shadow:0 0 20px rgba(0,255,255,.4)!important}
input[type=text],input[type=password],input[type=search],input[type=number],select,textarea{background:rgba(0,5,30,.9)!important;color:var(--c)!important;border:1px solid rgba(0,255,255,.3)!important;padding:7px 12px!important;font-family:"Share Tech Mono",monospace!important;font-size:13px!important;outline:none!important;transition:all .2s!important}
input:focus,select:focus,textarea:focus{border-color:var(--c)!important;box-shadow:0 0 10px rgba(0,255,255,.3)!important;color:#fff!important}
select option{background:#050015!important;color:var(--c)!important}
a{color:var(--c)!important;text-decoration:none!important;transition:all .2s!important}
a:hover{color:var(--p)!important;text-shadow:0 0 8px var(--p)!important}
.message,p.message{background:rgba(0,255,0,.05)!important;border:1px solid rgba(0,255,0,.3)!important;color:var(--g)!important;padding:10px 15px!important}
.error,p.error{background:rgba(255,0,0,.05)!important;border:1px solid rgba(255,50,50,.3)!important;color:#f55!important;padding:10px 15px!important}
pre,code{background:rgba(0,255,255,.03)!important;border:1px solid rgba(0,255,255,.1)!important;color:var(--g)!important;font-family:"Share Tech Mono",monospace!important;padding:10px!important}
span.null{color:rgba(255,0,255,.5)!important;font-style:italic!important}
::-webkit-scrollbar{width:6px;height:6px}
::-webkit-scrollbar-track{background:#020010}
::-webkit-scrollbar-thumb{background:rgba(0,255,255,.3);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:rgba(0,255,255,.6)}
#footer{color:var(--td)!important;border-top:1px solid rgba(0,255,255,.1)!important;padding:10px!important;font-size:11px!important;text-align:center!important}
</style>';

$html = str_replace('<title>Adminer</title>', '<title>⚡ BAYYZ DB Manager</title>', $html);
$html = str_replace('</head>', $css . '</head>', $html);
echo $html;
