---
name: tailwind-specificity
description: Maneja la especificidad de Tailwind v4 en entornos de WordPress usando el sufijo de importancia y patrones de dimensionamiento estricto.
---

# **Skill: Tailwind v4 Specificity (Importance Suffix)**

Cuando se trabaja en entornos de WordPress, los estilos del tema o de plugins externos pueden sobrescribir las utilidades de Tailwind CSS v4 debido a una mayor especificidad de CSS.

## **Instrucción**

Cuando el usuario solicite "estilos irrompibles" o cuando encuentres conflictos de estilo con el tema activo de WordPress, **DEBES** aplicar el sufijo de importancia (`!`) al **final** de cada clase de utilidad.

### **Reglas**
1.  **Colocación del Sufijo**: Coloca el `!` al final del nombre de la utilidad (sintaxis de Tailwind v4).
    *   **Correcto**: `bg-red-500!`
    *   **Incorrecto**: `!bg-red-500`
2.  **Interactividad**: Aplícalo también a pseudo-selectores y estados.
    *   **Ejemplo**: `hover:bg-blue-600!`, `focus:ring-2!`, `md:text-lg!`
3.  **Alcance**: Úsalo solo cuando se solicite o cuando las utilidades estándar estén siendo ignoradas por el navegador debido a conflictos de cascada.

## **Uso de Ejemplo**

```html
<!-- Estándar (Puede ser sobrescrito por el tema) -->
<div class="bg-white p-4 text-gray-900">...</div>

<!-- Irrompible (Gana la cascada) -->
<div class="bg-white! p-4! text-gray-900!">...</div>
```

## **Strict Sizing (Integridad del Layout)**

En temas de WordPress altamente restrictivos, las clases de tamaño simples (como `w-8 h-8`) pueden ser ignoradas o sobrescritas parcialmente. Para asegurar la integridad total del diseño, **DEBES** aplicar el patrón de "Dimensionamiento Estricto":

**Patrón**: Usa `w-{n}! h-{n}! min-w-{n}! min-h-{n}! max-w-{n}! max-h-{n}!` simultáneamente.

### **Ejemplo**
```html
<!-- Botón cuadrado estricto de 32px (8 unidades) -->
<button class="w-8! h-8! min-w-8! min-h-8! max-w-8! max-h-8! p-0!">...</button>
```

Usa este patrón para iconos, botones pequeños y avatares de tamaño fijo para evitar que el tema "estire" o "encoja" tus componentes de UI.
