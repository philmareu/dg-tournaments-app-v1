<?php

namespace DGTournaments\Http\Controllers\Account;

use DGTournaments\Http\Requests\User\UpdateProfileRequest;
use DGTournaments\Models\Upload;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('pages.account.profile')
            ->with('user', auth()->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        if($request->hasFile('image'))
        {
            $file = $request->file('image');
            $path = $file->store('public');

            $upload = new Upload([
                'title' => $file->getClientOriginalName(),
                'filename' => $this->extractFilenameFromPath($path),
                'alt' => '',
                'mime' => $file->getClientMimeType(),
                'size' => $file->getClientSize()
            ]);

            $upload->user()->associate($request->user())->save();
            $request->user()->image()->associate($upload);
        }

        $request->user()->update($request->only('name', 'email', 'location'));

        return redirect()->route('profile.edit')->with('success', 'Profile Updated!');
    }

    private function extractFilenameFromPath($path)
    {
        $segments = explode('/', $path);

        return end($segments);
    }
}
