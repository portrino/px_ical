<?php
namespace Portrino\PxICal\Tests\Unit\Service;

use Eluceo\iCal\Component\Event;
use Portrino\PxICal\Service\ICalFileService;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

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
    protected static $getFileName = 'calendar_12345.ics';

    const PREFIX = 'calendar';
    const UNID = '12345';
    const FILE_EXTENSION = '.ics';

    protected static $typo3host = 'www.example.com';

    /**
     * @test
     */
    public function isCreated()
    {
        $vEvent = new Event();
        $vEvent
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setNoTime(true)
            ->setSummary('Christmas')
            ->setUniqueId(self::UNID);

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                    'getFileName',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->iCalFileService
            ->expects(static::any())
                    ->method('getTypo3Host')
                    ->willReturn(self::$typo3host);

        $fileName = self::PREFIX . '_' . $vEvent->getUniqueId() . self::FILE_EXTENSION;

        $this->iCalFileService
            ->expects(static::any())
            ->method('getFileName')
            ->with($vEvent)
            ->willReturn($fileName);

        $isCreated = $this->iCalFileService->create($vEvent);

        static::assertFileExists($isCreated);
    }

    /**
     * @test
     */
    public function isRemoved()
    {
        $vEvent = new Event();
        $vEvent
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setNoTime(true)
            ->setSummary('Christmas')
            ->setUniqueId(self::UNID);

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                    'getFileName',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->iCalFileService
            ->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $fileName = self::PREFIX . '_' . $vEvent->getUniqueId() . self::FILE_EXTENSION;

        $this->iCalFileService
            ->expects(static::any())
            ->method('getFileName')
            ->with($vEvent)
            ->willReturn($fileName);

        $this->iCalFileService->create($vEvent);

        $isRemoved = $this->iCalFileService->remove($vEvent);

        static::assertTrue($isRemoved);
    }
}
