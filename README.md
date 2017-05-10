# px_ical - 0.1.0
Provides TYPO3 classes to render *.ics files via [eluceo — iCal](https://github.com/markuspoerschke/iCal) library.

## Installation

```sh
composer require portrino/px_ical
```

## Usage

### Extbase view

You can prepend `?tx_par_pi1[format]=ical` to your action controller request and extbase 
renders the corresponding view for you. By putting the ICalView class into the `$viewFormatToObjectNameMap`
extbase is able to get the correct view class for your request. When you following the Domain Driven Design
you have a domain model which is assigned to the view and get rendered by the different view classes.

By implementing the `ICalEventInterface` the ICalView class calls the `__toICalEvent()` method which 
have to be implemented by yourself. You have to return an `Eluceo\iCal\Component\Event` object here.

```php
use Portrino\PxICal\Mvc\View\ICalView;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

class BookingController extends RestController
{
    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = [
        'json' => JsonView::class,
        'ical' => ICalView::class
    ];

    /**
     * Action Show
     *
     * @param \Foo\Bar\Domain\Model\Booking $booking
     *
     * @return void
     */
    public function showAction($booking)
    {
        /**
         * $booking should implement the ICalEventInterface
         */
        $this->view->assign('booking', $booking);
    }
    
}

...

class Booking extends AbstractEntity implements ICalEventInterface
{

    /**
     * @return Event
     */
    public function __toICalEvent()
    {
        $event = new Event();

        $event
            ->setUniqueId('foo_bar_' . (string)$this->getUid())
            ->setDtStart($this->getStart())
            ->setDtEnd($this->getEnd());

        ...

        return $event;
    }
}
```

If you do not have a domain model or the `__toICalEvent()` does not fit your needs, you can also assign the
`Eluceo\iCal\Component\Event` object directly to the ICal view with the variable name **vEvent**

```php

use Eluceo\iCal\Component\Event;

/**
 * Action Show
 *
 * @return void
 */
public function showAction()
{
    $vEvent = new Event();
    
    $vEvent
        ->setUniqueId('foo_bar_' . (string)$this->getUid())
        ->setDtStart($this->getStart())
        ->setDtEnd($this->getEnd());
        
    ...

    $this->view->assign('vEvent', $vEvent);
}

```

## Service

This extensions provides a service class which creates an iCal file for you and put this file into: `/typo3temp/px_ical` folder.
You just have to inject the class into your controller, ... and then you can call these methods to generates / remove the ical file.

_Dependecy Injection:_

```php
/**
 * @var \Portrino\PxICal\Service\ICalFileServiceInterface
 * @inject
 */
protected $iCalFileService;
```


_from domain object:_

```php
$file = $this->iCalFileService->createFromDomainObject($booking);
```
_from event object:_

```php
$file = $this->iCalFileService->create($vEvent);
```


To remove created files you just have to call the inverse method which does the job for you:

_by domain object:_
```php
$file = $this->iCalFileService->removeByDomainObject($booking);
```

_by event object:_
```php
$file = $this->iCalFileService->remove($vEvent);
```
