# Sistema de Gestión de Personajes Star Wars

## Información del Candidato
Nombre: Eric Antonio Escobar Colin
Email: e888953@gmail.com
Teléfono: 5510914253

## Requisitos del Sistema
- PHP 8.3+ (Laravel 13 lo requiere)
- Composer
- Node.js 20+
- MySQL 8+

O bien, solo **Docker Desktop** si se usa la instalación con Docker.

## Instalación

### Opción A: con Docker (recomendada)

1. Clonar repositorio
```
git clone https://github.com/EricEscobar527/prueba_mau.git
cd prueba_mau
```

2. Crear el archivo de entorno (Docker Compose toma de aquí los datos de MySQL)
```
cp .env.example .env
```

3. Levantar los contenedores
```
docker compose up --build -d
```
Al iniciar, el contenedor `app` ejecuta automáticamente `composer install`, `npm install`, `npm run build`, `php artisan key:generate` y `php artisan migrate` (ver `docker/start.sh`). La primera vez tarda unos minutos.

4. Acceder
http://localhost:8000/personajes

MySQL queda expuesto en el puerto `3307` de la máquina (usuario `root`, contraseña `root`).

### Opción B: instalación local

1. Clonar repositorio
```
git clone https://github.com/EricEscobar527/prueba_mau.git
cd prueba_mau
```

2. Instalar dependencias
```
composer install
npm install
```

3. Configurar base de datos
```
cp .env.example .env
```
Editar `.env` con las credenciales de MySQL. **Cambiar `DB_HOST=mysql` por `DB_HOST=127.0.0.1`** (el valor `mysql` es el nombre del servicio en Docker).
```
php artisan key:generate
```

4. Ejecutar migraciones
```
php artisan migrate
```

5. Compilar assets
```
npm run build
```

6. Iniciar servidor
```
php artisan serve
```

7. Acceder
http://localhost:8000/personajes

## Funcionalidades Implementadas

Obligatorias:
- CRUD completo de personajes
- Integración con SWAPI
- Validaciones backend y frontend
- Paginación
- Componentes Vue reutilizables
- Diseño responsive

Bonus:
- Service Layer para la integración con SWAPI (`app/Services/SwapiService.php`)
- Docker Compose funcional

## Tecnologías
- Laravel 13.x
- Vue.js 3.x
- Inertia.js 3.x
- Tailwind CSS 4.x
- MySQL 8
- Vite

## Notas
- **Mapeo de género:** SWAPI tiene valores además de `male`, `female` y `n/a` (por ejemplo `hermaphrodite` en Jabba o `none`). `male` → `masculino`, `female` → `femenino` y cualquier otro valor → `n/a`.
- **Altura y masa:** SWAPI regresa estos campos como texto. Se quitan las comas (`"1,358"` → `1358`) y los valores no numéricos como `"unknown"` se guardan como `null`.
- **Planeta de origen:** SWAPI regresa `homeworld` como URL, así que se hace una segunda consulta para obtener el nombre del planeta. Si esa consulta falla, el personaje se importa igual con `planeta_origen` en `null`.
- **ID 17:** aunque el rango es 1–83, SWAPI no tiene personaje con ID 17 (responde 404). La aplicación muestra el mensaje "No existe un personaje con el ID 17 en SWAPI."
- **Personajes importados con valores fuera de rango:** las validaciones (masa máx. 500) aplican a la captura manual. Jabba (ID 16) se importa con masa 1358, así que para editarlo hay que ajustar la masa a un valor válido.

---

## Preguntas Técnicas

### 1. Organización del código backend
- **Controller (`PersonajeController`):** solo coordina: recibe la petición, llama al modelo o al servicio y regresa la vista de Inertia o una redirección con mensaje.
- **Form Requests (`PersonajeRequest`, `ImportarPersonajeRequest`):** contienen las reglas de validación y los mensajes en español, para que el controlador no tenga lógica de validación.
- **Service (`SwapiService`):** toda la comunicación con SWAPI (consulta HTTP, manejo de errores y transformación de campos de inglés a español) está separada del controlador. Si mañana cambia la API, solo se modifica este archivo.
- **Excepción propia (`SwapiException`):** el servicio lanza esta excepción con un mensaje claro y el controlador la convierte en un error de formulario.
- **Modelo (`Personaje`):** `$fillable`, `$casts` y la constante `GENEROS` que se usa en la validación.

### 2. Componentes Vue
- **`PersonajeForm.vue`:** el formulario completo con sus validaciones y errores. Lo usan las páginas Crear y Editar; solo cambian las props (`personaje`, `url`, `metodo`, `textoBoton`).
- **`ImportarModal.vue`:** el modal de importación con su propio `useForm`, estado de carga y errores. Se controla con la prop `abierto` y el evento `cerrar`, así que se puede poner en cualquier página.
- **`Paginacion.vue`:** recibe cualquier paginador de Laravel y dibuja los enlaces; sirve para cualquier listado.
- **`MensajeFlash.vue`:** muestra los mensajes de éxito/error compartidos desde el middleware de Inertia.
- **`Spinner.vue`:** indicador de carga que se usa en botones del formulario, del modal y de eliminar.

### 3. Manejo de errores de SWAPI
En `SwapiService`:
- **Timeout / API caída:** la petición tiene `timeout(10)`. Si no hay conexión o se agota el tiempo, Laravel lanza `ConnectionException`, que se convierte en `SwapiException` con el mensaje "No se pudo conectar con SWAPI".
- **ID inexistente (404):** se detecta con `$respuesta->notFound()` y se muestra "No existe un personaje con el ID X en SWAPI".
- **Otros errores (500, respuesta inválida):** si la respuesta falla o no trae `name`, se muestra "SWAPI respondió con un error".
- **Antes de consultar la API**, `ImportarPersonajeRequest` valida que el ID sea entero, esté entre 1 y 83 y que no se haya importado antes (`unique` sobre `swapi_id`), así no se hacen peticiones innecesarias. Además, `swapi_id` tiene índice `unique` en la base de datos.

En el controlador, la `SwapiException` se convierte en error del campo `swapi_id` (`back()->withErrors(...)`), así el mensaje aparece dentro del modal.

### 4. Validaciones
- **Backend (la fuente de verdad):** en Form Requests, porque mantienen el controlador limpio, se reutiliza la misma clase para crear y actualizar, y Laravel regresa los errores automáticamente a Inertia.
- **Frontend (básica):** atributos HTML5 (`required`, `minlength`, `maxlength`, `min`, `max`) para dar respuesta inmediata sin ir al servidor. No se duplica la lógica en JavaScript; los errores del backend se muestran debajo de cada campo con `form.errors`.

### 5. Mayor dificultad
Lo más difícil fue la integración con SWAPI, porque los datos no siempre vienen como se espera:
- La masa de Jabba viene como `"1,358"` (con coma) y varios personajes tienen `"unknown"` en altura o masa. Lo resolví quitando las comas y guardando `null` cuando el valor no es numérico.
- Hay géneros que no están en el mapeo (`hermaphrodite`, `none`), así que todo lo que no sea `male` o `female` se guarda como `n/a`.
- El ID 17 está dentro del rango 1–83 pero no existe en SWAPI (404), por lo que tuve que manejarlo con un mensaje claro.

También tuve un problema con el entorno: en Docker sobre Windows cada página tardaba de 8 a 15 segundos porque Laravel lee miles de archivos de `vendor/` desde el disco de Windows. Lo resolví guardando `vendor` y `node_modules` en volúmenes de Docker, y bajó a milisegundos.

### 6. Uso de Inteligencia Artificial
Sí. Usé **Claude (Claude Code)** como asistente durante todo el proyecto: configuración de Docker, instalación de Inertia/Vue, y generación del backend (migración, controlador, Form Requests, servicio de SWAPI) y del frontend (páginas y componentes).

Cómo validé el código:
- Revisé el código archivo por archivo para entender qué hace cada parte.
- Probé manualmente en el navegador el flujo completo: crear, editar, eliminar, paginación e importación desde SWAPI.
- Durante el desarrollo, Claude ejecutó pruebas de la importación contra SWAPI real con casos especiales (Luke, Jabba con masa `"1,358"`, C-3PO con género `n/a`, el ID 17 que da 404), simuló un timeout y verificó las validaciones con datos inválidos. Revisé los resultados de esas pruebas.

### 7. Mejoras futuras
- Pruebas automatizadas (Feature tests del CRUD y de la importación con `Http::fake()`).
- Búsqueda por nombre, filtro por género y ordenamiento de la tabla.
- Comando Artisan para importar varios personajes a la vez.
- Soft deletes.
- Cachear la consulta del planeta para no repetirla.

### 8. Autoevaluación
8/10. Cumple todo lo obligatorio y funciona sin errores, pero no agregué la mayoría de los bonus (búsqueda, filtros, pruebas automatizadas) y me apoyé bastante en IA.

Lo mejor de la implementación:
- El manejo de errores de SWAPI: cada caso (timeout, API caída, 404, ID duplicado o fuera de rango) muestra un mensaje claro dentro del modal.
- La separación de responsabilidades: el controlador es delgado, las validaciones están en Form Requests y la integración con SWAPI en un servicio.
- `PersonajeForm.vue` se reutiliza para crear y editar cambiando solo sus props.
- Se levanta completo con un solo `docker compose up --build -d`.

### 9. Tiempo invertido
Aproximadamente 3-4 horas, incluyendo la configuración de Docker, el desarrollo y la revisión del código.
