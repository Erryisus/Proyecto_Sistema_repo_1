# TODO: Reemplazo dni -> cedula (clientes + flujo ventas)

- [x] ClienteController.php: cambiar validación/insert/update de columna `dni` -> `cedula`.
- [x] ClienteController.php: renombrar flags/query `venta_dni` -> `venta_cedula` y recuperar `cedula`.
- [x] Clientes Blade: create.blade.php (input `dni` -> `cedula`, hidden `venta_dni` -> `venta_cedula`).
- [x] Clientes Blade: edit.blade.php (input `dni` -> `cedula`, errores correspondientes).
- [x] Clientes Blade: index.blade.php (mostrar `cedula`).
- [ ] VentaController.php (buscar por C.I.): corregir variables y formateo tras cambios automáticos (verificar `buscarClientePorDni`).
- [ ] VentaController.php: asegurar que responda `cedula` en JSON (y no `dni`) y que use `where('cedula', ...)`.
- [ ] VentaController.php: storeCliente(Request): renombrar validación/insert de `txtdni` -> `txtcedula` y ajustar indentación.
- [x] registroVentas.blade.php: inputs `txtdni` -> `txtcedula` (modal buscar y modal nuevo cliente).
- [ ] registroVentas.blade.php: corregir JS restantes (variables `dni`, rutas/queries `venta_dni` -> `venta_cedula`, setClienteSeleccionado con `res.cedula`).
- [ ] Revisar rutas/JS de endpoints: `venta.clienteBuscarPorDni` (aunque el método mantenga nombre, debe leer `txtcedula`).
- [ ] Completar búsqueda adicional en vistas/handlers del módulo de clientes/ventas para dejar **cero** referencias a `dni`.
