<?php

use App\Services\ExportService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class ExportTest extends TestCase
{

    use DatabaseMigrations;

    protected $export;

    /**
     * ExportTest constructor.
     */
    public function __construct()
    {
        $this->export = New ExportService;
    }

    private  function assertFileCount($count,$filename){
        $file = file(public_path('/app/csv/' . $filename));

        $this->assertCount($count,$file);
    }

    /**
     * @test
     * @group export_test
     */
    function should_be_able_to_export_all_students()
    {
        $students = factory(\App\Models\Student::class,5)->create();

        $filename = $this->export->students([
            'students' => $students->pluck('id')
        ]);

        $this->assertFileCount(6,$filename);

    }

    /**
     * @test
     * @group export_test
     */
    function should_be_able_to_export_some_students()
    {

        $someStudentsIds = collect(factory(\App\Models\Student::class,5)->create())
            ->random(3)
            ->pluck('id');

        $filename = $this->export->students([
            'students' => $someStudentsIds
        ]);

        $this->assertFileCount(4,$filename);

    }

    /**
     * @test
     * @group export_test
    */
    public function should_be_able_to_export_course_attendance()
    {
        factory(\App\Models\Course::class,5)->create();

        $filename = $this->export->course_attendance();

        $this->assertFileCount(6,$filename);

    }


    /**
     * @test
     * @group export_test
     */
    function should_be_able_to_export_results_as_csv()
    {
        factory(\App\Models\Student::class,2)->create();

        $filename = $this->export->students([
            'students' => []
        ]);

        $this->assertContains('csv',pathinfo($filename)['extension']);

    }


}