<?php
declare(strict_types=1);

namespace App\Entity;

class Grinder
{
    protected $mac;

    protected $manufacturer;

    protected $model;

    /**
     */
    public function __construct(string $mac, string $manufacturer, string $model)
    {
        $this->mac = $mac;
        $this->manufacturer = $manufacturer;
        $this->model = $model;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    public function getModel(): string
    {
        return $this->model;
    }


}
