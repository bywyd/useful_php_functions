<?php

function AllDatesBetweenTwoDates($StartDate, $EndDate)
{
    $dateFrom = new \DateTime($StartDate);
    $dateTo = new \DateTime($EndDate);
    $dates = [];

    if ($dateFrom > $dateTo) {
        return $dates;
    }

    while ($dateFrom <= $dateTo) {
        $dates[] = $dateFrom->format('Y-m-d');
        $dateFrom->modify('+1 day');
    }

    return $dates;
}