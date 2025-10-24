<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    protected bool $basic = false;

    public function basic(): self
    {
        $this->basic = true;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->basic ? [
            'id' => $this->id,
            'name' => $this->name,
        ] : $this->resource->toArray();
    }
}
