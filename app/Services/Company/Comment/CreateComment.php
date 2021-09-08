<?php

namespace App\Services\Company\Comment;

use App\Services\BaseService;
use App\Models\Company\Comment;
use App\Models\Company\Employee;

class CreateComment extends BaseService
{
    protected array $data;
    protected Comment $comment;
    protected Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'description' => 'required|string|max:65535',
            'object_id' => 'required|integer',
            'object_type' => 'required|string',
        ];
    }

    /**
     * Create a comment linked to the given object.
     *
     * @param array $data
     * @return Comment
     */
    public function execute(array $data): Comment
    {
        $this->data = $data;
        $this->validate();
        $this->createComment();
        return $this->comment;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->employee = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['author_id']);
    }

    private function createComment(): void
    {
        $this->comment = Comment::create([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'author_name' => $this->employee->name,
            'description' => $this->data['description'],
        ]);
    }
}
