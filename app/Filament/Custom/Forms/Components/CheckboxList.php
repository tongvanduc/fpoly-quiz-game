<?php

namespace App\Filament\Custom\Forms\Components;

use Closure;
use Filament\Forms\Components\CheckboxList as CheckboxListBase;
use Illuminate\Contracts\Support\Arrayable;

class CheckboxList extends CheckboxListBase
{
    protected string $view = 'filament.custome.components.checkbox-list';

    protected array|Arrayable|string|Closure|null $types = null;

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
