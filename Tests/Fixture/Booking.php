<?php
namespace Portrino\PxICal\Tests\Fixture;

use Eluceo\iCal\Component\Event;
use Portrino\PxICal\Domain\Model\Interfaces\ICalEventInterface;

/**
 * Class Booking
 * @package Portrino\PxICal\Tests\Fixture
 */
class Booking implements ICalEventInterface
{
    /**
     * @var string
     */
    protected $uniqueId;

    /**
     * @var \DateTime
     */
    protected $dtStart;

    /**
     * Preferentially chosen over the duration if both are set.
     *
     * @var \DateTime
     */
    protected $dtEnd;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var \DateTime
     */
    protected $dtStamp;

    /**
     * @param string $uniqueId
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
        return $this;
    }

    /**
     * @param \DateTime $dtStart
     */
    public function setDtStart($dtStart)
    {
        $this->dtStart = $dtStart;
        return $this;
    }

    /**
     * @param \DateTime $dtEnd
     */
    public function setDtEnd($dtEnd)
    {
        $this->dtEnd = $dtEnd;
        return $this;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param $dtStamp
     */
    public function setDtStamp($dtStamp)
    {
        $this->dtStamp = $dtStamp;
        return $this;
    }

    /**
     * @return Event
     */
    public function __toICalEvent()
    {
        $result = new Event();
        $result
            ->setUniqueId($this->uniqueId)
            ->setDtStart($this->dtStart)
            ->setDtEnd($this->dtEnd)
            ->setNoTime(false)
            ->setDtStamp($this->dtStamp)
            ->setSummary($this->summary);
        return $result;
    }
}
