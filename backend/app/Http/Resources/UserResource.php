<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'roles'       => $this->getRoleNames(),
            'permissions' => $this->getPermissionNames(),
            'person'      => $this->whenLoaded('person', function () {
                return [
                    'id'      => $this->person->id,
                    'name'    => $this->person->name,
                    'email'   => $this->person->email,
                    'city'    => $this->person->city,
                    'address' => $this->person->address,
                    // adicione outros campos que desejar
                ];
            }),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
