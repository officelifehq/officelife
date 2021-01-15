<?php

namespace App\Services\User\Avatar;

use Carbon\Carbon;
use App\Models\User\Avatar;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;

class UploadAvatar extends BaseService
{
    private array $data;

    private Avatar $avatar;

    private Employee $employee;

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
            'employee_id' => 'required|integer|exists:employees,id',
            'photo' => 'file|image',
        ];
    }

    /**
     * Upload an avatar.
     *
     * @param array $data
     *
     * @return Avatar
     */
    public function execute(array $data): Avatar
    {
        $this->data = $data;

        $this->validate();
        $photoData = $this->getPhotoData();
        $this->savePhoto($photoData);
        $this->setAllOtherAvatarsAsInactive();
        $this->log();

        return $this->avatar;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canBypassPermissionLevelIfEmployee($this->data['employee_id'])
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
    }

    private function getPhotoData(): array
    {
        $photo = $this->data['photo'];

        return [
            'company_id' => $this->data['company_id'],
            'employee_id' => $this->data['employee_id'],
            'original_filename' => $photo->getClientOriginalName(),
            'new_filename' => $photo->storePublicly('avatars', config('filesystems.default')),
            'extension' => $photo->extension(),
            'size' => $photo->getSize(),
        ];
    }

    private function savePhoto(array $photoData): void
    {
        $this->avatar = Avatar::create($photoData);
    }

    private function setAllOtherAvatarsAsInactive(): void
    {
        Avatar::where('id', '!=', $this->avatar->id)->update([
            'active' => false,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_avatar_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_avatar_set',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([]),
        ])->onQueue('low');
    }
}
