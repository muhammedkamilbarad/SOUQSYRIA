<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CategoryType;
use App\Enums\SyriaCities;
use App\Enums\Colors;
use App\Enums\FuelType;
use App\Enums\MarineType;
use App\Enums\MotorcycleType;
use App\Enums\CoolingType;
use App\Enums\TransmissionType;
use App\Enums\CarType;
use App\Enums\HouseType;

class AdvertisementRequest extends FormRequest
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
        //Add common rules for all categories
        $rules =  [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'required|in:' . implode(',', array_column(SyriaCities::cases(), 'name')),
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:rent,sale',
            'sale_details.is_swap' => 'boolean|required_if:type,sale|prohibited_if:type,rent',
            'rent_details.rental_period' => 'required_if:type,rent|prohibited_if:type,sale|in:daily,weekly,monthly,yearly',
            'images' => 'required|array|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'features' => 'array',
            'features.*' => 'exists:features,id'
        ];
        $category = CategoryType::tryFrom((int) $this->input('category_id'));
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
    private function getVehicleRules():array
    {
        return [
            'color' => 'required|in:' . implode(',', array_column(Colors::cases(), 'name')),
            'mileage' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1990|max:'.date('Y'),
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'fuel_type' => 'required|in:' . implode(',', array_column(FuelType::cases(), 'name')),
            'cylinders' => 'required_unless:fuel_type,ELECTRIC|prohibited_if:fuel_type,ELECTRIC|integer|min:1',
            'engine_capacity' => 'required_unless:fuel_type,ELECTRIC|prohibited_if:fuel_type,ELECTRIC|numeric|min:0',
            'horsepower' => 'required|integer|min:0',
            'transmission_type' => 'required|in:' . implode(',', array_column(TransmissionType::cases(), 'name')),
            'condition' => 'required|in:NEW,USED'
        ];
    }
    // House Rules
    private function getHouseRules():array
    {
        return [
            'house_type' => 'required|in:' . implode(',', array_column(HouseType::cases(), 'name')),
            'number_of_rooms' => 'required|integer|min:1',
            'number_of_bathrooms' => 'required|integer|min:1',
            'building_age' => 'required|integer|min:0',
            'square_meters' => 'required|numeric|min:0',
            'floor' => 'required|integer|min:0'
        ];
    }
    // Car Rules
    private function getCarRules():array
    {
        return [
            'car_type' => 'required|in:' . implode(',', array_column(CarType::cases(), 'name')),
            'seats' => 'required|integer|min:2|max:9',
            'doors' => 'required|integer|min:2|max:5',
            'seats_color' => 'required|in:' . implode(',', array_column(Colors::cases(), 'name'))
        ];
    }
    // Marine Rules
    private function getMarineRules():array
    {
        return [
            'marine_type' => 'required|in:' . implode(',', array_column(MarineType::cases(), 'name')),
            'length' => 'sometimes|numeric|min:0',
            'max_capacity' => 'sometimes|integer|min:1'
        ];
    }
    // Land Rules
    private function getLandRules():array
    {
        return [
            'square_meters' => 'required|numeric|min:0',
        ];
    }
    // Motorcycle Rules
    private function getMotorcycleRules():array
    {
        return [
            'cooling_type' => 'required|in:' . implode(',', array_column(CoolingType::cases(), 'name')),
            'motorcycle_type' => 'required|in:' . implode(',', array_column(MotorcycleType::cases(), 'name'))
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '.العنوان مطلوب',
            'title.string' => '.يجب أن يكون العنوان نصًا',
            'title.max' => '.لا يمكن أن يزيد العنوان عن 255 حرفًا',
            'description.required' => '.الوصف مطلوب',
            'description.string' => '.يجب أن يكون الوصف نصًا',
            'city.required' => '.المدينة مطلوبة',
            'city.in' => '.المدينة المحددة غير صحيحة',
            'location.required' => '.الموقع مطلوب',
            'location.string' => '.يجب أن يكون الموقع نصًا',
            'location.max' => '.لا يمكن أن يزيد الموقع عن 255 حرفًا',
            'category_id.required' => '.التصنيف مطلوب',
            'category_id.exists' => '.التصنيف المحدد غير صالح',
            'type.required' => '.نوع الإعلان مطلوب',
            'type.in' => '.نوع الإعلان يجب أن يكون إما إيجار أو بيع',
            'sale_details.is_swap.boolean' => '.يجب أن يكون حقل المقايضة صحيحًا أو خطأ',
            'sale_details.is_swap.required_if' => '.حقل المقايضة مطلوب عند اختيار النوع بيع',
            'sale_details.is_swap.prohibited_if' => '.حقل المقايضة غير مسموح به عند اختيار النوع إيجار',
            'price.required' => '.يجب إدخال حقل السعر',
            'price.numeric' => '.يجب أن يكون السعر رقمًا',
            'price.min' => '.يجب أن يكون السعر قيمة موجبة',
            'rent_details.rental_period.required_if' => '.يجب تحديد فترة الإيجار عند اختيار نوع الإيجار',
            'rent_details.rental_period.prohibited_if' => '.لا يمكنك تحديد فترة الإيجار إذا كان نوع الإعلان للبيع',
            'rent_details.rental_period.in' => '.يجب أن تكون فترة الإيجار إما يوميًا أو أسبوعيًا أو شهريًا أو سنويًا',
            'images.required' => '.الصور مطلوبة',
            'images.array' => '.يجب أن تكون الصور في شكل مصفوفة',
            'images.max' => '.لا يمكن تحميل أكثر من 5 صور',
            'images.*.required' => '.كل صورة مطلوبة',
            'images.*.image' => '.يجب أن تكون كل صورة من نوع صورة صالحة',
            'images.*.mimes' => '. jpeg أو png أو jpg يجب أن تكون الصورة بامتداد',
            'images.*.max' => '.الحد الأقصى لحجم الصورة هو 2048 كيلوبايت',
            'features.array' => '.يجب أن تكون الميزات في شكل مصفوفة',
            'features.*.exists' => '.إحدى الميزات المحددة غير صالحة',
            // Vehicle Rules
            'color.required' => '.اللون مطلوب',
            'color.in' => '.اللون المحدد غير صالح',
            'mileage.required' => '.المسافة المقطوعة مطلوبة',
            'mileage.numeric' => '.يجب أن تكون المسافة المقطوعة رقمًا',
            'mileage.min' => '.يجب أن تكون المسافة المقطوعة قيمة موجبة',
            'year.required' => '.سنة الصنع مطلوبة',
            'year.integer' => '.يجب أن تكون سنة الصنع رقمًا صحيحًا',
            'year.min' => '.يجب أن تكون سنة الصنع 1990 أو أحدث',
            'year.max' => '.لا يمكن أن تكون سنة الصنع في المستقبل',
            'brand_id.required' => '.الماركة مطلوبة',
            'brand_id.exists' => '.الماركة المحددة غير صالحة',
            'model_id.required' => '.الموديل مطلوب',
            'model_id.exists' => '.الموديل المحدد غير صالح',
            'fuel_type.required' => '.نوع الوقود مطلوب',
            'fuel_type.in' => '.نوع الوقود المحدد غير صالح',
            'cylinders.required_unless' => '.عدد الأسطوانات مطلوب ما لم يكن نوع الوقود كهربائي',
            'cylinders.prohibited_if' => '.لا يُسمح بتحديد عدد الأسطوانات إذا كان نوع الوقود كهربائي',
            'cylinders.integer' => '.يجب أن يكون عدد الأسطوانات رقمًا صحيحًا',
            'cylinders.min' => '.يجب أن يكون عدد الأسطوانات 1 على الأقل',
            'engine_capacity.required_unless' => '.سعة المحرك مطلوبة ما لم يكن نوع الوقود كهربائي',
            'engine_capacity.prohibited_if' => '.لا يُسمح بتحديد سعة المحرك إذا كان نوع الوقود كهربائي',
            'engine_capacity.numeric' => '.يجب أن تكون سعة المحرك رقمًا',
            'engine_capacity.min' => '.يجب أن تكون سعة المحرك قيمة موجبة',
            'horsepower.required' => '.قوة المحرك مطلوبة',
            'horsepower.integer' => '.يجب أن تكون قوة المحرك رقمًا صحيحًا',
            'horsepower.min' => '.يجب أن تكون قوة المحرك قيمة موجبة',
            'transmission_type.required' => '.نوع الغيار مطلوب',
            'transmission_type.in' => '.نوع الغيار المحدد غير صالح',
            'condition.required' => '.الحالة مطلوبة',
            'condition.in' => '.يجب أن تكون الحالة إما جديدة أو مستعملة',
            // House Rules
            'house_type.required' => '.نوع البيت مطلوب',
            'house_type.in' => '.نوع البيت المحدد غير صالح',
            'number_of_rooms.required' => '.عدد الغرف مطلوب',
            'number_of_rooms.integer' => '.يجب أن يكون عدد الغرف رقمًا صحيحًا',
            'number_of_rooms.min' => '.يجب أن يكون عدد الغرف 1 على الأقل',
            'number_of_bathrooms.required' => '.عدد الحمامات مطلوب',
            'number_of_bathrooms.integer' => '.يجب أن يكون عدد الحمامات رقمًا صحيحًا',
            'number_of_bathrooms.min' => '.يجب أن يكون عدد الحمامات 1 على الأقل',
            'building_age.required' => '.عمر البناء مطلوب',
            'building_age.integer' => '.يجب أن يكون عمر البناء رقمًا صحيحًا',
            'building_age.min' => '.يجب أن يكون عمر البناء قيمة موجبة',
            'square_meters.required' => '.المساحة مطلوبة',
            'square_meters.numeric' => '.يجب أن تكون المساحة رقمًا',
            'square_meters.min' => '.يجب أن تكون المساحة قيمة موجبة',
            'floor.required' => '.رقم الطابق مطلوب ',
            'floor.integer' => '.يجب أن يكون رقم الطابق رقمًا صحيحًا',
            'floor.min' => '.يجب أن يكون رقم الطابق قيمة موجبة',
            // Car Rules
            'car_type.required' => '.نوع السيارة مطلوب',
            'car_type.in' => '.نوع السيارة المحدد غير صالح',
            'seats.required' => '.عدد المقاعد مطلوب',
            'seats.integer' => '.يجب أن يكون عدد المقاعد رقمًا صحيحًا',
            'seats.min' => '.يجب أن يكون عدد المقاعد 2 على الأقل',
            'seats.max' => '.لا يمكن أن يزيد عدد المقاعد عن 9',
            'doors.required' => '.عدد الأبواب مطلوب',
            'doors.integer' => '.يجب أن يكون عدد الأبواب رقمًا صحيحًا',
            'doors.min' => '.يجب أن يكون عدد الأبواب 2 على الأقل',
            'doors.max' => '.لا يمكن أن يزيد عدد الأبواب عن 5',
            'seats_color.required' => '.لون المقاعد مطلوب',
            'seats_color.in' => '.لون المقاعد المحدد غير صالح',
            // Marine Rules
            'marine_type.required' => '.نوع القارب مطلوب',
            'marine_type.in' => '.نوع القارب المحدد غير صالح',
            'length.numeric' => '.يجب أن يكون الطول رقمًا',
            'length.min' => '.يجب أن يكون الطول قيمة موجبة',
            'max_capacity.integer' => '.يجب أن يكون الحد الأقصى للحمولة رقمًا صحيحًا',
            'max_capacity.min' => '.يجب أن يكون الحد الأقصى للحمولة 1 على الأقل',
            // Motorcycle Rules
            'cooling_type.required' => '.نوع التبريد مطلوب',
            'cooling_type.in' => '.نوع التبريد المحدد غير صالح',
            'motorcycle_type.required' => '.نوع الدراجة مطلوب',
            'motorcycle_type.in' => '.نوع الدراجة المحدد غير صالح',
        ];
    }
}
