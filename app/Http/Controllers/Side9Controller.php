<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Side9Controller extends BaseController
{
    function get(Request $request) {
        $number = $request->get('h');
        return "hey" . $number;
    }
}
