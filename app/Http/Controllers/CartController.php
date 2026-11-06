<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    protected function requireLoginResponse(Request $request): JsonResponse
    {
        $referer = $request->headers->get('referer');
        if ($referer) {
            $request->session()->put('url.intended', $referer);
        }

        return response()->json([
            'success' => false,
            'require_auth' => true,
            'redirect_url' => route('login', ['reason' => 'shop']),
            'message' => 'Please sign in first to add or buy products.',
        ], 401);
    }

    public function index(Request $request)
    {
        if (! $request->user()) {
            if ($request->query('fragment')) {
                return response(
                    '<div class="empty">' .
                    '<h3>Sign in required</h3>' .
                    '<p class="muted">Please sign in first to add products to your bag or place an order.</p>' .
                    '<a class="btn solid" href="' . route('login', ['reason' => 'shop']) . '" style="justify-content:center;margin-top:1rem">Sign in to shop</a>' .
                    '</div>'
                );
            }

            return redirect()->route('login', ['reason' => 'shop'])
                ->with('error', 'Please sign in first to view your bag or buy products.');
        }

        $cart = $this->cartService->getCart($request);
        $totals = $this->cartService->getTotals($cart);

        if ($request->query('fragment')) {
            return view('partials.cart-fragment', compact('cart', 'totals'));
        }

        return view('pages.cart', compact('cart', 'totals'));
    }

    public function add(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->requireLoginResponse($request);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_label' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $cart = $this->cartService->getCart($request);
        $size = $request->input('size_label', '16') ?: '16';
        $quantity = (int) $request->input('quantity', 1);

        $this->cartService->addItem($cart, $request->input('product_id'), $size, $quantity);

        $freshCart = $this->cartService->getCart($request);

        return response()->json([
            'success' => true,
            'message' => 'Piece added to your bag!',
            'cart_count' => $freshCart->total_quantity,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->requireLoginResponse($request);
        }

        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:0|max:10',
            'size_label' => 'nullable|string|max:10',
        ]);

        $cart = $this->cartService->getCart($request);
        $this->cartService->updateItem(
            $cart,
            $request->input('item_id'),
            $request->has('quantity') ? (int) $request->input('quantity') : null,
            $request->input('size_label')
        );

        $freshCart = $this->cartService->getCart($request);

        return response()->json([
            'success' => true,
            'cart_count' => $freshCart->total_quantity,
        ]);
    }

    public function remove(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->requireLoginResponse($request);
        }

        $request->validate([
            'item_id' => 'required|integer',
        ]);

        $cart = $this->cartService->getCart($request);
        $this->cartService->removeItem($cart, $request->input('item_id'));

        $freshCart = $this->cartService->getCart($request);

        return response()->json([
            'success' => true,
            'cart_count' => $freshCart->total_quantity,
        ]);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->requireLoginResponse($request);
        }

        $request->validate([
            'coupon_code' => 'required|string|max:30',
        ]);

        $cart = $this->cartService->getCart($request);
        $res = $this->cartService->applyCoupon($cart, $request->input('coupon_code'));

        return response()->json($res);
    }

    public function removeCoupon(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return $this->requireLoginResponse($request);
        }

        $cart = $this->cartService->getCart($request);
        $this->cartService->removeCoupon($cart);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
        ]);
    }
}
