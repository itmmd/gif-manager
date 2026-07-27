<?php

namespace Modules\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

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
