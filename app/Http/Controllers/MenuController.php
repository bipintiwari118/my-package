<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{


public function index()
{
    $menus = Menu::orderBy('parent_id')->orderBy('id')->get();

    $parents = $menus->whereNull('parent_id');
    $groupedChildren = $menus->whereNotNull('parent_id')->groupBy('parent_id');

    return view('frontend.index', compact('parents', 'groupedChildren'));
}




    public function create(){
        $menus=Menu::all();
        return view('admin.menu.add',compact('menus'));
    }

    public function store(Request $request){
        Menu::create([
            'name'=>$request->name,
            'parent_id'=>$request->parent_id,
            'url'=>$request->url,
        ]);

        return redirect()->route('menu.list')->with('success','Menu Created Successfully.');
    }

    public function list(){
        $menus=Menu::orderBy('order')->get();
        return view('admin.menu.list',compact('menus'));
    }

    public function updateOrder(Request $request){
            $menuData = $request->input('menuData', []);

                foreach ($menuData as $item) {
                    \App\Models\Menu::where('id', $item['id'])->update([
                        'parent_id' => $item['parent_id'],
                        'order' => $item['order'],
                    ]);
                }

                return response()->json(['success' => true]);
}


public function edit($id){
    $menus=Menu::all();
    $menu=Menu::findOrFail($id);
     return view('admin.menu.edit',compact('menu','menus'));

}

public function update(Request $request,$id){
    $menu=Menu::findOrFail($id);
    $menu->update([
           'name'=>$request->name,
            'parent_id'=>$request->parent_id,
            'url'=>$request->url,
    ]);

     return redirect()->route('menu.list')->with('success','Menu Updated Successfully.');
}


public function delete($id){
     $menu=Menu::findOrFail($id);
     $menu->delete();
     return redirect()->route('menu.list')->with('success','Menu Deleted Successfully.');

}


}
