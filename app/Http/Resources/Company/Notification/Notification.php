<?php

namespace App\Http\Resources\Company\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'object' => 'notification',
            'action' => $this->action,
            'localized_content' => $this->content,
            'read' => $this->read,
            'employee' => [
                'id' => $this->employee_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
