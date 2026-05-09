<?php
// ============================================
//   ADMINER - CYBER NEON THEME by BAYYZ
// ============================================

function adminer_object() {
    class AdminerCustom extends Adminer {
        function name() {
            return '<span style="color:#0ff;text-shadow:0 0 10px #0ff">⚡ BAYYZ</span> <span style="color:#f0f;text-shadow:0 0 10px #f0f">DB Manager</span>';
        }
        function loginForm() {
            echo '<style>
            /* ===== CYBER NEON THEME ===== */
            @import url("https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap");

            :root {
                --neon-cyan: #0ff;
                --neon-pink: #f0f;
                --neon-green: #0f0;
                --neon-yellow: #ff0;
                --neon-blue: #00f;
                --bg-dark: #020010;
                --bg-card: #0a0020;
                --bg-input: #050015;
                --border-glow: rgba(0,255,255,0.3);
                --text-main: #e0e0ff;
                --text-dim: #8888aa;
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                background: var(--bg-dark) !important;
                background-image:
                    radial-gradient(ellipse at 20% 50%, rgba(0,50,100,0.3) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 20%, rgba(100,0,100,0.2) 0%, transparent 50%),
                    linear-gradient(180deg, #020010 0%, #05001a 100%) !important;
                color: var(--text-main) !important;
                font-family: "Share Tech Mono", monospace !important;
                min-height: 100vh;
            }

            /* Grid scanline effect */
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

            /* ===== HEADER ===== */
            #h1, h1 {
                font-family: "Orbitron", monospace !important;
                font-size: 28px !important;
                font-weight: 900 !important;
                color: var(--neon-cyan) !important;
                text-shadow: 0 0 10px var(--neon-cyan), 0 0 20px var(--neon-cyan), 0 0 40px var(--neon-cyan) !important;
                letter-spacing: 4px !important;
                padding: 20px !important;
                border-bottom: 1px solid rgba(0,255,255,0.2) !important;
                background: rgba(0,255,255,0.03) !important;
            }

            /* ===== NAVIGATION ===== */
            #menu, nav {
                background: rgba(0,0,20,0.8) !important;
                border-right: 1px solid rgba(0,255,255,0.15) !important;
                backdrop-filter: blur(10px) !important;
            }

            #menu a, nav a {
                color: var(--neon-cyan) !important;
                text-decoration: none !important;
                font-family: "Share Tech Mono", monospace !important;
                font-size: 13px !important;
                padding: 8px 15px !important;
                display: block !important;
                border-left: 2px solid transparent !important;
                transition: all 0.2s !important;
                letter-spacing: 1px !important;
            }

            #menu a:hover, nav a:hover {
                color: var(--neon-pink) !important;
                border-left-color: var(--neon-pink) !important;
                background: rgba(255,0,255,0.05) !important;
                text-shadow: 0 0 8px var(--neon-pink) !important;
                padding-left: 20px !important;
            }

            /* ===== CONTENT AREA ===== */
            #content, #content > div {
                background: transparent !important;
                position: relative;
                z-index: 1;
            }

            /* ===== TABLES ===== */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                background: rgba(0,5,20,0.7) !important;
                border: 1px solid rgba(0,255,255,0.15) !important;
                box-shadow: 0 0 20px rgba(0,255,255,0.05) !important;
            }

            thead th, th {
                background: rgba(0,255,255,0.08) !important;
                color: var(--neon-cyan) !important;
                font-family: "Orbitron", monospace !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                letter-spacing: 2px !important;
                padding: 12px 15px !important;
                border-bottom: 1px solid rgba(0,255,255,0.3) !important;
                text-transform: uppercase !important;
                text-shadow: 0 0 5px var(--neon-cyan) !important;
            }

            tbody tr {
                border-bottom: 1px solid rgba(0,255,255,0.07) !important;
                transition: all 0.2s !important;
            }

            tbody tr:hover {
                background: rgba(0,255,255,0.05) !important;
                box-shadow: inset 0 0 20px rgba(0,255,255,0.03) !important;
            }

            tbody td {
                color: var(--text-main) !important;
                padding: 10px 15px !important;
                font-family: "Share Tech Mono", monospace !important;
                font-size: 13px !important;
                border-right: 1px solid rgba(0,255,255,0.05) !important;
            }

            /* ===== BUTTONS ===== */
            input[type=submit], button, .button {
                background: transparent !important;
                color: var(--neon-cyan) !important;
                border: 1px solid var(--neon-cyan) !important;
                padding: 8px 20px !important;
                font-family: "Orbitron", monospace !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                letter-spacing: 2px !important;
                cursor: pointer !important;
                text-transform: uppercase !important;
                transition: all 0.2s !important;
                box-shadow: 0 0 10px rgba(0,255,255,0.2), inset 0 0 10px rgba(0,255,255,0.05) !important;
                text-shadow: 0 0 5px var(--neon-cyan) !important;
                clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%) !important;
            }

            input[type=submit]:hover, button:hover {
                background: rgba(0,255,255,0.1) !important;
                box-shadow: 0 0 20px rgba(0,255,255,0.4), inset 0 0 20px rgba(0,255,255,0.1) !important;
                color: #fff !important;
                text-shadow: 0 0 10px var(--neon-cyan) !important;
            }

            /* Delete/danger buttons */
            input[type=submit][value*="Drop"], input[type=submit][value*="Delete"],
            input[type=submit][value*="Remove"] {
                color: var(--neon-pink) !important;
                border-color: var(--neon-pink) !important;
                box-shadow: 0 0 10px rgba(255,0,255,0.2) !important;
                text-shadow: 0 0 5px var(--neon-pink) !important;
            }

            /* ===== INPUTS ===== */
            input[type=text], input[type=password], input[type=search],
            input[type=number], input[type=email], select, textarea {
                background: rgba(0,5,30,0.8) !important;
                color: var(--neon-cyan) !important;
                border: 1px solid rgba(0,255,255,0.3) !important;
                padding: 8px 12px !important;
                font-family: "Share Tech Mono", monospace !important;
                font-size: 13px !important;
                outline: none !important;
                transition: all 0.2s !important;
                box-shadow: inset 0 0 10px rgba(0,255,255,0.03) !important;
            }

            input:focus, select:focus, textarea:focus {
                border-color: var(--neon-cyan) !important;
                box-shadow: 0 0 10px rgba(0,255,255,0.3), inset 0 0 10px rgba(0,255,255,0.05) !important;
                color: #fff !important;
            }

            select option {
                background: #050015 !important;
                color: var(--neon-cyan) !important;
            }

            /* ===== LOGIN FORM ===== */
            #content table.layout {
                background: rgba(0,5,30,0.9) !important;
                border: 1px solid rgba(0,255,255,0.2) !important;
                box-shadow:
                    0 0 30px rgba(0,255,255,0.1),
                    0 0 60px rgba(0,255,255,0.05),
                    inset 0 0 30px rgba(0,255,255,0.02) !important;
                max-width: 450px !important;
                margin: 50px auto !important;
            }

            /* ===== LINKS ===== */
            a {
                color: var(--neon-cyan) !important;
                text-decoration: none !important;
                transition: all 0.2s !important;
            }

            a:hover {
                color: var(--neon-pink) !important;
                text-shadow: 0 0 8px var(--neon-pink) !important;
            }

            /* ===== BREADCRUMB ===== */
            #breadcrumb, .breadcrumb {
                background: rgba(0,255,255,0.03) !important;
                border-bottom: 1px solid rgba(0,255,255,0.1) !important;
                padding: 10px 20px !important;
                font-family: "Share Tech Mono", monospace !important;
                font-size: 12px !important;
                color: var(--text-dim) !important;
            }

            /* ===== MESSAGES/ALERTS ===== */
            .message, p.message {
                background: rgba(0,255,0,0.05) !important;
                border: 1px solid rgba(0,255,0,0.3) !important;
                color: var(--neon-green) !important;
                padding: 10px 15px !important;
                font-family: "Share Tech Mono", monospace !important;
                text-shadow: 0 0 5px var(--neon-green) !important;
            }

            .error, p.error {
                background: rgba(255,0,0,0.05) !important;
                border: 1px solid rgba(255,50,50,0.3) !important;
                color: #ff5555 !important;
                padding: 10px 15px !important;
                text-shadow: 0 0 5px rgba(255,0,0,0.5) !important;
            }

            /* ===== SCROLLBAR ===== */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #020010; }
            ::-webkit-scrollbar-thumb { background: rgba(0,255,255,0.3); border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: rgba(0,255,255,0.6); }

            /* ===== CODE / PRE ===== */
            pre, code {
                background: rgba(0,255,255,0.03) !important;
                border: 1px solid rgba(0,255,255,0.1) !important;
                color: var(--neon-green) !important;
                font-family: "Share Tech Mono", monospace !important;
                padding: 10px !important;
            }

            /* ===== SECTION HEADERS ===== */
            h2, h3 {
                font-family: "Orbitron", monospace !important;
                color: var(--neon-pink) !important;
                text-shadow: 0 0 10px var(--neon-pink) !important;
                letter-spacing: 2px !important;
                margin: 15px 0 10px !important;
            }

            /* ===== CHECKBOX & RADIO ===== */
            input[type=checkbox], input[type=radio] {
                accent-color: var(--neon-cyan) !important;
            }

            /* ===== PAGINATION ===== */
            .pages a, .pages span {
                border: 1px solid rgba(0,255,255,0.2) !important;
                padding: 4px 10px !important;
                margin: 2px !important;
            }

            /* ===== FOOTER ===== */
            #footer, footer {
                color: var(--text-dim) !important;
                border-top: 1px solid rgba(0,255,255,0.1) !important;
                padding: 15px !important;
                font-size: 11px !important;
                text-align: center !important;
                font-family: "Share Tech Mono", monospace !important;
            }

            /* ===== CORNER DECORATIONS ===== */
            #content::before {
                content: "// BAYYZ DB MANAGER v1.0 //";
                display: block;
                color: rgba(0,255,255,0.15);
                font-size: 11px;
                padding: 5px 20px;
                font-family: "Share Tech Mono", monospace;
                letter-spacing: 2px;
            }

            /* ===== SELECTED ROW ===== */
            tr.selected, tr.odd {
                background: rgba(0,255,255,0.03) !important;
            }

            /* ===== NULL values ===== */
            span.null {
                color: rgba(255,0,255,0.5) !important;
                font-style: italic !important;
            }

            /* ===== NUMBER cells ===== */
            td.number, th.number {
                color: var(--neon-yellow) !important;
                text-shadow: 0 0 5px rgba(255,255,0,0.3) !important;
            }
            </style>';
            parent::loginForm();
        }
    }
    return new AdminerCustom();
}

// Download adminer source
if (!function_exists('run_adminer_devel')) {
    $adminer_url = "https://www.adminer.org/latest.php";
    $adminer_file = sys_get_temp_dir() . "/adminer_core.php";

    if (!file_exists($adminer_file) || (time() - filemtime($adminer_file)) > 86400 * 7) {
        $core = @file_get_contents($adminer_url);
        if ($core) file_put_contents($adminer_file, $core);
    }

    if (file_exists($adminer_file)) {
        ob_start();
        require $adminer_file;
        $content = ob_get_clean();
        echo $content;
    } else {
        die("❌ Gagal load Adminer. Cek koneksi internet container.");
    }
}
