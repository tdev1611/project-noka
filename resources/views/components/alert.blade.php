@if (session('error'))
    <div class="alert alert-danger w-50" id="notification">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success w-50" id="notification">
        {!! session('success') !!}
    </div>
@endif
