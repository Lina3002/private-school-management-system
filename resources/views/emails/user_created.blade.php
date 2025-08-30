@component('mail::message')
# Welcome, {{ $user->first_name }}!

Your account has been created. Here are your login details:

- **Email:** {{ $user->email }}
- **Temporary Password:** {{ $tempPassword }}

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

Please log in and change your password immediately. If you have any issues, contact your school administrator.

Thanks,
{{ config('app.name') }}
@endcomponent
