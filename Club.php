<?php

class Club
{
    const TIME = 8; // Время работы клуба

    const ICE_PEAK = 50; // Максимальный поток людей

    public $genre;

    public $userInfo;

    public $music;

    /**
     * Club constructor.
     */
    public function __construct()
    {
        $this->genre = $this->getGenre();

        $this->userInfo = $this->timeTableDay(self::ICE_PEAK);

        $this->music = $this->getMusic();
    }

    /**
     * @return array
     */
    private function getGenre()
    {
        return [
            'Поп-музыка' => ['path', 'forest', 'sea'],
            'Рок'        => ['musician', 'Orbit', 'Jose'],
            'Хип-хоп'    => ['sidewalk', 'path', 'lake'],
            'РЭП'        => ['student', 'river', 'bird'],
            'R&B'        => ['pig', 'rabbit', 'pharmacy'],
            'Джаз'       => ['goose', 'giraffe', 'rat'],
            'Электро'    => ['stop', 'Russia', 'fox'],
        ];
    }

    /**
     * @param $ice_peak
     *
     * @return array
     */
    private function timeTableDay($ice_peak)
    {
        $userInfo = $this->userRandDays($ice_peak);
        $res = [];

        foreach ($userInfo as $k => $users) {
            $tmp_arr = $this->userRandHoursByMin($users);
            $res[$k] = $tmp_arr;
        }

        return $res;
    }

    /**
     * @param $ice_peak
     *
     * @return array
     */
    private function userRandDays($ice_peak)
    {
        $arr = [];
        for ($x = 1; $x <= self::TIME; $x++) {
            $arr[] = $this->normalDist(1, $ice_peak, 30);
        }

        asort($arr);
        $array1 = [];
        $array2 = [];

        foreach ($arr as $k => $item) {
            $element = array_shift($arr);

            if ($k % 2 === 0) {
                array_unshift($array1, $element);
            } else {
                $array2[] = $element;
            }
        }

        return array_merge($array2, $array1);
    }

    /**
     * @param $total
     *
     * @return array
     */
    private function userRandHoursByMin($total)
    {
        $arr = [];
        for ($i = 0; $i < $total; $i++) {
            $arr[] = [
                'time'   => mt_rand(0, 59),
                'genre'  => $this->getLoveGenre(),
                'status' => 0,
                'place'  => false,
                'out'    => false,
            ];
        }
        usort($arr, ['Club', 'sortElement']);

        return $arr;
    }

    /**
     * @param $min
     * @param $max
     * @param $std_deviation
     *
     * @return float
     */
    private function normalDist($min, $max, $std_deviation)
    {
        $rand1 = (float) mt_rand() / mt_getrandmax();
        $rand2 = (float) mt_rand() / mt_getrandmax();

        $gaussian_number = sqrt(-2 * log($rand1)) * cos(2 * M_PI * $rand2);
        $mean = ($max + $min) / 2;
        $random_number = round(($gaussian_number * $std_deviation) + $mean);
        if ($random_number < $min || $random_number > $max) {
            $random_number = $this->normalDist($min, $max, $std_deviation);
        }

        return $random_number;
    }

    /**
     * @return array
     */
    private function getLoveGenre()
    {
        $len = mt_rand(1, count($this->genre) - 1);
        $arr = [];
        for ($i = 0; $i < $len; $i++) {
            $arr[] = array_rand($this->genre);
        }

        return array_unique($arr);
    }

    /**
     * @return array
     */
    private function getMusic()
    {
        return [
            'genre'  => 'none',
            'music'  => 'none',
            'time'   => 0,
            'active' => 0,
            'line'   => 0,
        ];
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    private function sortElement($a, $b)
    {
        if ($a['time'] === $b['time']) {
            return 0;
        }

        return ($a['time'] < $b['time']) ? -1 : 1;
    }
}