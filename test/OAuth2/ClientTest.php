<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Test\Odnoklassniki\OAuth2;

use alxmsl\Odnoklassniki\OAuth2\Client;
use PHPUnit_Framework_TestCase;

/**
 * OAUth2 client test class
 * @author alxmsl
 */
final class ClientTest extends PHPUnit_Framework_TestCase {
    public function testInitialState() {
        /** @var Client $Client */
        $Client = new Client();
        $this->assertEmpty($Client->getClientId());
        $this->assertEmpty($Client->getClientSecret());
        $this->assertEmpty($Client->getRedirectUri());
        $this->assertEquals(0, $Client->getConnectTimeout());
        $this->assertEquals(0, $Client->getRequestTimeout());
    }

    public function testProperties() {
        $Client = new Client();

        $Client->setClientId('clientIdentifier');
        $this->assertEquals('clientIdentifier', $Client->getClientId());

        $Client->setClientSecret('ClienTSECRET');
        $this->assertEquals('ClienTSECRET', $Client->getClientSecret());

        $Client->setRedirectUri('reDIRECTuri');
        $this->assertEquals('reDIRECTuri', $Client->getRedirectUri());

        $Client->setConnectTimeout(30.);
        $this->assertSame(30, $Client->getConnectTimeout());

        $Client->setRequestTimeout('60abc');
        $this->assertSame(60, $Client->getRequestTimeout());
    }

    public function testCreateAuthUrl() {
        $Client = new Client();
        $this->assertEquals('http://www.odnoklassniki.ru/oauth/authorize?client_id=&response_type=code&redirect_uri=&scope='
            , $Client->createAuthUrl([]));
        $this->assertEquals('http://www.odnoklassniki.ru/oauth/authorize?client_id=&response_type=code&redirect_uri=&scope=&layout=m'
            , $Client->createAuthUrl([], true));
        $this->assertEquals('http://www.odnoklassniki.ru/oauth/authorize?client_id=&response_type=code&redirect_uri=&scope=VALUABLE ACCESS;SET STATUS;PHOTO CONTENT&layout=m'
            , $Client->createAuthUrl([
                Client::SCOPE_VALUABLE_ACCESS,
                Client::SCOPE_SET_STATUS,
                Client::SCOPE_PHOTO_CONTENT,
            ], true));

        $Client->setClientId('clientIdentifier')
            ->setRedirectUri('https://example.com/oauth2callback');
        $this->assertEquals('http://www.odnoklassniki.ru/oauth/authorize?client_id=clientIdentifier&response_type=code&redirect_uri=https://example.com/oauth2callback&scope=VALUABLE ACCESS;PHOTO CONTENT'
            , $Client->createAuthUrl([
                Client::SCOPE_VALUABLE_ACCESS,
                Client::SCOPE_PHOTO_CONTENT,
            ]));
    }
}
