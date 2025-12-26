<?php

namespace App\Services;

use App\Models\User;
use App\Models\Books;
use App\Models\BorrowTransaction;
use Illuminate\Support\Facades\DB;

class BorrowService
{
    public function borrow(int $userId, int $bookId): void
    {
        // DB::transaction digunakan agar seluruh operasi menjadi atomic
        // Artinya: jika salah satu langkah gagal, semua perubahan akan di-rollback
        // Pendekatan ini dipilih untuk menjaga konsistensi data (stock, quota, transaksi)
        DB::transaction(function () use ($userId, $bookId) {

            // lockForUpdate mengunci baris buku
            // Tujuannya: mencegah race condition saat dua request meminjam buku yang sama
            // Tanpa ini, dua request bisa membaca stock bersamaan dan menyebabkan stock negatif
            $book = Books::where('id', $bookId)
                ->lockForUpdate()
                ->firstOrFail();

            // Ambil user dari DB
            // findOrFail dipilih agar langsung memberikan error jika user_id tidak valid
            $user = User::findOrFail($userId);
            
            // Validasi stock
            // Menggunakan exception agar bisa ditangani di controller dengan custom response
            if ($book->stock <= 0) {
                throw new \Exception('STOCK_EMPTY');
            }

            // Validasi kuota user
            if ($user->borrow_quota <= 0) {
                throw new \Exception('QUOTA_EXCEEDED');
            }

            // Update stock dan quota
            // decrement dipilih karena lebih efisien daripada read-modify-write manual
            $book->decrement('stock');
            $user->decrement('borrow_quota');

            // Buat record transaksi
            // create() dipilih agar sekaligus memasukkan borrowed_at sekarang
            // Dilakukan di dalam transaction agar rollback semua jika gagal
            BorrowTransaction::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'borrowed_at' => now(),
            ]);
        });
    }
}
