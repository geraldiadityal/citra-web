<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();
            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                    <a class="inline-block border border-blue-700 bg-blue-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-blue-800 focus:outline-none focus:shadow-outline" 
                    href="' . route('dashboard.user.show', $item->id) . '">
                    Show
                    </a>
                    <a class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline" 
                        href="' . route('dashboard.user.edit', $item->id) . '">
                        Edit
                    </a>
                    <form class="inline-block" action="' . route('dashboard.user.destroy', $item->id) . '" method="POST">
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                        Hapus
                    </button>
                        ' . method_field('delete') . csrf_field() . '
                    </form>';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.dashboard.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.dashboard.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->validate($request, [
            'password' => ['required', 'confirmed', new Password],
            'profile_photo_path' => 'required',
        ]);
        $image = $request->file('profile_photo_path')->store('assets/user', 'public');

        User::create([
            'profile_photo_path' => $image,
            'name' => $request->name,
            'email' => $request->email,
            'roles' => $request->roles,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('pages.dashboard.user.show', [
            'item' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('pages.dashboard.user.edit', [
            'item' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if ($request->password == "") {

            if ($request->file('profile_photo_path') == '') {

                $user = User::findOrFail($user->id);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'company_name' => $request->company_name,
                    'phone_number' => $request->phone_number,
                ]);
            } else {
                //delete old img
                Storage::disk('local')->delete($user->profile_photo_path);

                //upload new img
                $image = $request->file('profile_photo_path')->store('assets/user', 'public');


                $user = User::findOrFail($user->id);
                $user->update([
                    'profile_photo_path' => $image,
                    'name' => $request->name,
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'company_name' => $request->company_name,
                    'phone_number' => $request->phone_number,
                ]);
            }
        } else {
            $this->validate($request, [
                'password' => ['required', 'confirmed', new Password],
            ]);
            if ($request->file('profile_photo_path') == '') {

                $user = User::findOrFail($user->id);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'company_name' => $request->company_name,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                ]);
            } else {
                //delete old img
                Storage::disk('local')->delete($user->profile_photo_path);

                //upload new img
                $image = $request->file('profile_photo_path')->store('assets/user', 'public');

                $user = User::findOrFail($user->id);
                $user->update([
                    'profile_photo_path' => $image,
                    'name' => $request->name,
                    'email' => $request->email,
                    'roles' => $request->roles,
                    'company_name' => $request->company_name,
                    'phone_number' => $request->phone_number,
                    'password' => Hash::make($request->password),
                ]);
            }
        }
        return redirect()->route('dashboard.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);
        Storage::disk('local')->delete($user->profile_photo_path);
        $user->delete();
        return redirect()->route('dashboard.user.index');
    }
}
