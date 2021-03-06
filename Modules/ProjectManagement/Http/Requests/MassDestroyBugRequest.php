<?php

namespace Modules\ProjectManagement\Http\Requests;

use App\Models\Bug;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyBugRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('bug_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:bugs,id',
        ];
    }
}
