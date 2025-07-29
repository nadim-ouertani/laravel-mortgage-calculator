<?php

namespace App\Domain\Loan\Contracts;

use App\Domain\Loan\Entities\Loan;

interface LoanRepositoryInterface
{
    public function save(Loan $loan): Loan;
    
    public function findById(int $id): ?Loan;
    
    public function delete(int $id): bool;
}