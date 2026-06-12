# Auditoria UI

## Contexto

La aplicacion usa SmartAdmin sobre Bootstrap 4, DataTables, Select2, FullCalendar y helpers propios en `public/css/estilo.css`.

El tipo de producto es operativo: gestion de dispensas/vacaciones, honorarios/facturas, reservas de espacios y autorizaciones por rol. La UI deberia priorizar lectura rapida, acciones claras, estados visibles y poca decoracion.

Referencias usadas:

- Carbon Design System - Data table usage: https://carbondesignsystem.com/components/data-table/usage/
- Bootstrap 4.6 - Tables: https://getbootstrap.com/docs/4.6/content/tables/

## Hallazgos

- Hay 16 vistas usando `id="mytable"` y el layout inicializa un unico selector global `#mytable`. Esto limita multiples tablas por pantalla y acopla cada vista al layout.
- Hay 17 alertas informativas repetidas con textos largos. Muchas explican como usar la pantalla en lugar de reforzar estado, resultado o riesgo.
- Cinco vistas vuelven a cargar jQuery desde CDN aunque el layout ya carga `vendors.bundle.js`.
- Varias vistas repiten la estructura breadcrumb + alerta + panel de acciones + panel de tabla.
- Se repiten clases legacy invalidas como `col xs 12 col sm 12`.
- Hay textos mezclados o poco profesionales: `Info!`, `Atras`, `Click aqui`, `Close`, errores de ortografia y acentos ausentes.
- Los estados se comunican casi siempre con iconos sueltos, sin etiqueta textual. Esto dificulta escaneo, accesibilidad y soporte para usuarios nuevos.
- Las acciones de fila usan botones iguales para cosas muy distintas: ver, cargar, eliminar, resolver. Falta jerarquia visual.
- Los calendarios de periodo aparecen como tablas densas sin leyenda persistente. La primera mejora ya aplicada en `periodo/create` puede convertirse en patron comun.
- El layout global inicializa FullCalendar en todas las paginas, aunque solo se use en modal/calendario. Esto no es solo visual: impacta performance percibida y acoplamiento.

## Criterios visuales propuestos

- Mantener SmartAdmin/Bootstrap como base para no introducir otro framework.
- Crear una capa UI propia chica y estable: page header, metric strip, table toolbar, status badge, icon action button y calendario de dias.
- Cambiar alertas informativas permanentes por encabezados y subtitulos de pagina; reservar alerts para exito, error o advertencias reales.
- Usar tablas con titulo, descripcion breve, buscador/filtros visibles y acciones en toolbar cuando aplique.
- Mostrar estados con badge + texto: `Pendiente`, `Aprobado`, `Rechazado`, `Facturado`, `Sin factura`.
- Mantener acciones frecuentes visibles como botones con icono y tooltip; agrupar acciones destructivas o menos frecuentes.
- Mejorar copy funcional: verbos concretos, acentos, nombres consistentes de modulos y botones.
- Evitar paleta monotono-violeta heredada. Usar neutros claros, verde/teal para seleccion/positivo, amarillo suave para pendiente y rojo solo para rechazo/error.
- Priorizar responsive real: tablas con contenedor responsive, columnas esenciales, acciones compactas e informacion secundaria en detalle.

## Primeros componentes a extraer

1. `components.page-header`
   - Breadcrumb, titulo, subtitulo y acciones principales.
   - Reemplaza bloques repetidos de breadcrumb + alerta + panel de botones.

2. `components.metric-strip`
   - Tarjetas compactas para numeros de periodo, usados, extras, pendientes.
   - Ya existe una version inicial en `periodo/create`.

3. `components.status-badge`
   - Recibe estado numerico y contexto (`dispensa`, `espacio`, `factura`).
   - Devuelve color, icono y texto.

4. `components.table-panel`
   - Panel con titulo, descripcion, toolbar y slot de tabla.
   - Permite migrar `#mytable` a clases como `.js-data-table`.

5. `components.icon-action`
   - Boton icon-only consistente para ver, subir, descargar, eliminar, aprobar, rechazar.
   - Siempre con tooltip/titulo.

6. `components.period-calendar`
   - Calendario de dias con leyenda y estados: disponible, seleccionado, bloqueado, usado.
   - Reusable para `periodo.show`, `periodo.create`, `showuser` y `estadoanual`.

## Orden recomendado

1. Dispensas usuario
   - `periodo.show`: alinear con la nueva pantalla de solicitud.
   - `periodo.misSolicitudes`: tabla con badges de autorizacion y acciones claras.
   - `periodo.detail`: detalle con timeline simple de autorizacion.

2. Autorizaciones
   - `lista2` y `lista3`: bandeja de pendientes con estado, solicitante, dias y accion primaria.
   - `definir2` y `definir3`: pantalla de decision con resumen, dias solicitados, adjunto y form de resolucion mas claro.

3. Honorarios/facturas
   - `honorario.listado`, `factura.misFacturas`, `honorario.index`, `factura.index`.
   - Cambiar iconos sueltos por estados y acciones explicitas.

4. Espacios
   - `espacio.listado`, `autorizar`, `definir`.
   - Unificar estados de reserva y acciones destructivas.

5. Layout y assets
   - Agregar `@stack('scripts')` y mover inicializadores por pantalla.
   - Cambiar `#mytable` por `.js-data-table`.
   - Remover jQuery CDN duplicado.
   - Inicializar FullCalendar solo cuando exista `#calendar`.

## Riesgos

- Si se refactoriza el layout antes de las vistas criticas, puede romper muchas pantallas a la vez.
- Si se cambia DataTables globalmente sin migrar vistas, puede dejar tablas sin buscador/paginacion.
- Si se reemplazan iconos por badges sin una funcion central, cada modulo volvera a tener su propia version de estados.

## Proximo corte recomendado

Implementar los componentes minimos (`page-header`, `status-badge`, `table-panel`) y migrar `periodo.show` + `periodo.misSolicitudes`. Es un corte acotado, visible y con bajo riesgo porque ya estabilizamos el backend de dispensas.
