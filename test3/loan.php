<?php

require_once 'db.php';

class Loan
{
    private $id;
    private $loanAmount;
    private $loanPeriod;
    private $interestRate;
    private $firstDate;

    private $monthlyPay;

    private $db;

    public function __construct($input)
    {
        $this->loanAmount = $input['loan_amount'];
        $this->loanPeriod = $input['loan_period'];
        $this->interestRate = $input['interest_rate'];
        $this->firstDate = $input['first_payment'];
        $config = require_once('config.php');
        $this->db = new Db($config);
        $this->saveRequest();
        $this->calculate();
    }

    private function calculate()
    {
        $P = $this->interestRate / (12 * 100);
        $monthlyPay = $this->loanAmount * ($P + ($P / (pow((1 + $P), $this->loanPeriod) - 1)));
        $this->monthlyPay = $monthlyPay;
    }

    private function saveRequest()
    {
        $sql = "
            INSERT INTO calculate_request(date, loan_amount, loan_period, interest_rate, first_payment)
            VALUES(NOW(), {$this->loanAmount}, {$this->loanPeriod}, {$this->interestRate}, '{$this->firstDate}');
        ";
        $connection = $this->db->getConnection();

        $connection->exec($sql);
        $this->id = $connection->lastInsertId();
    }

    private function saveCalendar($payment_number, $date, $principal_debt, $interest, $total_amount, $remaining_debt)
    {
        $sql = "
            INSERT INTO payment_calendar(request_id, payment_number, payment_date, principal_debt, interest, total_amount, remaining_debt)
            VALUES({$this->id}, $payment_number, '$date', $principal_debt, $interest, $total_amount, $remaining_debt)
        ";
        $this->db->getConnection()->exec($sql);
    }

    public function getResult()
    {
        $result = [];
        $loanAmount = $this->loanAmount;
        $firstDate = $this->firstDate;
        $monthRate = $this->interestRate / (12 * 100);

        for ($i = 0; $i < $this->loanPeriod; $i++) {
            $percents = $loanAmount * $monthRate;
            $principalDebt = $this->monthlyPay - $percents;
            $loanAmount -= $principalDebt;
            $result[] = [
                'date' => $firstDate,
                'principal_debt' => round($principalDebt, 2),
                'interest' => round($percents, 2),
                'total_amount' => round($this->monthlyPay, 2),
                'remaining_debt' => round($loanAmount, 2)
            ];
            $this->saveCalendar($i + 1, $firstDate, $principalDebt, $percents, $this->monthlyPay, $loanAmount);
            $firstDate = date('Y-m-d', strtotime("+1 month", strtotime($firstDate)));
        }

        $result = json_encode($result);
        return $result;
    }
}
