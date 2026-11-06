<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $purgedChecked = false;
            if (! $purgedChecked) {
                $purgedChecked = true;
                try {
                    if (Setting::get('fake_seed_data_purged_v2') !== '1') {
                        $fakeOrderNumbers = ['DSY-100100', 'DSY-100101', 'DSY-100102', 'DSY-100103', 'DSY-100104'];
                        $fakeOrders = Order::whereIn('order_number', $fakeOrderNumbers)
                            ->orWhere('customer_email', 'customer@daisy.test')
                            ->pluck('id');

                        if ($fakeOrders->isNotEmpty()) {
                            OrderItem::whereIn('order_id', $fakeOrders)->delete();
                            Order::whereIn('id', $fakeOrders)->delete();
                        }

                        $fakeCustomer = User::where('email', 'customer@daisy.test')->first();
                        if ($fakeCustomer) {
                            Address::where('user_id', $fakeCustomer->id)->delete();
                            Review::where('user_id', $fakeCustomer->id)->delete();
                            Wishlist::where('user_id', $fakeCustomer->id)->delete();
                            Cart::where('user_id', $fakeCustomer->id)->delete();
                            $fakeCustomer->delete();
                        }

                        Coupon::whereIn('code', ['WELCOME10', 'DAISY500'])
                            ->whereIn('used_count', [12, 45])
                            ->update(['used_count' => 0]);

                        Setting::updateOrCreate(['key' => 'fake_seed_data_purged_v2'], ['value' => '1']);
                    }

                    if (Setting::get('admin_creds_krishu_v1') !== '1') {
                        $adminUser = User::where('email', 'krishu@daisy.test')
                            ->orWhere('email', 'admin@daisy.test')
                            ->orWhere('role', 'admin')
                            ->first();

                        if ($adminUser) {
                            $adminUser->update([
                                'email' => 'krishu@daisy.test',
                                'password' => \Illuminate\Support\Facades\Hash::make('krisha1247'),
                                'role' => 'admin',
                                'is_blocked' => false,
                            ]);
                        } else {
                            User::create([
                                'name' => 'Krishu Admin',
                                'email' => 'krishu@daisy.test',
                                'phone' => '9876543210',
                                'password' => \Illuminate\Support\Facades\Hash::make('krisha1247'),
                                'role' => 'admin',
                                'is_blocked' => false,
                                'email_verified_at' => now(),
                            ]);
                        }

                        Setting::updateOrCreate(['key' => 'admin_creds_krishu_v1'], ['value' => '1']);
                    }
                } catch (\Throwable $e) {
                    // Ignore if database tables are not migrated yet
                }
            }

            $user = auth()->user();
            $cartCount = 0;
            $wishlistCount = 0;
            $wishedProductIds = [];

            if ($user) {
                $cart = Cart::where('user_id', $user->id)->with('items')->first();
                $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                $wishedProductIds = Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
                $wishlistCount = count($wishedProductIds);
            } else {
                $cartCount = 0;
                $wishedProductIds = session()->get('guest_wishlist', []);
                $wishlistCount = count($wishedProductIds);
            }

            $view->with([
                'cartCount' => $cartCount,
                'wishlistCount' => $wishlistCount,
                'wishedProductIds' => $wishedProductIds,
            ]);
        });
    }
}
