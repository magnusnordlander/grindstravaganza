<?php
declare(strict_types=1);

namespace App;


use App\Entity\MeasuringPoint;

class RelativeMeasuringPoint
{
    /**
     * @var MeasuringPoint
     */
    private $mp;
    /**
     * @var int
     */
    private $tareValue;
    /**
     * @var float
     */
    private $scaleCalibration;
    /**
     * @var MeasuringPoint
     */
    private $first;

    public function __construct(MeasuringPoint $mp, int $tareValue, float $scaleCalibration, MeasuringPoint $first)
    {
        $this->mp = $mp;
        $this->tareValue = $tareValue;
        $this->scaleCalibration = $scaleCalibration;
        $this->first = $first;
    }

    public function getRelativeSeconds(): float
    {
        return ($this->mp->getMicroTime() - $this->first->getMicroTime()) / 1000000;
    }

    public function getWeight(): float
    {
        return ($this->mp->getRawData() - $this->tareValue) / $this->scaleCalibration;
    }
}
