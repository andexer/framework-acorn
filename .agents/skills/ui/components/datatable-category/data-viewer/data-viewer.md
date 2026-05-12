---
name:  'faqs-accordion'
files:
    index: resources/views/components/accordion/index.blade.php
    item: resources/views/components/accordion/item.blade.php
    usage: resources/views/components/accordion/usage.blade.php
---


### docs
irst up, let's set up an example model for `App\Models\Order` to interact with the `orders` table more easily:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Number;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $casts = [
        'status' => Status::class,
        'ordered_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAvatarAttribute()
    {
        return 'https://i.pravatar.cc/300?img=' . ((string) crc32($this->email))[0];
    }

    public function formatedDate()
    {
        return $this->ordered_at->format(
            $this->ordered_at->year === now()->year
                ? 'M d, g:i A'
                : 'M d, Y, g:i A'
        );
    }

    public function formattedAmount()
    {
        return Number::currency($this->amount);
    }

    public function archive()
    {
        $this->update([
            'is_archived' => true,
        ]);
    }
}
```
as you saw we cast  the status to the status `App\Enums\Status` Enums to make reusable and clean to work with how ever this is how the `status` class look like 

```php
<?php
namespace App\Enums;

enum Status :string {
    case Delivered = 'delivered';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';
    case Returned = 'returned';

    public function name()
    {
        return match ($this) {
            static::Delivered => 'Delivered',
            static::Shipped => 'shipped',
            static::Processing => 'Processing',
            static::Cancelled => 'Cancelled',
            static::Returned => 'Returned',
        };
    }

    public function icon()
    {
        return match ($this) {
            static::Delivered => 'icons.check-badge',
            static::Shipped => 'icons.shopping-cart',
            static::Processing => 'icons.arrow-path',
            static::Cancelled => 'icons.x-cercle',
            static::Returned => 'icons.arrow-uturn-left',
        };
    }
    public function color()
    {
        return match ($this) {
            static::Delivered => 'green',
            static::Shipped => 'blue',
            static::Processing => 'orange',
            static::Cancelled => 'red',
            static::Returned => 'purple',
        };
    }
}
```
the status enums is straigth forward provide 5 case, the `name` method used like a label .... 

 and we cast the `ordered_at` to date datatype to make enjoyable to work with.
 ### Product Model
 ```php
 <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;
    
    protected $guarded = [];
}
 ```

### orders migration 
```php
    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```
### orders migrations
```php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('number');
            $table->string('email');
            $table->integer('amount');
            $table->string('status');
            $table->timestamp('ordered_at');
            $table->bigInteger('product_id');
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
```
then run `php artisan migrate` to migrate the those migration
 you want to also to seed some demo data?
 no problem there is an example, in your `OrderFactory` paste this definition example function 
 
 ```php
 public function definition(): array
    {
      $biasedIdx = fake()->biasedNumberBetween(1, 6, fn($i) => 1 - sqrt($i));
      $statuses = ['returned', 'cancelled', 'shipped', 'processing'];

        return [
            'number' => fake()->randomNumber(5, strict: true),
            'email' => fake()->unique()->safeEmail(),
            'amount' => fake()->randomNumber(3, strict: false),
            'status' => $biasedIdx < 4 ? 'delivered' : fake()->randomElement($statuses),
            'ordered_at' => fake()->dateTimeBetween('-1 years', 'now'),
        ];
    }
 ```
 so we think we are ready to go let's create a livewire component 
 but before let's create an endpoint in your web.php file copy this
 ```php
    use App\Livewire\Orders\Index;
    Route::get('/orders',Index::class)
 ```
   
## Orders/Index Component Class 

 `php artisan livewire:make Orders/Index`
in our cas  the livewire component is pretty straightforward we just need  initiates a query on the `App\Models\Order` model using Eloquent's `query()` method to make it flexible, an then we export an orders variable to the  `livewire.orders.index` view . 

```php
<?php

namespace App\Livewire\Orders\Index;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Src\Components\Models\Order;

class Index extends Component {
    use WithPagination;

    public function render()
    {
        $query = Order::query();
        
        return view('livewire.orders.index', [
            'orders' => $query->paginate(15)
        ]);
    }
    
}
```

### livewire/orders/index

```php
<div class="mx-auto relative mt-16 max-w-4xl">
    <div class="flex-col gap-8 rounded-xl border divide-y divide-white/15 border-white/15">
        <table class="divide-white/15 rounded-2xl table-auto border-white/15 w-full divide-y duration-200 transition-opacity text-gray-800"
            wire:loading.class="opacity-30 "
            wire:target="sortBy, search,delete, nextPage, previousPage, archive, archiveSelected"
        >
            <thead>
                <tr class="w-full">
                    <th class="py-3 px-5 text-left text-sm font-semibold text-gray-300">
                        <div class="whitespace-nowrap">Order #</div>
                    </th>
                    <th class="py-3 px-5 text-left text-sm font-semibold text-gray-300">
                        <div>Status</div>
                    </th>
                    <th class="py-3 px-5 text-left text-sm font-semibold text-gray-300">
                        <div>Customer</div>
                    </th>
                    <th class="py-3 px-5  text-left text-sm font-semibold text-gray-300">
                        <div>Date</div>
                    </th>
                    <th class="py-3 px-5 text-left text-sm font-semibold text-gray-300">
                        <div>Amount</div>
                    </th>
                    
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10 bg-white/5 text-gray-300">
                @foreach ($orders as $order)
                    <tr wire:key="{{ $order->id }}">
                        <td class="whitespace-nowrap py-3 px-5 text-sm">
                            <div class="flex gap-1">
                                <span class="text-gray-300">#</span>
                                {{ $order->number }}
                            </div>
                        </td>

                        <td class="whitespace-nowrap py-3 px-5 text-sm">
                            @php
                                $classes="text-{$order->status->color()}-500 border border-{$order->status->color()}-500 bg-{$order->status->color()}-500/35 "
                            @endphp
                            <div
                                class="{{ $classes }} inline-flex items-center gap-1 rounded-md py-0.5 pl-2 pr-1 text-xs font-medium">
                                <x-dynamic-component :component="$order->status->icon()" />
                                <div>{{ $order->status->name() }}</div>
                            </div>
                        </td>

                        <td class="whitespace-nowrap py-3 px-5 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="h-5 w-5 overflow-hidden rounded-full">
                                    <img src="{{ $order->avatar }}" alt="Customer avatar">
                                </div>

                                <div>{{ $order->email }}</div>
                            </div>
                        </td>

                        <td class="whitespace-nowrap py-3 px-5 text-sm">
                            {{ $order->formattedDate() }}
                        </td>

                        <td class="w-auto whitespace-nowrap py-3 px-5 text-right text-sm font-semibold text-gray-400">
                            {{ $order->formattedAmount() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="absolute  size-16 top-1/2 left-1/2 hidden items-center justify-center" wire:loading.flex
            wire:target="nextPage, previousPage">
            <x-icon.spinner class=" text-gray-500" size="10" />
        </div>
        <div class="flex items-center justify-between pt-4 pb-4">
            <div class="px-4 text-sm text-gray-700">
                Total: {{ \Illuminate\Support\Number::format($orders->total()) }}
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
```

but we are not done yet, we need to make our pagination look more beautiful, to do that publish the pagination using `php artisan livewire:publish --pagination` and in `tailwind.blade.php` copy this code 

```html
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex gap-2 mt-2 mx-2 mb-2">
        <span>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button type="button" class="rounded-lg border border-white/15 px-3 py-2 bg-white/15 font-semibold text-sm text-gray-700 hover:bg-white/[0.07] disabled:bg-white/5 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500" disabled>
                    Prev
                </button>
            @else
                @if(method_exists($paginator,'getCursorName'))
                    <button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" class="rounded-lg border border-white/15 px-3 py-2 bg-white/15 font-semibold text-sm text-gray-700 hover:bg-white/10 hover:text-gray-300 disabled:bg-white/15 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500">
                        Prev
                    </button>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="rounded-lg border border-white/15 px-3 py-2 bg-white/15 text-gray-400 font-semibold text-sm  hover:bg-white/20 disabled:bg-white/15 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500">
                        Prev
                    </button>
                @endif
            @endif
        </span>

        <span>
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                @if(method_exists($paginator,'getCursorName'))
                    <button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" class="rounded-lg border border-white/5 px-3 py-2 bg-white/5 font-semibold text-sm text-gray-700 hover:bg-white/20 disabled:bg-white/15 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500">
                        Next
                    </button>
                @else
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="rounded-lg border border-white/15 px-3 py-2 bg-white/15 text-gray-400 font-semibold text-sm  hover:bg-white/20 disabled:bg-white/15 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500">
                        Next
                    </button>
                @endif
            @else
                <button type="button" class="rounded-lg border border-white/15 px-3 py-2 bg-white/15 font-semibold text-sm text-gray-700 hover:bg-white/20 disabled:bg-white/5 disabled:opacity-75 disabled:cursor-not-allowed disabled:text-gray-500" disabled>
                    Next
                </button>
            @endif
        </span>
    </nav>
@endif
```

