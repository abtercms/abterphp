<?php

namespace Integration\Website\Http;

/**
 * Defines the home tests
 */
class HomeTest extends IntegrationTestCase
{
    /**
     * Tests that the 404 template is set up correctly
     */
    public function test404PageIsSetUpCorrectly()
    {
        $this->get('/doesNotExist')
            ->go()
            ->assertResponse
            ->isNotFound();
    }

    /**
     * Tests that the home template is set up correctly
     */
    public function testHomePageIsSetUpCorrectly()
    {
        $this->get('/')
            ->go()
            ->assertResponse
            ->isOK();

        $description = 'AbterCMS is a security first, simple and flexible open source content management system for both educational and commercial usecases.'; // phpcs:ignore

//        $this->assertView
//            ->varEquals(
//                'title',
//                'New AbterCMS installation'
//            )
//            ->varEquals(
//                'metaKeywords',
//                ['cms', ' open source']
//            )
//            ->varEquals(
//                'metaDescription',
//                $description
//            )
//        ;
    }
}
