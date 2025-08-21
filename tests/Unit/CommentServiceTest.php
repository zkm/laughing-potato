<?php

namespace Sweetwater\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sweetwater\Services\CommentService;
use Sweetwater\Models\Comment;

/**
 * Unit tests for CommentService
 */
class CommentServiceTest extends TestCase
{
    private $commentService;
    private $mockCommentModel;

    protected function setUp(): void
    {
        $this->mockCommentModel = $this->createMock(Comment::class);
        $this->commentService = new CommentService($this->mockCommentModel);
    }

    public function testCategorizeCommentsWithCandyKeyword(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'I love candy'],
            ['orderid' => 2, 'comments' => 'Please send more candy'],
            ['orderid' => 3, 'comments' => 'General comment']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertArrayHasKey('candy', $result);
        $this->assertCount(2, $result['candy']);
        $this->assertContains('I love candy', $result['candy']);
        $this->assertContains('Please send more candy', $result['candy']);
    }

    public function testCategorizeCommentsWithCallMeKeyword(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'Please call me'],
            ['orderid' => 2, 'comments' => 'Don\'t call me'],
            ['orderid' => 3, 'comments' => 'General comment']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertArrayHasKey('call_me', $result);
        $this->assertCount(2, $result['call_me']);
        $this->assertContains('Please call me', $result['call_me']);
        $this->assertContains('Don\'t call me', $result['call_me']);
    }

    public function testCategorizeCommentsWithReferredKeyword(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'John referred me'],
            ['orderid' => 2, 'comments' => 'I was referred by Sarah'],
            ['orderid' => 3, 'comments' => 'General comment']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertArrayHasKey('referred', $result);
        $this->assertCount(2, $result['referred']);
        $this->assertContains('John referred me', $result['referred']);
        $this->assertContains('I was referred by Sarah', $result['referred']);
    }

    public function testCategorizeCommentsWithSignatureKeyword(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'No signature required'],
            ['orderid' => 2, 'comments' => 'Signature needed upon delivery'],
            ['orderid' => 3, 'comments' => 'General comment']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertArrayHasKey('signature', $result);
        $this->assertCount(2, $result['signature']);
        $this->assertContains('No signature required', $result['signature']);
        $this->assertContains('Signature needed upon delivery', $result['signature']);
    }

    public function testCategorizeCommentsWithMiscellaneous(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'Leave at door'],
            ['orderid' => 2, 'comments' => 'Thank you for your service'],
            ['orderid' => 3, 'comments' => '']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertArrayHasKey('misc', $result);
        $this->assertCount(3, $result['misc']);
        $this->assertContains('Leave at door', $result['misc']);
        $this->assertContains('Thank you for your service', $result['misc']);
        $this->assertContains('', $result['misc']);
    }

    public function testGetCommentStats(): void
    {
        // Arrange
        $mockComments = [
            ['orderid' => 1, 'comments' => 'I love candy'],
            ['orderid' => 2, 'comments' => 'Please call me'],
            ['orderid' => 3, 'comments' => 'General comment']
        ];

        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn($mockComments);

        // Act
        $result = $this->commentService->getCommentStats();

        // Assert
        $this->assertEquals(3, $result['total']);
        $this->assertArrayHasKey('by_category', $result);
        $this->assertEquals(1, $result['by_category']['candy']);
        $this->assertEquals(1, $result['by_category']['call_me']);
        $this->assertEquals(1, $result['by_category']['misc']);
    }

    public function testCategorizeCommentsWithEmptyResult(): void
    {
        // Arrange
        $this->mockCommentModel
            ->expects($this->once())
            ->method('getAllComments')
            ->willReturn([]);

        // Act
        $result = $this->commentService->categorizeComments();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('candy', $result);
        $this->assertArrayHasKey('call_me', $result);
        $this->assertArrayHasKey('referred', $result);
        $this->assertArrayHasKey('signature', $result);
        $this->assertArrayHasKey('misc', $result);
        $this->assertEmpty($result['candy']);
        $this->assertEmpty($result['call_me']);
        $this->assertEmpty($result['referred']);
        $this->assertEmpty($result['signature']);
        $this->assertEmpty($result['misc']);
    }
}
