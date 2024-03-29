<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels;

// --- Services --

/**
 * Class ConfPanel.
 */
class ConfPanel extends XotBasePanel
{
    /**
     * The model the resource corresponds to.
     */
    public static string $model = 'Modules\Xot\Models\Conf';

    /**
     * The single value that should be used to represent the resource when being displayed.
     */
    public static string $title = 'title';

    /**
     * @return object[]
     */
    public function fields(): array
    {
        return [
            (object) [
                'type' => 'Id',
                'name' => 'id',
                'comment' => null,
            ],
            (object) [
                'type' => 'String',
                'name' => 'name',
                // 'rules' => 'required',
                'comment' => null,
            ],
        ];
    }

    /*
    public function getBuilder() {
        return collect($this->row->getRows());
    }
    */
}
