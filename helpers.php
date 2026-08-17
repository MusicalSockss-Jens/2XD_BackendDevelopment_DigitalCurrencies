<?php

function emailToName($email) {
    $localPart = explode('@', $email)[0];
    $words = explode('.', $localPart);
    $words = array_map('ucfirst', $words);
    return implode(' ', $words);
}