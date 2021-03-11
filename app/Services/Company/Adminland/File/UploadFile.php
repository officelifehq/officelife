<?php

namespace App\Services\Company\Adminland\File;

use App\Models\Company\File;
use App\Services\BaseService;
use App\Exceptions\EnvVariablesNotSetException;

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
            'payload' => 'required|json',
            'type' => 'required|string',
        ];
    }

    /**
     * Upload a file.
     *
     * This doesn't really upload a file though. Upload is handled by Uploadcare.
     * However, we abstract uploads by the File object. This service here takes
     * the payload that Uploadcare sends us back, and map it into a File object
     * that the clients will consume.
     *
     * @param array $data
     * @return File
     */
    public function execute(array $data): File
    {
        $this->data = $data;
        $this->validate();
        $this->mapPayloadToObject();

        return $this->file;
    }

    private function validate(): void
    {
        if (is_null(config('officelife.uploadcare_private_key'))) {
            throw new EnvVariablesNotSetException();
        }

        if (is_null(config('officelife.uploadcare_public_key'))) {
            throw new EnvVariablesNotSetException();
        }

        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();
    }

    private function mapPayloadToObject(): void
    {
        $payload = json_decode($this->data['payload']);

        $this->file = File::create([
            'company_id' => $this->data['company_id'],
            'uuid' => $payload[0]->uuid,
            'url' => $payload[0]->url,
            'mime_type' => $payload[0]->mime_type,
            'size' => $payload[0]->size,
            'type' => $this->data['type'],
        ]);
    }
}
