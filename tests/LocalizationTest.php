<?php

namespace LaLu\JER;

class LocalizationTest extends \PHPUnit_Framework_TestCase
{
    public function testEN()
    {
        $lang = include __DIR__.'/../src/resources/lang/en/messages.php';
        $this->assertNotEmpty($lang);
    }
}
