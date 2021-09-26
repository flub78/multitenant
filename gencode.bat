@rem Models
@rem php artisan mustache:generate users Model.php.mustache app/Models/UserModel.php

@rem Create form
@rem php artisan mustache:generate --verbose users resources/views/tenants/create_view.blade.php.mustache resources/views/users/create.blade.php
@remphp artisan mustache:generate --verbose roles resources/views/tenants/create.blade.php.mustache resources/views/tenants/role/create.blade.php

@rem Edit form
php artisan mustache:generate --verbose roles resources/views/tenants/edit.blade.php.mustache resources/views/tenants/role/edit.blade.php

@rem index.blade.php
@rem php artisan mustache:generate --verbose users resources/views/tenants/index.blade.php.mustache resources/views/users/index.blade.php
@rem php artisan mustache:generate --verbose roles resources/views/tenants/index.blade.php.mustache resources/views/tenants/role/index.blade.php
