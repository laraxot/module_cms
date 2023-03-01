<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Livewire\Modal\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Modules\Cms\Actions\GetViewAction;
use Modules\Xot\Actions\Model\DestroyAction;
use WireElements\Pro\Components\Modal\Modal;

class Destroy extends Modal {
    public string $model_type;
    public string $model_id;
    public string $user_id;

    public function mount(string $model_type, string $model_id): void {
        $this->model_type = $model_type;
        $this->model_id = $model_id;
        $this->user_id = (string) Auth::id();
    }

    public function render(): Renderable {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute();

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function delete() {
        app(DestroyAction::class)->execute($this->model_type::findOrFail($this->model_id), [], []);
        $this->close();

        return redirect(request()->header('Referer'));
    }

    public static function behavior(): array {
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

    public static function attributes(): array {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => 'xl',
        ];
    }
}
