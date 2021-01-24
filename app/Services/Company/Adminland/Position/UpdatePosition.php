<?php

namespace App\Services\Company\Adminland\Position;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Position;

class UpdatePosition extends BaseService
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
            'title' => 'required|string|max:255',
        ];
    }

    /**
     * Update a position.
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

        $position = Position::where('company_id', $data['company_id'])
            ->findOrFail($data['position_id']);

        $oldPositionTitle = $position->title;

        Position::where('id', $position->id)->update([
            'title' => $data['title'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'position_id' => $position->id,
                'position_title' => $data['title'],
                'position_old_title' => $oldPositionTitle,
            ]),
        ])->onQueue('low');

        $position->refresh();

        return $position;
    }
}
