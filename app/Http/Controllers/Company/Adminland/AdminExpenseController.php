<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminExpenseViewHelper;
use App\Services\Company\Adminland\ExpenseCategory\CreateExpenseCategory;

class AdminExpenseController extends Controller
{
    /**
     * Show the list of expense categories.
     *
     * @return Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        $categoriesCollection = AdminExpenseViewHelper::categories($company);

        return Inertia::render('Adminland/Expense/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'categories' => $categoriesCollection,
        ]);
    }

    /**
     * Create a new expense category.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        $category = (new CreateExpenseCategory)->execute($request);

        return response()->json([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'url' => route('account.expenses.show', [
                    'company' => $loggedCompany->id,
                    'expense' => $category->id,
                ]),
            ],
        ], 201);
    }
}
