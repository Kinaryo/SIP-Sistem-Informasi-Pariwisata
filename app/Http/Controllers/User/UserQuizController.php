<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class UserQuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('is_active', true)->latest()->get();
        $userId = auth()->id();

        // Ambil leaderboard per quiz
        $leaderboards = [];

        foreach ($quizzes as $quiz) {
            // Top 10 skor
            $topScores = $quiz->results()
                ->with('user')
                ->orderByDesc('score')
                ->orderByDesc('updated_at')
                ->take(10)
                ->get();

            // Posisi user login
            $userScore = $quiz->results()->where('user_id', $userId)->first();
            $userRank = null;

            if ($userScore) {
                $userRank = $quiz->results()
                    ->where('score', '>', $userScore->score)
                    ->count() + 1;
            }

            $leaderboards[$quiz->id] = [
                'topScores' => $topScores,
                'userScore' => $userScore,
                'userRank' => $userRank,
            ];
        }

        return view('all.quiz.index', compact('quizzes', 'leaderboards'));
    }


    public function show($slug)
    {
        $quiz = Quiz::with('questions')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('all.quiz.show', compact('quiz'));
    }

    public function submit(Request $request, $slug)
    {
        $quiz = Quiz::with('questions')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $score = 0;
        $answers = [];

        foreach ($quiz->questions as $question) {
            $answer = $request->input('question_' . $question->id);
            $answers[$question->id] = $answer ?? null;

            if ($answer && $answer == $question->correct_answer) {
                $score++;
            }
        }

        $total = $quiz->questions->count();
        $percentage = $total > 0 ? round(($score / $total) * 100) : 0;

        // ✅ Cek dulu apakah user sudah punya hasil untuk quiz ini
        $quizResult = QuizResult::firstOrNew([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
        ]);

        // Update skor & jawaban
        $quizResult->score = $score;
        $quizResult->total_questions = $total;
        $quizResult->answers = $answers;
        $quizResult->save();

        return view('all.quiz.result', compact('quiz', 'score', 'total', 'percentage', 'answers'));
    }

    public function leaderboard($slug)
    {
        $quiz = Quiz::with('questions')->where('slug', $slug)->firstOrFail();

        $userId = auth()->id();

        // Ambil top 10 skor tertinggi
        $topScores = $quiz->results()
            ->with('user')
            ->orderByDesc('score')
            ->orderByDesc('updated_at')
            ->take(10)
            ->get();

        // Cek posisi user login
        $userScore = $quiz->results()->where('user_id', $userId)->first();

        $userRank = null;
        if ($userScore) {
            // Hitung rank user login
            $userRank = $quiz->results()
                ->where('score', '>', $userScore->score)
                ->count() + 1;
        }

        return view('all.quiz.leaderboard', compact('quiz', 'topScores', 'userScore', 'userRank'));
    }
}
