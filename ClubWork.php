<?php

class ClubWork
{
    const MAX_TIME_MUSIC = 4; // Максимальное время вопросизведения музыки ( мин )

    private $genre;

    private $userInfo;

    private $music;

    private $orderUser;

    private $hour;

    private $minute;

    /**
     * ClubWork constructor.
     *
     * @param $genre
     * @param $userInfo
     * @param $music
     */
    public function __construct($genre, $userInfo, $music)
    {
        $this->genre = $genre;
        $this->userInfo = $userInfo;
        $this->music = $music;
    }

    /**
     * Основная функция работы клуба
     */
    public function musicControlTest()
    {
        foreach ($this->userInfo as $key => $val) {
            for ($i = 0; $i <= 59; $i++) {
                foreach ($val as $keys => $value) {
                    if ($value['time'] === $i) {
                        $genre_num = array_rand($value['genre']);
                        $this->orderUser[] = $value['genre'][$genre_num];
                        $this->music['line']++;
                        $this->userInfo[$key][$keys]['status'] = 1;
                        $this->userInfo[$key][$keys]['out'] = mt_rand(10, 15);
                        $this->musicLoader();
                    }
                    if ($this->userInfo[$key][$keys]['status'] === 1) {
                        if (in_array($this->music['genre'], $value['genre'], true)) {
                            $this->userInfo[$key][$keys]['place'] = 'dance';
                        } else {
                            $this->userInfo[$key][$keys]['place'] = 'bar';
                        }
                    }
                }
                $this->hour = $key;
                $this->minute = $i;
                $this->generateTimeMusic();
            }
        }
    }

    /**
     * Загрузка музыки
     */
    private function musicLoader()
    {
        $this->music['active'] = 1;
        if ($this->music['time'] === 0) {
            $this->loadMusicTruck();
        }
    }

    /**
     * Загркзка трека
     */
    private function loadMusicTruck()
    {
        $this->outUser();
        $music_temp = array_rand($this->genre[$this->orderUser[0]]);
        $this->music['music'] = $this->genre[$this->orderUser[0]][$music_temp];
        $this->music['genre'] = $this->orderUser[0];
        $this->music['time'] = mt_rand(2, self::MAX_TIME_MUSIC);
    }

    /**
     * Генерация времени и очереди
     */
    private function generateTimeMusic()
    {
        if ($this->music['time'] > 0) {
            $this->music['time']--;
        }
        if ($this->music['line'] > 0 && $this->music['time'] === 0) {
            $this->music['line']--;
        }
        if ($this->music['time'] === 0 && $this->music['active'] === 1 && count($this->orderUser) > 1) {
            array_shift($this->orderUser);
            $this->musicLoader();
        }
        if ($this->music['line'] === 0 && $this->music['time'] === 0 && $this->music['genre'] !== 'none') {
            $this->loadMusicTruck();
        }
    }

    /**
     * Смена трека
     */
    private function outUser()
    {
        $userBar = 0;
        $userDance = 0;
        foreach ($this->userInfo as $key => $val) {
            foreach ($val as $keys => $value) {
                if ($value['status'] === 1) {
                    if ($value['out'] > 0) {
                        $this->userInfo[$key][$keys]['out']--;
                    } else {
                        $this->userInfo[$key][$keys]['status'] = 0;
                        $this->userInfo[$key][$keys]['place'] = false;
                    }
                }
            }
            $userBar += $this->arrayFilter($this->userInfo[$key], 'place', 'bar');
            $userDance += $this->arrayFilter($val, 'place', 'dance');
        }
        echo '<p style="font-size: 16px"><b>'.'Час: '.$this->hour.', Минута: '.$this->minute.'</b></p>';
        echo '<p>Активно танцуют: '.$userDance.', Активно выпивают: '.$userBar.'</p>';
        echo '<p>Играет музыка: '.$this->music['music'].', Жанр музыки: '.$this->music['genre'].'</p>';
    }

    /**
     * @param $val
     * @param $key
     * @param $check
     *
     * @return int
     */
    private function arrayFilter($val, $key, $check)
    {
        return count(array_filter($val, static function ($item) use ($key, $check) {
            return $item[$key] === $check;
        }));
    }
}