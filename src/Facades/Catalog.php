<?php

namespace PlanParts\Catalog\Facades;

use Illuminate\Support\Facades\Facade;
use PlanParts\Catalog\ApiClient;

/**
 * @method static array brands()
 * @method static array models(int $mfaId)
 * @method static array modifications(int $msId)
 * @method static array categories(int $ktype)
 * @method static array articles(int $ktype, int $ptId)
 * @method static array article(int $artId)
 * @method static array articleMedia(int $artId)
 * @method static array articleCriteria(int $artId)
 * @method static array articleCrosses(int $artId)
 * @method static array analogs(string $article, string $brand)
 * @method static array search(string $query, string $type = 'ART')
 * @method static array suggest(string $query)
 * @method static array ktype(array $params)
 *
 * @see \PlanParts\Catalog\ApiClient
 */
class Catalog extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiClient::class;
    }
}
