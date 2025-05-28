<style>
    /* Main container */
    .quiz-result-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 800px;
        margin: 2rem auto;
        padding: 2.5rem;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
    }

    /* Page title */
    .result-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 3px solid #3498db;
        text-align: center;
        font-size: 2rem;
    }

    /* Success alert */
    .success-alert {
        background-color: #d4edda;
        color: #155724;
        padding: 1.2rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border-left: 5px solid #28a745;
        font-size: 1.1rem;
        box-shadow: 0 2px 10px rgba(40, 167, 69, 0.1);
    }

    /* Button styling */
    .assignment-btn {
        display: inline-block;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 30px;
        padding: 0.9rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        margin-top: 1.5rem;
    }

    .assignment-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        background: linear-gradient(135deg, #2980b9, #3498db);
    }

    /* Button container */
    .btn-container {
        text-align: center;
        margin-top: 2.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .quiz-result-container {
            padding: 1.5rem;
            margin: 1.5rem;
        }
        
        .result-title {
            font-size: 1.6rem;
        }
    }
</style>

@extends('frontend.layouts.master')
@section('content')
<div class="container quiz-result-container">
    <h2 class="result-title">Kết quả bài làm</h2>

    <!-- Display success message -->
    @if(session('message'))
        <div class="success-alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="btn-container">
        <a href="{{ route('teacher.assignments') }}" class="assignment-btn">
    Làm bài tập
</a>
    </div>
</div>
@endsection