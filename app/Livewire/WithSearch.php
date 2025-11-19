<?php

namespace App\Livewire;

trait WithSearch
{
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
