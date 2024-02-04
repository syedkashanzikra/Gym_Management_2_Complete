<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GateCloudController extends Controller
{
    function handleWebhook(Request $request)
    {
        logger($request);
        $payload = $request->payload;
        $method = 'handle' . Str::studly(str_replace('.', '_', $request->type));

        if (method_exists($this, $method)) {
            $this->{$method}($payload);
        }

        return response()->json(true, 200);
    }

    protected function handleUsersLogCreated(array $payload)
    {
        logger($payload);
    }
}
