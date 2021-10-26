<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company\ProjectBoard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CheckBoard
{
    /**
     * Check that the board can be access.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestedProjectId = $request->route()->parameter('project');
        $requestedBoardId = $request->route()->parameter('board');

        try {
            $board = ProjectBoard::where('project_id', $requestedProjectId)
                ->findOrFail($requestedBoardId);

            $request->attributes->add(['board' => $board]);

            return $next($request);
        } catch (ModelNotFoundException $e) {
            abort(401);
        }
    }
}
