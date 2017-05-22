<?php

$input = $argv[1];
$output = $argv[2];

if (empty($input) || empty($output)) {
    exit('Please use: "php test_1.php /path/to/input.txt /path/to/output.txt"' . PHP_EOL);
}

$resInput = fopen($input, 'r');
$resOutput = fopen($output, 'w+');
$wordPosition = 0;

while(($string = fgets($resInput)) !== false) {
    $wordsArray = explode(' ', $string);
    $wordsArrayCount = count($wordsArray);
    for ($i = 0; $i < $wordsArrayCount; $i++) {
        $wordPosition++;
        if ($wordPosition % 15 === 0) {
            $wordsArray[$i] = '-FIFTEEN-';
        } else if ($wordPosition % 5 === 0) {
            $wordsArray[$i] = '-FIVE-';
        } else if ($wordPosition % 3 === 0) {
            $wordsArray[$i] = '-THREE-';
        }
    }

    $string = implode(' ', $wordsArray);
    fwrite($resOutput, $string);
}

fclose($resOutput);
fclose($resInput);