<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {



    if (!Auth::check()) {
        return view('welcome');
    }
    $role_id = Auth::user()->role_id;
    if ($role_id == '1') {
        $users = User::orderBy('created_at', 'asc')->get();
            $totalUsers = $users->count();
        return view('users.users', compact('users','totalUsers'));
    } elseif ($role_id == '2') {
       $users = User::where('region', Auth::user()->region)
            ->orderBy('created_at', 'asc')
            ->get();
        $totalUsers = $users->count();
        return view('users.users', compact('users','totalUsers' ));
    }


       // $users = User::onlyTrashed('created_at', 'asc')->get(); ipapakita yung binura
       // $users = User::withTrashed('created_at', 'asc')->get(); ipapakita yung binura at hindi
        $users = User::orderBy('created_at', 'asc')->get();
        return view('users.users', compact('users'));
    }
    //check changes

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return  view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:8',
            'office_unit'=>'required',
            'contact_number'=>'nullable',
            'role_id'=>'required',
            'region'=>'required',
            'photo'=>'required|image|mimes:jpg,jpeg,png'
        ]);

        
        // Ensure the public storage is linked to the public app
    // Run: php artisan storage:link

        $fileName = null;

        if($request->hasFile('photo')){
            $file=$request->file('photo');
            $fileName=time() . '_' .
            $file ->getClientOriginalName();
            $file->storeAs('public/uploads',$fileName);
        }
        User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'office_unit'=>$validated['office_unit'],
            'contact_number'=>$validated['contact_number'],
            'role_id'=>$validated['role_id'],
            'region'=>$validated['region'],
            'password'=>bcrypt($validated['password']),
            'photo'=>$fileName
        ]);

        return redirect()->route('users.users')->with('success', 'User Added Successfully');
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
    public function edit($id)
    {
         $user=User::findOrFail($id);
         return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            $user = User::findOrFail($id);

            $validated =$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email',
            'photo'=>'nullable|image',
            'office_unit'=>'required',
            'contact_number'=>'nullable',
            'role_id'=>'required',
            'region'=>'required',
        ]);

        $fileName=$user->photo;

        if($request->hasFile('photo')){
            if($user->photo && Storage::exists('public/uploads/'.$user->photo)){
                Storage::delete('public/uploads/'.$user->photo);
            }

            $file = $request->file('photo');
            $fileName=time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/uploads/', $fileName);
        }

        $user->update([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'office_unit'=>$validated['office_unit'],
            'contact_number'=>$validated['contact_number'],
            'role_id'=>$validated['role_id'],
            'region'=>$validated['region'],
            'photo'=>$fileName,
        ]);
        return redirect()->route('users.users')->with('success', 'User Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user=User::findOrFail($id);

        if($user->photo && Storage::exists('public/uploads/'.$user->photo)){
            Storage::delete('public/uploads/'.$user->photo);
        }
//$user->restore();
        $user->delete();
        return response()->json(['success'=>'User deleted successfully']);
    }
}
