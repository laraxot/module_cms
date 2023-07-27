<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Nwidart\Modules\Facades\Module as NwModule;
use Sushi\Sushi;

/**
 * Modules\Cms\Models\Module.
 *
 * @property int         $id
 * @property string|null $name
 *
 * @method static \Modules\Cms\Database\Factories\ModuleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Module  newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module  newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Module  query()
 * @method static \Illuminate\Database\Eloquent\Builder|Module  whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Module  whereName($value)
 *
 * @mixin \Eloquent
 * @mixin IdeHelperModule
 */
class Module extends BaseModel
{
    use Sushi;

    /**
     * @var string[]
     */
    public $fillable = [
        'id', 'name',
    ];

    public function getRows(): array
    {
        $modules = NwModule::getByStatus(1);
        $rows = [];
        $i = 1;
        foreach ($modules as $k => $module) {
            $tmp = [
                'id' => $i++,
                'name' => $module->getName(),
            ];
            $rows[] = $tmp;
        }

        return $rows;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
}
