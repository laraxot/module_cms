<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Livewire\Modal\Panel;

use Illuminate\Support\Facades\Auth;
use Modules\Cms\Actions\GetViewAction;
<<<<<<< HEAD
use Modules\Tenant\Services\TenantService;
=======
use Illuminate\Support\Facades\Session;
use Modules\Tenant\Services\TenantService;
use Illuminate\Contracts\Support\Renderable;
use Modules\Xot\Actions\Model\DestroyAction;
>>>>>>> 91aa56e (up)
use WireElements\Pro\Components\Modal\Modal;

class Destroy extends Modal
{
    public string $model_type;
    public string $model_id;
    public string $user_id;

    public function mount(string $model_type, string $model_id): void
    {
        $this->model_type = $model_type;
        $this->model_id = $model_id;
        $this->user_id = (string) Auth::id();
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute();

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    /**
     * Undocumented function.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete()
    {
<<<<<<< HEAD
        TenantService::model($this->model_type)->find($this->model_id)?->delete();
=======
        // $model_class = collect(config('morph_map'))->get($this->model_type);
        // $model = app($model_class)->findOrFail($this->model_id)?->delete();
        $model = TenantService::model($this->model_type)->findOrFail($this->model_id)?->delete();
>>>>>>> 91aa56e (up)

        $this->close();

        Session::flash('status', 'eliminato');

        return redirect(request()->header('Referer'));
    }

    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => true,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => false,
            // Trap the users focus inside the modal (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the modal
            'remove-state-on-close' => false,
        ];
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => 'xl',
        ];
    }
}
