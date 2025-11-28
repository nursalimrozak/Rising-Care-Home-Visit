<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HealthScreeningQuestion;
use App\Models\UserHealthResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthRecordController extends Controller
{
    public function index()
    {
        // Fetch categories with their active questions
        $categories = \App\Models\HealthQuestionCategory::with(['questions' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])->whereHas('questions', function($query) {
            $query->where('is_active', true);
        })->orderBy('order')->get();

        // Fetch uncategorized active questions
        $uncategorizedQuestions = HealthScreeningQuestion::where('category_id', null)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // If there are uncategorized questions, create a dummy category
        if ($uncategorizedQuestions->count() > 0) {
            $othersCategory = new \App\Models\HealthQuestionCategory([
                'name' => 'Lainnya',
                'description' => 'Pertanyaan tambahan',
                'order' => 9999
            ]);
            $othersCategory->setRelation('questions', $uncategorizedQuestions);
            $categories->push($othersCategory);
        }

        $userResponses = UserHealthResponse::where('user_id', Auth::id())->get()->keyBy('question_id');
        
        $hasCompleted = $userResponses->isNotEmpty();
        $lastUpdated = $hasCompleted ? $userResponses->first()->updated_at : null;

        return view('customer.health-record.index', compact('categories', 'userResponses', 'hasCompleted', 'lastUpdated'));
    }

    public function store(Request $request)
    {
        // Validation Rules
        $rules = [];
        $questions = HealthScreeningQuestion::where('is_active', true)->get();
        
        foreach ($questions as $question) {
            if ($question->type == 'checklist') {
                $rules['question_' . $question->id] = 'required|array|min:1';
            } else {
                $rules['question_' . $question->id] = 'required';
            }
        }

        $request->validate($rules, [
            'required' => 'Pertanyaan ini wajib diisi.',
            'min' => 'Pilih setidaknya satu opsi.'
        ]);

        $data = $request->except('_token');
        $userId = Auth::id();

        // Fetch all active questions to avoid N+1 queries
        $questions = HealthScreeningQuestion::where('is_active', true)->get()->keyBy('id');
        
        // Group answers by category for HealthRecord
        $answersByCategory = [];

        foreach ($data as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = str_replace('question_', '', $key);
                
                // Handle array value (checklist)
                $dbValue = $value;
                if (is_array($value)) {
                    $dbValue = json_encode($value);
                }
                
                // 1. Save to UserHealthResponse (for form state persistence)
                UserHealthResponse::updateOrCreate(
                    ['user_id' => $userId, 'question_id' => $questionId],
                    ['response' => $dbValue]
                );

                // 2. Prepare for HealthRecord (for Petugas view)
                if (isset($questions[$questionId])) {
                    $question = $questions[$questionId];
                    $categoryId = $question->category_id;
                    
                    // Only process if category exists (Petugas view requires category->name)
                    if ($categoryId) {
                        // Format answer for display
                        $displayAnswer = $dbValue;
                        if (is_array($value)) {
                            $displayAnswer = implode(', ', $value);
                        }

                        $answersByCategory[$categoryId][] = [
                            'question' => $question->question,
                            'answer' => $displayAnswer
                        ];
                    }
                }
            }
        }

        // Save grouped records to HealthRecord table
        foreach ($answersByCategory as $categoryId => $answers) {
            \App\Models\HealthRecord::updateOrCreate(
                [
                    'customer_id' => $userId,
                    'category_id' => $categoryId
                ],
                [
                    'answers' => $answers,
                    'recorded_at' => now(),
                    'recorded_by' => $userId
                ]
            );
        }

        return redirect()->route('customer.health-record.index')->with('success', 'Rekap kesehatan berhasil disimpan');
    }
}
