<p>You have a new contact form request:</p>

<p><strong>Name:</strong> {{ $request->name }}</p>
<p><strong>Email:</strong> {{ $request->email }}</p>
{{--<p><strong>Phone:</strong> {{ $request->phone or "N/A" }}</p>--}}
<p><strong>Message:</strong> {{ $request->message }}</p>