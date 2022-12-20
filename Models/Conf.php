<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tenant\Services\TenantService;
use Sushi\Sushi;

/**
 * Modules\Cms\Models\Conf.
 *
 * @property int         $id
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|Conf newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf query()
 * @method static \Illuminate\Database\Eloquent\Builder|Conf whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Conf whereName($value)
 * @mixin \Eloquent
 */
class Conf extends Model {
    use Sushi;

    /**
     * @var string[]
     */
    public $fillable = [
        'id', 'name',
    ];

    public function getRows(): array {
        $rows = TenantService::getConfigNames(); //  local/ptvx

        return $rows;
    }

    /*
    protected function sushiShouldCache() {
        return false;
    }
    */

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'name';
    }
}
