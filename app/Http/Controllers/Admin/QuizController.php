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
    public function index(Request $request)
    {
        $query = Quiz::withCount('questions')->latest();

        // SEARCH (server-side biar pagination tetap sinkron)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // PAGINATION
        $quizzes = $query->paginate(10)->withQueryString();

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
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ]);

            $quiz = Quiz::create([
                'title' => $request->title,
                'slug' => $this->createUniqueSlug($request->title),
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil ditambahkan',
                'data' => $quiz
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan quiz',
                'error' => $e->getMessage()
            ], 500);
        }
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


    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        try {
            $quiz = Quiz::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'required|boolean',
            ]);

            $quiz->update([
                'title' => $request->title,
                'slug' => $this->createUniqueSlug($request->title, $id),
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil diupdate',
                'data' => $quiz
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update quiz',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->delete();

            return response()->json([
                'success' => true,
                'message' => 'Quiz berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus quiz',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function toggleActive($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->is_active = !$quiz->is_active;
        $quiz->save();

        return response()->json([
            'message' => $quiz->is_active
                ? 'Quiz berhasil diaktifkan'
                : 'Quiz berhasil dinonaktifkan'
        ]);
    }
}
