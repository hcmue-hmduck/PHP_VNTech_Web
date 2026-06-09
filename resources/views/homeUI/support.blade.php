@extends('layouts.app')
@section('title', 'Trung tâm hỗ trợ và Chính sách VNTech')

@section('content')
@php
    $supportPolicies = config('support_policies');
@endphp

<!-- Link Google Fonts & Google Material Symbols Icons -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
</style>

<div class="max-w-6xl mx-auto px-6 md:px-12 py-12 bg-[#fcf9f8] rounded-3xl my-8 border border-[#e4beb1]/35 shadow-xs text-[#1c1b1b]"
     x-data="{ currentTab: 'faqs', activeFaq: null }">
    <div class="space-y-10">
        <!-- Hero Section -->
        <div class="text-center space-y-3">
            <h1 class="font-display text-3xl md:text-5xl font-black tracking-tight uppercase text-gray-900">
                HỖ TRỢ & <span class="text-[#ff5c00] drop-shadow-[0_0_15px_rgba(255,92,0,0.1)]">CHÍNH SÁCH</span>
            </h1>
            <p class="font-sans text-[#5b4137]/80 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                Giải đáp nhanh chóng các câu hỏi thường gặp và tham khảo chính sách mua sắm tại VNTech.
            </p>
        </div>

        <!-- Navigation Tab Selection Bar -->
        <div class="flex flex-wrap items-center justify-center gap-2 border-b border-[#e4beb1]/20 pb-4">
            @foreach($supportPolicies['tabs'] as $tabKey => $tab)
                <button @click="currentTab = '{{ $tabKey }}'"
                        :class="currentTab === '{{ $tabKey }}'
                            ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                            : 'bg-white border border-[#e4beb1]/30 text-gray-600 hover:text-[#a73a00]'"
                        class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <!-- MAIN DYNAMIC CONTENT CONTAINER -->
        <div>
            <!-- 1. TAB CONTENT: FAQS & QUICK CATEGORIES -->
            <div x-show="currentTab === 'faqs'" class="space-y-12" x-transition:enter="transition ease-out duration-250">
                <!-- Support Categories Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($supportPolicies['quick_links'] as $quickLink)
                        <div @click="currentTab = '{{ $quickLink['tab'] }}'@if(isset($quickLink['faq_id'])); activeFaq = {{ $quickLink['faq_id'] }}@endif"
                             class="bg-white border border-[#e4beb1]/35 p-6 rounded-3xl hover:border-[#ff5c00]/40 transition-all duration-300 group flex flex-col justify-between cursor-pointer text-left shadow-xs">
                            <div class="space-y-4">
                                <div class="w-12 h-12 rounded-2xl bg-[#ffdbce]/40 flex items-center justify-center text-[#a73a00] group-hover:scale-105 transition-transform">
                                    <span class="material-symbols-outlined text-xl">{{ $quickLink['icon'] }}</span>
                                </div>
                                <h3 class="text-base font-bold uppercase tracking-wider text-gray-900">{{ $quickLink['title'] }}</h3>
                                <p class="text-gray-600 text-xs md:text-sm leading-relaxed">{{ $quickLink['description'] }}</p>
                            </div>
                            <span class="text-[#a73a00] font-bold text-xs uppercase tracking-widest mt-6 inline-flex items-center gap-1 group-hover:translate-x-1 transition-all">
                                {{ $quickLink['cta'] }} <span class="material-symbols-outlined text-sm font-bold">arrow_forward</span>
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- FAQs Accordion -->
                <div class="space-y-6">
                    <h2 class="font-display text-lg md:text-xl font-bold uppercase tracking-wide text-gray-950 flex items-center gap-2 justify-start">
                        <span class="material-symbols-outlined text-[#a73a00]">help_outline</span>
                        Câu hỏi thường gặp (FAQs)
                    </h2>

                    <div class="space-y-4">
                        @foreach($supportPolicies['faqs'] as $faq)
                            <div class="border border-[#e4beb1]/30 bg-white rounded-2xl overflow-hidden shadow-2xs">
                                <button @click="activeFaq = activeFaq === {{ $faq['id'] }} ? null : {{ $faq['id'] }}"
                                        class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50/50 transition-colors">
                                    <span class="font-bold text-sm md:text-base text-gray-900">{{ $faq['question'] }}</span>
                                    <span class="material-symbols-outlined text-gray-400 transition-transform duration-300"
                                          :class="activeFaq === {{ $faq['id'] }} ? 'rotate-180 text-[#ff5c00]' : ''">keyboard_arrow_down</span>
                                </button>
                                <div x-show="activeFaq === {{ $faq['id'] }}"
                                     class="px-5 pb-5 text-gray-650 text-xs md:text-sm leading-relaxed border-t border-neutral-100 pt-4"
                                     style="display: none;">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Contact Support CTA -->
                <div class="bg-gradient-to-r from-[#ffdbce]/40 to-[#ffdbce]/10 border border-[#e4beb1]/35 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row justify-between items-center gap-6 text-left shadow-xs">
                    <div class="space-y-1.5">
                        <h3 class="font-display text-lg font-bold text-gray-905 uppercase tracking-wide">{{ $supportPolicies['cta']['title'] }}</h3>
                        <p class="text-gray-600 text-xs md:text-sm max-w-lg leading-relaxed">{{ $supportPolicies['cta']['description'] }}</p>
                    </div>
                    <a href="{{ route('contact') }}"
                       class="px-8 py-3.5 bg-[#a73a00] hover:bg-[#ff5c00] text-white font-bold uppercase text-xs tracking-widest transition-all duration-300 rounded-xl shrink-0 shadow-md shadow-[#a73a00]/10 cursor-pointer">
                        {{ $supportPolicies['cta']['button'] }}
                    </a>
                </div>
            </div>

            @foreach($supportPolicies['policies'] as $policyKey => $policy)
                <div x-show="currentTab === '{{ $policyKey }}'"
                     class="bg-white border border-[#e4beb1]/30 p-6 md:p-8 rounded-3xl shadow-xs text-left space-y-6"
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-250">
                    <h2 class="font-display text-lg font-bold uppercase tracking-wider text-[#a73a00] flex items-center gap-2 border-b border-gray-100 pb-3">
                        <span class="material-symbols-outlined text-xl">{{ $policy['icon'] }}</span>
                        {{ $policy['title'] }}
                    </h2>
                    <div class="space-y-4 text-gray-650 text-sm leading-relaxed">
                        @foreach($policy['blocks'] as $block)
                            <p class="font-bold text-gray-900 @if(!$loop->first) mt-4 @endif">{{ $block['heading'] }}</p>

                            @if($block['type'] === 'paragraph')
                                <p>{{ $block['content'] }}</p>
                            @elseif($block['type'] === 'ordered_list')
                                <ol class="list-decimal pl-5 space-y-1.5">
                                    @foreach($block['items'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <ul class="list-disc pl-5 space-y-1.5">
                                    @foreach($block['items'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
