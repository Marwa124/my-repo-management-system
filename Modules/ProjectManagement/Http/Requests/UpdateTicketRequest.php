<?php

namespace Modules\ProjectManagement\Http\Requests;

use App\Models\Ticket;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTicketRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ticket_edit');
    }

    public function rules()
    {
        return [
//            'ticket_code'   => [
//                'string',
//                'nullable',
//            ],
            'subject'       => [
                'required',
                'string',
                //'nullable',
                'unique:tickets,subject,'. request()->route('ticket')->id.',id,project_id,'.request()->project_id,
            ],
            'status'        => [
                'required',
                'string',
                //'nullable',
            ],
            'email'      => [
                'required',
                'email',
                //'nullable',
            ],
//            'reporter'      => [
//                'nullable',
//                'integer',
//                'min:-2147483648',
//                'max:2147483647',
//            ],
            'priority'      => [
                'required',
                'string',
                //'nullable',
            ],
            'body'      => [
                'required',
            ],
            'project_id'      => [
                'required',
            ],
//            'last_reply'    => [
//                'string',
//                'nullable',
//            ],
//            'permissions.*' => [
//                'integer',
//            ],
//            'permissions'   => [
//                'array',
//            ],
        ];
    }
}