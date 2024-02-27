<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Balance extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];

    public function deposit(float $value) : Array
    {
        DB::beginTransaction();

        $totalBefore = $this->amount ? $this->amount : 0;
        $this->amount += number_format($value, 2, '.', '');
        $deposit = $this->save();

        $historic = auth()->user()->historics()->create([ //substituir por usuario autenticado
        // $historic = User::find(7)->historics()->create([ //substituir por usuario autenticado
            'type'          => 'I',
            'amount'        => $value,
            'total_before'  => $totalBefore,
            'total_after'   => $this->amount,
            'date'          => date('Y/m/d H:i:s'),
        ]);

        if ($deposit && $historic) {

            DB::commit();            
            
            return [
                'success' => true,
                'message' => 'Sucesso ao recarregar'
            ];
        } else {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Falha ao carregar'
            ];
        }
    }
}
