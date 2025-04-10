**Contexto General del Proyecto:**  
Se requiere requiere una **aplicación web integral y responsive** para optimizar la gestión de un taller mecánico, digitalizando procesos manuales y mejorando la comunicación entre el jefe de operaciones, los mecánicos y los clientes. La solución debe operar en un **servidor local** con base de datos integrada, estar en español y adaptarse al flujo de trabajo actual del taller, basado en los formularios físicos adjuntos.

---

### **Objetivos Principales:**

1. **Digitalizar órdenes de trabajo** según el formato físico proporcionado (`HojaDeCliente.jpg`).
2. **Facilitar la colaboración interna**:
    - Jefe de operaciones: Registra vehículos, asigna tareas y gestiona recursos.
    - Mecánicos: Ejecutan tareas, documentan materiales y verifican procesos.
3. **Mejorar la experiencia del cliente**: Permitir consultas en tiempo real sobre el estado de su vehículo.

---

### **Funcionalidades Clave:**

#### **1. Para el Jefe de Operaciones (Dashboard Administrativo):**

-   **Carga de órdenes**:
    -   Datos del cliente (nombre, RUT, teléfono, domicilio).
    -   Detalles del vehículo (modelo, matrícula, kilometraje, número de serie).
    -   **Campo adicional**: Descripción de daños externos (ej: rayones, abolladuras).
-   **Gestión de materiales**:
    -   Añadir/editar materiales (nombre, precio unitario, stock. Se estima que esto lo manejen otros trabajadores).
-   **Gestión de usuarios**:
    -   CRUD de usuarios (mecánicos y otros jefes).
-   **Edición de órdenes**:
    -   Modificar estados (ej: "En revisión" → "Aprobado"), asignar mecánicos, corregir datos.

#### **2. Para los Mecánicos (Dashboard Operativo):**

-   **Vista de órdenes asignadas**:
    -   Lista de tareas pendientes (ej: "Cambio de aceite", "Revisión de frenos").
    -   **Registro de materiales**:
        -   Tabla dinámica para añadir materiales usados (cantidad editable, precios bloqueados).
        -   Cálculo automático de costos (total por ítem y suma final).
    -   **Checkboxes de verificación**:
        -   Tareas predefinidas (ej: "Neumáticos revisados") + comentarios opcionales.
-   **Restricciones**:
    -   Solo puede editar campos relacionados a su trabajo (materiales usados y checkboxes).

#### **3. Para los Clientes (Acceso Público):**

-   **Consulta por matrícula**:
    -   Estado del vehículo (ej: "En proceso", "Listo para retiro", "Por pagar").
    -   Información básica: Fecha de ingreso, trabajos realizados.
-   **Sin autenticación**: Solo requiere la matrícula como identificador.

---

### **Requisitos Técnicos:**

#### **Frontend:**

-   **Tecnologías**: HTML/CSS/JavaScript con React.js + Bootstrap (para responsividad y dinamismo).
-   **Interfaces**:
    -   Dashboard del jefe: Paneles de gestión con tablas y formularios.
    -   Dashboard del mecánico: Vista simplificada para tabletas.
    -   Consulta cliente: Página pública minimalista.
    -   Posible dashboard para que ciertos empleados puedan editar los productos.

#### **Backend:**

-   **Tecnologías**: PHP con Laravel.
-   **Funcionalidades**:
    -   Autenticación por roles (jefe vs. mecánico).
    -   Cálculos automáticos (ej: total de materiales).
    -   Generación de estados para clientes.

#### **Base de Datos:**

-   **Motor**: MySQL con Eloquent.
-   **Tablas principales**:
    -   `Clientes`, `Vehículos`, `Órdenes`, `Materiales`, `Usuarios`, `ChecklistMecanico`, `Entre otros`.
-   **Relaciones**:
    -   Una orden pertenece a un cliente y un vehículo.
    -   Un mecánico puede tener múltiples órdenes asignadas.

#### **Despliegue:**

-   **Entorno local**: XAMPP (PHP/MySQL).
-   **Documentación**: Instrucciones claras para instalar y ejecutar la aplicación.

---

### **Entregables Finales:**

1. **Código fuente completo**:
    - Frontend, backend y scripts de base de datos.
2. **Documentación técnica**:
    - Configuración inicial, estructura de la base de datos, roles de usuario.

---

### **Ejemplo de Flujo de Trabajo:**

1. **Recepción**:
    - Jefe ingresa datos del cliente + vehículo, incluyendo "rayones".
    - Crea una orden con estado "En revisión".
2. **Reparación**:
    - Mecánico ve la orden en su tablet, añade materiales (ej: 3 litros de aceite) y marca checkboxes.
    - El estado se actualiza a "En proceso".
3. **Finalización**:
    - Jefe aprueba la orden y cambia el estado a "Listo para retiro".
4. **Consulta del cliente**:
    - Ingresa su matrícula en la página pública y ve "Listo para retiro" o el estado que toque en ese momento.
