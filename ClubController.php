<?php
require 'Club.php';
require 'ClubWork.php';

class ClubController
{
    private $clubWork;

    /**
     * ClubController constructor.
     */
    public function __construct()
    {
        $club = new Club();
        $this->clubWork = new ClubWork($club->genre, $club->userInfo, $club->music);
    }

    /**
     * @return bool
     */
    public function startWork()
    {
        $this->clubWork->musicControlTest();

        return false;
    }
}

$club = new ClubController();
$club->startWork();