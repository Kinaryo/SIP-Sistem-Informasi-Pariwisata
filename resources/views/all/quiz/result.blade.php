@extends('all.layouts.app-all')

@section('title','Hasil Kuis')

@section('content')
<div class="container py-5">
    <div class="row mb-4 text-center">
        <div class="col-lg-8 mx-auto">
            <h2 class="fw-bold text-dark mb-2">{{ $quiz->title }}</h2>
            <p class="mb-1">Skor Anda: <strong>{{ $score }}/{{ $total }}</strong></p>
            <p class="mb-0">Persentase: <strong>{{ $percentage }}%</strong></p>
            <hr class="w-25 mx-auto opacity-25 mt-3">
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h4 class="fw-bold mb-3">Jawaban Anda:</h4>
            @foreach($quiz->questions as $index => $question)
            <div class="card border-0 rounded-4 shadow-sm mb-4 quiz-card">
                <div class="card-header bg-white border-0 pt-3 px-3">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-primary me-3 mt-1 quiz-badge">{{ $index + 1 }}</span>
                        <h5 class="fw-bold text-dark mb-0 mt-2 lh-base quiz-question">{{ $question->question }}</h5>
                    </div>
                </div>
                <div class="card-body p-3">
                    <p>
                        Jawaban Anda:
                        <span class="{{ $answers[$question->id] == $question->correct_answer ? 'text-success' : 'text-danger fw-bold' }}">
                            {{ $answers[$question->id] ?? '-' }}
                        </span>
                    </p>
                    <p>Kunci Jawaban: <strong>{{ $question->correct_answer }}</strong></p>

                    @if(!empty($question->option_explanations))
                        <small class="text-muted d-block mt-1 option-explanation">
                            Penjelasan: {{ $question->option_explanations[array_search($question->correct_answer, $question->options) ] ?? '-' }}
                        </small>
                    @endif
                </div>
            </div>
            @endforeach

            <div class="text-center mt-3">
                <a href="{{ route('quiz.index') }}" class="btn btn-secondary rounded px-4 py-2">
                    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Daftar Kuis
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Card hover */
.quiz-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.quiz-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
}

/* Badge dan judul */
.quiz-badge {
    font-size: 0.85rem;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quiz-question {
    font-size: 0.95rem;
}

/* Jawaban */
.text-success {
    color: #198754 !important;
}

.text-danger {
    color: #dc3545 !important;
}

/* Penjelasan */
.option-explanation {
    font-size: 0.85rem;
}

/* Tombol */
.btn {
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Responsif */
@media (max-width: 768px) {
    .quiz-question {
        font-size: 0.9rem;
    }

    .quiz-badge {
        width: 24px;
        height: 24px;
        font-size: 0.75rem;
    }
}
</style>
@endsection
