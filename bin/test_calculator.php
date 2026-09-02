<?php

declare(strict_types=1);

echo "Testing Loan Calculator Mathematics...\n";

function calculateLoan(float $principal, float $annualRate, int $months, string $type = 'effective'): array {
    $monthlyRate = ($annualRate / 100) / 12;
    $schedule = [];
    $remaining = $principal;
    $totalInterest = 0;

    if ($type === 'flat') {
        $totalInterest = $principal * ($annualRate / 100) * ($months / 12);
        $totalPayment = $principal + $totalInterest;
        $monthlyPayment = $totalPayment / $months;
        $monthlyPrincipal = $principal / $months;
        $monthlyInterestPortion = $totalInterest / $months;

        for ($i = 1; $i <= $months; $i++) {
            $remaining -= $monthlyPrincipal;
            $schedule[] = [
                'month' => $i,
                'payment' => round($monthlyPayment, 2),
                'principal' => round($monthlyPrincipal, 2),
                'interest' => round($monthlyInterestPortion, 2),
                'balance' => max(0, round($remaining, 2))
            ];
        }
    } else {
        // Effective / Reducing balance
        if ($monthlyRate > 0) {
            $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1);
        } else {
            $monthlyPayment = $principal / $months;
        }

        for ($i = 1; $i <= $months; $i++) {
            $interest = $remaining * $monthlyRate;
            $principalPortion = $monthlyPayment - $interest;

            if ($i === $months || $remaining - $principalPortion < 1) {
                $principalPortion = $remaining;
                $monthlyPayment = $principalPortion + $interest;
                $remaining = 0;
            } else {
                $remaining -= $principalPortion;
            }

            $totalInterest += $interest;
            $schedule[] = [
                'month' => $i,
                'payment' => round($monthlyPayment, 2),
                'principal' => round($principalPortion, 2),
                'interest' => round($interest, 2),
                'balance' => max(0, round($remaining, 2))
            ];
        }
    }

    return [
        'monthly_payment' => round($schedule[0]['payment'], 2),
        'total_interest' => round($totalInterest, 2),
        'total_payment' => round($principal + $totalInterest, 2),
        'schedule_count' => count($schedule),
        'ending_balance' => (float)$schedule[count($schedule) - 1]['balance']
    ];
}

// Test Case 1: 1,000,000 THB at 5.5% for 120 months (Effective Rate)
$result1 = calculateLoan(1000000, 5.5, 120, 'effective');
assert($result1['monthly_payment'] > 10800 && $result1['monthly_payment'] < 10900, "Effective monthly payment in expected range");
assert($result1['ending_balance'] == 0.0, "Loan ending balance should be 0");
assert($result1['schedule_count'] === 120, "Schedule must have 120 rows");
echo "✓ Test Case 1 (1,000,000 THB @ 5.5% 120m Effective) Passed! Monthly: " . number_format($result1['monthly_payment'], 2) . " THB | Total Interest: " . number_format($result1['total_interest'], 2) . " THB\n";

// Test Case 2: 100,000 THB at 6.0% for 24 months (Flat Rate)
$result2 = calculateLoan(100000, 6.0, 24, 'flat');
assert($result2['total_interest'] == 12000.0, "Flat interest calculation exact match");
assert($result2['ending_balance'] == 0.0, "Flat loan ending balance should be 0");
echo "✓ Test Case 2 (100,000 THB @ 6.0% 24m Flat) Passed! Total Interest: " . number_format($result2['total_interest'], 2) . " THB\n";

echo "All Loan Calculator mathematical test assertions passed!\n";
