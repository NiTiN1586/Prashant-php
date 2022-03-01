<?php

declare(strict_types=1);

namespace App\Tests\Unit\Integration\Application\IssueTracker\Jira\Converter;

use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Converter\JiraDescriptionConvert;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\ContentField;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Description;
use PHPUnit\Framework\TestCase;

class JiraDescriptionConverterTest extends TestCase
{
    public function testEmptyDescription(): void
    {
        $descriptionConverter = new JiraDescriptionConvert();

        self::assertEquals('', $descriptionConverter->convert(null));
    }

    /**
     * @dataProvider getDescriptionApiResponseObject
     *
     * @param Description $description
     * @param string $convertedDescription
     */
    public function testCorrectHtmlMarkupConvertion(Description $description, string $convertedDescription): void
    {
        $descriptionConverter = new JiraDescriptionConvert();
        self::assertEquals($convertedDescription, $descriptionConverter->convert($description));
    }

    /**
     * @return array<int, array<int, Description|string>>
     */
    public function getDescriptionApiResponseObject(): array
    {
        $description = new Description();

        $contentField1 = new ContentField();
        $contentField1->type = 'heading';
        $contentField1->content = [
            (object) [
                'text' => 'Objective',
                'type' => 'text',
            ],
        ];

        $contentField2 = new ContentField();
        $contentField2->type = 'paragraph';
        $contentField2->content = [
            (object) [
                'type' => 'text',
                'text' => 'Right now for each change you need to re-build the application to see the change applied.',
            ],
            (object) [
                'type' => 'hardBreak',
            ],
            (object) [
                'type' => 'text',
                'text' => 'I want to create a "npm run serve" kind of functionality to auto-update the docker image.',
            ],
        ];

        $contentField3 = new ContentField();
        $contentField3->type = 'paragraph';
        $contentField3->content = [
            (object) [
                'type' => 'paragraph',
                'content' => [
                    (object) [
                        'type' => 'codeBlock',
                        'content' => [
                            (object) [
                                'type' => 'text',
                                'text' => 'Code Block',
                                'marks' => [
                                    (object) [
                                        'type' => 'underline',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    (object) [
                        'type' => 'blockquote',
                        'content' => [
                            (object) [
                                'type' => 'text',
                                'text' => 'BlockQuote',
                                'marks' => [
                                    (object) [
                                        'type' => 'code',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    (object) [
                        'type' => 'bulletList',
                        'content' => [
                            (object) [
                                'type' => 'listItem',
                                'content' => [
                                    (object) [
                                        'type' => 'text',
                                        'text' => 'Bullet list',
                                        'marks' => [
                                            (object) [
                                                'type' => 'em',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    (object) [
                        'type' => 'orderedList',
                        'content' => [
                            (object) [
                                'type' => 'listItem',
                                'content' => [
                                    (object) [
                                        'type' => 'text',
                                        'text' => 'Ordered list',
                                        'marks' => [
                                            (object) [
                                                'type' => 'strike',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    (object) [
                        'type' => 'table',
                        'content' => [
                            (object) [
                                'type' => 'tableHeader',
                                'content' => [
                                    (object) [
                                        'type' => 'text',
                                        'text' => 'Table header',
                                        'marks' => [
                                            (object) [
                                                'type' => 'subsup',
                                                'attrs' => (object) ['type' => 'sup'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            (object) [
                                'type' => 'tableRow',
                                'content' => [
                                    (object) [
                                        'type' => 'tableCell',
                                        'content' => [
                                            (object) [
                                                'type' => 'text',
                                                'text' => 'Table cell',
                                                'marks' => [
                                                    (object) [
                                                        'type' => 'strong',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $description->setContent([
            $contentField1,
            $contentField2,
            $contentField3,
        ]);

        return [
            [
                $description,
                '<h2>Objective</h2><p>Right now for each change you need to re-build the application to see the change applied.I want to create a "npm run serve" kind of functionality to auto-update the docker image.</p><p><p><code><underline>Code Block</underline></code><blockquote><code>BlockQuote</code></blockquote><ul><li><em>Bullet list</em></li></ul><ol><li><strike>Ordered list</strike></li></ol><table><th><sup>Table header</sup></th><tr><td><strong>Table cell</strong></td></tr></table></p></p>',
            ],
        ];
    }
}
