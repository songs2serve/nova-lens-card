# Nova Lens Card

Turn your Laravel Nova 3 lenses into summary cards.

## Installation

```
composer require songs2serve/nova-lens-card
```

## Usage

Add a card on your Nova dashboard:

```php
namespace App\Providers;

use App\Models\Song;
use Songs2Serve\NovaLensCard\LensCard;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    protected function cards()
    {
        return [
            new LensCard(new MyLens(), Song::class),
        ];
    }
}
```

Add a card on a resource overview:

```php
namespace App\Nova;

use Songs2Serve\NovaLensCard\LensCard;

class Song extends Resource
{
    protected function cards()
    {
        return [
            new LensCard(new MyLens()),
        ];
    }
}
```

The amount of records can be customized by calling the `limit()` method.
