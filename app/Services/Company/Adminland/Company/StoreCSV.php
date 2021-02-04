<?php

namespace App\Services\Company\Adminland\Company;

use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class StoreCSV extends BaseService
{
    private array $data;

    private string $path;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|max:10240|mimes:csv',
        ];
    }

    /**
     * Store the CSV on the disk.
     *
     * @param array $data
     * @return string
     */
    public function execute(array $data): string
    {
        $this->data = $data;
        $this->validate();
        $this->saveToDisk();

        return $this->path;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);
    }

    private function saveToDisk(): void
    {
        $this->path = Storage::disk('public')->putFile('csv', $this->data['file']);
    }
}
