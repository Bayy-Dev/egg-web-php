<?php
// ============================================
//   ADMINER - CYBER NEON THEME by BAYYZ
//   Fix: load core dulu baru extend class
// ============================================

// Download adminer core ke /tmp kalau belum ada / expired
$adminer_core = sys_get_temp_dir() . "/adminer_core_latest.php";

if (!file_exists($adminer_core) || (time() - filemtime($adminer_core)) > 86400) {
    $core = @file_get_contents("https://www.adminer.org/latest.php");
    if ($core) {
        file_put_contents($adminer_core, $core);
    }
}

if (!file_exists($adminer_core)) {
    die("<h2 style='color:red;font-family:monospace'>❌ Gagal load Adminer core. Cek koneksi internet container.</h2>");
}

// Load core dulu (defines class Adminer)
// Pakai output buffering biar tidak langsung output
function adminer_object();
ob_start();
require $adminer_core;
ob_end_clean();

// Sekarang baru bisa extend
function adminer_object() {
    class AdminerCyberNeon extends Adminer {
        function name() {
            return '<span style="color:#0ff;text-shadow:0 0 10px #0ff;font-family:monospace">⚡ BAYYZ DB Manager</span>';
        }

        function head() {
            parent::head();
            echo '<style>
@import url("https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap");

:root {
    --neon-cyan: #0ff;
    --neon-pink: #f0f;
    --neon-green: #0f0;
    --neon-yellow: #ff0;
    --bg-dark: #020010;
    --bg-card: #0a0020;
    --bg-input: #050015;
    --text-main: #e0e0ff;
    --text-dim: #8888aa;
}

* { box-sizing: border-box; }

body {
    background: var(--bg-dark) !important;
    background-image:
        radial-gradient(ellipse at 20% 50%, rgba(0,50,100,0.3) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(100,0,100,0.2) 0%, transparent 50%) !important;
    color: var(--text-main) !important;
    font-family: "Share Tech Mono", monospace !important;
    min-height: 100vh;
}

body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image:
        linear-gradient(rgba(0,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 0;
}

h1 {
    font-family: "Orbitron", monospace !important;
    color: var(--neon-cyan) !important;
    text-shadow: 0 0 10px var(--neon-cyan), 0 0 20px var(--neon-cyan) !important;
    letter-spacing: 4px !important;
}

h2, h3 {
    font-family: "Orbitron", monospace !important;
    color: var(--neon-pink) !important;
    text-shadow: 0 0 8px var(--neon-pink) !important;
    letter-spacing: 2px !important;
}

/* NAV */
#menu { background: rgba(0,0,20,0.9) !important; border-right: 1px solid rgba(0,255,255,0.15) !important; }
#menu a, #menu br + a { color: var(--neon-cyan) !important; font-family: "Share Tech Mono", monospace !important; font-size: 13px !important; padding: 6px 15px !important; display: block !important; border-left: 2px solid transparent !important; transition: all 0.2s !important; }
#menu a:hover { color: var(--neon-pink) !important; border-left-color: var(--neon-pink) !important; background: rgba(255,0,255,0.05) !important; text-shadow: 0 0 8px var(--neon-pink) !important; padding-left: 20px !important; }

/* TABLE */
table { background: rgba(0,5,20,0.8) !important; border: 1px solid rgba(0,255,255,0.15) !important; width: 100% !important; border-collapse: collapse !important; }
thead th, th { background: rgba(0,255,255,0.08) !important; color: var(--neon-cyan) !important; font-family: "Orbitron", monospace !important; font-size: 11px !important; letter-spacing: 2px !important; padding: 12px 15px !important; border-bottom: 1px solid rgba(0,255,255,0.3) !important; text-transform: uppercase !important; text-shadow: 0 0 5px var(--neon-cyan) !important; }
tbody tr { border-bottom: 1px solid rgba(0,255,255,0.07) !important; transition: background 0.2s !important; }
tbody tr:hover { background: rgba(0,255,255,0.05) !important; }
tbody td { color: var(--text-main) !important; padding: 9px 15px !important; font-family: "Share Tech Mono", monospace !important; border-right: 1px solid rgba(0,255,255,0.05) !important; }

/* BUTTONS */
input[type=submit], button {
    background: transparent !important;
    color: var(--neon-cyan) !important;
    border: 1px solid var(--neon-cyan) !important;
    padding: 7px 18px !important;
    font-family: "Orbitron", monospace !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    cursor: pointer !important;
    text-transform: uppercase !important;
    transition: all 0.2s !important;
    box-shadow: 0 0 8px rgba(0,255,255,0.2) !important;
    text-shadow: 0 0 5px var(--neon-cyan) !important;
}
input[type=submit]:hover, button:hover {
    background: rgba(0,255,255,0.1) !important;
    box-shadow: 0 0 20px rgba(0,255,255,0.4) !important;
}

/* INPUTS */
input[type=text], input[type=password], input[type=search], input[type=number], select, textarea {
    background: rgba(0,5,30,0.9) !important;
    color: var(--neon-cyan) !important;
    border: 1px solid rgba(0,255,255,0.3) !important;
    padding: 7px 12px !important;
    font-family: "Share Tech Mono", monospace !important;
    font-size: 13px !important;
    outline: none !important;
    transition: all 0.2s !important;
}
input:focus, select:focus, textarea:focus {
    border-color: var(--neon-cyan) !important;
    box-shadow: 0 0 10px rgba(0,255,255,0.3) !important;
    color: #fff !important;
}
select option { background: #050015 !important; color: var(--neon-cyan) !important; }

/* LINKS */
a { color: var(--neon-cyan) !important; text-decoration: none !important; transition: all 0.2s !important; }
a:hover { color: var(--neon-pink) !important; text-shadow: 0 0 8px var(--neon-pink) !important; }

/* MESSAGES */
.message, p.message { background: rgba(0,255,0,0.05) !important; border: 1px solid rgba(0,255,0,0.3) !important; color: var(--neon-green) !important; padding: 10px 15px !important; }
.error, p.error { background: rgba(255,0,0,0.05) !important; border: 1px solid rgba(255,50,50,0.3) !important; color: #ff5555 !important; padding: 10px 15px !important; }

/* CODE */
pre, code { background: rgba(0,255,255,0.03) !important; border: 1px solid rgba(0,255,255,0.1) !important; color: var(--neon-green) !important; font-family: "Share Tech Mono", monospace !important; padding: 10px !important; }

/* NULL */
span.null { color: rgba(255,0,255,0.5) !important; font-style: italic !important; }

/* SCROLLBAR */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #020010; }
::-webkit-scrollbar-thumb { background: rgba(0,255,255,0.3); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: rgba(0,255,255,0.6); }

/* FOOTER */
#footer { color: var(--text-dim) !important; border-top: 1px solid rgba(0,255,255,0.1) !important; padding: 10px !important; font-size: 11px !important; text-align: center !important; }
</style>';
        }
    }
    return new AdminerCyberNeon();
}

// Jalankan adminer
require $adminer_core;
