<?php

namespace App\Services\Company\Adminland\Position;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Position;

class CreatePosition extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'title' => 'required|string|max:255',
        ];
    }

    /**
     * Create a position.
     *
     * @param array $data
     *
     * @return Position
     */
    public function execute(array $data): Position
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $position = Position::create([
            'company_id' => $data['company_id'],
            'title' => $data['title'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
        ])->onQueue('low');

        return $position;
    }
}
