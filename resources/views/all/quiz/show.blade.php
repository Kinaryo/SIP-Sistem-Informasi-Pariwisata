@extends('all.layouts.app-all')

@section('title', $quiz->title)

@section('content')
<div class="container py-5">
    <div class="row mb-5 justify-content-center text-center">
        <div class="col-lg-8">
            <h3 class="fw-bold text-dark mb-3 quiz-title">{{ $quiz->title }}</h3>
            <p class="text-muted quiz-description">{{ $quiz->description }}</p>
            <hr class="w-25 mx-auto opacity-25">
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            @auth
            <form action="{{ route('quiz.submit', $quiz->slug) }}" method="POST">
                @csrf
                @foreach($quiz->questions as $index => $question)
                <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden quiz-card">
                    <div class="card-header bg-white border-0 pt-3 px-3">
                        <div class="d-flex align-items-start">
                            <span class="badge bg-primary me-3 mt-1 quiz-badge">{{ $index + 1 }}</span>
                            <h5 class="fw-bold text-dark mb-0 mt-2 lh-base quiz-question">{{ $question->question }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="quiz-options-group">
                            @foreach((array) $question->options as $option)
                            <div class="option-item mb-2">
                                <input type="radio" name="question_{{ $question->id }}" value="{{ $option }}"
                                    class="btn-check" id="q{{ $question->id }}_{{ $loop->index }}" required>
                                <label class="btn btn-outline-light text-start w-100 p-2 rounded-3 d-flex align-items-center custom-option-label"
                                    for="q{{ $question->id }}_{{ $loop->index }}">
                                    <span class="option-letter me-2">{{ chr(65 + $loop->index) }}</span>
                                    <div class="option-content">
                                        <span class="option-text text-dark">{{ $option }}</span>
                                        @if(!empty($question->option_explanations[$loop->index] ?? ''))
                                        <small class="text-muted d-block mt-1 option-explanation">
                                            {{ $question->option_explanations[$loop->index] }}
                                        </small>
                                        @endif
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary rounded px-4 py-2">
                        <i class="bi bi-arrow-left-circle me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded px-4 py-2 submit-btn">
                        Submit Jawaban <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </div>
            </form>
            @else
            <div class="card border-0 bg-light rounded-4 p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-lock-fill text-warning fs-2"></i>
                </div>
                <h4 class="fw-bold">Akses Terbatas</h4>
                <p class="text-muted">Silakan login terlebih dahulu untuk mulai mengerjakan kuis ini.</p>
                <a href="{{ route('login') }}" class="btn btn-primary px-3 rounded-pill mt-2">Login Sekarang</a>
            </div>
            @endauth
        </div>
    </div>
</div>

<style>
/* Card hover */
.quiz-card {
    padding: 0;
    transition: transform 0.3s, box-shadow 0.3s;
}

.quiz-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
}

/* Judul dan deskripsi proporsional */
.quiz-title {
    font-size: 1.75rem;
}

.quiz-description {
    font-size: 0.95rem;
}

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

/* Opsi modern dengan hover */
.custom-option-label {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    border: 2px solid #edf2f7 !important;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}

.custom-option-label:hover {
    background-color: #f0f7ff !important;
    border-color: #0d6efd !important;
    transform: translateX(3px);
}

.btn-check:checked + .custom-option-label {
    background-color: #e7f1ff !important;
    border-color: #0d6efd !important;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
}

.btn-check:checked + .custom-option-label .option-letter {
    background-color: #0d6efd;
    color: white;
}

.option-letter {
    width: 26px;
    height: 26px;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f1f3f5;
    border-radius: 50%;
    font-weight: bold;
    color: #6c757d;
    flex-shrink: 0;
    transition: all 0.2s;
}

.option-explanation {
    font-size: 0.8rem;
}

/* Tombol submit */
.submit-btn {
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Responsif */
@media (max-width: 768px) {
    .quiz-title {
        font-size: 1.5rem;
    }
    .quiz-question {
        font-size: 0.9rem;
    }
    .custom-option-label {
        font-size: 0.85rem;
    }
    .option-letter {
        width: 24px;
        height: 24px;
        font-size: 0.75rem;
    }
}
</style>
@endsection
