<?php

namespace App\Http\Requests\API\Quiz;

use App\Models\Quiz\Contest;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContestResultRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                Rule::exists((new User())->getTable(), 'id')->where(function (Builder $query) {
                    return $query->where('id', $this->request->get('user_id'));
                }),
            ],
            'quiz_contest_id' => [
                'required',
                Rule::exists((new Contest())->getTable(), 'id')->where(function (Builder $query) {
                    return $query->where('id', $this->request->get('quiz_contest_id'));
                }),
            ],
            'results' => 'required|array',
            'results.*' => 'required|array',
            'point' => 'required|numeric',
            'total_time' => 'required|numeric',
        ];
    }
}
