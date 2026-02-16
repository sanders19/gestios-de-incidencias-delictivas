# 🤝 Guía de Contribución

Gracias por querer contribuir al Sistema Policial de Gestión de Incidencias. Esta guía te explica cómo hacerlo de forma ordenada y profesional.

---

## Código de Conducta

- ✅ Sé respetuoso con otros contribuidores
- ✅ Explica claramente tus cambios en commits y PRs
- ✅ Prueba tu código antes de enviar
- ✅ Sigue los estándares de estilo del proyecto
- ❌ No incluyas credenciales, contraseñas o tokens en commits

---

## Flujo de Trabajo Git

### 1. Evitar Trabajar Directamente en `main`

Siempre crear una rama nueva para cambios:

```bash
git checkout -b feature/nombre-descriptivo
# o
git checkout -b bugfix/nombre-descriptivo
# o
git checkout -b docs/nombre-descriptivo
```

**Ejemplos de nombres correctos**:
- `feature/busqueda-avanzada`
- `bugfix/login-doble-sesion`
- `docs/actualizar-readme`
- `refactor/simplificar-incidencia-model`

**❌ Nombres incorrectos**:
- `cambios`
- `mi-rama`
- `hola123`
- `arreglar`

### 2. Hacer Commits Atómicos

Cada commit debe ser **una unidad lógica completa**:

```bash
# ✅ BIEN: Un feature por commit
git add controllers/jefe/JefeAsignacionController.php
git commit -m "feat: agregar validación en asignación de casos"

# ❌ MAL: Mezclar múltiples cosas
git add -A
git commit -m "varias cosas"
```

### 3. Mensaje de Commit Profesional

Usar convenio **Conventional Commits**:

```
<tipo>(<alcance>): <descripción>

<cuerpo opcional>

<pie opcional>
```

**Tipos permitidos**:
- `feat`: Nueva característica
- `fix`: Arregla un bug
- `docs`: Cambios en documentación
- `style`: Formato de código (sin cambio de lógica)
- `refactor`: Reorganizar código sin cambiar comportamiento
- `perf`: Mejoras de performance
- `test`: Agregar o modificar tests
- `chore`: Tareas de mantenimiento (deps, tooling)

**Ejemplos**:

```bash
# Feature
git commit -m "feat(incidencia): agregar geolocalización automática"

# Bugfix
git commit -m "fix(auth): corregir validación de contraseña en login"

# Refactor
git commit -m "refactor(models): extraer lógica de transacciones a trait"

# Con descripción larga
git commit -m "feat(reportes): exportar PDF con estadísticas

- Agregar generador de gráficos con FPDF
- Filtrar por rango de fechas
- Incluir logo en encabezado
- Closes #42"
```

### 4. Subir e Crear Pull Request

```bash
# Haber hecho commits en tu rama
git push origin feature/nombre-descriptivo

# En GitHub: abrir Pull Request (PR)
# - Título descriptivo: "feat: agregar asignación automática de casos"
# - Describir QUÉ cambió y POR QUÉ
# - Linkar issue si es relacionado: "Closes #42"
# - Listar cambios principales
```

**Plantilla de PR**:

```markdown
## Descripción
Agregar flujo de asignación automática de casos a SEINCRI según carga de trabajo.

## Tipo de Cambio
- [x] Nueva característica
- [ ] Bugfix
- [ ] Breaking change
- [ ] Cambio en documentación

## Cambios Principales
- Nueva tabla `Asignaciones` en modelo
- Controller `JefeAsignacionController` con lógica
- Vistas HTML + JavaScript para UI

## Testing
- [x] Probado en navegador Chrome
- [x] Probado en Firefox
- [x] Testeado con MySQL 5.7

## Checklist
- [x] Mi código sigue el estilo del proyecto
- [x] Hice self-review
- [x] Actualicé documentación relevante
- [x] Ningún error PHP en consola
- [x] No incluí credenciales

## Screenshots (si aplica)
[Agregar capturas antes/después]
```

---

## Estándares de Código PHP

### Estructura de Archivos

```php
<?php
// 1. Abrir tag solo (sin cerrar ?>)

// 2. Imports/requires en orden
require_once 'Database.php';
require_once 'Validator.php';

// 3. Clase o funciones globales
class MiClase {
    // Contenido
}
?>
```

### Denominaciones (Naming)

| Tipo | Convención | Ejemplo |
|------|-----------|---------|
| **Clases** | PascalCase | `UsuarioController, IncidenciaModel` |
| **Métodos** | camelCase | `crearUsuario(), validarEmail()` |
| **Variables** | snake_case | `$usuario_id, $fecha_creacion` |
| **Constantes** | UPPER_SNAKE | `DB_HOST, MAX_UPLOAD_SIZE` |
| **Funciones** | snake_case | `sanitizar_input(), verificar_rol()` |

### Estructura de Clases

```php
<?php
class IncidenciaController {
    
    // 1. Propiedades privadas
    private $modelo;
    private $validator;
    
    // 2. Constructor
    public function __construct() {
        $this->modelo = new Incidencia();
        $this->validator = new Validator();
    }
    
    // 3. Métodos públicos (en orden de importancia)
    public function index() {
        // Implementación
    }
    
    public function crear() {
        // Implementación
    }
    
    // 4. Métodos privados/helpers
    private function procesarDatos($datos) {
        // Implementación
    }
}
?>
```

### Validación de Entrada

**SIEMPRE validar entrada**:

```php
// ❌ MAL: Confiar en datos del usuario
$id = $_GET['id'];
$incidencia = $modelo->obtener($id);

// ✅ BIEN: Validar y sanitizar
$id = $_GET['id'] ?? null;
if (!is_numeric($id) || $id < 1) {
    throw new Exception("ID inválido");
}
$incidencia = $modelo->obtener((int)$id);
```

### Manejo de Errores

```php
// ✅ BIEN: Try-catch con logging
try {
    $resultado = $this->modelo->crear($datos);
    Session::success("Incidencia creada");
} catch (PDOException $e) {
    error_log("Error BD: " . $e->getMessage());
    Session::error("Error al crear incidencia. Intenta más tarde.");
}

// ❌ MAL: Silenciar errores
@$resultado = $this->modelo->crear($datos);
```

### Comentarios

Usar comentarios **solo para el "por qué"**, no el "qué":

```php
// ❌ MAL: Obvio por el código
$usuario = $modelo->obtener($id);  // Obtener usuario
$hash = password_hash($pass, PASSWORD_DEFAULT);  // Hashear contraseña

// ✅ BIEN: Explica decisión técnica
// Usar bcrypt con costo 10 en lugar de ARGON2
// porque servidor legacy no soporta ARGON2id
$hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);
```

---

## Estándares de Frontend (HTML, CSS, JS)

### HTML

```html
<!-- Estructura semántica -->
<section class="registros">
    <h2>Mis Registros</h2>
    <table class="tabla-registros">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <!-- filas -->
        </tbody>
    </table>
</section>
```

### CSS

```css
/* Usar clases (no IDs para estilos) */
.boton-primario {
    background-color: #007bff;
    padding: 10px 20px;
    border-radius: 4px;
}

/* Mobile-first */
.dashboard {
    display: grid;
    grid-template-columns: 1fr;  /* Mobile: 1 columna */
}

@media (min-width: 768px) {
    .dashboard {
        grid-template-columns: 2fr 1fr;  /* Tablet+: 2 columnas */
    }
}
```

### JavaScript

```javascript
// Declarar variables con const/let, no var
const idUsuario = sessionStorage.getItem('id_usuario');
let contadorErrores = 0;

// Usar async/await en lugar de callbacks
async function cargarIncidencias(filtro) {
    try {
        const response = await fetch(`/api/incidencias?${filtro}`);
        const datos = await response.json();
        renderizar(datos);
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('Error cargando incidencias');
    }
}

// Usar nombre descriptivo
document.getElementById('boton-buscar')
    .addEventListener('click', buscarIncidencias);
```

---

## Testing Manual

**Antes de hacer commit**:

### Pruebas Funcionales

```bash
# 1. Login con cada rol
# - jefe_demo / demo123 (Jefe)
# - mesa_demo / demo123 (Mesa)
# - seincri_demo / demo123 (SEINCRI)

# 2. Crear incidencia (Mesa)
# → Rellenar formulario completo
# → Verificar que se registra con ID generado

# 3. Asignar caso (Jefe)
# → Ver dashboard con incidencias pendientes
# → Asignar a SEINCRI
# → Verificar que estado cambia a "En Atención"

# 4. Generar reporte
# → Reportes → Seleccionar filtros
# → Descargar PDF
# → Verificar contenido en lector PDF
```

### Pruebas de Errores

```bash
# Inyección SQL
# En búsqueda: ' OR '1'='1
# → Debería NOT retornar todos los registros

# XSS
# En formulario: <script>alert('XSS')</script>
# → Debería escapar, no ejecutar

# Archivos
# Intentar subir .exe
# → Debería rechazar con error

# Sesión
# Cambiar cookie de sesión manualmente
# → Debería logout automático
```

### Validación de BD

```bash
mysql -u root sistema_policial_huancavelica

# Verificar integridad de datos
SELECT COUNT(*) FROM Incidencias;  # No 0
SELECT * FROM Usuarios WHERE rol='Jefe';  # Al menos 1

# Verificar sin datos consistentes
SELECT * FROM Incidencias WHERE id_denunciante IS NULL;  # Debería estar vacío
```

---

## Actualización de Documentación

Si tu cambio **afecta cómo se usa o se despliega el sistema**, actualiza docs:

- **README.md**: Cambios en requisitos o instalación
- **ARCHITECTURE.md**: Nuevo patrón o flujo de negocio
- **SETUP_GUIDE.md**: Nuevas variables de entorno o dependencias

Ejemplo de update:

```markdown
## Antes
```
git clone <repo>
```

## Ahora
```
git clone <repo>
npm install  # ← Nuevo step agregado
php -S localhost:8000 -t public
```
```

---

## Checklist Antes de Hacer PR

- [ ] `git pull origin main` (sincronizar con cambios remotos)
- [ ] Probar **toda la rama en navegador** sin errores
- [ ] `php -l models/*.php` (verificar sintaxis PHP)
- [ ] Console de navegador sin errores JS (F12)
- [ ] Base de datos intacta (no borraste tables)
- [ ] NO hay credenciales en commit (`.env` no committeado)
- [ ] Commits tienen mensaje descriptivo
- [ ] Documentación actualizada si es necesario
- [ ] Rama `feature/...` actualizada con último `main`

```bash
# Pasos finales
git fetch origin
git rebase origin/main  # Traer cambios recientes a tu rama
git push origin feature/nombre
# → Abrir PR en GitHub
```

---

## Proceso de Review

### Si Tu PR es Revisado

1. **Cambios solicitados**: Actualiza la rama y hace push nuevamente (no PR nueva)
   ```bash
   git add .
   git commit -m "feat: responder feedback de revisión"
   git push origin feature/nombre  # Mismo PR se actualiza
   ```

2. **Aprobada**: Proyecto owner hará merge (no hagas tú merge)

3. **Merged**: Tu rama se puede eliminar
   ```bash
   git checkout main
   git pull origin main
   git branch -d feature/nombre
   ```

---

## Preguntas Frecuentes

**P: ¿Puedo modificar un commit ya pusheado?**  
R: No en `main`. En tu rama `feature/...` sí:
```bash
git reset --soft HEAD~1      # Deshacer último commit, guardar cambios
git commit --amend           # Modificar mensaje
git push --force origin feature/nombre
```

**P: ¿Cómo traigo cambios de `main` a mi rama?**  
R: Rebase:
```bash
git fetch origin
git rebase origin/main
git push --force origin feature/nombre
```

**P: ¿Accidentalmente hice commit de .env?**  
R: Removerlo del historio:
```bash
git rm --cached .env
git commit --amend  # Actualiza commit anterior
git push --force origin feature/nombre
```

**P: ¿Puedo commitear a `main` directamente?**  
R: No. Siempre PR on rama nueva. `main` está protegida.

---

## Roadmap de Desarrollo

Características planeadas (prioridad):

### Priori­dad 1 (Próximo mes)
- [ ] 2FA TOTP para Jefe/SEINCRI
- [ ] API REST (endpoints read-only)
- [ ] Unit tests (PHPUnit)

### Prioridad 2 (2-3 meses)
- [ ] App móvil (React Native)
- [ ] Integración RENIEC
- [ ] Notificaciones email

### Prioridad 3 (Backlog largo)
- [ ] Chat interno de casos
- [ ] Dark mode
- [ ] Analytics dashboard

---

## Contacto

- **Mantenedor**: [TU NOMBRE] - [@GitHub](https://github.com/tuusuario)
- **Issues**: [GitHub Issues](https://github.com/tuusuario/sistema-policial/issues)
- **Email**: tu@email.com

---

**Gracias por contribuir al Sistema Policial. Juntos lo hacemos mejor. 🚔**

Última actualización: 15 de Febrero de 2026

