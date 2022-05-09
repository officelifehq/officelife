<?php

namespace App\Services\Company\Adminland\Subscription;

use Exception;
use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Company;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Exceptions\NoCustomerPortalSetException;
use App\Exceptions\NoLicenceKeyEncryptionSetException;

class ActivateLicenceKey extends BaseService
{
    private Company $company;
    private array $data;
    private Response $response;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'licence_key' => 'required|string:255',
        ];
    }

    /**
     * Check if the licence key given by the user is a valid licence key.
     * If it is, activate the licence key and set the valid_until_at date.
     *
     * @param  array  $data
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);
        $this->data = $data;
        $this->company = Company::findOrFail($data['company_id']);

        $this->validateEnvVariables();
        $this->makeRequestToCustomerPortal();
        $this->checkResponseCode();
        $this->decodeAndStoreKey();
    }

    private function validateEnvVariables(): void
    {
        if (! config('officelife.licence_key_encryption_key')) {
            throw new NoLicenceKeyEncryptionSetException();
        }

        if (config('officelife.customer_portal_url') == '') {
            throw new NoCustomerPortalSetException();
        }
    }

    private function makeRequestToCustomerPortal(): void
    {
        $url = config('officelife.customer_portal_url').'/'.config('officelife.customer_portal_secret_key').'/validate/'.$this->data['licence_key'];

        // necessary for testing purposes
        if (App::environment('production')) {
            $this->response = Http::get($url);
        } else {
            $this->response = Http::withOptions(['verify' => false])->get($url);
        }
    }

    private function checkResponseCode(): void
    {
        if ($this->response->status() == 404) {
            throw new Exception(trans('settings.subscriptions_licence_key_does_not_exist'));
        }

        if ($this->response->status() == 900) {
            throw new Exception(trans('settings.subscriptions_licence_key_invalid'));
        }

        if ($this->response->status() != 200) {
            throw new Exception(trans('settings.subscriptions_licence_key_problem'));
        }
    }

    private function decodeAndStoreKey(): void
    {
        $licenceKey = base64_decode($this->data['licence_key']);
        $licenceKey = Str::replace('123', '', $licenceKey);
        $array = json_decode($licenceKey, true);

        $this->company->licence_key = $this->data['licence_key'];
        $this->company->valid_until_at = $array[0]['next_check_at'];
        $this->company->purchaser_email = $array[0]['purchaser_email'];
        $this->company->frequency = $array[0]['frequency'];
        $this->company->quantity = $array[0]['quantity'];
        $this->company->save();
    }
}
