<?php

namespace Portrino\PxICal\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Andre Wuttig <wuttig@portrino.de>, portrino GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Portrino\PxICal\Domain\Model\Interfaces\ICalEventInterface;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Interface ICalServiceInterface
 *
 * @package Portrino\PxICal\Service
 */
interface ICalFileServiceInterface extends SingletonInterface
{

    /**
     * Create iCal file from domain object which implements the IcalEventInterface
     *
     * @param  ICalEventInterface $domainObject
     * @return string Filepath
     */
    public function createFromDomainObject($domainObject);

    /**
     * Remove corresponding iCal file of domain object which was maybe created before
     *
     * @param  ICalEventInterface $domainObject
     * @return bool
     */
    public function removeByDomainObject($domainObject);

    /**
     * Create iCal file from Event object
     *
     * @param  Event $vEvent
     * @return string Filepath
     */
    public function create($vEvent);


    /**
     * Create iCal file which maybe created before
     *
     * @param  Event $vEvent
     * @return boolean
     */
    public function remove($vEvent);
}
