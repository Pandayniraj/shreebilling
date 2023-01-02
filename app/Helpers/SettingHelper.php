<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class SettingHelper
{
    private static function getjsonfile()
    {
        $jsonString = file_get_contents(storage_path('app/settings.json'));
        $data = json_decode($jsonString, true);

        return $data;
    }

    public static function set($key, $value)
    {
        $array = self::getjsonfile();
        Arr::set($array, $key, $value);
        $newJsonString = json_encode($array, JSON_PRETTY_PRINT);
        file_put_contents(storage_path('app/settings.json'), stripslashes($newJsonString));

        return 0;
    }

    public static function get($key)
    {
        $array = self::getjsonfile();
        $value = Arr::get($array, $key, null);

        return $value;
    }
}
