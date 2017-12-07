You have a new contact form request:

Name: {{ $request->name }}
Email: {{ $request->email }}
{{--Phone: {{ $request->phone or "N/A" }}--}}
Message: {{ $request->message }}