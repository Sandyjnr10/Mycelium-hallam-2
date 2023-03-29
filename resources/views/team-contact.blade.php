<x-guest-layout>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="font-weight-bold">
                            {{ __('Contacts for '.$team->name) }}
                        </h1>
                        @foreach ($contacts as $contact)
                            <ul>
                                <li>Handle: {{$contact->handle}}</li>
                                <li>Website: {{$contact->website}}</li>
                            </ul>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>