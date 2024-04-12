<?php

namespace DGTournaments\Http\Controllers\Account;

use DGTournaments\Models\User\UserEmailNotificationType;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class EmailNotificationsController extends Controller
{
    protected $emailNotificationType;

    public function __construct(UserEmailNotificationType $emailNotificationType)
    {
        $this->emailNotificationType = $emailNotificationType;

        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.account.notifications')
            ->withUser(auth()->user())
            ->withEmailNotificationTypes($this->emailNotificationType->all());
    }

    public function update(Request $request)
    {
        if($request->has('email_notifications')) $request->user()->emailNotificationSettings()->sync($request->email_notifications);
        else $request->user()->emailNotificationSettings()->sync([]);

        return redirect('account/notifications')->with('success', 'Settings updated.');
    }
}
