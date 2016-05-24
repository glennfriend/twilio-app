<?php
namespace App\Controllers\Other;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager;
use App\Utility\Url\TwilioWrap;

/**
 *
 */
class Twilio extends AdminPageController
{

    /**
     *
     */
    protected function init()
    {
        MenuManager::setMain('other');
    }

    /**
     *
     */
    protected function test()
    {
        MenuManager::setSub('twilio-test');

        $accounts = TwilioWrap::get('/2010-04-01/Accounts.json');

        /*
        foreach ($accounts['accounts'] as $account) {
            if (!isset($account['subresource_uris'])) {
                continue;
            }
            if (!isset($account['subresource_uris']['incoming_phone_numbers'])) {
                continue;
            }
            $incomingPhone = $account['subresource_uris']['incoming_phone_numbers'];
            $phone = TwilioWrap::get($incomingPhone);
            echo '<br/> -> ' . count($phone['incoming_phone_numbers']);
        }
        */

        $this->render('other.twilio.test', [
            'accounts' => $accounts,
        ]);
    }

}
