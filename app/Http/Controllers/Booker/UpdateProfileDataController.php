<?php 

namespace App\Http\Controllers\Booker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
USE App\Http\Requests\UpdateProfileDataRequest;


class UpdateProfileDataController extends Controller
{
    public function edit(Request $request){
        $name = auth()->user()->name;
        $email = auth()->user()->email;
        
        return view('booker.profile.edit', compact('name', 'email'));
    }

    public function update(UpdateProfileDataRequest $request){
        $validatedData = $request->validated();

        if (!\Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->withErrors(['Unešena je neispravna lozinka']);
        }

        auth()->user()->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['new_password']),
        ]);

        return redirect()->back()->withSuccess('Podaci su uspješno izmjenjeni.');
    }
}
