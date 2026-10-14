<?php
// Insertion Sort in PHP

$numbers = array(12, 11, 13, 5, 6);
$n = count($numbers);

echo "Original Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}

// Insertion Sort
for ($i = 1; $i < $n; $i++) {

    // Current element
    $key = $numbers[$i];

    // Previous element
    $j = $i - 1;

    // Move elements greater than key
    // one position ahead
    while ($j >= 0 && $numbers[$j] > $key) {
        $numbers[$j + 1] = $numbers[$j];
        $j--;
    }

    // Insert key at correct position
    $numbers[$j + 1] = $key;
}

echo "<br><br>Sorted Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}
?>