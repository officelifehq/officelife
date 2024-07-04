<?php

namespace App\Http\Middleware;

use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckProject
{
    /**
     * Check that the user can access this project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestedProjectId = $request->route()->parameter('project');
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($requestedProjectId);

            $request->attributes->add(['project' => $project]);

            return $next($request);
        } catch (ModelNotFoundException $e) {
            abort(401);
        }
    }
}
