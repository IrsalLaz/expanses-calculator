<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction = Transaction::latest()->get();
        $sum_income = $transaction->where('type_id', 1)->sum('amount');
        $sum_expanse = $transaction->where('type_id', 2)->sum('amount');

        $types = Type::all(); // types : income and expanse
        $categories = Category::all();

        $balance = $sum_income - $sum_expanse;

        $income_data = Transaction::where('type_id', 1)
            ->select('category_id', DB::raw('sum(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $income_labels = $income_data->pluck('category.name');
        $income_amounts = $income_data->pluck('total');

        // Group by category for expanse
        $expanse_data = Transaction::where('type_id', 2)
            ->select('category_id', DB::raw('sum(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $expanse_labels = $expanse_data->pluck('category.name');
        $expanse_amounts = $expanse_data->pluck('total');

        return view('index', [
            'transactions' => $transaction,
            'balance' => number_format($balance, 0, ',', '.'),
            'income' => number_format($sum_income, 0, ',', '.'),
            'expanse' => number_format($sum_expanse, 0, ',', '.'),
            'types' => $types,
            'categories' => $categories,
            'income_labels' => $income_labels, // Pass labels for income chart
            'income_amounts' => $income_amounts, // Pass amounts for income chart
            'expanse_labels' => $expanse_labels, // Pass labels for expanse chart
            'expanse_amounts' => $expanse_amounts, // Pass amounts for expanse chart
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // get data from request and validate
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type_id' => 'required',
            'category_id' => 'required',
        ]);

        Transaction::create($validatedData);
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // dd($transaction->id);

        Transaction::destroy($transaction->id);
        return redirect('/');
    }
}
