<?php

namespace App\Http\Controllers\Admin;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\QuizResult;

class QuizController extends Controller
{
    // Menampilkan halaman index quiz
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->get();
        return view('admin.quiz.index', compact('quizzes'));
    }

    // Menampilkan data quiz untuk API (fetch JS)
    public function show($id)
    {
        $quiz = Quiz::with('questions')->find($id);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        return response()->json($quiz);
    }

    // Menyimpan quiz baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Generate slug otomatis dari title
        $slug = $this->createUniqueSlug($request->title);

        $quiz = Quiz::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'message' => 'Quiz created successfully',
            'quiz' => $quiz
        ], 201);
    }

    // Menampilkan halaman show quiz
    public function showPage($id)
    {
        $quiz = Quiz::with(['questions' => function ($query) {
            $query->orderBy('updated_at', 'desc') // Pertanyaan terbaru diupdate paling atas
                ->orderBy('created_at', 'desc'); // Jika sama update_at, urutkan berdasarkan created_at
        }])->findOrFail($id);

        return view('admin.quiz.show', compact('quiz'));
    }

    // Update quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|required|boolean',
        ]);

        $data = [
            'title' => $request->title ?? $quiz->title,
            'description' => $request->description ?? $quiz->description,
            'is_active' => $request->has('is_active') ? $request->is_active : $quiz->is_active,
        ];

        // Update slug jika title berubah
        if ($quiz->title !== $data['title']) {
            $data['slug'] = $this->createUniqueSlug($data['title'], $quiz->id);
        }

        $quiz->update($data);

        return response()->json([
            'message' => 'Quiz updated successfully',
            'quiz' => $quiz
        ]);
    }

    // Delete quiz
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return response()->json(['message' => 'Quiz not found'], 404);
        }

        $quiz->delete();

        return response()->json(['message' => 'Quiz deleted successfully']);
    }

    /**
     * Generate slug unik dari title
     *
     * @param string $title
     * @param int|null $ignoreId
     * @return string
     */
    private function createUniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Quiz::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // Leaderboard Quiz
    public function leaderboard($id)
    {
        $quiz = Quiz::with('results.user')->findOrFail($id);

        // Urutkan hasil dari score tertinggi
        $results = $quiz->results->sortByDesc('score');

        return view('admin.quiz.leaderboard', compact('quiz', 'results'));
    }


    public function showAnswers($resultId)
    {
        $result = QuizResult::with('quiz.questions', 'user')->findOrFail($resultId);
        $quiz = $result->quiz;
        $user = $result->user;

        $answers = [];

        foreach ($quiz->questions as $question) {
            // Ambil jawaban peserta dari array result->answers
            $answer = $result->answers[$question->id] ?? null;

            $answers[] = [
                'question' => $question->question,
                'answer' => $answer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $answer === $question->correct_answer
            ];
        }

        return view('admin.quiz.showAnswers', compact('quiz', 'user', 'result', 'answers'));
    }
}
