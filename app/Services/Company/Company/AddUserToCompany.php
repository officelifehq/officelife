<?php

namespace App\Services\Company\Team;

use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Company;
use App\Services\Company\Company\LogAction;

class AddUserToCompany extends BaseService
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
            'user_id' => 'required|integer|exists:users,id',
            'permission_level' => 'required|integer',
        ];
    }

    /**
     * Add a user to the company.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) : Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions($data['author_id'], $data['company_id'], 'hr');

        $employee = Employee::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'uuid' => Str::uuid()->toString(),
            'permission_level' => $data['permission_level'],
        ]);

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'user_added_to_company',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]),
        ]);

        return $team;
    }
}
