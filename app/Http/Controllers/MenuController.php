<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }
    
    public function create()
    {
        return view('menu.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Jasmine,Dinorado,Sinandomeng,Brown Rice',
            'price_per_kilo' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ]);
        
        Menu::create($request->all());
        
        return redirect()->route('menu.index')->with('success', 'Rice product added successfully!');
    }
    
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menu.edit', compact('menu'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Jasmine,Dinorado,Sinandomeng,Brown Rice',
            'price_per_kilo' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ]);
        
        $menu = Menu::findOrFail($id);
        $menu->update($request->all());
        
        return redirect()->route('menu.index')->with('success', 'Rice product updated successfully!');
    }
    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        
        return redirect()->route('menu.index')->with('success', 'Rice product deleted successfully!');
    }
}