<?php

namespace App\Exceptions\Admin;

use Exception;

class UserDepositFailed extends Exception
{
    protected $message = 'Deposit failed';

    public function render()
    {
        return response()->json([
            'error' => class_basename($this),
            'message' => $this->getMessage(),
        ], 400);
    }
}
