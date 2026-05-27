# TODO: Fix Profile Photo Display

## Steps:
- [x] Step 1: Run `php artisan storage:link` to create public/storage symlink. (Already exists)
- [x] Step 2: Update app/Models/Usuario.php - Add 'foto' to $fillable.
- [x] Step 3: Refactor app/Http/Controllers/PerfilController.php to use Eloquent (secure, no raw SQL).
- [x] Step 4: Enhance img in resources/views/layouts/app.blade.php (navbar).
- [x] Step 5: Minor fixes in resources/views/vistas/perfil.blade.php.
- [x] Step 6: Test upload/display, clear caches. (Cleared)
- [x] Step 7: Mark complete.

Current: Starting Step 1.
