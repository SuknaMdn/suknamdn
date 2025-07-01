<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderInstallment;
use App\Models\User;
use App\Notifications\ReceiptUploadedForReview;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InstallmentController extends Controller
{
    public function uploadReceipt(Request $request, OrderInstallment $installment)
    {
        try {
            //  التحقق من صلاحية المستخدم لهذا القسط
            if ($request->user()->id !== $installment->unitOrder->user_id) {
                return response()->json(['message' => 'غير مصرح لك بتنفيذ هذا الإجراء'], 403);
            }

            //  القسط مدفوع بالفعل
            if ($installment->status === 'paid') {
                return response()->json(['message' => 'هذا القسط مدفوع بالفعل'], 400);
            }

            //  التحقق من صحة الملف المرفوع
            $validated = $request->validate([
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            //  تخزين الملف
            $path = $request->file('receipt')->storePubliclyAs(
                'receipts/' . $installment->unitOrder->id,
                uniqid() . '.' . $request->file('receipt')->getClientOriginalExtension(),
                'public'
            );

            //  تحديث القسط
            $installment->update([
                'receipt_url' => $path,
                'status' => 'due',
            ]);

            //  إرسال إشعار للمطور
            $developer = $installment->unitOrder->unit->project->developer;
            $recipient = User::find($developer->user_id);
            if ($recipient) {
                $recipient->notify(new ReceiptUploadedForReview($installment));
            }

            return response()->json([
                'message' => 'تم رفع الإيصال بنجاح وجاري مراجعته.',
                'receipt_url' => Storage::url($path),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'خطأ في التحقق من صحة البيانات',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Upload Receipt Error', [
                'installment_id' => $installment->id,
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'حدث خطأ غير متوقع. الرجاء المحاولة لاحقًا.',
            ], 500);
        }
    }
}
