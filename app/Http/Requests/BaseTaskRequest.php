<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            // صيغة الكتابة: 'اسم_الحقل.اسم_القاعدة' => 'الرسالة'

            // رسائل خاصة بحقل العنوان
            'title.required' => 'يرجى إدخال عنوان للمهمة.',
            'title.string' => 'يجب أن يكون العنوان نصاً.',
            'title.max' => 'العنوان طويل جداً، يجب ألا يتجاوز 50 حرفاً.',

            // رسائل خاصة بحقل الوصف
            'description.string' => 'الوصف يجب أن يكون نصاً.',

            // رسائل خاصة بحقل الأولوية
            'priority.required' => 'تحديد الأولوية أمر ضروري.',
            'priority.integer' => 'الأولوية يجب أن تكون رقماً صحيحاً.',
            'priority.min' => 'أقل قيمة للأولوية هي 1.',
            'priority.max' => 'أعلى قيمة للأولوية هي 5.',
        ];
    }
}
