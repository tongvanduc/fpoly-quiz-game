<?php

namespace App\Filament\Custom\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class CheckboxList extends \Filament\Forms\Components\CheckboxList
{

    protected string $view = 'filament.custom.components.checkbox-list';

    protected array $types = [];

    public function type(array|Arrayable|string|Closure|null $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getTypes()
    {
        return $this->evaluate($this->types);
    }
}
