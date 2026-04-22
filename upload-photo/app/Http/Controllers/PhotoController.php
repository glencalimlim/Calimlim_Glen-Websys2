<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
   
public function create()
{
   $photos = Photo::latest()->paginate(10);
    return view('upload', compact('photos'));
}



public function storeSingle(Request $request)

{
    $request->validate([

'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',

]);

$image = $request->file('image');

$name = time().'_'.$image->getClientOriginalName();

$image->move(public_path('images'), $name);

Photo::create(['image' => $name]);

return back()->with('success', 'Single image uploaded successfully!');

}
public function storeMultiple(Request $request)

{

$request->validate([

'images.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',

]);

foreach ($request->file('images') as $image) {

$name = time().'_'.$image->getClientOriginalName();

$image->move(public_path('images'), $name);

Photo::create(['image' => $name]);

}

return back()->with('success', 'Multiple images uploaded successfully!');

}



public function destroy($id)
{
    $photo = Photo::findOrFail($id);

    
    $path = public_path('images/' . $photo->image);
    if (File::exists($path)) {
        File::delete($path);
    }

   
    $photo->delete();

   return redirect()->back()->with('success', 'Photo deleted successfully!');
}

}

