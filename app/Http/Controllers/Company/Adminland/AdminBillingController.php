<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\CompanyInvoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Adminland\AdminBillingViewHelper;

class AdminBillingController extends Controller
{
    /**
     * Show the Invoices & billing page.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        return Inertia::render('Adminland/Billing/Index', [
            'invoices' => AdminBillingViewHelper::index($loggedCompany),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the details of the invoice.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $invoiceId
     * @return Response
     */
    public function show(Request $request, int $companyId, int $invoiceId): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $invoice = CompanyInvoice::where('company_id', $loggedCompany->id)
                ->findOrFail($invoiceId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Adminland/Billing/Show', [
            'invoice' => AdminBillingViewHelper::show($invoice),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
