<?php
// Selection Sort in PHP

$numbers = array(64, 25, 12, 22, 11);
$n = count($numbers);

echo "Original Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}

// Selection Sort
for ($i = 0; $i < $n - 1; $i++) {

    // Assume current element is minimum
    $minIndex = $i;

    // Find the smallest element
    for ($j = $i + 1; $j < $n; $j++) {
        if ($numbers[$j] < $numbers[$minIndex]) {
            $minIndex = $j;
        }
    }

    // Swap
    $temp = $numbers[$i];
    $numbers[$i] = $numbers[$minIndex];
    $numbers[$minIndex] = $temp;
}

echo "<br><br>Sorted Array:<br>";
foreach ($numbers as $value) {
    echo $value . " ";
}
?>