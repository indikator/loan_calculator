<?php
/**
 * Created by PhpStorm.
 * User: indikator
 * Date: 5/22/17
 * Time: 1:47 PM
 */

$input = './input.txt';
$output = './output.txt';

$resInput = fopen($input, 'r');
$resOutput = fopen($output, 'a+');
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