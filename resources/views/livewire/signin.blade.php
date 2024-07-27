<div class="authincation h-100">
    @section("title","Giriş Yap.")
    <div class="card-body">
        <div class="text-center mb-3">
            <a href="{{route('login')}}"><img class="logo-auth" src="{{asset('xhtml/images/logo-renkli.svg')}}" alt="Giriş Yapın"></a>
        </div>
        <h4 class="text-center mb-4">Hesabınıza Giriş Yapın.</h4>
        <form wire:submit.prevent="submit">
            <div class="form-group mb-4">
                <label class="form-label" for="username">E-posta adresiniz</label>
                <input type="text" class="form-control" placeholder="E-posta adresinizi girin." id="email"
                       wire:model.defer="email">
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-sm-4 mb-3 position-relative">
                <label class="form-label" for="dlab-password">Password</label>
                <input type="password" id="dlab-password" class="form-control" placeholder="******"
                       wire:model.defer="password">
                @error('password')
                <div class="alert alert-danger">{{ $message }}</div> @enderror
{{--                <span class="show-pass eye">--}}
{{--                                        <i class="fa fa-eye-slash"></i>--}}
{{--                                        <i class="fa fa-eye"></i>--}}
{{--                                    </span>--}}
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
            </div>
        </form>
        <div class="new-account mt-3">


            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

    </div>
</div>
