<?php
namespace Portrino\PxICal\Tests\Service;

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
     * @test
     */
    public function isCreated()
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
                    'getFileName',
                ]
            )
            ->getMock();

        $this->iCalFileService->expects(static::any())
                    ->method('getTypo3Host')
                    ->willReturn('www.example.com');

        $this->iCalFileService->expects(static::any())
            ->method('getFileName')
            ->willReturn('testfile.ics');

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
        ;

        $this->iCalFileService = $this
            ->getMockBuilder(ICalFileService::class)
            ->setMethods(
                [
                    'getTypo3Host',
                    'getFileName',
                ]
            )
            ->getMock();

        $this->iCalFileService->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn('www.example.com');


        $this->iCalFileService->expects(static::any())
            ->method('getFileName')
            ->willReturn('testfile.ics');

        $isRemoved = $this->iCalFileService->remove($vEvent);

        static::assertTrue($isRemoved);
    }
}
