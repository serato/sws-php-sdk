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
}
