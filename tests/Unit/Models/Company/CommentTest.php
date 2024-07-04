<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
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
