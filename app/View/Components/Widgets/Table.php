<?php

namespace App\View\Components\Widgets;

use Illuminate\View\Component;
use Illuminate\View\View;

class Table extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.widgets.table');
    }
}
