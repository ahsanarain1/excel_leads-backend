<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\IpWhitelist;
use Illuminate\Http\Request;

class IpWhitelistController extends Controller
{

    public function index()
    {
        $ips = IpWhitelist::all();

        return response()->json([
            'success' => true,
            'data' => $ips,
            'count' => $ips->count(),
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'ip_address' => 'required|ip|unique:ip_whitelist,ip_address',
        ]);

        $whitelist = IpWhitelist::create([
            'ip_address' => $request->ip_address,
        ]);

        return response()->json(['success' => true, 'message' => 'IP added to whitelist', 'data' => $whitelist], 201);
    }

    public function destroy($id)
    {
        $ip = IpWhitelist::find($id);

        if (!$ip) {
            return response()->json(['message' => 'IP not found'], 404);
        }

        $ip->delete();
        return response()->json(['message' => 'IP removed from whitelist']);
    }
}
