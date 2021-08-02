<?php

namespace App\Services\Company\Adminland\Company;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Company;

class UpdateCompanySlug extends BaseService
{
    private Company $company;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }

    /**
     * Updates the company slug.
     *
     * @param array $data
     *
     * @return Company
     */
    public function execute(array $data): Company
    {
        $this->company = Company::find($data['company_id']);

        $slug = Str::slug($this->company->name, '-');

        $isSlugUnique = false;
        while ($isSlugUnique === false) {
            $existingCompaniesWithThisSlug = Company::where('slug', '=', $slug)
                ->where('id', '!=', $this->company->id)
                ->count();

            if ($existingCompaniesWithThisSlug > 0) {
                $slug = $slug.'-'.rand(1, 999);
            }

            $isSlugUnique = Company::where('slug', $slug)
                ->where('id', '!=', $this->company->id)
                ->count() == 0;
        }

        $this->company->slug = $slug;
        $this->company->save();

        return $this->company->refresh();
    }
}
