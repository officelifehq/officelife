<?php

namespace App\Services\User\Avatar;

use App\Models\User\Avatar;
use App\Services\BaseService;
use App\Models\Company\Employee;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ResizeAvatar extends BaseService
{
    private array $data;

    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'width' => 'required|integer',
        ];
    }

    /**
     * Resize an avatar.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;

        $this->validate();
        $this->save();
        $this->resize();
        $this->log();

        return $this->employee;
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

    private function save(): void
    {
        Employee::where('id', $this->data['employee_id'])
            ->update([
                'avatar' => $this->data['photo']->storePublicly('avatars', config('filesystems.default')),
                'avatar_original_filename' => $this->data['photo']->getClientOriginalName(),
                'avatar_extension' => $this->data['photo']->extension(),
                'avatar_size' => $this->data['photo']->getSize(),
            ]);

        $this->employee->refresh();
    }

    private function resize(): void
    {
        $avatar = Image::make(Storage::disk(config('filesystems.default'))->get($this->employee->avatar));
        $avatar->fit(50);
        Storage::disk(config('filesystems.default'))->put($this->employee->avatar, (string) $avatar->stream(), 'public');
    }
}
