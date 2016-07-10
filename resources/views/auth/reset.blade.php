<!DOCTYPE html>
<html>
    <head>
        <link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}" />
        <meta charset="UTF-8" />
        <title>е-Glasanje</title>
        <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}"/>
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- Bootstrap Material Design -->
        <link rel="stylesheet" type="text/css" href="../../bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.css">
        <link rel="stylesheet" type="text/css" href="../../bower_components/bootstrap-material-design/dist/css/ripples.min.css">
    </head>
    <body id="generalbody">
        <div id="wrapper" class="well">
            <header id="header">
                <span id="coltitle">
                    ЕLEKTROTEHNIČKI FAKULTET
                </span>
                <span id="unititle">
                    UNIVERZITET U BEOGRADU
                </span>
            </header>
            <section id="content">
                <form id="login_form" action="{{ route('auth.reset') }}" method="post">
                    <h2>Promena lozinke</h2>
                    <div class="form-group label-floating">
                        <label for="email" class="control-label">Е-mail adresa:</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}" />
                    </div>
                    
                    <div class="form-group label-floating">
                        <label for="password" class="control-label">Lozinka:</label>
                        <input type="password" class="form-control" id="password" name="password" value="{{old('password')}}" />
                    </div>

                    <div class="form-group label-floating">
                        <label for="password_confirmation" class="control-label">Potvrda lozinke:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}" />
                    </div>

                    <div class="center">
                        @if(count($errors) > 0)
                            @foreach($errors->all() as $error)
                            <p class="text-danger">
                                {{ $error }}
                            </p>
                            @endforeach
                        @endif

                        @if(Session::has('fail'))
                            <p class="text-danger">
                                {{ Session::get('fail') }}
                            </p>
                        @endif
                        
                        @if(Session::has('success'))
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ Session::get('success') }}
                            </div>
                        @endif
                    </div>

                    <div class="center">
                        <button type="submit" class="btn btn-raised btn-danger btn-lg" name="submit">
                            Promenite
                        </button>
                        <input type="hidden" name="_token" value="{{ Session::token() }}" />
                        <input type="hidden" name="token" value="{{ $token }}">
                    </div>
                </form>
            </section>
        </div>
        <!-- SCRIPTS -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="../../bower_components/bootstrap-material-design/dist/js/ripples.min.js"></script>
        <script src="../../bower_components/bootstrap-material-design/dist/js/material.min.js"></script>
        <script>
          $(function () {
            $.material.init();
          });
        </script>
    </body>
</html>