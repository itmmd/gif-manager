<?php

namespace Modules\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

/**
 * Agent that analyses the first frame of a GIF/MP4 and returns structured output:
 *   - suggested_title  : short descriptive title (max 60 chars)
 *   - suggested_tags   : 3–5 relevant tags
 *   - description      : one-sentence description (max 120 chars)
 *   - is_flagged       : true when content is inappropriate for public display
 *   - flag_reason      : reason if flagged, null otherwise
 *
 * Prompt injection protection: instructions never include user-supplied content.
 * Only the image attachment (binary) is passed to the provider — no user text.
 */
class VisionAnalysisAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): string
    {
        return <<<'PROMPT'
You are a media analysis assistant. Analyse the provided image and respond ONLY with valid JSON matching the required schema. Do not include any text outside the JSON object.

Rules:
- suggested_title: concise descriptive title, max 60 characters, no HTML, no markdown.
- suggested_tags: array of 3 to 5 lowercase single-word or hyphenated tags relevant to the image content.
- description: one sentence, max 120 characters, no HTML, plain text only.
- is_flagged: set to true ONLY for graphic violence, explicit adult nudity, or clearly illegal content. Animated/cartoon content should almost always be false.
- flag_reason: a brief reason string when is_flagged is true; omit or set to null otherwise.
PROMPT;
    }

    /**
     * Return the structured output schema (a single Type from the JSON Schema API).
     *
     * The SDK passes a JsonSchemaTypeFactory instance so all factory methods
     * (string(), boolean(), array(), object()) are available.
     *
     * @return array<string, Type>  — associative map used by the SDK to build the schema
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'suggested_title' => $schema->string(),
            'suggested_tags'  => $schema->array()->items($schema->string()),
            'description'     => $schema->string(),
            'is_flagged'      => $schema->boolean(),
            'flag_reason'     => $schema->string()->nullable(),
        ];
    }
}
