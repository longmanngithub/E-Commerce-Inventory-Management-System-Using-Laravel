<h1>You have been invited!</h1>

<p>An account has been created for you. Please click the link below to set your password and activate your account.</p>
<a href="{{ route('invitation.accept', ['token' => $invitation->token]) }}">Set Your Password</a>
