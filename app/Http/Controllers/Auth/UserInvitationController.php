<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserInvitationController extends Controller
{
    /**
     * Validates the invitation page.
     *
     * @param Request $request
     * @param string  $invitationLink
     *
     * @return Response
     */
    public function check(Request $request, string $invitationLink)
    {
        try {
            $employee = Employee::where('invitation_link', $invitationLink)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return Inertia::render('Auth/Invitation/InvalidInvitationLink', []);
        }

        if ($employee->invitation_used_at) {
            return Inertia::render('Auth/Invitation/InvitationLinkAlreadyAccepted', []);
        }

        if (Auth::check()) {
            return Inertia::render('Auth/Invitation/AcceptInvitation', [
                'company' => $employee->company,
                'employee' => $employee->toObject(),
                'invitationLink' => $invitationLink,
                'user' => Auth::user()->getEmployeeObjectForCompany($employee->company),
            ]);
        }

        return Inertia::render('Auth/Invitation/AcceptInvitationUnlogged', [
            'company' => $employee->company,
            'employee' => $employee->toObject(),
            'invitationLink' => $invitationLink,
        ]);
    }

    /**
     * Create or login with a user account to accept the invitation.
     * We use the same route to check both actions.
     *
     * @param Request $request
     * @param string $invitationLink
     */
    public function join(Request $request, string $invitationLink)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $requestInputs = [
            'email' => $email,
            'password' => $password,
        ];

        try {
            User::where('email', $email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // email doesn't exist yet, create the account
            (new CreateAccount)->execute($requestInputs);
        }

        Auth::attempt($requestInputs);

        // mark the link as used
        $employee = Employee::where('invitation_link', $invitationLink)
            ->firstOrFail();

        $employee->invitation_used_at = Carbon::now();
        $employee->user_id = Auth::user()->id;
        $employee->save();
    }
}
