@component('mail::message')
# Welcome to {{ $school->name }}

Dear {{ $student->first_name }} {{ $student->last_name }},

Your student account has been successfully created at {{ $school->name }}. Here are your temporary login credentials:

**Email:** {{ $student->email }}  
**Temporary Password:** {{ $tempPassword }}

## Important Information:
- **Massar Code:** {{ $student->massar_code }}
- **Class:** {{ $student->classroom ? $student->classroom->name : 'Not yet assigned' }}
- **Transportation:** {{ $student->driving_service ? 'School Bus' : 'No Bus (Walking/Private)' }}

## Next Steps:
1. **Login to your account** using the credentials above
2. **Change your password** immediately after your first login for security
3. **Complete your profile** with any missing information
4. **Check your class schedule** and assignments

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

## Security Notice:
- Please change your password immediately after logging in
- Keep your login credentials secure and private
- Contact the school administration if you have any issues

If you have any questions or need assistance, please contact your school administration.

Best regards,  
{{ $school->name }} Administration

---
*This is an automated message. Please do not reply to this email.*
@endcomponent


