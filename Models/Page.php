<?php

declare(strict_types=1);

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Xot\Services\FileService;
use Sushi\Sushi;

/**
 * Modules\Blog\Models\Page.
 *
 * @property int                                             $id
 * @property string                                          $title
 * @property \Illuminate\Database\Eloquent\Collection|Page[] $sons
 * @property int|null                                        $sons_count
 *
 * @method static \Modules\Blog\Database\Factories\PageFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Page   newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page   newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page   query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page   whereId($value)
 *
 * @mixin \Eloquent
 */
class Page extends BaseModel {
    use Sushi;
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'pos',
        'article_type',
        'published_at',
        'category_id',
        'layout_position',
        'blade',
        'parent_id',
        'icon',
        'is_modal',
        'status',
    ];

    // --------- relationship ---------------

    // --------- relationship ---------------

    public function sons(): HasMany {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function getRows(): array {
        // creates nss array
        $nss = [];

        // push pub_theme
        $nss[] = 'pub_theme';
        /**
         * @var string
         */
        $main_module = config('xra.main_module');
        if ('' !== $main_module && null !== $main_module) {
            // push main module (ex: blog) in nss
            $nss[] = strtolower($main_module);
        }

        // creates $pages collection
        $pages = collect([]);

        // for each nss
        foreach ($nss as $ns) {
            // get theme path trough FileService
            $pub_theme_path = FileService::getViewNameSpacePath($ns);

            // get page path starting from the pub_theme_path
            $pages_path = $pub_theme_path.\DIRECTORY_SEPARATOR.'pages';

            // creates temporary collection for every page in theme (all blade.php files in pub_theme)
            $tmp = collect(File::files($pages_path));
            $tmp = $tmp->filter(
                function ($item) {
                    return Str::endsWith($item->getFilename(), '.blade.php');
                    // return true;
                }
            );

            // creates the page paths with view format (separated by points)
            // and without blade.php extension
            $tmp = $tmp->map(function ($file) use ($ns) {
                // get title from the page name (SEO)
                // for example if page name is scores.blade.php the title will be "Scores"
                $title = $file->getFilenameWithoutExtension();
                $title = Str::before($title, '.blade');

                // returns the page into the model
                return [
                    'id' => $title,
                    'parent_id' => 0,
                    'guid' => $title,
                    'title' => $title,
                    'ns' => $ns,
                    //    'ext' => $file->getExtension(),
                ];
            });
            $pages = $pages->merge($tmp);
        }

        // returns the page into the model
        // then you can call /{?lang}/pages/{page_title}
        return $pages->all();
    }
}