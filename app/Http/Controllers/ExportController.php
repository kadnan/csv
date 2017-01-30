<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\University;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{

    private $export;

    public function __construct(ExportService $export)
    {
        $this->export = $export;
        // Only to test in the browser api auth
        Auth::loginUsingId(1);
    }

    public function welcome()
    {
        return view('hello');
    }

    /**
     * View all students found in the database
     */
    public function viewStudents()
    {

        $students = Student::with('courses.university')->get();

        return view('view_students', compact(['students']));
    }

    /**
     * Exports all student data to a CSV file
     */
    public function exportStudentsToCSV()
    {
        $this->validate(request(), [
            'students' => 'required',
        ]);

        $pathToFile = $this->export->students(request());

        return response()->json([
            'file' => 'app/csv/' . $pathToFile
        ]);

    }

    /**
     * Exports the total amount of students that are taking each course to a CSV file
     */
    public function exportCourseAttendenceToCSV()
    {
        $pathToFile = $this->export->course_attendance();

        return response()->json([
            'file' => 'app/csv/' . $pathToFile
        ]);

    }
}
