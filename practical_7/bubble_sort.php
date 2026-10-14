<?php
// Bubble Sort in PHP

$numbers = array(64, 34, 25, 12, 22, 11, 90);
$n = count($numbers);

echo "Original Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}

// Bubble Sort
for ($i = 0; $i < $n - 1; $i++) {
    for ($j = 0; $j < $n - $i - 1; $j++) {

        // Compare adjacent elements
        if ($numbers[$j] > $numbers[$j + 1]) {

            // Swap elements
            $temp = $numbers[$j];
            $numbers[$j] = $numbers[$j + 1];
            $numbers[$j + 1] = $temp;
        }
    }
}

echo "<br><br>Sorted Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}
?>