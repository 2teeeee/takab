<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
class AdminLayout extends Component
{
    public string $title;
    public string $header;

    public function __construct(string $title = 'پنل مدیریت', string $header = 'مدیریت')
    {
        $this->title = $title;
        $this->header = $header;
    }

    public function render(): View
    {
        return view('components.admin-layout');
    }
}
