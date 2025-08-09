<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OurTeamCard extends Component
{
    public $trainer;

    public function __construct($trainer)
    {
        $this->trainer = $trainer;
    }

    public function render(): View|Closure|string
    {
        return view('components.user.our-team-card');
    }
}
