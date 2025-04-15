<?php

namespace App\Http\Controllers;

use App\Jobs\SendTestEmailJob;
use Illuminate\Http\Request;


class TestMailController
{
    public function send(Request $request)
    {
        $email = $request->input('email', 'youremail@example.com');

        SendTestEmailJob::dispatch($email);

        return response()->json([
            'message' => 'Письмо отправлено в очередь!',
            'email' => $email,
        ]);
    }

}
