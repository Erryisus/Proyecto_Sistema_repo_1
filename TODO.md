# TODO - Módulo Materia Prima (Laravel)

- [x] Crear modelos Eloquent: UnidadMedida, MateriaPrima, TipoMovimiento, MovimientoMateria con relaciones correctas.
- [x] Crear controladores: MateriaPrimaController (CRUD) y MovimientoMateriaController (registro de movimientos + historial, con transacción y ajuste de existencia_actual).
- [x] Registrar rutas en routes/web.php para CRUD y movimientos.
- [x] Crear vistas Blade:
    - [x] Materias Primas: index (tabla inventario), registroMateriaPrima (create/edit)
    - [x] Movimientos: indexMovimientosMateria (historial), registroMovimientoMateria (form)
- [x] Asegurar compatibilidad con layout `resources/views/layouts/app.blade.php` y estilo Bootstrap/DataTables.
- [x] Validar lógica de stock: existencia_anterior/nueva según `tipo_movimiento.afecta_stock`.
- [x] Probar flujo completo desde navegador (crear materia prima -> registrar movimiento -> verificar existencia).
