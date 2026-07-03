<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only Admin and Organizer can create/update events
        $user = $this->user();
        return $user && ($user->isAdmin() || $user->isOrganizer());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:10|max:200',
            'description' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'capacity' => 'required|integer|min:10|max:5000',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'status' => 'required|in:draft,published,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // 2MB limit
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề sự kiện không được để trống.',
            'title.min' => 'Tiêu đề sự kiện phải chứa ít nhất 10 ký tự.',
            'title.max' => 'Tiêu đề sự kiện tối đa 200 ký tự.',
            'description.required' => 'Mô tả sự kiện không được để trống.',
            'description.min' => 'Mô tả sự kiện phải chứa ít nhất 50 ký tự.',
            'location.required' => 'Địa điểm tổ chức không được để trống.',
            'location.max' => 'Địa điểm tổ chức tối đa 255 ký tự.',
            'start_time.required' => 'Thời gian bắt đầu không được để trống.',
            'start_time.date' => 'Thời gian bắt đầu không đúng định dạng ngày giờ.',
            'start_time.after' => 'Thời gian bắt đầu phải từ thời điểm hiện tại trở đi.',
            'end_time.required' => 'Thời gian kết thúc không được để trống.',
            'end_time.date' => 'Thời gian kết thúc không đúng định dạng ngày giờ.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'capacity.required' => 'Số lượng người tham gia tối đa không được để trống.',
            'capacity.integer' => 'Số lượng người tham gia tối đa phải là số nguyên.',
            'capacity.min' => 'Số lượng người tham gia tối thiểu là 10 người.',
            'capacity.max' => 'Số lượng người tham gia tối đa là 5000 người.',
            'category_id.required' => 'Vui lòng chọn danh mục sự kiện.',
            'category_id.exists' => 'Danh mục sự kiện được chọn không hợp lệ.',
            'tags.array' => 'Định dạng danh sách thẻ không hợp lệ.',
            'tags.*.exists' => 'Thẻ (tag) được chọn không tồn tại trong hệ thống.',
            'status.required' => 'Trạng thái sự kiện không được để trống.',
            'status.in' => 'Trạng thái sự kiện phải là nháp (draft), công khai (published) hoặc hủy bỏ (cancelled).',
            'image.image' => 'File tải lên phải là một hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, svg hoặc webp.',
            'image.max' => 'Dung lượng hình ảnh tối đa là 2MB.',
        ];
    }
}
