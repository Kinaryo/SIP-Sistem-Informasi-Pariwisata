<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    // Tambah Question
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
        ]);

        // Pastikan correct_answer ada di options
        if (!in_array($request->correct_answer, $request->options)) {
            return response()->json(['message' => 'Jawaban benar harus sama dengan salah satu opsi'], 422);
        }

        $question = $quiz->questions()->create([
            'question' => $request->question,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
        ]);

        return response()->json([
            'message' => 'Soal berhasil ditambahkan',
            'question' => $question
        ]);
    }

    // Update Question
    public function update(Request $request, $quizId, $id)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
        ]);

        // Pastikan correct_answer ada di options
        if (!in_array($request->correct_answer, $request->options)) {
            return response()->json(['message' => 'Jawaban benar harus sama dengan salah satu opsi'], 422);
        }

        $question->update([
            'question' => $request->question,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
        ]);

        return response()->json([
            'message' => 'Soal berhasil diperbarui',
            'question' => $question
        ]);
    }

    // Delete Question
    public function destroy($quizId, $id)
    {
        $quiz = Quiz::findOrFail($quizId);
        $question = $quiz->questions()->findOrFail($id);
        $question->delete();

        return response()->json([
            'message' => 'Soal berhasil dihapus'
        ]);
    }

    
}
