<?php

namespace App\Services\Company\Adminland\Flow;

use App\Models\Company\Flow;
use App\Services\BaseService;
use App\Services\Company\Adminland\Company\LogAuditAction;

class CreateFlow extends BaseService
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
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a flow.
     *
     * @param array $data
     * @return Flow
     */
    public function execute(array $data): Flow
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $flow = Flow::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'flow_created',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'flow_id' => $flow->id,
                'flow_name' => $flow->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $flow;
    }
}
