<?php

namespace App\Services\Company\Adminland\Question;

use App\Exceptions\NotEnoughPermissionException;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Question;
use App\Services\BaseService;
use Carbon\Carbon;

class DeactivateQuestion extends BaseService
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
     * Deactivate a question.
     *
     *
     * @throws NotEnoughPermissionException
     */
    public function execute(array $data): Question
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $question = Question::where('company_id', $data['company_id'])
            ->findOrFail($data['question_id']);

        Question::where('id', $question->id)->update([
            'active' => false,
            'deactivated_at' => Carbon::now(),
        ]);

        $question->refresh();

        $this->log($data, $question);

        return $question->refresh();
    }

    /**
     * Create an audit log.
     */
    private function log(array $data, Question $question): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'question_deactivated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'question_id' => $question->id,
                'question_title' => $question->title,
            ]),
        ])->onQueue('low');
    }
}
