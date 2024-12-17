<?php

namespace App\Services;

use App\Models\Receipt;
use Carbon\Carbon;

class FineCalculationService
{
    protected $finePerDay = 50; // ₱50 per day
    protected $gracePeriod = 3; // 3 days grace period

    public function calculateFine(Receipt $receipt): float
    {
        if (!$receipt->return_date || !$receipt->due_date) {
            return 0;
        }

        $dueDate = Carbon::parse($receipt->due_date);
        $returnDate = Carbon::parse($receipt->return_date);
        
        if ($returnDate->lte($dueDate)) {
            return 0;
        }

        $daysLate = $returnDate->diffInDays($dueDate);
        
        // Subtract grace period if applicable
        $daysLate = max(0, $daysLate - $this->gracePeriod);
        
        return $daysLate * $this->finePerDay;
    }

    public function applyFine(Receipt $receipt)
    {
        $fine = $this->calculateFine($receipt);
        
        $receipt->update([
            'fine_amount' => $fine,
            'status' => $fine > 0 ? 'overdue' : 'returned'
        ]);

        return $fine;
    }
} 