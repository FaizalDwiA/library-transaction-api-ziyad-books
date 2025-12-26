<?php

namespace App\Http\Controllers;

use App\Models\BorrowTransaction;
use App\Services\BorrowService; 
use App\Http\Requests\StoreBorrowTransactionRequest;
use App\Http\Requests\UpdateBorrowTransactionRequest;

class BorrowTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected BorrowService $borrowService;

    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;
    }

    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBorrowTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBorrowTransactionRequest $request)
    {
         $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        try {
            $this->borrowService->borrow(
                $request->user_id,
                $request->book_id
            );

            return response()->json([
                'message' => 'Borrow success'
            ], 201);

        } catch (\Exception $e) {
            $message = 'Terjadi kesalahan sistem';
            $errorCode = 'ZYD-ERR-999';

            if ($e->getMessage() === 'STOCK_EMPTY') {
                $message = 'Stok buku habis';
                $errorCode = 'ZYD-ERR-001';
            } elseif ($e->getMessage() === 'QUOTA_EXCEEDED') {
                $message = 'Kuota peminjaman habis';
                $errorCode = 'ZYD-ERR-002';
            }

            return response()->json([
                'message' => $message,
                'ziyad_error_code' => $errorCode,
                'trace_id' => (string) \Illuminate\Support\Str::uuid()
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BorrowTransaction  $borrowTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(BorrowTransaction $borrowTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BorrowTransaction  $borrowTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(BorrowTransaction $borrowTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBorrowTransactionRequest  $request
     * @param  \App\Models\BorrowTransaction  $borrowTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBorrowTransactionRequest $request, BorrowTransaction $borrowTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BorrowTransaction  $borrowTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(BorrowTransaction $borrowTransaction)
    {
        //
    }
}
