<?php

namespace Portrino\PxICal\Mvc\View;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\AbstractView;
use TYPO3\CMS\Extbase\Mvc\Web\Response as WebResponse;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ICalView
 *
 * @package Portrino\PxICal\Mvc\View
 */
class ICalView extends AbstractView
{

    /**
     * @var Calendar
     */
    protected $vCalendar;

    /**
     * @var string
     */
    protected $overrideFileName = '';

    /**
     * @var string
     */
    protected $prefix = 'calendar_';

    /**
     *
     */
    public function initializeView()
    {
        $this->vCalendar = new Calendar($this->getTypo3Host());
    }

    /**
     * Renders the view
     *
     * @return string The rendered view
     * @api
     */
    public function render()
    {
        $response = $this->controllerContext->getResponse();
        if ($response instanceof WebResponse) {
            $response->setHeader('Content-Type', 'text/calendar; charset=utf-8');
            $generatedFileName = '';

            /**
             * one variable can be processed
             */
            if (count($this->getVariablesWithIcalEvents()) === 1) {
                foreach ($this->getVariablesWithIcalEvents() as $variable) {
                    if ($variable instanceof ICalEventInterface) {
                        $vEvent = $variable->__toICalEvent();
                        $this->vCalendar->addComponent($vEvent);
                        $generatedFileName = $this->prefix . $vEvent->getUniqueId() . '.ics';
                    }
                    break;
                }
            }

            /**
             * multiple variables can be processed
             */
            if (count($this->getVariablesWithIcalEvents()) > 1) {
                $filehash = '';
                foreach ($this->getVariablesWithIcalEvents() as $variable) {
                    if ($variable instanceof ICalEventInterface) {
                        $vEvent = $variable->__toICalEvent();
                        $this->vCalendar->addComponent($vEvent);
                        $filehash .= $vEvent->getUniqueId();
                    }
                }
                $generatedFileName = $this->prefix . md5($filehash) . '.ics';
            }

            /**
             * vEvent variable can be processed
             */
            if (isset($this->variables['vEvent'])) {
                $vEvent = $this->variables['vEvent'];
                if ($vEvent instanceof Event) {
                    $this->vCalendar->addComponent($vEvent);
                    $generatedFileName = $this->prefix . $vEvent->getUniqueId() . '.ics';
                }
            }

            /**
             * vEvents variable can be processed
             */
            if (isset($this->variables['vEvents']) && is_array($this->variables['vEvents'])) {
                $filehash = '';
                foreach ($this->variables['vEvents'] as $vEvent) {
                    if ($vEvent instanceof Event) {
                        $this->vCalendar->addComponent($vEvent);
                        $filehash .= $vEvent->getUniqueId();
                    }
                }
                $generatedFileName = $this->prefix . md5($filehash) . '.ics';
            }

            $fileName = (!empty($this->overrideFileName)) ? $this->overrideFileName : $generatedFileName;

            $response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $response->setContent($this->vCalendar->render());
            $response->send();
            die();
        }
        return '';
    }

    /**
     * @return string
     */
    protected function getTypo3Host()
    {
        return GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY');
    }

    /**
     * @param string $overrideFileName
     */
    public function setOverrideFileName(string $overrideFileName)
    {
        $this->overrideFileName = $overrideFileName;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Return only variables which have the type
     *
     * @return array
     */
    protected function getVariablesWithIcalEvents()
    {
        $result = [];
        foreach ($this->variables as $variable) {
            if ($variable instanceof ICalEventInterface) {
                $result[] = $variable;
            }
        }
        return $result;
    }
}
