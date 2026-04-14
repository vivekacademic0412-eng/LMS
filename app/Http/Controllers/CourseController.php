<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureCanView($request);

        $categoryId = $request->query('category_id');
        $subcategoryId = $request->query('subcategory_id');
        $activeSearch = trim((string) $request->query('search'));
        $searchLike = '%'.$activeSearch.'%';

        $applyCourseSearch = function ($query) use ($activeSearch, $searchLike): void {
            $query->when($activeSearch !== '', function ($searchableQuery) use ($activeSearch, $searchLike): void {
                $searchableQuery->where(function ($searchQuery) use ($activeSearch, $searchLike): void {
                    if (ctype_digit($activeSearch)) {
                        $searchQuery->orWhere('courses.id', (int) $activeSearch);
                    }

                    $searchQuery
                        ->orWhere('title', 'like', $searchLike)
                        ->orWhere('short_description', 'like', $searchLike)
                        ->orWhere('description', 'like', $searchLike)
                        ->orWhere('language', 'like', $searchLike)
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', $searchLike))
                        ->orWhereHas('subcategory', fn ($subcategoryQuery) => $subcategoryQuery->where('name', 'like', $searchLike))
                        ->orWhereHas('creator', function ($creatorQuery) use ($searchLike) {
                            $creatorQuery
                                ->where('name', 'like', $searchLike)
                                ->orWhere('email', 'like', $searchLike);
                        });
                });
            });
        };

        $applyCourseFilters = function ($query) use ($categoryId, $subcategoryId, $applyCourseSearch): void {
            $query
                ->when($categoryId, fn ($courseQuery) => $courseQuery->where('category_id', $categoryId))
                ->when($subcategoryId, fn ($courseQuery) => $courseQuery->where('subcategory_id', $subcategoryId));

            $applyCourseSearch($query);
        };

        $coursesQuery = Course::with(['category', 'subcategory', 'creator'])
            ->latest();

        $applyCourseFilters($coursesQuery);

        $isTrainer = $this->isTrainer($request);
        $assignedCourseIds = $isTrainer
            ? CourseEnrollment::where('trainer_id', $request->user()->id)->pluck('course_id')->all()
            : [];

        $categories = CourseCategory::query()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->with('children:id,name,parent_id')
            ->get(['id', 'name']);

        $browseCategoriesQuery = CourseCategory::query()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->with('children:id,name,parent_id');

        if ($isTrainer) {
            $browseCategoriesQuery
                ->when($categoryId, fn ($query) => $query->whereKey($categoryId))
                ->when(
                    $subcategoryId || $activeSearch !== '',
                    function ($query) use ($applyCourseFilters) {
                        $query->whereHas('courses', function ($courseQuery) use ($applyCourseFilters) {
                            $applyCourseFilters($courseQuery);
                        });
                    }
                )
                ->with([
                    'children:id,name,parent_id',
                    'courses' => function ($courseQuery) use ($applyCourseFilters) {
                        $courseQuery
                            ->with(['category', 'subcategory', 'creator'])
                            ->latest();

                        $applyCourseFilters($courseQuery);
                    },
                ]);
        }

        return view('courses.index', [
            'courses' => $coursesQuery->paginate(8)->withQueryString(),
            'categories' => $categories,
            'browseCategories' => $browseCategoriesQuery->get(['id', 'name']),
            'canManage' => $this->canManage($request),
            'isTrainer' => $isTrainer,
            'assignedCourseIds' => $assignedCourseIds,
            'activeCategoryId' => $categoryId,
            'activeSubcategoryId' => $subcategoryId,
            'activeSearch' => $activeSearch,
        ]);
    }

    public function show(Request $request, Course $course): View|RedirectResponse
    {
        $this->ensureCanView($request);
        $assignmentBlock = $this->ensureTrainerAssigned($request, $course);
        if ($assignmentBlock instanceof RedirectResponse) {
            return $assignmentBlock;
        }

        $course->load(['category', 'subcategory', 'weeks.sessions.items.quizQuestions']);

        $enrollments = CourseEnrollment::query()
            ->with(['student', 'trainer'])
            ->where('course_id', $course->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('courses.show', [
            'course' => $course,
            'enrollments' => $enrollments,
            'canManage' => $this->canManage($request),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($this->canManage($request), 403);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:course_categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:course_categories,id'],
            'title' => ['required', 'string', 'max:160'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'language' => ['nullable', 'string', 'max:80'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $this->validateSubcategory($data['category_id'], $data['subcategory_id'] ?? null);

        $request->validate([
            'title' => [
                Rule::unique('courses', 'title')->where(function ($query) use ($data) {
                    $query->where('category_id', $data['category_id']);
                    if (empty($data['subcategory_id'])) {
                        $query->whereNull('subcategory_id');
                    } else {
                        $query->where('subcategory_id', $data['subcategory_id']);
                    }
                }),
            ],
        ]);

        Course::create([
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id'] ?? null,
            'title' => $data['title'],
            'short_description' => $data['short_description'] ?? null,
            'slug' => Str::slug($data['title']).'-'.Str::lower(Str::random(4)),
            'description' => $data['description'] ?? null,
            'language' => $data['language'] ?? null,
            'thumbnail' => $request->hasFile('thumbnail')
                ? $request->file('thumbnail')->store('course-thumbnails', 'public')
                : null,
            'duration_hours' => $data['duration_hours'],
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Course created successfully.');
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_unless($this->canManage($request), 403);

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:course_categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:course_categories,id'],
            'title' => ['required', 'string', 'max:160'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'language' => ['nullable', 'string', 'max:80'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $this->validateSubcategory($data['category_id'], $data['subcategory_id'] ?? null);

        $request->validate([
            'title' => [
                Rule::unique('courses', 'title')
                    ->where(function ($query) use ($data) {
                        $query->where('category_id', $data['category_id']);
                        if (empty($data['subcategory_id'])) {
                            $query->whereNull('subcategory_id');
                        } else {
                            $query->where('subcategory_id', $data['subcategory_id']);
                        }
                    })
                    ->ignore($course->id),
            ],
        ]);

        $thumbnailPath = $course->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath && !Str::startsWith($thumbnailPath, ['http://', 'https://']) && Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        $course->update([
            'category_id' => $data['category_id'],
            'subcategory_id' => $data['subcategory_id'] ?? null,
            'title' => $data['title'],
            'short_description' => $data['short_description'] ?? null,
            'slug' => Str::slug($data['title']).'-'.$course->id,
            'description' => $data['description'] ?? null,
            'language' => $data['language'] ?? null,
            'thumbnail' => $thumbnailPath,
            'duration_hours' => $data['duration_hours'],
        ]);

        return back()->with('success', 'Course updated successfully.');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        abort_unless($this->canManage($request), 403);

        if ($course->thumbnail && !Str::startsWith($course->thumbnail, ['http://', 'https://']) && Storage::disk('public')->exists($course->thumbnail)) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return back()->with('success', 'Course deleted successfully.');
    }

    private function validateSubcategory(int $categoryId, ?int $subcategoryId): void
    {
        if ($subcategoryId === null) {
            return;
        }

        $validSubcategory = CourseCategory::query()
            ->whereKey($subcategoryId)
            ->where('parent_id', $categoryId)
            ->exists();

        if (! $validSubcategory) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'Selected subcategory does not belong to selected category.',
            ]);
        }
    }

    private function ensureCanView(Request $request): void
    {
        abort_unless(
            in_array($request->user()?->role, [
                User::ROLE_SUPERADMIN,
                User::ROLE_ADMIN,
                User::ROLE_MANAGER_HR,
                User::ROLE_IT,
                User::ROLE_TRAINER,
            ], true),
            403,
            'You do not have access to this page.'
        );
    }

    private function ensureTrainerAssigned(Request $request, Course $course): ?RedirectResponse
    {
        if (! $this->isTrainer($request)) {
            return null;
        }

        $assigned = CourseEnrollment::where('course_id', $course->id)
            ->where('trainer_id', $request->user()->id)
            ->exists();

        if (! $assigned) {
            return redirect()->route('courses.index')
                ->withErrors('You can only view assigned courses.');
        }

        return null;
    }

    private function isTrainer(Request $request): bool
    {
        return $request->user()?->role === User::ROLE_TRAINER;
    }

    private function canManage(Request $request): bool
    {
        return in_array($request->user()?->role, [User::ROLE_SUPERADMIN, User::ROLE_ADMIN], true);
    }
}
