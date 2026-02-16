# 🔧 Guía de Instalación Detallada

Instrucciones paso a paso para configurar el sistema **Sistema Policial de Gestión de Incidencias** en tu entorno local.

---

## Requisitos Previos

### Mínimo Recomendado

| Software | Versión | Notas |
|----------|---------|-------|
| PHP | 7.4+ | Soporte OOP, prepared statements |
| MySQL | 5.7+ | Tipo dato JSON opcional |
| Apache | 2.4+ | mod_rewrite requerido para rutas |
| Git | 2.0+ | Control de versiones |

### Instalación Rápida en Windows: XAMPP

XAMPP incluye PHP + MySQL + Apache listo para usar.

1. Descargar de [apachefriends.org](https://www.apachefriends.org/)
2. Ejecutar instalador
3. Elegir componentes: ✅ Apache, ✅ MySQL, ✅ PHP
4. Ruta por defecto: `C:\xampp`

Verificar:
```powershell
php -v          # Debería retornar versión
mysql -V        # MySQL CLI
```

---

## Paso 1: Clonar Repositorio

```powershell
cd C:\xampp\htdocs
git clone https://github.com/tuusuario/sistema-policial.git
cd sistema-policial
ls                # Verificar que ves config/, controllers/, models/, etc
```

Si no tienes Git instalado:
1. Descargar ZIP desde GitHub
2. Extraer en `C:\xampp\htdocs\sistema-policial`

---

## Paso 2: Configurar Archivo .env

El archivo `.env` contiene credenciales y configuración sensible.

### Crear o Editar `.env`

```bash
# Windows PowerShell
notepad .env
```

**Contenido mínimo**:

```dotenv
# Base de Datos
DB_HOST=localhost
DB_NAME=sistema_policial_huancavelica
DB_USER=root
DB_PASS=                    # Dejar vacío si XAMPP + sin contraseña (típico)
DB_PORT=3306

# Aplicación
APP_ENV=development         # development|production
APP_DEBUG=true              # true|false
TIMEZONE=America/Lima

# Session
SESSION_TIMEOUT=3600        # segundos (1 hora)
REMEMBER_ME_DAYS=7

# Upload de Evidencias
UPLOAD_LIMIT_MB=50
UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,pdf,doc,docx,xls

# Google Maps (opcional, pero recomendado)
GOOGLE_MAPS_API_KEY=[COMPLETAR SI USAS API]
```

### ⚠️ Seguridad: Nunca commitear .env

Verificar `.gitignore` contiene `.env`:

```bash
# .gitignore
.env
*.log
public/uploads/
node_modules/
```

---

## Paso 3: Crear Base de Datos MySQL

### Opción A: PHPMyAdmin (Interfaz Gráfica)

1. Abrir XAMPP Control Panel → Click "Admin" de MySQL
2. PHPMyAdmin abre en navegador
3. Click en "Nuevo" o "+" en la izquierda
4. Nombre: `sistema_policial_huancavelica`
5. Colocación: `utf8mb4_unicode_ci`
6. Crear

### Opción B: MySQL CLI (Línea de Comandos)

```bash
# Abrir CMD o PowerShell
# Navegar a C:\xampp\mysql\bin (o simplemente):
mysql -u root

# Dentro de MySQL:
CREATE DATABASE sistema_policial_huancavelica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_policial_huancavelica;
SOURCE C:/xampp/htdocs/sistema-policial/database/schema.sql;
SOURCE C:/xampp/htdocs/sistema-policial/database/seeds.sql;
EXIT;
```

### Opción C: Automatizado (Script PowerShell)

```powershell
# Windows PowerShell (como Admin)
$dbHost = "localhost"
$dbUser = "root"
$dbPass = ""
$dbName = "sistema_policial_huancavelica"

# Crear BD
mysql -h $dbHost -u $dbUser $("-p" + $dbPass) -e "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Cargar schema
mysql -h $dbHost -u $dbUser $("-p" + $dbPass) $dbName < database/schema.sql

# Cargar datos iniciales
mysql -h $dbHost -u $dbUser $("-p" + $dbPass) $dbName < database/seeds.sql

Write-Host "BD creada exitosamente"
```

### Verificar Creación

```bash
mysql -u root -e "SHOW DATABASES LIKE 'sistema_policial%';"
# Debería listar: sistema_policial_huancavelica
```

---

## Paso 4: Configurar Permisos de Carpetas

Las carpetas de upload necesitan permisos de escritura para que Apache/PHP guarde archivos.

### Windows PowerShell (Como Administrador)

```powershell
# Navegar a la raíz del proyecto
cd C:\xampp\htdocs\sistema-policial

# Crear directorios si no existen
mkdir -Path "public/uploads/evidencias" -Force
mkdir -Path "public/uploads/reportes" -Force

# Dar permisos de lectura/escritura
icacls "public/uploads" /grant:r "$($env:USERNAME):F" /T
icacls "public/uploads/evidencias" /grant:r "$($env:USERNAME):F" /T
icacls "public/uploads/reportes" /grant:r "$($env:USERNAME):F" /T

Write-Host "✓ Permisos configurados"
```

### Windows GUI (Alternativa)

1. Click derecho `public/uploads` → Propiedades
2. Pestaña "Seguridad" → Editar
3. Seleccionar tu usuario → Click "Editar"
4. Marcar ✅ Modificar, ✅ Lectura, ✅ Escritura
5. Aplicar → Aceptar

---

## Paso 5: Iniciar Servidor Web

### Opción A: Apache (XAMPP) - RECOMENDADO

```
1. Abrir XAMPP Control Panel (C:\xampp\xampp-control.exe)
2. Buscar "Apache" en la lista
3. Click en botón "Start" (se pondrá verde)
4. Esperar 2-3 segundos
5. Verificar log sin errores
```

**Acceder a la app**:
```
http://localhost/sistema-policial/public/
o
http://127.0.0.1/sistema-policial/public/
```

### Opción B: PHP Built-in Server (Desarrollo Ligero)

```powershell
cd C:\xampp\htdocs\sistema-policial

# Iniciar servidor en puerto 8000
php -S localhost:8000 -t public

# Debería imprimir:
# Development Server (PHP 7.4.x) started at [Wed Feb 15 10:30:00 2026]
# Listening on http://localhost:8000
# Press Ctrl-C to quit
```

**Acceder**:
```
http://localhost:8000
o
http://127.0.0.1:8000
```

**Ventajas del Built-in server**:
- ✅ No requiere Apache
- ✅ Perfecto para desarrollo
- ✅ Puedes ver logs en consola en tiempo real

**Desventajas**:
- ⚠️ No apto para producción
- ⚠️ Single-threaded (1 request a la vez)
- ⚠️ Se detiene si cierras PowerShell

---

## Paso 6: Verificar Instalación

Navega a la URL según tu opción:

### Debe Mostrarse: Página de Login

```
┌─────────────────────────────────────┐
│    SISTEMA POLICIAL                 │
│  Gestión de Incidencias             │
│                                      │
│  Usuario: [____________]            │
│  Contraseña: [____________]        │
│                                      │
│  [    Ingresar   ]                  │
│  ¿Olvidaste contraseña?             │
└─────────────────────────────────────┘
```

### Si Ves Error 404

**Causa 1: Apache no tiene mod_rewrite activo**
```
→ XAMPP Control Panel → Apache → Config → httpd.conf
→ Buscar: #LoadModule rewrite_module
→ Quitar # (uncomment): LoadModule rewrite_module
→ Guardar y reiniciar Apache
```

**Causa 2: Archivo .htaccess no existe o está mal**
```
→ Verificar C:\xampp\htdocs\sistema-policial\public\.htaccess existe
→ Contenido debe tener reglas de reescritura
```

**Causa 3: Ruta incorrecta de proyecto**
```
→ Asegúrate que clonaste en C:\xampp\htdocs\sistema-policial
→ NO es C:\xampp\htdocs\sistema-policial\sistema-policial (doble carpeta)
```

### Si Ves Error: "Base de datos no encontrada"

**Verificar conexión MySQL**:
```bash
# Terminal
mysql -u root -h localhost
# Debería conectar sin error

SHOW DATABASES;
# Debería listar sistema_policial_huancavelica

USE sistema_policial_huancavelica;
SHOW TABLES;
# Debería listar: Usuarios, Incidencias, Personas, etc (8 tablas)
```

**Verificar credenciales en config**:
```bash
notepad C:\xampp\htdocs\sistema-policial\config\database.php
# Asegurar que coinciden con:
# - DB_HOST = localhost
# - DB_USER = root
# - DB_PASS = (vacío si es XAMPP default)
# - DB_NAME = sistema_policial_huancavelica
```

---

## Paso 7: Datos Iniciales (Seeds)

El archivo `database/seeds.sql` crea usuarios de demo.

**Usuarios disponibles después de cargar seeds**:

| Usuario | Contraseña | Rol | Comisaría |
|---------|-----------|-----|-----------|
| jefe_demo | demo123 | Jefe | Huancavelica |
| mesa_demo | demo123 | Mesa | Huancavelica |
| seincri_demo | demo123 | Seincri | Huancavelica |

**Para crear nuevos usuarios**:
1. Inicia sesión con `jefe_demo` / `demo123`
2. Menú "Crear Usuario"
3. Asignar rol y comisaría
4. Usuario recibe contraseña temporal
5. Obliga cambio de contraseña en primer acceso

---

## Paso 8: Primeros Pasos en la App

### 8.1 Login

```
Usuario: jefe_demo
Contraseña: demo123
```

Click "Ingresar" → Debería redirigir a `/jefe/dashboard`

### 8.2 Crear Incidencia (Rol Mesa)

Si eres **Jefe**, primero crea un usuario Mesa:

```
1. Cambia de usuario o logout
2. Inicia sesión con mesa_demo / demo123
3. Menú lateral → "Registro"
4. Formulario Nuevo Registro de Incidencia:
   - Nombre denunciante
   - Seleccionar ubicación en mapa
   - Tipo de delito
   - Descripción
   - Click "Registrar"
5. Debería confirmar: "Incidencia registrada con ID: INC-2026-0001"
```

### 8.3 Asignar Caso (Rol Jefe)

Ahora con rol **Jefe**:

```
1. Logout e inicia sesión con jefe_demo / demo123
2. Dashboard → Ver tabla de incidencias pendientes
3. Click en "INC-2026-0001" → "Asignar"
4. Seleccionar especialista SEINCRI
5. Click "Asignar"
6. Estado cambia a "En Atención"
```

### 8.4 Generar Reportes (Rol Jefe)

```
1. Menú Reportes
2. Filtrar por período (mes/año)
3. Click "Generar PDF"
4. Se descarga archivo: reporte_2026_02.pdf
5. Contiene estadísticas de incidencias por zona y delito
```

---

## Troubleshooting Avanzado

### Problema: Error 500 - "Error de conexión a base de datos"

**Paso 1: Verificar MySQL está corriendo**
```bash
# PowerShell
Get-Service MySQL | Select-Object Status
# Output: Status (debe ser "Running")

# Si NO está corriendo:
Start-Service MySQL
```

**Paso 2: Verificar archivo database.php**
```bash
notepad config/database.php
# Asegurar que carga variables de .env correctamente
```

**Paso 3: Test de conexión**
```php
// Crear archivo test_db.php en public/
<?php
require_once __DIR__ . '/../models/Database.php';
$db = Database::getInstance();
echo "✓ Conexión exitosa";
?>

# Acceder a http://localhost:8000/test_db.php
```

### Problema: "Permission Denied" en public/uploads

**Windows**: 
```powershell
# Asegurar que Apache tiene permisos
icacls "public\uploads" /grant:r "NETWORK SERVICE:F" /T
icacls "public\uploads" /grant:r "IUSR:F" /T
icacls "public\uploads" /grant:r "$($env:USERNAME):F" /T
```

**Linux/Mac**:
```bash
chmod -R 755 public/uploads
chmod -R 777 public/uploads  # Si es de desarrollo
```

### Problema: Las Rutas Retornan 404

**Causas comunes**:

1. **mod_rewrite no activo en Apache**
   ```
   XAMPP Control Panel → Apache → Config → httpd.conf
   Buscar: LoadModule rewrite_module
   (descommentar si está con #)
   Reiniciar Apache
   ```

2. **Archivo .htaccess no existe o está mal**
   ```bash
   # Verificar que existe
   ls -la public/.htaccess
   
   # Contenido mínimo:
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteBase /sistema-policial/public/
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]
   </IfModule>
   ```

3. **Usando PHP Built-in server**
   ```bash
   # El built-in NO interpreta .htaccess
   # Las rutas funcionan, aber URL puede variar:
   http://localhost:8000/jefe/dashboard
   # En lugar de:
   http://localhost/sistema-policial/public/jefe/dashboard
   ```

### Problema: Google Maps No Muestra Mapa

**Causa**: Falta API key

**Solución rápida** (desarrollo sin API key):
```javascript
// En public/js/registro-mapa.js (buscar Google Maps)
// Comentar la carga de script:
// <script src="https://maps.googleapis.com/maps/api/js?key=..."></script>

// Usar fallback manual de coordenadas:
document.getElementById('latitud').value = "-12.0658";
document.getElementById('longitud').value = "-75.5278";  // Lima, Perú
```

**Solución producción**: Obtener API key
1. Ir a [Google Cloud Console](https://console.cloud.google.com/)
2. Crear proyecto
3. Habilitar: Maps JavaScript API + Geocoding API  
4. Generar API Key
5. Agregar a .env: `GOOGLE_MAPS_API_KEY=tu_key_aqui`
6. Configurar restricciones HTTP Referrer

---

## Checklists de Instalación

### ✅ Setup Completado Exitosamente

- [ ] PHP 7.4+ instalado y accesible desde PowerShell
- [ ] MySQL corriendo y accesible desde CLI
- [ ] XAMPP Apache iniciado (o php -S corriendo)
- [ ] Repositorio clonado en `C:\xampp\htdocs\sistema-policial`
- [ ] Archivo `.env` creado con credenciales
- [ ] Base de datos `sistema_policial_huancavelica` creada
- [ ] Schema y seeds cargados (`SHOW TABLES` muestra 8+ tablas)
- [ ] Carpetas `public/uploads/` con permisos 755+
- [ ] App accesible en http://localhost:8000 o http://localhost/sistema-policial/public/
- [ ] Login funciona con `jefe_demo` / `demo123`
- [ ] Dashboard muestra sin errores PHP

### ❌ Errores Comunes Resueltos

- [ ] Error 404 → mod_rewrite habilitado en Apache
- [ ] Error conexión BD → MySQL corriendo + credenciales en .env
- [ ] Error permisos uploads → icacls + permisos folder
- [ ] Google Maps en blanco → API key configurada o fallback manual
- [ ] Session expirada → SESSION_TIMEOUT ajustado en .env

---

## Siguientes Pasos

1. **Explorar la App**: 
   - Crear incidencias con rol Mesa
   - Asignar casos con rol Jefe
   - Ver reportes

2. **Leer Documentación**:
   - [README.md](../README.md) - Descripción general
   - [ARCHITECTURE.md](../ARCHITECTURE.md) - Diseño técnico profundo

3. **Contribuir**:
   - [CONTRIBUTING.md](../CONTRIBUTING.md) - Guía para commits

4. **Producción** (cuando esté listo):
   - Cambiar `APP_ENV=production` en `.env`
   - Configurar HTTPS
   - Usar servidor MySQL remoto
   - Habilitar 2FA y audit log

---

**Última actualización**: 15 de Febrero de 2026  
**Versión**: 1.0.0
