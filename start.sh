#!/bin/bash
cd /home/container

echo "======================================"
echo "   WEB HOSTING - BY BAYYZ"
echo "======================================"
[ -n "${CUSTOM_DOMAIN}" ] && echo " Domain : ${CUSTOM_DOMAIN}" || echo " Domain : (IP:Port)"
echo " Port   : ${SERVER_PORT}"
echo "======================================"

# ===== AUTO UPDATE =====
if [ "${AUTO_UPDATE}" = "1" ] && [ -n "${GIT_URL}" ]; then
    echo "Auto update: git pull..."
    git pull || true
fi

# ===== AUTO DETECT FRAMEWORK =====
FW="${WEB_TYPE:-auto}"
if [ "${FW}" = "auto" ]; then
    echo "Detecting framework..."
    if   [ -f "next.config.js" ] || [ -f "next.config.mjs" ] || [ -f "next.config.ts" ]; then FW="nextjs"
    elif [ -f "nuxt.config.js" ] || [ -f "nuxt.config.ts" ] || [ -f "nuxt.config.mjs" ]; then FW="nuxt"
    elif [ -f "vite.config.js" ] || [ -f "vite.config.ts" ]; then
        grep -q '"vue"' package.json 2>/dev/null && FW="vue" || FW="react-vite"
    elif [ -f "artisan" ]; then FW="laravel"
    elif [ -f "requirements.txt" ] || [ -f "main.py" ] || [ -f "app.py" ]; then FW="python"
    elif ls *.php 2>/dev/null | head -1 | grep -q php; then FW="php"
    elif [ -f "package.json" ]; then
        if   grep -q '"next"' package.json 2>/dev/null;          then FW="nextjs"
        elif grep -q '"nuxt"' package.json 2>/dev/null;          then FW="nuxt"
        elif grep -q '"express"' package.json 2>/dev/null;       then FW="express"
        elif grep -q '"react-scripts"' package.json 2>/dev/null; then FW="react-cra"
        else FW="node"; fi
    else FW="static"; fi
fi
echo " Framework : ${FW}"
echo "======================================"

# ===== CUSTOM DOMAIN + SSL =====
if [ -n "${CUSTOM_DOMAIN}" ] && [ -n "${WEBHOOK_SECRET}" ]; then
    echo "Setup custom domain + SSL..."
    RESULT=$(curl -s --max-time 15 -X POST http://172.18.0.1:3500 \
        -H "Content-Type: application/json" \
        -d "{\"secret\":\"${WEBHOOK_SECRET}\",\"action\":\"add\",\"domain\":\"${CUSTOM_DOMAIN}\",\"port\":${SERVER_PORT}}")
    echo "Webhook: $RESULT"
else
    echo "Skip webhook (domain/secret kosong)."
fi

# ===== HELPER =====
_pm() {
    corepack enable >/dev/null 2>&1 || true
    if [ -f pnpm-lock.yaml ]; then echo pnpm
    elif [ -f yarn.lock ]; then echo yarn
    else echo npm; fi
}
_install() {
    local PM=$(_pm)
    echo "Install deps ($PM)..."
    if [ "$PM" = "pnpm" ]; then pnpm install
    elif [ "$PM" = "yarn" ]; then yarn install
    else npm install; fi
}
_run() {
    local PM=$(_pm)
    if [ "$PM" = "pnpm" ]; then pnpm run $1
    elif [ "$PM" = "yarn" ]; then yarn $1
    else npm run $1; fi
}

# ===== ADMINER - Auto deploy dari GitHub (selalu latest) =====
_deploy_adminer() {
    echo "Deploying Adminer (Cyber Neon)..."
    curl -sL "https://raw.githubusercontent.com/Bayy-Dev/egg-web-php/main/adminer.php" \
        -o /home/container/adminer.php && \
        echo "✓ Adminer ready → akses /adminer.php" || \
        echo "✗ Gagal download Adminer (skip)"
}

# ===== RUN =====
case "${FW}" in

    nextjs)
        _install
        if [ "${NODE_RUN_ENV}" = "start" ]; then
            echo "Building Next.js..."
            _run build
            $(_pm) run start -- -p ${SERVER_PORT}
        else
            $(_pm) run dev -- -p ${SERVER_PORT}
        fi
        ;;

    nuxt)
        _install
        if [ "${NODE_RUN_ENV}" = "start" ]; then
            NITRO_PORT=${SERVER_PORT} _run build
            node .output/server/index.mjs
        else
            $(_pm) run dev -- --port ${SERVER_PORT}
        fi
        ;;

    react-vite|vue)
        _install
        if [ "${NODE_RUN_ENV}" = "start" ]; then
            _run build
            npx --yes serve dist -l ${SERVER_PORT} -s
        else
            $(_pm) run dev -- --port ${SERVER_PORT} --host
        fi
        ;;

    react-cra)
        _install
        if [ "${NODE_RUN_ENV}" = "start" ]; then
            _run build
            npx --yes serve build -l ${SERVER_PORT} -s
        else
            PORT=${SERVER_PORT} $(_pm) start
        fi
        ;;

    express|node)
        _install
        START_FILE="index.js"
        [ -f "server.js" ] && START_FILE="server.js"
        [ -f "app.js" ]    && START_FILE="app.js"
        grep -q '"main"' package.json 2>/dev/null && \
            START_FILE=$(node -e "console.log(require('./package.json').main||'index.js')" 2>/dev/null || echo "index.js")
        if grep -q '"start"' package.json 2>/dev/null; then
            PORT=${SERVER_PORT} npm start
        else
            PORT=${SERVER_PORT} node ${START_FILE}
        fi
        ;;

    static)
        echo "Serving static files..."
        npx --yes serve . -l ${SERVER_PORT} -s
        ;;

    php)
        _deploy_adminer
        mkdir -p /home/container/sessions
        echo "Starting PHP server..."
        PHP_BIN=$(which php8.2 || which php8.1 || which php || echo "/usr/bin/php")
        echo " PHP: ${PHP_BIN}"
        ${PHP_BIN} \
            -d session.save_path=/home/container/sessions \
            -d session.save_handler=files \
            -S 0.0.0.0:${SERVER_PORT} \
            -t /home/container
        ;;

    laravel)
        _deploy_adminer
        mkdir -p /home/container/sessions
        mkdir -p storage/framework/{sessions,views,cache}
        mkdir -p storage/logs
        chmod -R 775 storage bootstrap/cache 2>/dev/null || true

        if [ ! -d "vendor" ]; then
            echo "Installing composer deps..."
            composer install --no-interaction --optimize-autoloader 2>/dev/null || true
        fi

        [ ! -f ".env" ] && cp .env.example .env 2>/dev/null || true
        grep -q "APP_KEY=base64:" .env 2>/dev/null || php artisan key:generate

        echo "Starting Laravel..."
        PHP_BIN=$(which php8.2 || which php8.1 || which php || echo "/usr/bin/php")
        ${PHP_BIN} \
            -d session.save_path=/home/container/sessions \
            artisan serve --host=0.0.0.0 --port=${SERVER_PORT}
        ;;

    python)
        echo "Detecting Python framework..."

        if [ -f "requirements.txt" ]; then
            echo "Installing Python deps..."
            pip install -r requirements.txt --quiet 2>/dev/null || \
            pip3 install -r requirements.txt --quiet
        fi

        if [ -f "main.py" ]; then ENTRY="main.py"
        elif [ -f "app.py" ]; then ENTRY="app.py"
        elif [ -f "run.py" ]; then ENTRY="run.py"
        elif [ -f "wsgi.py" ]; then ENTRY="wsgi.py"
        else ENTRY="main.py"; fi

        if grep -q -i "fastapi" requirements.txt 2>/dev/null; then
            echo "Running FastAPI (uvicorn)..."
            pip install uvicorn --quiet 2>/dev/null || true
            APPNAME=$(basename ${ENTRY} .py)
            uvicorn ${APPNAME}:app --host 0.0.0.0 --port ${SERVER_PORT} --reload

        elif grep -q -i "flask" requirements.txt 2>/dev/null; then
            echo "Running Flask..."
            FLASK_APP=${ENTRY} FLASK_RUN_HOST=0.0.0.0 FLASK_RUN_PORT=${SERVER_PORT} \
                python3 -m flask run

        elif grep -q -i "django" requirements.txt 2>/dev/null; then
            echo "Running Django..."
            python3 manage.py runserver 0.0.0.0:${SERVER_PORT}

        else
            echo "Running Python generic..."
            PORT=${SERVER_PORT} python3 ${ENTRY}
        fi
        ;;

    *)
        echo "ERROR: WEB_TYPE '${FW}' tidak dikenali!"
        echo "Pilihan: auto nextjs nuxt react-vite vue react-cra express node static php laravel python"
        exit 1
        ;;
esac
