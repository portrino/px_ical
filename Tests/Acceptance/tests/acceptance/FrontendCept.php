<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('Open frontend without errors.');

$I->amOnPage('/');

$I->seeInTitle('New TYPO3 Console site: Home');
$I->see('TYPO3');
