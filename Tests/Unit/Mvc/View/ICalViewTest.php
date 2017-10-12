<?php

namespace Portrino\PxICal\Tests\Unit\Mvc\View;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Portrino\PxICal\Mvc\View\ICalView;
use Portrino\PxICal\Tests\Fixture\Booking;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Form\Mvc\Controller\ControllerContext;

/**
 * Class ICalViewTest
 *
 * @package Portrino\PxICal\Tests\Mvc\View
 */
class ICalViewTest extends UnitTestCase
{
    /**
     * @var ICalView|PHPUnit_Framework_MockObject_MockObject
     */
    protected $view;

    /**
     * @var string
     */
    protected static $typo3host = 'www.example.com';

    /**
     * @var string
     */
    protected static $FileName = 'testFileName';

    /**
     * @var string
     */
    protected static $prefix = 'cal_';

    /**
     *
     */
    protected function setUp()
    {
        $this->view = $this
            ->getMockBuilder(ICalView::class)
            ->setMethods(
                [
                    'getTypo3Host'
                ]
            )
            ->getMock();

        $this->view->expects(static::any())
            ->method('getTypo3Host')
            ->willReturn(self::$typo3host);

        $this->view->initializeView();

        /** @var ControllerContext|PHPUnit_Framework_MockObject_MockObject $controllerContext */
        $controllerContext = $this
            ->getMockBuilder(ControllerContext::class)
            ->setMethods(
                [
                    'getResponse',
                ]
            )
            ->getMock();

        $response = $this
            ->getMockBuilder(Response::class)
            ->setMethods(
                [
                    'getResponse',
                ]
            )
            ->getMock();

        $controllerContext
            ->expects(static::any())
            ->method('getResponse')
            ->willReturn($response);

        $this->view->setControllerContext($controllerContext);
    }

    /**
     * @test
     */
    public function renderSingleEventAsEvent()
    {
        $vEvent = new Event();
        $vEvent
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setNoTime(false)
            ->setSummary('Christmas');

        $vCalendar = new Calendar(self::$typo3host);
        $vCalendar->addComponent($vEvent);

        $this->view->assign('vEvent', $vEvent);

        $renderedView = $this->view->render();

        $this->view->setOverrideFileName(self::$FileName);
        $this->view->setPrefix(self::$prefix);
        static::assertEquals($vCalendar->render(), $renderedView);
    }

    /**
     * @test
     */
    public function renderMultipleEventsAsEvent()
    {
        $vEvent1 = new Event();
        $vEvent1
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setNoTime(false)
            ->setSummary('Christmas');

        $vEvent2 = new Event();
        $vEvent2
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setNoTime(false)
            ->setSummary('Eastern');

        $vCalendar = new Calendar(self::$typo3host);
        $vCalendar->addComponent($vEvent1);
        $vCalendar->addComponent($vEvent2);

        $this->view->assign('vEvents', [$vEvent1, $vEvent2]);

        $renderedView = $this->view->render();

        $this->view->setPrefix(self::$prefix);
        $this->view->setOverrideFileName(self::$FileName);
        static::assertEquals($vCalendar->render(), $renderedView);
    }

    /**
     * @test
     */
    public function renderMultipleEventsAsBooking()
    {
        $booking1 = new Booking();
        $booking1
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-04-15'))
            ->setDtEnd(new \DateTime('2012-04-15'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setSummary('Christmas');

        $booking2 = new Booking();
        $booking2
            ->setUniqueId('abcdef')
            ->setDtStart(new \DateTime('2012-12-24'))
            ->setDtEnd(new \DateTime('2012-12-24'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setSummary('Eastern');

        $vCalendar = new Calendar(self::$typo3host);
        $vCalendar->addComponent($booking1->__toICalEvent());
        $vCalendar->addComponent($booking2->__toICalEvent());

        $this->view
            ->assign('booking1', $booking1)
            ->assign('booking2', $booking2);

        $renderedView = $this->view->render();

        $this->view->setPrefix(self::$prefix);
        $this->view->setOverrideFileName(self::$FileName);
        static::assertEquals($vCalendar->render(), $renderedView);
    }

    /**
     * @test
     */
    public function renderSingleEventAsBooking()
    {
        $booking1 = new Booking();
        $booking1
            ->setUniqueId('123456')
            ->setDtStart(new \DateTime('2012-04-15'))
            ->setDtEnd(new \DateTime('2012-04-15'))
            ->setDtStamp(new \DateTime('20171012T062640Z'))
            ->setSummary('Christmas');

        $vCalendar = new Calendar(self::$typo3host);
        $vCalendar->addComponent($booking1->__toICalEvent());

        $this->view->assign('booking1', $booking1);

        $renderedView = $this->view->render();

        $this->view->setPrefix(self::$prefix);
        $this->view->setOverrideFileName(self::$FileName);
        static::assertEquals($vCalendar->render(), $renderedView);
    }
}
