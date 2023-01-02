<?php

namespace App\Helpers;

    class NepaliCalendar
    {
        public $bs = [
            0=>[2000, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            1=>[2001, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            2=>[2002, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            3=>[2003, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            4=>[2004, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            5=>[2005, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            6=>[2006, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            7=>[2007, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            8=>[2008, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            9=>[2009, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            10=>[2010, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            11=>[2011, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            12=>[2012, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            13=>[2013, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            14=>[2014, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            15=>[2015, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            16=>[2016, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            17=>[2017, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            18=>[2018, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            19=>[2019, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            20=>[2020, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            21=>[2021, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            22=>[2022, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            23=>[2023, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            24=>[2024, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            25=>[2025, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            26=>[2026, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            27=>[2027, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            28=>[2028, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            29=>[2029, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            30=>[2030, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            31=>[2031, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            32=>[2032, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            33=>[2033, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            34=>[2034, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            35=>[2035, 30, 32, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            36=>[2036, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            37=>[2037, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            38=>[2038, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            39=>[2039, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            40=>[2040, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            41=>[2041, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            42=>[2042, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            43=>[2043, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            44=>[2044, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            45=>[2045, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            46=>[2046, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            47=>[2047, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            48=>[2048, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            49=>[2049, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            50=>[2050, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            51=>[2051, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            52=>[2052, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            53=>[2053, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            54=>[2054, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            55=>[2055, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            56=>[2056, 31, 31, 32, 31, 32, 30, 30, 29, 30, 29, 30, 30],
            57=>[2057, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            58=>[2058, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            59=>[2059, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            60=>[2060, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            61=>[2061, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            62=>[2062, 30, 32, 31, 32, 31, 31, 29, 30, 29, 30, 29, 31],
            63=>[2063, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            64=>[2064, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            65=>[2065, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            66=>[2066, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 29, 31],
            67=>[2067, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            68=>[2068, 31, 31, 32, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            69=>[2069, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            70=>[2070, 31, 31, 31, 32, 31, 31, 29, 30, 30, 29, 30, 30],
            71=>[2071, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            72=>[2072, 31, 32, 31, 32, 31, 30, 30, 29, 30, 29, 30, 30],
            73=>[2073, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 31],
            74=>[2074, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            75=>[2075, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            76=>[2076, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            77=>[2077, 31, 32, 31, 32, 31, 30, 30, 30, 29, 30, 29, 31],
            78=>[2078, 31, 31, 31, 32, 31, 31, 30, 29, 30, 29, 30, 30],
            79=>[2079, 31, 31, 32, 31, 31, 31, 30, 29, 30, 29, 30, 30],
            80=>[2080, 31, 32, 31, 32, 31, 30, 30, 30, 29, 29, 30, 30],
            81=>[2081, 31, 31, 32, 32, 31, 30, 30, 30, 29, 30, 30, 30],
            82=>[2082, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
            83=>[2083, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
            84=>[2084, 31, 31, 32, 31, 31, 30, 30, 30, 29, 30, 30, 30],
            85=>[2085, 31, 32, 31, 32, 30, 31, 30, 30, 29, 30, 30, 30],
            86=>[2086, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
            87=>[2087, 31, 31, 32, 31, 31, 31, 30, 30, 29, 30, 30, 30],
            88=>[2088, 30, 31, 32, 32, 30, 31, 30, 30, 29, 30, 30, 30],
            89=>[2089, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
            90=>[2090, 30, 32, 31, 32, 31, 30, 30, 30, 29, 30, 30, 30],
        ];

        private $nep_date = ['year'=>'', 'month'=>'', 'date'=>'', 'day'=>'', 'nmonth'=>'', 'num_day'=>''];
        private $eng_date = ['year'=>'', 'month'=>'', 'date'=>'', 'day'=>'', 'emonth'=>'', 'num_day'=>''];
        public $debug_info = '';

        /**
         * Calculates wheather english year is leap year or not.
         *
         * @param int $year
         * @return bool
         */
        public function is_leap_year($year)
        {
            $a = $year;
            if ($a % 100 == 0) {
                if ($a % 400 == 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($a % 4 == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        private function get_english_month($m)
        {
            $eMonth = false;
            switch ($m) {
                case 1:
                    $eMonth = 'January';
                    break;
                case 2:
                    $eMonth = 'February';
                    break;
                case 3:
                    $eMonth = 'March';
                    break;
                case 4:
                    $eMonth = 'April';
                    break;
                case 5:
                    $eMonth = 'May';
                    break;
                case 6:
                    $eMonth = 'June';
                    break;
                case 7:
                    $eMonth = 'July';
                    break;
                case 8:
                    $eMonth = 'August';
                    break;
                case 9:
                    $eMonth = 'September';
                    break;
                case 10:
                    $eMonth = 'October';
                    break;
                case 11:
                    $eMonth = 'November';
                    break;
                case 12:
                    $eMonth = 'December';
            }

            return $eMonth;
        }

        private function get_day_of_week($day)
        {
            switch ($day) {
                case 1:
                    $day = 'आइतबार';
                    break;

                case 2:
                    $day = 'सोमबार';
                    break;

                case 3:
                    $day = 'मंगलबार';
                    break;

                case 4:
                    $day = 'बुधबार';
                    break;

                case 5:
                    $day = 'बिहीबार';
                    break;

                case 6:
                    $day = 'शुक्रबार';
                    break;

                case 7:
                    $day = 'शनिबार';
                    break;
            }

            return $day;
        }

        public function get_nepali_gate($m)
        {
            $egate = false;
            switch ($m) {
                case 1:
                    $egate = '01';
                    break;
                case 2:
                    $egate = '02';
                    break;
                case 3:
                    $egate = '03';
                    break;
                case 4:
                    $egate = '04';
                    break;
                case 5:
                    $egate = '05';
                    break;
                case 6:
                    $egate = '06';
                    break;
                case 7:
                    $egate = '07';
                    break;
                case 8:
                    $egate = '08';
                    break;
                case 9:
                    $egate = '09';
                    break;
                case 10:
                    $egate = '10';
                    break;
                case 11:
                    $egate = '11';
                    break;
                case 12:
                    $egate = '12';
                    break;
                case 13:
                    $egate = '13';
                    break;
                case 14:
                    $egate = '14';
                    break;
                case 15:
                    $egate = '15';
                    break;
                case 16:
                    $egate = '16';
                    break;
                case 17:
                    $egate = '17';
                    break;
                case 18:
                    $egate = '18';
                    break;
                case 19:
                    $egate = '19';
                    break;
                case 20:
                    $egate = '20';
                    break;
                case 21:
                    $egate = '21';
                    break;
                case 22:
                    $egate = '22';
                    break;
                case 23:
                    $egate = '23';
                    break;
                case 24:
                    $egate = '24';
                    break;
                case 25:
                    $egate = '25';
                    break;
                case 26:
                    $egate = '26';
                    break;
                case 27:
                    $egate = '27';
                    break;
                case 28:
                    $egate = '28';
                    break;
                case 29:
                    $egate = '29';
                    break;
                case 30:
                    $egate = '30';
                    break;
                case 31:
                    $egate = '31';
                    break;
                case 32:
                    $egate = '32';
                    break;
            }

            return $egate;
        }

        private function get_nepali_month($m)
        {
            $n_month = false;

            switch ($m) {
                case 1:
                    $n_month = '01';
                    break;

                case 2:
                    $n_month = '02';
                    break;

                case 3:
                    $n_month = '03';
                    break;

                case 4:
                    $n_month = '04';
                    break;

                case 5:
                    $n_month = '05';
                    break;

                case 6:
                    $n_month = '06';
                    break;

                case 7:
                    $n_month = '07';
                    break;

                case 8:
                    $n_month = '08';
                    break;

                case 9:
                    $n_month = '09';
                    break;

                case 10:
                    $n_month = '10';
                    break;

                case 11:
                    $n_month = '11';
                    break;

                case 12:
                    $n_month = '12';
                    break;
            }

            return  $n_month;
        }

        private function get_nepali_saal($m)
        {
            $n_saal = false;

            switch ($m) {
                case 2072:
                    $n_saal = '2072';
                    break;
                case 2073:
                    $n_saal = '2073';
                    break;
                case 2074:
                    $n_saal = '2074';
                    break;
                case 2075:
                    $n_saal = '2075';
                    break;
                case 2076:
                    $n_saal = '2076';
                    break;
                case 2077:
                    $n_saal = '2077';
                    break;
                case 2078:
                    $n_saal = '2078';
                    break;
                case 2079:
                    $n_saal = '2079';
                    break;
                case 2080:
                    $n_saal = '2080';
                    break;
                case 2081:
                    $n_saal = '2081';
                    break;
                case 2082:
                    $n_saal = '2082';
                    break;
                default:
                    $n_saal = $m;
            }

            return $n_saal;
        }

        private function is_range_eng($yy, $mm, $dd)
        {
            if ($yy < 1944 || $yy > 2033) {
                $this->debug_info = 'Supported only between 1944-2022';

                return false;
            }

            if ($mm < 1 || $mm > 12) {
                $this->debug_info = 'Error! value 1-12 only';

                return false;
            }

            if ($dd < 1 || $dd > 31) {
                $this->debug_info = 'Error! value 1-31 only';

                return false;
            }

            return true;
        }

        private function is_range_nep($yy, $mm, $dd)
        {
            if ($yy < 2000 || $yy > 2089) {
                $this->debug_info = 'Supported only between 2000-2089';

                return false;
            }

            if ($mm < 1 || $mm > 12) {
                $this->debug_info = 'Error! value 1-12 only';

                return false;
            }

            if ($dd < 1 || $dd > 32) {
                $this->debug_info = 'Error! value 1-31 only';

                return false;
            }

            return true;
        }

        /**
         * currently can only calculate the date between AD 1944-2033...
         *
         * @param unknown_type $yy
         * @param unknown_type $mm
         * @param unknown_type $dd
         * @return unknown
         */
        public function eng_to_nep($yy, $mm, $dd)
        {
            if ($this->is_range_eng($yy, $mm, $dd) == false) {
                return false;
            } else {

                // english month data.
                $month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                $lmonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

                $def_eyy = 1944;                                    //spear head english date...
                $def_nyy = 2000;
                $def_nmm = 9;
                $def_ndd = 17 - 1;     //spear head nepali date...
                $total_eDays = 0;
                $total_nDays = 0;
                $a = 0;
                $day = 7 - 1;     //all the initializations...
                $m = 0;
                $y = 0;
                $i = 0;
                $j = 0;
                $numDay = 0;

                // count total no. of days in-terms of year
                for ($i = 0; $i < ($yy - $def_eyy); $i++) { //total days for month calculation...(english)
                    if ($this->is_leap_year($def_eyy + $i) == 1) {
                        for ($j = 0; $j < 12; $j++) {
                            $total_eDays += $lmonth[$j];
                        }
                    } else {
                        for ($j = 0; $j < 12; $j++) {
                            $total_eDays += $month[$j];
                        }
                    }
                }

                // count total no. of days in-terms of month
                for ($i = 0; $i < ($mm - 1); $i++) {
                    if ($this->is_leap_year($yy) == 1) {
                        $total_eDays += $lmonth[$i];
                    } else {
                        $total_eDays += $month[$i];
                    }
                }

                // count total no. of days in-terms of date
                $total_eDays += (float)$dd;

                $i = 0;
                $j = $def_nmm;
                $total_nDays = $def_ndd;
                $m = $def_nmm;
                $y = $def_nyy;

                // count nepali date from array
                while ($total_eDays != 0) {
                    $a = $this->bs[$i][$j];
                    $total_nDays++;                     //count the days
                    $day++;                             //count the days interms of 7 days
                    if ($total_nDays > $a) {
                        $m++;
                        $total_nDays = 1;
                        $j++;
                    }
                    if ($day > 7) {
                        $day = 1;
                    }
                    if ($m > 12) {
                        $y++;
                        $m = 1;
                    }
                    if ($j > 12) {
                        $j = 1;
                        $i++;
                    }
                    $total_eDays--;
                }

                $numDay = $day;

                $this->nep_date['year'] = $this->get_nepali_saal($y);
                $this->nep_date['month'] = $m;
                $this->nep_date['date'] = $this->get_nepali_gate($total_nDays);

                $this->nep_date['day'] = $this->get_day_of_week($day);
                $this->nep_date['nmonth'] = $this->get_nepali_month($m);
                $this->nep_date['num_day'] = $numDay;

                return $this->nep_date;
            }
        }

        /**
         * currently can only calculate the date between BS 2000-2089.
         *
         * @param unknown_type $yy
         * @param unknown_type $mm
         * @param unknown_type $dd
         * @return unknown
         */
        public function nep_to_eng($yy, $mm, $dd)
        {
            $def_eyy = 1943;
            $def_emm = 4;
            $def_edd = 14 - 1;       // init english date.
            $def_nyy = 2000;
            $def_nmm = 1;
            $def_ndd = 1;        // equivalent nepali date.
            $total_eDays = 0;
            $total_nDays = 0;
            $a = 0;
            $day = 4 - 1;     // initializations...
            $m = 0;
            $y = 0;
            $i = 0;
            $k = 0;
            $numDay = 0;

            $month = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            $lmonth = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

            if ($this->is_range_nep($yy, $mm, $dd) === false) {
                return false;
            } else {

                // count total days in-terms of year
                for ($i = 0; $i < ($yy - $def_nyy); $i++) {
                    for ($j = 1; $j <= 12; $j++) {
                        $total_nDays += $this->bs[$k][$j];
                    }
                    $k++;
                }

                // count total days in-terms of month
                for ($j = 1; $j < $mm; $j++) {
                    $total_nDays += $this->bs[$k][$j];
                }

                // count total days in-terms of dat
                $total_nDays += $dd;

                //calculation of equivalent english date...
                $total_eDays = $def_edd;
                $m = $def_emm;
                $y = $def_eyy;
                while ($total_nDays != 0) {
                    if ($this->is_leap_year($y)) {
                        $a = $lmonth[$m];
                    } else {
                        $a = $month[$m];
                    }
                    $total_eDays++;
                    $day++;
                    if ($total_eDays > $a) {
                        $m++;
                        $total_eDays = 1;
                        if ($m > 12) {
                            $y++;
                            $m = 1;
                        }
                    }
                    if ($day > 7) {
                        $day = 1;
                    }
                    $total_nDays--;
                }
                $numDay = $day;

                $this->eng_date['year'] = $y;
                $this->eng_date['month'] = $m;
                $this->eng_date['date'] = $total_eDays;
                $this->eng_date['day'] = $this->get_day_of_week($day);
                $this->eng_date['emonth'] = $this->get_english_month($m);
                $this->eng_date['num_day'] = $numDay;

                return $this->eng_date;
            }
        }

        public function nep_to_eng_digit_conversion($date)
        {
            $nepaliNumbers = ['०'=>0, '१'=>1, '२'=>2, '३'=>3, '४'=>4, '५'=>5, '६'=>6, '७'=>7, '८'=>8, '९'=>9];
            $date = explode('-', $date);
            $engdigit = [];
            foreach ($date as $loop=>$d) {
                $digits = preg_split('//u', (''.$d), -1);
                $d1 = '';
                foreach ($digits as $di) {
                    if ($di) {
                        $digit_eng = $nepaliNumbers[$di];
                        $d1 .= $digit_eng;
                    }
                }
                if (strlen($d1) == 1) {
                    $d1 = '0'.$d1;
                }
                array_push($engdigit, $d1);
            }

            return $engdigit;
        }

        public function change_eng_nepdateFormatted($date)
        {
            $cal = new self();
            $exp = explode('-', $date);
            $converted = $cal->eng_to_nep($exp[0], $exp[1], $exp[2]);
        }

        public function full_nepali_from_eng_date($date)
        {
            $exp = explode('-', $date);

            $converted = $this->eng_to_nep($exp[0], $exp[1], $exp[2]);
        
            if(!$converted){

                return '---';
            }
            $date = $converted['year'].'-'.$this->get_nepali_gate($converted['month']).'-'.$converted['date'];

            

            return $date;
        }

        public function getnepaliMonth($n)
        {
            $n = (int) $n;
            $monthsName = ['Baisakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik', 'Mangsir', 'Paush', 'Mangh', 'Falgun', 'Chaitra'];

            return $monthsName[$n];
        }

        public function formated_nepali_from_eng_date($date)
        {
            $exp = explode('-', $date);

            if(count($exp) < 3 ){

                return '---';
            }

            $converted = $this->eng_to_nep($exp[0], $exp[1], $exp[2]);

            $nmonth = self::getnepaliMonth($converted['nmonth'] - 1); //array index starts from 0

            $date = $converted['date'].'-'.$nmonth. ' '. $converted['year'];

            return $date;
        }
    }
