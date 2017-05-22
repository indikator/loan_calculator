<?php

require_once 'loan.php';

$loan = new Loan($_REQUEST);
echo $loan->getResult();