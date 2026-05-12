<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generateContent(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'language'    => 'nullable|string|in:id,en',
            'points'      => 'nullable|string|max:4000',
            'tone'        => 'nullable|string|in:casual,formal,professional,friendly,storytelling',
            'length'      => 'nullable|string|in:short,medium,long',
        ]);

        $title    = $request->input('title');
        $language = $request->input('language', 'en');
        $points   = trim((string) $request->input('points', ''));
        $tone     = $request->input('tone', 'professional');
        $length   = $request->input('length', 'medium');
        $category = $request->filled('category_id')
            ? optional(Category::find($request->category_id))->name
            : null;

        $langInstruction = $language === 'id'
            ? 'Write the article in natural, clear, and engaging Bahasa Indonesia.'
            : 'Write the article in clear, engaging English.';

        $lengthMap = [
            'short'  => '400-600 words',
            'medium' => '600-900 words',
            'long'   => '900-1400 words',
        ];
        $lengthInstruction = $lengthMap[$length] ?? $lengthMap['medium'];

        $toneMap = [
            'casual'        => 'casual and conversational, like talking to a friend',
            'formal'        => 'formal and official',
            'professional'  => 'professional and informative',
            'friendly'      => 'warm, friendly, and personal',
            'storytelling'  => 'narrative storytelling style that flows naturally',
        ];
        $toneInstruction = $toneMap[$tone] ?? $toneMap['professional'];

        $categoryLine = $category ? "Category: {$category}" : '';
        $pointsBlock  = $points !== ''
            ? "Key points that MUST be covered (use them as the outline / sub-headings):\n{$points}"
            : '';

        $prompt = <<<PROMPT
You are a professional blog writer. Write an informative, SEO-friendly, and easy-to-read blog article.

Title: {$title}
{$categoryLine}
Writing tone: {$toneInstruction}

{$pointsBlock}

Instructions:
- {$langInstruction}
- Article length: {$lengthInstruction}.
- If key points are provided above, use them as the main outline (turn them into sub-headings) and expand each one into a detailed paragraph.
- Structure: an engaging opening paragraph (hook), several sub-headings (use <h2> or <h3>), bullet points (<ul>/<ol>) when relevant, and a closing paragraph with a conclusion or call-to-action.
- Output HTML body ONLY (no <html>, <head>, or <body> tags). Allowed tags: <p>, <h2>, <h3>, <strong>, <em>, <ul>, <ol>, <li>, <blockquote>.
- Do NOT include markdown fences (```), explanations, or intros like "Here is the article:".
- Start directly with the HTML content.
PROMPT;

        $result = $this->callGemini($prompt);

        if (!$result['ok']) {
            return response()->json([
                'status'  => 'error',
                'message' => $result['message'],
            ], 500);
        }

        $html = $this->cleanHtml($result['text']);

        return response()->json([
            'status'  => 'success',
            'content' => $html,
        ]);
    }

    public function generateTitles(Request $request)
    {
        $request->validate([
            'topic'       => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'count'       => 'nullable|integer|min:1|max:10',
            'language'    => 'nullable|string|in:id,en',
            'mode'        => 'nullable|string|in:headline,autocomplete',
        ]);

        $topic    = $request->input('topic');
        $count    = $request->input('count', 5);
        $language = $request->input('language', 'en');
        $mode     = $request->input('mode', 'headline');
        $category = $request->filled('category_id')
            ? optional(Category::find($request->category_id))->name
            : null;

        if (!$topic && !$category) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please enter a topic or select a category first.',
            ], 422);
        }

        $langLine     = $language === 'id' ? 'in Bahasa Indonesia' : 'in English';
        $categoryLine = $category ? "Category context: {$category}" : '';

        if ($mode === 'autocomplete' && $topic) {
            $prompt = <<<PROMPT
You are an AI autocomplete engine for a blog title input field, similar to Google Search autocomplete.

The user is currently typing this partial blog title: "{$topic}"
{$categoryLine}

Task: Generate {$count} natural completions of the user's partial text, {$langLine}.

Rules:
- Every completion MUST start with the user's typed text exactly as written (preserve casing and wording), then continue it naturally to form a complete, click-worthy blog title.
- Treat it like Google autocomplete: predict what the user is likely trying to write.
- Each full completion must be at most 80 characters total.
- Output ONLY a JSON array of complete title strings. Example: ["partial typed text continues like this", "partial typed text becomes that"]
- Do NOT add numbering, explanations, markdown, or any other text.
PROMPT;
        } else {
            $topicLine = $topic ? "Topic/keyword: {$topic}" : '';
            $prompt = <<<PROMPT
You are an expert SEO blog copywriter specializing in headline writing.

{$topicLine}
{$categoryLine}

Task: Create {$count} blog titles that are catchy, click-worthy, and SEO-friendly, written {$langLine}.

Output rules:
- Output ONLY a JSON array of title strings. Example: ["Title 1", "Title 2", "Title 3"]
- Do NOT add explanations, numbering, markdown code blocks, or any other text.
- Each title must be at most 70 characters.
PROMPT;
        }

        $result = $this->callGemini($prompt);

        if (!$result['ok']) {
            return response()->json([
                'status'  => 'error',
                'message' => $result['message'],
            ], 500);
        }

        $titles = $this->parseTitleList($result['text']);

        return response()->json([
            'status' => 'success',
            'titles' => $titles,
        ]);
    }

    protected function callGemini(string $prompt): array
    {
        $apiKey = config('services.gemini.key');
        $model  = config('services.gemini.model', 'gemini-2.0-flash');

        if (empty($apiKey)) {
            return [
                'ok'      => false,
                'message' => 'GEMINI_API_KEY is not set in the .env file.',
            ];
        }

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        try {
            $response = Http::timeout(60)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($endpoint . '?key=' . $apiKey, [
                    'contents' => [[
                        'parts' => [['text' => $prompt]],
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.8,
                        'topP'            => 0.95,
                        'maxOutputTokens' => 2048,
                    ],
                ]);
        } catch (\Throwable $e) {
            Log::error('Gemini call failed: ' . $e->getMessage());
            return [
                'ok'      => false,
                'message' => 'Failed to reach Gemini API: ' . $e->getMessage(),
            ];
        }

        if (!$response->successful()) {
            Log::error('Gemini API error', ['body' => $response->body()]);
            return [
                'ok'      => false,
                'message' => 'Gemini API error (HTTP ' . $response->status() . ').',
            ];
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (!$text) {
            return [
                'ok'      => false,
                'message' => 'Gemini returned no content. Please try again.',
            ];
        }

        return ['ok' => true, 'text' => $text];
    }

    protected function cleanHtml(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/^```(?:html)?\s*/i', '', $text);
        $text = preg_replace('/\s*```\s*$/', '', $text);
        return trim($text);
    }

    protected function parseTitleList(string $text): array
    {
        $clean = $this->cleanHtml($text);

        $decoded = json_decode($clean, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('strval', $decoded)));
        }

        $lines = preg_split('/\r?\n/', $clean);
        $titles = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $line = preg_replace('/^[\d]+[\.\)]\s*/', '', $line);
            $line = preg_replace('/^[-*•]\s*/', '', $line);
            $line = trim($line, " \"'");
            if ($line !== '') $titles[] = $line;
        }
        return $titles;
    }
}
