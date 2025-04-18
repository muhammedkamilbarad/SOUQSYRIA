<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Advertisement;
use App\Enums\CategoryType;
use App\Enums\SyriaCities;
use App\Enums\Colors;
use App\Enums\FuelType;
use App\Enums\MarineType;
use App\Enums\MotorcycleType;
use App\Enums\CoolingType;
use App\Enums\TransmissionType;

class AdvertisementUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        $advertisement = Advertisement::findOrFail($id);
        $rules = [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'city' => 'sometimes|in:' . implode(',', array_column(SyriaCities::cases(), 'name')),
            'location' => 'sometimes|string|max:255',
            'type' => 'prohibited',
            'category_id' => 'prohibited',
            'sale_details.is_swap' => $advertisement->type === 'rent' ? 'prohibited' : 'sometimes|boolean',
            'rent_details.rental_period' => $advertisement->type === 'sale' ? 'prohibited' : 'sometimes|in:daily,weekly,monthly,yearly',
            // 'images' => 'sometimes|array|max:5',
            // 'images.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'new_images' => 'sometimes|array|max:10',
            'new_images.*' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'deleted_images_ids' => 'sometimes|array|max:10',
            'deleted_images_ids.*' => 'sometimes|exists:images,id',
            'features' => 'array',
            'features.*' => 'exists:features,id'
        ];
        $category = CategoryType::tryFrom((int) $advertisement->category_id);
        //Add category-specific rules
        $specificRules = match($category)
        {
            CategoryType::LAND => $this->getLandRules(),
            CategoryType::HOUSE => $this->getHouseRules(),
            CategoryType::CAR => array_merge($this->getVehicleRules(), $this->getCarRules()),
            CategoryType::MARINE => array_merge($this->getVehicleRules(), $this->getMarineRules()),
            CategoryType::MOTORCYCLE => array_merge($this->getVehicleRules(), $this->getMotorcycleRules()),
            default => []
        };
        return array_merge($rules, $specificRules);
    }
    // Vehicle Rules
    private function getVehicleRules(): array
    {
        return [
            'color' => 'prohibited',
            'mileage' => 'prohibited',
            'year' => 'prohibited',
            'fuel_type' => 'prohibited',
            'cylinders' => 'prohibited',
            'engine_capacity' => 'prohibited',
            'horsepower' => 'prohibited',
            'transmission_type' => 'prohibited',
            'condition' => 'prohibited',
            'brand_id' => 'prohibited',
            'model_id' => 'prohibited',
        ];
    }
    // House Rules
    private function getHouseRules():array
    {
        return [
            'house_type' => 'prohibited',
            'number_of_rooms' => 'prohibited',
            'number_of_bathrooms' => 'prohibited',
            'building_age' => 'prohibited',
            'square_meters' => 'prohibited',
            'floor' => 'prohibited'
        ];
    }
    // Car Rules
    private function getCarRules():array
    {
        return [
            'car_type' => 'prohibited',
            'seats' => 'prohibited',
            'doors' => 'prohibited',
            'seats_color' => 'prohibited'
        ];
    }
    // Marine Rules
    private function getMarineRules():array
    {
        return [
            'marine_type' => 'prohibited',
            'length' => 'prohibited',
            'max_capacity' => 'prohibited'
        ];
    }
    // Land Rules
    private function getLandRules():array
    {
        return [
            'square_meters' => 'prohibited',
        ];
    }
    // Motorcycle Rules
    private function getMotorcycleRules():array
    {
        return [
            'cooling_type' => 'prohibited',
            'motorcycle_type' => 'prohibited'
        ];
    }

    public function messages(): array
{
    return [
        'title.string' => '.يجب أن يكون العنوان نصًا',
        'title.max' => '.لا يمكن أن يزيد العنوان عن 255 حرفًا',
        'description.string' => '.يجب أن يكون الوصف نصًا',
        'price.numeric' => '.يجب أن يكون السعر رقمًا',
        'price.min' => '.يجب أن يكون السعر قيمة موجبة',
        'city.in' => '.المدينة المحددة غير صحيحة',
        'location.string' => '.يجب أن يكون الموقع نصًا',
        'location.max' => '.لا يمكن أن يزيد الموقع عن 255 حرفًا',
        'type.prohibited' => '.لا يمكنك تعديل النوع',
        'category_id.prohibited' => '.لا يمكنك تعديل الفئة',
        'sale_details.is_swap.boolean' => '.يجب أن يكون حقل المقايضة صحيحًا أو خاطئًا',
        'sale_details.is_swap.prohibited' => '.لا يمكنك تحديد حقل المقايضة إذا كان نوع الإعلان للإيجار',
        'rent_details.rental_period.prohibited' => '.لا يمكنك تحديد فترة الإيجار إذا كان نوع الإعلان للبيع',
        'rent_details.rental_period.in' => '.يجب أن تكون فترة الإيجار إما يوميًا أو أسبوعيًا أو شهريًا أو سنويًا',
        'images.array' => '.يجب أن تكون الصور في شكل قائمة',
        'images.max' => '.لا يمكنك تحميل أكثر من 5 صور',
        'images.*.image' => '.يجب أن تكون الصورة بصيغة صحيحة',
        'images.*.mimes' => '.jpeg أو png أو jpg يجب أن تكون الصورة بصيغة',
        'images.*.max' => '.يجب ألا يتجاوز حجم الصورة 2 ميغابايت',
        'features.array' => '.يجب أن تكون الميزات في شكل قائمة',
        'features.*.exists' => '.الميزة المحددة غير موجودة في قاعدة البيانات',
        // Vehicle Rules
        'color.prohibited' => '.لا يمكنك تعديل اللون',
        'mileage.prohibited' => '.لا يمكنك تعديل عدد الكيلومترات',
        'year.prohibited' => '.لا يمكنك تعديل سنة الصنع',
        'fuel_type.prohibited' => '.لا يمكنك تعديل نوع الوقود',
        'cylinders.prohibited' => '.لا يمكنك تعديل عدد الأسطوانات',
        'engine_capacity.prohibited' => '.لا يمكنك تعديل سعة المحرك',
        'horsepower.prohibited' => '.لا يمكنك تعديل قوة المحرك',
        'transmission_type.prohibited' => '.لا يمكنك تعديل نوع ناقل الحركة',
        'condition.prohibited' => '.لا يمكنك تعديل الحالة',
        'brand_id.prohibited' => '.لا يمكنك تعديل العلامة التجارية',
        'model_id.prohibited' => '.لا يمكنك تعديل الموديل',
        // House Rules
        'number_of_rooms.prohibited' => '.لا يمكنك تعديل عدد الغرف',
        'number_of_bathrooms.prohibited' => '.لا يمكنك تعديل عدد الحمامات',
        'building_age.prohibited' => '.لا يمكنك تعديل عمر المبنى',
        'square_meters.prohibited' => '.لا يمكنك تعديل المساحة',
        'floor.prohibited' => '.لا يمكنك تعديل رقم الطابق',
        // Car Rules
        'seats.prohibited' => '.لا يمكنك تعديل عدد المقاعد',
        'doors.prohibited' => '.لا يمكنك تعديل عدد الأبواب',
        'seats_color.prohibited' => '.لا يمكنك تعديل لون المقاعد',
        // Marine Rules
        'marine_type.prohibited' => '.لا يمكنك تعديل نوع المركب البحري',
        'length.prohibited' => '.لا يمكنك تعديل الطول',
        'max_capacity.prohibited' => '.لا يمكنك تعديل السعة القصوى',
        // Land Rules
        'square_meters.prohibited' => '.لا يمكنك تعديل المساحة',
        // Motorcycle Rules
        'cooling_type.prohibited' => '.لا يمكنك تعديل نوع التبريد',
        'motorcycle_type.prohibited' => '.لا يمكنك تعديل نوع الدراجة النارية',
    ];
}

}
