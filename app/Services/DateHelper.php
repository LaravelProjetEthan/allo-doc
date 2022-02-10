<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class DateHelper
{
    /**
     * @param $currentDate
     * @return null
     */
    public static function firstDayOfPreviousWeek($currentDate)
    {
        $date_previous_week = null;
        $dateToCheck = new Carbon($currentDate);
        if ($dateToCheck->startOfWeek() != Carbon::now()->startOfWeek()) {
            $date_previous_week = new Carbon($currentDate);
            $date_previous_week = $date_previous_week->startOfWeek()->subDay();
        }
        return $date_previous_week;
    }

    /**
     * @param $currentDate
     * @return Carbon
     */
    public static function getCarbonDate($currentDate): Carbon
    {
        return new Carbon($currentDate);
    }

    /**
     * @param $currentDate
     * @return Carbon
     */
    public static function firstDayOfNextWeek($currentDate): Carbon
    {
        $date_next_week = new Carbon($currentDate);
        return $date_next_week->endOfWeek()->addDay();
    }

    /**
     * @param string $dateSrc
     * @return string
     */
    public static function getFrenchDayFromString(string $dateSrc): string
    {
        $dateCarbon = new Carbon($dateSrc);
        return self::getFrenchDay($dateCarbon);
    }

    /**
     * @param Carbon $dateSrc
     * @return string
     */
    public static function getFrenchDay(Carbon $dateSrc): string
    {
        $txt = self::getFrenchDayName($dateSrc->format("w")) . " ";
        $txt .= $dateSrc->format("j") . " ";
        $txt .= self::getFrenchMonthName($dateSrc->format("n")) . " ";
        $txt .= $dateSrc->format("Y");

        return $txt;
    }

    /**
     * @param int $numDay
     * @return string
     */
    public static function getFrenchDayName(int $numDay): string
    {
        switch ($numDay) {
            case 0:
                return "Dimanche";
                break;
            case 1:
                return "Lundi";
                break;
            case 2:
                return "Mardi";
                break;
            case 3:
                return "Mercredi";
                break;
            case 4:
                return "Jeudi";
                break;
            case 5:
                return "Vendredi";
                break;
            case 6:
                return "Samedi";
                break;
            default:
                return "";
                break;
        }
    }

    /**
     * @param int $numMonth
     * @return string
     */
    public static function getFrenchMonthName(int $numMonth): string
    {
        switch ($numMonth) {
            case 1:
                return "janvier";
                break;
            case 2:
                return "février";
                break;
            case 3:
                return "mars";
                break;
            case 4:
                return "avril";
                break;
            case 5:
                return "mai";
                break;
            case 6:
                return "juin";
                break;
            case 7:
                return "juillet";
                break;
            case 8:
                return "août";
                break;
            case 9:
                return "septembre";
                break;
            case 10:
                return "octobre";
                break;
            case 11:
                return "novembre";
                break;
            case 12:
                return "décembre";
                break;
            default:
                return "";
                break;
        }
    }
}
