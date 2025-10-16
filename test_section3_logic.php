<?php
// Test the Section 3 logic with the example data provided

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

// Calculate variance using only Actual values from past quarters
$variance = 0;
if ($annualBudget !== 0) {
    $variance = (($annualBudget - $annualActual) / $annualBudget) * 100;
}

// Format the output table
echo "| Category             | Q3 Budget | Q3 Actual  | Q4 Budget | Q4 Forecast    | Q1 Budget | Q1 Forecast | Q2 Budget | Q2 Forecast | Annual Budget | Annual Actual | Variance (%) |\n";
echo "|----------------------|-----------|------------|-----------|----------------|-----------|-------------|-----------|-------------|---------------|---------------|--------------|\n";

printf("| %-20s | %9.0f | %10.2f | %9.0f | %14.0f | %9.0f | %11.0f | %9.0f | %11.0f | %13.0f | %13.2f | %12.2f%% |\n",
    "Administrative costs",
    $quarters['Q3']['budget'],
    $quarters['Q3']['actual'],
    $quarters['Q4']['budget'],
    $quarters['Q4']['forecast'],
    $quarters['Q1']['budget'],
    $quarters['Q1']['forecast'],
    $quarters['Q2']['budget'],
    $quarters['Q2']['forecast'],
    $annualBudget,
    $annualActual,
    $variance
);

echo "\nExpected output:\n";
echo "| Category             | Q3 Budget | Q3 Actual  | Q4 Budget | Q4 Forecast    | Q1 Budget | Q1 Forecast | Q2 Budget | Q2 Forecast | Annual Budget | Annual Actual | Variance (%) |\n";
echo "|----------------------|-----------|------------|-----------|----------------|-----------|-------------|-----------|-------------|---------------|---------------|--------------|\n";
echo "| Administrative costs | 259,650   | 10,000.50  | 1,272,000 | 27,999,999     | 610,800   | 0           | 610,800   | 0           | 2,753,250     | 10,000.50     | 99.64%       |\n";

echo "\nActual values:\n";
echo "Annual Budget: " . number_format($annualBudget, 0) . "\n";
echo "Annual Actual: " . number_format($annualActual, 2) . "\n";
echo "Variance: " . number_format($variance, 2) . "%\n";

// Verify the results
$expectedAnnualBudget = 2753250;
$expectedAnnualActual = 10000.50;
$expectedVariance = 99.64;

if (abs($annualBudget - $expectedAnnualBudget) < 1 && 
    abs($annualActual - $expectedAnnualActual) < 0.01 && 
    abs($variance - $expectedVariance) < 0.01) {
    echo "\n✓ Test PASSED! All values match the expected output.\n";
} else {
    echo "\n✗ Test FAILED! Values do not match the expected output.\n";
}
?>