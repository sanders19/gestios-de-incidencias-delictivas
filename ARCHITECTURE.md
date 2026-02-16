# Sistema Policial de Gestión de Incidencias

**Sistema integral de captura, seguimiento y análisis de incidencias delictivas con geolocalización en tiempo real, diseñado para autoridades policiales con gestión centralizada por roles (Mesa, Jefatura, Seincri).**

El sistema permite registrar denuncias de incidencias delictivas con información detallada del hecho, personas involucradas y localización geográfica. Implementa un flujo de trabajo con estados de progresión (Pendiente → En Atencion → Resuelto) y diferenciación de permisos según rol de usuario. Integra reportería, búsqueda avanzada y carga de evidencias digitales.

---

## 1. Contexto del Problema

El sistema policial requiere un medio digital para:
- **Registrar incidencias**: Capturar denuncias con datos de denunciante, agredido, agresor, clasificación de delito y localización exacta.
- **Gestionar casos**: Permitir seguimiento de estados (Pendiente, En Atención, Resuelto) con asignación a personal especializado.
- **Análisis territorial**: Localizar incidencias geográficamente para identificar zonas de mayor criminalidad.
- **Control de acceso**: Diferenciar permisos por rol (Mesa recepcionista, Jefatura, Seincri) para garantizar seguridad y confidencialidad.
- **Reportería**: Generar informes en PDF con datos agregados por período, zona, tipo de delito.
- **Evidencia digital**: Almacenar documentos y multimedia asociados a cada caso.

**Antes del sistema**: Registros en papel, búsquedas manuales, sin trazabilidad geográfica, duplicación de datos.

---

## 2. Tecnologías Utilizadas

### Backend
- **PHP 7.4+**: Lenguaje de servidor, orientado a objetos con clases models/controllers
- **PDO (PHP Data Objects)**: Acceso a base de datos con prepared statements contra inyección SQL

### Frontend
- **HTML5**: Estructura semántica de vistas
- **CSS3**: Responsive design (mobile-first), 8 hojas de estilo modularizadas
- **JavaScript (Vanilla)**: Interactividad, AJAX, integración con mapas, validación cliente

### Base de Datos
- **MySQL 5.7+**: Almacenamiento relacional, utf8mb4 para caracteres especiales
- **Charset UTF-8**: Soporte para español y caracteres acentuados

### Herramientas y Librerías
- **XAMPP**: Stack de desarrollo (Apache, MySQL, PHP)
- **FPDF**: Generación de reportes y documentos en PDF
- **Google Maps API**: Geolocalización e integración cartográfica
- **Git**: Control de versiones

---

## 3. Arquitectura del Sistema

### 3.1 Patrón Arquitectónico: MVC Robusto con Middleware

El sistema implementa **Model-View-Controller** tradicional reforzado con capas de middleware para autenticación y autorización:

```
REQUEST → Router ↓
          ↓ (Middleware de Autenticación)
          ↓ (Middleware de Autorización por Rol)
          Controller (Lógica de negocio)
          ↓
          Model (Acceso a datos + transacciones)
          ↓
          Database (PDO/MySQL)
          
          Controller ↓ Renderiza View
          ↓
          Response (HTML + CSS + JS)
```

**Flujo concreto**: Usuario hace GET `/mesa/registro` → `AuthMiddleware::check()` verifica sesión → `RoleMiddleware::check()` valida permisos del rol Mesa → `MesaRegistroController@index()` instancia modelos (Incidencia, Usuario, etc.) → Retorna vista HTML.

### 3.2 Estructura del Sistema

```
├── config/              # Configuración centralizada
│   ├── config.php      # Variables de entorno, rutas, ajustes
│   ├── constants.php   # Constantes globales
│   └── database.php    # Credenciales y conexión MySQL
│
├── routes/
│   └── web.php         # Enrutador manual con diccionario de rutas y regex
│                       # Patrón: "METHOD /path/(param)" => closure o "Controller@method"
│
├── middleware/         # Capa de prevalidación
│   ├── AuthMiddleware.php          # Verifica sesión activa
│   ├── RoleMiddleware.php          # Verifica rol vs acción permitida
│   └── ForcePasswordChangeMiddleware.php  # Obliga cambio de contraseña en primer login
│
├── models/             # Lógica de datos (Singleton Database)
│   ├── Database.php    # Conexión PDO con patrón singleton
│   ├── Usuario.php     # CRUD usuarios, autenticación, cambio contraseña
│   ├── Incidencia.php  # CRUD incidencias con geolocalización
│   ├── Asignacion.php  # CRUD asignaciones de casos
│   ├── Evidencia.php   # Gestión de archivos adjuntos
│   └── Reporte.php     # Consultas agregadas para estadísticas
│
├── controllers/        # Lógica de presentación y orquestación (organizados por rol)
│   ├── AuthController.php          # Login/logout/cambio contraseña
│   ├── jefe/                       # Acciones de Jefatura
│   │   ├── JefeDashboardController.php        # Vista general y distribución
│   │   ├── JefeAsignacionController.php       # Asignar casos a SEINCRI
│   │   ├── JefeAtencionController.php         # Cambiar estado de casos
│   │   ├── JefeBusquedaController.php         # Búsqueda avanzada
│   │   ├── JefeReportesController.php         # Reportería
│   │   ├── JefeCrearUsuarioController.php     # ABM usuarios
│   │   └── ...
│   ├── mesa/                       # Acciones de Mesa (Recepcionista)
│   │   ├── MesaDashboardController.php        # Mis registros
│   │   ├── MesaRegistroController.php         # Crear incidencia
│   │   ├── MesaBusquedaController.php         # Buscar propios registros
│   │   └── ...
│   └── seincri/                    # Acciones de SEINCRI (Especialista)
│       └── ...
│
├── views/              # Plantillas HTML (organizadas por rol)
│   ├── auth/           # login, cambiar_contrasena, recuperar
│   ├── components/     # Componentes reutilizables (modal, tarjeta, etc)
│   ├── layouts/        # header, footer, sidebar (templates maestros)
│   ├── jefe/           # Vistas específicas de Jefatura
│   ├── mesa/           # Vistas específicas de Mesa
│   └── seincri/        # Vistas específicas de SEINCRI
│
├── helpers/            # Funciones utilitarias
│   ├── Session.php     # Manejo de sesiones $_SESSION
│   ├── Notification.php # Sistemas de alertas y notificaciones
│   ├── Validator.php   # Validación de datos (email, DNI, etc)
│   ├── Uploader.php    # Procesamiento y almacenamiento de archivos
│   ├── PDF.php         # Wrapper de FPDF para reportes
│   └── fpdf/           # Librería FPDF (código de terceros)
│
├── public/             # Raíz web pública
│   ├── index.php       # Punto de entrada (Bootstrap)
│   ├── css/            # (8 archivos CSS modularizados)
│   ├── js/             # (10+ archivos JS - app, busqueda, mapas, etc)
│   ├── img/            # Logos, iconos
│   └── uploads/        # Directorio de almacenamiento de evidencias
│       ├── evidencias/ # Archivos de casos
│       └── reportes/   # PDFs generados
│
└── database/           # Scripts de BD
    ├── schema.sql      # Estructura de tablas [COMPLETAR]
    ├── seeds.sql       # Datos iniciales (usuarios demo) [COMPLETAR]
    └── migrations/     # Control de cambios [COMPLETAR]
```

### 3.3 Justificación Técnica

| Decisión | Alternativas | Justificación |
|---|---|---|
| **PHP Vanilla sin Framework** | Laravel, Symfony, Slim | Proyecto educativo/Startup. PHP puro permite control total, < footprint, no dependencias externas. Trade-off: mayor boilerplate en routing. |
| **Router Manual en web.php** | FastRoute, Nikic | Simpleza, visibilidad total del flujo de rutas. El volumen de rutas es manejable (~50). Permite entender cómo funcionan frameworks por debajo. |
| **Singleton para Database** | Factory, DI Container | Garantiza única conexión PDO reutilizada. PDO es stateful (transacciones). Evita múltiples handshakes a MySQL. |
| **Middleware en closure** | Clases middleware decoradoras | Functional, legible. No requiere inyección de dependencias. Suficiente para 2-3 checks por ruta. |
| **MySQL sin ORM** | Eloquent, Doctrine | Queries explícitas, SQL visible, debugging directo. Volumen de datos bajo (~10k incidencias). ORM sería overhead. |
| **Roles en static check** | RBAC table-driven | Solo 3 roles (Jefe, Mesa, Seincri). Hardcodeo es viable. Escalabilidad futura: migrar a tabla `role_permissions`. |
| **Google Maps embebido** | Leaflet, Mapbox | Geolocalización reverse-geocoding nativa, UI familiar, sin API key compleja. |
| **FPDF para reportes** | Dompdf, mPDF | Librería madura, sin dependencias JS, < size. Mssql perfecta para PDF estático. |

---

## 4. Diseño Técnico

### 4.1 Modelo de Base de Datos

**Entidades principales**:

| Tabla | Propósito | Relaciones |
|---|---|---|
| **Usuarios** | Autenticación y permisos | 1:N con Incidencias (registrado_por) |
| **Incidencias** | Caso/denuncia central | M:1 Usuarios, 1:1 Personas (denunciante), 1:N Evidencias, 1:N Asignaciones |
| **Personas** | Denunciante, agredido, agresor | M:1 Incidencias |
| **Asignaciones** | Derivación a especialista | M:1 Incidencias, M:1 Usuarios (jefe, seincri) |
| **Evidencias** | Archivos adjuntos (fotos, videos, doc) | M:1 Incidencias |
| **Reportes** | Documentos generados | M:1 Incidencias, 1:1 Usuarios (generado_por) |

**Estados de Incidencia**:
- `Pendiente`: Registrada, sin atencion aún
- `En Atencion`: Asignada a SEINCRI
- `Resuelto`: Caso cerrado o derivado

**Roles de Usuario**:
- `Mesa`: Recepcionista, solo crea/edita propias incidencias en estado Pendiente  
- `Jefe`: Gestor, asigna casos, ve reportes, crea usuarios
- `Seincri`: Especialista, atiende casos asignados, levanta acta de atencion

### 4.2 Estructura de Carpetas y Responsabilidades

| Componente | Responsabilidad | Ejemplo |
|---|---|---|
| **models/Usuario.php** | Queries de autenticación, CRUD usuarios, hash contraseña | `$usuario->login($user, $pass)` |
| **models/Incidencia.php** | Generación ID, CRUD incidencias, inserción de personas vinculadas, geolocalización | `$incidencia->crear($datos)` |
| **controllers/jefe/JefeAsignacionController.php** | Validación negocio, orquestación de modelos, pasaje a vista | `asignar($id_incidencia, $id_seincri)` → Update estado + Insert asignacion |
| **views/jefe/asignacion.php** | Renderizado HTML del formulario, JavaScript de cliente | Form POST a JefeAsignacionController |
| **helpers/Validator.php** | Validación de entrada (email, DNI format, etc) | `Validator::esEmail($email)` |
| **helpers/Uploader.php** | Procesamiento seguro de files, sanitización, almacenamiento | `Uploader::guardarEvidencia($file)` |
| **middleware/RoleMiddleware.php** | Verifica sesión + rol requerido antes de controller | `RoleMiddleware::check('JefeAsignacionController@asignar')` |
| **routes/web.php** | Mapeo URL → Controller + pattern matching dinámico | `GET /incidencia/(id)` → extraer param `$id` |

### 4.3 Flujos API principales (no REST, MVC)

No hay API REST explícita. El sistema usa servidor web clásico (PHP tradicional). Interacciones:

**1. Crear Incidencia (Mesa)**
```
POST /mesa/registro 
→ MesaRegistroController::index()
→ if($_POST) $incidencia->crear($data)
→ Redirect /mesa/dashboard
```

**2. Asignar Caso (Jefe)**
```
POST /jefe/asignacion/(id)
→ AuthMiddleware::check()
→ RoleMiddleware::check('Jefe')
→ JefeAsignacionController::asignar($id)
→ $asignacion->crear() + $incidencia->cambiarEstado('En Atencion')
→ Redirect /jefe/dashboard
```

**3. Búsqueda Avanzada (módulo AJAX)**
```
GET /jefe/busqueda?tipo_delito=...&estado=...
→ JefeBusquedaController::buscar()
→ $incidencia->buscar($filtros)
→ return JSON (si AJAX) o render HTML
```

**4. Generar Reporte PDF**
```
GET /jefe/reportes/descargar?mes=02&ano=2026
→ JefeReportesController::descargar()
→ $reporte->generarPDF($data)
→ header('Content-Type: application/pdf')
→ readfile($pathPDF)
```

---

## 5. Metodología Aplicada

**Contexto**: Proyecto educativo/startup policial con ciclo de desarrollo iterativo.

### Ciclo Implementado

| Fase | Entregable | Métrica |
|---|---|---|
| **Backlog Inicial** | Listado de features: login, registro incidencia, asignación, reportes | ~15 user stories |
| **Sprint 1 (1 semana)** | Login + CRUD usuario (Mesa) | Rutas + Middleware + Auth modelo |
| **Sprint 2 (1 semana)** | Registro incidencia con geolocalización | Schema BD + Incidencia modelo + Vista |
| **Sprint 3 (1 semana)** | Asignación de casos + Dashboard Jefe | Flujo completo 3 roles |
| **Sprint 4 (1 semana)** | Búsqueda + Reportes + Upload evidencias | Features avanzadas |

### Artefactos Ágiles

- **Product Backlog**: schema.sql + roadmap comentado en controllers
- **Sprint Board**: Estado de tasks en comentarios código (`// TODO: validar DNI`)
- **Definition of Done**: Feature testeable en navegador, sin errores PHP, BD actualizada

### Problemas Encontrados y Pivotes

| Problema | Solución |
|---|---|
| Rutas complejas (GET /incidencia/(id)/editar) | Router regex manual en web.php |
| Transacciones en creación incidencia (N personas vinculadas) | Transacciones PDO (beginTransaction/commit) |
| Seguridad: SQL Injection | PDO prepared statements en todo el código |
| Geolocalización offline | Google Maps API + fallback a input manual |
| Sesiones expiradas de repente | Session timeout + ForcePasswordChangeMiddleware para primer acceso |

---

## 6. Instalación y Ejecución

### Requisitos
- **PHP 7.4+** with extensions: `pdo_mysql`, `json`, `mbstring`
- **MySQL 5.7+**
- **Apache 2.4+** (XAMPP integra todo)

### Pasos de Setup Inicial

#### 6.1 Clonar y Configurar

```bash
# Windows PowerShell en C:\xampp\htdocs
cd C:\xampp\htdocs
git clone <repo> sistema-policial
cd sistema-policial
```

#### 6.2 Configurar Credenciales BD

Editar `.env`:
```dotenv
DB_HOST=localhost
DB_NAME=sistema_policial_huancavelica  # crear en MySQL
DB_USER=root
DB_PASS=                                 # contraseña local (típicamente vacía en XAMPP)
DB_PORT=3306
APP_ENV=development
```

Copiar a `config/database.php`:
```bash
# Windows: puedes volver a copiar el contenido manualmente o usar
Copy-Item -Path .env -Destination config/database.php
```

#### 6.3 Crear Base de Datos

```sql
-- En phpMyAdmin o CLI MySQL
CREATE DATABASE sistema_policial_huancavelica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Cargar schema
USE sistema_policial_huancavelica;
SOURCE database/schema.sql;
SOURCE database/seeds.sql;
```

#### 6.4 Permisos y Directorios

```bash
# Windows (PowerShell como Admin)
mkdir public\uploads\evidencias
mkdir public\uploads\reportes
icacls "public\uploads" /grant:r "%USERNAME%:F" /T  # Permisos lectura-escritura
```

#### 6.5 Iniciar Servidor

**Opción A: XAMPP Apache**
```
Abrir XAMPP Control Panel → Start Apache
Navegar a http://localhost/sistema-policial/public/
```

**Opción B: PHP Built-in Server** (desarrollo rápido)
```bash
cd C:\xampp\htdocs\sistema-policial
php -S localhost:8000 -t public
# Navegar a http://localhost:8000
```

### Primeros Pasos en la App

1. **Login**: Usuario demo `jefe_demo` / `demo123`
2. **Crear Incidencia** (rol Mesa): Menú Registro → Completar formulario
3. **Asignar Caso** (rol Jefe): Menú Asignación → Seleccionar incidencia → Designar SEINCRI
4. **Ver Reportes**: Menú Reportes → Filtrar por mes/zona → Descargar PDF

---

## 7. Evidencia del Sistema

[COMPLETAR: Agregar aquí capturas de pantalla o descriptor de vistas principales]

**Pantallas key**:
- Login (auth/login.php)
- Dashboard Mesa (mesa/dashboard.php)
- Formulario Registro Incidencia (mesa/registro.php con Google Maps embebido)
- Dashboard Jefe con tabla de incidencias (jefe/dashboard.php)
- Modal de Asignación (components/modal-asignacion.php)
- Reporte PDF generado (public/uploads/reportes/...)

---

## 8. Retos Técnicos y Soluciones

### Reto 1: Geolocalización Manual vs API
**Problema**: Google Maps Geocoding API requiere API key. Usuarios offline no pueden localizar.

**Solución**: Dual approach:
- Si hay JavaScript + internet: `maps.js` hace reverse-geocoding automático  
- Si falla: Input manual de dirección. En backend: validar formato dirección, guardar NULL lat/lng.

**Código**:
```javascript
// public/js/maps.js
navigator.geolocation.getCurrentPosition((pos) => {
  fetch(`/api/geocode?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`)
    .then(r => r.json())
    .then(data => document.getElementById('direccion').value = data.address);
}, () => alert('Permiso denegado o offline'));
```

### Reto 2: Transacciones en Creación de Incidencia
**Problema**: Una incidencia implica insertar 3-5 registros (Personas + Incidencia + Evidencias). Si falla uno, quedan datos huérfanos.

**Solución**: Transacción PDO:
```php
$this->pdo->beginTransaction();
try {
  // Insert denunciante
  // Insert agredido (si aplica)
  // Insert agresor (si aplica)
  // Insert incidencia
  $this->pdo->commit();
} catch(Exception $e) {
  $this->pdo->rollBack();
  throw $e;
}
```

### Reto 3: Seguridad - Inyección SQL
**Problema**: Entrada de usuario en búsquedas (ej: "O'Brien" quiebra query).

**Solución**: PDO prepared statements con placeholders:
```php
$stmt = $this->pdo->prepare("SELECT * FROM Incidencias WHERE tipo_delito = ? AND estado = ?");
$stmt->execute([$tipo, $estado]); // Valores escapados automáticamente
```

### Reto 4: Control de Acceso Granular
**Problema**: Jefe no debe ver incidencias de otra comisaría. Mesa solo ve propias.

**Solución**: Middleware + WHERE condicionado:
```php
// RoleMiddleware verifica rol y permiso
if($role === 'Mesa') {
  $incidencias = $incidencia->obtenerPorUsuario($_SESSION['id_usuario']);
} elseif($role === 'Jefe') {
  $incidencias = $incidencia->obtenerPorComisaria($_SESSION['comisaria']);
}
```

### Reto 5: Cambio Contraseña en Primer Acceso
**Problema**: Usuarios creados por Jefe reciben contraseña temporal. Deben cambiarla antes de operar.

**Solución**: Flag `es_primer_inicio=1` + Middleware:
```php
class ForcePasswordChangeMiddleware {
  public static function check() {
    if($_SESSION['es_primer_inicio']) {
      header('Location: /cambiar-contrasena');
      exit;
    }
  }
}
```

---

## 9. Mejoras Futuras

### Escalabilidad
1. **Migrar a Framework**: PHP 8.2 + Laravel 11. Beneficios: Eloquent ORM (menos SQL manual), Blade templates, Built-in migrations, Queues para reportes async.
2. **API REST**: Exponer endpoints JSON para apps móviles (iOS/Android nativo).
3. **Cache**: Redis para dashboards (precalcular incidencias por zona diariamente).

### Seguridad
1. **2FA**: TOTP (Google Authenticator) para usuarios Jefe/SEINCRI.
2. **Rate Limiting**: DDoS protection en login (máx 5 intentos/min).
3. **Audit Log**: Tabla que registre quién vio qué, cuándo. Cumplimiento normativo.
4. **HTTPS Obligatorio**: Certificado SSL. Env var `FORCE_HTTPS=1`.

### Performance
1. **Indexes en BD**: Índices en `(id_usuario, fecha_creacion)` para queries rápidas.
2. **Caching de Sesión**: Memcached en lugar de archivos.
3. **CDN para Assets**: CSS/JS minificados, servidos desde Cloudflare.
4. **Paginación en Búsquedas**: Límite 50 resultados por página. Evitar SELECT * de 10k filas.

### Features
1. **Notificaciones Email**: Cuando se asigna un caso, enviar mail a SEINCRI.
2. **Chat Interno**: Comentarios entre Mesa/Jefe/SEINCRI sobre un caso.
3. **Integración RENIEC**: Validación automática DNI contra registro oficiales.
4. **Mobile Responsive**: Mejorar layout para tablets (ahora solo mobile-first CSS).
5. **Dark Mode**: CSS variables + toggle en perfil.
6. **Analytics Dashboard**: Gráficos de tendencias de delitos, hotspots en mapa.

### Technical Debt
- [ ] Refactorizar controllers largos (> 200 líneas) en métodos más chicos.
- [ ] Crear trait `HasTimestamps` para no repetir `created_at, updated_at` en modelos.
- [ ] Tests unitarios con PHPUnit (0% coverage actualmente).
- [ ] Scripts database migrations en lugar de manual SQL.
- [ ] Documentar APIs (Swagger/OpenAPI) si se expone REST.

---

## 10. Autor

**[COMPLETAR: tu nombre, email, GitHub, LinkedIn]**

---

## Anexos

### A. Comandos Útiles (Windows PowerShell)

```powershell
# Iniciar servidor rápidamente
cd C:\xampp\htdocs\sistema-policial; php -S localhost:8000 -t public

# Ver logs en tiempo real
Get-Content -Path "path/logs/app.log" -Wait

# Respaldar BD
mysqldump -u root sistema_policial_huancavelica > backup_$(Get-Date -Format 'yyyyMMdd').sql

# Recargar esquema (destructivo)
mysql -u root < database/schema.sql
```

### B. Variables de Entorno Clave

| Variable | Valor | Notas |
|---|---|---|
| `APP_ENV` | `development` \| `production` | Controla display_errors |
| `DEFAULT_TIMEZONE` | `America/Lima` | Zona horaria Perú |
| `UPLOAD_LIMIT_MB` | `50` | Máximo tamaño archivo |
| `SESSION_TIMEOUT` | `3600` | Segundos antes de expirar |

### C. Estructura de Tabla Usuarios (Referencia)

```sql
CREATE TABLE Usuarios (
    id_usuario VARCHAR(10) PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    rol ENUM('Mesa', 'Jefe', 'Seincri') NOT NULL,
    comisaria VARCHAR(100),
    es_primer_inicio BOOLEAN DEFAULT TRUE,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP
);
```

---

**Última actualización**: 15 de Febrero de 2026
