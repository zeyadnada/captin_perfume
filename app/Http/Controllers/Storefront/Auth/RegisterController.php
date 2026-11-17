<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('storefront.auth.register', [
            'title' => __('storefront.auth.register_title'),
        ]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('account.show')
            ->with('success', __('storefront.flash.registered'));
    }
}
