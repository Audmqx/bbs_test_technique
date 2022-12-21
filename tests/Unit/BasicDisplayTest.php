<?php

namespace Tests\Unit;

use App\Service\BasicDisplayApi;
use Tests\TestCase;


use function PHPUnit\Framework\assertJson;

class BasicDisplayTest extends TestCase
{

    public function test_getMediaIds_should_have_medias_and_ids()
    {   
        $basicDisplay = new BasicDisplayApi();
        $response = $basicDisplay->getMediaIds();
        $this->assertArrayHasKey('media', $response);
        $this->assertArrayHasKey('id', $response);
    }

    public function test_getMediaIds_should_fail()
    {   
        $falseUserId = '554654560';
        $basicDisplay = new BasicDisplayApi($falseUserId);
        $response = $basicDisplay->getMediaIds();
        $this->assertArrayNotHasKey('media', $response);
        $this->assertArrayNotHasKey('id', $response);
    }

    public function test_getMediaDatas_should_have_data()
    {
        $basicDisplay = new BasicDisplayApi();
        $response = $basicDisplay->getMediasDatas();
        $this->assertArrayNotHasKey('media_type', $response);
        $this->assertArrayNotHasKey('media_url', $response);
        $this->assertArrayNotHasKey('username', $response);
    }
}
