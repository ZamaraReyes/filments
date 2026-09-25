# Filments

Aplicación web de películas construida con Laravel y Livewire. Consume la API de [TMDB](https://www.themoviedb.org/documentation/api) (y [OMDb](https://www.omdbapi.com/) para datos complementarios) para mostrar películas en cartelera, próximos estrenos, actores y géneros, y permite a los usuarios registrados guardar favoritos y recibir recomendaciones personalizadas.

## Características

- **Catálogo**: películas en cartelera (con scroll infinito), próximos estrenos, ficha de película (reparto, similares, tráiler), ficha de actor y listado por género.
- **Búsqueda en vivo** con Livewire (`SearchDropdown`).
- **Cuentas de usuario**: registro, login, verificación de email, recuperación de contraseña — con rate limiting en las rutas sensibles.
- **Favoritos**: películas, actores y géneros favoritos, y "no me interesa" (dislike) para afinar las recomendaciones.
- **Recomendaciones personalizadas**: `RecommendationService` combina los géneros y actores favoritos del usuario, filtrando lo ya visto/descartado.
- **Panel de administración**: gestión de usuarios (ver detalle, editar, resetear contraseña por email, eliminar) protegido por un middleware de admin.

## Stack

- PHP 8.2+, [Laravel 12](https://laravel.com/docs)
- [Livewire 3](https://livewire.laravel.com/) para los componentes interactivos
- Laravel Mix + Vue 2 + Bootstrap 5 para los assets front-end
- MySQL (u otra BD compatible con Eloquent)
- APIs externas: TMDB y OMDb, con caché de respuestas (`TmdbService`) para no golpear los límites de la API

## Requisitos

- PHP >= 8.2 con las extensiones habituales de Laravel
- Composer
- Node.js + npm (para compilar los assets)
- Una base de datos (MySQL recomendado; también puede usarse Sail/Docker, ver `docker-compose.yml`)
- Claves de API de [TMDB](https://www.themoviedb.org/settings/api) y (opcional) [OMDb](https://www.omdbapi.com/apikey.aspx)

## Instalación

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Edita `.env` y completa al menos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filments
DB_USERNAME=root
DB_PASSWORD=

TMDB_API_KEY=tu_api_key_de_tmdb
TMDB_TOKEN=tu_token_de_lectura_de_tmdb
OMDB_API_KEY=tu_api_key_de_omdb
```

Después:

```bash
php artisan migrate

npm run dev      # compila assets en desarrollo (o `npm run watch`)
php artisan serve
```

La aplicación quedará disponible en `http://localhost:8000`.

Para promover un usuario a administrador (no hay UI para el primer admin), desde `php artisan tinker`:

```php
\App\Models\User::where('email', 'tu@correo.com')->update(['is_admin' => true]);
```

## Tests

```bash
php artisan test
```

Cubre el flujo de favoritos, el guard de acceso al panel de admin, la protección contra mass-assignment de `is_admin` y `RecommendationService`.

## Build para producción

```bash
npm run prod
php artisan config:cache
php artisan route:cache
```

## 📝 License

Sin licencia definida todavía.

## 👤 Author

**Zamara Reyes** — [https://www.linkedin.com/in/zamarareyes/] · [https://zamarareyes.es/]
