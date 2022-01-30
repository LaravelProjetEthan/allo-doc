@if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show position-absolute" role="alert">
        <strong>{{ $message }}</strong>
        <button style="position: absolute; right: 0px; top: 0px" type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show position-absolute" role="alert">
        <strong>{{ $message }}</strong>
        <button style="position: absolute; right: 0px; top: 0px" type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible fade show position-absolute" role="alert">
        <strong>{{ $message }}</strong>
        <button style="position: absolute; right: 0px; top: 0px" type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-dismissible fade show position-absolute" role="alert">
        <strong>{{ $message }}</strong>
        <button style="position: absolute; right: 0px; top: 0px" type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-absolute" role="alert">
        <button style="position: absolute; right: 0px; top: 0px" type="button" class="close btn" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
