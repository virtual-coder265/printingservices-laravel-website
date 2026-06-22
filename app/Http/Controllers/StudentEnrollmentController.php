<?php

namespace App\Http\Controllers;

use App\Enums\StudentEnrollmentStatus;
use App\Enums\StudentEnrollmentType;
use App\Http\Requests\StoreStudentEnrollmentRequest;
use App\Models\StudentEnrollment;
use App\Services\CartService;
use App\Services\SiteContentService;
use App\Services\StudentEnrollments\RegistrationCalendarService;
use App\Services\StudentEnrollments\StudentEnrollmentPayloadBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentEnrollmentController extends Controller
{
    public function __construct(
        protected SiteContentService $siteContent,
        protected CartService $cartService,
        protected RegistrationCalendarService $calendarService,
    ) {}

    public function store(
        StoreStudentEnrollmentRequest $request,
        StudentEnrollmentPayloadBuilder $payloadBuilder,
    ): RedirectResponse {
        $openPeriod = $this->calendarService->getOpenPeriod();
        $isEnrollmentOpen = $openPeriod !== null;

        $externalId = StudentEnrollment::generateExternalId();
        $payload = $payloadBuilder->buildFromInput(
            $request->validated(),
            $externalId,
            $isEnrollmentOpen,
            $openPeriod,
        );

        $enrollment = DB::transaction(function () use ($request, $payload, $externalId, $isEnrollmentOpen, $openPeriod) {
            return StudentEnrollment::create([
                'external_id' => $externalId,
                'type' => $isEnrollmentOpen ? StudentEnrollmentType::Enrollment : StudentEnrollmentType::Interest,
                'registration_period_id' => $openPeriod?->id,
                'status' => StudentEnrollmentStatus::Pending,
                'payload_json' => $payload,
                'client_ip' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
            ]);
        });

        return redirect()
            ->route('enrollment.confirmation', $enrollment)
            ->with('external_id', $enrollment->external_id);
    }

    public function confirmation(StudentEnrollment $studentEnrollment): View
    {
        $data = $this->siteContent->getPageData('training');
        $data['page']['utility']['cart_count'] = $this->cartService->count();
        $data['enrollment'] = $studentEnrollment;

        return view('pages.enrollment-confirmation', $data);
    }
}
