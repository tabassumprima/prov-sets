<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;

class DecodeIds
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        #Decoding input ids
        $input = $request->all();
        if( isset($input['id']) )
        {
            $input['id'] = CustomHelper::decode($input['id']);
            $request->replace($input);
        }

        #Decoding dynamic ids in route parameters
        if( isset($request->route()->parameters()['id']) )
        {
            $decodedId = CustomHelper::decode($request->route()->parameters()['id']);
            $request->route()->setParameter('id', $decodedId);
        }

        return $next($request);
    }
}
