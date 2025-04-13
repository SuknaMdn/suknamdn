<div>
    <nav class="bg-black fixed w-full z-10">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center" dir="rtl">
            <div class="flex items-center">
                <img src="{{ asset('images/logo-text.png') }}" style="height: 50px;" alt="">
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-white hover:text-yellow-300 transition">الرئيسية</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section min-h-[40vh] flex items-center pt-16" style="background-image: url('{{ asset('developer/14b1e4b87a9d602a87a037ddc6b7759b.jpg') }}');">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">سياسة الخصوصية</h1>
            <p class="text-xl text-gray-300 mb-10 max-w-3xl mx-auto">نلتزم بحماية خصوصيتك وبياناتك الشخصية</p>
        </div>
    </section>

    <!-- Privacy Content Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto" dir="rtl">
                <div class="space-y-12">
                    <!-- Privacy Section 1 -->
                    <div class="bg-white rounded-lg p-8 shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-slate-900">جمع المعلومات</h2>
                        <p class="text-gray-600">
                            نقوم بجمع المعلومات التي تقدمها لنا عند استخدام تطبيق سُكنا، بما في ذلك:
                        </p>
                        <ul class="list-disc list-inside mt-4 text-gray-600 space-y-2">
                            <li>المعلومات الشخصية (الاسم، البريد الإلكتروني، رقم الهاتف)</li>
                            <li>تفضيلات البحث العقاري</li>
                            <li>معلومات الجهاز ونظام التشغيل</li>
                            <li>سجل التصفح والبحث في التطبيق</li>
                        </ul>
                    </div>

                    <!-- Privacy Section 2 -->
                    <div class="bg-white rounded-lg p-8 shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-slate-900">استخدام المعلومات</h2>
                        <p class="text-gray-600">
                            نستخدم المعلومات التي نجمعها للأغراض التالية:
                        </p>
                        <ul class="list-disc list-inside mt-4 text-gray-600 space-y-2">
                            <li>تحسين تجربة المستخدم وتخصيص المحتوى</li>
                            <li>التواصل معك بخصوص العروض والتحديثات</li>
                            <li>تحليل وتحسين خدماتنا</li>
                            <li>حماية أمن وسلامة التطبيق والمستخدمين</li>
                        </ul>
                    </div>

                    <!-- Privacy Section 3 -->
                    <div class="bg-white rounded-lg p-8 shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-slate-900">حماية المعلومات</h2>
                        <p class="text-gray-600">
                            نتخذ إجراءات أمنية مناسبة لحماية معلوماتك من الوصول غير المصرح به أو التعديل أو الإفصاح أو الإتلاف. نحن نستخدم تقنيات التشفير المتقدمة لحماية البيانات المنقولة وتخزينها بشكل آمن.
                        </p>
                    </div>

                    <!-- Privacy Section 4 -->
                    <div class="bg-white rounded-lg p-8 shadow-lg">
                        <h2 class="text-2xl font-bold mb-6 text-slate-900">مشاركة المعلومات</h2>
                        <p class="text-gray-600">
                            لا نقوم ببيع أو تأجير أو مشاركة معلوماتك الشخصية مع أطراف ثالثة إلا في الحالات التالية:
                        </p>
                        <ul class="list-disc list-inside mt-4 text-gray-600 space-y-2">
                            <li>بموافقتك الصريحة</li>
                            <li>عندما يكون ذلك مطلوباً قانونياً</li>
                            <li>لحماية حقوقنا القانونية</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="gold-gradient text-gray-700 py-12" dir="rtl">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-xl font-bold text-black mb-4">Sukna</h3>
                    <p class="mb-4">بوابة للوصول إلى مشاريع العقارات الفاخرة وفرص الاستثمار.</p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="text-black font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="hover:text-black transition">الرئيسية</a></li>
                        <li><a href="#" class="hover:text-black transition">شروط الخدمة</a></li>
                        <li><a href="#" class="hover:text-black transition">سياسة الخصوصية</a></li>
                    </ul>
                </div>
                <!-- Contact -->
                <div>
                    <h4 class="text-black font-bold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2">
                        <li><i class="fas fa-envelope mr-2"></i> info@sukna.sa</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-10 pt-6 text-center">
                <p>&copy; 2025 Sukna App. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
</div>
