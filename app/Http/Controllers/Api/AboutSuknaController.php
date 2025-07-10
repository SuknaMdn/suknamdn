<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class AboutSuknaController extends Controller
{
    public function about(GeneralSettings $settings){
        try {
            return $settings->about ?? 'About section not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function term_and_condition(GeneralSettings $settings){
        try {
            return $settings->term_and_condition ?? 'Terms and conditions not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function privacy_policy(GeneralSettings $settings){
        try {
            return $settings->privacy_policy ?? 'Privacy policy not available';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }
    public function project_ownership(GeneralSettings $settings)
    {
        try {
            return '
            <div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px;">
                <h1 style="text-align: center; color: #2c3e50; margin-bottom: 30px;">شروط الاستخدام</h1>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">مقدمة:</h2>
                <p>تهدف هذه السياسة إلى تحديد الشروط والأحكام التي تحكم استخدامك لتطبيقنا لشراء العقارات (سكنة)، وتسهيل عملية شراء العقارات بشكل إلكتروني، يرجى منك قراءة هذه الشروط بعناية قبل استخدام التطبيق.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">المادة الأولى: القبول والالتزام</h2>
                <p>باستخدامك للتطبيق، فإنك تقر بقراءة هذه الشروط والأحكام، والموافقة عليها دون تحفظ.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">المادة الثانية: التعريفات</h2>
                <ul style="list-style-type: none; padding-left: 0;">
                    <li><strong>التطبيق:</strong> يعني تطبيق (سكنة) المتوفر على الأجهزة المحمولة.</li>
                    <li><strong>المستخدم:</strong> يعني أي شخص يقوم بتسجيل حساب واستخدام التطبيق.</li>
                    <li><strong>العقار:</strong> يعني أي عقار سكني أو تجاري معروض للبيع عبر التطبيق.</li>
                    <li><strong>المالك:</strong> يعني مالك العقار المعروض للبيع.</li>
                    <li><strong>الشركة:</strong> يعني الشركة المالكة والمطورة للتطبيق.</li>
                </ul>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">الخدمات:</h2>
                <ul>
                    <li>يوفر التطبيق منصة إلكترونية تتيح للمستخدمين تصفح العقارات المعروضة، والاطلاع على تفاصيلها، والتواصل مع المالكين، وإتمام عملية الشراء.</li>
                    <li>لا تضمن الشركة دقة المعلومات المقدمة من قبل المالكين قبل إتمام الصفقة، وتتحمل الشركة مسؤولية فحص هذه المعلومات قبل اتخاذ أي قرار شراء.</li>
                </ul>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">تسجيل الحساب:</h2>
                <ul>
                    <li>يتعين على المستخدم تسجيل حساب ليتمكن من استخدام جميع خدمات التطبيق.</li>
                    <li>يحمل المستخدم مسؤولية الحفاظ على سرية بيانات حسابه.</li>
                    <li>قد يتم جمع بعض البيانات لأغراض إحصائية أو تحسين الخدمة، دون كشف هوية المستخدم.</li>
                </ul>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">عرض العقارات:</h2>
                <p>تقوم الشركة بفحص الإعلانات العقارية قبل نشرها على التطبيق، وفقاً للأنظمة واللوائح ذات الصلة، ولكنها لا تتحمل مسؤولية عن أي معلومات مضللة أو غير دقيقة مقدمة من قبل المالك.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">عملية الشراء:</h2>
                <ul>
                    <li>يتم الاتفاق على تفاصيل عملية الشراء بعد دفع رسوم الخدمة من قبل المستخدم.</li>
                    <li>لا تتدخل الشركة في عملية التفاوض على السعر أو الشروط الأخرى.</li>
                    <li>توصي الشركة باستشارة محامٍ متخصص قبل إتمام أي صفقة عقارية.</li>
                </ul>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">الرسوم والمدفوعات:</h2>
                <ul>
                    <li>قد تتضمن بعض الخدمات المقدمة عبر التطبيق رسوماً إضافية، سيتم إعلام العميل أو المستخدم بها.</li>
                    <li>يتم تحديد طريقة الدفع من قبل الشركة.</li>
                </ul>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">الخصوصية:</h2>
                <p>تحترم الشركة خصوصية المستخدمين وتلتزم بحماية بياناتهم الشخصية وفقاً لسياسة الخصوصية الخاصة بها.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">إخلاء المسؤولية:</h2>
                <p>لا تتحمل الشركة أي مسؤولية عن أي خسائر أو أضرار مباشرة أو غير مباشرة ناتجة عن استخدام التطبيق.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">التعديلات:</h2>
                <p>تحتفظ منصة "سكنة" بحق تعديل هذه الشروط في أي وقت دون إشعار مسبق. وتُعد النسخة المنشورة على الموقع الرسمي هي النسخة المعتمدة ونافذة المفعول، كما يُعد استمرار استخدام التطبيق بعد التعديل موافقة ضمنية على الشروط الجديدة.</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">نطاق التطبيق:</h2>
                <p>تشمل هذه السياسة جميع خدمات تطبيق سكنة، بما في ذلك موقع الويب والتطبيق الجوال. وهي جزء ولا يتجزأ من سياسة الخصوصية وسياسة حقوق الملكية الفكرية</p>
                
                <h2 style="color: #3498db; margin-top: 25px; border-bottom: 1px solid #eee; padding-bottom: 5px;">القانون الحاكم:</h2>
                <p>تُفسر هذه الشروط وفقًا للأنظمة المعمول بها في المملكة العربية السعودية. وفي حال حدوث أي نزاعات ناشئة عن تطبيق هذه الشروط والأحكام فإن الجهات القضائية بمدينة الرياض، هي المختصة بنظر هذا النزاع.</p>
            </div>
            ';
        } catch (\Exception $e) {
            return '<div style="color: red; padding: 20px;">An error occurred: ' . $e->getMessage() . '</div>';
        }
    }

    /**
     * Get the unit value for unit reservation.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitValueForUnitReservation(GeneralSettings $settings)
    {
        return response()->json([
            'unit_value_for_unit_reservation' => $settings->serious_value_for_unit_reservation,
        ], 200);
    }

}
