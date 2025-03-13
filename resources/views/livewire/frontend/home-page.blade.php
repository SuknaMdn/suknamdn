<div>
    <nav class="bg-black fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center" dir="rtl">
            <div class="flex items-center">
                <img src="{{ asset('images/logo-text.png') }}" style="height: 50px;" alt="">
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-white hover:text-yellow-300 transition" style="margin-left: 24px !important;">المميزات</a>
                <a href="#projects" class="text-white hover:text-yellow-300 transition">عن التطبيق</a>
                {{-- <a href="#testimonials" class="text-white hover:text-yellow-300 transition">اراء العملاء</a> --}}
                <a href="#faq" class="text-white hover:text-yellow-300 transition">الاسئلة الشائعة</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-slate-800">
            <div class="px-4 py-3 space-y-4">
                <a href="#features" class="block text-white hover:text-yellow-300 transition">المميزات</a>
                <a href="#projects" class="block text-white hover:text-yellow-300 transition">عن التطبيق</a>
                {{-- <a href="#testimonials" class="block text-white hover:text-yellow-300 transition">اراء العملاء</a> --}}
                <a href="#faq" class="block text-white hover:text-yellow-300 transition">الاسئلة الشائعة</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section min-h-screen flex items-center pt-16" style="background-image: url('{{ asset('developer/14b1e4b87a9d602a87a037ddc6b7759b.jpg') }}');">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">اكتشف وحدتك المثالية في أرقى المشاريع العقارية بالمملكة</h1>
            <p class="text-xl text-gray-300 mb-10 max-w-3xl mx-auto">سكنة توفر لك الفرصة لتصفح أحدث المشاريع العقارية وتقديم طلب اهتمام بالوحدات التي تناسبك</p>
            <div class="flex flex-col md:flex-row justify-center gap-4 mb-16">
                <button class="gold-gradient text-slate-900 font-bold py-3 px-8 rounded-full flex items-center justify-center">
                    <i class="fab fa-apple mr-2 text-xl"></i> متجر التطبيقات
                </button>
                <button class="bg-white text-slate-900 font-bold py-3 px-8 rounded-full flex items-center justify-center">
                    <i class="fab fa-google-play mr-2 text-xl"></i> جوجل بلاي
                </button>
            </div>
            <a href="#features" class="scroll-down text-white inline-block">
                <i class="fas fa-chevron-down text-3xl"></i>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-16 text-slate-900" dir="rtl">لماذا تطبيق Sukna؟</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-lg p-8 shadow-lg transition duration-300 text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-building text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">مشاريع حصرية</h3>
                    <p class="text-gray-600">اكتشف مشاريع عقارية فاخرة غير متوفرة في أي مكان آخر</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-lg p-8 shadow-lg transition duration-300 text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">تجربة مستخدم ممتازة</h3>
                    <p class="text-gray-600">تصفح سلس وأدوات بحث متقدمة لإيجاد العقار المثالي بسرعة</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-lg p-8 shadow-lg transition duration-300 text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">فرص استثمارية</h3>
                    <p class="text-gray-600">شراكة مع أكبر المطورين في المملكة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- App Images Section -->
    <section id="app-images" class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <!-- <h2 class="text-3xl md:text-4xl font-bold text-center mb-16 text-slate-900">صور التطبيق</h2> -->
            <div class="grid grid-cols-1 md:grid-cols-6 gap-8">
                <!-- Image 1 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.10.29 PM.png') }}" alt="Sukna App Screenshot 1" class="w-full ">
                </div>
                <!-- Image 2 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.10.54 PM.png') }}" alt="Sukna App Screenshot 2" class="w-full ">
                </div>
                <!-- Image 3 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.11.01 PM.png') }}" alt="Sukna App Screenshot 3" class="w-full ">
                </div>
                <!-- Image 1 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.11.13 PM.png') }}" alt="Sukna App Screenshot 1" class="w-full ">
                </div>
                <!-- Image 2 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.19.28 PM.png') }}" alt="Sukna App Screenshot 2" class="w-full ">
                </div>
                <!-- Image 3 -->
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="{{ asset('developer/Screenshot 2025-03-11 at 10.19.44 PM.png') }}" alt="Sukna App Screenshot 3" class="w-full ">
                </div>
            </div>
        </div>
    </section>
    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-16 text-slate-900">كيف يعمل تطبيق سُكنا</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" dir="rtl">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-download text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">حمّل التطبيق</h3>
                    <p class="text-gray-600">حمّل تطبيق سُكنا من متجر التطبيقات</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-plus text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">أنشئ حسابًا</h3>
                    <p class="text-gray-600">سجّل بياناتك واهتماماتك العقارية</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">استكشف المشاريع</h3>
                    <p class="text-gray-600">تصفح المشاريع المميزة حسب الموقع أو النوع أو السعر</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-comments text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-slate-900">قدم طلب اهتمام</h3>
                    <p class="text-gray-600">تواصل مباشرةً مع مطوري المشاريع أو وكلاء المبيعات</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-16 text-slate-900">الأسئلة الشائعة</h2>

            <div class="max-w-3xl mx-auto" dir="rtl">
                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item border-b pb-6">
                        <button class="faq-question flex justify-between items-center w-full text-left">
                            <span class="text-xl font-semibold text-slate-900">هل التطبيق مجاني?</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="faq-answer mt-4 text-gray-600">
                            <p>نعم، تنزيل واستخدام تطبيق سكنة مجاني تمامًا. نكسب المال من خلال شراكاتنا مع المطورين وليس من مستخدمينا.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="faq-item border-b pb-6">
                        <button class="faq-question flex justify-between items-center w-full text-left">
                            <span class="text-xl font-semibold text-slate-900">كيف يتم اختيار المشاريع العقارية؟</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="faq-answer mt-4 text-gray-600 hidden">
                            <p>يقوم فريقنا من خبراء العقارات بفحص كل مشروع بدقة من حيث الجودة والموثوقية وإمكانات الاستثمار قبل عرضه على منصتنا. نضمن أن جميع المشاريع المعروضة تلبي معاييرنا الصارمة للعقارات المتميزة.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="faq-item border-b pb-6">
                        <button class="faq-question flex justify-between items-center w-full text-left">
                            <span class="text-xl font-semibold text-slate-900">هل يمكنني حجز العقار مباشرة عبر التطبيق؟</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>
                        <div class="faq-answer mt-4 text-gray-600 hidden">
                            <p>نعم، يمكنك إجراء الحجوزات الأولية للعديد من العقارات مباشرةً عبر التطبيق. أما المشاريع التي تتطلب توثيقًا أو إجراءات محددة، فنتواصل معك مباشرةً عبر فريق المبيعات.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Download CTA Section -->
    <section class="py-20 bg-black text-white">
        <div class="container mx-auto px-4 text-center">

            <div class="flex justify-center mb-10">
                <img src="{{ asset('images/logo-text.png') }}" alt="Sukna" class="max-h-20">
            </div>
            <h2 class="text-3xl md:text-4xl font-bold mb-6">ابدأ رحلتك العقارية المميزة الآن!</h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto">لا تفوت فرصًا عقارية استثنائية. حمل تطبيق سُكنا الآن</p>

            <div class="flex flex-col md:flex-row justify-center gap-6 mb-12">
                <button class="gold-gradient text-slate-900 font-bold py-4 px-8 rounded-full flex items-center justify-center">
                    <i class="fab fa-apple mr-2 text-xl"></i> تنزيل على متجر التطبيقات
                </button>
                <button class="bg-white text-slate-900 font-bold py-4 px-8 rounded-full flex items-center justify-center">
                    <i class="fab fa-google-play mr-2 text-xl"></i> احصل عليه من Google Play
                </button>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="gold-gradient text-gray-700 py-12" dir="rtl">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-xl font-bold text-black mb-4">Sukna</h3>
                    <p class="mb-4">بوابة للوصول إلى مشاريع العقارات الفاخرة وفرص الاستثمار.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-700 hover:text-black transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-700 hover:text-black transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-700 hover:text-black transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-700 hover:text-black transition"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="text-black font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="hover:text-black transition">المميزات</a></li>
                        <li><a href="#testimonials" class="hover:text-black transition">التقييمات</a></li>
                        <li><a href="#faq" class="hover:text-black transition">الأسئلة الشائعة</a></li>
                    </ul>
                </div>
                <!-- Legal -->
                <div>
                    <h4 class="text-black font-bold mb-4">القانونية</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-black transition">شروط الخدمة</a></li>
                        <li><a href="#" class="hover:text-black transition">سياسة الخصوصية</a></li>
                        <li><a href="#" class="hover:text-black transition">سياسة الكوكيز</a></li>
                        <li><a href="#" class="hover:text-black transition">إخلاء المسؤولية</a></li>
                    </ul>
                </div>
                <!-- Contact -->
                {{-- <div>
                    <h4 class="text-black font-bold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2">
                        <li><i class="fas fa-envelope mr-2"></i> info@Suknamdn.sa</li>
                        <li><i class="fas fa-phone mr-2"></i> +699 </li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 1234 شارع العقار، مدينة العقارات</li>
                    </ul>
                </div> --}}
            </div>
            <div class="border-t border-gray-700 mt-10 pt-6 text-center">
                <p>&copy; 2025 Sukna App. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // FAQ Accordions
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const icon = question.querySelector('i');

                // Toggle answer visibility
                answer.classList.toggle('hidden');

                // Toggle icon
                if (answer.classList.contains('hidden')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70, // Offset for fixed header
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
    </script>
</div>
