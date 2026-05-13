#!/bin/bash
set -e

WORKDIR=/var/www/html
cd "$WORKDIR"

echo "🔍 Verificando si Laravel está inicializado..."

# Bootstrap Laravel si composer.json no existe
if [ ! -f "$WORKDIR/composer.json" ]; then
    echo "🚀 Creando proyecto Laravel 12 desde cero..."
    composer create-project laravel/laravel:^12.0 /tmp/laravel-bootstrap --no-interaction --quiet

    echo "📋 Copiando archivos base de Laravel..."
    rsync -a --exclude='Dockerfile' --exclude='docker-entrypoint.sh' /tmp/laravel-bootstrap/ "$WORKDIR/"
    rm -rf /tmp/laravel-bootstrap

    echo "📦 Instalando Laravel Breeze + Inertia + Vue..."
    composer require laravel/breeze --no-interaction --quiet
    php artisan breeze:install vue --no-interaction --quiet
fi

# Configurar .env si no existe
if [ ! -f "$WORKDIR/.env" ]; then
    echo "⚙️  Configurando .env..."
    cp "$WORKDIR/.env.example" "$WORKDIR/.env" 2>/dev/null || true
fi

echo "🏗️  Creando directorios de storage..."
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

echo "🔒 Ajustando permisos..."
chown -R www-data:www-data "$WORKDIR"
chmod -R 775 storage bootstrap/cache

echo "📦 Instalando dependencias PHP (sin scripts)..."
composer install --no-interaction --no-scripts --quiet

echo "🔑 Generando APP_KEY..."
# Si el entorno ya tiene APP_KEY vacía (inyectada por Docker), generamos la key,
# la escribimos en .env y la exportamos explícitamente al proceso actual
# para que PHP-FPM la herede (dotenv no sobreescribe vars ya definidas en el entorno).
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
    GENERATED_KEY=$(grep "^APP_KEY=" "$WORKDIR/.env" | cut -d'=' -f2-)
    export APP_KEY="$GENERATED_KEY"
    echo "   APP_KEY generada y exportada."
else
    echo "   APP_KEY ya está configurada."
fi

echo "📦 Registrando paquetes Laravel..."
composer run-script post-autoload-dump --no-interaction 2>/dev/null || true

echo "📦 Instalando dependencias JS..."
if [ ! -d "node_modules" ]; then
    npm install --silent
fi

echo "🔨 Compilando assets..."
npm run build 2>&1 | tail -5

echo "⏳ Esperando base de datos..."
until php artisan migrate:status > /dev/null 2>&1; do
    echo "   ...base de datos no disponible, reintentando en 3s"
    sleep 3
done

echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force --no-interaction

echo "🌱 Ejecutando seeders..."
php artisan db:seed --force --no-interaction 2>/dev/null || true

echo "⚡ Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "✅ Laravel listo. Iniciando PHP-FPM..."
exec "$@"
