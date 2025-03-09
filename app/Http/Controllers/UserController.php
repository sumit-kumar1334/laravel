<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
class UserController extends Controller
{
    public function index(Request $request){
        $users = User::all();
        return view('users.index',compact('users'));

    }
    public function create(Request $request){
        $user = Auth::user();
        $roles = Role::all();
        return view('users.create',compact('roles'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|exists:roles,name',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->save();
        if ($request->cropped_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->cropped_image));
            $fileSizeKB = strlen($imageData) / 1024;
            if ($fileSizeKB < 500 || $fileSizeKB > 1024) {
                return redirect()->route('user.index')->with('success','File size must be between 500KB and 1MB');
            }
            $imageName = 'profile_images/' . uniqid() . '.jpg';
            Storage::put('public/' . $imageName, $imageData);
            $users->update([
                'profile_image' => $imageName,
            ]);
        }
        $users->assignRole($request->role);
        return redirect()->route('user.index')->with('success','User Created Successfully');
    }
    public function edit(Request $request,$id){
        $user = User::find($id);
        $roles = Role::all();
        return view('users.edit',compact('user','roles'));
    }
    public function update(Request $request,$id){
        $user = User::find($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        if($request->password){
            $user->update(['password' => Hash::make($request->password)]);
        }
        if ($request->cropped_image) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->cropped_image));
            $fileSizeKB = strlen($imageData) / 1024;
            if ($fileSizeKB < 500 || $fileSizeKB > 1024) {
                return redirect()->route('user.index')->with('success','File size must be between 500KB and 1MB');
            }
            $imageName = 'profile_images/' . uniqid() . '.jpg';
            Storage::put('public/' . $imageName, $imageData);
            $user->update([
                'profile_image' => $imageName,
            ]);
        }
        $user->syncRoles($request->role);
        return redirect()->route('user.index')->with('success','User Updated Successfully');
    }
    public function delete($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index')->with('success','User Deleted Successfully');
    }
    public function uploadForm()
    {
        return view('upload');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048', // Validate file type and size
        ]);

        // Import data
        Excel::import(new UsersImport, $request->file('file'));

        // Check if duplicate file exists
        if (session()->has('duplicate_file')) {
            return response()->json([
                'message' => 'File uploaded successfully!',
                'duplicate_file' => route('download.duplicates', ['file' => session('duplicate_file')]),
            ]);
        }

        return response()->json(['message' => 'File uploaded successfully! No duplicates found.']);
    }
    public function downloadDuplicates($file)
    {
        if (Storage::exists($file)) {
            return Storage::download($file);
        }
        return abort(404, 'File not found');
    }
}
