<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo,
            'area' => $this->area,
            'address' => $this->address,
            'whatsapp' => $this->whatsapp,
            'bank' => $this->bank,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'qris' => $this->qris,
        ];
    }
}
