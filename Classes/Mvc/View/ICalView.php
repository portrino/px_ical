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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\AbstractView;
use TYPO3\CMS\Extbase\Mvc\Web\Response as WebResponse;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class ICalView
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
    protected $fileName = 'cal.ics';

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
            // @todo Ticket: #63643 This should be solved differently once request/response model is available for TSFE.
            if (!empty($GLOBALS['TSFE']) && $GLOBALS['TSFE'] instanceof TypoScriptFrontendController) {
                /** @var TypoScriptFrontendController $typoScriptFrontendController */
                $typoScriptFrontendController = $GLOBALS['TSFE'];
                if (empty($typoScriptFrontendController->config['config']['disableCharsetHeader'])) {
                    // If the charset header is *not* disabled in configuration,
                    // TypoScriptFrontendController will send the header later with the Content-Type which we set here.
                    $typoScriptFrontendController->setContentType('text/calendar');
                } else {
                    // Although the charset header is disabled in configuration, we *must* send a Content-Type header here.
                    // Content-Type headers optionally carry charset information at the same time.
                    // Since we have the information about the charset, there is no reason to not include the charset information although disabled in TypoScript.
                    $response->setHeader(
                        'Content-Type',
                        'text/calendar; charset=' . trim($typoScriptFrontendController->metaCharset)
                    );
                }
            }
            $response->setHeader('Content-Type', 'text/calendar');
        }

        if (isset($this->variables['vEvent'])) {
            $vEvent = $this->variables['vEvent'];
            if ($vEvent instanceof Event) {
                $this->vCalendar->addComponent($vEvent);
                $this->fileName = 'calendar_' . $vEvent->getUniqueId() . '.ics';
            }
        }

        if (isset($this->variables['vEvents']) && is_array($this->variables['vEvents'])) {
            $this->fileName = 'calendar_';
            foreach ($this->variables['vEvents'] as $vEvent) {
                if ($vEvent instanceof Event) {
                    $this->vCalendar->addComponent($vEvent);
                    $this->fileName .= '_' . $vEvent->getUniqueId();
                }
            }
            $this->fileName .= '.ics';
        }

        $response->setHeader('Content-Disposition', 'attachment; filename="' . $this->fileName . '"');

        return $this->vCalendar->render();
    }

    /**
     * @return string
     */
    protected function getTypo3Host()
    {
        return GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY');
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
}
