<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company(): void
    {
        $comment = Comment::factory()->create([]);
        $this->assertTrue($comment->company()->exists());
    }

    /** @test */
    public function it_belongs_to_employee(): void
    {
        $comment = Comment::factory()->create([]);
        $this->assertTrue($comment->author()->exists());
    }
}
