<?php

namespace App\Services\Company\Adminland\Position;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Position;

class DestroyPosition extends BaseService
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
            'position_id' => 'required|integer|exists:positions,id',
        ];
    }

    /**
     * Destroy a position.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $position = Position::where('company_id', $data['company_id'])
            ->findOrFail($data['position_id']);

        $position->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'position_title' => $position->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
