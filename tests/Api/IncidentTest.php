<?php

/*
 * This file is part of Cachet.
 *
 * (c) Cachet HQ <support@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Tests\Cachet\Api;

use CachetHQ\Tests\Cachet\AbstractTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IncidentTest extends AbstractTestCase
{
    use DatabaseMigrations;

    public function testGetIncidents()
    {
        $this->get('/api/v1/incidents');
        $this->seeJson(['data' => []]);
        $this->assertResponseOk();
    }

    public function testGetInvalidIncident()
    {
        $this->get('/api/v1/incidents/1');
        $this->assertResponseStatus(404);
    }

    public function testPostIncidentUnauthorized()
    {
        $this->post('/api/v1/incidents');
        $this->assertResponseStatus(401);
        $this->seeJson(['message' => 'You are not authorized to view this content.', 'status_code' => 401]);
    }

    public function testPostIncidentNoData()
    {
        $this->beUser();

        $this->post('/api/v1/incidents');
        $this->assertResponseStatus(400);
    }

    public function testPostIncident()
    {
        $this->beUser();

        $this->post('/api/v1/incidents', [
            'name'    => 'Foo',
            'message' => 'Lorem ipsum dolor sit amet',
            'status'  => 1,
        ]);
        $this->seeJson(['name' => 'Foo']);
        $this->assertResponseOk();
    }

    public function testGetNewIncident()
    {
        $incident = factory('CachetHQ\Cachet\Models\Incident')->create();

        $this->get('/api/v1/incidents/1');
        $this->seeJson(['name' => $incident->name]);
        $this->assertResponseOk();
    }

    public function testPutIncident()
    {
        $this->beUser();
        $component = factory('CachetHQ\Cachet\Models\Incident')->create();

        $this->put('/api/v1/incidents/1', [
            'name' => 'Foo',
        ]);
        $this->seeJson(['name' => 'Foo']);
        $this->assertResponseOk();
    }

    public function testDeleteIncident()
    {
        $this->beUser();
        $component = factory('CachetHQ\Cachet\Models\Incident')->create();

        $this->delete('/api/v1/incidents/1');
        $this->assertResponseStatus(204);
    }
}
