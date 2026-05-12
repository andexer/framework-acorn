<?php

namespace App\Http\Controllers\WooCommerce;

use Illuminate\Http\Request;

class MyAccountController
{
    public function __invoke()
    {
        $usuario = wp_get_current_user();
        return view('framework::woocommerce.my-account', compact('usuario'));
    }
}
