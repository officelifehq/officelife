<?php

namespace App\Services\Adminland\Position;

use App\Services\BaseService;
use App\Models\Company\Position;
use App\Services\Adminland\Company\LogAction;

class DestroyPosition extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'position_id' => 'required|integer|exists:positions,id',
        ];
    }

    /**
     * Destroy a position.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data): bool
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

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'position_destroyed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
        ]);

        return true;
    }
}
