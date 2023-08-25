<?php

namespace App\Filament\Custom\Forms\Components;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\CanBeSearchable;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasGridDirection;
use Filament\Forms\Components\Concerns\HasOptions;
use Filament\Forms\Components\Field;
use Filament\Support\Enums\ActionSize;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Str;

class CheckboxList extends \Filament\Forms\Components\CheckboxList
{

    protected string $view = 'filament.custom.components.checkbox-list';

    protected $types = [];

    public function type(array|Arrayable|string|Closure|null $types): static
    {
        $this->types = $types;

        return $this;
    }

//    public function options(array|Arrayable|string|Closure|null $options): static
//    {
//        $this->options = $options;
////        if (is_array($options)) {
////            foreach ($options as $key => $value) {
////                if (isset($value['type'])) {
////                    $this->types[$key] = $value['type'];
////                }
////            }
////        }
//
//        return $this;
//    }


    public function getTypes()
    {
//        dd($this->types);
        return $this->evaluate($this->types);
    }
}
