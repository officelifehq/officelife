<?php

namespace Tests\Unit\Controllers\Auth;

use Tests\TestCase;
use App\Models\User\User;
use Tests\Helpers\GuzzleMock;
use App\Models\User\UserToken;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GithubProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class SocialiteCallbackControllerTest extends TestCase
{
    use DatabaseTransactions;

    private function mockSocialite($client = null): void
    {
        config(['auth.login_providers' => 'test']);

        /** @var \Laravel\Socialite\SocialiteManager $socialite */
        $socialite = app(SocialiteFactory::class);
        $socialite->extend(
            'test',
            function () use ($client) {
                $provider = new GithubProvider(app('request'), 'client_id', 'client_secret', 'redirect_url', []);
                if ($client) {
                    $provider->setHttpClient($client);
                }

                return $provider;
            }
        );
    }

    private function getMock(): GuzzleMock
    {
        return new GuzzleMock([
            'https://github.com/login/oauth/access_token' => [
                'access_token' => 'token',
            ],
            'https://api.github.com/user' => [
                'id' => 12345,
                'login' => 'dwigth',
                'name' => 'Dwight Schrute',
                'avatar_url' => '',
            ],
            'https://api.github.com/user/emails' => [[
                'primary' => true,
                'verified' => true,
                'email' => 'dwigth@dundermifflin.com',
            ]],
        ]);
    }

    /** @test */
    public function it_get_redirect_url(): void
    {
        $this->mockSocialite();
        Socialite::driver('test')->stateless();

        $response = $this->get('/auth/test');

        $response->assertStatus(302);
        $response->assertRedirect('https://github.com/login/oauth/authorize?client_id=client_id&redirect_uri=redirect_url&scope=user%3Aemail&response_type=code');
    }

    /** @test */
    public function it_get_user_created(): void
    {
        $mock = $this->getMock();
        $this->mockSocialite($mock->getClient());

        session()->put('state', 'state');

        $response = $this->get('/auth/test/callback?code=thecode&state=state');
        $response->assertStatus(302);
        $response->assertRedirect(config('app.url').'/home');

        $mock->assertResponses();

        $this->assertDatabaseHas('users', [
            'email' => 'dwigth@dundermifflin.com',
        ]);
        $user = User::where('email', 'dwigth@dundermifflin.com')->first();
        $this->assertDatabaseHas('user_tokens', [
            'driver_id' => 12345,
            'driver' => 'test',
            'user_id' => $user->id,
            'email' => 'dwigth@dundermifflin.com',
            'format' => 'oauth2',
            'token' => 'token',
        ]);
    }

    /** @test */
    public function it_associate_token_to_logged_user(): void
    {
        $mock = $this->getMock();
        $this->mockSocialite($mock->getClient());

        $user = User::factory()->create(['email' => 'dwigth@dundermifflin.com']);
        $this->actingAs($user);

        session()->put('state', 'state');

        $response = $this->get('/auth/test/callback?code=thecode&state=state');
        $response->assertStatus(302);
        $response->assertRedirect(config('app.url').'/home');

        $mock->assertResponses();

        $this->assertDatabaseHas('user_tokens', [
            'driver_id' => 12345,
            'driver' => 'test',
            'user_id' => $user->id,
            'email' => 'dwigth@dundermifflin.com',
            'format' => 'oauth2',
            'token' => 'token',
        ]);
    }

    /** @test */
    public function it_wont_associate_token_if_user_already_exist(): void
    {
        $mock = $this->getMock();
        $this->mockSocialite($mock->getClient());

        $user = User::factory()->create(['email' => 'dwigth@dundermifflin.com']);

        session()->put('state', 'state');

        $response = $this->get('/auth/test/callback?code=thecode&state=state');

        $mock->assertResponses();

        $response->assertStatus(302);
        $response->assertRedirect(config('app.url'));

        $this->assertDatabaseMissing('user_tokens', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_wont_associate_token_if_another_user_already_connected(): void
    {
        $mock = $this->getMock();
        $this->mockSocialite($mock->getClient());

        $user1 = User::factory()->create();
        UserToken::factory()->create([
            'driver_id' => 12345,
            'driver' => 'test',
            'user_id' => $user1->id,
            'email' => 'dwigth@dundermifflin.com',
            'format' => 'oauth2',
            'token' => 'token',
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        session()->put('state', 'state');

        $response = $this->get('/auth/test/callback?code=thecode&state=state');

        $mock->assertResponses();

        $response->assertStatus(302);
        $response->assertRedirect(config('app.url'));

        $this->assertDatabaseMissing('user_tokens', [
            'user_id' => $user->id,
        ]);
    }
}
