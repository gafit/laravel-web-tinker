<?php

namespace Spatie\WebTinker\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\WebTinker\Tinker;

class WebTinkerController
{
    public function index()
    {
        try {
            \Debugbar::disable();
        }
        catch (\Exception $e){}
        return view('web-tinker::web-tinker');
    }

    public function execute(Request $request)
    {
        try {
            \Debugbar::disable();
        }
        catch (\Exception $e){}
        $validated = $request->validate([
            'code' => 'required',
        ]);

        return Tinker::execute($validated['code']);
    }
}
