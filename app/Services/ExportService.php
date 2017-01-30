<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Student;
use App\Models\University;
use Carbon\Carbon;

class ExportService
{

    /**
     * Returns the required student data as csv
     * @param $data
     * @return string
     */
    public function students($data){

        $results = $this->getStudentsData($data);

        $header = ['id','firstname','surname','email','nationality'];

        return $this->createCsvFile($results,$header);

    }

    /**
     * Returns the required course data as csv
     * @param $data
     * @return string
     */
    public function course_attendance(){

        $results = $this->getCourseAttendance();

        $header = ['university','course_name','students'];

        return $this->createCsvFile($results,$header);

    }

    /**
     * Get students data from the requested information
     * @param $data
     * @return array
     */
    private function getStudentsData($data){

        $results = Student::whereIn('id', $data['students'])->get();

        return $results->toArray();
    }

    /**
     * * Get course data from the requested information
     * @return array
     */
    private function getCourseAttendance(){

        $courses=[];

        University::with(['courses' => function ($query) {
            $query->withCount('students')->get();
        }])
            ->orderBy('name')
            ->get()
            ->each(function ($university, $key) use(&$courses) {
                $university->courses->each(function ($course,$key) use(&$courses,$university){
                    $courses[] = [
                        'university' => $university->name,
                        'course_name' => $course->course_name,
                        'studentes' => $course->students_count
                    ];
                });
            })
            ->toArray();

        return $courses;

    }

    /**
     * Creates a csv file from the provided data
     * @param $data
     * @param $header
     * @return string
     */
    private function createCsvFile($data,$header){

        $filename = Carbon::now()->timestamp . '-' . mt_rand() . '.csv';

        $fp = fopen(public_path('app/csv/' . $filename), 'w');

        fputcsv($fp, $header);

        foreach ($data as $item) {
            fputcsv($fp, $item);
        }

        fclose($fp);

        return $filename;

    }

}