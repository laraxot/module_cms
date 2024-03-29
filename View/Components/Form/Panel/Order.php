<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Form\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Services\PanelService;

class Order extends Component
{
    public string $tpl;
    public array $qs;

    public array $options;
    public array $options1 = ['desc' => 'desc', 'asc' => 'asc'];
    public array $input_attrs = [];
    public array $form_attrs = ['method' => 'get'];

    public ?string $sort_by;
    public ?string $sort_order;

    public function __construct(string $tpl = 'v1')
    {
        $panel = PanelService::make()->getRequestPanel();
        $this->tpl = $tpl;

        /**
         * @var array
         */
        $query = request()->query();
        $this->qs = collect($query)
                    ->except(['sort'])
                    ->all();
        if (! is_null($panel)) {
            $this->options = array_combine($panel->orderBy(), $panel->orderBy());
        } else {
            throw new \Exception('['.__LINE__.']['.__FILE__.'], panel is null');
        }
        $this->input_attrs = ['placeholder' => 'Ordinamento', 'label' => ' '];
        switch ($tpl) {
            case 'inline':
                $this->form_attrs['class'] = 'd-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search float-right';
                break;
        }

        /**
         * @var string|null
         */
        $sort_by = Request::input('sort.by');

        /**
         * @var string|null
         */
        $sort_order = Request::input('sort.order');

        $this->sort_by = $sort_by;
        $this->sort_order = $sort_order;
    }

    // public function setSortOrderAttributes():array{
    //     return [
    //         'options' = ['desc' => 'desc', 'asc' => 'asc'],
    //         'attrs' = ['placeholder' => 'Ordinamento', 'label' => ' '],

    //     ];
    // }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);

        $view_params = [];

        return view($view, $view_params);
    }
}
