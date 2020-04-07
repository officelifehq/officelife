<?php

namespace App\Http\Resources\Company\AuditLog;

use App\Models\Company\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLog extends JsonResource
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
        $employee = Employee::where('id', $this->author_id)->first();

        return [
            'id' => $this->id,
            'object' => 'auditlog',
            'action' => $this->action,
            'objects' => json_decode($this->objects),
            'localized_content' => $this->content,
            'author' => [
                'id' => is_null($employee) ? null : $employee->id,
                'name' => is_null($employee) ? $this->author_name : $employee->name,
            ],
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
