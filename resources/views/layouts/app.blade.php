<!DOCTYPE html>
<html lang="en" class="w-mod-js wf-ambroisefrancoisstd-n4-active wf-sloopscriptthree-n4-active wf-active lenis" style="--_100svh: 643px;">
<head>
<meta charset="utf-8">
<title>Urban Company - Get Expert Professional Services at Home in Ahmedabad</title>
<meta content="width=device-width, initial-scale=1" name="viewport">   

<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/grid.css') }}" />
</head>
<body>

<div class="bg-white text-gray-900 font-sans w-full  relative">
  @include('layouts.header.header')      

  <main class="container mx-auto px-4 lg:px-8 mt-5 relative z-30">
    @yield('content')
  </main>    

  @include('layouts.footer.footer')
</div>

<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/documentReady.js') }}"></script>

@yield('customJs')

</body>
</html>