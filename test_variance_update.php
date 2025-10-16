<?php
// Test the variance calculation with both formulas

// Example data from the user query:
// Category: Administrative costs
// Current quarter: Q4
// Q3: Budget=259,650, Actual=10,000.50, Forecast=249,649.50
// Q4: Budget=1,272,000, Forecast=27,999,999
// Q1: Budget=610,800, Forecast=0
// Q2: Budget=610,800, Forecast=0

// Set current quarter to Q4 for testing
$currentQuarter = 4;
$pastQuarter = (($currentQuarter - 2 + 4) % 4) + 1; // previous quarter (Q3)

// Data
$data = [
    'Q3' => ['budget' => 259650, 'actual' => 10000.50, 'forecast' => 249649.50],
    'Q4' => ['budget' => 1272000, 'actual' => 0, 'forecast' => 27999999],
    'Q1' => ['budget' => 610800, 'actual' => 0, 'forecast' => 0],
    'Q2' => ['budget' => 610800, 'actual' => 0, 'forecast' => 0]
];

// Calculate annual totals
$annualBudget = array_sum(array_column($data, 'budget'));

// Annual Actual: only sum Actual values from past quarters (Q3 in this case)
$annualActual = $data['Q3']['actual'];

// Actual + Forecast: Past quarter shows Actual, current and future show Forecast
$actualPlusForecast = $data['Q3']['actual'] + $data['Q4']['forecast'] + $data['Q1']['forecast'] + $data['Q2']['forecast'];

echo "=== Test Data ===\n";
echo "Current Quarter: Q{$currentQuarter}\n";
echo "Past Quarter: Q{$pastQuarter}\n";
echo "Annual Budget: " . number_format($annualBudget, 2) . "\n";
echo "Annual Actual (only from past quarter): " . number_format($annualActual, 2) . "\n";
echo "Actual + Forecast: " . number_format($actualPlusForecast, 2) . "\n";

// Test "Budget vs Actual" formula
$variance_budget_actual = 0;
if ($annualBudget !== 0) {
    $variance_budget_actual = (($annualBudget - $annualActual) / $annualBudget) * 100;
}

echo "\n=== Budget vs Actual Formula ===\n";
echo "Variance (%) = (Annual Budget - Annual Actual) / Annual Budget * 100\n";
echo "Variance (%) = ({$annualBudget} - {$annualActual}) / {$annualBudget} * 100\n";
echo "Variance (%) = " . number_format($variance_budget_actual, 2) . "%\n";

// Test "Budget vs Actual + Forecast" formula
$variance_budget_actual_forecast = 0;
if ($annualBudget !== 0) {
    $variance_budget_actual_forecast = (($annualBudget - $actualPlusForecast) / $annualBudget) * 100;
}

echo "\n=== Budget vs Actual + Forecast Formula ===\n";
echo "Variance (%) = (Annual Budget - (Annual Actual + Forecast)) / Annual Budget * 100\n";
echo "Variance (%) = ({$annualBudget} - {$actualPlusForecast}) / {$annualBudget} * 100\n";
echo "Variance (%) = " . number_format($variance_budget_actual_forecast, 2) . "%\n";

echo "\n=== Expected Results ===\n";
echo "With Budget vs Actual: 99.64%\n";
echo "With Budget vs Actual + Forecast: -917.47%\n";

// Verify the results
echo "\n=== Verification ===\n";
if (abs($variance_budget_actual - 99.64) < 0.01) {
    echo "✓ Budget vs Actual calculation is correct\n";
} else {
    echo "✗ Budget vs Actual calculation is incorrect\n";
}

if (abs($variance_budget_actual_forecast - (-917.47)) < 0.01) {
    echo "✓ Budget vs Actual + Forecast calculation is correct\n";
} else {
    echo "✗ Budget vs Actual + Forecast calculation is incorrect\n";
}
?>