@php
    $demoSubmitPopup = session('demo_task_submission_popup');
@endphp

    @if ($dashboardMode === 'demo' && !empty($demoSubmitPopup))
        <div class="modal-overlay open" id="modal-demo-submit-success" aria-hidden="false" data-demo-kiosk-reload-seconds="{{ $demoSubmitPopup['cooldown_seconds'] ?? $demoTaskCooldownSeconds }}">
            <div class="modal modal-demo-submit" role="dialog" aria-modal="true" aria-labelledby="modal-demo-submit-success-title">
                <div class="modal-head">
                    <h3 id="modal-demo-submit-success-title">Task Submitted</h3>
                    <button type="button" class="modal-close" data-modal-close="modal-demo-submit-success" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="demo-submit-success">
                        <div class="demo-submit-success-hero">
                            <div class="demo-submit-confetti" aria-hidden="true">
                                <span class="demo-submit-confetti-piece"></span>
                                <span class="demo-submit-confetti-piece"></span>
                                <span class="demo-submit-confetti-piece"></span>
                                <span class="demo-submit-confetti-piece"></span>
                                <span class="demo-submit-confetti-piece"></span>
                                <span class="demo-submit-confetti-piece"></span>
                            </div>
                            <div class="demo-submit-success-check" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12.5l4.2 4.2L19 7.8"></path>
                                </svg>
                            </div>
                            <div class="demo-submit-success-copy">
                                <span class="demo-submit-success-badge">Submission Received</span>
                                <strong>Your task has been submitted successfully.</strong>
                                <p>We have received your response. Our HR team will review it and contact you soon with the next steps.</p>
                            </div>
                        </div>
                        <div class="demo-submit-success-grid">
                            <div class="demo-submit-success-stat">
                                <span>Task</span>
                                <strong>{{ $demoSubmitPopup['task_title'] ?? 'Demo Task' }}</strong>
                            </div>
                            <div class="demo-submit-success-stat">
                                <span>Email</span>
                                <strong>{{ $demoSubmitPopup['participant_email'] ?? '-' }}</strong>
                            </div>
                            <div class="demo-submit-success-stat">
                                <span>Phone</span>
                                <strong>{{ $demoSubmitPopup['participant_phone'] ?? '-' }}</strong>
                            </div>
                            <div class="demo-submit-success-stat">
                                <span>Video Rating</span>
                                <strong>{{ !empty($demoSubmitPopup['video_rating']) ? ((int) $demoSubmitPopup['video_rating']).'/5' : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
