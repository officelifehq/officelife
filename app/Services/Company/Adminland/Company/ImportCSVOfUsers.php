<?php

namespace App\Services\Company\Adminland\Company;

use App\Services\BaseService;
use App\Models\Company\Company;
use App\Jobs\AddEmployeeToCompany;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportCSVOfUsers extends BaseService
{
    private array $data;

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
            'path' => 'required|string',
        ];
    }

    /**
     * Import a CSV file of users in the company.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->import();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
    }

    /**
     * Import the CSV.
     * The CSV should have the following lines in this order:
     * - first name
     * - last name
     * - email
     * - permission level
     * - send email.
     */
    private function import(): void
    {
        SimpleExcelReader::create($this->data['path'])
            ->trimHeaderRow()
            ->headersToSnakeCase()
            ->getRows()
            ->each(function (array $rowProperties) {
                AddEmployeeToCompany::dispatch([
                    'company_id' => $this->data['company_id'],
                    'author_id' => $this->data['author_id'],
                    'email' => $rowProperties['email'],
                    'first_name' => $rowProperties['first_name'],
                    'last_name' => $rowProperties['last_name'],
                    'permission_level' => $rowProperties['permission_level'],
                    'send_invitation' => false,
                ])->onQueue('low');
            });
    }
}
