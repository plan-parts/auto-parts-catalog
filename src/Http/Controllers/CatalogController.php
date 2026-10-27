<?php

namespace PlanParts\Catalog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PlanParts\Catalog\ApiClient;
use PlanParts\Catalog\Exceptions\ApiException;

class CatalogController extends Controller
{
    public function __construct(private readonly ApiClient $api) {}

    /** Step 1: vehicle manufacturers. */
    public function index()
    {
        return $this->render('index', fn () => [
            'brands' => $this->api->brands(),
        ]);
    }

    /** Step 2: model series for a manufacturer. */
    public function models(int $mfaId)
    {
        return $this->render('models', fn () => [
            'mfa_id' => $mfaId,
            'models' => $this->api->models($mfaId),
        ]);
    }

    /** Step 3: modifications for a model series. */
    public function modifications(int $msId)
    {
        return $this->render('modifications', fn () => [
            'ms_id'         => $msId,
            'modifications' => $this->api->modifications($msId),
        ]);
    }

    /** Step 4: part categories for a vehicle K-Type. */
    public function categories(int $ktype)
    {
        return $this->render('categories', fn () => [
            'ktype'      => $ktype,
            'categories' => $this->api->categories($ktype),
        ]);
    }

    /** Step 5: parts list for a vehicle + category. */
    public function parts(int $ktype, int $ptId)
    {
        return $this->render('parts', fn () => [
            'ktype' => $ktype,
            'pt_id' => $ptId,
            'parts' => $this->api->articles($ktype, $ptId),
        ]);
    }

    /** Step 6: full article detail page. */
    public function article(int $artId)
    {
        return $this->render('article', function () use ($artId) {
            $article  = $this->api->article($artId);
            $media    = $this->api->articleMedia($artId);
            $criteria = $this->api->articleCriteria($artId);
            $crosses  = $this->api->articleCrosses($artId);

            // Analogs — requires article_nr + brand from the detail response
            $analogs       = [];
            $articleNumber = $article['data']['article_nr'] ?? null;
            $brand         = $article['data']['brand']      ?? null;

            if ($articleNumber && $brand) {
                try {
                    $analogs = $this->api->analogs($articleNumber, $brand);
                } catch (ApiException) {
                    // analogs are optional — skip if unavailable
                }
            }

            return compact('article', 'media', 'criteria', 'crosses', 'analogs');
        });
    }

    /** Search page. */
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $type  = $request->input('type', 'ART');

        return $this->render('search', function () use ($query, $type) {
            $results = [];

            if (strlen($query) >= 2) {
                $results = $this->api->search($query, $type);
            }

            return compact('query', 'type', 'results');
        });
    }

    /** JSON autocomplete for the search field. */
    public function suggest(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            return response()->json($this->api->suggest($query));
        } catch (ApiException) {
            return response()->json([]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function render(string $view, callable $data)
    {
        try {
            return view("catalog::{$view}", $data());
        } catch (ApiException $e) {
            return view('catalog::error', ['message' => $e->getMessage()]);
        }
    }
}
