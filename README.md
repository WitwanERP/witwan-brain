# Witwan Brain

Sistema de administración de licencias y secciones desarrollado con Laravel 12 y Vue 3.

## Stack Tecnológico

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Vue 3 + Inertia.js
- **Estilos:** Tailwind CSS
- **Build:** Vite
- **Base de datos:** SQLite (configurable)

## Requisitos

- PHP 8.2+
- Composer
- Node.js 18+
- npm

## Instalación

```bash
# Clonar el repositorio
git clone <repo-url>
cd witwan-brain

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Crear base de datos y ejecutar migraciones
touch database/database.sqlite
php artisan migrate --seed
```

## Desarrollo

```bash
# Iniciar todos los servicios (servidor, queue, logs, vite)
composer run dev
```

Esto ejecuta concurrentemente:
- Servidor PHP en `http://localhost:8000`
- Worker de colas
- Logs en tiempo real
- Vite dev server

## Funcionalidades

- **Gestión de Licencias:** Administración de licencias con información de facturación, claves y configuración por país
- **Gestión de Secciones:** Organización jerárquica de módulos/permisos asignables a licencias
- **Sistemas:** Soporte para múltiples sistemas (Receptivo, Mayorista, Minorista, Consolidador, Administración, Configuración, Nacional)
- **Autenticación:** Sistema de login integrado

## Estructura del Proyecto

```
app/
├── Http/Controllers/    # Controladores
├── Models/              # Modelos Eloquent
└── Services/            # Servicios de negocio

resources/js/
├── Pages/               # Páginas Vue (Inertia)
├── Components/          # Componentes reutilizables
└── Layouts/             # Layouts de la aplicación
```

## Scripts Disponibles

```bash
composer run dev      # Desarrollo (todos los servicios)
npm run build         # Build de producción
npm run dev           # Solo Vite dev server
```
