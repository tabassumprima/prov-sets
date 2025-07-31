<?php

namespace App\Http\Middleware;

use App\Helpers\CustomHelper;
use Closure;
use Exception;
use Illuminate\Http\Request;

class PreventDbTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {    
        $running_import_data = CustomHelper::fetchSessionImport($request);

        if ( $running_import_data->isEmpty() ) 
            CustomHelper::fetchSessionProvision($request);

        $active_provision = $request->session()->get('active_provision');
        $type = $request->session()->get('type');
      
        if ($active_provision && in_array($type, ['provision', 'import', 'opening', 'posting'])) {
            $message = "Cannot perform transactions while {$type} is running";
        } else {
            $message = 'Unapproved entries pending';
        }

        if ($active_provision || $request->session()->get('provision_alert')) {
            //Return json response if request is expecting json
            if ($request->expectsJson()) {
                session()->flash('error', $message);
                return response()->json();
            } else
                return redirect()->back()->withErrors(['error' => $message]);
        }

        return $next($request);
    }
}
