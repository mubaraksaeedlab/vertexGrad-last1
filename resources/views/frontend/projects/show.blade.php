@php
    $authUser = auth('web')->user();
    $isInvestor = $authUser && $authUser->role === 'Investor';

    $currentInvestorRelation = null;
    $currentInvestorStatus = null;

    if ($isInvestor && isset($project)) {
        $currentInvestorRelation = $project->investors->firstWhere('id', $authUser->id);
        $currentInvestorStatus = $currentInvestorRelation?->pivot?->status;
    }

    $interestedCount = isset($project)
        ? $project->investors->where('pivot.status', 'interested')->count()
        : 0;

    $interestedUsers = isset($project)
        ? $project->investors->where('pivot.status', 'interested')->take(5)
        : collect();

    $requestedCount = isset($project)
        ? $project->investors->where('pivot.status', 'requested')->count()
        : 0;

    $canViewInvestorDeck = isset($project) && in_array($project->status, [
        'active',
        'published',
        'approved',
        'completed',
        'investor_visible',
    ], true);
@endphp

@extends('frontend.layouts.app')

@section('content')
<div class="min-h-screen py-10 pt-28 bg-theme-bg transition-colors duration-300">
    <div class="w-full max-w-7xl mx-auto px-4">

        <div class="mb-6">
            <a href="{{ route('frontend.projects.index') }}" class="text-brand-accent hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> {{ __('frontend.project_show.back_to_projects') }}
            </a>
        </div>

        @if(isset($project))
            @php
                $images = $project->getMedia('images');
                $videoUrl = $project->getFirstMediaUrl('videos');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                        <span class="text-brand-accent font-bold uppercase tracking-widest text-xs">
                            {{ $project->projectCategory?->display_name ?? $project->category ?? __('frontend.project_show.uncategorized') }}
                        </span>

                        <h1 class="text-4xl font-bold text-theme-text mt-2">
                            {{ $project->name ?? __('frontend.project_show.project_name_missing') }}
                        </h1>

                        <div class="mt-8">
                            <h3 class="text-theme-text font-bold text-xl mb-4">{{ __('frontend.project_show.description') }}</h3>
                            <p class="text-theme-muted leading-relaxed italic text-lg">
                                "{{ $project->description ?? __('frontend.project_show.no_description') }}"
                            </p>
                        </div>

                        @if($interestedCount > 0)
                            <div class="mt-8 pt-6 border-t border-theme-border">
                                <h3 class="text-theme-text font-bold text-lg mb-4">{{ __('frontend.project_show.interested_investors') }}</h3>

                                <div class="flex items-center gap-3 flex-wrap">
                                    <div class="flex -space-x-2">
                                        @foreach($interestedUsers as $investor)
                                            <div class="w-10 h-10 rounded-full bg-brand-accent-soft border border-brand-accent text-brand-accent flex items-center justify-center text-sm font-black">
                                                {{ strtoupper(substr($investor->name ?? 'I', 0, 1)) }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <span class="text-sm text-theme-muted">
                                        {{ $interestedCount }} {{ trans_choice('frontend.project_show.interested_investor_count', $interestedCount) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                        <h3 class="text-theme-text font-bold text-xl mb-6">{{ __('frontend.project_show.project_images') }}</h3>

                        @if($images->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($images as $image)
                                    <a href="{{ $image->getUrl() }}" target="_blank" class="block rounded-2xl overflow-hidden border border-theme-border hover:border-brand-accent/40 transition">
                                        <img src="{{ $image->getUrl() }}" alt="{{ __('frontend.project_show.project_image') }}" class="w-full h-52 object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-theme-muted italic">{{ __('frontend.project_show.no_images_uploaded') }}</p>
                        @endif
                    </div>

                    <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                        <h3 class="text-theme-text font-bold text-xl mb-6">{{ __('frontend.project_show.project_video') }}</h3>

                        @if($videoUrl)
                            <video class="w-full rounded-2xl border border-theme-border bg-black" controls style="max-height: 500px;">
                                <source src="{{ $videoUrl }}">
                                {{ __('frontend.project_show.video_not_supported') }}
                            </video>
                        @else
                            <p class="text-theme-muted italic">{{ __('frontend.project_show.no_video_uploaded') }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="theme-panel p-8 rounded-3xl shadow-brand-soft">
                        <p class="text-theme-muted text-xs uppercase font-bold mb-1">{{ __('frontend.project_show.requested_budget') }}</p>
                        <h2 class="text-4xl font-black text-green-600">
                            ${{ is_numeric($project->budget) ? number_format($project->budget) : '0' }}
                        </h2>

                        <div class="mt-6 pt-6 border-t border-theme-border space-y-4">
                            <div class="flex justify-between gap-4">
                                <span class="text-theme-muted">{{ __('frontend.project_show.student_lead') }}:</span>
                                <span class="text-brand-accent font-bold text-right">{{ $project->student?->name ?? __('frontend.project_show.unknown_user') }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-theme-muted">{{ __('frontend.project_show.status') }}:</span>
                                <span class="text-yellow-600 font-bold text-right">{{ $project->status ?? 'pending' }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-theme-muted">{{ __('frontend.project_show.interest_count') }}:</span>
                                <span class="text-theme-text font-bold text-right">{{ $interestedCount }}</span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-theme-muted">{{ __('frontend.project_show.funding_requests') }}:</span>
                                <span class="text-theme-text font-bold text-right">{{ $requestedCount }}</span>
                            </div>
                        </div>

                        @if($isInvestor)
                            @if($canViewInvestorDeck)
                                <div class="mt-6 space-y-3">
                                    <a href="{{ route('investor.projects.summary', $project) }}"
                                       class="w-full inline-flex items-center justify-center py-3 bg-brand-accent text-white font-bold rounded-xl hover:bg-brand-accent-strong transition">
                                        <i class="fas fa-file-lines mr-2"></i>
                                        {{ __('frontend.project_show.view_summary') }}
                                    </a>

                                    <a href="{{ route('investor.projects.pitch-deck.download', $project) }}"
                                       class="w-full inline-flex items-center justify-center py-3 bg-theme-surface-2 text-theme-text font-bold rounded-xl border border-theme-border hover:border-brand-accent hover:text-brand-accent transition">
                                        <i class="fas fa-file-powerpoint mr-2"></i>
                                        {{ __('frontend.project_show.download_powerpoint') }}
                                    </a>
                                </div>
                            @endif

                            @if(!$currentInvestorStatus)
                                <div class="mt-6 space-y-3">
                                    <form method="POST" action="{{ route('frontend.projects.invest', $project) }}">
                                        @csrf
                                        <button type="submit" class="w-full py-3 bg-brand-accent text-white font-bold rounded-xl hover:bg-brand-accent-strong transition">
                                            {{ __('frontend.project_show.express_investment_interest') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('frontend.projects.requestFunding', $project) }}" class="space-y-3">
                                        @csrf
                                        <input
                                            type="number"
                                            name="amount"
                                            min="1"
                                            step="0.01"
                                            placeholder="{{ __('frontend.project_show.funding_amount') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        >

                                        <textarea
                                            name="message"
                                            rows="4"
                                            placeholder="{{ __('frontend.project_show.funding_message_placeholder') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        ></textarea>

                                        <button type="submit" class="w-full py-3 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition">
                                            {{ __('frontend.project_show.request_funding') }}
                                        </button>
                                    </form>
                                </div>

                            @elseif($currentInvestorStatus === 'interested')
                                <div class="mt-6 space-y-3">
                                    <div class="text-green-600 font-bold text-center p-3 rounded-xl bg-green-500/10 border border-green-500/20">
                                        {{ __('frontend.project_show.already_expressed_interest') }}
                                    </div>

                                    <form method="POST" action="{{ route('frontend.projects.interest.remove', $project) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-3 bg-theme-surface-2 text-theme-text font-bold rounded-xl hover:bg-red-500/10 hover:text-red-600 transition border border-theme-border">
                                            {{ __('frontend.project_show.remove_interest') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('frontend.projects.requestFunding', $project) }}" class="space-y-3">
                                        @csrf
                                        <input
                                            type="number"
                                            name="amount"
                                            min="1"
                                            step="0.01"
                                            placeholder="{{ __('frontend.project_show.funding_amount') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        >

                                        <textarea
                                            name="message"
                                            rows="4"
                                            placeholder="{{ __('frontend.project_show.funding_message_placeholder') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        ></textarea>

                                        <button type="submit" class="w-full py-3 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition">
                                            {{ __('frontend.project_show.upgrade_to_funding_request') }}
                                        </button>
                                    </form>
                                </div>

                            @elseif($currentInvestorStatus === 'requested')
                                <div class="mt-6 text-center p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-700 font-bold">
                                    {{ __('frontend.project_show.request_under_review') }}
                                </div>

                            @elseif($currentInvestorStatus === 'approved')
                                <div class="mt-6 text-center p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 font-bold">
                                    {{ __('frontend.project_show.request_approved') }}
                                </div>

                            @elseif($currentInvestorStatus === 'rejected')
                                <div class="mt-6 space-y-3">
                                    <div class="text-center p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 font-bold">
                                        {{ __('frontend.project_show.request_rejected') }}
                                    </div>

                                    <form method="POST" action="{{ route('frontend.projects.requestFunding', $project) }}" class="space-y-3">
                                        @csrf
                                        <input
                                            type="number"
                                            name="amount"
                                            min="1"
                                            step="0.01"
                                            placeholder="{{ __('frontend.project_show.new_funding_amount') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        >

                                        <textarea
                                            name="message"
                                            rows="4"
                                            placeholder="{{ __('frontend.project_show.new_request_placeholder') }}"
                                            class="w-full px-4 py-3 rounded-xl bg-theme-surface-2 border border-theme-border text-theme-text placeholder:text-theme-muted focus:outline-none focus:border-brand-accent"
                                        ></textarea>

                                        <button type="submit" class="w-full py-3 bg-brand-accent text-white font-bold rounded-xl hover:bg-brand-accent-strong transition">
                                            {{ __('frontend.project_show.submit_new_funding_request') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="theme-panel p-6 rounded-3xl shadow-brand-soft">
                        <h3 class="text-theme-text font-bold mb-4 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-brand-accent"></i> {{ __('frontend.project_show.legacy_files') }}
                        </h3>

                        @if($project->files && $project->files->count() > 0)
                            @foreach($project->files as $file)
                                <div class="flex items-center justify-between p-3 bg-theme-surface-2 rounded-xl border border-theme-border mb-2 hover:bg-brand-accent-soft transition-all">
                                    <span class="text-theme-text text-sm font-semibold">
                                        {{ strtoupper($file->file_type ?? __('frontend.project_show.document')) }}
                                    </span>
                                    <a href="{{ asset('storage/' . ($file->file_path ?? '')) }}" target="_blank" class="text-brand-accent hover:text-theme-text">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-theme-muted italic text-sm text-center py-4">{{ __('frontend.project_show.no_legacy_files') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-500/10 border border-red-500/40 p-10 rounded-3xl text-center">
                <h2 class="text-2xl text-red-600 font-bold">{{ __('frontend.project_show.project_not_found') }}</h2>
                <p class="text-theme-muted mt-2">{{ __('frontend.project_show.project_not_found_text') }}</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // =========================
    // Page load stagger animation
    // =========================
    if (!prefersReducedMotion) {
        const animatedBlocks = [
            ...document.querySelectorAll('.theme-panel'),
            ...document.querySelectorAll('h1'),
            ...document.querySelectorAll('.grid > div')
        ];

        const uniqueBlocks = [...new Set(animatedBlocks)].filter(Boolean);

        uniqueBlocks.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px) scale(0.985)';
            el.style.transition = 'opacity 0.65s ease, transform 0.65s ease';

            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0) scale(1)';
            }, 80 + (index * 90));
        });
    }

    // =========================
    // Hover lift for cards/files/images
    // =========================
    const hoverCards = document.querySelectorAll(
        '.theme-panel, .theme-panel a.block, .theme-panel .flex.items-center.justify-between.p-3'
    );

    hoverCards.forEach(card => {
        const originalTransition = getComputedStyle(card).transition;
        card.style.transition = originalTransition && originalTransition !== 'all 0s ease 0s'
            ? originalTransition + ', transform 0.22s ease, box-shadow 0.22s ease'
            : 'transform 0.22s ease, box-shadow 0.22s ease';

        card.addEventListener('mouseenter', function () {
            if (prefersReducedMotion) return;
            if (card.classList.contains('theme-panel')) return;
            card.style.transform = 'translateY(-4px)';
            card.style.boxShadow = '0 16px 35px rgba(0,0,0,0.08)';
        });

        card.addEventListener('mouseleave', function () {
            if (card.classList.contains('theme-panel')) return;
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    });

    // =========================
    // Animated numbers
    // =========================
    if (!prefersReducedMotion) {
        const numberCandidates = Array.from(document.querySelectorAll('h2, span')).filter(el => {
            const text = el.textContent.trim();
            return /^[$]?\d[\d,]*$/.test(text);
        });

        numberCandidates.forEach((el, index) => {
            const originalText = el.textContent.trim();
            const hasDollar = originalText.startsWith('$');
            const numericValue = parseInt(originalText.replace(/[^\d]/g, ''), 10);

            if (isNaN(numericValue)) return;

            el.textContent = hasDollar ? '$0' : '0';

            setTimeout(() => {
                const duration = 950;
                const startTime = performance.now();

                function animate(currentTime) {
                    const progress = Math.min((currentTime - startTime) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(numericValue * eased);
                    el.textContent = (hasDollar ? '$' : '') + current.toLocaleString();

                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        el.textContent = (hasDollar ? '$' : '') + numericValue.toLocaleString();
                    }
                }

                requestAnimationFrame(animate);
            }, 150 + (index * 100));
        });
    }

    // =========================
    // Image preview modal
    // =========================
    const imageLinks = document.querySelectorAll('a[href] img');
    if (imageLinks.length) {
        const modal = document.createElement('div');
        modal.innerHTML = `
            <div id="projectImagePreviewModal" style="
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.82);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                padding: 24px;
                opacity: 0;
                transition: opacity 0.25s ease;
            ">
                <button type="button" id="projectImagePreviewClose" style="
                    position: absolute;
                    top: 18px;
                    right: 18px;
                    width: 46px;
                    height: 46px;
                    border: none;
                    border-radius: 14px;
                    background: rgba(255,255,255,0.12);
                    color: white;
                    font-size: 20px;
                    cursor: pointer;
                ">×</button>
                <img id="projectImagePreviewTarget" src="" alt="Preview" style="
                    max-width: 92vw;
                    max-height: 88vh;
                    border-radius: 20px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.35);
                    transform: scale(0.96);
                    transition: transform 0.25s ease;
                ">
            </div>
        `;
        document.body.appendChild(modal);

        const modalEl = document.getElementById('projectImagePreviewModal');
        const modalImg = document.getElementById('projectImagePreviewTarget');
        const closeBtn = document.getElementById('projectImagePreviewClose');

        imageLinks.forEach(img => {
            const link = img.closest('a');
            if (!link) return;

            link.addEventListener('click', function (e) {
                e.preventDefault();
                modalImg.src = link.href;
                modalEl.style.display = 'flex';

                requestAnimationFrame(() => {
                    modalEl.style.opacity = '1';
                    modalImg.style.transform = 'scale(1)';
                });

                document.body.style.overflow = 'hidden';
            });
        });

        function closeModal() {
            modalEl.style.opacity = '0';
            modalImg.style.transform = 'scale(0.96)';

            setTimeout(() => {
                modalEl.style.display = 'none';
                modalImg.src = '';
                document.body.style.overflow = '';
            }, 220);
        }

        closeBtn.addEventListener('click', closeModal);
        modalEl.addEventListener('click', function (e) {
            if (e.target === modalEl) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modalEl.style.display === 'flex') {
                closeModal();
            }
        });
    }

    // =========================
    // Video polish
    // =========================
    const video = document.querySelector('video');
    if (video) {
        video.addEventListener('play', function () {
            video.style.boxShadow = '0 20px 45px rgba(0,0,0,0.18)';
            video.style.transform = 'scale(1.003)';
            video.style.transition = 'transform 0.25s ease, box-shadow 0.25s ease';
        });

        video.addEventListener('pause', function () {
            video.style.boxShadow = '';
            video.style.transform = '';
        });

        video.addEventListener('ended', function () {
            video.style.boxShadow = '';
            video.style.transform = '';
        });
    }

    // =========================
    // Funding forms UX
    // =========================
    const fundingForms = document.querySelectorAll('form[action*="requestFunding"]');
    fundingForms.forEach(form => {
        const amountInput = form.querySelector('input[name="amount"]');
        const messageInput = form.querySelector('textarea[name="message"]');
        const submitBtn = form.querySelector('button[type="submit"]');

        if (amountInput) {
            const hint = document.createElement('div');
            hint.className = 'text-xs mt-2';
            hint.style.minHeight = '18px';
            amountInput.insertAdjacentElement('afterend', hint);

            function updateAmountHint() {
                const value = parseFloat(amountInput.value);
                if (!amountInput.value.trim()) {
                    hint.textContent = '';
                    hint.className = 'text-xs mt-2';
                    amountInput.style.borderColor = '';
                    return;
                }

                if (isNaN(value) || value <= 0) {
                    hint.textContent = 'Please enter a valid funding amount.';
                    hint.className = 'text-xs mt-2 text-red-500';
                    amountInput.style.borderColor = 'rgb(239 68 68)';
                } else {
                    hint.textContent = 'Amount looks good.';
                    hint.className = 'text-xs mt-2 text-green-600';
                    amountInput.style.borderColor = 'rgb(34 197 94)';
                }
            }

            amountInput.addEventListener('input', updateAmountHint);
            amountInput.addEventListener('blur', updateAmountHint);
        }

        if (messageInput) {
            const counter = document.createElement('div');
            counter.className = 'text-xs text-theme-muted mt-2 text-right';
            messageInput.insertAdjacentElement('afterend', counter);

            function updateCounter() {
                const length = messageInput.value.length;
                counter.textContent = `${length} characters`;

                if (length > 500) {
                    counter.className = 'text-xs mt-2 text-right text-red-500';
                } else if (length > 300) {
                    counter.className = 'text-xs mt-2 text-right text-yellow-500';
                } else {
                    counter.className = 'text-xs text-theme-muted mt-2 text-right';
                }
            }

            updateCounter();
            messageInput.addEventListener('input', updateCounter);
        }

        form.addEventListener('submit', function () {
            if (!submitBtn) return;

            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <span style="display:inline-flex;align-items:center;gap:10px;">
                    <span style="
                        width:16px;
                        height:16px;
                        border:2px solid rgba(255,255,255,0.45);
                        border-top-color:white;
                        border-radius:50%;
                        display:inline-block;
                        animation: projectSpin .7s linear infinite;
                    "></span>
                    Processing...
                </span>
            `;
            submitBtn.style.opacity = '0.9';
            submitBtn.style.cursor = 'wait';
        });
    });

    // =========================
    // Other form loading states
    // =========================
    const allForms = document.querySelectorAll('form');
    allForms.forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        if (form.action.includes('requestFunding')) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;

            if (!submitBtn.dataset.originalText) {
                submitBtn.dataset.originalText = submitBtn.innerHTML;
            }

            submitBtn.innerHTML = `
                <span style="display:inline-flex;align-items:center;gap:10px;">
                    <span style="
                        width:16px;
                        height:16px;
                        border:2px solid rgba(255,255,255,0.45);
                        border-top-color:currentColor;
                        border-radius:50%;
                        display:inline-block;
                        animation: projectSpin .7s linear infinite;
                    "></span>
                    Loading...
                </span>
            `;
        });
    });

    // =========================
    // Accessibility focus style
    // =========================
    const interactiveItems = document.querySelectorAll('a, button, input, textarea');
    interactiveItems.forEach(item => {
        item.addEventListener('focus', function () {
            item.style.outline = 'none';
            item.style.boxShadow = '0 0 0 3px rgba(99, 102, 241, 0.18)';
        });

        item.addEventListener('blur', function () {
            item.style.boxShadow = '';
        });
    });

    // =========================
    // Inject spin keyframes
    // =========================
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes projectSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection