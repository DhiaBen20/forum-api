<?php

namespace App;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait HasMarkdown
{
    /** @return Attribute<string,never> */
    protected function bodyInHtml(): Attribute
    {
        return Attribute::make(
            get: fn ($_, array $attributes) => Str::markdown(
                $attributes['body'],
                ['html_input' => 'escape', 'allow_unsafe_links' => false]
            )
        );
    }
}
