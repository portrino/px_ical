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
use Portrino\PxICal\Domain\Model\Interfaces\IcalEventInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Class ICalFileService
 * @package Portrino\PxICal\Service
 */
class ICalFileService implements ICalFileServiceInterface
{
    const FOLDER_ICALFILES = 'px_ical';

    const PREFIX = 'calendar';

    const FILE_EXTENSION = '.ics';

    /**
     * Create iCal file from domain object which implements the IcalEventInterface
     *
     * @param IcalEventInterface $domainObject
     * @return string Filepath
     */
    public function createFromDomainObject($domainObject)
    {
        return $this->create($domainObject->__toICalEvent());
    }

    /**
     * Remove corresponding iCal file of domain object which was maybe created before
     *
     * @param IcalEventInterface $domainObject
     * @return bool
     */
    public function removeByDomainObject($domainObject)
    {
        return $this->remove($domainObject->__toICalEvent());
    }

    /**
     * @param Event $vEvent
     * @return string Filepath
     */
    public function create($vEvent)
    {
        $tempFolder = $this->getTempFolder();
        $fileName = $this->getFileName($vEvent);

        $iCalFile = $tempFolder . $fileName;

        $vCalendar = new Calendar($this->getTypo3Host());
        $vCalendar->addComponent($vEvent);

        GeneralUtility::writeFile($iCalFile, $vCalendar->render());

        return $iCalFile;
    }

    /**
     * @param Event $vEvent
     * @return bool
     */
    public function remove($vEvent)
    {
        $tempFolder = $this->getTempFolder();
        $fileName = $this->getFileName($vEvent);

        $iCalFile = $tempFolder . $fileName;

        $result = GeneralUtility::rmdir($iCalFile);

        return $result;
    }

    /**
     * @return string
     */
    protected function getTempFolder()
    {
        /**
         * we have to check if the tempFolder exists via pure php methods,
         * because folder is not part of FAL ResourceStorage
         */
        $tempFolder = GeneralUtility::getFileAbsFileName('typo3temp/' . self::FOLDER_ICALFILES);
        $tempFolder = PathUtility::sanitizeTrailingSeparator($tempFolder);
        if (file_exists($tempFolder) === false) {
            mkdir($tempFolder, 0775, true);
        }
        return $tempFolder;
    }

    /**
     * @param Event $vEvent
     * @return string
     */
    protected function getFileName($vEvent)
    {
        $fileName = self::PREFIX;
        if ($vEvent->getUniqueId()) {
            $fileName .= '_' . $vEvent->getUniqueId();
        }
        $fileName .= self::FILE_EXTENSION;
        return $fileName;
    }

    /**
     * @return string
     */
    protected function getTypo3Host()
    {
        return GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY');
    }
}
