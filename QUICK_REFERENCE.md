# Quick Reference

## Start
```bash
php artisan serve    # Terminal 1
npm run dev          # Terminal 2
```

## Controller → Vue
```php
use Inertia\Inertia;

return Inertia::render('Events/Index', [
    'events' => Event::all()
]);
```

```vue
<script setup>
defineProps({ events: Array });
</script>
```

## Form
```vue
<script setup>
import { useForm } from '@inertiajs/vue3';
const form = useForm({ name: '' });
const submit = () => form.post(route('events.store'));
</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.name" />
        <span v-if="form.errors.name">{{ form.errors.name }}</span>
        <button :disabled="form.processing">Save</button>
    </form>
</template>
```

## Navigation
```vue
<Link :href="route('events.index')">Events</Link>
<Link :href="route('logout')" method="post">Logout</Link>
```

## Data
```vue
{{ $page.props.auth.user.name }}
{{ $page.props.flash.message }}
```
