<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\DiscountCode;
use Illuminate\Support\Str;

class AccountTradingTest extends TestCase
{
    public function testBuy()
    {
        $account = Account::inRandomOrder()
            ->where('status_code', '>=', 400)
            ->where('status_code', '<=', 499)
            ->first();


        $route = route('account-trading.buy', ['account' => $account]);
        $user = User::factory()->make();
        $goldCoin = rand($account->calculateTemporaryPrice(), $account->calculateTemporaryPrice() + 200000);
        $user->gold_coin = $goldCoin;
        $user->save();

        # Case: enough gold coin to buy account
        $res = $this->actingAs($user)
            ->json('post', $route);
        $res->assertStatus(200);

        $res = $this->actingAs($user)
            ->json('get', route('profile.show'));
        $res->assertJson(
            fn ($j) => $j
                ->where('data.goldCoin',  $goldCoin - $account->cost)
        );

        $account = Account::inRandomOrder()
            ->where('status_code', '>=', 400)
            ->where('status_code', '<=', 499)
            ->first();
        $route = route('account-trading.buy', ['account' => $account]);
        # Case: don't enough gold coin to buy account
        $user->gold_coin = rand(1, $account->cost - 1);
        $user->save();
        $res = $this->actingAs($user)
            ->json('post', $route);
        $res->assertStatus(501);
    }

    public function testBuyRouteMiddleware()
    {
        $validAccount = Account::where('status_code', '>=', 400)
            ->where('status_code', '<=', 499)
            ->first();
        $boughtAccount = Account::where('status_code', '>=', 800)
            ->first();
        $invalidAccount = Account::where('status_code', '<', 400)
            ->orWhere('status_code', '>', 499)
            ->first();

        /**
         * Not auth
         * ------------
         */
        $this->json('post', route('account-trading.buy', ['account' => $validAccount]))
            ->assertStatus(401);

        /**
         * Auth as creator
         * -------------
         */

        # valid account
        $this->actingAs($validAccount->creator)
            ->json('post', route('account-trading.buy', ['account' => $validAccount]))
            ->assertStatus(403);

        # invalid account
        $this->actingAs($invalidAccount->creator)
            ->json('post', route('account-trading.buy', ['account' => $invalidAccount]))
            ->assertStatus(403);

        # bought account
        $this->actingAs($boughtAccount->creator)
            ->json('post', route('account-trading.buy', ['account' => $boughtAccount]))
            ->assertStatus(403);


        /**
         * Auth as regular user
         * -------------
         */
        $user = User::factory()->make();
        $user->save();

        # valid account
        $this->actingAs($user)
            ->json('post', route('account-trading.buy', ['account' => $validAccount]))
            ->assertStatus(501);

        # invalid account
        $this->actingAs($user)
            ->json('post', route('account-trading.buy', ['account' => $invalidAccount]))
            ->assertStatus(403);

        # bought account
        $this->actingAs($user)
            ->json('post', route('account-trading.buy', ['account' => $boughtAccount]))
            ->assertStatus(403);
    }

    // public function testCalculateDetailPrice()
    // {
    //     $account = Account::inRandomOrder()->first();
    //     $route = route('account-trading.calculate-detail-price', ['account' => $account]);

    //     $discountCode = DiscountCode::create([
    //         'discount_code' => Str::random(50),
    //         'percentage_discount' => rand(0, 100),
    //         'directDiscount' => rand(0, 20000),
    //     ]);
    //     $discountCode->refresh();

    //     $fee =

    //     $res = $this->json('post', $route, [
    //         'discountCode' => $discountCode->getKey(),
    //     ]);
    //     $res->assertStatus(200);
    //     $res->assertJson(fn ($j) => $j
    //         ->has(
    //             'data',
    //             fn ($j) => $j
    //                 ->where('cost', $account->cost)
    //                 ->where('fee', $fee)
    //         ));
    // }
}