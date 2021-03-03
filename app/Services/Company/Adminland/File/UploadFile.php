<?php

namespace App\Services\Company\Adminland\File;

use Illuminate\Support\Str;
use App\Models\Company\File;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class UploadFile extends BaseService
{
    private array $data;

    private File $file;

    private string $hashedName;

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
            'file' => 'required|file',
        ];
    }

    /**
     * Import a file.
     *
     * @param array $data
     * @return File
     */
    public function execute(array $data): File
    {
        $this->data = $data;
        $this->validate();
        $this->hashName();
        $this->upload();

        return $this->file;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();
    }

    /**
     * We need to handle names of the files ourselves. Why? Because in the case
     * of a CSV, Laravel, for some reasons, change the extension to .txt and
     * this causes problems.
     */
    private function hashName(): void
    {
        $this->hashedName = Str::random(40).'.'.$this->data['file']->getClientOriginalExtension();
    }

    /**
     * Upload the file.
     */
    private function upload(): void
    {
        $file = Storage::putFileAs('files', $this->data['file'], $this->hashedName);

        $this->file = File::create([
            'company_id' => $this->data['company_id'],
            'filename' => $this->data['file']->getClientOriginalName(),
            'extension' => $this->data['file']->guessClientExtension(),
            'size_in_kb' => Storage::size($file),
            'hashed_filename' => $file,
        ]);
    }
}
