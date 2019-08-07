<?php

namespace App\Services\Company\Adminland\Position;

use App\Services\BaseService;
use App\Models\Company\Position;
use App\Jobs\Logs\LogAccountAudit;

class DestroyPosition extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'position_id' => 'required|integer|exists:positions,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Destroy a position.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $position = Position::where('company_id', $data['company_id'])
            ->findOrFail($data['position_id']);

        $position->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'position_destroyed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'position_title' => $position->title,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return true;
    }
}
