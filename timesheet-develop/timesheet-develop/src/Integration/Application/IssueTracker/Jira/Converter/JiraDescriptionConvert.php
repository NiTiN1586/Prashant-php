<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Converter;

use Jagaad\WitcherApi\Integration\Application\IssueTracker\Jira\Converter\Interfaces\JiraDescriptionConverterInterface;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\ContentField;
use Jagaad\WitcherApi\Integration\Domain\IssueTracker\Description;

final class JiraDescriptionConvert implements JiraDescriptionConverterInterface
{
    private const TYPE_TEXT = 'text';

    private const TAG_MAPPING = [
        'heading' => '<h2>%s</h2>',
        'paragraph' => '<p>%s</p>',
        'hardBreak' => '<hr/>',
        'codeBlock' => '<code>%s</code>',
        'blockquote' => '<blockquote>%s</blockquote>',
        'bulletList' => '<ul>%s</ul>',
        'orderedList' => '<ol>%s</ol>',
        'table' => '<table>%s</table>',
        'listItem' => '<li>%s</li>',
        'tableCell' => '<td>%s</td>',
        'tableHeader' => '<th>%s</th>',
        'tableRow' => '<tr>%s</tr>',
    ];

    private const MARK_SUBSUP = 'subsup';

    public function convert(?Description $description): string
    {
        if (null === $description) {
            return '';
        }

        return $this->processDescriptionContentFields($description->getContent());
    }

    /**
     * @param ContentField[]|object[]|null $contentFields
     * @param string $description
     *
     * @return string
     */
    private function processDescriptionContentFields(?array $contentFields, string $description = ''): string
    {
        if (null === $contentFields) {
            return '';
        }

        $tmpText = '';

        foreach ($contentFields as $contentField) {
            if (!\property_exists($contentField, 'type')) {
                continue;
            }

            if (self::TYPE_TEXT !== $contentField->type) {
                if (!isset(self::TAG_MAPPING[$contentField->type])
                    || !\property_exists($contentField, 'content')
                    || 0 === \count($contentField->content)
                ) {
                    continue;
                }

                $tmpMarkup = $this->processDescriptionContentFields($contentField->content, $tmpText);

                if ('' !== $tmpMarkup) {
                    $description .= \sprintf(self::TAG_MAPPING[$contentField->type], $tmpMarkup);
                }

                continue;
            }

            if (!\property_exists($contentField, 'text')) {
                continue;
            }

            if (\property_exists($contentField, 'marks')) {
                $tmpText .= $this->applyMarks($contentField->marks, $contentField->text);

                continue;
            }

            $tmpText .= $contentField->text;
        }

        $description .= $tmpText;

        return $description;
    }

    /**
     * @param object[] $marks
     * @param string $text
     *
     * @return string
     */
    private function applyMarks(array $marks, string $text): string
    {
        if (0 === \count($marks)) {
            return $text;
        }

        foreach ($marks as $mark) {
            if (!\property_exists($mark, 'type')) {
                continue;
            }

            if (self::MARK_SUBSUP === $mark->type) {
                if (\property_exists($mark, 'attrs') && \property_exists($mark->attrs, 'type')) {
                    $text = \sprintf('<%1$s>%2$s</%1$s>', $mark->attrs->type, $text);
                }

                continue;
            }

            $text = \sprintf('<%1$s>%2$s</%1$s>', $mark->type, $text);
        }

        return $text;
    }
}
