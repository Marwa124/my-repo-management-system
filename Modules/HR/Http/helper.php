<?php

use Carbon\Carbon;
use App\Models\Config;
use Carbon\CarbonPeriod;
use Modules\HR\Entities\Absence;
use Modules\HR\Entities\Holiday;
use Modules\HR\Entities\Vacation;
use Modules\HR\Entities\WorkingDay;
use Modules\HR\Entities\LeaveApplication;
use Modules\HR\Entities\FingerprintAttendance;

if (!function_exists('getAbsentUsers')) {
    function getAbsentUsers($date, $user_id)
    {
        $data_value = FingerprintAttendance::where('date', $date)->where('user_id', $user_id)->first();
        $userAbsent = Absence::where('user_id', $user_id)->where('date', $date)->first();

        if (!$data_value && $date < date('Y-m-d') && !weekEnds($date) && !getVacations($date, $user_id) && !getHolidays($date)) {
            if (!$userAbsent) {
                // dd('ppppmmm');
                $result = new Absence();
                $result->date = $date;
                $result->user_id = $user_id;
                $result->timestamps = false;
                $result->save();
            } // Recorded user in db
            return 1;
        } // not having fingerprint
        else {
            return 0;
        }
    }
}

if (!function_exists('getUserLeaves')) {
    function getUserLeaves($date, $user_id)
    {
        $leavesApp = LeaveApplication::where('user_id', $user_id)->where('leave_start_date', '<=', $date)->where('leave_end_date', '>=', $date)->first();
        return ($leavesApp) ? $leavesApp->leave_category()->first()->name : 0;
    }
}

if (!function_exists('getHolidays')) {
    function getHolidays($date)
    {
        $result = Holiday::where('start_date', '<=', $date)->where('end_date', '>=', $date)->first();
        return ($result) ? 1 : 0;
    }
}

if (!function_exists('getVacations')) {
    function getVacations($date, $user_id)
    {
        $result = Vacation::where('user_id', $user_id)->where('start_date', '<=', $date)->where('end_date', '>=', $date)->first();
        return ($result && !getHolidays($date)) ? 1 : 0;
    }
}

if (!function_exists('weekEnds')) {
    function weekEnds($day)
    {
        $dayFormat = date('D', strtotime($day));
        return WorkingDay::where('day', $dayFormat)->where('working_status', 0)->first();
    }
}

if (!function_exists('getDateRange')) {
    function getDateRange($date)
    {
        $currentMonth = date("Y-m-d", strtotime($date . '-24'));
        $carbonDate =  Carbon::createFromFormat('Y-m-d', $currentMonth)->subMonth()->format('Y-m');
        $previousMonth = date("Y-m-d", strtotime($carbonDate . '-25'));
        $period = CarbonPeriod::create($previousMonth, $currentMonth);
        // Iterate over the period
        $val = [];
        foreach ($period as $date) {
            $val[] = $date->format('Y-m-d');
        }
        return $val;
    }
}

if (!function_exists('getArabicDayName')) {
    function getArabicDayName($day)
    {
        $dayName = date('l', strtotime($day));
        switch ($dayName) {
            case 'Saturday':
                return 'السبت';
                break;
            case 'Monday':
                return 'الأثنين';
                break;
            case 'Tuesday':
                return 'الثلاثاء';
                break;
            case 'Wednesday':
                return 'الاربعاء';
                break;
            case 'Thursday':
                return 'الخميس';
                break;
            case 'Friday':
                return 'الجمعة';
                break;
            case 'Sunday':
                return 'الأحد';
                break;
        }
    }
}




if (!function_exists('settings')) {
    function settings($key, $alt = null)
    {
        $config = Config::where('key', $key)->first();
        return $config ? $config->value : $alt;
    }
}


if (!function_exists('flash')) {
    function flash($message = 'No Message Set', $type = 'info')
    {

        return [
            'message' => $message,
            'alert-type' => $type
        ];
    }
}



if (!function_exists('timezones')) {
    function timezones()
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime = new DateTime('now', new DateTimeZone('UTC'));


        $tempTimezones = array();
        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $currentTimezone = new DateTimeZone($timezoneIdentifier);

            $tempTimezones[] = array(
                'offset' => (int)$currentTimezone->getOffset($utcTime),
                'identifier' => $timezoneIdentifier
            );
        }

        // Sort the array by offset,identifier ascending
        usort($tempTimezones, function ($a, $b) {
            return ($a['offset'] == $b['offset']) ? strcmp($a['identifier'], $b['identifier']) : $a['offset'] - $b['offset'];
        });

        $timezoneList = array();
        foreach ($tempTimezones as $tz) {
            $sign = ($tz['offset'] > 0) ? '+' : '-';
            $offset = gmdate('H:i', abs($tz['offset']));
            $timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' .
                $tz['identifier'];
        }

        return $timezoneList;
    }
}
