<?php

namespace App\Traits;

use Google2FA;
use Illuminate\Support\Facades\Log;

trait TwoFactorAuthenticatable
{
    public function generate2faSecret()
    {
        try {
        $model = $this->getModel();
        $model->google2fa_secret = Google2FA::generateSecretKey();
        $model->save();
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $this;
    }

    public function update2fa($value)
    {
        try {
        $model = $this->getModel();
        $model->google2fa_enable = $value;
        $model->save();
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $this;
    }

}


