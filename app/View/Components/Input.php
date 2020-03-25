<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Indicates whether the input field is required.
     */
    public bool $required;

    /**
     * The type of the input field.
     */
    public string $type;

    /**
     * The name of the input field.
     */
    public string $name;

    /**
     * The label of the input field.
     */
    public string $label;

    /**
     * The model of the input field.
     */
    public string $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(bool $required = false, string $type, string $name, string $model, string $label)
    {
        $this->required = $required;
        $this->type = $type;
        $this->name = $name;
        $this->model = $model;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.input');
    }
}
