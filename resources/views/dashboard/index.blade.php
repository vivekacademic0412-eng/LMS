@extends('layouts.app')
@php
    $roleLabels = \App\Models\User::roleOptions();
    $accentClass = [
        'blue' => 'accent-blue',
        'green' => 'accent-green',
        'violet' => 'accent-violet',
        'orange' => 'accent-orange',
        'red' => 'accent-red',
        'teal' => 'accent-teal',
    ];
    $courseIcons = ['DS', 'UX', 'FN', 'WB', 'CL', 'AI'];
    $allCoursesRoute = $user->role === \App\Models\User::ROLE_STUDENT ? route('student.courses') : route('courses.index');
    $isStudent = $dashboardMode === 'student';
    $isTrainer = $dashboardMode === 'trainer';
    $learningTitle = $isStudent ? 'My Learning' : ($isTrainer ? 'Assigned Learning' : 'Course Snapshot');
    $learningSubtitle = $isStudent
        ? 'Track your progress and continue your training path.'
        : ($isTrainer ? 'Track assigned learners and completion.' : 'Monitor catalog activity with role-safe access.');
    $learningActionLabel = $isStudent ? 'View all courses ->' : 'Open catalog ->';
    $heroKicker = $isStudent ? 'Continue learning' : ($user->role === \App\Models\User::ROLE_SUPERADMIN ? '' : 'Dashboard overview');
    $heroResumeRoute = route('panel.' . $user->role);
    if ($isStudent && !empty($studentResumeItem) && !empty($studentResumeItem['route'])) {
        $heroResumeRoute = $studentResumeItem['route'];
    } elseif (!empty($heroCourse) && !empty($heroCourse['resume_route'])) {
        $heroResumeRoute = $heroCourse['resume_route'];
    } elseif (!empty($heroCourse['course_id'])) {
        $heroResumeRoute = $user->role === \App\Models\User::ROLE_STUDENT
            ? route('student.courses.show', $heroCourse['course_id'])
            : route('courses.show', $heroCourse['course_id']);
    }
@endphp
@section('content')
    <style>
        .page { max-width: 1440px; padding: 0 18px 0; }
        .dash-grid { display: grid; gap: 18px; }
        .student-mode .dash-hero {
            background: radial-gradient(circle at 10% 0%, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0) 45%),
                        linear-gradient(120deg, #0e4aa8 0%, #2a79da 100%);
            box-shadow: 0 18px 36px rgba(15, 55, 120, 0.2);
        }
        .student-mode .hero-btn {
            border-radius: 999px;
            padding: 10px 16px;
            box-shadow: 0 10px 22px rgba(255, 255, 255, 0.25);
        }
        .student-mode .learning-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .student-mode .recommend-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .student-mode .course-card {
            border-color: #cfd9ec;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.08);
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }
        .student-mode .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 30px rgba(18, 42, 86, 0.14);
            border-color: #b7c9e8;
        }
        .student-mode .course-top::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(12, 32, 64, 0.18), rgba(12, 32, 64, 0.55));
            z-index: 0;
        }
        .student-mode .course-top > * { z-index: 1; }
        .student-mode .course-body h3 { font-size: 19px; }
        .student-focus-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            align-items: start;
        }
        .resume-panel {
            border: 1px solid #d7deea;
            border-radius: 14px;
            background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 56%, #eef4ff 100%);
            padding: 16px;
            display: grid;
            gap: 12px;
            align-content: start;
            grid-auto-rows: max-content;
            box-shadow: 0 12px 24px rgba(18, 42, 86, 0.07);
        }
        .resume-panel-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .resume-copy {
            display: grid;
            gap: 8px;
        }
        .resume-copy h2 {
            margin: 0;
            font-size: 24px;
            line-height: 1.12;
            color: #102849;
        }
        .resume-note {
            margin: 0;
            color: #5c6b84;
            font-size: 13px;
            line-height: 1.6;
        }
        .resume-route-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .resume-route-meta .pill,
        .resume-route-meta .focus-pill {
            flex: 0 0 auto;
        }
        .resume-stat-grid {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .resume-stat {
            border: 1px solid #d7e4f5;
            border-radius: 12px;
            background: #fff;
            padding: 10px 12px;
            display: grid;
            gap: 4px;
        }
        .resume-stat span {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #62728b;
        }
        .resume-stat strong {
            color: #102849;
            font-size: 20px;
            line-height: 1.1;
        }
        .focus-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid #d5e3f6;
            background: #f6f9ff;
            color: #31588f;
        }
        .focus-pill--task {
            background: #fff6ea;
            color: #a86112;
            border-color: #f0d7b7;
        }
        .focus-pill--quiz {
            background: #edf4ff;
            color: #1c56b5;
            border-color: #c8d8f5;
        }
        .action-queue {
            border: 1px solid #d7deea;
            border-radius: 14px;
            background: #fff;
            padding: 16px;
            display: grid;
            gap: 12px;
            align-content: start;
            box-shadow: 0 12px 24px rgba(18, 42, 86, 0.05);
        }
        .action-queue-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: end;
        }
        .action-queue-head h3 {
            margin: 0;
            font-size: 20px;
        }
        .action-queue-head p {
            margin: 4px 0 0;
            color: #617089;
            font-size: 13px;
        }
        .queue-summary {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .queue-summary-box {
            border: 1px solid #dce4f1;
            border-radius: 12px;
            background: #f8fbff;
            padding: 10px 12px;
            display: grid;
            gap: 4px;
        }
        .queue-summary-box span {
            color: #617089;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .queue-summary-box strong {
            color: #102849;
            font-size: 20px;
            line-height: 1.1;
        }
        .queue-list {
            display: grid;
            gap: 8px;
            max-height: 320px;
            overflow: auto;
            padding-right: 4px;
            align-content: start;
            scrollbar-width: thin;
        }
        .queue-list::-webkit-scrollbar {
            width: 8px;
        }
        .queue-list::-webkit-scrollbar-thumb {
            background: #c8d6ea;
            border-radius: 999px;
        }
        .queue-item {
            text-decoration: none;
            color: inherit;
            border: 1px solid #dde6f3;
            border-radius: 12px;
            background: #f9fbff;
            padding: 10px 12px;
            display: grid;
            gap: 4px;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }
        .queue-item:hover {
            transform: translateY(-2px);
            border-color: #bfd1ef;
            box-shadow: 0 14px 24px rgba(18, 42, 86, 0.08);
        }
        .queue-item-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: start;
        }
        .queue-item strong {
            color: #102849;
            font-size: 15px;
            line-height: 1.3;
        }
        .queue-item p {
            margin: 0;
            color: #5a6b84;
            font-size: 13px;
        }
        .queue-meta {
            color: #6a7890;
            font-size: 12px;
        }
        .queue-tag {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }
        .queue-tag--task {
            background: #fff4e7;
            color: #a65e0e;
        }
        .queue-tag--quiz {
            background: #edf4ff;
            color: #1d56b3;
        }
        .submission-card--done {
            border-color: #cfe5d9;
            background: linear-gradient(180deg, #f7fffb 0%, #ffffff 100%);
        }
        .submission-card--pending {
            border-color: #d7deea;
        }
        .submission-card--revision {
            border-color: #f0d7b8;
            background: linear-gradient(180deg, #fffaf3 0%, #ffffff 100%);
        }
        .submission-empty {
            border: 1px dashed #ccd8ea;
            border-radius: 12px;
            background: #f9fbff;
            padding: 18px;
            color: #5c6b84;
            font-size: 13px;
        }
        .certificate-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        .certificate-card {
            border: 1px solid #d7e4f8;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            padding: 16px;
            display: grid;
            gap: 10px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.05);
        }
        .certificate-card-top {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 10px;
        }
        .certificate-card h4 {
            margin: 0;
            color: #102849;
            font-size: 19px;
            line-height: 1.25;
        }
        .certificate-meta {
            margin: 0;
            color: #5a6b84;
            font-size: 13px;
            line-height: 1.65;
        }
        .certificate-code {
            color: #6d7d95;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .student-dashboard-columns {
            display: grid;
            gap: 16px;
            grid-template-columns: minmax(0, 1fr) minmax(300px, 340px);
            align-items: start;
        }
        .student-dashboard-columns > * {
            min-width: 0;
        }
        .student-dashboard-main,
        .student-dashboard-side {
            display: grid;
            gap: 16px;
            align-content: start;
        }
        .student-learning-section {
            width: 100%;
        }
        .student-learning-section .section-head {
            align-items: center;
        }
        .student-learning-section .learning-grid {
            align-items: stretch;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        .student-learning-section .course-card {
            height: 100%;
        }
        .student-column-group {
            display: grid;
            gap: 8px;
            align-content: start;
        }
        .student-column-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            border: 1px solid #d6e1f3;
            background: #edf3ff;
            color: #335689;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .dashboard-section {
            border: 1px solid #d7deea;
            border-radius: 14px;
            background: #fff;
            padding: 16px;
            display: grid;
            gap: 12px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.05);
        }
        .dashboard-section--side {
            padding: 14px;
        }
        .dashboard-section .section-head {
            align-items: center;
        }
        .dashboard-section .section-head h2 {
            font-size: 22px;
        }
        .dashboard-section .section-head p {
            max-width: 56ch;
        }
        .dashboard-section-body {
            display: grid;
            gap: 10px;
        }
        .student-progress-dashboard {
            gap: 16px;
            background:
                radial-gradient(circle at top right, rgba(42, 121, 218, 0.09), rgba(42, 121, 218, 0) 34%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }
        .student-progress-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .student-progress-card {
            border: 1px solid #d7e2f3;
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(246, 250, 255, 0.92));
            padding: 16px;
            display: grid;
            gap: 14px;
            box-shadow: 0 14px 28px rgba(18, 42, 86, 0.06);
        }
        .student-progress-card-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .student-progress-card-head h3 {
            margin: 4px 0 0;
            color: #102849;
            font-size: 22px;
            line-height: 1.15;
        }
        .student-progress-kicker {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            background: #edf4ff;
            color: #2f5c96;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .student-progress-score {
            color: #145fd1;
            font-size: 34px;
            line-height: 1;
            letter-spacing: -0.04em;
        }
        .student-progress-note {
            margin: -6px 0 0;
            color: #61748f;
            font-size: 12px;
            line-height: 1.65;
        }
        .student-completion-layout {
            display: grid;
            gap: 14px;
            grid-template-columns: minmax(160px, 180px) minmax(0, 1fr);
            align-items: center;
        }
        .student-completion-ring {
            position: relative;
            width: 148px;
            height: 148px;
            margin: 0 auto;
        }
        .student-completion-ring svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        .student-completion-ring-track,
        .student-completion-ring-value {
            fill: none;
            stroke-width: 12;
        }
        .student-completion-ring-track {
            stroke: #dfe8f6;
        }
        .student-completion-ring-value {
            stroke: #1f6fd3;
            stroke-linecap: round;
            transition: stroke-dashoffset 240ms ease;
        }
        .student-completion-center {
            position: absolute;
            inset: 0;
            display: grid;
            place-content: center;
            text-align: center;
            gap: 2px;
        }
        .student-completion-center strong {
            color: #102849;
            font-size: 30px;
            line-height: 1;
        }
        .student-completion-center span {
            color: #687a95;
            font-size: 12px;
            line-height: 1.5;
            max-width: 88px;
        }
        .student-progress-meta-list {
            display: grid;
            gap: 10px;
        }
        .student-progress-meta,
        .student-progress-inline-stat {
            border: 1px solid #dbe6f5;
            border-radius: 14px;
            background: #ffffff;
            padding: 11px 12px;
            display: grid;
            gap: 4px;
        }
        .student-progress-meta span,
        .student-progress-inline-stat span {
            color: #617089;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .student-progress-meta strong,
        .student-progress-inline-stat strong {
            color: #102849;
            font-size: 22px;
            line-height: 1.1;
        }
        .student-progress-series {
            display: grid;
            gap: 10px;
        }
        .student-progress-series-row {
            display: grid;
            gap: 8px;
            grid-template-columns: minmax(0, 1fr) minmax(120px, 1.3fr) auto;
            align-items: center;
        }
        .student-progress-series-copy {
            display: grid;
            gap: 2px;
            min-width: 0;
        }
        .student-progress-series-copy strong {
            color: #102849;
            font-size: 14px;
            line-height: 1.35;
        }
        .student-progress-series-copy span {
            color: #687a95;
            font-size: 12px;
        }
        .student-progress-series-meter {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #e5edf9;
            overflow: hidden;
        }
        .student-progress-series-meter span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #1f6fd3 0%, #5aa4ff 100%);
        }
        .student-progress-series-row em {
            color: #355688;
            font-size: 12px;
            font-style: normal;
            font-weight: 800;
        }
        .student-time-chart,
        .student-quiz-chart {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(14, minmax(0, 1fr));
            align-items: end;
            min-height: 190px;
        }
        .student-quiz-chart {
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }
        .student-time-bar,
        .student-quiz-point {
            display: grid;
            gap: 6px;
            justify-items: center;
        }
        .student-time-bar-track,
        .student-quiz-point-track {
            width: 100%;
            min-height: 132px;
            border-radius: 999px;
            background: linear-gradient(180deg, #f0f4fb 0%, #e3eaf7 100%);
            display: flex;
            align-items: end;
            padding: 6px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        }
        .student-time-bar-track span,
        .student-quiz-point-track span {
            display: block;
            width: 100%;
            border-radius: 999px;
        }
        .student-time-bar-track span {
            background: linear-gradient(180deg, #7cb1ff 0%, #1f6fd3 100%);
        }
        .student-quiz-point-track span {
            background: linear-gradient(180deg, #6bd4b1 0%, #1f8d77 100%);
        }
        .student-time-bar strong,
        .student-quiz-point strong {
            color: #102849;
            font-size: 12px;
            line-height: 1;
        }
        .student-time-bar span,
        .student-quiz-point span {
            color: #6a7a92;
            font-size: 11px;
            font-weight: 700;
        }
        .student-time-bar.is-today .student-time-bar-track,
        .student-quiz-point:hover .student-quiz-point-track {
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88), 0 12px 20px rgba(31, 111, 211, 0.12);
        }
        .student-progress-inline-stats {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .student-streak-grid {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }
        .student-streak-cell {
            position: relative;
            aspect-ratio: 1 / 1;
            min-height: 42px;
            border-radius: 14px;
            border: 1px solid #dbe6f5;
            background: #f5f8fd;
            display: grid;
            place-content: center;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        }
        .student-streak-cell.level-1 { background: #e9f4ff; border-color: #cde0fb; }
        .student-streak-cell.level-2 { background: #d6e9ff; border-color: #b6d2f7; }
        .student-streak-cell.level-3 { background: #b6d7ff; border-color: #86b8ef; }
        .student-streak-cell.level-4 { background: linear-gradient(180deg, #5ba3ff 0%, #1f6fd3 100%); border-color: #1f6fd3; }
        .student-streak-cell.level-4 .student-streak-day,
        .student-streak-cell.level-4 .student-streak-month {
            color: #ffffff;
        }
        .student-streak-cell.is-today {
            outline: 2px solid #1f6fd3;
            outline-offset: 2px;
        }
        .student-streak-month {
            position: absolute;
            left: 6px;
            top: 6px;
            color: #5f7695;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .student-streak-day {
            color: #14345e;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }
        .student-streak-legend {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            color: #6a7a92;
            font-size: 11px;
            font-weight: 700;
        }
        .student-streak-legend-steps {
            display: inline-flex;
            gap: 6px;
            align-items: center;
        }
        .student-streak-legend-steps i {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            border: 1px solid #dbe6f5;
            display: inline-block;
            background: #f5f8fd;
        }
        .student-streak-legend-steps i.level-1 { background: #e9f4ff; border-color: #cde0fb; }
        .student-streak-legend-steps i.level-2 { background: #d6e9ff; border-color: #b6d2f7; }
        .student-streak-legend-steps i.level-3 { background: #b6d7ff; border-color: #86b8ef; }
        .student-streak-legend-steps i.level-4 { background: #1f6fd3; border-color: #1f6fd3; }
        .student-progress-dashboard {
            display: grid;
            gap: 16px;
        }
        .student-progress-stat-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .student-progress-stat-card {
            position: relative;
            overflow: hidden;
            border: 1px solid #d7e2f3;
            border-radius: 18px;
            padding: 16px;
            display: grid;
            gap: 8px;
            background:
                radial-gradient(circle at top right, rgba(42, 121, 218, 0.14), rgba(42, 121, 218, 0) 42%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 14px 28px rgba(18, 42, 86, 0.06);
        }
        .student-progress-stat-card::after {
            content: '';
            position: absolute;
            inset: auto -24px -34px auto;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            opacity: 0.18;
        }
        .student-progress-stat-card--blue::after { background: radial-gradient(circle, #7cb1ff 0%, rgba(124, 177, 255, 0) 70%); }
        .student-progress-stat-card--indigo::after { background: radial-gradient(circle, #8f9bff 0%, rgba(143, 155, 255, 0) 70%); }
        .student-progress-stat-card--green::after { background: radial-gradient(circle, #75d6bf 0%, rgba(117, 214, 191, 0) 70%); }
        .student-progress-stat-card--amber::after { background: radial-gradient(circle, #f5c46a 0%, rgba(245, 196, 106, 0) 70%); }
        .student-progress-stat-label {
            color: #617089;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .student-progress-stat-value {
            color: #102849;
            font-size: 34px;
            line-height: 1;
            letter-spacing: -0.04em;
        }
        .student-progress-stat-card p {
            margin: 0;
            color: #60718a;
            font-size: 13px;
            line-height: 1.6;
            max-width: 22ch;
        }
        .student-progress-visual-grid,
        .student-progress-detail-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .student-progress-card--wide {
            grid-column: 1 / -1;
        }
        .student-progress-toggle-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }
        .student-progress-toggle {
            border: 1px solid #d7e2f3;
            border-radius: 999px;
            background: #f7faff;
            color: #355688;
            font-size: 12px;
            font-weight: 800;
            padding: 7px 12px;
            cursor: pointer;
            transition: 180ms ease;
        }
        .student-progress-toggle:hover {
            transform: translateY(-1px);
            border-color: #bfd2f0;
            box-shadow: 0 10px 20px rgba(18, 42, 86, 0.08);
        }
        .student-progress-toggle.is-active {
            background: linear-gradient(180deg, #2a79da 0%, #145fd1 100%);
            border-color: #145fd1;
            color: #ffffff;
            box-shadow: 0 14px 24px rgba(20, 95, 209, 0.24);
        }
        .student-weekly-shell {
            display: grid;
            gap: 14px;
            grid-template-columns: minmax(0, 1fr) 220px;
            align-items: stretch;
        }
        .student-weekly-stage {
            position: relative;
            border: 1px solid #dfe7f5;
            border-radius: 18px;
            padding: 16px 14px 10px;
            background: linear-gradient(180deg, #fafdff 0%, #f4f9ff 100%);
            min-height: 240px;
        }
        .student-weekly-plot {
            display: none;
            width: 100%;
            height: 100%;
        }
        .student-weekly-plot.is-active {
            display: block;
        }
        .student-weekly-plot svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }
        .student-weekly-grid-line {
            stroke: #dfe8f6;
            stroke-width: 0.8;
            stroke-dasharray: 3 3;
        }
        .student-weekly-area {
            fill: rgba(42, 121, 218, 0.12);
        }
        .student-weekly-line {
            fill: none;
            stroke: #1f6fd3;
            stroke-width: 2.4;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .student-weekly-dot {
            fill: #ffffff;
            stroke: #1f6fd3;
            stroke-width: 1.8;
        }
        .student-weekly-dot.is-today {
            fill: #1f6fd3;
        }
        .student-weekly-summaries {
            display: grid;
            gap: 10px;
            align-content: start;
        }
        .student-weekly-summary {
            display: none;
            gap: 10px;
        }
        .student-weekly-summary.is-active {
            display: grid;
        }
        .student-weekly-axis {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }
        .student-weekly-axis div {
            border: 1px solid #dbe6f5;
            border-radius: 14px;
            background: #ffffff;
            padding: 10px;
            display: grid;
            gap: 4px;
            text-align: center;
        }
        .student-weekly-axis div.is-today {
            border-color: #adc8ed;
            background: #edf4ff;
        }
        .student-weekly-axis span {
            color: #6880a4;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .student-weekly-axis strong {
            color: #102849;
            font-size: 13px;
            line-height: 1.3;
        }
        .student-donut-layout {
            display: grid;
            gap: 16px;
            grid-template-columns: minmax(180px, 220px) minmax(0, 1fr);
            align-items: center;
        }
        .student-donut-chart {
            position: relative;
            width: 190px;
            height: 190px;
            margin: 0 auto;
            border-radius: 50%;
        }
        .student-donut-chart::before {
            content: '';
            position: absolute;
            inset: 20px;
            border-radius: 50%;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        .student-donut-inner {
            position: absolute;
            inset: 0;
            display: grid;
            place-content: center;
            text-align: center;
            gap: 4px;
            padding: 0 34px;
            z-index: 1;
        }
        .student-donut-inner strong {
            color: #102849;
            font-size: 34px;
            line-height: 1;
        }
        .student-donut-inner span {
            color: #657890;
            font-size: 12px;
            line-height: 1.55;
        }
        .student-donut-legend {
            display: grid;
            gap: 10px;
        }
        .student-donut-legend-row {
            border: 1px solid #dbe6f5;
            border-radius: 14px;
            background: #ffffff;
            padding: 11px 12px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
        }
        .student-donut-legend-copy {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .student-donut-legend-copy i {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            display: inline-block;
        }
        .student-donut-legend-copy span {
            color: #102849;
            font-size: 13px;
            font-weight: 700;
        }
        .student-donut-legend-row strong {
            color: #315682;
            font-size: 13px;
        }
        .student-radar-shell {
            display: grid;
            place-items: center;
            min-height: 260px;
        }
        .student-radar-shell svg {
            width: min(100%, 360px);
            height: auto;
        }
        .student-radar-ring,
        .student-radar-axis {
            fill: none;
            stroke: #dfe8f6;
            stroke-width: 1;
        }
        .student-radar-target {
            fill: rgba(133, 156, 190, 0.08);
            stroke: #b3c3da;
            stroke-width: 1.2;
            stroke-dasharray: 4 4;
        }
        .student-radar-value {
            fill: rgba(31, 111, 211, 0.18);
            stroke: #1f6fd3;
            stroke-width: 2;
        }
        .student-radar-dot {
            fill: #1f6fd3;
            stroke: #ffffff;
            stroke-width: 1.5;
        }
        .student-radar-label {
            fill: #466385;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .student-radar-legend {
            display: grid;
            gap: 8px;
        }
        .student-radar-legend-row {
            border: 1px solid #dbe6f5;
            border-radius: 12px;
            background: #ffffff;
            padding: 10px 12px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            color: #102849;
            font-size: 13px;
            font-weight: 700;
        }
        .student-heatmap-scroll {
            overflow-x: auto;
            padding-bottom: 4px;
        }
        .student-heatmap-months {
            display: flex;
            gap: 6px;
            color: #6a7b92;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            min-width: max-content;
        }
        .student-heatmap-months span:first-child {
            width: 34px;
            flex: 0 0 34px;
            opacity: 0;
        }
        .student-heatmap-months span:not(:first-child) {
            width: 12px;
            flex: 0 0 12px;
        }
        .student-heatmap-body {
            display: flex;
            gap: 8px;
            align-items: start;
            min-width: max-content;
        }
        .student-heatmap-weekdays {
            display: grid;
            gap: 6px;
            padding-top: 2px;
            flex: 0 0 34px;
        }
        .student-heatmap-weekdays span {
            min-height: 12px;
            color: #6f8199;
            font-size: 10px;
            font-weight: 700;
        }
        .student-heatmap-grid {
            display: flex;
            gap: 6px;
        }
        .student-heatmap-week {
            display: grid;
            gap: 6px;
            width: 12px;
            flex: 0 0 12px;
        }
        .student-heatmap-cell {
            width: 100%;
            min-width: 12px;
            aspect-ratio: 1 / 1;
            border-radius: 4px;
            border: 1px solid #dbe6f5;
            background: #f5f8fd;
        }
        .student-heatmap-cell.level-1 { background: #e9f4ff; border-color: #cde0fb; }
        .student-heatmap-cell.level-2 { background: #d6e9ff; border-color: #b6d2f7; }
        .student-heatmap-cell.level-3 { background: #b6d7ff; border-color: #86b8ef; }
        .student-heatmap-cell.level-4 { background: #1f6fd3; border-color: #1f6fd3; }
        .student-heatmap-cell.is-outside {
            opacity: 0.32;
        }
        .student-heatmap-cell.is-today {
            outline: 2px solid #1f6fd3;
            outline-offset: 1px;
        }
        .student-progress-bar-list {
            display: grid;
            gap: 12px;
        }
        .student-progress-bar-row {
            display: grid;
            gap: 8px;
        }
        .student-progress-bar-headline {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .student-progress-bar-headline strong {
            display: block;
            color: #102849;
            font-size: 14px;
            line-height: 1.3;
        }
        .student-progress-bar-headline span {
            color: #687a95;
            font-size: 12px;
            line-height: 1.5;
        }
        .student-progress-bar-headline em {
            color: #32527f;
            font-size: 12px;
            font-style: normal;
            font-weight: 800;
            white-space: nowrap;
        }
        .student-progress-fill-track {
            height: 12px;
            border-radius: 999px;
            background: #e6eef9;
            overflow: hidden;
        }
        .student-progress-fill-track span {
            display: block;
            width: 0;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #1f6fd3 0%, #63a7ff 100%);
            transition: width 760ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .student-progress-fill-track--quiz span {
            background: linear-gradient(90deg, #1f8d77 0%, #6bd4b1 100%);
        }
        .student-progress-inline-stats--three {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .student-streak-tracker-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(14, minmax(0, 1fr));
        }
        .student-streak-tracker-day {
            border: 1px solid #dbe6f5;
            border-radius: 14px;
            background: #f7faff;
            padding: 10px 8px;
            display: grid;
            gap: 4px;
            justify-items: center;
            text-align: center;
        }
        .student-streak-tracker-day span {
            color: #6f8199;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .student-streak-tracker-day strong {
            color: #102849;
            font-size: 16px;
            line-height: 1;
        }
        .student-streak-tracker-day.is-active {
            background: linear-gradient(180deg, #edf6ff 0%, #dfeeff 100%);
            border-color: #bcd4f4;
            box-shadow: 0 10px 18px rgba(31, 111, 211, 0.1);
        }
        .student-streak-tracker-day.is-today {
            outline: 2px solid #1f6fd3;
            outline-offset: 2px;
        }
        .student-dashboard-side .topic-grid,
        .student-dashboard-side .quick-actions-grid {
            grid-template-columns: 1fr;
        }
        .notification-feed {
            display: grid;
            gap: 12px;
        }
        .notification-summary {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            border: 1px solid #dbe6f5;
            border-radius: 18px;
            padding: 14px 16px;
            background:
                radial-gradient(circle at top right, rgba(42, 121, 218, 0.12), rgba(42, 121, 218, 0) 34%),
                linear-gradient(180deg, #ffffff 0%, #f6faff 100%);
            box-shadow: 0 12px 22px rgba(18, 42, 86, 0.05);
        }
        .notification-summary-copy {
            display: grid;
            gap: 4px;
        }
        .notification-summary-kicker {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 9px;
            background: #edf4ff;
            color: #2f5c96;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .notification-summary strong {
            color: #102849;
            font-size: 18px;
            line-height: 1.2;
        }
        .notification-summary p {
            margin: 0;
            color: #61758f;
            font-size: 13px;
            line-height: 1.6;
        }
        .notification-summary-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            justify-content: flex-end;
        }
        .notification-summary-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 10px;
            background: #eff5ff;
            color: #2f5c96;
            font-size: 11px;
            font-weight: 800;
        }
        .notification-summary-btn,
        .notification-inline-btn {
            border: 1px solid #c7d8f1;
            border-radius: 999px;
            background: #ffffff;
            color: #145fd1;
            font-size: 11px;
            font-weight: 800;
            padding: 6px 10px;
            cursor: pointer;
            transition: 160ms ease;
        }
        .notification-summary-btn:hover,
        .notification-inline-btn:hover {
            border-color: #a9c4ec;
            background: #eef5ff;
            transform: translateY(-1px);
        }
        .notification-list {
            display: grid;
            gap: 12px;
        }
        .notification-card {
            position: relative;
            border: 1px solid #dbe5f4;
            border-radius: 16px;
            background:
                radial-gradient(circle at top right, rgba(65, 124, 217, 0.08), rgba(65, 124, 217, 0) 36%),
                linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
            padding: 14px;
            display: grid;
            gap: 8px;
            box-shadow: 0 12px 24px rgba(18, 42, 86, 0.06);
        }
        .notification-card::before {
            content: '';
            position: absolute;
            inset: 14px auto 14px 0;
            width: 4px;
            border-radius: 999px;
            background: rgba(42, 121, 218, 0.16);
        }
        .notification-card--quiz::before {
            background: linear-gradient(180deg, #2a79da 0%, #5ea5ff 100%);
        }
        .notification-card--submission::before {
            background: linear-gradient(180deg, #c8851a 0%, #f0b257 100%);
        }
        .notification-card--broadcast::before {
            background: linear-gradient(180deg, #2a79da 0%, #39bbae 100%);
        }
        .notification-card--system::before {
            background: linear-gradient(180deg, #66758d 0%, #93a6c0 100%);
        }
        .notification-card--unread::before {
            background: linear-gradient(180deg, #2a79da 0%, #39bbae 100%);
            box-shadow: 0 6px 12px rgba(42, 121, 218, 0.24);
        }
        .notification-card-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .notification-card-lead {
            display: flex;
            gap: 12px;
            align-items: start;
            min-width: 0;
            flex: 1 1 auto;
        }
        .notification-avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-content: center;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.05em;
            flex: 0 0 auto;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
        }
        .notification-avatar--broadcast {
            background: linear-gradient(145deg, #edf4ff 0%, #ddebff 100%);
            color: #245ebc;
        }
        .notification-avatar--quiz {
            background: linear-gradient(145deg, #edf4ff 0%, #d7e9ff 100%);
            color: #245ebc;
        }
        .notification-avatar--submission {
            background: linear-gradient(145deg, #fff5e8 0%, #ffe4bc 100%);
            color: #a96409;
        }
        .notification-avatar--system {
            background: linear-gradient(145deg, #f1f5fb 0%, #dde6f3 100%);
            color: #536a8d;
        }
        .notification-card-copy {
            display: grid;
            gap: 4px;
            min-width: 0;
            flex: 1 1 auto;
        }
        .notification-kicker {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 9px;
            background: #edf4ff;
            color: #2e5c99;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .notification-time {
            color: #6880a4;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }
        .notification-card-side {
            display: grid;
            justify-items: end;
            gap: 8px;
            flex: 0 0 auto;
        }
        .notification-card strong {
            color: #102849;
            font-size: 15px;
            line-height: 1.35;
        }
        .notification-card .muted {
            margin: 0;
            font-size: 13px;
            line-height: 1.65;
        }
        .notification-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .notification-tag {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 9px;
            background: #eff5ff;
            color: #315b92;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .notification-tag--soft {
            background: #f4f7fb;
            color: #6d7d95;
        }
        @media (max-width: 720px) {
            .notification-summary,
            .notification-card-top {
                grid-template-columns: 1fr;
            }
            .notification-summary {
                display: grid;
            }
            .notification-summary-actions {
                justify-content: flex-start;
            }
            .notification-card-top {
                display: grid;
            }
            .notification-card-side {
                justify-items: start;
            }
        }
        .quick-actions-card h2 {
            margin: 0;
            font-size: 22px;
            line-height: 1.15;
        }
        .quick-actions-card p {
            margin: 4px 0 0;
            color: #617089;
            font-size: 13px;
        }
        .quick-actions-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .quick-action-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            border-radius: 12px;
            border: 1px solid #d7deea;
            background: #f8fbff;
            color: #15335c;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }
        .quick-action-link:hover {
            transform: translateY(-1px);
            border-color: #bcd0ef;
            box-shadow: 0 14px 24px rgba(18, 42, 86, 0.08);
        }
        .student-side-card h3 {
            margin: 0 0 10px;
            font-size: 22px;
        }
        .student-side-card .stack {
            display: grid;
            gap: 10px;
        }
        .mini-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #0f59c7;
            background: #edf3ff;
            border-radius: 999px;
            padding: 5px 10px;
            width: fit-content;
        }
        .dash-hero {
            background: linear-gradient(120deg, #0f4dbf 0%, #1d6ed0 100%);
            color: #fff;
            border-radius: 14px;
            padding: 20px 22px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .dash-hero.with-image {
            background-size: cover;
            background-position: center;
        }
        .dash-hero::before { content: none; }
        .dash-hero::after {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.09);
            right: -40px;
            top: -60px;
            z-index: 0;
        }
        .hero-kicker { margin: 0; text-transform: uppercase; letter-spacing: 0.7px; font-size: 11px; opacity: 0.88; }
        .hero-title { margin: 5px 0; font-size: 30px; line-height: 1.08; }
        .hero-meta { margin: 0; font-size: 13px; opacity: 0.92; }
        .hero-sub { margin: 7px 0 0; font-size: 13px; opacity: 0.86; }
        .hero-btn {
            display: inline-block;
            text-decoration: none;
            color: #0f4dbf;
            background: #fff;
            font-weight: 700;
            border-radius: 8px;
            padding: 9px 14px;
            margin-top: 10px;
        }
        .hero-ring {
            width: 98px;
            height: 98px;
            border-radius: 50%;
            border: 7px solid rgba(255, 255, 255, 0.85);
            display: grid;
            place-content: center;
            text-align: center;
            z-index: 1;
        }
        .hero-ring b { font-size: 28px; line-height: 1; }
        .hero-ring span { font-size: 10px; text-transform: uppercase; opacity: 0.78; }
        .section-head { display: flex; justify-content: space-between; align-items: end; gap: 10px; }
        .section-head h2 { margin: 0; font-size: 24px; }
        .section-head p { margin: 4px 0 0; font-size: 13px; color: #617089; }
        .learning-grid { display: grid; gap: 10px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .course-card {
            border: 1px solid #d7deea;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 6px 14px rgba(17, 36, 66, 0.07);
        }
        .course-top {
            color: #fff;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 68px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .course-lock {
            position: absolute;
            right: 10px;
            bottom: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #1f2f48;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
            text-transform: uppercase;
        }
        .course-card.disabled {
            opacity: 0.7;
            filter: grayscale(0.3);
            pointer-events: none;
        }
        .course-top::after {
            content: none;
        }
        .course-top > * { position: relative; z-index: 1; }
        .icon-box {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.25);
            display: grid;
            place-content: center;
            font-size: 11px;
            font-weight: 700;
        }
        .badge { background: rgba(255, 255, 255, 0.28); border-radius: 999px; padding: 3px 8px; font-size: 10px; font-weight: 700; }
        .course-body { padding: 11px; display: grid; gap: 6px; }
        .pill { display: inline-flex; width: fit-content; background: #eef3fb; color: #30496d; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 700; }
        .course-body h3 { margin: 0; font-size: 18px; line-height: 1.2; }
        .course-meta { margin: 0; color: #69758e; font-size: 12px; }
        .bar-track { height: 6px; border-radius: 999px; background: #edf1f6; overflow: hidden; }
        .bar-val { height: 100%; border-radius: inherit; }
        .course-foot { display: flex; justify-content: space-between; font-size: 12px; color: #65748d; }
        .stats-grid { display: grid; gap: 12px; grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .stat-box { background: #fff; border: 1px solid #d7deea; border-radius: 12px; padding: 14px; display: flex; gap: 10px; align-items: center; }
        .stat-icon { width: 34px; height: 34px; border-radius: 9px; background: #edf3ff; color: #1d67d2; display: grid; place-content: center; font-size: 11px; font-weight: 800; }
        .stat-box b { display: block; font-size: 24px; line-height: 1; }
        .stat-box span { color: #5f6c82; font-size: 12px; }
        .split-grid { display: grid; gap: 12px; grid-template-columns: 1fr 1fr; }
        .panel-box { background: #fff; border: 1px solid #d7deea; border-radius: 12px; padding: 14px; }
        .panel-box h3 { margin: 0 0 10px; font-size: 20px; }
        .admin-demo-submission-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            margin-top: 10px;
        }
        .admin-demo-submission-card {
            border: 1px solid #d7deea;
            border-radius: 14px;
            background: #fff;
            padding: 14px;
            display: grid;
            gap: 12px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.05);
        }
        .admin-demo-submission-card strong {
            color: #102849;
            font-size: 17px;
            line-height: 1.3;
        }
        .admin-demo-submission-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            color: #5c6b84;
            font-size: 12px;
        }
        .admin-demo-user-grid {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .admin-demo-user-stat {
            border: 1px solid #dce5f2;
            border-radius: 12px;
            background: #f8fbff;
            padding: 10px;
            display: grid;
            gap: 4px;
        }
        .admin-demo-user-stat span {
            color: #6a7a91;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .admin-demo-user-stat strong {
            font-size: 14px;
            word-break: break-word;
        }
        .admin-demo-rating {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .admin-demo-rating .stars {
            color: #f4b400;
            letter-spacing: 0.05em;
        }
        .admin-demo-rating .score {
            color: #365078;
            font-size: 12px;
            font-weight: 700;
        }
        .admin-demo-answer {
            border-top: 1px dashed #d7deea;
            padding-top: 10px;
            color: #425066;
            font-size: 13px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .skill-row { margin-bottom: 10px; }
        .skill-row:last-child { margin-bottom: 0; }
        .skill-label { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
        .topic-grid { display: grid; gap: 8px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .topic { border: 1px solid #d7deea; border-radius: 10px; padding: 11px; display: flex; gap: 9px; align-items: center; }
        .topic-bullet { width: 30px; height: 30px; border-radius: 8px; background: #eef3fb; color: #2a61b8; display: grid; place-content: center; font-size: 11px; font-weight: 700; }
        .topic p { margin: 0; color: #66758e; font-size: 12px; }
        .recommend-grid { display: grid; gap: 10px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .recommend-card { border: 1px solid #d7deea; border-radius: 12px; overflow: hidden; background: #fff; }
        .recommend-top { color: #fff; padding: 12px; min-height: 66px; display: flex; align-items: center; }
        .recommend-body { padding: 12px; }
        .recommend-body h4 { margin: 6px 0 2px; font-size: 18px; }
        .recommend-meta { margin: 0 0 8px; color: #6e7b93; font-size: 12px; }
        .recommend-foot { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #65748d; }
        .mini-btn { text-decoration: none; background: #0f59c7; color: #fff; border-radius: 7px; padding: 7px 10px; font-size: 12px; font-weight: 700; }
        .panel-inline-hero {
            border: 1px solid #d7deea;
            border-radius: 14px;
            padding: 18px 20px;
            color: #fff;
            background: linear-gradient(120deg, #0f4dbf 0%, #1d6ed0 100%);
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.08);
        }
        .panel-inline-hero h3 { margin: 0 0 4px; font-size: 24px; }
        .panel-inline-hero p { margin: 0; font-size: 13px; opacity: 0.92; }
        .panel-inline-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .panel-inline-kpi {
            border: 1px solid #d7deea;
            border-radius: 10px;
            background: #f8fbff;
            padding: 12px;
        }
        .panel-inline-kpi p { margin: 0; color: #63708a; font-size: 12px; }
        .panel-inline-kpi b { display: block; margin-top: 5px; font-size: 24px; line-height: 1; }
        .accent-blue { background: linear-gradient(115deg, #1f5fcc, #5b92df); }
        .accent-green { background: linear-gradient(115deg, #21a86b, #67c796); }
        .accent-violet { background: linear-gradient(115deg, #7047af, #9c7acb); }
        .accent-orange { background: linear-gradient(115deg, #e17a0c, #f3ac63); }
        .accent-red { background: linear-gradient(115deg, #c94f43, #dd8e87); }
        .accent-teal { background: linear-gradient(115deg, #0c95a7, #4cc0ca); }
        @media (max-width: 1320px) {
            .student-dashboard-columns {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 1180px) {
            .learning-grid, .recommend-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .panel-inline-grid { grid-template-columns: 1fr; }
            .student-focus-grid { grid-template-columns: 1fr; }
            .student-progress-grid { grid-template-columns: 1fr; }
            .student-progress-visual-grid,
            .student-progress-detail-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 900px) {
            .dash-hero { grid-template-columns: 1fr; }
            .split-grid { grid-template-columns: 1fr; }
            .resume-panel-head, .action-queue-head { display: grid; }
            .resume-stat-grid, .queue-summary { grid-template-columns: 1fr; }
            .quick-actions-grid { grid-template-columns: 1fr; }
            .student-completion-layout { grid-template-columns: 1fr; }
            .student-progress-inline-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .student-progress-series-row { grid-template-columns: 1fr; }
            .student-progress-stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .student-weekly-shell,
            .student-donut-layout { grid-template-columns: 1fr; }
            .student-progress-card-head,
            .activity-feed-head { display: grid; }
            .student-progress-toggle-group { justify-content: flex-start; }
            .student-streak-tracker-grid { grid-template-columns: repeat(7, minmax(0, 1fr)); }
        }
        @media (max-width: 640px) {
            .learning-grid, .recommend-grid, .stats-grid, .topic-grid { grid-template-columns: 1fr; }
            .hero-title { font-size: 24px; }
            .resume-copy h2 { font-size: 24px; }
            .student-time-chart { grid-template-columns: repeat(7, minmax(0, 1fr)); }
            .student-quiz-chart { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .student-progress-inline-stats { grid-template-columns: 1fr; }
            .student-streak-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
            .student-progress-stat-grid { grid-template-columns: 1fr; }
            .student-progress-inline-stats--three { grid-template-columns: 1fr; }
            .student-weekly-axis { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            .student-streak-tracker-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
            .student-heatmap-months,
            .student-heatmap-body { min-width: max-content; }
        }
        .demo-grid { display: grid; gap: 12px; }
        .demo-panel {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            animation: demoPanelIn 760ms cubic-bezier(0.2, 0.9, 0.25, 1) both;
        }
        .demo-panel::before {
            content: '';
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, rgba(15, 77, 191, 0.28), rgba(15, 77, 191, 0));
            pointer-events: none;
            z-index: 0;
        }
        .demo-panel::after {
            content: '';
            position: absolute;
            inset: auto -80px -90px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(85, 177, 255, 0.14), rgba(85, 177, 255, 0));
            pointer-events: none;
            z-index: 0;
        }
        .demo-panel > * {
            position: relative;
            z-index: 1;
        }
        .demo-panel--intro { animation-delay: 0.04s; }
        .demo-panel--notify { animation-delay: 0.12s; }
        .demo-panel--tasks { animation-delay: 0.2s; }
        .demo-panel--feature { animation-delay: 0.28s; }
        .demo-panel--reviews { animation-delay: 0.36s; }
        .demo-video-slider {
            display: grid;
            gap: 14px;
            margin-top: 8px;
            position: relative;
            padding: 14px;
            border-radius: 32px;
            border: 1px solid #d7e3f4;
            background:
                radial-gradient(circle at top right, rgba(15, 77, 191, 0.08), rgba(15, 77, 191, 0) 34%),
                linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);
            box-shadow: 0 18px 38px rgba(18, 42, 86, 0.1);
        }
        .demo-video-slider::before,
        .demo-video-slider::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            animation: demoAmbientFloat 7.8s ease-in-out infinite;
        }
        .demo-video-slider::before {
            inset: 16px auto auto -30px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(255, 214, 122, 0.22), rgba(255, 214, 122, 0));
        }
        .demo-video-slider::after {
            inset: auto -20px 24px auto;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, rgba(60, 170, 255, 0.18), rgba(60, 170, 255, 0));
            animation-delay: 1.2s;
        }
        .demo-video-viewport {
            overflow: hidden;
            border-radius: 30px;
            position: relative;
            z-index: 1;
            transition: height 320ms ease;
        }
        .demo-video-track {
            display: flex;
            width: 100%;
            align-items: flex-start;
            transition: transform 380ms ease;
            will-change: transform;
        }
        .demo-video-slide {
            flex: 0 0 100%;
            min-width: 100%;
        }
        .demo-video {
            border: 1px solid var(--line);
            border-radius: 28px;
            background: var(--card);
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(280px, 0.72fr) minmax(540px, 1.48fr);
            gap: 0;
            box-shadow: 0 28px 56px rgba(18, 42, 86, 0.14);
            position: relative;
            width: 100%;
            transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
        }
        .demo-video--feature-reel {
            grid-template-columns: 1fr;
            width: min(430px, 100%);
            margin-inline: auto;
            border-radius: 32px;
        }
        .demo-video:hover {
            transform: translateY(-4px);
            border-color: #b8cdee;
            box-shadow: 0 34px 64px rgba(18, 42, 86, 0.18);
        }
        .demo-video::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 77, 191, 0.08), rgba(15, 77, 191, 0) 45%);
            pointer-events: none;
        }
        .demo-video-cover {
            min-height: 520px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0) 36%),
                linear-gradient(120deg, #0f4dbf 0%, #1d6ed0 55%, #0d3f8f 100%);
            color: #fff;
            padding: 40px 28px;
            display: grid;
            align-content: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
        }
        .demo-video-cover::after {
            content: '';
            position: absolute;
            inset: auto -42px -48px auto;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.10);
        }
        .demo-video-cover::before {
            content: '';
            position: absolute;
            inset: auto auto -18px -18px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0));
            animation: demoFloat 8s ease-in-out infinite;
        }
        .demo-video-cover > * { position: relative; z-index: 1; }
        .demo-video-cover h3 { margin: 0; font-size: clamp(32px, 3.4vw, 40px); line-height: 1.04; max-width: 100%; }
        .demo-video-cover p { margin: 0; line-height: 1.85; font-size: 17px; max-width: 56ch; }
        .demo-video-slide.active .demo-video-cover h3 {
            animation: demoSlideCopyIn 620ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .demo-video-slide.active .demo-video-cover p {
            animation: demoSlideCopyIn 720ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .demo-video-cover .hero-note {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 6px 0 2px;
        }
        .demo-video-cover .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.95);
        }
        .demo-video-cover .btn,
        .demo-video-cover .btn-soft {
            width: fit-content;
            border-color: rgba(255, 255, 255, 0.65);
            background: rgba(255, 255, 255, 0.96);
            color: #0f4dbf;
        }
        .demo-video-cover .hero-play {
            padding: 14px 22px;
            border-radius: 999px;
            box-shadow: 0 12px 26px rgba(4, 20, 56, 0.18);
            transition: transform 200ms ease, box-shadow 200ms ease, background 200ms ease;
        }
        .demo-video-slide.active .demo-video-cover .hero-play {
            animation: demoSlideCopyIn 820ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .demo-video-cover .btn:hover,
        .demo-video-cover .btn-soft:hover {
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 18px 28px rgba(4, 20, 56, 0.2);
        }
        .demo-video-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.92);
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }
        .demo-video-thumb {
            background:
                radial-gradient(circle at 50% 18%, rgba(15, 77, 191, 0.08), rgba(15, 77, 191, 0) 44%),
                #f3f6fb;
            position: relative;
            display: grid;
            align-items: center;
            justify-items: stretch;
            min-height: 520px;
            overflow: hidden;
        }
        .demo-video-thumb--reel {
            min-height: auto;
            padding: 0;
            justify-items: stretch;
            background: #07162f;
        }
        .demo-media-frame {
            width: 100%;
            display: grid;
            background: #07162f;
            overflow: hidden;
        }
        .demo-media-frame--landscape {
            min-height: 100%;
            height: 100%;
        }
        .demo-media-frame--reel {
            width: 100%;
            aspect-ratio: 4 / 5;
            max-width: none;
            margin-inline: 0;
            border-radius: 0;
            box-shadow: none;
        }
        .demo-media-frame video,
        .demo-media-frame iframe {
            width: 100%;
            height: 100%;
            display: block;
            border: 0;
            background: #07162f;
        }
        .demo-media-frame video {
            object-fit: contain;
            object-position: center;
            transition: transform 360ms ease, filter 360ms ease;
        }
        .demo-video-thumb::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(5, 18, 40, 0.10), rgba(5, 18, 40, 0.20)),
                radial-gradient(circle at center, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0) 40%);
            pointer-events: none;
        }
        .demo-video-slide.active .demo-video-thumb {
            animation: demoSlideMediaIn 760ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .demo-video--feature-reel .demo-video-thumb {
            order: 1;
            min-height: auto;
            padding: 0;
        }
        .demo-video--feature-reel .demo-video-cover {
            order: 2;
            min-height: auto;
            padding: 24px 24px 30px;
            gap: 10px;
            justify-items: center;
            text-align: center;
        }
        .demo-video--feature-reel .demo-video-cover h3 {
            font-size: clamp(24px, 2.6vw, 30px);
            line-height: 1.08;
        }
        .demo-video--feature-reel .demo-video-cover p {
            font-size: 15px;
            line-height: 1.7;
            max-width: 34ch;
        }
        .demo-video--feature-reel .demo-video-cover .hero-note {
            justify-content: center;
        }
        .demo-video--feature-reel .demo-media-frame--reel {
            width: 100%;
        }
        .demo-video-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }
        .demo-video-nav-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .demo-video-arrow {
            border: 1px solid #c7d7ef;
            background: #fff;
            color: #173a73;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            display: inline-grid;
            place-content: center;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(18, 42, 86, 0.08);
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }
        .demo-video-arrow:hover {
            transform: translateY(-2px) scale(1.04);
            border-color: #9ebae7;
            box-shadow: 0 14px 24px rgba(18, 42, 86, 0.14);
        }
        .demo-video-dots {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .demo-video-dot {
            border: 0;
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #c9d6e8;
            padding: 0;
            cursor: pointer;
            transition: width 180ms ease, background 180ms ease, transform 180ms ease, box-shadow 180ms ease;
        }
        .demo-video-dot.active {
            width: 28px;
            background: #0f4dbf;
            transform: scaleY(1.05);
            box-shadow: 0 0 0 6px rgba(15, 77, 191, 0.12);
            animation: demoDotPulse 1.9s ease-in-out infinite;
        }
        .demo-video-counter {
            color: #55657d;
            font-size: 13px;
            font-weight: 700;
        }
        .demo-video-empty {
            min-height: 420px;
            display: grid;
            place-content: center;
            text-align: center;
            color: #506179;
            font-size: 14px;
            letter-spacing: 0.03em;
        }
        .upload-empty {
            border: 1px dashed var(--line);
            border-radius: 14px;
            padding: 18px;
            min-height: 220px;
            display: grid;
            align-content: center;
            gap: 8px;
            color: var(--muted);
            line-height: 1.7;
            background:
                linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0)),
                var(--card);
        }
        .demo-empty--media {
            height: 100%;
            display: grid;
            place-content: center;
            text-align: center;
        }
        .demo-review-slider .demo-video-cover {
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0) 36%),
                linear-gradient(120deg, #0b3b7d 0%, #155cc7 52%, #0a8d7f 100%);
        }
        .demo-review-slider .demo-video {
            grid-template-columns: 1fr;
        }
        .demo-review-slider .demo-video--review-reel {
            width: min(430px, 100%);
            margin-inline: auto;
            border-radius: 30px;
        }
        .demo-review-slider .demo-video-badge {
            padding: 3px 8px;
            font-size: 10px;
        }
        .demo-review-slider .demo-video-cover h3 {
            max-width: none;
            font-size: clamp(20px, 1.8vw, 26px);
            line-height: 1.16;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .demo-review-slider .demo-video-cover p {
            font-size: 12px;
            line-height: 1.45;
            max-width: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .demo-review-slider .demo-video-cover {
            order: 2;
            min-height: auto;
            padding: 14px 18px 18px;
            gap: 8px;
        }
        .demo-review-slider .demo-video--review-reel .demo-video-thumb {
            min-height: auto;
            padding: 0;
        }
        .demo-review-slider .demo-video--review-reel .demo-video-cover {
            padding: 18px 20px 24px;
            justify-items: center;
            text-align: center;
            gap: 10px;
        }
        .demo-review-slider .demo-video--review-reel .demo-review-actions {
            justify-content: center;
        }
        .demo-review-slider .demo-video--review-reel .demo-review-actions .hero-note {
            flex: 0 0 auto;
            justify-content: center;
        }
        .demo-review-slider .demo-video--review-reel .demo-video-cover .hero-play {
            margin-left: 0;
        }
        .demo-review-slider .demo-video--review-reel .demo-media-frame--reel {
            width: 100%;
        }
        .demo-review-slider .demo-video-cover .hero-note {
            gap: 5px;
            margin: 0;
        }
        .demo-review-slider .demo-review-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .demo-review-slider .demo-review-actions .hero-note {
            flex: 1 1 240px;
        }
        .demo-review-slider .demo-video-cover .hero-chip {
            padding: 3px 7px;
            font-size: 9px;
        }
        .demo-review-slider .demo-video-cover .hero-play {
            padding: 9px 15px;
            font-size: 12px;
            margin-left: auto;
        }
        .demo-review-slider .demo-video-thumb {
            order: 1;
            min-height: clamp(360px, 46vw, 640px);
        }
        .demo-submission-alert {
            border: 1px solid #cfe5d9;
            border-radius: 16px;
            background: linear-gradient(180deg, #f6fffa 0%, #ffffff 100%);
            padding: 16px;
            display: grid;
            gap: 8px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.05);
            position: relative;
            overflow: hidden;
            animation: demoAlertIn 680ms cubic-bezier(0.2, 0.9, 0.25, 1) both;
        }
        .demo-submission-alert::after {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            background: linear-gradient(180deg, #19a463 0%, #3ec98a 100%);
        }
        .demo-submission-alert strong {
            color: #1c6a45;
            font-size: 17px;
        }
        .demo-submission-alert p {
            margin: 0;
            color: #4e6458;
            font-size: 14px;
            line-height: 1.65;
        }
        .demo-submission-alert--success {
            border-color: #bfe7cd;
            background: linear-gradient(180deg, #f2fff7 0%, #ffffff 100%);
        }
        .demo-submission-alert--success::after {
            background: linear-gradient(180deg, #15a15e 0%, #37cf84 100%);
        }
        .demo-submission-alert-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .demo-submission-alert-check {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(180deg, #15a15e 0%, #2bcf7c 100%);
            box-shadow: 0 10px 20px rgba(21, 161, 94, 0.26);
        }
        .demo-task-card {
            border: 1px solid var(--line);
            border-radius: 16px;
            background:
                linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0)),
                var(--card);
            padding: 14px;
            display: grid;
            gap: 12px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.07);
            position: relative;
            overflow: hidden;
            transition: transform 240ms ease, box-shadow 240ms ease, border-color 240ms ease;
            animation: demoTaskCardIn 720ms cubic-bezier(0.2, 0.9, 0.25, 1) both;
        }
        .demo-task-shell {
            display: grid;
            gap: 14px;
            grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
            align-items: stretch;
            position: relative;
            z-index: 1;
        }
        .demo-task-shell--landscape {
            grid-template-columns: minmax(360px, 1.15fr) minmax(0, 1fr);
        }
        .demo-task-shell.no-video {
            grid-template-columns: 1fr;
        }
        .demo-task-media-col {
            display: flex;
            min-width: 0;
        }
        .demo-task-media-stack {
            display: grid;
            gap: 12px;
            width: 100%;
            min-width: 0;
            align-content: start;
        }
        .demo-task-form-col {
            min-width: 0;
            display: flex;
        }
        .demo-grid > .demo-task-card:nth-child(1) { animation-delay: 0.08s; }
        .demo-grid > .demo-task-card:nth-child(2) { animation-delay: 0.16s; }
        .demo-grid > .demo-task-card:nth-child(3) { animation-delay: 0.24s; }
        .demo-grid > .demo-task-card:nth-child(4) { animation-delay: 0.32s; }
        .demo-grid > .demo-task-card:nth-child(5) { animation-delay: 0.4s; }
        .demo-grid > .demo-task-card:nth-child(6) { animation-delay: 0.48s; }
        .demo-task-card::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 100%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0) 28%, rgba(255, 255, 255, 0.34) 48%, rgba(255, 255, 255, 0) 68%);
            transform: translateX(-130%);
            transition: transform 720ms ease;
            pointer-events: none;
        }
        .demo-task-video {
            border-radius: 14px;
            border: 1px solid #d7deea;
            background: linear-gradient(180deg, rgba(8, 21, 44, 0.95), rgba(8, 21, 44, 0.84));
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.04);
            width: 100%;
            min-height: 560px;
            flex: 1;
            display: grid;
            grid-template-rows: auto 1fr;
        }
        .demo-task-video--landscape {
            min-height: auto;
        }
        .demo-task-video-frame {
            width: 100%;
            height: 100%;
            min-height: 0;
            margin: 0 auto;
            border-radius: 0px 0px 12px 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 14px 26px rgba(0, 0, 0, 0.32);
            background: #050d1b;
            position: relative;
        }
        .demo-task-video-frame--landscape {
            aspect-ratio: 16 / 9;
            max-width: 100%;
        }
        .demo-task-video-frame::after {
            content: '';
            position: absolute;
            inset: auto 0 0 0;
            height: 36%;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.34), rgba(0, 0, 0, 0));
            pointer-events: none;
        }
        .demo-task-video video {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
            background: #08152c;
        }
        .demo-task-video-frame--landscape video {
            object-fit: contain;
        }
        .demo-task-video-note {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #244a86;
            background: #eef4ff;
            border: 1px solid #d7e2f5;
            border-radius: 12px 12px 0px 0px;
            text-align: center;
        }
        .demo-task-copy,
        .demo-task-top {
            display: grid;
            gap: 6px;
        }
        .demo-task-copy strong,
        .demo-task-top strong {
            font-size: 17px;
            line-height: 1.3;
            color: #13335f;
        }
        .demo-task-card:hover {
            transform: translateY(-6px);
            border-color: #b9cdee;
            box-shadow: 0 18px 36px rgba(18, 42, 86, 0.14);
        }
        .demo-task-card:hover::before {
            transform: translateX(130%);
        }
        .demo-task-meta { color: var(--muted); font-size: 13px; line-height: 1.55; }
        .demo-task-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .demo-task-actions--media { margin-top: 2px; }
        .demo-task-actions .btn-soft {
            border-color: #cbdaf3;
            background: #f4f8ff;
            color: #1f4fa3;
            transition: transform 180ms ease, box-shadow 180ms ease, background 180ms ease, border-color 180ms ease;
        }
        .demo-task-actions .btn-soft:hover {
            background: #eef4ff;
            transform: translateY(-2px);
            border-color: #b4cbef;
            box-shadow: 0 12px 22px rgba(18, 42, 86, 0.08);
        }
        .demo-submit-panel {
            border: 1px solid #d7deea;
            border-radius: 14px;
            background: #fff;
            padding: 12px;
            display: grid;
            gap: 10px;
            min-height: 560px;
            align-content: start;
            flex: 1;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.06);
            transition: transform 220ms ease, box-shadow 220ms ease, border-color 220ms ease;
        }
        .demo-submit-panel:hover {
            transform: translateY(-2px);
            border-color: #bfd0eb;
            box-shadow: 0 16px 28px rgba(18, 42, 86, 0.1);
        }
        .demo-submit-note {
            margin: 0;
            color: #576884;
            font-size: 12px;
            line-height: 1.6;
        }
        .demo-submit-grid {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .demo-submit-field {
            display: grid;
            gap: 6px;
        }
        .demo-submit-field label {
            color: #6c7c94;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .demo-submit-block {
            border: 1px solid #dce4f1;
            border-radius: 12px;
            background: #f8fbff;
            padding: 10px;
            display: grid;
            gap: 8px;
        }
        .demo-submit-block h4 {
            margin: 0;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #6c7c94;
        }
        .demo-rating-block {
            background: linear-gradient(180deg, rgba(15, 77, 191, 0.06), rgba(15, 77, 191, 0.02));
        }
        .demo-task-rating-panel {
            padding: 12px 14px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.06);
        }
        .demo-task-rating-panel h4 {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.55;
        }
        .demo-rating-input {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            align-items: stretch;
        }
        .demo-rating-input input[type="radio"] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }
        .demo-rating-input label {
            position: relative;
            min-height: 66px;
            padding: 8px 6px 7px;
            border: 1px solid #d6e0f0;
            border-radius: 15px;
            background:
                radial-gradient(circle at 50% 18%, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0) 44%),
                linear-gradient(180deg, #ffffff, #f7faff);
            color: #73839f;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            overflow: hidden;
            transition: color 180ms ease, transform 180ms ease, border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }
        .demo-rating-input label::before {
            content: '';
            position: absolute;
            inset: auto 12px 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
            opacity: 0.08;
            filter: blur(10px);
            transition: opacity 180ms ease;
        }
        .demo-rating-input label svg {
            width: 28px;
            height: 28px;
            stroke: currentColor;
            stroke-width: 1.9;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            position: relative;
            z-index: 1;
        }
        .demo-rating-input label .face-fill,
        .demo-rating-input label .face-eye {
            stroke: none;
            fill: currentColor;
        }
        .demo-rating-input label .face-fill {
            opacity: 0.12;
        }
        .demo-rating-text {
            position: relative;
            z-index: 1;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.05em;
            line-height: 1.2;
            text-transform: uppercase;
            text-align: center;
        }
        .demo-rating-input label:hover,
        .demo-rating-input input[type="radio"]:checked + label {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 16px 24px rgba(18, 42, 86, 0.14);
        }
        .demo-rating-input label:hover::before,
        .demo-rating-input input[type="radio"]:checked + label::before {
            opacity: 0.18;
        }
        .demo-rating-input label.demo-rating-smiley--1:hover,
        .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--1 {
            border-color: #efb1b1;
            background:
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, #fff7f7, #ffecec);
            color: #c45353;
        }
        .demo-rating-input label.demo-rating-smiley--2:hover,
        .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--2 {
            border-color: #efc4a0;
            background:
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, #fff9f2, #ffefdf);
            color: #cf7b2d;
        }
        .demo-rating-input label.demo-rating-smiley--3:hover,
        .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--3 {
            border-color: #e6d5a5;
            background:
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, #fffdf5, #fff6dc);
            color: #b18918;
        }
        .demo-rating-input label.demo-rating-smiley--4:hover,
        .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--4 {
            border-color: #b7d8d2;
            background:
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, #f5fffc, #e6faf4);
            color: #228571;
        }
        .demo-rating-input label.demo-rating-smiley--5:hover,
        .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--5 {
            border-color: #b9d9b5;
            background:
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0) 42%),
                linear-gradient(180deg, #f7fff5, #e8f8e3);
            color: #3d8c39;
        }
        .demo-rating-input input[type="radio"]:focus-visible + label {
            outline: 2px solid #7ea9ea;
            outline-offset: 3px;
            border-radius: 15px;
        }
        .demo-rating-hint {
            color: #5f7190;
            font-size: 12px;
            line-height: 1.6;
        }
        @media (max-width: 620px) {
            .demo-rating-input {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (max-width: 420px) {
            .demo-rating-input {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .demo-submit-field input,
        .demo-submit-block textarea,
        .demo-submit-block input[type="file"] {
            width: 100%;
            border: 1px solid #cad9ef;
            border-radius: 10px;
            background: #fff;
            color: #16304f;
            padding: 11px 12px;
            transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
        }
        .demo-submit-field input::placeholder,
        .demo-submit-block textarea::placeholder {
            color: #8a99af;
        }
        .demo-submit-field input:focus,
        .demo-submit-block textarea:focus,
        .demo-submit-block input[type="file"]:focus {
            outline: none;
            border-color: #7da8e8;
            box-shadow: 0 0 0 4px rgba(28, 95, 202, 0.12);
            transform: translateY(-1px);
        }
        .demo-task-reset-note {
            border-radius: 12px;
            border: 1px dashed #c8d7f1;
            background: #f6f9ff;
            padding: 10px 12px;
            color: #4f6485;
            font-size: 12px;
            line-height: 1.6;
        }
        .demo-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #1f4fa3;
            background: #eef4ff;
            border: 1px solid #c8d8f5;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(9, 20, 40, 0.58);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 70;
        }
        .modal-overlay.open { display: flex; }
        .modal-overlay.open .modal.modal-demo-submit {
            animation: demoSubmitModalIn 520ms cubic-bezier(0.2, 0.9, 0.25, 1);
        }
        .modal {
            width: min(540px, 100%);
            border-radius: 22px;
            background: #fff;
            border: 1px solid #d6e0ee;
            box-shadow: 0 24px 58px rgba(10, 28, 56, 0.28);
            overflow: hidden;
        }
        .modal.modal-demo-submit {
            width: min(620px, 100%);
            border: 1px solid #c8d9f2;
            background:
                radial-gradient(circle at top right, rgba(29, 110, 208, 0.14), rgba(29, 110, 208, 0) 34%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 30px 70px rgba(10, 28, 56, 0.34);
        }
        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid #e2e9f4;
        }
        .modal.modal-demo-submit .modal-head {
            padding: 18px 22px;
            background: rgba(255, 255, 255, 0.72);
        }
        .modal-head h3 {
            margin: 0;
            font-size: 22px;
            color: #102849;
        }
        .modal-close {
            width: 38px;
            height: 38px;
            border: 1px solid #d6dfed;
            border-radius: 999px;
            background: #f8fbff;
            color: #21457f;
            cursor: pointer;
        }
        .modal-close:hover {
            background: #eef4ff;
            border-color: #bcd0ee;
        }
        .modal-body {
            padding: 18px;
            display: grid;
            gap: 14px;
        }
        .modal.modal-demo-submit .modal-body {
            padding: 22px;
        }
        .demo-submit-success {
            display: grid;
            gap: 18px;
        }
        .demo-submit-success-hero {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 16px;
            align-items: start;
            padding: 18px;
            border-radius: 18px;
            border: 1px solid #d7e4f5;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(38, 175, 121, 0.12), rgba(38, 175, 121, 0) 34%),
                linear-gradient(180deg, #f8fffb 0%, #ffffff 100%);
        }
        .demo-submit-success-hero::after {
            content: '';
            position: absolute;
            inset: -40% auto auto -20%;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 215, 128, 0.24), rgba(255, 215, 128, 0));
            animation: demoSubmitGlow 4.8s ease-in-out infinite;
            pointer-events: none;
        }
        .demo-submit-confetti {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .demo-submit-confetti-piece {
            position: absolute;
            top: -18px;
            width: 10px;
            height: 18px;
            border-radius: 999px;
            opacity: 0;
            transform: translate3d(0, -16px, 0) rotate(0deg);
            animation: demoConfettiDrop 2.8s ease-in-out infinite;
        }
        .demo-submit-confetti-piece:nth-child(1) {
            left: 10%;
            background: linear-gradient(180deg, #ffd36b, #ff9f43);
            animation-delay: 0.08s;
        }
        .demo-submit-confetti-piece:nth-child(2) {
            left: 22%;
            background: linear-gradient(180deg, #57d6ff, #2b86ff);
            animation-delay: 0.34s;
        }
        .demo-submit-confetti-piece:nth-child(3) {
            left: 38%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #17c784;
            animation-delay: 0.18s;
        }
        .demo-submit-confetti-piece:nth-child(4) {
            left: 56%;
            background: linear-gradient(180deg, #7af0d2, #1dbf9f);
            animation-delay: 0.46s;
        }
        .demo-submit-confetti-piece:nth-child(5) {
            left: 72%;
            width: 12px;
            background: linear-gradient(180deg, #ff89bf, #ff5d8f);
            animation-delay: 0.26s;
        }
        .demo-submit-confetti-piece:nth-child(6) {
            left: 86%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #8d7bff;
            animation-delay: 0.58s;
        }
        .demo-submit-success-check {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: grid;
            place-content: center;
            background: linear-gradient(135deg, #18a56a 0%, #0d7d53 100%);
            box-shadow: 0 14px 28px rgba(24, 165, 106, 0.22);
            position: relative;
            z-index: 1;
            animation: demoSuccessBadgePop 780ms cubic-bezier(0.2, 0.95, 0.3, 1), demoSuccessBadgePulse 2.6s ease-in-out 820ms infinite;
        }
        .demo-submit-success-check svg {
            width: 28px;
            height: 28px;
            stroke: #ffffff;
        }
        .demo-submit-success-copy {
            display: grid;
            gap: 8px;
            position: relative;
            z-index: 1;
        }
        .demo-submit-success-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 6px 12px;
            background: #edf7ff;
            color: #1656b6;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            animation: demoSuccessBadgeSlide 620ms cubic-bezier(0.2, 0.85, 0.25, 1) 120ms both;
        }
        .demo-submit-success-copy strong {
            font-size: 28px;
            color: #12315d;
            line-height: 1.3;
            animation: demoSuccessCopyRise 680ms cubic-bezier(0.2, 0.85, 0.25, 1) 180ms both;
        }
        .demo-submit-success-copy p {
            margin: 0;
            color: #526580;
            line-height: 1.7;
            font-size: 14px;
            animation: demoSuccessCopyRise 720ms cubic-bezier(0.2, 0.85, 0.25, 1) 280ms both;
        }
        .demo-submit-success-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
        .demo-submit-success-stat {
            border: 1px solid #d7e2f2;
            border-radius: 16px;
            background:
                linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0)),
                #ffffff;
            padding: 14px;
            display: grid;
            gap: 6px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.06);
            animation: demoSuccessStatRise 700ms cubic-bezier(0.2, 0.85, 0.25, 1) both;
        }
        .demo-submit-success-stat:nth-child(1) { animation-delay: 0.24s; }
        .demo-submit-success-stat:nth-child(2) { animation-delay: 0.32s; }
        .demo-submit-success-stat:nth-child(3) { animation-delay: 0.4s; }
        .demo-submit-success-stat span {
            color: #6c7c94;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .demo-submit-success-stat strong {
            color: #12315d;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
        }
        @keyframes demoSubmitModalIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.94);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        @keyframes demoSuccessBadgePop {
            0% {
                transform: scale(0.72) rotate(-12deg);
                opacity: 0;
            }
            70% {
                transform: scale(1.08) rotate(4deg);
                opacity: 1;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }
        @keyframes demoSuccessBadgePulse {
            0%, 100% {
                box-shadow: 0 14px 28px rgba(24, 165, 106, 0.22);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 18px 36px rgba(24, 165, 106, 0.28);
                transform: scale(1.04);
            }
        }
        @keyframes demoSuccessBadgeSlide {
            0% {
                opacity: 0;
                transform: translateY(12px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoSuccessCopyRise {
            0% {
                opacity: 0;
                transform: translateY(16px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoSuccessStatRise {
            0% {
                opacity: 0;
                transform: translateY(18px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoConfettiDrop {
            0% {
                opacity: 0;
                transform: translate3d(0, -18px, 0) rotate(0deg);
            }
            12% {
                opacity: 1;
            }
            60% {
                opacity: 1;
                transform: translate3d(12px, 66px, 0) rotate(160deg);
            }
            100% {
                opacity: 0;
                transform: translate3d(-8px, 132px, 0) rotate(260deg);
            }
        }
        @keyframes demoSubmitGlow {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: 0.75;
            }
            50% {
                transform: translate3d(20px, 14px, 0) scale(1.08);
                opacity: 1;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .modal-overlay.open .modal.modal-demo-submit,
            .demo-submit-success-check,
            .demo-submit-success-badge,
            .demo-submit-success-copy strong,
            .demo-submit-success-copy p,
            .demo-submit-success-stat,
            .demo-submit-confetti-piece,
            .demo-submit-success-hero::after {
                animation: none !important;
            }
        }
        .submission-grid {
            display: grid;
            gap: 10px;
        }
        .activity-feed-grid {
            display: grid;
            gap: 12px;
        }
        .activity-feed-card {
            border: 1px solid #d7e2f3;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
            padding: 14px;
            display: grid;
            gap: 10px;
            box-shadow: 0 12px 22px rgba(18, 42, 86, 0.05);
        }
        .activity-feed-card--completed {
            border-color: #cfe5d9;
            background: linear-gradient(180deg, #f7fffb 0%, #ffffff 100%);
        }
        .activity-feed-card--pending {
            border-color: #d7e2f3;
        }
        .activity-feed-card--revision {
            border-color: #f0d7b8;
            background: linear-gradient(180deg, #fffaf3 0%, #ffffff 100%);
        }
        .activity-feed-card--certificate {
            border-color: #cfe2fb;
            background: linear-gradient(180deg, #f5f9ff 0%, #ffffff 100%);
        }
        .activity-feed-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: start;
        }
        .activity-feed-copy {
            display: grid;
            gap: 4px;
            min-width: 0;
        }
        .activity-feed-topline {
            color: #6880a4;
            font-size: 11px;
            font-weight: 700;
        }
        .activity-feed-copy strong {
            color: #102849;
            font-size: 16px;
            line-height: 1.3;
        }
        .activity-feed-copy p {
            margin: 0;
            color: #5f728d;
            font-size: 13px;
            line-height: 1.6;
        }
        .activity-feed-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: flex-end;
        }
        .activity-feed-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .activity-feed-badge--completed {
            background: #ebf8f1;
            color: #1f7d5c;
        }
        .activity-feed-badge--pending {
            background: #edf4ff;
            color: #1d56b3;
        }
        .activity-feed-badge--revision {
            background: #fff3e4;
            color: #a86112;
        }
        .activity-feed-badge--certificate {
            background: #eef5ff;
            color: #245ebc;
        }
        .activity-feed-meta {
            color: #6a7a92;
            font-size: 12px;
            line-height: 1.6;
        }
        .submission-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--card);
            padding: 12px;
            box-shadow: 0 10px 22px rgba(18, 42, 86, 0.06);
            display: grid;
            gap: 8px;
        }
        .submission-head {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            align-items: start;
            flex-wrap: wrap;
        }
        .submission-head-left {
            display: grid;
            gap: 4px;
        }
        .submission-head strong {
            display: block;
            font-size: 15px;
        }
        .submission-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .submission-answer {
            white-space: pre-wrap;
            font-size: 13px;
            line-height: 1.55;
        }
        .submission-answer-box {
            border: 1px solid #dce4f1;
            border-radius: 12px;
            background: #f8fbff;
            padding: 10px;
        }
        .submission-answer-box h4,
        .submission-doc-box h4 {
            margin: 0 0 6px;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #6c7c94;
        }
        .submission-doc-box {
            border: 1px solid #dce4f1;
            border-radius: 12px;
            background: #fff;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: space-between;
            align-items: center;
        }
        .submission-doc-box .doc-name {
            color: #425066;
            font-size: 13px;
            word-break: break-word;
        }
        .submission-meta {
            color: var(--muted);
            font-size: 12px;
        }
        .submission-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .tab-row { display: flex; gap: 12px; flex-wrap: wrap; }
        .tab-row.centered { justify-content: center; }
        .tab-row .tab-btn { transition: 180ms ease; }
        .tab-row .tab-btn:hover { transform: translateY(-1px); border-color: #b6c7e8; }
        .tab-btn {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            background: var(--card);
            color: var(--text);
            cursor: pointer;
        }
        .tab-btn.active {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: #bcd3f7;
            box-shadow: 0 10px 20px rgba(28, 95, 202, 0.18);
        }
        .tab-btn.main-tab {
            padding: 8px 16px;
            font-size: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            border: 1px solid #c6d4ee;
            background: #fff;
        }
        .tab-btn.main-tab.active {
            background: linear-gradient(135deg, rgba(28, 95, 202, 0.12), rgba(73, 142, 255, 0.08));
            border-color: #9cbcf4;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; animation: fadeUp 240ms ease; }
        .subtab-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; margin-bottom: 10px; justify-content: center; }
        .subtab-btn {
            border: 1px dashed #c7d4ea;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            background: #fff;
            color: var(--text);
            cursor: pointer;
            transition: 160ms ease;
        }
        .subtab-btn:hover { transform: translateY(-1px); }
        .subtab-btn.active {
            border-style: solid;
            border-color: #bcd3f7;
            background: #eef4ff;
            color: #1f4fa3;
        }
        .subtab-label {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #7a8aa6;
            margin-top: 6px;
        }
        .demo-course-grid { display: grid; gap: 18px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .demo-course-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 360px));
            justify-content: center;
            justify-items: center;
            max-width: 1480px;
            margin: 0 auto;
        }
        .demo-course-tile {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: var(--card);
            box-shadow: var(--shadow);
            color: inherit;
            width: 100%;
            max-width: 360px;
        }
        .tab-panel {
            padding-top: 12px;
        }
        .demo-course-top {
            min-height: 220px;
            padding: 18px;
            color: #fff;
            display: flex;
            align-items: end;
            background-size: cover;
            background-position: center;
        }
        .demo-course-body { padding: 16px 18px 18px; display: grid; gap: 10px; }
        .badge-lock {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #7a879b;
            background: #f1f4f9;
            border-radius: 999px;
            padding: 4px 8px;
        }
        .demo-empty {
            border: 1px dashed var(--line);
            border-radius: 14px;
            padding: 16px;
            color: var(--muted);
            background: linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0));
            animation: demoEmptyPulse 4.4s ease-in-out infinite;
        }
        .demo-section-title {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 10px;
            margin-bottom: 14px;
        }
        .demo-section-title > div {
            position: relative;
        }
        .demo-section-title > div::after {
            content: '';
            display: block;
            width: 84px;
            height: 4px;
            margin-top: 10px;
            border-radius: 999px;
            background: linear-gradient(90deg, #0f4dbf 0%, #2eb79b 100%);
            box-shadow: 0 8px 18px rgba(15, 77, 191, 0.16);
        }
        .demo-section-title h2 {
            margin: 0;
            font-size: 24px;
        }
        .demo-section-title p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 13px;
        }
        @keyframes demoPanelIn {
            0% {
                opacity: 0;
                transform: translateY(18px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoTaskCardIn {
            0% {
                opacity: 0;
                transform: translateY(22px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        @keyframes demoAlertIn {
            0% {
                opacity: 0;
                transform: translateY(14px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoAmbientFloat {
            0%, 100% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: 1;
            }
            50% {
                transform: translate3d(12px, -10px, 0) scale(1.06);
                opacity: 0.82;
            }
        }
        @keyframes demoDotPulse {
            0%, 100% {
                box-shadow: 0 0 0 6px rgba(15, 77, 191, 0.12);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(15, 77, 191, 0.05);
            }
        }
        @keyframes demoSlideCopyIn {
            0% {
                opacity: 0;
                transform: translateY(18px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes demoSlideMediaIn {
            0% {
                opacity: 0;
                transform: translateX(18px) scale(0.985);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        @keyframes demoEmptyPulse {
            0%, 100% {
                box-shadow: inset 0 0 0 0 rgba(15, 77, 191, 0.04);
            }
            50% {
                box-shadow: inset 0 0 0 1px rgba(15, 77, 191, 0.08);
            }
        }
        html[data-theme="dark"] .dash-grid :is(
            [class$="-card"],
            [class*="-card "],
            [class$="-panel"],
            [class*="-panel "],
            [class$="-box"],
            [class*="-box "],
            [class$="-stat"],
            [class*="-stat "],
            [class$="-tile"],
            [class*="-tile "]
        ) {
            background: linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 98%, var(--primary-soft) 2%)) !important;
            border-color: color-mix(in srgb, var(--line) 86%, #6e9be0 14%) !important;
            color: var(--text);
            box-shadow: 0 18px 34px rgba(0, 0, 0, 0.24);
        }
        html[data-theme="dark"] .dash-grid :is(
            .resume-copy h2,
            .resume-stat strong,
            .action-queue-head h3,
            .queue-summary-box strong,
            .stat-box strong,
            .panel-box h3,
            .recommend-card h3,
            .course-body h3,
            .demo-section-title h2
        ) {
            color: var(--text) !important;
        }
        html[data-theme="dark"] .dash-grid :is(
            .resume-note,
            .action-queue-head p,
            .queue-summary-box span,
            .resume-stat span,
            .course-foot,
            .course-meta,
            .recommend-foot,
            .demo-section-title p,
            .muted
        ) {
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .dash-grid :is(
            .pill,
            .focus-pill
        ) {
            background: color-mix(in srgb, var(--primary-soft) 82%, #152642 18%) !important;
            border-color: color-mix(in srgb, var(--primary) 36%, var(--line) 64%) !important;
            color: var(--primary) !important;
        }
        html[data-theme="dark"] .dash-grid .student-mode .dash-hero,
        html[data-theme="dark"] .dash-grid .dash-hero {
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.32);
        }
        html[data-theme="dark"] .dash-grid .demo-section-title > div::after {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3);
        }
        html[data-theme="dark"] .dash-grid :is(
            .resume-stat,
            .queue-summary-box,
            .queue-item,
            .submission-empty,
            .certificate-card,
            .dashboard-section,
            .student-progress-dashboard,
            .student-progress-card,
            .notification-card,
            .quick-action-link,
            .stat-box,
            .admin-demo-user-stat,
            .topic,
            .recommend-card,
            .panel-inline-kpi,
            .activity-feed-card,
            .submission-answer-box,
            .submission-doc-box,
            .demo-submit-panel,
            .demo-submit-block,
            .demo-task-reset-note,
            .demo-submit-success-stat
        ) {
            border-color: color-mix(in srgb, var(--line) 84%, #6e9be0 16%) !important;
            background: linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 98%, var(--primary-soft) 2%)) !important;
            color: var(--text);
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.22);
        }
        html[data-theme="dark"] .dash-grid :is(
            .resume-stat span,
            .resume-note,
            .action-queue-head p,
            .queue-summary-box span,
            .queue-item p,
            .queue-meta,
            .submission-empty,
            .certificate-meta,
            .certificate-code,
            .dashboard-section .section-head p,
            .notification-time,
            .notification-card .muted,
            .quick-actions-card p,
            .section-head p,
            .course-meta,
            .course-foot,
            .stat-box span,
            .admin-demo-submission-meta,
            .admin-demo-user-stat span,
            .admin-demo-rating .score,
            .skill-label,
            .topic p,
            .recommend-meta,
            .recommend-foot,
            .panel-inline-kpi p,
            .activity-feed-topline,
            .activity-feed-copy p,
            .activity-feed-meta,
            .submission-answer-box h4,
            .submission-doc-box h4,
            .submission-meta,
            .demo-task-video-note,
            .demo-task-meta,
            .demo-submit-note,
            .demo-submit-field label,
            .demo-submit-block h4,
            .demo-rating-hint,
            .demo-task-reset-note,
            .demo-submit-success-copy p,
            .demo-submit-success-stat span
        ) {
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .dash-grid :is(
            .queue-item strong,
            .certificate-card h4,
            .notification-card strong,
            .quick-action-link,
            .stat-box b,
            .admin-demo-submission-card strong,
            .admin-demo-user-stat strong,
            .admin-demo-answer,
            .recommend-body h4,
            .panel-inline-kpi b,
            .activity-feed-copy strong,
            .submission-head strong,
            .submission-answer,
            .submission-doc-box .doc-name,
            .demo-submit-success-copy strong,
            .demo-submit-success-stat strong
        ) {
            color: var(--text) !important;
        }
        html[data-theme="dark"] .dash-grid :is(
            .notification-kicker,
            .notification-tag,
            .mini-cta,
            .student-column-label,
            .demo-submit-success-badge,
            .demo-status
        ) {
            background: color-mix(in srgb, var(--primary-soft) 80%, #152642 20%) !important;
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line) 66%) !important;
            color: #dfeaff !important;
        }
        html[data-theme="dark"] .dash-grid .notification-tag--soft {
            background: color-mix(in srgb, var(--field-bg) 88%, #101a2d 12%) !important;
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .dash-grid .quick-action-link:hover,
        html[data-theme="dark"] .dash-grid .demo-submit-panel:hover {
            border-color: color-mix(in srgb, var(--primary) 36%, var(--line) 64%) !important;
            box-shadow: 0 18px 32px rgba(0, 0, 0, 0.28);
        }
        html[data-theme="dark"] .dash-grid .queue-list::-webkit-scrollbar-thumb {
            background: color-mix(in srgb, var(--line) 78%, #6e92c8 22%);
        }
        html[data-theme="dark"] .dash-grid :is(
            .focus-pill--task,
            .queue-tag--task,
            .activity-feed-badge--revision
        ) {
            background: rgba(255, 191, 102, 0.16) !important;
            border-color: rgba(255, 191, 102, 0.32) !important;
            color: #ffc88a !important;
        }
        html[data-theme="dark"] .dash-grid :is(
            .focus-pill--quiz,
            .queue-tag--quiz,
            .activity-feed-badge--pending,
            .activity-feed-badge--certificate
        ) {
            background: rgba(120, 175, 255, 0.16) !important;
            border-color: rgba(120, 175, 255, 0.3) !important;
            color: #d4e4ff !important;
        }
        html[data-theme="dark"] .dash-grid .activity-feed-badge--completed {
            background: rgba(74, 196, 136, 0.16) !important;
            border-color: rgba(74, 196, 136, 0.3) !important;
            color: #9de1be !important;
        }
        html[data-theme="dark"] :is(
            .modal.modal-demo-submit,
            .modal,
            .demo-submit-panel,
            .demo-submit-block,
            .demo-task-rating-panel,
            .demo-task-reset-note,
            .demo-submit-success-stat
        ) {
            border-color: color-mix(in srgb, var(--line) 82%, #6e9be0 18%) !important;
            background:
                radial-gradient(circle at top right, rgba(120, 175, 255, 0.12), rgba(120, 175, 255, 0) 36%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 88%, var(--primary-soft) 12%)) !important;
            box-shadow: 0 28px 60px rgba(0, 0, 0, 0.42);
        }
        html[data-theme="dark"] :is(
            .demo-submit-note,
            .demo-submit-field label,
            .demo-submit-block h4,
            .demo-rating-hint,
            .demo-task-reset-note,
            .demo-submit-success-copy p,
            .demo-submit-success-stat span
        ) {
            color: var(--muted) !important;
        }
        html[data-theme="dark"] :is(
            .modal.modal-demo-submit .modal-head,
            .modal .modal-head
        ) {
            border-bottom-color: var(--line) !important;
            background: rgba(17, 30, 50, 0.72) !important;
        }
        html[data-theme="dark"] :is(
            .modal-head h3,
            .demo-submit-success-copy strong,
            .demo-submit-success-stat strong,
            .demo-task-copy strong,
            .demo-task-top strong
        ) {
            color: var(--text) !important;
        }
        html[data-theme="dark"] :is(
            .demo-submit-field input,
            .demo-submit-block textarea,
            .demo-submit-block input[type="file"],
            .modal-close
        ) {
            background: color-mix(in srgb, var(--field-bg) 88%, #101a2d 12%) !important;
            border-color: var(--field-border) !important;
            color: var(--text) !important;
        }
        html[data-theme="dark"] :is(
            .demo-submit-field input::placeholder,
            .demo-submit-block textarea::placeholder
        ) {
            color: color-mix(in srgb, var(--muted) 76%, transparent) !important;
        }
        html[data-theme="dark"] .modal-close:hover {
            background: color-mix(in srgb, var(--primary-soft) 44%, var(--card) 56%) !important;
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line) 66%) !important;
        }
        html[data-theme="dark"] .demo-submit-success-hero {
            border-color: color-mix(in srgb, var(--line) 82%, rgba(74, 196, 136, 0.24) 18%) !important;
            background:
                radial-gradient(circle at top left, rgba(74, 196, 136, 0.1), rgba(74, 196, 136, 0) 34%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 88%, var(--primary-soft) 12%)) !important;
        }
        html[data-theme="dark"] :is(
            .demo-video-slider,
            .demo-video,
            .upload-empty
        ) {
            border-color: color-mix(in srgb, var(--line) 84%, #6e9be0 16%) !important;
            background:
                radial-gradient(circle at top right, rgba(120, 175, 255, 0.1), rgba(120, 175, 255, 0) 36%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 88%, var(--primary-soft) 12%)) !important;
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.28);
        }
        html[data-theme="dark"] .demo-video:hover {
            border-color: color-mix(in srgb, var(--primary) 36%, var(--line) 64%) !important;
            box-shadow: 0 30px 56px rgba(0, 0, 0, 0.34);
        }
        html[data-theme="dark"] :is(
            .demo-video-thumb,
            .demo-video-empty
        ) {
            background:
                radial-gradient(circle at 50% 18%, rgba(120, 175, 255, 0.12), rgba(120, 175, 255, 0) 44%),
                #091427 !important;
            color: var(--muted) !important;
        }
        html[data-theme="dark"] :is(
            .demo-video-thumb--reel,
            .demo-media-frame,
            .demo-media-frame video,
            .demo-media-frame iframe
        ) {
            background: #081325 !important;
        }
        html[data-theme="dark"] .demo-video-thumb::after {
            background:
                linear-gradient(180deg, rgba(4, 12, 24, 0.18), rgba(4, 12, 24, 0.34)),
                radial-gradient(circle at center, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0) 42%) !important;
        }
        html[data-theme="dark"] :is(
            .demo-video-arrow,
            .demo-task-actions .btn-soft,
            .demo-task-video-note
        ) {
            border-color: var(--line) !important;
            background: color-mix(in srgb, var(--field-bg) 88%, #101a2d 12%) !important;
            color: var(--text) !important;
        }
        html[data-theme="dark"] .demo-video-arrow:hover,
        html[data-theme="dark"] .demo-task-actions .btn-soft:hover {
            border-color: color-mix(in srgb, var(--primary) 34%, var(--line) 66%) !important;
            background: color-mix(in srgb, var(--primary-soft) 44%, var(--card) 56%) !important;
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.26);
        }
        html[data-theme="dark"] .demo-task-rating-panel {
            box-shadow: 0 16px 28px rgba(0, 0, 0, 0.24);
        }
        html[data-theme="dark"] .demo-rating-input label {
            border-color: var(--line) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(160, 198, 255, 0.08), rgba(160, 198, 255, 0) 42%),
                color-mix(in srgb, var(--field-bg) 88%, #101a2d 12%) !important;
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .demo-rating-input label.demo-rating-smiley--1:hover,
        html[data-theme="dark"] .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--1 {
            border-color: rgba(231, 122, 122, 0.42) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(255, 181, 181, 0.12), rgba(255, 181, 181, 0) 42%),
                linear-gradient(180deg, rgba(95, 29, 40, 0.78), rgba(72, 20, 29, 0.82)) !important;
            color: #ff9d9d !important;
        }
        html[data-theme="dark"] .demo-rating-input label.demo-rating-smiley--2:hover,
        html[data-theme="dark"] .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--2 {
            border-color: rgba(230, 161, 81, 0.42) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(255, 206, 143, 0.12), rgba(255, 206, 143, 0) 42%),
                linear-gradient(180deg, rgba(95, 58, 21, 0.8), rgba(70, 42, 15, 0.84)) !important;
            color: #ffbf73 !important;
        }
        html[data-theme="dark"] .demo-rating-input label.demo-rating-smiley--3:hover,
        html[data-theme="dark"] .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--3 {
            border-color: rgba(214, 188, 94, 0.42) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(245, 220, 122, 0.12), rgba(245, 220, 122, 0) 42%),
                linear-gradient(180deg, rgba(88, 72, 20, 0.8), rgba(64, 51, 12, 0.84)) !important;
            color: #f4d36f !important;
        }
        html[data-theme="dark"] .demo-rating-input label.demo-rating-smiley--4:hover,
        html[data-theme="dark"] .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--4 {
            border-color: rgba(85, 192, 167, 0.42) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(124, 226, 202, 0.12), rgba(124, 226, 202, 0) 42%),
                linear-gradient(180deg, rgba(17, 78, 70, 0.8), rgba(11, 59, 54, 0.84)) !important;
            color: #75dbc1 !important;
        }
        html[data-theme="dark"] .demo-rating-input label.demo-rating-smiley--5:hover,
        html[data-theme="dark"] .demo-rating-input input[type="radio"]:checked + label.demo-rating-smiley--5 {
            border-color: rgba(108, 199, 97, 0.42) !important;
            background:
                radial-gradient(circle at 50% 18%, rgba(152, 235, 142, 0.12), rgba(152, 235, 142, 0) 42%),
                linear-gradient(180deg, rgba(26, 86, 34, 0.8), rgba(16, 63, 22, 0.84)) !important;
            color: #8ee584 !important;
        }
        html[data-theme="dark"] .demo-video-dot {
            background: color-mix(in srgb, var(--line) 78%, #8fb3e6 22%);
        }
        html[data-theme="dark"] .demo-video-dot.active {
            background: var(--primary);
            box-shadow: 0 0 0 6px rgba(120, 175, 255, 0.14);
        }
        html[data-theme="dark"] .demo-video-counter {
            color: var(--muted);
        }
        html[data-theme="dark"] :is(
            .demo-submission-alert,
            .demo-submission-alert--success
        ) {
            border-color: rgba(74, 196, 136, 0.28) !important;
            background:
                radial-gradient(circle at top right, rgba(74, 196, 136, 0.16), rgba(74, 196, 136, 0) 36%),
                linear-gradient(180deg, color-mix(in srgb, var(--card) 94%, #101a2d 6%), color-mix(in srgb, var(--card) 88%, var(--primary-soft) 12%)) !important;
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.24);
        }
        html[data-theme="dark"] .demo-submission-alert strong {
            color: #dff6ea !important;
        }
        html[data-theme="dark"] .demo-submission-alert p {
            color: var(--muted) !important;
        }
        @media (prefers-reduced-motion: reduce) {
            .demo-panel,
            .demo-video-slider::before,
            .demo-video-slider::after,
            .demo-video-cover::before,
            .demo-submission-alert,
            .demo-task-card,
            .demo-empty,
            .demo-video-slide.active .demo-video-cover h3,
            .demo-video-slide.active .demo-video-cover p,
            .demo-video-slide.active .demo-video-cover .hero-play,
            .demo-video-slide.active .demo-video-thumb,
            .demo-video-dot.active {
                animation: none !important;
            }
            .demo-task-card::before {
                transition: none !important;
            }
        }
        @media (max-width: 980px) {
            .demo-video { grid-template-columns: 1fr; }
            .demo-video-cover,
            .demo-video-thumb { min-height: 320px; }
            .demo-video-cover { padding: 28px 22px; }
            .demo-video--feature-reel,
            .demo-review-slider .demo-video--review-reel {
                width: 100%;
            }
            .demo-video--feature-reel .demo-video-thumb,
            .demo-review-slider .demo-video--review-reel .demo-video-thumb {
                padding: 0;
            }
            .demo-video--feature-reel .demo-video-cover,
            .demo-review-slider .demo-video--review-reel .demo-video-cover {
                padding: 20px 18px 24px;
            }
            .demo-submit-success-hero { grid-template-columns: 1fr; }
            .demo-submit-success-grid { grid-template-columns: 1fr; }
            .demo-submit-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .demo-task-shell { grid-template-columns: 1fr; }
            .demo-task-media-col { display: block; }
            .demo-task-video { min-height: auto; }
            .demo-task-video-frame:not(.demo-task-video-frame--landscape) {
                aspect-ratio: 9 / 16;
                width: min(100%, 320px);
            }
            .demo-task-form-col { display: block; }
            .demo-submit-panel { min-height: auto; }
            .demo-video-nav {
                justify-content: center;
            }
            .demo-course-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                max-width: 100%;
            }
            .demo-course-tile { max-width: 100%; }
            .admin-demo-user-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 720px) {
            .demo-submit-grid { grid-template-columns: 1fr; }
        }
        @keyframes demoFloat {
            0%, 100% { transform: translate3d(0, 0, 0); opacity: 0.65; }
            50% { transform: translate3d(16px, -12px, 0); opacity: 1; }
        }
        .demo-video:hover .demo-media-frame video {
            transform: none;
            filter: saturate(1.05);
        }
    </style>

    <div class="dash-grid {{ $isStudent ? 'student-mode' : '' }}">
        @include('dashboard.partials.student.hero')
        @includeWhen($dashboardMode === 'demo', 'dashboard.partials.demo.content')
        @includeWhen($dashboardMode !== 'demo', 'dashboard.partials.admin.overview')
        @includeWhen($dashboardMode !== 'demo', 'dashboard.partials.non-demo.content')
    </div>

    @include('dashboard.partials.demo.submit-modal')
    @include('dashboard.partials.demo.scripts')
@endsection
