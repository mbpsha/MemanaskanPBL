# Setup

## Install
```bash
composer install
npm install
composer require tightenco/ziggy
```

## Run
```bash
php artisan serve    # Terminal 1
npm run dev          # Terminal 2
```

## Ziggy Setup (untuk route helper)
Setelah install Ziggy, update `resources/js/app.js`:

```javascript
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
app.use(ZiggyVue);
```
