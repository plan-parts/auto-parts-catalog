# Auto Parts Catalog for Laravel

A Laravel package that provides a ready-to-use **auto parts catalog** powered by the [plan.parts Aftermarket API](https://plan.parts/docs/aftermarket-oem-oe-iam).

Browse parts by vehicle — from manufacturer down to a specific modification — or search by article number and OEM code. Includes full part detail pages with images, specifications, OEM cross-references, and analog parts.

---

## How It Works

```
/auto-parts                         → Select vehicle brand (708 manufacturers)
/auto-parts/brands/{id}/models      → Select model series
/auto-parts/models/{id}/modifications → Select exact modification (engine, year)
/auto-parts/vehicle/{ktype}/categories → Browse part categories
/auto-parts/vehicle/{ktype}/category/{pt_id} → Parts list with images
/auto-parts/article/{art_id}        → Full part detail: specs, OEM numbers, analogs
/auto-parts/search?q=F026407123     → Search by article, OEM, EAN
```

All pages are rendered via Blade views — fully customizable by publishing them to your app.

---

## Requirements

- PHP **8.2+**
- Laravel **11** or **12**
- A plan.parts API token — [register here](https://plan.parts/register)

---

## Installation

```bash
composer require plan-parts/auto-parts-catalog
```

Run the setup:

```bash
# Publish config
php artisan vendor:publish --tag=auto-parts-catalog-config
```

Add your API token to `.env`:

```dotenv
PLAN_PARTS_TOKEN=your_token_here
```

Open `/auto-parts` in your browser — the catalog is ready.

---

## Configuration

```php
// config/auto-parts-catalog.php
return [
    'token'        => env('PLAN_PARTS_TOKEN'),
    'base_url'     => 'https://cdn.plan.parts/api',

    // Route prefix: catalog available at /auto-parts
    'route_prefix' => 'auto-parts',
    'middleware'   => ['web'],

    // Cache API responses to save tokens on repeated browsing
    'cache' => [
        'enabled' => true,
        'ttl'     => 3600, // 1 hour
    ],

    'lang' => 'en', // en, ru, de
];
```

### Caching

Browsing the catalog (brands, models, categories) costs tokens on every page load. Enable caching to avoid paying for the same data repeatedly:

```dotenv
PLAN_PARTS_CACHE=true
PLAN_PARTS_CACHE_TTL=3600
```

---

## Customizing Views

Publish the Blade views to your application:

```bash
php artisan vendor:publish --tag=auto-parts-catalog-views
```

Views are published to `resources/views/vendor/auto-parts-catalog/`. All pages extend `layouts/catalog.blade.php`, which you can replace with your own layout.

---

## Programmatic Usage

### Using the Facade

```php
use PlanParts\Catalog\Facades\Catalog;

// Get all vehicle brands
$brands = Catalog::brands();

// Get models for VW (mfa_id from brands list)
$models = Catalog::models(16);

// Get modifications for Golf (ms_id from models list)
$modifications = Catalog::modifications(1994);

// Get part categories for a specific vehicle
$categories = Catalog::categories(8799);

// Get parts for vehicle + category
$parts = Catalog::articles(8799, 1);

// Full article detail
$article = Catalog::article(6499252);

// Find OEM/IAM analogs
$analogs = Catalog::analogs('F026407123', 'BOSCH');

// Search by article number
$results = Catalog::search('03L115562', 'OEM');
```

### Direct Injection

```php
use PlanParts\Catalog\ApiClient;

class MyController extends Controller
{
    public function __construct(private ApiClient $catalog) {}

    public function show(int $artId)
    {
        $article  = $this->catalog->article($artId);
        $media    = $this->catalog->articleMedia($artId);
        $crosses  = $this->catalog->articleCrosses($artId);

        return view('my-part', compact('article', 'media', 'crosses'));
    }
}
```

---

## Token Costs

| Action | Tokens |
|--------|--------|
| Load brands / models / categories | 1 token each |
| Load parts list / article detail | 2 tokens |
| Search by article number | 2 tokens |
| Find analogs | 2 tokens |

Caching is enabled by default — repeated page loads within the TTL window use **0 tokens**.

---

## Example: Find All Filters for a VW Golf 2.0 TDI

```php
// 1. Find the vehicle K-Type
$vehicles = Catalog::ktype(['brand' => 'VW', 'model' => 'Golf', 'year' => 2018]);
$ktype = $vehicles['data'][0]['ktype'];

// 2. Get part categories
$categories = Catalog::categories($ktype);
$oilFilterCat = collect($categories['data'])->firstWhere('pt_name', 'Oil Filter');

// 3. Get all oil filters for this vehicle
$filters = Catalog::articles($ktype, $oilFilterCat['pt_id']);

// 4. Get analogs for the first result
$first = $filters['data'][0];
$analogs = Catalog::analogs($first['art_article_nr'], $first['mfa_brand']);
```

---

## Support

- Register and get your API token: [plan.parts/register](https://plan.parts/register)
- API documentation: [plan.parts/docs/aftermarket-oem-oe-iam](https://plan.parts/docs/aftermarket-oem-oe-iam)
- Support tickets: [plan.parts](https://plan.parts)

---

## License

MIT — free to use for any lawful purpose. See [LICENSE](LICENSE).
