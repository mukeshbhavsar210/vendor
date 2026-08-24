<!DOCTYPE html>
<html lang="en" class="w-mod-js wf-ambroisefrancoisstd-n4-active wf-sloopscriptthree-n4-active wf-active lenis" style="--_100svh: 643px;">
<head>
<meta charset="utf-8">
<title>Urban Clap</title>  
<meta content="width=device-width, initial-scale=1" name="viewport">   
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" type="text/css">

</head>
<body class="body">

<div data-barba="wrapper" class="transition-wrapper">
    @include('layouts.header.header')      
    
    <main data-barba-namespace="home" data-barba="container" class="transition-container">
        @yield('content')
    </main>

    @include('layouts.footer.footer')
</div>

@yield('customJs')

</body>
</html>