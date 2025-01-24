<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = Checkin::orderBy('created_at', 'desc')->paginate(20);
        return view('checkin.list', compact('checkins'));
    }

    public function create()
    {
        return view('checkin.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'date' => 'required|date',
            'type' => 'required|string',
            'start_time' => 'required',
            'end_time' => ['required', function ($attribute, $value, $fail) use ($request) {
                $start_time = $request->input('start_time');
                if (strtotime($value) <= strtotime($start_time)) {
                    $fail('End time must be greater than start time.');
                }
            }],
            'signature' => 'required',
        ]);

        $signature = $request->input('signature');
        $signaturePath = 'signatures/' . uniqid() . '.png';
        $signatureData = str_replace('data:image/png;base64,', '', $signature);
        file_put_contents(public_path($signaturePath), base64_decode($signatureData));
        Checkin::create([
            'date' => $request->date,
            'type' => $request->type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'signature' => $signaturePath,
            'username' => $request->username,
        ]);

        return redirect()->back()->with('success', 'Check-in thành công!');
    }
}
