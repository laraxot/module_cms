<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Cms\Actions\GetViewAction;
use Modules\Xot\Services\ArrayService;
use Modules\Xot\Services\ModelService;
use Nwidart\Modules\Facades\Module;

// -------- models -----------

// -------- services --------

// -------- bases -----------

/**
 * Class CloneAction.
 */
class DbAction extends XotBasePanelAction
{
    public bool $onItem = true;

    public string $icon = '<i class="fas fa-database"></i>';

    /**
     * return \Illuminate\Http\RedirectResponse.
     */
    public function handle(): Renderable
    {
        // k$database=config('database');
        $data = $this->getAllTablesAndFields();
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute(); // 'xot::admin.home.acts.db';
        $model = $this->getModel();
        $model_service = ModelService::make()->setModel($model);
        $view_params = [
            'view' => $view,
            'rows' => $data,
            'model_service' => $model_service,
        ];

        return view($view, $view_params);
    }

    /**
     * @return mixed|void
     */
    public function postHandle()
    {
        $search = request('search');
        $data = $this->getAllTablesAndFields();
        $data = $data->map(
            function ($item) {
                // $item['sql']=$this->makeSql($item,$search);
                return $item;
            });
        $model = $this->getModel();
        $model_service = ModelService::make()->setModel($model);

        foreach ($data as $v) {
            try {
                // $res=$model_service->select($v['sql']);
                $res = $model->getConnection()->table($v['name']);
                $valid = false;
                foreach ($v['fields'] as $field) {
                    if (\in_array($field['type'], ['string', 'text'], true)) {
                        $res = $res->orWhere($field['name'], 'like', '%'.$search.'%');
                        $valid = true;
                    }
                }
                if ($valid) {
                    $rows = $res->limit(10);
                    $res = $rows->get();
                }
            } catch (QueryException $e) {
                $msg = '<pre>'.$v['sql'].'</pre><pre>'.$e->getMessage().'</pre>';
                throw new \Exception($msg.'['.__LINE__.']['.__FILE__.']');
            } catch (\Exception $e) {
                $msg = '<pre>'.$v['sql'].'</pre><pre>'.$e->getMessage().'</pre>';
                throw new \Exception($msg.'['.__LINE__.']['.__FILE__.']');
            }
            if ($res->count() > 0 && $valid && isset($rows)) {
                echo '<hr>';
                echo '<h3>Table: '.$v['name'].'</h3>';
                echo '<h3>Search: '.$search.'</h3>';
                echo '<h3>N ['.\count($res).'] Results</h3>';
                // echo '<pre>'.print_r($v['sql'],true).'</pre>';
                // $sql = Str::replaceArray('?', $rows->getBindings(), $rows->toSql());
                $sql = rowsToSql($rows);
                echo '<pre>'.print_r($sql, true).'</pre>';
                echo ArrayService::make()->setArray($res->toArray())->toHtml()->render();
                // dddx($res);
            }
        }
        echo '<h3>+Done Searching ['.$search.']</h3>';
    }

    /**
     * Undocumented function.
     *
     * @see https://stackoverflow.com/questions/26181170/laravel-how-to-use-query-builder-dbtable-with-dbconnection
     *
     * @param array  $item
     * @param string $search
     *
     * @return string
     */
    public function makeSql($item, $search)
    {
        // $programs=DB::connection('mysql2')->table('node')->where('type', 'Programs')->get();

        $sql = 'select * from '.$item['name'].'
        where ';
        $where = [];
        foreach ($item['fields'] as $field) {
            $name = $field['name'];
            $name = '`'.addslashes($name).'`'; // test

            switch ($field['type']) {
                // case 'string':
                //    $where[]=$name.' like "%'.$search.'%"';
                // break;
                default:
                    $where[] = $name.' = "'.$search.'"';
                    break;
            }
        }

        return $sql.'('.implode(\chr(13).\chr(10).' OR ', $where).') limit 10';
    }

    public function getModel(): Model
    {
        $module_name = $this->panel->getModuleName();
        $cache_key = Str::slug($module_name.'_model');
        /**
         * @var string
         */
        $first_model_class = Cache::rememberForever($cache_key, function () use ($module_name) {
            $module_path = Module::getModulePath($module_name);
            $module_models_path = $module_path.'/Models';
            $models = File::files($module_models_path);
            $i = 0;
            $is_abstract = true;
            while ($is_abstract) {
                $first_model_file = $models[$i++];
                /**
                 * @var class-string
                 */
                $first_model_class = 'Modules\\'.$module_name.'\Models\\'.Str::before($first_model_file->getBasename(), '.php');
                $reflect = new \ReflectionClass($first_model_class);
                $is_abstract = $reflect->isAbstract();
            }

            return $first_model_class;
        });
        $first_model = app($first_model_class);

        return $first_model;
    }

    public function getAllTablesAndFields(): Collection
    {
        $first_model = $this->getModel();
        $cache_key = Str::slug(\get_class($first_model).'_'.__FUNCTION__);

        /**
         * @var Collection
         */
        $data = Cache::rememberForever($cache_key, function () use ($first_model) {
            return ModelService::make()->setModel($first_model)->getAllTablesAndFields();
        });

        return $data;
    }
}
