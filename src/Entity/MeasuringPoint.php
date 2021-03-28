<?php
declare(strict_types=1);

namespace App\Entity;


class MeasuringPoint
{
    protected $microTime;

    protected $rawData;

    /**
     */
    public function __construct(int $microTime, int $rawData)
    {
        $this->microTime = $microTime;
        $this->rawData = $rawData;
    }

    public function getMicroTime(): int
    {
        return $this->microTime;
    }

    public function getRawData(): int
    {
        return $this->rawData;
    }


}
