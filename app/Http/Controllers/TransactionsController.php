<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function sell()
    {
        return view('transactions.sell');
    }
}
