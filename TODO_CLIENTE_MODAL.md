# TODO: Flujo modales para Cliente en REGISTRAR NUEVA VENTA

- [ ] Revisar y ajustar `registroVentas.blade.php` para incorporar botón **Buscar por C.I.** y 2 modales (buscar/no encontrado y registro).
- [ ] Crear endpoint en `VentaController` para **buscar cliente por DNI** usando columna `cliente.dni`.
- [ ] Registrar ruta en `routes/web.php` para el endpoint del buscador.
- [ ] Conectar AJAX del buscador para:
    - [ ] Si existe cliente: seleccionar `#clienteSelect` y cerrar modal 1.
    - [ ] Si no existe: mostrar aviso y habilitar apertura de modal 2.
- [ ] Adaptar AJAX del guardado del modal 2 para:
    - [ ] Insertar cliente.
    - [ ] Seleccionar inmediatamente el nuevo cliente en `#clienteSelect`.
    - [ ] Cerrar modal 2.
- [ ] Probar manualmente:
    - [ ] Buscar DNI existente.
    - [ ] Buscar DNI inexistente y registrar.
    - [ ] Confirmar que al guardar no recarga la página y la venta queda lista.
