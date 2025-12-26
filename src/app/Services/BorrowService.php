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
        // lockForUpdate digunakan untuk mencegah race condition
        // ketika dua request mencoba meminjam buku yang sama
        DB::transaction(function () use ($userId, $bookId) {

            // Mengunci baris buku agar tidak terjadi race condition
            // ketika dua request meminjam buku yang sama
            $book = Books::where('id', $bookId)
                ->lockForUpdate()
                ->firstOrFail();

            $user = User::findOrFail($userId);
            

            if ($book->stock <= 0) {
                throw new \Exception('STOCK_EMPTY');
            }

            if ($user->borrow_quota <= 0) {
                throw new \Exception('QUOTA_EXCEEDED');
            }

            // update stock & quota
            $book->decrement('stock');
            $user->decrement('borrow_quota');

            BorrowTransaction::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'borrowed_at' => now(),
            ]);

            echo "ok";
        });
    }
}
