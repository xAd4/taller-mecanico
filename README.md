# 🛠️ Sistema para Taller Mecánico (Backend)

**Backend** desarrollado en PHP para la gestión de un taller mecánico. Proporciona una API RESTful con autenticación JWT y control de roles.

---

## 👨💼 Dashboards y Funcionalidades

### 1. **Dashboard del Jefe de Taller** (`/jefe/**`)

-   **Gestión Maestra**:
    -   🧑🤝🧑 **Clientes**: CRUD completo de clientes (nombre, apellido, email, dni, RUT, teléfono y domicilio).
    -   🚗 **Vehículos**: Registro de vehículos con detalles técnicos (modelo, marca, color, matrícula, kilometraje, numero de serie, numero de motor y fecha de compra).
    -   📑 **Órdenes de Trabajo**:
        -   Creación de órdenes vinculando cliente + vehículo.
        -   Campos para datos extras que habla el estado de entrada del vehiculo, fecha de recepcion, fecha prometida, checkboxs de cambio de aceites y de filtro si aplica y detalles extras.
    -   📦 **Inventario**:
        -   Categorías de productos con su respectiva categoria (ej: "Lubricantes", "Frenos").
        -   Productos con stock, precios y disponibilidad.
    -   👥 **Usuarios**: Creación de mecánicos y otros jefes.

### 2. **Dashboard del Mecánico** (`/mecanico/**`)

-   **Tareas Asignadas**:
    -   ✅ Listado de tareas por estado (`pendiente`, `en_proceso`, `completado`).
    -   ⚙️ Actualización en tiempo real del estado de las tareas.
-   **Registro Técnico**:
    -   🔧 **Componentes del Vehículo**:
        -   Tren delantero/trasero.
        -   Frenos.
        -   Neumáticos.
    -   📊 **Materiales Usados**:
        -   Selección de productos del inventario.
        -   Cálculo automático de costos (precio unitario \* cantidad).

### 3. **Consulta Pública para Clientes** (`/consulta`)

-   🔍 **Búsqueda por Matrícula**:
    -   Visualización del estado actual del vehículo.
    -   Detalles de facturación (pendiente/pagado).
-   🔒 **Sin Autenticación**: Acceso directo mediante matrícula.

## 🛠️ Tecnologías Utilizadas

-   **Laravel 12** (PHP 8.2)
-   **MySQL** (Motor de base de datos)
-   **Eloquent ORM** (Gestión de relaciones DB)
-   **Laravel Sanctum** (Autenticación API)
-   **Postman** (Documentación y testing)

---

## 🔒 Consideraciones de Seguridad

-   **Middlewares**:
    -   `AutorizacionJefe`: Restringe acceso solo a usuarios con rol `jefe`.
    -   `AutorizacionMecanico`: Restringe acceso solo a usuarios con rol `mecanico`.
    -   `ChecarRol`: Verifica roles en cada endpoint.
-   **Transacciones DB**:
    -   Operaciones críticas (ej: actualización de stock) usan transacciones atómicas.
-   **Rate Limiting**:
    -   Límite de 100 peticiones por minuto por IP en endpoints públicos para evitar ataques de fuerza bruta.

---
