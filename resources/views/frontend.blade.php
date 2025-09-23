<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Edwards - Law Firm HTML Template</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{assets('frontend/images/favicon.png')}}">
    <link rel="stylesheet" href="{{assets('frontend/css/vendor/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{assets('frontend/css/vendor/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{assets('frontend/css/vendor/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{assets('frontend/css/helper.css')}}">
    <link rel="stylesheet" href="{{assets('frontend/css/style.css')}}'">

    <script>window.baseUrl = '{{url('/')}}'</script>
    <script>window.uploadPath = '{{env('UPLOAD_PATH')}}'</script>
    <script>window.publicPath = '{{env('PUBLIC_PATH')}}'</script>
    <script>window.locale = '{{auth()->check() ? auth()->user()->locale : 'en'}}'</script>
    <script>
        function dd(...args) {
            args.forEach(arg => {
                console.log(arg);
            });
        }
    </script>
</head>

<body class="{{auth()->check() ? auth()->user()->theme : ''}}">
<div id="app">

</div>
<script src="{{assets('frontend/js/vendor/jquery-3.3.1.min.js')}}"></script>
<script src="{{assets('frontend/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{assets('frontend/js/main.js')}}"></script>
@vite('resources/js/web.js')
</body>
</html>
