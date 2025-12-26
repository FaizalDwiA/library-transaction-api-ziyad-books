<?php

namespace App\Http\Controllers;

use App\Models\BorrowTransaction;
use App\Services\BorrowService; 
use App\Http\Requests\StoreBorrowTransactionRequest;
use App\Http\Requests\UpdateBorrowTransactionRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        // Validasi input menggunakan Form Request
        // Pendekatan ini dipilih agar validasi terpisah dari logic controller
        // Dan memanfaatkan rule Laravel: user_id & book_id harus ada di tabel masing-masing
         $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
        ]);

        // Generate trace_id unik untuk request ini
        $traceId = (string) Str::uuid();

        try {
            // Memanggil service BorrowService
            // Dipisahkan ke service untuk menjaga Single Responsibility Principle
            $this->borrowService->borrow(
                $request->user_id,
                $request->book_id
            );

            // Log transaksi sukses di controller
            Log::info(
                "Transaksi peminjaman berhasil: \n" . 
                json_encode([
                    'trace_id' => $traceId,
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                ], JSON_PRETTY_PRINT)
            );

            // Jika berhasil, kembalikan response JSON dengan HTTP 201 Created
            // Pilihan 201 sesuai standar RESTful untuk resource baru
            return response()->json([
                'message' => 'Borrow success'
            ], 201);

        } catch (\Exception $e) {
            // Default response error
            $message = 'Terjadi kesalahan sistem';
            $errorCode = 'ZYD-ERR-999';

            // Custom exception handling
            // Memetakan exception dari service ke error code & message yang spesifik
            // Tujuan: agar API client tahu penyebab error dengan jelas
            if ($e->getMessage() === 'STOCK_EMPTY') {
                $message = 'Stok buku habis';
                $errorCode = 'ZYD-ERR-001';
            } elseif ($e->getMessage() === 'QUOTA_EXCEEDED') {
                $message = 'Kuota peminjaman habis';
                $errorCode = 'ZYD-ERR-002';
            }

            // Log error di controller
            Log::error(
                "Transaksi peminjaman gagal: " . $message . "\n" .
                json_encode([
                    'trace_id' => $traceId,
                    'user_id' => $request->user_id,
                    'book_id' => $request->book_id,
                    'exception' => $e->getMessage(),
                ], JSON_PRETTY_PRINT)
            );

            // Kembalikan response JSON custom
            // trace_id dibuat unik setiap request untuk mempermudah tracking error
            return response()->json([
                'message' => $message,
                'ziyad_error_code' => $errorCode,
                'trace_id' => $traceId
            ], 409); // 409 Conflict sesuai konteks transaksi gagal
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
