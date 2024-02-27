<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\Admin\UserDepositFailed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MoneyValidationFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        $balance = auth()->user()->balance;
        $amount = $balance ? $balance->amount : 0;
        
        return response()->json([
            "amount" => $amount,
        ]);
    }

    public function store(Request $request)
    {
        // $balance = User::find(7)->balance()->firstOrCreate([]); //substituir por usuario autenticado
        $balance = auth()->user()->balance()->firstOrCreate([]); //substituir por usuario autenticado
        $response = $balance->deposit($request->value);

        if (!$response['success']) {
            throw new UserDepositFailed();
        }
        return $response['message'];
    }
}
