<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();
        $devices = Device::where('guru_id', $guru->id)->get();

        return response()->json(['devices' => $devices]);
    }
}
