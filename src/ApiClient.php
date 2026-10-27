<?php

namespace PlanParts\Catalog;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PlanParts\Catalog\Exceptions\ApiException;

class ApiClient
{
    public function __construct(
        private readonly string $token,
        private readonly string $baseUrl = 'https://cdn.plan.parts/api',
        private readonly int $timeout = 15,
    ) {}

    // ── Vehicle navigation ────────────────────────────────────────────────

    /** List all vehicle manufacturers (~708 brands). Cost: 1 token */
    public function brands(): array
    {
        return $this->cached('brands', fn () =>
            $this->get('/v1/aftermarket/catalog/brands')
        );
    }

    /** Model series for a manufacturer. Cost: 1 token */
    public function models(int $mfaId): array
    {
        return $this->cached("models.{$mfaId}", fn () =>
            $this->get('/v1/aftermarket/catalog/models', ['mfa_id' => $mfaId])
        );
    }

    /** Vehicle modifications for a model series. Cost: 1 token */
    public function modifications(int $msId): array
    {
        return $this->cached("modifications.{$msId}", fn () =>
            $this->get('/v1/aftermarket/catalog/modifications', ['ms_id' => $msId])
        );
    }

    /** Part categories for a vehicle K-Type. Cost: 1 token */
    public function categories(int $ktype): array
    {
        return $this->cached("categories.{$ktype}", fn () =>
            $this->get('/v1/aftermarket/catalog/categories', ['ktype' => $ktype])
        );
    }

    /** Spare parts list for a vehicle + category. Cost: 2 tokens */
    public function articles(int $ktype, int $ptId): array
    {
        return $this->cached("articles.{$ktype}.{$ptId}", fn () =>
            $this->get('/v1/aftermarket/catalog/articles', [
                'ktype' => $ktype,
                'pt_id' => $ptId,
            ])
        );
    }

    // ── Article details ───────────────────────────────────────────────────

    /** Full article detail: name, brand, OEM numbers, attributes. Cost: 2 tokens */
    public function article(int $artId): array
    {
        return $this->cached("article.{$artId}", fn () =>
            $this->get("/v1/aftermarket/articles/{$artId}", [
                'lang' => config('auto-parts-catalog.lang', 'en'),
            ])
        );
    }

    /** Images and documents for an article. Cost: 1 token */
    public function articleMedia(int $artId): array
    {
        return $this->cached("article.media.{$artId}", fn () =>
            $this->get("/v1/aftermarket/articles/{$artId}/media")
        );
    }

    /** Technical specifications for an article. Cost: 1 token */
    public function articleCriteria(int $artId): array
    {
        return $this->cached("article.criteria.{$artId}", fn () =>
            $this->get("/v1/aftermarket/articles/{$artId}/criteria", [
                'lang' => config('auto-parts-catalog.lang', 'en'),
            ])
        );
    }

    /** OEM and cross-reference numbers for an article. Cost: 2 tokens */
    public function articleCrosses(int $artId): array
    {
        return $this->cached("article.crosses.{$artId}", fn () =>
            $this->get("/v1/aftermarket/articles/{$artId}/crosses", [
                'lang' => config('auto-parts-catalog.lang', 'en'),
            ])
        );
    }

    /** OEM/IAM analogs by article + brand. Cost: 2 tokens */
    public function analogs(string $article, string $brand): array
    {
        return $this->get('/v1/aftermarket/analogs', [
            'article' => $article,
            'brand'   => $brand,
            'lang'    => config('auto-parts-catalog.lang', 'en'),
        ]);
    }

    // ── Search ────────────────────────────────────────────────────────────

    /** Search by article number, OEM, EAN, or IAM. Cost: 2 tokens */
    public function search(string $query, string $type = 'ART'): array
    {
        return $this->get('/v1/aftermarket/search', [
            'q'    => $query,
            'type' => $type,
        ]);
    }

    /** Autocomplete suggestions. Cost: 1 token */
    public function suggest(string $query): array
    {
        return $this->get('/v1/aftermarket/search/suggest', [
            'q'     => $query,
            'lang'  => config('auto-parts-catalog.lang', 'en'),
            'limit' => 10,
        ]);
    }

    // ── K-Type ────────────────────────────────────────────────────────────

    /** Find vehicle by brand/model/year or K-Type. Cost: 1 token */
    public function ktype(array $params): array
    {
        return $this->get('/v1/aftermarket/ktype', $params);
    }

    // ── Internal ─────────────────────────────────────────────────────────

    private function get(string $path, array $query = []): array
    {
        if (empty($this->token)) {
            throw new ApiException(
                'API token is not set. Add PLAN_PARTS_TOKEN to your .env file. ' .
                'Get your token at https://plan.parts/register'
            );
        }

        $response = Http::timeout($this->timeout)
            ->withToken($this->token)
            ->get(rtrim($this->baseUrl, '/') . $path, $query);

        if ($response->status() === 401) {
            throw new ApiException('Invalid API token. Register at https://plan.parts');
        }
        if ($response->status() === 402) {
            throw new ApiException('Insufficient tokens. Top up at https://plan.parts/portal');
        }
        if ($response->failed()) {
            throw new ApiException("API error {$response->status()}: {$response->body()}");
        }

        return $response->json() ?? [];
    }

    private function cached(string $key, callable $callback): array
    {
        if (!config('auto-parts-catalog.cache.enabled', true)) {
            return $callback();
        }

        return Cache::store(config('auto-parts-catalog.cache.store'))
            ->remember(
                "plan-parts.catalog.{$key}",
                config('auto-parts-catalog.cache.ttl', 3600),
                $callback
            );
    }
}
