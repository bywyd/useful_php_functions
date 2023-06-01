<?php

function isWeekend($date) {
    if ((date('N', strtotime($date)) >= 6) == 1) {
        return true;
    } else {
        return false;
    }
}