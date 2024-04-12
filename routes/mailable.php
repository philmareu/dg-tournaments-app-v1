<?php

Route::get('/mailable/user/registration/open', function () {
    $registration = \DGTournaments\Models\Registration::find(1);

    return new \DGTournaments\Mail\User\RegistrationIsOpenMailable($registration);
});