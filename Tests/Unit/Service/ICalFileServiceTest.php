<?php
namespace Portrino\PxICal\Tests\Unit\Service;

use Eluceo\iCal\Component\Event;
use Portrino\PxICal\Service\ICalFileService;
use PHPUnit\Framework\TestCase;
use Portrino\PxICal\Tests\Fixture\Booking;
use PHPUnit_Framework_MockObject_MockObject;
use Eluceo\iCal\Component\Calendar;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ICalFileServiceTest
 *
 * @package Portrino\PxICal\Tests\Service
 */
class ICalFileServiceTest extends TestCase
{
    /**
     * @var ICalFileService|PHPUnit_Framework_MockObject_MockObject
     */
    protected $iCalFileService;

    /**
     * @var string
     */
    protected static $fileName = 'testFileName';

    /**
     * @var string
     */
    protected static $typo3host = 'www.example.com';

    /**
     * @var string
     */
    protected static $tempFolder = '';

    /**
     * @test
     */
    public function createEventFileExists()
    {
        $vEvent = new Event();
        $vEvent
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setNoTime(true)
            ->setSummary('Christmas');

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $isCreated = $this->iCalFileService->create($vEvent);

        static::assertFileExists($isCreated);
    }

    /**
     * @test
     */
    public function createEvent()
    {
        $vEvent = new Event();
        $vEvent
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setNoTime(true)
            ->setSummary('Christmas');

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                    'getTempFolder',
                    'getFileName',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTempFolder')
            ->willReturn(self::$tempFolder);

        $this->iCalFileService
            ->expects(static::any())
            ->method('getFileName')
            ->willReturn(self::$fileName);

        $iCalFile = self::$tempFolder . self::$fileName;

        $isCreated = $this->iCalFileService->create($vEvent);

        static::assertEquals($iCalFile, $isCreated);
    }

    /**
     * @test
     */
    public function removeEvent()
    {
        $vEvent = new Event();
        $vEvent
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setNoTime(true)
            ->setSummary('Christmas');

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $this->iCalFileService->create($vEvent);

        $isRemoved = $this->iCalFileService->remove($vEvent);

        static::assertTrue($isRemoved);
    }

    /**
     * @test
     */
    public function createEventFromDomainObject()
    {
        $booking = new Booking();
        $booking
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-04-15'))
            ->setDtEnd(new \DateTime('2012-04-15'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setSummary('Christmas');

        $this->iCalFileService = $this
        ->getMockBuilder(ICalFileService::class)
        ->setMethods(
            [
                'getTypo3Host',
                'getTempFolder',
                'getFileName',
            ]
        )
        ->disableOriginalConstructor()
        ->getMock();

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTempFolder')
            ->willReturn(self::$tempFolder);

        $this->iCalFileService
            ->expects(static::any())
            ->method('getFileName')
            ->willReturn(self::$fileName);

        $iCalFile = self::$tempFolder . self::$fileName;

        $bookingEvent = $this->iCalFileService->createFromDomainObject($booking);

        static::assertEquals($iCalFile, $bookingEvent);
    }

    /**
     * @test
     */
    public function removeEventByDomainObject()
    {
        $booking = new Booking();
        $booking
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setSummary('Eastern');

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'dummy',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $remove = $this->iCalFileService->removeByDomainObject($booking);
        static::assertFalse($remove);
    }
}
