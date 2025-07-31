<?php

namespace App\Helpers;

class RouterHelper
{
    public function redirect($entity, $error, $message, array $params = null)
    {
        return redirect()->route($entity, $params)->with([
            $error ? 'error' : 'success' => $message
        ]);
    }

    public function redirectBack($error, $message)
    {
        return redirect()->back()->with([
            $error ? 'error' : 'success' => $message
        ]);
    }
}
