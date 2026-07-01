<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Fields to skip sanitization (passwords, tokens, rich text).
     */
    protected array $skipFields;

    public function __construct()
    {
        $this->skipFields = config('security.sanitization.skip_fields', [
            'password', 'password_confirmation', '_token', 'content', 'description', 'body'
        ]);
    }

    public function handle(Request $request, Closure $next)
    {
        if (config('security.sanitization.strip_tags', true)) {
            $input = $this->sanitizeArray($request->all());
            $request->replace($input);
        }

        return $next($request);
    }

    protected function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->skipFields)) {
                continue;
            }

            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->sanitizeString($value);
            }
        }

        return $data;
    }

    protected function sanitizeString(string $value): string
    {
        $maxLength = config('security.sanitization.max_input_length', 65535);

        // Trim whitespace
        $value = trim($value);

        // Strip HTML tags
        $value = strip_tags($value);

        // Truncate to max length
        $value = mb_substr($value, 0, $maxLength);

        return $value;
    }
}
