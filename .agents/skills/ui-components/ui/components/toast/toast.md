---
name: 'toast'
---

# Toast Notifications Component

## Introduction

The `toasts` component provides a lightweight, accessible, and customizable toast message system built with Alpine.js and Tailwind CSS. It supports multiple toast types (`info`, `success`, `error`, `warning`), auto-dismiss with progress bars, hover-to-pause functionality, keyboard-accessible close buttons, and configurable max visible toasts.

This component listens to a global `notify` event, enabling toast triggering from anywhere in your app without prop drilling.

## Installation


```bash
```
then put the `<x-ui.toast />` in your global layout file like so:

```html
<!-- this is your base layout file -->
<x-ui.toast />
```

## Basic Usage

@blade
<x-demo>
    <div x-data class="flex items-center justify-center">
    <x-ui.button
        x-on:click="$dispatch('notify', {
            type: 'success',
            content: 'Operation successful!',
            duration: 6000
        })"
    >
        show notification
    </x-ui.button>
    </div>
</x-demo>
@endblade

```html
<div x-data>
  <button
    x-on:click="$dispatch('notify', {
      type: 'success',
      content: 'Operation successful!',
      duration: 6000
    })"
  >
    Show Success Toast
  </button>
</div>
```

## Variants
@blade
<x-demo>
<div 
    x-data
    class="flex items-center justify-center gap-2"
>
    <button 
        x-on:click="$dispatch('notify', { type: 'success', content:'Success toast', duration: 6000 })"
        class="py-2 px-4 bg-green-500/15 cursor-pointer rounded-xl dark:text-white text-green-500"
    >
        Success
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'info', content:'Info toast', duration: 6000 })"
        class="py-2 px-4 bg-gray-300 dark:bg-white/5 cursor-pointer rounded-xl dark:text-white text-gray-500"
    >
        Info
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'error', content:'Error toast', duration: 6000 })"
        class="py-2 px-4 bg-red-500/15 cursor-pointer rounded-xl dark:text-white text-red-500"
    >
        Error
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'warning', content:'Warning toast', duration: 6000 })"
        class="py-2 px-4 bg-yellow-500/15 cursor-pointer rounded-xl dark:text-white text-yellow-500"
    >
        Warning
    </button>
</div>
</x-demo>
@endblade

```html
<div 
    x-data
    class="flex items-center justify-center gap-2"
>
    <button 
        x-on:click="$dispatch('notify', { type: 'success', content:'Success toast', duration: 6000 })"
        class="py-2 px-4 bg-green-500/15 rounded-xl dark:text-white text-green-500"
    >
        Success
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'info', content:'Info toast', duration: 6000 })"
        class="py-2 px-4 bg-white rounded-xl dark:text-white text-gray-500"
    >
        Info
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'error', content:'Error toast', duration: 6000 })"
        class="py-2 px-4 bg-red-500/15 rounded-xl dark:text-white text-red-500"
    >
        Error
    </button>
    <button 
        x-on:click="$dispatch('notify', { type: 'warning', content:'Warning toast', duration: 6000 })"
        class="py-2 px-4 bg-yellow-500/15 rounded-xl dark:text-white text-yellow-500"
    >
        Warning
    </button>
</div>
```
### How To Use 

Place the toast container somewhere in your page (usually root layout):

```blade
<x-ui.toast position="bottom-right" maxToasts="5" />
```
#### Use With Livewire
you can use livewire to show the toast, here is an example 

```php
use Livewire\Component;
 
class CreatePost extends Component
{
    public function save()
    {
        // ...
 
        $this->dispatch('notify',
            type: 'success',
            content:'post saved successfully',
            duration: 4000
        ); 
    }
}
```
#### Use With Alpine.js 

```html
<button
    @click="$dispatch('notify', {
        type: 'success',
        content: 'This is a success toast!',
        duration: 3000,
    })"
>
    Show Success Toast
</button>
```

#### Use Raw Javascript

```js
window.dispatchEvent(
    new CustomEvent('notify', {
        detail: {
            type: 'success',
            content: 'This is a success message!',
            duration: 3000 
        }
    })
);
```
#### From Backend

You can create toasts from your backend using Laravel's `session()->flash()` helper:

```php
session()->flash('notify', [
    'content' => 'Operation completed successfully!',
    'type' => 'success', // optional if type is info.
    'duration' => 5000 // optional
]);
```

**Available Keys:**
- `content` (required) - The toast message text
- `type` (optional) - Toast variant: `info` (default), `success`, `error`, `warning`  
- `duration` (optional) - Display duration in milliseconds (default: 4000ms)

**Example Use Cases:**

**After User Logout:**
```php
<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    public function __invoke(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('notify', [
            'content' => 'You have been logged out successfully',
            'type' => 'success'
        ]);

        return redirect()->route('home');
    }
}
```

**Form Validation Errors:**
```php
// In your controller
if ($validator->fails()) {
    session()->flash('notify', [
        'content' => 'Please fix the validation errors',
        'type' => 'error',
        'duration' => 6000
    ]);
    
    return redirect()->back()->withErrors($validator);
}
```

**After Data Operations:**
```php
// After creating a record
$post = Post::create($validatedData);

session()->flash('notify', [
    'content' => 'Post created successfully!',
    'type' => 'success'
]);

return redirect()->route('posts.index');
```

> **Note:** The toast system uses `session()->pull()` on the frontend to retrieve and display flashed toast data, ensuring toasts appear only once per session flash.
## Toast Types and Styling

Supports types:

* `info`
* `success`
* `error`
* `warning`

Each type has its own colors and icons for light and dark modes, using Tailwind and color-mix for theme consistency.

## Creating Class and Trait Helpers

Laravel applications typically need toast notifications in two scenarios:

1. **Real-time notifications** - For Livewire actions like `saveChanges()`, `updateProfile()`, etc.
2. **Post-redirect notifications** - After operations like login, registration, or form submissions that cause redirects

Our helper classes handle both cases elegantly using traits for Livewire components and a static class for controllers.

> **Note:** If you're using our starter kit, this implementation is already included.

### Livewire Trait


For real-time toast notifications in Livewire components, create a trait:

```php
<?php
// app/Livewire/Concerns/HasToast.php

namespace App\Livewire\Concerns;

trait HasToast
{
    /**
     * Dispatch a success toast notification
     */
    public function toastSuccess(string $content): void
    {
        $this->toast($content, 'success');
    }

    /**
     * Dispatch a warning toast notification
     */
    public function toastWarning(string $content): void
    {
        $this->toast($content, 'warning');
    }

    /**
     * Dispatch an error toast notification
     */
    public function toastError(string $content): void
    {
        $this->toast($content, 'error');
    }

    /**
     * Dispatch an info toast notification
     */
    public function toastInfo(string $content): void
    {
        $this->toast($content, 'info');
    }

    /**
     * Dispatch a toast notification
     */
    public function toast(string $content, string $type = 'info'): void
    {
        $this->dispatch('notify', 
            type: $type,
            content: $content,
            duration: 4000
        );
    }
}
```

**Usage in Livewire Components:**

Add the trait to your Livewire components and use the convenient methods:

```php
<?php

namespace App\Livewire\Settings;

use App\Livewire\Concerns\HasToast;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Livewire\Component;

class Account extends Component
{
    use HasToast;

    public function saveChanges(#[CurrentUser] User $user)
    {   
        // Your validation and update logic here
        $validated = $this->validate([...]);
        $user->update($validated);

        // Show success toast
        $this->toastSuccess('Your account has been updated.');
    }

    public function deleteAccount()
    {
        // Deletion logic here
        
        $this->toastWarning('Your account has been deleted.');
    }
}
```

### PHP Class for Session-Based Toasts

For controllers and redirect scenarios, create a static Toast class:

```php
<?php
// app/Support/Toast.php

namespace App\Support;

use Illuminate\Support\Facades\Session;

final class Toast
{
    public static function success(string $content): void
    {
        static::add($content, 'success');
    }

    public static function warning(string $content): void
    {
        static::add($content, 'warning');
    }

    public static function error(string $content): void
    {
        static::add($content, 'error');
    }

    public static function info(string $content): void
    {
        static::add($content, 'info');
    }

    public static function add(string $content, string $type): void
    {
        Session::flash('notify', [
            'content' => $content,
            'type' => $type
        ]);
    }
}
```

**Usage in Controllers:**

Use the Toast class in controllers or any code that performs redirects:

```php
<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Support\Toast;

class AuthController extends Controller
{
    public function store()
    {
        // Login logic...
        
        Toast::success('You have successfully logged in!');
        
        return redirect()->intended(route('dashboard'));
    }

    public function destroy()
    {
        // Logout logic...
        
        Toast::info('You have been logged out.');
        
        return redirect()->route('home');
    }
}
```

**Usage in Livewire (with redirects):**

```php
<?php
// app/Livewire/Auth/Login.php

namespace App\Livewire\Auth;

use App\Support\Toast;
use Livewire\Component;

class Login extends Component
{
    public function login()
    {
        // Login validation and logic...
        
        Toast::success('You have successfully logged in!');
        
        $this->redirectIntended(
            default: route('dashboard', absolute: false), 
            navigate: true
        );
    }
}
```

### When to Use Which Approach

- **Use the HasToast trait** for real-time notifications within Livewire components (no page reload)
- **Use the Toast class** for notifications that need to persist through redirects (login, logout, form submissions)

Both approaches work seamlessly with the same toast component, providing a consistent user experience across your entire application.

## Customization

### Positioning

Set the toast container position:

```html
<x-ui.toast position="top-left" />
```

### Max Toasts

Control maximum visible toasts via `maxToasts` prop:

```blade
<x-ui.toast maxToasts="3" />
```

### Progress Bar

You can make the progress bar thin by using the `progressBarVariant` attribute:
```html
<x-ui.toast 
    progressBarVariant="thin" 
/>
```

You can align the progress bar to the top by using the `progressBarAlignment` attribute:

```html
<x-ui.toast 
    progressBarVariant="thin"
    progressBarAlignment="top" 
/>
```




### Notes

Toasts dismiss automatically after a duration (default 4000ms). Progress bar shows remaining time.
Hover pauses dismissal and progress animation.

> I (mohamed) actually believe this toasts system working for all kind of tasts (after actions) in laravel, is deadly simple and powerfull, if you find any cases where it doesn't open new issue and let the work to me.  




## Component Props

| Prop Name   | Type    | Default          | Required | Description                                                 |
| ----------- | ------- | ---------------- | -------- | ----------------------------------------------------------- |
| `position`  | string  | `'bottom-right'` | No       | Toast container position (`bottom-right`, `top-left`, etc.) |
| `maxToasts` | integer | `5`              | No       | Maximum number of concurrent visible toasts                 |
