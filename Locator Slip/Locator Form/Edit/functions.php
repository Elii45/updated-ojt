<?php

function formatDateForInput($dateString) {
    if (empty($dateString)) return '';

    try {
        $date = new DateTime($dateString);
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        $parts = explode(' ', $dateString);
        if (count($parts) >= 3) {
            $month = date_parse($parts[0])['month'];
            $day = rtrim($parts[1], ',');
            $year = $parts[2];
            return sprintf("%04d-%02d-%02d", $year, $month, $day);
        }
    }
    return '';
}

function formatTimeForInput($timeString) {
    if (empty($timeString)) return '';
    $time = DateTime::createFromFormat('g:i A', $timeString);
    return $time ? $time->format('H:i') : '';
}

function normalizeEmployeeName($name) {
    $name = trim($name);
    if (preg_match('/^([^,]+),\s*(.+)$/', $name, $matches)) {
        $lastName = trim($matches[1]);
        $rest = trim($matches[2]);
        return "$lastName, $rest";
    }
    return $name;
}

function isPartialMatch($employeeName, $requesterName) {
    return stripos($employeeName, $requesterName) !== false || stripos($requesterName, $employeeName) !== false;
}

function deduplicateEmployees(array $employees) {
    $unique = [];
    $names = [];
    foreach ($employees as $employee) {
        $lowerName = strtolower($employee['name']);
        if (!in_array($lowerName, $names)) {
            $unique[] = $employee;
            $names[] = $lowerName;
        }
    }
    return $unique;
}
