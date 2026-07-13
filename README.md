# TOUR UP CMS

Sistema de gestión (CMS) para agencias de turismo. Laravel + MySQL.

## Requisitos

- PHP 8.0+
- MySQL / MariaDB 10.4+
- Composer
- Node.js (opcional, para assets)

## Instalación

```bash
composer install
cp .env.example .env
# Configurar DB en .env
php artisan key:generate
php artisan storage:link
```

Abrir `http://localhost/web-turismo/public` — el asistente de instalación guiará el resto.

## Estructura

- `agent.md` — Especificación funcional completa
- `plan-implementacion.md` — Plan de implementación fase a fase
- `project-map.md` — Estado real del sistema
- `dashboard-design/` — Prototipos de referencia del panel admin
- `tema-visual-base/` — Maquetación de referencia del tema Earth

## Stack

- Laravel 9.x
- Tailwind CSS (CDN)
- Font Awesome 6.5 (CDN)
- JavaScript vanilla
- MySQL / MariaDB
