<?php

namespace App\Helpers;

class Helper{
    public static function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
    public static function distance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return floatval(number_format(($angle * $earthRadius)/1000, 1));
    }

    public static function csvToArray($path, $delimiter){
        if (!file_exists($path) || !is_readable($path))
            return false;
        $header = null;
        $data = array();
        if (($handle = fopen($path, 'r')) !== false)
        {
            $row = fgetcsv($handle, 1000, $delimiter);
            $header = $row;
            $columns = count($row);
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else{
                    if(count($row) == $columns){
                        $data[] = array_combine($header, $row);
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
