<?php
class AgeTypeCalculator
{
    private $today;

    public function __construct()
    {
        $this->today = strtotime(date('Y-m-d'));
    }

    public function passanger_type($highest_age, $infant_age_limit, $born_date)
    {
        $born_year = strtotime($born_date);
        $calculated_age = ($this->today-$born_year)/31556926;

        if ($calculated_age > $highest_age) {
            $passanger_type = "Adult";
        }
        elseif ($calculated_age > $infant_age_limit && $highest_age > $calculated_age) {
            $passanger_type = "Child";
        } 
        elseif ($calculated_age < $infant_age_limit ) {
            $passanger_type = "Infant";
        }

        return $passanger_type;
    }

    public function calc_age($born_date) 
    {
        $born_year = strtotime($born_date);
        $calculated_age = ($this->today-$born_year)/31556926;
        return $calculated_age;
    }
}