<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Test;

use Serato\SwsSdk\FirewallHeader;

class FirewallHeaderTest extends AbstractTestCase
{
    public function testGetHeaderValue(): void
    {
        $firewallHeader = new FirewallHeader();
        $headerValue = $firewallHeader->getHeaderValue();

        // Check that the header returned matches the expected pattern (see comments in FirewallHeader)
        $this->assertRegExp(FirewallHeader::HEADER_PATTERN, $headerValue);
    }

    public function testGetHeaderValueTwice(): void
    {
        $firewallHeader = new FirewallHeader();
        $firstHeaderValue = $firewallHeader->getHeaderValue();
        $secondHeaderValue = $firewallHeader->getHeaderValue();

        // Check that the same header is returned if the value is requested multiple times from the same instance
        $this->assertEquals($firstHeaderValue, $secondHeaderValue);
    }
}
