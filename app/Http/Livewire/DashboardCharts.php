<?php

namespace App\Http\Livewire;
use App\Models\Operation;
use Livewire\Component;
use Carbon\Carbon;

class DashboardCharts extends Component
{
    public function render()
    {
        return view('livewire.dashboard-charts');
    }
}
