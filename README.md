# 🚔 Sistema Policial de Gestión de Incidencias

**Plataforma integral para registrar, asignar y reportar incidencias delictivas con geolocalización en tiempo real.**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/) 
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green)]()

---

## 📋 Descripción Rápida

Sistema policial diseñado para autoridades locales que necesitan:

- **Registro inmediato** de denuncias con datos de denunciante, localización y tipo de delito
- **Gestión de casos** con flujo Pendiente → En Atención → Resuelto  
- **Asignación inteligente** de casos a especialistas (SEINCRI)
- **Control de acceso por rol**: Mesa (recepcionista), Jefe (gestor), SEINCRI (especialista)
- **Reportería en PDF** con estadísticas por zona, período y tipo de delito
- **Almacenamiento de evidencias** digitales (fotos, documentos)

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| **Backend** | PHP 7.4+ (OOP, PDO) |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla) |
| **Base de Datos** | MySQL 5.7+, UTF-8MB4 |
| **Servidor** | Apache 2.4 (XAMPP) o PHP Built-in |
| **Reportes** | FPDF, Google Maps API |
| **Versionado** | Git |

---

## 🚀 Inicio Rápido (5 minutos)

### 1. Descargar y Configurar

```bash
# Windows PowerShell en C:\xampp\htdocs
cd C:\xampp\htdocs
git clone <repo> sistema-policial
cd sistema-policial

# Editar .env con credenciales MySQL
notepad .env
# DB_HOST=localhost
# DB_NAME=sistema_policial_huancavelica
# DB_USER=root
# DB_PASS=<tu_contraseña>
```

### 2. Crear Base de Datos

```bash
# En MySQL/phpMyAdmin
CREATE DATABASE sistema_policial_huancavelica CHARACTER SET utf8mb4;
USE sistema_policial_huancavelica;
SOURCE C:/xampp/htdocs/sistema-policial/database/schema.sql;
SOURCE C:/xampp/htdocs/sistema-policial/database/seeds.sql;
```

### 3. Iniciar Servidor

**Opción A: XAMPP (Recomendado)**
```
1. Abrir XAMPP Control Panel
2. Hacer clic en "Start" para Apache
3. Ir a http://localhost/sistema-policial/public/
```

**Opción B: PHP CLI**
```bash
cd C:\xampp\htdocs\sistema-policial
php -S localhost:8000 -t public
# Ir a http://localhost:8000
```

### 4. Login Demo

```
Usuario: jefe_demo
Contraseña: demo123
Rol: Jefe
```

---

## 📁 Estructura del Proyecto

```
├── config/              # Configuración (DB, constantes, entorno)
├── routes/web.php       # Enrutador central (todas las rutas)
├── middleware/          # Autenticación, autorización, validaciones
├── models/              # Lógica de datos (Usuario, Incidencia, etc)
├── controllers/         # Lógica de presentación (organizados por rol)
│   ├── jefe/           # Acciones de Jefatura
│   ├── mesa/           # Acciones de Recepción
│   └── seincri/        # Acciones de Especialista
├── views/              # Templates HTML (organizados por rol)
├── helpers/            # Funciones auxiliares (validación, uploads, PDF)
├── public/             # Raíz web (index.php, CSS, JS, uploads)
└── database/           # Scripts SQL (schema, seeds, migrations)
```

**⚠️ Más detalle**: Ver [ARCHITECTURE.md](./ARCHITECTURE.md) para diagrama completo, patrones de diseño y justificación técnica.

---

## 🔑 Roles y Permisos

| Rol | Capacidades |
|-----|-----------|
| **Mesa** | Crear incidencias propias, editar si está Pendiente, ver propios registros |
| **Jefe** | Crear usuarios, asignar casos a SEINCRI, ver reportes, cambiar estados |
| **SEINCRI** | Ver casos asignados, registrar atención, descargar casos |

---

## 📝 Flujo Típico de Uso

```
1. Mesa (Recepcionista)
   └─> Accede a /mesa/registro
   └─> Completa formulario (denunciante, delito, localización con mapa)
   └─> Incidencia guardada en estado "Pendiente"

2. Jefe (Gestor)
   └─> Ve incidencia en dashboard (/jefe/dashboard)
   └─> Entra a /jefe/asignacion/{id}
   └─> Selecciona especialista SEINCRI
   └─> Incidencia pasa a "En Atención"

3. SEINCRI (Especialista)
   └─> Ve caso en /seincri/dashboard
   └─> Registra acta de atención (/seincri/atencion/{id})
   └─> Sube evidencias (fotos, informes)
   └─> Caso pasa a "Resuelto"

4. Jefe (Reporting)
   └─> Genera PDF (/jefe/reportes)
   └─> Filtra por período, zona, tipo delito
   └─> Descarga informe ejecutivo
```

---

## 🔐 Seguridad Implementada

✅ **Preparadas contra inyección SQL**: PDO prepared statements en todos los modelos  
✅ **Autenticación**: Sesiones PHP + Hash bcrypt para contraseñas  
✅ **Autorización**: Middleware de roles antes de cada acción  
✅ **Validación**: Entrada cliente + servidor con helper Validator  
✅ **Primer acceso**: Obliga cambio de contraseña temporal  

⚠️ **No implementado (Mejoras futuras)**:
- 2FA (TOTP)
- Rate limiting en login
- Audit log de cambios
- HTTPS forzado

---

## 🐛 Troubleshooting

### Error: "Base de datos no encontrada"
```
→ Verifica que creaste la BD en MySQL
→ Revisa credenciales en .env vs config/database.php
```

### Error: "Permisos denegados" en uploads
```powershell
# Windows PowerShell (como Admin)
icacls "public\uploads" /grant:r "%USERNAME%:F" /T
```

### Google Maps no carga geolocalización
```
→ Verifica que tienes internet conexión
→ Comprueba que el navegador permite Geolocation
→ Ingresa dirección manualmente como fallback
```

### Las rutas retornan 404
```
→ Verifica que Apache reescribe URLs (mod_rewrite activo)
→ Consulta .htaccess en public/
→ Prueba con php -S localhost:8000 (sin Apache)
```

---

## 📊 Estadísticas del Proyecto

| Métrica | Valor |
|---------|-------|
| **Líneas de PHP** | ~3,500 |
| **Líneas de SQL** | ~200 |
| **Hojas CSS** | 8 |
| **Archivos JS** | 12+ |
| **Modelos OOP** | 6 (Usuario, Incidencia, Asignacion, Evidencia, Reporte, Database) |
| **Controllers** | 15+ |
| **Rutas** | ~50 |
| **Tablas BD** | 8 (Usuarios, Incidencias, Personas, Asignaciones, Evidencias, Reportes, etc) |

---

## 🤝 Contribución

Para cambios significativos, abrir un issue primero.

```bash
git checkout -b feature/nueva-feature
git add .
git commit -m "Agregar nueva feature"
git push origin feature/nueva-feature
```

---

## 📖 Documentación Extendida

- **[ARCHITECTURE.md](./ARCHITECTURE.md)**: Diseño técnico, patrones, retos y soluciones
- **[database/schema.sql](./database/schema.sql)**: Estructura completa de base de datos
- **[routes/web.php](./routes/web.php)**: Todas las rutas y middlewares

---

## 📌 Roadmap

- [x] Login y autenticación por rol
- [x] CRUD incidencias con geolocalización
- [x] Asignación de casos
- [x] Reportes en PDF
- [ ] API REST (v2)
- [ ] App móvil (React Native)
- [ ] 2FA TOTP
- [ ] Chat interno de casos
- [ ] Integración RENIEC

---

## 📧 Contacto

**Autor**: [TU NOMBRE]  
**Email**: [tu@email.com]  
**GitHub**: [@tuusuario](https://github.com/tuusuario)  

---

**Última actualización**: 15 de Febrero de 2026  
**Versión**: 1.0.0

