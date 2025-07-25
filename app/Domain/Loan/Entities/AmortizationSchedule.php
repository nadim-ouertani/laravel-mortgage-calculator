<?php

namespace App\Domain\Loan\Entities;

class AmortizationSchedule
{
    private int $loanId;
    private array $entries;

    public function __construct(int $loanId)
    {
        $this->loanId = $loanId;
        $this->entries = [];
    }

    public function getLoanId(): int
    {
        return $this->loanId;
    }

    public function addEntry(PaymentScheduleEntry $entry): void
    {
        $this->entries[] = $entry;
    }

    public function getEntries(): array
    {
        return $this->entries;
    }

    public function getTotalInterestPaid(): float
    {
        return array_sum(array_map(fn($entry) => $entry->getInterestComponent(), $this->entries));
    }

    public function getTotalPrincipalPaid(): float
    {
        return array_sum(array_map(fn($entry) => $entry->getPrincipalComponent(), $this->entries));
    }

    public function getTotalExtraPayments(): float
    {
        return array_sum(array_map(fn($entry) => $entry->getExtraPayment(), $this->entries));
    }

    public function getActualTerm(): int
    {
        return count($this->entries);
    }

    public function isEmpty(): bool
    {
        return empty($this->entries);
    }

    public function getTotalPayments(): int
    {
        return count($this->entries);
    }

    public function getTotalAmountPaid(): float
    {
        return $this->getTotalInterestPaid() + $this->getTotalPrincipalPaid();
    }

    public function last(): ?PaymentScheduleEntry
    {
        return empty($this->entries) ? null : end($this->entries);
    }
}