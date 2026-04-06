@extends('layouts.app')
@php
    $videoRatioOptions = \App\Support\DemoVideoRatio::options();
@endphp
@section('content')
    <style>
        .feature-page {
            display: grid;
            gap: 18px;
        }
        .feature-hero {
            border: 1px solid #d6dfef;
            border-radius: 24px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.24), rgba(255, 255, 255, 0) 34%),
                linear-gradient(120deg, #0d4aac 0%, #1a6ed2 58%, #0f8a84 100%);
            color: #fff;
            padding: 28px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            box-shadow: 0 24px 48px rgba(17, 54, 117, 0.18);
        }
        .feature-hero h1 {
            margin: 8px 0 10px;
            font-size: 34px;
            line-height: 1.05;
        }
        .feature-hero p {
            margin: 0;
            max-width: 68ch;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
        }
        .feature-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .feature-hero-actions {
            display: flex;
            justify-content: flex-end;
            align-items: start;
        }
        .feature-hero-actions .btn {
            box-shadow: 0 14px 28px rgba(4, 20, 56, 0.18);
        }
        .feature-stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }
        .feature-stat {
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 14px 16px;
        }
        .feature-stat span {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.78);
        }
        .feature-stat strong {
            display: block;
            margin-top: 6px;
            font-size: 26px;
            line-height: 1;
        }
        .feature-section {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--card);
            padding: 18px;
            box-shadow: 0 14px 30px rgba(18, 42, 86, 0.08);
        }
        .feature-section-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 12px;
            margin-bottom: 16px;
        }
        .feature-section-head h2 {
            margin: 0;
            font-size: 24px;
        }
        .feature-section-head p {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 13px;
        }
        .feature-spotlight {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(360px, 1.1fr);
            gap: 16px;
            align-items: stretch;
        }
        .feature-spotlight-copy {
            border-radius: 20px;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0) 40%),
                linear-gradient(135deg, #0e4aa8 0%, #134fbc 55%, #0c7f89 100%);
            color: #fff;
            padding: 24px;
            display: grid;
            align-content: center;
            gap: 12px;
            min-height: 100%;
        }
        .feature-position-pill {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .feature-spotlight-copy h3 {
            margin: 0;
            font-size: 34px;
            line-height: 1.08;
        }
        .feature-spotlight-copy p {
            margin: 0;
            font-size: 15px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
        }
        .feature-spotlight-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
        }
        .feature-preview {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #d6dfef;
            background: #08152c;
            min-height: 100%;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.04);
            display: grid;
            place-items: center;
        }
        .feature-preview video,
        .feature-preview iframe,
        .feature-library-video video,
        .feature-library-video iframe {
            width: 100%;
            height: 100%;
            display: block;
            background: #08152c;
        }
        .feature-preview video,
        .feature-library-video video {
            object-fit: contain;
        }
        .feature-preview iframe,
        .feature-library-video iframe {
            min-height: 0;
            border: 0;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
        }
        .feature-tile {
            border: 1px solid #d8e0ee;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0)), #fff;
            overflow: hidden;
            box-shadow: 0 14px 28px rgba(18, 42, 86, 0.08);
        }
        .feature-library-video {
            aspect-ratio: 16 / 9;
            background: #08152c;
            display: grid;
            place-items: center;
        }
        .feature-preview--landscape,
        .feature-library-video--landscape {
            width: 100%;
            aspect-ratio: 16 / 9;
        }
        .feature-preview--reel,
        .feature-library-video--reel {
            width: min(360px, 100%);
            aspect-ratio: 9 / 16;
            min-height: 0;
            margin-inline: auto;
        }
        .feature-library-video.feature-library-video--empty,
        .feature-preview.feature-preview--empty {
            display: grid;
            place-content: center;
            color: rgba(255, 255, 255, 0.78);
            text-align: center;
            padding: 20px;
        }
        .feature-tile-body {
            padding: 16px;
            display: grid;
            gap: 12px;
        }
        .feature-tile-top {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: start;
        }
        .feature-tile-top strong {
            font-size: 20px;
            line-height: 1.15;
        }
        .feature-order-badge {
            display: inline-grid;
            place-content: center;
            min-width: 48px;
            height: 48px;
            padding: 0 10px;
            border-radius: 14px;
            background: #edf4ff;
            color: #104db4;
            font-size: 18px;
            font-weight: 800;
            box-shadow: inset 0 0 0 1px #d0def5;
        }
        .feature-tile-desc {
            margin: 0;
            color: #5d6d84;
            font-size: 14px;
            line-height: 1.65;
        }
        .feature-tile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .feature-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #47607f;
            background: #f3f6fb;
            border: 1px solid #dbe4f0;
        }
        .feature-tile-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .feature-empty {
            border: 1px dashed #cbd8ea;
            border-radius: 18px;
            padding: 32px 18px;
            text-align: center;
            background: linear-gradient(180deg, rgba(15, 77, 191, 0.03), rgba(15, 77, 191, 0));
            color: #53657d;
        }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(8, 15, 28, 0.56);
            backdrop-filter: blur(3px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 120;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            width: min(700px, 100%);
            max-height: calc(100vh - 36px);
            overflow: auto;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: var(--card);
            box-shadow: 0 28px 56px rgba(0, 0, 0, 0.25);
        }
        .modal.modal-sm { width: min(420px, 100%); }
        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 18px;
            border-bottom: 1px solid var(--line-soft);
        }
        .modal-head h3 { margin: 0; font-size: 22px; }
        .modal-close {
            border: 0;
            background: transparent;
            color: var(--muted);
            font-size: 26px;
            line-height: 1;
            cursor: pointer;
        }
        .modal-body { padding: 18px; }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid var(--line-soft);
            padding: 14px 18px;
            gap: 8px;
        }
        .feature-form-grid {
            display: grid;
            grid-template-columns: 140px minmax(0, 1fr);
            gap: 14px;
        }
        .field-note {
            margin-top: 6px;
            font-size: 12px;
            color: var(--muted);
        }
        .feature-check-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        .feature-check-row input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        .feature-check-row label {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0;
            text-transform: none;
        }
        @media (max-width: 960px) {
            .feature-hero,
            .feature-spotlight,
            .feature-form-grid {
                grid-template-columns: 1fr;
            }
            .feature-hero-actions {
                justify-content: start;
            }
            .feature-stat-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="feature-page">
        <section class="feature-hero">
            <div>
                <span class="feature-eyebrow">Admin Demo Media</span>
                <h1>Demo Feature Videos</h1>
                <p>Manage the full video slider shown to demo users. Upload a direct video, add a YouTube link fallback, or use a YouTube link by itself. Position <strong>1</strong> appears first on the dashboard, position <strong>2</strong> appears second, and so on.</p>
                <div class="feature-stat-grid">
                    <div class="feature-stat">
                        <span>Total Videos</span>
                        <strong>{{ $videos->count() }}</strong>
                    </div>
                    <div class="feature-stat">
                        <span>Next Position</span>
                        <strong>{{ $nextPosition }}</strong>
                    </div>
                    <div class="feature-stat">
                        <span>First On Dashboard</span>
                        <strong>{{ $featured?->position ?? '-' }}</strong>
                    </div>
                </div>
            </div>
            <div class="feature-hero-actions">
                <button type="button" class="btn" data-modal-open="modal-feature-upload">Upload Video</button>
            </div>
        </section>

        @if ($featured)
            @php
                $featuredOpenUrl = $featured->has_uploaded_video ? route('demo-feature-video.show', $featured) : $featured->watch_url;
            @endphp
            <section class="feature-section">
                <div class="feature-section-head">
                    <div>
                        <h2>Dashboard First Video</h2>
                        <p>This is the video demo users see first in the dashboard slider.</p>
                    </div>
                </div>
                <div class="feature-spotlight">
                    <div class="feature-spotlight-copy">
                        <span class="feature-position-pill">Position {{ $featured->position ?? 1 }}</span>
                        <h3>{{ $featured->title ?: 'Feature Video' }}</h3>
                        <p>{{ $featured->description ?: 'No description provided for this video yet.' }}</p>
                        <div class="feature-spotlight-meta">
                            @if ($featured->has_uploaded_video)
                                <span class="feature-chip">{{ $featured->file_name ?: 'Uploaded video' }}</span>
                            @endif
                            @if ($featured->has_youtube_video)
                                <span class="feature-chip">YouTube {{ $featured->youtube_id }}</span>
                            @endif
                            <span class="feature-chip">{{ $featured->video_ratio_label }}</span>
                            <span class="feature-chip">Uploaded {{ $featured->created_at?->format('M d, Y') }}</span>
                        </div>
                        <div class="feature-tile-actions">
                            @if ($featuredOpenUrl)
                                <a class="btn btn-soft" href="{{ $featuredOpenUrl }}" target="_blank" rel="noopener">Open Video</a>
                            @else
                                <span class="btn btn-soft" style="pointer-events:none;opacity:.75;">No Source</span>
                            @endif
                            <button type="button" class="btn btn-soft" data-modal-open="modal-feature-edit-{{ $featured->id }}">Edit</button>
                            <button type="button" class="btn btn-danger" data-modal-open="modal-feature-delete-{{ $featured->id }}">Delete</button>
                        </div>
                    </div>
                    <div class="feature-preview feature-preview--{{ $featured->resolved_video_ratio }} {{ ! $featured->has_uploaded_video && ! $featured->embed_url ? 'feature-preview--empty' : '' }}">
                        @if ($featured->has_uploaded_video)
                            <video controls preload="metadata" controlslist="nodownload">
                                <source src="{{ route('demo-feature-video.show', $featured) }}" type="{{ $featured->file_mime ?: 'video/mp4' }}">
                            </video>
                        @elseif ($featured->embed_url)
                            <iframe
                                src="{{ $featured->embed_url }}"
                                title="{{ $featured->title ?: 'Feature Video' }}"
                                loading="lazy"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                            ></iframe>
                        @else
                            <div>No video source available.</div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <section class="feature-section">
            <div class="feature-section-head">
                <div>
                    <h2>Video Library</h2>
                    <p>All videos are sorted by dashboard position, so the order here matches the demo user slider.</p>
                </div>
            </div>

            @if ($videos->isNotEmpty())
                <div class="feature-grid">
                    @foreach ($videos as $video)
                        @php
                            $videoOpenUrl = $video->has_uploaded_video ? route('demo-feature-video.show', $video) : $video->watch_url;
                        @endphp
                        <article class="feature-tile">
                            <div class="feature-library-video feature-library-video--{{ $video->resolved_video_ratio }} {{ ! $video->has_uploaded_video && ! $video->embed_url ? 'feature-library-video--empty' : '' }}">
                                @if ($video->has_uploaded_video)
                                    <video controls preload="metadata" controlslist="nodownload">
                                        <source src="{{ route('demo-feature-video.show', $video) }}" type="{{ $video->file_mime ?: 'video/mp4' }}">
                                    </video>
                                @elseif ($video->embed_url)
                                    <iframe
                                        src="{{ $video->embed_url }}"
                                        title="{{ $video->title ?: 'Feature Video' }}"
                                        loading="lazy"
                                        referrerpolicy="strict-origin-when-cross-origin"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen
                                    ></iframe>
                                @else
                                    <div>No video source available.</div>
                                @endif
                            </div>
                            <div class="feature-tile-body">
                                <div class="feature-tile-top">
                                    <div>
                                        <strong>{{ $video->title ?: 'Feature Video' }}</strong>
                                    </div>
                                    <span class="feature-order-badge">{{ $video->position ?? '-' }}</span>
                                </div>
                                <p class="feature-tile-desc">{{ $video->description ?: 'No description provided.' }}</p>
                                <div class="feature-tile-meta">
                                    <span class="feature-meta-chip">Position {{ $video->position ?? '-' }}</span>
                                    <span class="feature-meta-chip">{{ $video->video_ratio_label }}</span>
                                    <span class="feature-meta-chip">Uploaded {{ $video->created_at?->format('M d, Y') }}</span>
                                    @if ($video->has_uploaded_video)
                                        <span class="feature-meta-chip">{{ $video->file_name ?: 'Uploaded video' }}</span>
                                    @endif
                                    @if ($video->has_youtube_video)
                                        <span class="feature-meta-chip">YouTube {{ $video->youtube_id }}</span>
                                    @endif
                                </div>
                                <div class="feature-tile-actions">
                                    @if ($videoOpenUrl)
                                        <a class="btn btn-soft" href="{{ $videoOpenUrl }}" target="_blank" rel="noopener">Open Video</a>
                                    @else
                                        <span class="btn btn-soft" style="pointer-events:none;opacity:.75;">No Source</span>
                                    @endif
                                    <button type="button" class="btn btn-soft" data-modal-open="modal-feature-edit-{{ $video->id }}">Edit</button>
                                    <button type="button" class="btn btn-danger" data-modal-open="modal-feature-delete-{{ $video->id }}">Delete</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="feature-empty">
                    <h3 style="margin: 0 0 8px;">No Feature Videos Yet</h3>
                    <p style="margin: 0;">Upload your first video or add a YouTube link and set position <strong>1</strong> to make it the first slide on the demo dashboard.</p>
                </div>
            @endif
        </section>
    </div>

    <div class="modal-overlay" id="modal-feature-upload" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true">
            <div class="modal-head">
                <h3>Upload Feature Video</h3>
                <button type="button" class="modal-close" data-modal-close="modal-feature-upload" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('demo-feature-video.store') }}" enctype="multipart/form-data" class="stack form-premium">
                    @csrf
                    <div class="feature-form-grid">
                        <div class="field">
                            <label>Dashboard Position</label>
                            <input type="number" name="position" min="1" step="1" value="{{ old('position', $nextPosition) }}">
                            <div class="field-note">Use unique positions. Video 1 shows first, video 2 shows second.</div>
                        </div>
                        <div class="field">
                            <label>Video Ratio</label>
                            <select name="video_ratio" required>
                                @foreach ($videoRatioOptions as $ratioValue => $ratioLabel)
                                    <option value="{{ $ratioValue }}" @selected(old('video_ratio', \App\Support\DemoVideoRatio::LANDSCAPE) === $ratioValue)>{{ $ratioLabel }}</option>
                                @endforeach
                            </select>
                            <div class="field-note">Use Landscape for wide videos and Reel for portrait or shorts-style videos.</div>
                        </div>
                    </div>
                    <div class="feature-form-grid">
                        <div class="field form-span-2">
                            <label>Upload Video File</label>
                            <input type="file" name="video_file" accept="video/*">
                            <div class="field-note">Uploaded video is the primary source when available.</div>
                        </div>
                    </div>
                    <div class="feature-form-grid">
                        <div class="field form-span-2">
                            <label>YouTube Video Link (optional)</label>
                            <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="field-note">If no uploaded file exists, the dashboard will show this YouTube video instead.</div>
                        </div>
                    </div>
                    <div class="feature-form-grid">
                        <div class="field">
                            <label>Title (optional)</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Feature video title">
                        </div>
                        <div class="field">
                            <label>Description (optional)</label>
                            <textarea name="description" rows="4">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="actions-row">
                        <button class="btn btn-soft" type="submit">Upload Video</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-modal-close="modal-feature-upload">Close</button>
            </div>
        </div>
    </div>

    @foreach ($videos as $video)
        <div class="modal-overlay" id="modal-feature-edit-{{ $video->id }}" aria-hidden="true">
            <div class="modal" role="dialog" aria-modal="true">
                <div class="modal-head">
                    <h3>Edit Feature Video</h3>
                    <button type="button" class="modal-close" data-modal-close="modal-feature-edit-{{ $video->id }}" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('demo-feature-video.update', $video) }}" enctype="multipart/form-data" class="stack form-premium">
                        @csrf
                        @method('PUT')
                        <div class="feature-form-grid">
                            <div class="field">
                                <label>Dashboard Position</label>
                                <input type="number" name="position" min="1" step="1" value="{{ old('position', $video->position) }}">
                                <div class="field-note">Choose where this video should appear in the dashboard slider.</div>
                            </div>
                            <div class="field">
                                <label>Video Ratio</label>
                                <select name="video_ratio" required>
                                    @foreach ($videoRatioOptions as $ratioValue => $ratioLabel)
                                        <option value="{{ $ratioValue }}" @selected(old('video_ratio', $video->resolved_video_ratio) === $ratioValue)>{{ $ratioLabel }}</option>
                                    @endforeach
                                </select>
                                <div class="field-note">Use Landscape for wide videos and Reel for portrait or shorts-style videos.</div>
                            </div>
                        </div>
                        <div class="feature-form-grid">
                            <div class="field form-span-2">
                                <label>Replace Video File (optional)</label>
                                <input type="file" name="video_file" accept="video/*">
                                <div class="field-note">Uploaded video stays first priority when it exists.</div>
                                @if ($video->has_uploaded_video || $video->file_path)
                                    <div class="feature-check-row">
                                        <input type="checkbox" id="remove-video-file-{{ $video->id }}" name="remove_video_file" value="1" {{ old('remove_video_file') ? 'checked' : '' }}>
                                        <label for="remove-video-file-{{ $video->id }}">Remove current uploaded file and use YouTube fallback</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="feature-form-grid">
                            <div class="field form-span-2">
                                <label>YouTube Video Link (optional)</label>
                                <input type="url" name="video_url" value="{{ old('video_url', $video->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                                <div class="field-note">Shown only when no uploaded feature video is available.</div>
                            </div>
                        </div>
                        <div class="feature-form-grid">
                            <div class="field">
                                <label>Title (optional)</label>
                                <input type="text" name="title" value="{{ old('title', $video->title) }}">
                            </div>
                            <div class="field">
                                <label>Description (optional)</label>
                                <textarea name="description" rows="4">{{ old('description', $video->description) }}</textarea>
                            </div>
                        </div>
                        <div class="actions-row">
                            <button class="btn" type="submit">Update Video</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-modal-close="modal-feature-edit-{{ $video->id }}">Close</button>
                </div>
            </div>
        </div>

        <div class="modal-overlay" id="modal-feature-delete-{{ $video->id }}" aria-hidden="true">
            <div class="modal modal-sm" role="dialog" aria-modal="true">
                <div class="modal-head">
                    <h3>Delete Feature Video</h3>
                    <button type="button" class="modal-close" data-modal-close="modal-feature-delete-{{ $video->id }}" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <p class="muted">Delete <strong>{{ $video->title ?: 'Feature Video' }}</strong> from position <strong>{{ $video->position ?? '-' }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-modal-close="modal-feature-delete-{{ $video->id }}">Cancel</button>
                    <form method="POST" action="{{ route('demo-feature-video.destroy', $video) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script src="{{ asset('js/course-modals.js') }}" defer></script>
@endsection
