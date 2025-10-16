<?php
// Test the variance formula switch logic with the example data provided

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
$quarters = [
    'Q3' => ['budget' => 259650, 'actual' => 10000.50, 'forecast' => 249649.50],
    'Q4' => ['budget' => 1272000, 'actual' => 0, 'forecast' => 27999999],
    'Q1' => ['budget' => 610800, 'actual' => 0, 'forecast' => 0],
    'Q2' => ['budget' => 610800, 'actual' => 0, 'forecast' => 0]
];

// Calculate annual totals according to the new logic
$annualBudget = 0;
$annualActual = 0; // Only sum Actual values from past quarters
$actualPlusForecast = 0;

foreach ($quarters as $quarter => $data) {
    $annualBudget += $data['budget'];
    
    // For Annual Actual: only sum Actual values from past quarters
    if ($quarter === 'Q3') { // Q3 is the past quarter in this example
        $annualActual += $data['actual'];
    }
    
    // Calculate Actual + Forecast based on display logic
    // Past quarter (Q3) shows Actual
    if ($quarter === 'Q3') {
        $actualPlusForecast += $data['actual'];
    } else {
        // Current and future quarters (Q4, Q1, Q2) show Forecast
        $actualPlusForecast += $data['forecast'];
    }
}

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

// Expected results from user's example
echo "\n=== Expected Results from User's Example ===\n";
echo "Annual Budget: 2,753,250.00\n";
echo "Annual Actual: 10,000.50\n";
echo "Variance (Budget vs Actual): 99.64%\n";

// Verify the results
$expectedAnnualBudget = 2753250;
$expectedAnnualActual = 10000.50;
$expectedVariance = 99.64;

if (abs($annualBudget - $expectedAnnualBudget) < 1 && 
    abs($annualActual - $expectedAnnualActual) < 0.01 && 
    abs($variance_budget_actual - $expectedVariance) < 0.01) {
    echo "\n✓ Test PASSED! All values match the expected output.\n";
} else {
    echo "\n✗ Test FAILED! Values do not match the expected output.\n";
    echo "  Annual Budget: Expected {$expectedAnnualBudget}, Got {$annualBudget}\n";
    echo "  Annual Actual: Expected {$expectedAnnualActual}, Got {$annualActual}\n";
    echo "  Variance: Expected {$expectedVariance}%, Got " . number_format($variance_budget_actual, 2) . "%\n";
}
?>