<?php

function name($name )
{   
    if (strlen(strval($name)) > 255) {
        return array(
            'error' => true,
            'message' => 'Name must be less than 255 characters'
        );
    }
    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function startDate($startDate)
{
    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $startDate);
    $errors = DateTime::getLastErrors();

    if (intval($errors['warning_count']) + intval($errors['error_count']) > 0 || $dateTime === false)  {
        return array(
            'error' => true,
            'message' => 'Start date must be a valid ISO8601 date. Example: 2022-12-31T14:59:00Z'
        );
    }

    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function endDate($endDate, $startDate)
{
    $endDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $endDate);
    $startDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $startDate);

    if ($endDateTime === false) {
        return array(
            'error' => true,
            'message' => 'End date must be a valid ISO8601 date. Example: 2022-12-31T14:59:00Z'
        );
    }

    if ($endDateTime <= $startDateTime) {
        return array(
            'error' => true,
            'message' => 'End date must be greater than start date'
        );
    }

    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function color($color)
{
    if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
        return array(
            'error' => true,
            'message' => 'Color must be a valid hex color code. Example: #FF0000'
        );
    }

    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function externalId($externalId)
{
    if (strlen($externalId) > 255) {
        return array(
            'error' => true,
            'message' => 'External ID must be less than 255 characters'
        );
    }

    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function status($status)
{
    $validStatuses = ['NEW', 'PLANNED', 'DELETED'];
    if (!in_array($status, $validStatuses)) {
        return array(
            'error' => true,
            'message' => 'Status must be one of the following: ' . implode(', ', $validStatuses)
        );
    } elseif ($status === 'DELETED') {
        return array(
            'error' => true,
            'message' => 'Status cannot be DELETED'
        );
    }
    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}
function durationUnit($durationUnit)
{
    $validDurationUnits = ['HOURS', 'DAYS', 'WEEKS'];

    if (!empty($durationUnit) && !in_array($durationUnit, $validDurationUnits)) {
        return array(
            'error' => true,
            'message' => 'Duration unit must be one of the following: ' . implode(', ', $validDurationUnits)
        );
    }

    return array(
        'error' => false,
        'message' => 'Status is valid'
    );
}

function calculateDuration($startDate, $endDate, $durationUnit)
{
    if (is_null($endDate)) {
        return null;
    }

    $startDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $startDate);
    $endDateTime = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $endDate);

    $durationInSeconds = $endDateTime->getTimestamp() - $startDateTime->getTimestamp();

    switch ($durationUnit) {
        case 'HOURS':
            $duration = round($durationInSeconds / 3600, 2);
            break;
        case 'WEEKS':
            $duration = round($durationInSeconds / (7 * 24 * 3600), 2);
            break;
        default: // DAYS
            $duration = round($durationInSeconds / (24 * 3600), 2);
            break;
    }

    return $duration;
}

function getValidate($key, ...$vals)
{
    $result= array();
    
    switch ($key) {
        case 'name':
            array_push($result, name($vals[0]));
            break;
        case 'startDate':
            array_push($result, startDate($vals[0]));
            break;
        case 'endDate':
            array_push($result, endDate($vals[0], $vals[1]));
            break;
        case 'color':
            array_push($result, color($vals[0]));
            break;
        case 'externalId':
            array_push($result, externalId($vals[0]));
            break;
        case 'status':
            array_push($result, status($vals[0]));
            break;
        case 'durationUnit':
            array_push($result, durationUnit($vals[0]));
            break;
    }



    return $result;
}
