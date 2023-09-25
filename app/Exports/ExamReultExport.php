<?php

namespace App\Exports;

use App\Models\Quiz\ExamResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamReultExport implements FromCollection,WithHeadings,WithMapping
{
    public $examId;

    public $count;

    public function __construct($examId, $count)
    {
        $this->examId = $examId;
        $this->count = $count;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result = ExamResult::query()
            ->join('users', 'quiz_exam_results.user_id', '=', 'users.id')
            ->where('quiz_exam_results.quiz_exam_id', $this->examId)
            ->get();
        return $result;
    }

    /**
     * Returns headers for report
     * @return array
     */
    public function headings(): array {
        return [
            'Name',
            'Email',
            'Point',
            "Total time",
            "Created",
            "Updated"
        ];
    }

    public function map($result): array {
        return [
            $result->name,
            $result->email,
            $result->point,
            $result->total_time,
            $result->created_at,
            $result->updated_at
        ];
    }
}
