# Frontend Setup - Laravel + Vue + Inertia

## Start Development
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

## Upload Gambar
Simpan gambar di: `public/images/`
- `public/images/hero-bg.jpg` - Hero background
- `public/images/logo-white.png` - Logo footer

Gambar langsung bisa diakses di Vue:
```vue
<img src="/images/hero-bg.jpg" />
```

## Controller untuk Landing Page
```php
use Inertia\Inertia;

public function index()
{
    return Inertia::render('Landing');
}
```

Route:
```php
Route::get('/', [HomeController::class, 'index']);
```
