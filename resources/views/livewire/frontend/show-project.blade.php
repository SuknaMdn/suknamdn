<div class="antialiased" dir="rtl">
    {{-- Custom Styles and Font --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap');
        
        body {
            background-color: #f0f2f5;
            font-family: 'Cairo', sans-serif;
        }
        .prose-custom { color: #4a5568; }
        .prose-custom h1, .prose-custom h2, .prose-custom h3 { color: #2d3748; }
        .prose-custom p { margin-top: 0.75em; margin-bottom: 0.75em; }
        .prose-custom ul > li::before { background-color: #4a5568; }
        .prose-custom ul { padding-right: 1.5em; padding-left: 0; }
        
        /* RTL spacing fixes */
        .space-x-reverse > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 1;
            margin-right: calc(0.75rem * var(--tw-space-x-reverse));
            margin-left: calc(0.75rem * calc(1 - var(--tw-space-x-reverse)));
        }
    </style>

    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <main class="w-full max-w-lg bg-gray-50 rounded-3xl shadow-2xl overflow-hidden my-8 transform transition-all duration-500">
            
            {{-- 1. Image Gallery Section --}}
            <div class="relative" x-data="{ 
                images: @js($project->images ?? []),
                activeImage: @js(isset($project->images) && count($project->images) > 0 ? Storage::url($project->images[0]) : 'https://placehold.co/600x400/e2e8f0/a0aec0?text=no+image')
            }" x-init="if (images.length === 0) activeImage = 'https://placehold.co/600x400/e2e8f0/a0aec0?text=no+image';">
                
                <img :src="activeImage" alt="{{ $project->title ?? 'مشروع عقاري' }}" class="w-full h-72 object-cover transition-opacity duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/75 to-transparent"></div>

                {{-- Image Thumbnails --}}
                <template x-if="images.length > 1">
                    <div class="absolute bottom-20 left-0 right-0 p-4 flex justify-center items-center gap-2">
                        <template x-for="(image, index) in images" :key="index">
                            <button @click="activeImage = @js(Storage::url('')) + '/' + image">
                                <img :src="@js(Storage::url('')) + '/' + image" class="h-12 w-12 rounded-lg object-cover border-2 transition-all" :class="activeImage.includes(image) ? 'border-white' : 'border-transparent opacity-60 hover:opacity-100'">
                            </button>
                        </template>
                    </div>
                </template>
                
                <div class="absolute bottom-0 start-0 p-6 text-white w-full flex justify-between items-end">
                    <div>
                        <h1 class="text-3xl font-bold leading-tight">{{ $project->title ?? 'مشروع عقاري' }}</h1>
                        <p class="text-sm opacity-90 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ms-1.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ optional($project->city)->name ?? 'غير محدد' }}, {{ optional($project->state)->name ?? 'غير محدد' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Details Section --}}
            <div class="p-6">
                
                {{-- Key Info Blocks --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center border-b pb-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">المساحة (م²)</p>
                        <p class="text-lg font-bold text-gray-800">{{ $project->area_range_from ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">النوع</p>
                        <p class="text-lg font-bold text-gray-800">{{ optional($project->propertyType)->name ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">طراز البناء</p>
                        <p class="text-lg font-bold text-gray-800">{{ $project->building_style ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">الغرض</p>
                        <p class="text-lg font-bold text-gray-800 capitalize">{{ $project->purpose === 'sale' ? 'بيع' : 'إيجار' }}</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex gap-4">
                    @if(!empty($project->video))
                    <a href="{{ $project->video }}" target="_blank" class="flex-1 text-center bg-gray-800 text-white font-bold py-3 px-4 rounded-xl hover:bg-gray-700 transition duration-300 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        <span>شاهد الفيديو</span>
                    </a>
                    @endif
                    @if(!empty($project->mediaPDF))
                    <a href="{{ Storage::url($project->mediaPDF) }}" target="_blank" class="flex-1 text-center bg-indigo-600 text-white font-bold py-3 px-4 rounded-xl hover:bg-indigo-500 transition duration-300 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                        <span>تحميل الكتيب</span>
                    </a>
                    @endif
                </div>

                {{-- Accordion for Details --}}
                <div class="mt-6 space-y-2">
                    @if(!empty($project->description))
                    <div class="border-t pt-4">
                        <div class="prose-custom max-w-none">
                            {!! $project->description !!}
                        </div>
                    </div>
                    @endif
                    
                    {{-- All Accordion Sections --}}
                    @php
                        $sections = [
                            'facilities' => ['title' => 'المرافق والخدمات', 'items' => $project->facilities ?? collect()],
                            'operationalServices' => ['title' => 'الخدمات التشغيلية', 'items' => $project->operationalServices ?? collect()],
                            'warranties' => ['title' => 'الضمانات', 'items' => $project->warranties ?? collect()],
                            'landmarks' => ['title' => 'معالم قريبة', 'items' => $project->landmarks ?? collect()],
                        ];
                    @endphp

                    @foreach($sections as $key => $section)
                        @if($section['items']->count() > 0)
                        <div x-data="{ open: false }" class="border-t pt-4">
                            <button @click="open = !open" class="w-full flex justify-between items-center text-start">
                                <h3 class="text-lg font-bold text-gray-800">{{ $section['title'] }}</h3>
                                <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4">
                                @if($key === 'landmarks')
                                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                                       @foreach($section['items'] as $item)
                                       <li>{{ $item->title ?? 'غير محدد' }} - <span class="font-semibold">{{ $item->distance ?? 'غير محدد' }}</span></li>
                                       @endforeach
                                    </ul>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                                        @foreach($section['items'] as $item)
                                        <div class="flex items-center space-x-3 space-x-reverse text-gray-700">
                                            @if(!empty($item->icon))
                                            <img src="{{ Storage::url($item->icon) }}" class="h-6 w-6 flex-shrink-0" alt="icon">
                                            @else
                                            <span class="h-6 w-6 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xs">✓</span>
                                            @endif
                                            <div>
                                                <span>{{ $item->title ?? 'عنصر' }}</span>
                                                @if(isset($item->content) && !empty($item->content))
                                                <p class="text-xs text-gray-500">{{ $item->content }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                
   {{-- Units Section --}}
                @if(isset($project->units) && $project->units->isNotEmpty())
                <div class="border-t pt-6 mt-6">
                    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 rounded-2xl p-8 text-white text-center">
                        {{-- Animated background elements --}}
                        <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full -translate-x-16 -translate-y-16 animate-pulse"></div>
                        <div class="absolute bottom-0 right-0 w-24 h-24 bg-white/10 rounded-full translate-x-12 translate-y-12 animate-pulse" style="animation-delay: 1s;"></div>
                        
                        {{-- Content --}}
                        <div class="relative z-10">
                            {{-- Icon --}}
                            <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl font-bold mb-2">🏠 حمّل التطبيق واكتشف {{ $project->units->count() }} وحدة مميزة!</h2>
                            <p class="text-white/90 mb-6 text-sm leading-relaxed">
                                شاهد التفاصيل الكاملة، الصور الحصرية، والجولات الافتراضية لجميع الوحدات السكنية 
                                <br>
                                <span class="font-semibold">✨ عروض حصرية متاحة فقط عبر التطبيق</span>
                            </p>
                            
                            {{-- App Store Buttons with enhanced styling --}}
                            <div class="flex justify-center gap-3 mb-4">
                                <a href="#" target="_blank" rel="noopener" class="transform hover:scale-105 transition-transform duration-200">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-12 rounded-lg shadow-lg">
                                </a>
                                <a href="#" target="_blank" rel="noopener" class="transform hover:scale-105 transition-transform duration-200">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-12 rounded-lg shadow-lg">
                                </a>
                            </div>
                            
                            {{-- Features preview --}}
                            <div class="flex justify-center items-center gap-6 text-xs text-white/80">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    مخططات ثلاثية الأبعاد
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    حجز فوري
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    جولات افتراضية
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Developer & License Info --}}
                <div class="border-t text-center mt-8 pt-4">
                    <p class="text-sm text-gray-500">المطور العقاري</p>
                    <p class="font-bold text-gray-700">{{ optional($project->developer)->name ?? 'غير معروف' }}</p>
                    @if(!empty($project->AdLicense))
                    <p class="text-xs text-gray-400 mt-2">رقم رخصة الإعلان: {{ $project->AdLicense }}</p>
                    @endif
                </div>

            </div>
        </main>
        
        {{-- App Download CTA --}}
        <footer class="w-full max-w-lg text-center pb-8">
            <h2 class="text-2xl font-bold text-gray-800">استكشف المزيد عبر تطبيقنا</h2>
            <p class="text-gray-600 mt-2 mb-6">احصل على التجربة الكاملة. حمّل تطبيقنا للعروض الحصرية والمميزات الفريدة.</p>
            <div class="flex justify-center gap-4">
                <a href="#" target="_blank" rel="noopener">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" class="h-12">
                </a>
                <a href="#" target="_blank" rel="noopener">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-12">
                </a>
            </div>
        </footer>

    </div>

    {{-- Make sure Alpine.js and collapse plugin are loaded --}}
    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</div>