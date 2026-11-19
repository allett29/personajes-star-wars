# Sistema de Gestión de Personajes Star Wars

Aplicación web para administrar personajes de Star Wars: CRUD completo e importación desde [SWAPI](https://swapi.py4e.com/).

## Información del Candidato

- **Nombre:** Bryan Alejandro Acosta Vergara
- **Email:** alejoav.2905@gmail.com
- **Teléfono:** 0995053499

## Requisitos del Sistema

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8+

## Instalación

1. Clonar repositorio

```bash
git clone https://github.com/allett29/personajes-star-wars.git
cd personajes-star-wars
```

2. Instalar dependencias

```bash
composer install
npm install
```

3. Configurar base de datos

```bash
cp .env.example .env
php artisan key:generate
```

Crear una base de datos vacía llamada `personajes_sw` y ajustar en `.env` las credenciales de MySQL (`DB_USERNAME`, `DB_PASSWORD`).

4. Ejecutar migraciones (y opcionalmente cargar datos de ejemplo)

```bash
php artisan migrate
php artisan db:seed
```

5. Compilar assets

```bash
npm run build
```

6. Iniciar servidor

```bash
php artisan serve
```

7. Acceder

http://localhost:8000/personajes

### Importar varios personajes desde la terminal (opcional)

```bash
php artisan personajes:importar-swapi 1 4 10
```

## Funcionalidades Implementadas

**Obligatorias:**

- CRUD completo de personajes (listar, crear, editar, eliminar con confirmación)
- Integración con SWAPI: importación por ID (1–83) con mapeo de campos al español y sin duplicados
- Validaciones backend (Form Requests) y frontend (errores por campo con `useForm`)
- Paginación de 10 registros por página
- Componentes Vue reutilizables
- Diseño responsive
- Estados de carga y mensajes de éxito/error

**Bonus:**

- Service Layer: `SwapiPersonajeService` concentra la consulta y el mapeo de SWAPI
- Soft Deletes: eliminar no borra el registro; reimportar un personaje eliminado lo restaura
- Comando Artisan para importar múltiples personajes
- Búsqueda en tiempo real por nombre
- Filtro por género
- Ordenamiento de tabla por columnas
- Animaciones con Tailwind (modales, notificaciones, spinners)
- Dark mode (se recuerda la preferencia)
- Seeder con datos de prueba

## Tecnologías

- Laravel 10.x
- Vue.js 3.x (`<script setup>`)
- Inertia.js
- Tailwind CSS
- MySQL
- Vite

## Notas

- No se usó Breeze ni Jetstream; Inertia se configuró manualmente.
- El servidor usa `inertia-laravel` 2.x y el cliente `@inertiajs/vue3` 3.x. Por eso `.env` incluye `INERTIA_USE_SCRIPT_ELEMENT_FOR_INITIAL_PAGE=true` (ya está en `.env.example`).
- Durante el desarrollo, en lugar del paso 5 se puede usar `npm run dev` junto a `php artisan serve`.

---

## Decisiones Técnicas

### 1. Organización del código backend

El controller (`PersonajeController`) solo coordina: recibe la petición, llama a quien corresponde y redirige con un mensaje. Las validaciones van en Form Requests (`StorePersonajeRequest`, `UpdatePersonajeRequest`, `ImportPersonajeSwapiRequest`) y la comunicación con SWAPI en un Service (`SwapiPersonajeService`). Así el mismo servicio lo reutilizan el controller y el comando Artisan sin duplicar código.

### 2. Componentes Vue

- `PersonajeForm`: formulario único para crear y editar; recibe el `form`, el texto del botón y la URL de cancelar.
- `FormField`: etiqueta, campo y mensaje de error con el mismo estilo en todo el formulario.
- `ModalBase`: modal genérico (cierra con Esc o clic afuera); sobre él se construyen `ImportModal` y `ConfirmDialog`.
- `ConfirmDialog`: confirmación reutilizable (eliminar, salir sin guardar).
- `Paginacion`, `ToastContainer`, `Spinner`, `PageHeader` y `OrigenBadge`: piezas de interfaz usadas en varias páginas.

Son reutilizables porque reciben todo por props y avisan con eventos, sin depender de una página concreta.

### 3. Manejo de errores de SWAPI

En `SwapiPersonajeService`:

- **Timeout / API caída:** la petición tiene `timeout(15)` y se captura `ConnectionException`.
- **ID inválido:** el Form Request limita el ID a 1–83 y, si la API responde 404, se muestra "no existe".
- **Otros errores HTTP:** se informa que SWAPI no respondió correctamente.
- **Datos "unknown":** altura y masa se guardan como `null`.
- **Duplicados:** antes de consultar se revisa si el `swapi_id` ya existe.

Todos los errores se muestran al usuario en español dentro del modal, sin romper la aplicación.

### 4. Validaciones

En el backend, con Form Requests, porque es la única validación que no se puede saltar y deja el controller limpio. Los mensajes están en español y Inertia los envía al formulario, donde `useForm` muestra cada error debajo de su campo. En el frontend solo hay ayudas básicas (campos `required`, tipo número) para mejorar la experiencia.

### 5. Mayor dificultad

La pantalla en blanco al iniciar Inertia: el cliente (v3) y el servidor (v2) envían la página inicial de forma distinta. Lo resolví leyendo el error de consola y la documentación, y activando `INERTIA_USE_SCRIPT_ELEMENT_FOR_INITIAL_PAGE`. Otro punto fue que, con Soft Deletes, reimportar un personaje eliminado chocaba con el `swapi_id` único; lo resolví buscando con `withTrashed()` y restaurándolo.

### 6. Uso de Inteligencia Artificial

Sí, usé Claude (en Cursor). Principalmente para el frontend (diseño de la interfaz, modales, notificaciones, dark mode) y para limpiar y simplificar la lógica. Validé el código probando cada flujo en el navegador (crear, editar, eliminar, importar, errores de validación y de SWAPI), ejecutando las migraciones y el comando Artisan, compilando con `npm run build` y revisando cada archivo para entenderlo y poder modificarlo.

### 7. Mejoras futuras

- Pruebas automatizadas (Feature tests del CRUD y de la importación con `Http::fake`).
- Papelera para ver y restaurar personajes eliminados desde la interfaz.
- Obtener el nombre real del planeta de origen desde SWAPI.
- Docker Compose y GitHub Actions.

### 8. Autoevaluación

**8/10.** Cumple todo lo obligatorio y varios bonus, con código ordenado y fácil de modificar. Lo mejor: la separación en Service y Form Requests, y los componentes reutilizables. Le falta cubrirlo con pruebas automatizadas.

### 9. Tiempo invertido

Aproximadamente **4 horas**.
