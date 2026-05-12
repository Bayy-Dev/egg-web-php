#!/bin/bash
# ============================================
#   WEB HOSTING PHP START SCRIPT - BY BAYYZ
# ============================================

# DB Credentials - diisi user dari tab Databases panel
# Kosong = user belum setup database
export DB_HOST="${DB_HOST:-}"
export DB_PORT="${DB_PORT:-3306}"
export DB_NAME="${DB_NAME:-}"
export DB_USER="${DB_USER:-}"
export DB_PASS="${DB_PASS:-}"
WEBHOOK_SECRET="__WEBHOOK_SECRET__"

echo "======================================"
echo " BAYYZ Web Hosting - PHP Edition"
echo "======================================"
if [ -n "${DB_HOST}" ]; then
    echo " DB Host : ${DB_HOST}:${DB_PORT}"
    echo " DB Name : ${DB_NAME}"
    echo " DB User : ${DB_USER}"
else
    echo " DB      : Belum dikonfigurasi"
    echo " Buka folder upload_file_db/BACA_INI.txt"
fi
echo "======================================"

cd /home/container

# ============================================
#   AUTO CREATE FOLDER upload_file_db
# ============================================
mkdir -p /home/container/upload_file_db

# Selalu overwrite README biar selalu ada dan up to date
cat > /home/container/upload_file_db/BACA_INI.txt <<'READMEEOF'
========================================================
  FOLDER IMPORT / EXPORT DATABASE - BAYYZ HOSTING
========================================================

[ LANGKAH 1 - BUAT DATABASE ]
1. Buka tab "Databases" di panel server kamu
2. Klik tombol "NEW DATABASE"
3. Isi nama database bebas, klik Create
4. Klik icon mata (view) untuk lihat credentials:
   - ENDPOINT  : host:port
   - USERNAME  : username DB kamu
   - PASSWORD  : password DB kamu
   - DB NAME   : nama database (setelah underscore kedua)

[ LANGKAH 2 - KONEKSI KE PROJECT ]
Masukkan credentials ke file koneksi project kamu:

  Laravel (.env):
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=username
    DB_PASSWORD=password

  CodeIgniter (app/Config/Database.php):
    'hostname' => '127.0.0.1',
    'database' => 'nama_database',
    'username' => 'username',
    'password' => 'password',

  Plain PHP (db.php / koneksi.php):
    $host = '127.0.0.1';
    $dbname = 'nama_database';
    $user = 'username';
    $pass = 'password';

[ CARA IMPORT DATABASE ]
1. Upload file .sql kamu ke folder ini
   Nama file harus: import.sql
2. Restart server kamu
3. Database otomatis ter-import
4. File akan berubah nama jadi import.sql.done

[ CARA EXPORT DATABASE ]
1. Buat file kosong bernama: DO_EXPORT
   di folder ini (isi bebas, boleh kosong)
2. Restart server kamu
3. Tunggu proses export selesai (lihat console)
4. File hasil export ada di sini:
   export_TANGGAL_JAM.sql
5. Download file tersebut via File Manager

========================================================
BAYYZ HOSTING - Web Hosting Panel
========================================================
READMEEOF

echo ">>> Folder upload_file_db siap."

# ============================================
#   AUTO UPDATE (git pull)
# ============================================
if [ "${AUTO_UPDATE}" = "1" ] && [ -d .git ]; then
    echo ">>> Auto update: git pull..."
    git pull 2>/dev/null || true
fi

# ============================================
#   DETEKSI FRAMEWORK
# ============================================
detect_framework() {
    if [ "${WEB_TYPE}" != "auto" ]; then
        echo "${WEB_TYPE}"
        return
    fi

    # Laravel
    if [ -f artisan ] && [ -f composer.json ]; then
        grep -q "laravel/framework" composer.json 2>/dev/null && echo "laravel" && return
    fi

    # CodeIgniter 4
    if [ -f spark ] && [ -f composer.json ]; then
        grep -q "codeigniter4/framework" composer.json 2>/dev/null && echo "codeigniter" && return
    fi

    # Symfony
    if [ -f bin/console ] && [ -f composer.json ]; then
        grep -q "symfony/framework-bundle" composer.json 2>/dev/null && echo "symfony" && return
    fi

    # Plain PHP
    if [ -f index.php ]; then
        echo "php"
        return
    fi

    echo "php"
}

FRAMEWORK=$(detect_framework)
echo ">>> Detected framework: ${FRAMEWORK}"

# ============================================
#   CEK & VALIDASI KONEKSI DATABASE
# ============================================
check_db_connection() {
    if [ -z "${DB_HOST}" ] || [ -z "${DB_NAME}" ] || [ -z "${DB_USER}" ]; then
        echo ""
        echo "======================================"
        echo " [!] DATABASE BELUM DIKONFIGURASI"
        echo "======================================"
        echo " Buka folder upload_file_db/BACA_INI.txt"
        echo " untuk panduan setup database."
        echo "======================================"
        echo ""
        return 1
    fi

    echo ">>> Mengecek koneksi database..."
    mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" -e "SELECT 1;" > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo ""
        echo "======================================"
        echo " [✓] SETUP DATABASE KONEKSI DONE"
        echo "======================================"
        echo " Host : ${DB_HOST}:${DB_PORT}"
        echo " DB   : ${DB_NAME}"
        echo " User : ${DB_USER}"
        echo "======================================"
        echo ""
        return 0
    else
        echo ""
        echo "======================================"
        echo " [✗] DATABASE KONEKSI GAGAL!"
        echo "======================================"
        echo " Pastikan credentials DB sudah benar."
        echo " Cek tab Databases di panel kamu."
        echo "======================================"
        echo ""
        return 1
    fi
}

# ============================================
#   INJECT DB KE .env
# ============================================
inject_env() {
    if [ ! -f .env ] && [ -f .env.example ]; then
        cp .env.example .env
    fi

    if [ -f .env ] && [ -n "${DB_HOST}" ]; then
        for VAR in DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
            grep -q "^${VAR}=" .env || echo "${VAR}=" >> .env
        done
        sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|g" .env
        sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|g" .env
        sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|g" .env
        sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|g" .env
        sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|g" .env
    fi
}

# ============================================
#   IMPORT SQL
# ============================================
import_sql() {
    IMPORT_FILE=""
    [ -f /home/container/upload_file_db/import.sql ] && IMPORT_FILE="/home/container/upload_file_db/import.sql"
    [ -f /home/container/import.sql ] && IMPORT_FILE="/home/container/import.sql"

    if [ -n "${IMPORT_FILE}" ]; then
        FNAME=$(basename "${IMPORT_FILE}")
        echo ""
        echo "======================================"
        echo " IMPORT DATABASE ${FNAME} ..."
        echo "======================================"
        mysql -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < "${IMPORT_FILE}"
        if [ $? -eq 0 ]; then
            mv "${IMPORT_FILE}" "${IMPORT_FILE}.done"
            echo ""
            echo "======================================"
            echo " [✓] IMPORT DATABASE ${FNAME} DONE"
            echo "======================================"
            echo ""
        else
            echo ""
            echo "======================================"
            echo " [✗] IMPORT DATABASE ${FNAME} GAGAL!"
            echo " Cek format file SQL kamu."
            echo "======================================"
            echo ""
        fi
    fi
}

# ============================================
#   EXPORT SQL
#   Trigger: ada file DO_EXPORT di upload_file_db
# ============================================
export_sql() {
    if [ -f /home/container/upload_file_db/DO_EXPORT ]; then
        TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
        EXPORT_FILE="/home/container/upload_file_db/export_${TIMESTAMP}.sql"
        echo ">>> File DO_EXPORT terdeteksi, memulai export database..."
        mysqldump -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" > "${EXPORT_FILE}"
        if [ $? -eq 0 ]; then
            rm -f /home/container/upload_file_db/DO_EXPORT
            echo ">>> Export berhasil! File: upload_file_db/export_${TIMESTAMP}.sql"
            echo ">>> Download file tersebut via File Manager panel kamu."
        else
            echo ">>> [ERROR] Export gagal!"
        fi
    fi
}

# ============================================
#   JALANKAN BERDASARKAN FRAMEWORK
# ============================================

case "${FRAMEWORK}" in

    laravel)
        echo ">>> Starting Laravel..."
        inject_env
        check_db_connection

        # Install composer deps kalau belum ada vendor
        if [ ! -d vendor ]; then
            echo ">>> Installing composer dependencies..."
            composer install --no-interaction --prefer-dist --optimize-autoloader
        fi

        # Generate app key kalau belum ada
        if [ -f .env ]; then
            grep -q "APP_KEY=base64:" .env || php artisan key:generate --force
        fi

        # Export dulu, baru import
        export_sql
        import_sql

        # Run migrations kalau ada
        if [ "${RUN_MIGRATE}" = "1" ]; then
            echo ">>> Running migrations..."
            php artisan migrate --force 2>/dev/null || true
        fi

        # Optimize
        php artisan config:cache 2>/dev/null || true
        php artisan route:cache 2>/dev/null || true

        echo ">>> Laravel ready! Starting PHP server..."
        php artisan serve --host=0.0.0.0 --port="${SERVER_PORT:-8080}"
        ;;

    codeigniter)
        echo ">>> Starting CodeIgniter 4..."
        inject_env
        check_db_connection

        if [ ! -d vendor ]; then
            composer install --no-interaction
        fi

        export_sql
        import_sql

        # CI4 pakai spark serve
        if [ -f spark ]; then
            php spark serve --host=0.0.0.0 --port="${SERVER_PORT:-8080}"
        else
            php -S 0.0.0.0:"${SERVER_PORT:-8080}" -t public/
        fi
        ;;

    symfony)
        echo ">>> Starting Symfony..."
        inject_env
        check_db_connection

        if [ ! -d vendor ]; then
            composer install --no-interaction
        fi

        export_sql
        import_sql

        php -S 0.0.0.0:"${SERVER_PORT:-8080}" -t public/
        ;;

    php|*)
        echo ">>> Starting Plain PHP..."
        inject_env
        check_db_connection
        export_sql
        import_sql

        if [ ! -d vendor ] && [ -f composer.json ]; then
            composer install --no-interaction 2>/dev/null || true
        fi

        # Cari root folder
        ROOT="."
        [ -d public ] && ROOT="public"
        [ -d public_html ] && ROOT="public_html"
        [ -d www ] && ROOT="www"

        echo ">>> Serving dari folder: ${ROOT}"
        php -S 0.0.0.0:"${SERVER_PORT:-8080}" -t "${ROOT}/"
        ;;
esac
