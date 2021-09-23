@rem php artisan mustache:generate users Model.php.mustache app/Models/UserModel.php
@rem php artisan mustache:generate --verbose users resources/views/tenants/create_view.blade.php.mustache resources/views/users/create.blade.php
php artisan mustache:generate --verbose users resources/views/tenants/index.blade.php.mustache resources/views/users/index.blade.php
