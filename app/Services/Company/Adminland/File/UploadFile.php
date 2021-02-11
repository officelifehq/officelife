<?php

namespace App\Services\Company\Adminland\File;

use App\Models\Company\File;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class UploadFile extends BaseService
{
    private array $data;

    private File $file;

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
     * Upload the file.
     */
    private function upload(): void
    {
        $file = Storage::putFile('files', $this->data['file']);

        $this->file = File::create([
            'company_id' => $this->data['company_id'],
            'filename' => $this->data['file']->getClientOriginalName(),
            'extension' => $this->data['file']->guessClientExtension(),
            'size_in_kb' => Storage::size($file),
        ]);
    }
}
