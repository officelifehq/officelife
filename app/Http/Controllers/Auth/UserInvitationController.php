<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
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
     * @param string $invitationLink
     *
     * @return Response
     */
    public function check(Request $request, string $invitationLink): Response
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
     *
     * @return JsonResponse
     */
    public function join(Request $request, string $invitationLink): JsonResponse
    {
        if (($user = Auth::user()) === null) {
            $email = $request->input('email');
            $password = $request->input('password');

            $requestInputs = [
                'email' => $email,
                'password' => $password,
            ];

            try {
                $user = User::where('email', $email)->firstOrFail();
                // The user already exists, and should log before...
                // TODO
            } catch (ModelNotFoundException $e) {
                // email doesn't exist yet, create the account
                $user = (new CreateAccount)->execute($requestInputs);

                $user->email_verified_at = Carbon::now();
                $user->save();

                /** @var \Illuminate\Contracts\Auth\StatefulGuard */
                $guard = Auth::guard();
                $guard->login($user);
            }
        }

        // mark the link as used
        $employee = Employee::where('invitation_link', $invitationLink)
            ->firstOrFail();

        Employee::where('id', $employee->id)->update([
            'invitation_used_at' => Carbon::now(),
            'user_id' => $user->id,
        ]);

        return new JsonResponse([], 204);
    }
}
