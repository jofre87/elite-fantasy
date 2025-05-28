# Proyecto Laravel

Este proyecto está construido con Laravel y requiere PHP, Composer, Node.js y un servidor de base de datos compatible (MySQL, MariaDB, etc.). A continuación se describen los pasos para configurar y poner en marcha la aplicación.

---

## 1. Requisitos previos

Antes de comenzar, asegúrate de tener instalado en tu sistema:

* **PHP 8.1** o superior
* **Composer** (gestor de dependencias de PHP)
* **MySQL** o **MariaDB**, o cualquier otro SGBD compatible
* **Node.js** y **npm** (opcional, necesario si usas recursos frontend con Vite o Laravel Mix)
* **Servidor web**: Apache, Nginx o el servidor integrado de Laravel (`php artisan serve`)

---

## 2. Instalación de dependencias

1. Clona el repositorio e sitúate en su carpeta raíz:

   ```bash
   git clone <url-del-repositorio>
   cd <nombre-del-proyecto>
   ```

2. Instala las dependencias de PHP con Composer:

   ```bash
   composer install
   ```

3. (Opcional) Si el proyecto incluye recursos frontend:

   ```bash
   npm install
   npm run build
   ```

4. Añade bibliotecas adicionales necesarias para tests o scraping:

   ```bash
   composer require symfony/browser-kit symfony/http-client symfony/dom-crawler
   ```

---

## 3. Configuración del entorno

1. Copia el archivo de ejemplo de variables de entorno:

   ```bash
   cp .env.example .env
   ```

2. Edita `.env` y ajusta las siguientes variables según tu entorno:

   ```dotenv
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_de_tu_bd
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

3. Genera la clave de la aplicación:

   ```bash
   php artisan key:generate
   ```

---

## 4. Migraciones y seeders

1. Ejecuta las migraciones para crear las tablas en la base de datos:

   ```bash
   php artisan migrate
   ```

2. Si necesitas resetear la base de datos y volver a migrar desde cero:

   ```bash
   php artisan migrate:fresh
   ```

3. (Opcional) Rellena la base de datos con datos de prueba usando seeders:

   ```bash
   php artisan db:seed
   ```

---

## 5. Iniciar el servidor de desarrollo

Arranca el servidor integrado de Laravel:

```bash
php artisan serve
```

Accede a la aplicación en tu navegador en:

```
http://localhost:8000
```

---

## 6. Resumen de comandos

```bash
# Instalar dependencias PHP
a composer install

# (Opcional) Instalar y compilar frontend
npm install && npm run build

# Instalar librerías adicionales
composer require symfony/browser-kit symfony/http-client symfony/dom-crawler

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migraciones
a php artisan migrate
# o para resetear: php artisan migrate:fresh

# Seeders (opcional)
php artisan db:seed

# Levantar servidor
php artisan serve
```
