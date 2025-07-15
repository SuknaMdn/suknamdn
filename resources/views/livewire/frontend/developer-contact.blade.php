<div>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(0, 0, 0, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 0, 0, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 0, 0, 0.02) 0%, transparent 50%);
        }
        
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .form-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 7px 14px !important;
            border-radius: 12px !important;
        }
        
        .form-input:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.02));
            animation: float 20s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: -5s;
        }
        
        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            left: 85%;
            animation-delay: -10s;
        }
        
        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 80%;
            left: 20%;
            animation-delay: -15s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(30px) rotate(240deg); }
        }
        
        .logo-container {
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .dashboard-card {
            backdrop-filter: blur(15px);
            direction: rtl
        }
        .metric-card {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.02) 100%);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        
        .metric-card:hover {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.08) 0%, rgba(0, 0, 0, 0.04) 100%);
        }
        
        .chart-animation {
            animation: drawChart 2s ease-in-out;
        }
        
        @keyframes drawChart {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
        
        .pulse-dot {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>

    <div class="min-h-screen bg-gray-50 bg-pattern px-md-20 py-14">
        <div>
            @if (session()->has('success'))
                <div class="p-4 text-green-700 bg-green-100 rounded-xl text-sm" wire:poll.100ms>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 text-red-700 bg-red-100 rounded-xl text-sm" wire:poll.100ms>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="flex flex-col lg:flex-row gap-8 max-w-6xl w-full">

                <!-- Registration Form Section -->
                <div class="lg:w-1/2 order-1 order-md-2" dir="rtl">
                    <!-- Main Card -->
                    <div class="glass-card w-full max-w-md mx-auto rounded-2xl p-8 shadow-xl">
                        <div class="text-center mb-8">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">إنشاء حساب مطور عقاري</h1>
                            <p class="text-gray-600 text-sm">انضم إلى شبكتنا المتميزة من المطورين العقاريين</p>
                        </div>

                        <!-- Form -->
                        <form class="space-y-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label for="fullName" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                                <input 
                                    type="text" 
                                    id="fullName" 
                                    name="fullName" 
                                    placeholder="الاسم الكامل"
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                                    required
                                >
                            </div>
                            
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    placeholder="البريد الإلكتروني"
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                                    required
                                >
                            </div>
                            
                            <!-- Phone -->
                            <div class="space-y-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    placeholder="رقم الهاتف"
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                                    required
                                >
                            </div>
                            
                            <!-- Company Name -->
                            <div class="space-y-2">
                                <label for="company" class="block text-sm font-medium text-gray-700">اسم الشركة</label>
                                <input 
                                    type="text" 
                                    id="company" 
                                    name="company" 
                                    placeholder="اسم الشركة"
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
                                    required
                                >
                            </div>
                            
                            <!-- Terms Checkbox -->
                            <div class="flex items-start space-x-3">
                                <input 
                                    type="checkbox" 
                                    id="terms" 
                                    name="terms" 
                                    class="mt-1 h-4 w-4 text-black focus:ring-black border-gray-300 rounded me-2"
                                    required
                                >
                                <label for="terms" class="text-sm text-gray-600 leading-5">
                                    أوافق على <a href="#" class="text-black hover:underline font-medium">شروط الخدمة</a> و <a href="#" class="text-black hover:underline font-medium">سياسة الخصوصية</a>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn-primary w-full py-3 px-4 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                تقديم الطلب
                            </button>
                        </form>
                        
                        <!-- Footer -->
                        <div class="mt-8 text-center">
                            <p class="text-sm text-gray-600">
                                هل لديك حساب بالفعل؟ 
                                <a href="#" class="text-black hover:underline font-medium">تسجيل الدخول</a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Footer Text -->
                    <div class="mt-8 text-center text-xs text-gray-500">
                        <p>© 2025 منصة العقارات. جميع الحقوق محفوظة.</p>
                    </div>
                </div>
                
                <!-- Dashboard Preview Section -->
                <div class="lg:w-1/2 order-1 order-me-1">
                    <div class="dashboard-card rounded-2xl p-6">
                        <!-- Metrics Cards -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="metric-card rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">المشاريع النشطة</p>
                                        <p class="text-xl font-bold text-gray-900">12</p>
                                    </div>
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="metric-card rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500">المبيعات</p>
                                        <p class="text-xl font-bold text-gray-900"> 2.4M رس</p>
                                    </div>
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sales Chart -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-medium text-gray-700">أداء المبيعات</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="pulse-dot w-2 h-2 bg-green-500 rounded-full ms-1"></span>
                                    <span class="text-xs text-gray-500">مباشر</span>
                                </div>
                            </div>
                            <div class="h-32 bg-gray-50 rounded-lg p-4">
                                <svg class="w-full h-full" viewBox="0 0 300 80">
                                    <defs>
                                        <linearGradient id="salesGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#000;stop-opacity:0.1" />
                                            <stop offset="100%" style="stop-color:#000;stop-opacity:0" />
                                        </linearGradient>
                                    </defs>
                                    
                                    <!-- Grid Lines -->
                                    <g opacity="0.3">
                                        <line x1="0" y1="20" x2="300" y2="20" stroke="#e5e7eb" stroke-width="1"/>
                                        <line x1="0" y1="40" x2="300" y2="40" stroke="#e5e7eb" stroke-width="1"/>
                                        <line x1="0" y1="60" x2="300" y2="60" stroke="#e5e7eb" stroke-width="1"/>
                                    </g>
                                    
                                    <!-- Chart Area -->
                                    <path d="M 0 60 L 50 45 L 100 35 L 150 25 L 200 30 L 250 20 L 300 15 L 300 80 L 0 80 Z" 
                                          fill="url(#salesGradient)" opacity="0.3"/>
                                    
                                    <!-- Chart Line -->
                                    <path d="M 0 60 L 50 45 L 100 35 L 150 25 L 200 30 L 250 20 L 300 15" 
                                          stroke="#000" stroke-width="2" fill="none" 
                                          stroke-dasharray="5,5" 
                                          class="chart-animation"/>
                                    
                                    <!-- Data Points -->
                                    <circle cx="50" cy="45" r="3" fill="#000"/>
                                    <circle cx="100" cy="35" r="3" fill="#000"/>
                                    <circle cx="150" cy="25" r="3" fill="#000"/>
                                    <circle cx="200" cy="30" r="3" fill="#000"/>
                                    <circle cx="250" cy="20" r="3" fill="#000"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Project Status -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-700">حالة المشاريع</h3>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center ms-2 me-0">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">مشروع ١</p>
                                        <p class="text-xs text-gray-500">قيد الإنشاء</p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-900">75%</p>
                                    <div class="w-16 h-1 bg-gray-200 rounded-full mt-1">
                                        <div class="w-3/4 h-full bg-amber-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center ms-2 me-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">مشروع ٢</p>
                                        <p class="text-xs text-gray-500">مكتمل</p>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-900">100%</p>
                                    <div class="w-16 h-1 bg-gray-200 rounded-full mt-1">
                                        <div class="w-full h-full bg-green-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>