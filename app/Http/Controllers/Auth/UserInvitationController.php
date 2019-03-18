<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Company\Employee;

class UserInvitationController extends Controller
{
    /**
     * Validates the invitation page.
     *
     * @param Request $request
     * @param string $invitationLink
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request, string $invitationLink)
    {
        try {
            $employee = Employee::where('invitation_link', $invitationLink)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return View::component('InvalidInvitationLink', []);
        }

        if ($employee->invitationAlreadyAccepted()) {
            return View::component('InvitationLinkAlreadyAccepted', []);
        }

        //YOU ARE INVITED TO JOIN THE COMPANY
        return View::component('ShowInviteEmployeeAcceptancePage', [
            'company' => $employee->company,
            'employee' => new EmployeeResource($employee),
        ]);
    }
}
