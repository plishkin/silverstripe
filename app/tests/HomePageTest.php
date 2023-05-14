<?php

namespace App\tests;

use SilverStripe\Dev\FunctionalTest;

class HomePageTest extends FunctionalTest
{
    public function testViewHomePage()
    {
        $page = $this->get('/');

        // Home page should load..
        $this->assertEquals(200, $page->getStatusCode());
    }

}
