<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Itinearary;
use Illuminate\Http\Request;

class ItineararyController extends Controller
{
    public function create(){
        return view('admin.itinearary.add');
    }

    public function store(Request $request){
        Itinearary::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'itinerary'=> !empty($request->itinerary) ? json_encode($request->itinerary) : null,
        ]);

         return redirect()->route('itinearary.list')->with('success', 'Itinerary created successfully.');
    }

    public function list(){
        $items=Itinearary::all();
        return view('admin.itinearary.list',compact('items'));
    }

    public function edit($id){
        $item=Itinearary::findOrFail($id);
         return view('admin.itinearary.edit',compact('item'));

    }

    public function update(Request $request,$id){
        // dd($request->all());
          $item=Itinearary::findOrFail($id);
          $item->update([
              'name'=>$request->name,
            'email'=>$request->email,
            'itinerary'=> !empty($request->itinerary) ? json_encode($request->itinerary) : null,
          ]);


         return redirect()->route('itinearary.list')->with('success', 'Itinerary Updated successfully.');

    }
}
