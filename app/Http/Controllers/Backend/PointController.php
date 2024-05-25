<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        $points = Point::query()->orderBy('id', 'desc')->paginate(15);
        return view('backend.point.index', compact('points', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'count' => 'required',
            'price' => 'required',
        ]);

        $point = new Point();
        $point->country_id = $request->country;
        $point->count = $request->count;
        $point->price = $request->price;
        $point->save();

        return back()->with('success', 'Point Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Point $point)
    {
        $request->validate([
            'country' => 'required',
            'count' => 'required',
            'price' => 'required',
        ]);

        $point->country_id = $request->country;
        $point->count = $request->count;
        $point->price = $request->price;
        $point->save();

        return back()->with('success', 'Point Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Point $point)
    {
        $point->delete();
//        return back()->with('success', 'Point Dele Successfully');
    }
}
