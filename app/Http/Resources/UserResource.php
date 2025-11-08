<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    protected bool $basic = false;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->only(['id', 'name', 'email']);
    }
}
