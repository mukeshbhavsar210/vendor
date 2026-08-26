  <nav class="w-full bg-white text-gray-900 top-0 left-0 right-0 z-50 transition-transform ">
    <div class="container mx-auto max-w-screen-2xl px-4 py-1 flex items-center justify-between">
      <a class="flex items-center shrink-0" href="/">      
        <img src="front-assets/images/logo.png" alt="Urban Clap" width="80" class="h-16 w-auto object-contain">
      </a>
      <div class="hidden xl:flex flex-1 justify-center items-center space-x-4 lg:space-x-6 px-4">
          <a class="flex items-center text-[15px] font-medium hover:text-gray-900" href="/vastu-calculator">Vastu Calculator</a>
          </div>
          <div class="hidden xl:flex items-center space-x-5 shrink-0"><button class="flex items-center gap-2 text-[15px] font-semibold hover:text-blue-600 transition-colors" title="Scan Builder Logo"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-line h-5 w-5"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><path d="M7 12h10"></path></svg>Scan Logo</button><button title="AI Search" class="relative flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-[#f67474] to-[#55a3e4] text-white text-sm font-semibold shadow hover:opacity-90 transition-all hover:scale-105 active:scale-95 mt-1 lg:mt-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic h-4 w-4"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" x2="12" y1="19" y2="22"></line></svg><span class="pr-1">AI Search</span><span class="absolute -top-2 -right-2.5 rotate-[18deg] bg-white text-[#f67474] text-[9px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded-sm shadow-md border border-gray-100">Beta</span></button><a class="flex items-center gap-2 p-2 text-gray-700 hover:text-red-500 transition-colors" href="/wishlist"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart h-6 w-6"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg><span class="font-medium">Wishlist</span></a></div><div class="xl:hidden flex items-center gap-3"><button title="AI Search" class="flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-[#f67474] to-[#55a3e4] text-white shadow-md hover:opacity-90 transition-all active:scale-95"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" x2="12" y1="19" y2="22"></line></svg></button><button class="p-2 border rounded-lg text-gray-700 hover:bg-gray-50"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-line"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><path d="M7 12h10"></path></svg></button><button class="p-2 border rounded-lg hover:bg-gray-50"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu"><line x1="4" x2="20" y1="12" y2="12"></line><line x1="4" x2="20" y1="6" y2="6"></line><line x1="4" x2="20" y1="18" y2="18"></line></svg></button>
    </div>
    </div>
  </nav>

  @if (Route::has('login'))
      <nav class="flex items-center justify-end gap-4">
          @auth
              <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Dashboard
              </a>             
          @else
              <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal" > 
                Log in
              </a>
              @if (Route::has('register'))
                  <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                      Register
                  </a>
              @endif
          @endauth
      </nav>
  @endif
