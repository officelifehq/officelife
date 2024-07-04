<?php

namespace App\Services\Company\Adminland\Question;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Question;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroyQuestion extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'question_id' => 'required|integer|exists:questions,id',
        ];
    }

    /**
     * Destroy a question.
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $question = Question::where('company_id', $data['company_id'])
            ->findOrFail($data['question_id']);

        $question->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'question_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
