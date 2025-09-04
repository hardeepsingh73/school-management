<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApiLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShadowLoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware('verified')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    // Permissions Routes
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::match(['get', 'post'], '/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });
    // Roles Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::match(['get', 'post'], '/', [RoleController::class, 'index'])->name('index');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });
    // User Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::match(['get', 'post'], '/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('settings')->name('settings.')->group(function () {
        // Route::match(['get', 'post'], '/', [SettingController::class, 'index'])->name('index');
        Route::get('/create', [SettingController::class, 'create'])->name('create');
        Route::post('/store', [SettingController::class, 'store'])->name('store');
        Route::get('/{setting}/edit', [SettingController::class, 'edit'])->name('edit');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('update');
        Route::delete('/{setting}', [SettingController::class, 'destroy'])->name('destroy');
        Route::get('/', [SettingController::class, 'bulkedit'])->name('index');
        Route::post('/bulk', [SettingController::class, 'bulkupdate'])->name('bulk-update');
    });
    Route::prefix('login-history')->name('login-history.')->group(function () {
        Route::match(['get', 'post'], '/', [LoginHistoryController::class, 'index'])->name('index');
        Route::get('/my-login-history', [LoginHistoryController::class, 'myHistory'])->name('personal');
        Route::post('/clear', [LoginHistoryController::class, 'clear'])->name('clear');
    });

    Route::prefix('error-logs')->name('error-logs.')->group(function () {
        Route::match(['get', 'post'], '/', [ErrorLogController::class, 'index'])->name('index');
        Route::post('/clear', [ErrorLogController::class, 'clear'])->name('clear');
    });
    Route::prefix('email-logs')->name('email-logs.')->group(function () {
        Route::match(['get', 'post'], '/', [EmailLogController::class, 'index'])->name('index');
        Route::post('/clear', [emailLogController::class, 'clear'])->name('clear');
        Route::get('/{id}/show', [emailLogController::class,  'show'])->name('show');
    });

    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::post('/clear', [ActivityLogController::class, 'clear'])->name('clear');
        Route::patch('/{id}/restore', [ActivityLogController::class, 'restore'])->name('restore');
    });
    Route::prefix('api-logs')->name('api-logs.')->group(function () {
        Route::match(['get', 'post'], '/', [ApiLogController::class, 'index'])->name('index');
        Route::get('/{apiLog}', [ApiLogController::class, 'show'])->name('show');
        Route::post('/clear', [ApiLogController::class, 'clear'])->name('clear');
    });

    // student Routes
    Route::prefix('students')->name('students.')->group(function () {
        Route::match(['get', 'post'], '/', [StudentController::class, 'index'])->name('index');
        Route::get('/create', [StudentController::class, 'create'])->name('create');
        Route::post('store', [StudentController::class, 'store'])->name('store');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update');
        Route::get('/{student}', [StudentController::class, 'show'])->name('show');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
    });
    // teacher Routes
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::match(['get', 'post'], '/', [TeacherController::class, 'index'])->name('index');
        Route::get('/create', [TeacherController::class, 'create'])->name('create');
        Route::post('store', [TeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}/edit', [TeacherController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [TeacherController::class, 'update'])->name('update');
        Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
        Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
    });
    // department Routes
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::match(['get', 'post'], '/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('create');
        Route::post('store', [DepartmentController::class, 'store'])->name('store');
        Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
    });
    // result Routes
    Route::prefix('results')->name('results.')->group(function () {
        Route::match(['get', 'post'], '/', [ResultController::class, 'index'])->name('index');
        Route::get('/create', [ResultController::class, 'create'])->name('create');
        Route::post('store', [ResultController::class, 'store'])->name('store');
        Route::get('/{result}/edit', [ResultController::class, 'edit'])->name('edit');
        Route::put('/{result}', [ResultController::class, 'update'])->name('update');
        Route::delete('/{result}', [ResultController::class, 'destroy'])->name('destroy');
    });
    // timetable Routes
    Route::prefix('timetables')->name('timetables.')->group(function () {
        Route::match(['get', 'post'], '/', [TimetableController::class, 'index'])->name('index');
        Route::get('/create', [TimetableController::class, 'create'])->name('create');
        Route::post('store', [TimetableController::class, 'store'])->name('store');
        Route::get('/{timetable}/edit', [TimetableController::class, 'edit'])->name('edit');
        Route::put('/{timetable}', [TimetableController::class, 'update'])->name('update');
        Route::get('/{timetable}', [TimetableController::class, 'show'])->name('show');
        Route::delete('/{timetable}', [TimetableController::class, 'destroy'])->name('destroy');
        Route::post('/timetables/get', [TimetableController::class, 'timetables'])->name('timetables');
    });
    // school_classe Routes
    Route::prefix('school-classes')->name('school_classes.')->group(function () {
        Route::match(['get', 'post'], '/', [SchoolClassController::class, 'index'])->name('index');
        Route::get('/create', [SchoolClassController::class, 'create'])->name('create');
        Route::post('store', [SchoolClassController::class, 'store'])->name('store');
        Route::get('/{schoolClass}/edit', [SchoolClassController::class, 'edit'])->name('edit');
        Route::put('/{schoolClass}', [SchoolClassController::class, 'update'])->name('update');
        Route::delete('/{schoolClass}', [SchoolClassController::class, 'destroy'])->name('destroy');
    });
    // subject Routes
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::match(['get', 'post'], '/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('store', [SubjectController::class, 'store'])->name('store');
        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
    });
    // attendance Routes
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::match(['get', 'post'], '/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('store', [AttendanceController::class, 'store'])->name('store');
        Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
        Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('show');
        Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('/subjects-by-class/{class}', [AttendanceController::class, 'getSubjectsByClass'])->name('SubjectsByClass');
        Route::get('/students-by-class-subject/{class_id}/{subject_id}', [AttendanceController::class, 'getStudentsByClassSubject'])->name('attendances.StudentsByClassSubject');
    });
    // exam Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::match(['get', 'post'], '/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('store', [ExamController::class, 'store'])->name('store');
        Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
        Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
        Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');
    });
    // class-subject Routes
    Route::prefix('class-subject')->name('class-subject.')->group(function () {
        Route::match(['get', 'post'], '/', [ClassSubjectController::class, 'index'])->name('index');
        Route::get('/create', [ClassSubjectController::class, 'create'])->name('create');
        Route::post('store', [ClassSubjectController::class, 'store'])->name('store');
        Route::get('/{class-subject}/edit', [ClassSubjectController::class, 'edit'])->name('edit');
        Route::put('/{class-subject}', [ClassSubjectController::class, 'update'])->name('update');
        Route::get('/{class-subject}', [ClassSubjectController::class, 'show'])->name('show');
        Route::delete('/{class-subject}', [ClassSubjectController::class, 'destroy'])->name('destroy');
    });
});
Route::middleware(['auth', 'is_superadmin'])->group(function () {
    Route::get('/shadow-login/{user}', [ShadowLoginController::class, 'loginAsUser'])->name('shadow.login');
    Route::get('/shadow-logout', [ShadowLoginController::class, 'revertBack'])->name('shadow.logout');
});
require __DIR__ . '/auth.php';
