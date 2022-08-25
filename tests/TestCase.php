<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;
    use WithFaker;

    /**
     * @param $data
     * @return Request
     */
    public function createCustomRequest($data): Request
    {
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));
        return $request;
    }

    public function login($user = null)
    {
        $userLogin = $user ?? User::factory()->create();
        $this->actingAs($userLogin);
        return $userLogin;
    }
}
