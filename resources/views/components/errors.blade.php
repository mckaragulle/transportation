<div class="card-footer">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
        </button>
        @foreach ($errors->all() as $key => $error)
            {{ $error }}<br />
        @endforeach
    </div>
    @endif
</div>