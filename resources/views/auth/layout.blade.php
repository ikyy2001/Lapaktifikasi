<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>

    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Sweetalert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">

    <style>
        body {
            background-color: #fafafa !important;
            color: #000000 !important;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        .card {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #000000;
            z-index: 10;
        }

        .card.card-primary {
            border-top: none !important;
        }

        .text-primary, h4.text-primary {
            color: #000000 !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 24px !important;
        }

        label {
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.72rem !important;
            letter-spacing: 0.5px !important;
            color: #555555 !important;
        }

        .form-control {
            background: #ffffff !important;
            border: 1px solid #cccccc !important;
            border-radius: 8px !important;
            color: #000000 !important;
            height: 44px !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus {
            border-color: #000000 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-primary {
            background: #000000 !important;
            border: 1px solid #000000 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.85rem !important;
            letter-spacing: 0.5px !important;
            border-radius: 8px !important;
            height: 44px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .btn-primary:hover {
            background: transparent !important;
            color: #000000 !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-light {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.85rem !important;
            letter-spacing: 0.5px !important;
            border-radius: 8px !important;
            height: 44px !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
        }

        .btn-light:hover {
            background: #000000 !important;
            color: #ffffff !important;
            border-color: #000000 !important;
        }

        a, .text-small {
            color: #555555 !important;
            font-weight: 600 !important;
            transition: color 0.2s ease !important;
        }

        a:hover, .text-small:hover {
            color: #000000 !important;
            text-decoration: underline !important;
        }

        .alert-success {
            background-color: #ffffff !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
        }

        /* Checkbox overrides */
        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #000000 !important;
            border-color: #000000 !important;
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: 4px !important;
            border: 1px solid #000000 !important;
        }

        /* Rebrand logo styling inside auth layout */
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .auth-logo-icon {
            width: 54px;
            height: 54px;
            background: #000000;
            color: #ffffff;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 12px;
        }

        .auth-logo-text {
            font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #000000;
            text-transform: uppercase;
        }

        .auth-logo-text span {
            color: #666666;
        }
    </style>
</head>

<body>

    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="auth-logo">
                            <div class="auth-logo-icon">L</div>
                            <div class="auth-logo-text">LAPAK<span>TIFIKASI</span></div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{asset('assets/js/stisla.js')}}"></script>

    <!-- JS Libraies -->

    <!-- Template JS File -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

    <!-- Page Specific JS File -->
</body>

</html>