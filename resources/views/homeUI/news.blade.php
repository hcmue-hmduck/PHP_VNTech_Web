@extends('layouts.app')
@section('title', 'Tin tức')

@section('content')
<!-- Link Google Fonts & Google Material Symbols Icons -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<style>
    .font-display {
        font-family: 'Space Grotesk', sans-serif;
    }
    
    .material-symbols-outlined.filled {
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="max-w-7xl mx-auto px-6 md:px-12 py-8 bg-[#fcf9f8] rounded-3xl my-6 border border-[#e4beb1]/35 shadow-xs text-[#1c1b1b]"
     x-data="newsApp()"
     x-init="init()">
    
    <!-- Title & Toolbar Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 border-b border-[#e4beb1]/30 pb-6 text-left">
        <div class="relative pl-6">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-12 bg-[#a73a00] rounded-r"></div>
            <h1 class="font-display text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#1c1b1b] tracking-tight leading-tight mb-2">
                Tin tức công nghệ
            </h1>
            <p class="font-sans text-sm text-[#5b4137]/80 leading-relaxed max-w-xl">
                Cập nhật tin tức mới nhất, chuyên sâu nhất về thế giới phần cứng, phần mềm và xu hướng tương lai.
            </p>
        </div>
        
        <!-- Quick tools -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search input -->
            <div class="flex items-center bg-[#eae7e7]/70 hover:bg-[#e1dedd] focus-within:bg-white focus-within:ring-1 focus-within:ring-[#ff5c00] px-4 py-2 rounded-xl border border-transparent transition-all">
                <span class="material-symbols-outlined text-gray-500 text-lg mr-2">search</span>
                <input class="bg-transparent border-none focus:outline-none text-xs w-44 font-sans text-gray-805 placeholder-gray-500"
                       placeholder="Tìm kiếm tin tức..."
                       type="text"
                       x-model="searchQuery" />
                <button x-show="searchQuery !== ''"
                        @click="searchQuery = ''"
                        class="p-0.5 rounded-full hover:bg-gray-250 transition-colors flex items-center justify-center text-gray-500">
                    <span class="material-symbols-outlined text-xs">close</span>
                </button>
            </div>

            <!-- Write post button -->
            <button @click="isWriteModalOpen = true"
                    class="flex items-center gap-1.5 bg-[#0070eb]/10 hover:bg-[#0070eb]/20 text-[#001a41] px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide transition-colors">
                <span class="material-symbols-outlined text-xs font-bold">edit</span>
                Đăng bài
            </button>

            <!-- Notifications dropdown trigger -->
            <div class="relative">
                <button @click="showNotificationsDropdown = !showNotificationsDropdown; showProfileDropdown = false;"
                        :class="showNotificationsDropdown ? 'bg-[#eae7e7]' : 'bg-[#eae7e7]/50 hover:bg-[#eae7e7]'"
                        class="p-2.5 rounded-xl transition-colors flex items-center justify-center relative">
                    <span class="material-symbols-outlined text-gray-700 text-lg">notifications</span>
                    <span x-show="notifications.length > 0" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#ff5c00] rounded-full ring-2 ring-white"></span>
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="showNotificationsDropdown"
                     @click.away="showNotificationsDropdown = false"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-neutral-100 overflow-hidden z-50 py-2.5"
                     style="display: none;">
                    <div class="px-4 pb-2 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <span class="text-xs font-bold text-gray-900 uppercase">Thông báo</span>
                        <button x-show="notifications.length > 0" @click="notifications = []" class="text-[10px] text-gray-500 hover:text-[#a73a00]">Xoá tất cả</button>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="px-4 py-6 text-center text-xs text-gray-500 font-sans">
                                Bạn không có thông báo nào mới.
                            </div>
                        </template>
                        <template x-for="(notif, idx) in notifications" :key="idx">
                            <div class="px-4 py-3 text-xs text-gray-750 border-b border-gray-50 hover:bg-orange-50/30 transition-colors last:border-0 text-left">
                                <p class="font-semibold text-neutral-800" x-text="notif"></p>
                                <span class="text-[9px] text-gray-400 font-mono mt-1 block">Vừa xong</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Profile dropdown trigger -->
            <div class="relative">
                <button @click="showProfileDropdown = !showProfileDropdown; showNotificationsDropdown = false;"
                        :class="showProfileDropdown ? 'bg-[#eae7e7]' : 'bg-[#eae7e7]/50 hover:bg-[#eae7e7]'"
                        class="p-2.5 rounded-xl transition-colors flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-700 text-lg">person</span>
                </button>

                <!-- Profile Dropdown -->
                <div x-show="showProfileDropdown"
                     @click.away="showProfileDropdown = false"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-neutral-100 overflow-hidden z-50 p-4"
                     style="display: none;">
                    <div class="flex items-center gap-3 border-b border-neutral-100 pb-3 text-left">
                        <div class="w-10 h-10 bg-[#ffdbce] text-[#a73a00] rounded-full flex items-center justify-center font-bold text-sm">
                            VH
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-gray-900" x-text="username"></h4>
                            <span class="text-[10px] text-gray-500 font-medium bg-gray-100 px-1.5 py-0.5 rounded mt-0.5 inline-block" x-text="userRole"></span>
                        </div>
                    </div>
                    <div class="py-2.5 space-y-2 border-b border-gray-50 text-[11px] text-gray-605 text-left">
                        <div class="flex justify-between">
                            <span>Đã thích:</span>
                            <span class="font-bold" x-text="likedArticles.length + ' bài viết'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Lưu trữ:</span>
                            <span class="font-bold text-[#0058bc]" x-text="bookmarkedArticles.length + ' bài viết'"></span>
                        </div>
                    </div>
                    <div class="pt-2 text-center text-[10px] text-gray-400">
                        TechHub Premium Member
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Tab Bar -->
    <div class="mb-8 border-b border-[#e4beb1]/20 pb-3 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2.5">
            <template x-for="category in ['Tất cả', 'Tin mới', 'Đánh giá', 'Hướng dẫn', 'Khuyến mãi']" :key="category">
                <button @click="selectedCategory = category; selectedTag = null;"
                        :class="selectedCategory === category
                            ? 'bg-[#ff5c00] text-white shadow-md shadow-[#ff5c00]/20'
                            : 'bg-[#eae7e7]/70 text-gray-700 hover:bg-[#e1dedd]'"
                        class="px-5 py-1.5 rounded-full text-xs font-bold tracking-wide transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                    <span x-text="category"></span>
                    <span :class="selectedCategory === category ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-500'"
                          class="text-[9px] px-1.5 py-0.2 rounded-full font-mono"
                          x-text="getCategoryCount(category)"></span>
                </button>
            </template>
        </div>

        <!-- Tag filter status or write button -->
        <div x-show="selectedTag || searchQuery !== ''" 
             class="flex items-center gap-2 bg-[#ffdbce]/40 px-3.5 py-1 rounded-full text-xs text-[#a73a00]"
             style="display: none;">
            <span class="font-semibold">Bộ lọc hiện tại:</span>
            <span class="font-mono bg-[#ffdbce] px-1.5 py-0.2 rounded font-bold"
                  x-text="selectedTag || '“' + searchQuery + '”'"></span>
            <button @click="selectedTag = null; searchQuery = '';"
                    class="hover:text-red-700 font-bold ml-1 text-sm bg-white/50 w-4 h-4 rounded-full inline-flex items-center justify-center transition-colors"
                    title="Xóa bộ lọc">
                ×
            </button>
        </div>
    </div>

    <!-- Adaptive Layout container -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- LEFT: Feed Cards List (8 columns) -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Empty state -->
            <div x-show="filteredArticles.length === 0"
                 class="p-12 text-center bg-[#eae7e7]/30 border border-dashed border-[#e4beb1]/50 rounded-2xl"
                 style="display: none;">
                <span class="material-symbols-outlined text-4xl text-gray-400 mb-3 block">
                    sentiment_dissatisfied
                </span>
                <h3 class="font-bold text-base text-gray-800 mb-1">Không tìm thấy bài viết nào</h3>
                <p class="text-xs text-gray-505 max-w-md mx-auto">
                    Hãy thử thay đổi từ khoá tìm kiếm, chọn chuyên mục khác, hoặc nhấn nút bên dưới để khôi phục các bài viết mặc định.
                </p>
                <button @click="selectedCategory = 'Tất cả'; selectedTag = null; searchQuery = '';"
                        class="mt-4 bg-[#a73a00] text-white px-4 py-2 rounded-lg text-xs font-bold uppercase transition-transform">
                    Xoá bộ lọc nguồn
                </button>
            </div>

            <!-- Main news feed content -->
            <div x-show="filteredArticles.length > 0">
                <!-- 1. FEATURED ARTICLE - Bento block style -->
                <template x-if="featuredArticle !== null">
                    <article @click="activeArticleId = featuredArticle.id"
                             class="group bg-white rounded-2xl border border-[#e4beb1]/40 overflow-hidden shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 cursor-pointer mb-8">
                        <div class="relative aspect-[16/9] md:aspect-[21/9] lg:aspect-[16/9] overflow-hidden bg-[#e5e2e1]">
                            <img :alt="featuredArticle.title"
                                 class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700"
                                 :src="featuredArticle.coverImage"
                                 referrerpolicy="no-referrer" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"></div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-[#a73a00] text-white text-[10px] uppercase font-bold tracking-widest px-3 py-1 rounded"
                                      x-text="featuredArticle.categoryLabel"></span>
                            </div>

                            <!-- Overlap Text -->
                            <div class="absolute bottom-6 left-6 right-6 text-white md:max-w-[90%] text-left">
                                <!-- Meta information -->
                                <div class="flex flex-wrap items-center gap-4 text-white/80 font-mono text-[10px] md:text-xs font-semibold uppercase tracking-wider mb-2.5">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">calendar_today</span>
                                        <span x-text="featuredArticle.date"></span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">visibility</span>
                                        <span x-text="featuredArticle.views.toLocaleString() + ' lượt xem'"></span>
                                    </span>
                                    <span class="flex items-center gap-1 text-[#ffb59a]">
                                        <span class="material-symbols-outlined text-xs">schedule</span>
                                        <span x-text="featuredArticle.readingTime"></span>
                                    </span>
                                </div>

                                <!-- Title -->
                                <h2 class="font-display text-xl md:text-2xl lg:text-3xl font-extrabold leading-tight text-white group-hover:text-[#ffb59a] transition-colors line-clamp-2"
                                    x-text="featuredArticle.title"></h2>
                            </div>
                        </div>

                        <!-- Excerpt details -->
                        <div class="p-6 md:p-8 bg-white text-left">
                            <p class="font-sans text-gray-700 text-sm md:text-base leading-relaxed mb-6"
                               x-text="featuredArticle.excerpt"></p>

                            <div class="flex items-center justify-between">
                                <!-- Hashtags list -->
                                <div class="flex items-center flex-wrap gap-2">
                                    <template x-for="tag in featuredArticle.tags.slice(0, 3)" :key="tag">
                                        <button @click.stop="selectedTag = tag; selectedCategory = 'Tất cả';"
                                                class="text-xs font-semibold text-[#0058bc] hover:underline"
                                                x-text="tag"></button>
                                    </template>
                                </div>

                                <!-- Read full article trigger -->
                                <div class="flex items-center gap-1.5 text-[#a73a00] font-bold text-xs uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                                    <span>Đọc thêm</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </div>
                            </div>
                        </div>
                    </article>
                </template>

                <!-- 2. GRID OF SECONDARY NEWS (2 Columns layout on md/lg) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <template x-for="article in gridArticles" :key="article.id">
                        <div @click="activeArticleId = article.id"
                             class="bg-white rounded-xl border border-[#e4beb1]/30 overflow-hidden group hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex flex-col justify-between cursor-pointer">
                            <div>
                                <!-- Graphic wrapper -->
                                <div class="h-44 overflow-hidden relative bg-[#e5e2e1]">
                                    <img :alt="article.title"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         :src="article.coverImage"
                                         referrerpolicy="no-referrer" />
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-[#bf8900] text-white px-2.5 py-0.8 rounded text-[10px] font-bold uppercase tracking-wider"
                                              x-text="article.categoryLabel"></span>
                                    </div>

                                    <!-- Corner actions -->
                                    <div class="absolute right-2 top-2 flex items-center gap-1">
                                        <button @click.stop="handleToggleBookmark(article.id)"
                                                :class="bookmarkedArticles.includes(article.id)
                                                    ? 'bg-[#ffdbce]/90 text-[#a73a00]'
                                                    : 'bg-black/60 text-white hover:bg-black/80'"
                                                class="p-1.5 rounded-full backdrop-blur-md shadow-xs transition-colors"
                                                title="Lưu trữ bài viết">
                                            <span class="material-symbols-outlined text-[15px]"
                                                  :class="bookmarkedArticles.includes(article.id) ? 'filled' : ''">
                                                bookmark
                                            </span>
                                        </button>
                                        <button @click.stop="handleToggleLike(article.id)"
                                                :class="likedArticles.includes(article.id)
                                                    ? 'bg-red-50 text-red-650 border border-red-250'
                                                    : 'bg-black/60 text-white hover:bg-black/80'"
                                                class="p-1.5 rounded-full backdrop-blur-md shadow-xs transition-colors"
                                                title="Thích bài viết">
                                            <span class="material-symbols-outlined text-[15px]"
                                                  :class="likedArticles.includes(article.id) ? 'filled' : ''">
                                                favorite
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content context -->
                                <div class="p-5 space-y-2 text-left">
                                    <h3 class="font-display font-extrabold text-gray-900 group-hover:text-[#a73a00] transition-colors leading-snug text-base md:text-lg line-clamp-2"
                                        x-text="article.title"></h3>
                                    <p class="text-[#5b4137]/90 text-xs md:text-sm line-clamp-2"
                                       x-text="article.excerpt"></p>
                                </div>
                            </div>

                            <!-- Footer information -->
                            <div class="px-5 pb-5 pt-2 border-t border-gray-50/70 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                                <span class="flex items-center gap-1 font-mono">
                                    <span class="material-symbols-outlined text-xs">calendar_today</span>
                                    <span x-text="article.date"></span>
                                </span>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-xs">visibility</span>
                                        <span x-text="article.views.toLocaleString()"></span>
                                    </span>
                                    <span class="flex items-center gap-1 text-[#0058bc]">
                                        <span class="material-symbols-outlined text-xs">chat_bubble</span>
                                        <span x-text="article.comments.length"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- RIGHT: Sidebar tools (4 columns) -->
        <aside class="lg:col-span-4 space-y-8" aria-label="Khám phá">
            <!-- Action panel: Add news -->
            <div class="bg-[#fcf9f8] rounded-xl p-5 border border-dashed border-[#e4beb1]/70 bg-gradient-to-br from-[#ffdbce]/10 to-[#ff5c00]/5 text-center">
                <span class="text-xs font-semibold text-gray-600 block mb-2">Độc giả TechHub đóng góp bài viết?</span>
                <button @click="isWriteModalOpen = true"
                        class="bg-[#0070eb] text-white text-xs font-semibold uppercase tracking-wider w-full py-2.5 rounded-lg hover:bg-[#0058bc] shadow-md shadow-[#0070eb]/10 transition-all cursor-pointer flex items-center justify-center gap-1.5"
                        id="sidebar-btn-write">
                    <span class="material-symbols-outlined text-sm font-bold">edit</span>
                    Viết & đăng tin tức
                </button>
            </div>

            <!-- Sidebar list: Trending stories -->
            <section class="bg-white rounded-xl p-5 border border-[#e4beb1]/30 shadow-xs">
                <h3 class="font-display font-extrabold text-[#1c1b1b] text-base md:text-lg mb-4 flex items-center gap-2 justify-start">
                    <span class="material-symbols-outlined text-[#a73a00] filled">
                        trending_up
                    </span>
                    Bài viết nổi bật
                </h3>

                <div class="divide-y divide-gray-100">
                    <template x-for="(art, idx) in articles.slice(0, 4)" :key="art.id">
                        <div @click="activeArticleId = art.id"
                             class="flex gap-4 py-3 cursor-pointer group first:pt-0 last:pb-0 text-left">
                            <!-- Index identifier -->
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-[#f0eded] flex items-center justify-center text-[10px] font-black text-[#5b4137] group-hover:bg-[#a73a00] group-hover:text-white transition-colors">
                                <span x-text="idx + 1"></span>
                            </div>

                            <div class="space-y-0.5">
                                <h4 class="text-xs font-bold text-gray-900 group-hover:text-[#a73a00] transition-colors leading-normal line-clamp-2"
                                    x-text="art.title"></h4>
                                <div class="flex items-center gap-2 text-[10px] text-gray-400 font-semibold font-mono tracking-wide uppercase">
                                    <span x-text="art.category"></span>
                                    <span>•</span>
                                    <span x-text="(art.views + 8000).toLocaleString() + ' lượt xem'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <!-- Sidebar Tags cloud: "Tags phổ biến" -->
            <section class="bg-white rounded-xl p-5 border border-[#e4beb1]/30 shadow-xs">
                <h3 class="font-display font-extrabold text-[#1c1b1b] text-base md:text-lg mb-4 flex items-center gap-2 justify-start">
                    <span class="material-symbols-outlined text-[#a73a00]">sell</span>
                    Tags phổ biến
                </h3>

                <div class="flex flex-wrap gap-1.5">
                    <template x-for="tag in popularTags" :key="tag">
                        <button @click="selectedTag = (selectedTag === tag) ? null : tag; if (selectedTag) selectedCategory = 'Tất cả';"
                                :class="selectedTag === tag
                                    ? 'bg-[#a73a00] text-white border border-transparent shadow-xs'
                                    : 'bg-[#f6f3f2] border border-transparent text-[#5b4137] hover:border-[#a73a00] hover:text-[#a73a00]'"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-all"
                                x-text="tag"></button>
                    </template>
                </div>

                <button x-show="selectedTag !== null"
                        @click="selectedTag = null"
                        class="mt-3 text-[10px] uppercase tracking-wider font-extrabold text-blue-600 hover:underline block text-left"
                        style="display: none;">
                    Xoá lọc theo Hashtag
                </button>
            </section>

            <!-- Newsletter widget wrapper -->
            <section class="bg-gradient-to-br from-[#ff5c00] to-[#a73a00] rounded-xl p-6 text-white shadow-md relative overflow-hidden text-left"
                     id="newsletter-section">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-12 translate-x-12 blur-xl"></div>

                <div class="relative z-10 space-y-4">
                    <h3 class="font-display text-lg font-black text-white">Đăng ký nhận tin</h3>
                    <p class="text-xs text-white/90 leading-relaxed font-sans">
                        Nhận những tin tức công nghệ mới nhất hàng ngày trực tiếp vào hòm thư email của bạn.
                    </p>

                    <div x-show="isSubscribed" 
                         class="bg-white/15 p-4 rounded-lg text-center border border-white/20"
                         style="display: none;">
                        <span class="material-symbols-outlined text-[#ffdbce] text-3xl mb-1.5 block">
                            task_alt
                        </span>
                        <h4 class="font-bold text-xs text-white uppercase tracking-wider">Đăng ký thành công!</h4>
                        <p class="text-[10px] text-white/80 mt-1">
                            Cảm ơn bạn đã đăng ký. Bản tin TechHub đầu tiên sẽ gửi tới bạn vào 8h00 sáng mai.
                        </p>
                        <button @click="isSubscribed = false; localStorage.removeItem('techhub_subscribed');"
                                class="mt-2 text-[9px] text-[#ffdbce] hover:underline">
                            Đăng ký email khác
                        </button>
                    </div>

                    <form x-show="!isSubscribed" 
                          @submit.prevent="handleSubscribeNewsletter" 
                          class="space-y-3">
                        <div class="bg-white/20 p-1 rounded-lg flex border border-white/30 backdrop-blur-xs">
                            <input class="bg-transparent border-none focus:outline-none focus:ring-0 text-white placeholder-white/60 text-xs flex-grow px-3 py-2 font-sans w-full"
                                   placeholder="Email của bạn..."
                                   required
                                   type="email"
                                   x-model="newsletterEmail"
                                   aria-label="Email đăng ký nhận tin" />
                            <button type="submit"
                                    class="bg-white text-[#a73a00] px-4 py-2 rounded-md font-sans text-xs font-bold hover:bg-[#fcf9f8] transition-colors shadow-xs shrink-0 cursor-pointer">
                                Đăng ký
                            </button>
                        </div>
                        <p class="text-[9px] text-white/70 italic">Chúng tôi cam kết tôn trọng tuyệt đối quyền riêng tư.</p>
                    </form>
                </div>
            </section>
        </aside>
    </div>
</div>

<!-- ARTICLE READER MODAL -->
<div x-show="activeArticleId !== null" 
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="activeArticleId = null"
     style="display: none;"
     x-data="{}">
    
    <div class="bg-[#fcf9f8] w-full max-w-3xl rounded-2xl overflow-hidden shadow-2xl border border-[#e4beb1]/30 max-h-[90vh] flex flex-col relative"
         @click.stopPropagation()
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 translate-y-10"
         x-transition:enter-end="scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="scale-100 translate-y-0"
         x-transition:leave-end="scale-95 translate-y-10"
         x-data="{ newCommentText: '' }">
         
         <!-- Modal Header -->
         <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-[#f9f5f3] sticky top-0 z-10">
             <div class="flex items-center gap-2">
                 <span class="bg-[#a73a00] text-white text-[10px] uppercase font-bold tracking-widest px-2.5 py-0.8 rounded" 
                       x-text="activeArticle ? activeArticle.categoryLabel : ''"></span>
                 <span class="text-xs text-gray-500 font-medium font-mono" 
                       x-text="activeArticle ? activeArticle.date : ''"></span>
             </div>
             <button @click="activeArticleId = null" 
                     class="p-1 text-gray-550 hover:text-gray-905 hover:bg-gray-200 rounded-lg transition-all">
                 <span class="material-symbols-outlined">close</span>
             </button>
         </div>

         <!-- Modal Body -->
         <div class="p-6 md:p-8 space-y-6 overflow-y-auto flex-1 text-left">
             <!-- Cover Image -->
             <div class="relative rounded-2xl overflow-hidden aspect-[21/9] bg-gray-150 border border-gray-100">
                 <img :src="activeArticle ? activeArticle.coverImage : ''" 
                      :alt="activeArticle ? activeArticle.title : ''"
                      class="w-full h-full object-cover" />
             </div>

             <!-- Title -->
             <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900 leading-tight" 
                 x-text="activeArticle ? activeArticle.title : ''"></h2>

             <!-- Meta stats -->
             <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 font-semibold font-mono border-y border-gray-100 py-3">
                 <span class="flex items-center gap-1">
                     <span class="material-symbols-outlined text-sm">visibility</span>
                     <span x-text="activeArticle ? activeArticle.views.toLocaleString() + ' lượt xem' : ''"></span>
                 </span>
                 <span class="flex items-center gap-1">
                     <span class="material-symbols-outlined text-sm">schedule</span>
                     <span x-text="activeArticle ? activeArticle.readingTime : ''"></span>
                 </span>
                 <span class="flex items-center gap-1 text-red-650">
                     <span class="material-symbols-outlined text-sm filled text-red-500">favorite</span>
                     <span x-text="activeArticle ? activeArticle.likes + ' lượt thích' : ''"></span>
                 </span>
             </div>

             <!-- Excerpt -->
             <p class="font-sans text-gray-600 font-semibold italic text-sm md:text-base leading-relaxed border-l-4 border-[#ff5c00] pl-4"
                x-text="activeArticle ? activeArticle.excerpt : ''"></p>

             <!-- Content paragraphs -->
             <div class="space-y-4 font-sans text-gray-800 text-sm md:text-base leading-relaxed">
                 <template x-for="(para, idx) in (activeArticle ? activeArticle.content : [])" :key="idx">
                     <p x-text="para"></p>
                 </template>
             </div>

             <!-- Tags -->
             <div class="flex flex-wrap gap-2 pt-4">
                 <template x-for="tag in (activeArticle ? activeArticle.tags : [])" :key="tag">
                     <button @click="selectedTag = tag; activeArticleId = null; selectedCategory = 'Tất cả';" 
                             class="text-xs font-bold text-[#0058bc] hover:underline" 
                             x-text="tag"></button>
                 </template>
             </div>

             <!-- Action Footer inside Modal -->
             <div class="border-t border-gray-100 pt-6 flex items-center justify-between">
                 <div class="flex items-center gap-2">
                     <button @click="handleToggleLike(activeArticle.id)" 
                             class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all shadow-xs"
                             :class="likedArticles.includes(activeArticleId) ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 hover:bg-gray-200 text-gray-750'">
                         <span class="material-symbols-outlined text-sm" :class="likedArticles.includes(activeArticleId) ? 'filled' : ''">favorite</span>
                         <span x-text="likedArticles.includes(activeArticleId) ? 'Đã thích' : 'Thích bài'"></span>
                     </button>

                     <button @click="handleToggleBookmark(activeArticle.id)" 
                             class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all shadow-xs"
                             :class="bookmarkedArticles.includes(activeArticleId) ? 'bg-[#ffdbce]/90 text-[#a73a00]' : 'bg-gray-100 hover:bg-gray-200 text-gray-750'">
                         <span class="material-symbols-outlined text-sm" :class="bookmarkedArticles.includes(activeArticleId) ? 'filled' : ''">bookmark</span>
                         <span x-text="bookmarkedArticles.includes(activeArticleId) ? 'Đã lưu' : 'Lưu trữ'"></span>
                     </button>
                 </div>
             </div>

             <!-- Comments Section -->
             <div class="border-t border-gray-100 pt-8 space-y-6">
                 <h4 class="font-display font-extrabold text-gray-900 text-lg">
                     Bình luận (<span x-text="activeArticle ? activeArticle.comments.length : 0"></span>)
                 </h4>

                 <!-- Add Comment Form -->
                 <form @submit.prevent="handleAddComment(activeArticle.id, newCommentText); newCommentText = '';" 
                       class="space-y-3">
                     <div>
                         <textarea rows="3" 
                                   x-model="newCommentText"
                                   placeholder="Chia sẻ ý kiến của bạn về bài viết này..." 
                                   required
                                   class="w-full text-sm border border-gray-250 rounded-xl p-3 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all resize-none leading-relaxed"></textarea>
                     </div>
                     <div class="flex justify-end">
                         <button type="submit" 
                                 class="bg-[#a73a00] hover:opacity-95 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase transition-all">
                            Gửi bình luận
                         </button>
                     </div>
                 </form>

                 <!-- Comments list -->
                 <div class="space-y-4">
                     <template x-for="comment in (activeArticle ? activeArticle.comments : [])" :key="comment.id">
                         <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col gap-1">
                             <div class="flex items-center justify-between">
                                 <span class="text-xs font-bold text-gray-950" x-text="comment.author"></span>
                                 <span class="text-[10px] text-gray-400 font-mono" x-text="comment.date"></span>
                             </div>
                             <p class="text-xs text-gray-700 leading-relaxed" x-text="comment.text"></p>
                         </div>
                     </template>
                     <div x-show="activeArticle && activeArticle.comments.length === 0" 
                          class="text-center text-xs text-gray-400 py-4">
                         Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ cảm nghĩ!
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>

<!-- CUSTOM WRITE ARTICLE FORM MODAL -->
<div x-show="isWriteModalOpen"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="isWriteModalOpen = false"
     style="display: none;"
     x-data="{}">
    
    <div class="bg-[#fcf9f8] w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-[#e4beb1]/30 max-h-[90vh] flex flex-col text-left"
         @click.stopPropagation()
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 translate-y-10"
         x-transition:enter-end="scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="scale-100 translate-y-0"
         x-transition:leave-end="scale-95 translate-y-10">
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-[#f9f5f3]">
            <h3 class="font-display font-extrabold text-base md:text-lg text-gray-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#a73a00]">edit_note</span>
                Đăng bài viết mới lên TechHub
            </h3>
            <button @click="isWriteModalOpen = false"
                    class="p-1 px-2 text-gray-550 hover:text-gray-900 hover:bg-gray-205 rounded-lg transition-all"
                    id="btn-close-write-modal">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Form body -->
        <form @submit.prevent="handleCreateArticle()" class="p-6 md:p-8 space-y-5 overflow-y-auto flex-1">
            <div x-show="writeError !== ''" 
                 class="text-xs text-red-650 font-semibold bg-red-50 p-2.5 rounded border border-red-200"
                 x-text="writeError"
                 style="display: none;"></div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Tiêu đề bài viết*</label>
                <input type="text"
                       required
                       x-model="newTitle"
                       placeholder="VD: Trên tay siêu phẩm robot hút bụi thông minh năm 2026..."
                       class="w-full text-sm border border-gray-200 rounded-lg p-2.5 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Chuyên mục*</label>
                    <select x-model="newCategory"
                            class="w-full text-sm border border-gray-205 bg-white rounded-lg p-2.5 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all cursor-pointer">
                        <option value="Tin mới">Tin mới</option>
                        <option value="Đánh giá">Đánh giá</option>
                        <option value="Hướng dẫn">Hướng dẫn</option>
                        <option value="Khuyến mãi">Khuyến mãi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Mẫu Hashtags</label>
                    <input type="text"
                           x-model="newTagsString"
                           placeholder="#Apple, #Mới, #TinTuc"
                           class="w-full text-sm border border-gray-200 rounded-lg p-2.5 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Tóm tắt ngắn (Excerpt)*</label>
                <input type="text"
                       required
                       maxLength="200"
                       x-model="newExcerpt"
                       placeholder="Bản tóm lược 1-2 câu hiển thị ở danh sách tin tức..."
                       class="w-full text-sm border border-gray-200 rounded-lg p-2.5 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all" />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Đường dẫn ảnh bìa (Tùy chọn)</label>
                <input type="url"
                       x-model="newCoverUrl"
                       placeholder="https://images.unsplash.com/... (để trống sẽ tự sinh ảnh đẹp)"
                       class="w-full text-sm border border-gray-200 rounded-lg p-2.5 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all" />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Nội dung chi tiết*</label>
                <textarea rows="6"
                          required
                          x-model="newContentText"
                          placeholder="Viết nội dung bài viết kỹ thuật hoặc tin tức công nghệ ở đây. Có thể xuống dòng 2 lần để tự động tách thành các đoạn văn riêng biệt..."
                          class="w-full text-sm border border-gray-250 rounded-lg p-3 focus:border-[#ff5c00] focus:ring-1 focus:ring-[#ff5c00] outline-none transition-all resize-none leading-relaxed"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                <button type="button"
                        @click="isWriteModalOpen = false"
                        class="px-4 py-2 border border-gray-200 hover:bg-gray-50 rounded-lg text-xs font-bold uppercase transition-all">
                    Huỷ bỏ
                </button>
                <button type="submit"
                        class="bg-[#0070eb] text-white hover:bg-[#0058bc] px-5 py-2 rounded-lg text-xs font-bold uppercase transition-all cursor-pointer flex items-center gap-1.5"
                        id="submit-article">
                    <span class="material-symbols-outlined text-sm">publish</span>
                    Công bố bài viết
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function newsApp() {
        const initialArticles = [
            {
                id: "iphone-15-pro-max-review",
                category: "Đánh giá",
                categoryLabel: "Đánh giá",
                title: "Đánh giá chi tiết iPhone 15 Pro Max sau 6 tháng sử dụng",
                excerpt: "iPhone 15 Pro Max mang đến khung titan siêu nhẹ, nút Action mới và chip A17 Pro mạnh mẽ. Liệu đây có phải là chiếc flagship đáng mua nhất thời điểm hiện tại?",
                content: [
                    "Sau hơn 6 tháng ra mắt, iPhone 15 Pro Max vẫn là tâm điểm chú ý của giới công nghệ. Chiếc điện thoại này sở hữu nhiều nâng cấp đáng giá từ chất liệu titan, cổng kết nối Type-C cho đến camera zoom quang học 5x.",
                    "Trải nghiệm cầm nắm thực tế cho thấy chất liệu titan giúp máy nhẹ hơn đáng kể so với người tiền nhiệm 14 Pro Max. Viền màn hình mỏng cũng mang lại trải nghiệm thị giác ấn tượng và hiện đại hơn.",
                    "Hiệu năng của vi xử lý A17 Pro là không phải bàn cãi. Các tác vụ từ cơ bản đến chơi game đồ họa cao như Genshin Impact đều được xử lý vô cùng mượt mà. Tuy nhiên, nhiệt độ máy vẫn hơi ấm khi sử dụng liên tục trong thời gian dài."
                ],
                coverImage: "https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=1000&h=600&fit=crop",
                date: "12/04/2026",
                views: 12450,
                readingTime: "5 phút đọc",
                tags: ["#Apple", "#iPhone", "#Mới"],
                commentsCount: 2,
                comments: [
                    { id: "c1", author: "Minh Tuấn", text: "Bài viết rất chi tiết, mình đang dùng bản titan tự nhiên thấy rất hài lòng.", date: "Vừa xong" },
                    { id: "c2", author: "Thu Trang", text: "Giá hiện tại tại TechHub Store đang cực kỳ tốt, mình đang cân nhắc lên đời.", date: "1 ngày trước" }
                ],
                likes: 42
            },
            {
                id: "macbook-air-m3-review",
                category: "Đánh giá",
                categoryLabel: "Đánh giá",
                title: "Đánh giá MacBook Air M3: Sức mạnh vượt trội từ thế hệ vi xử lý mới",
                excerpt: "Được trang bị chip M3 thế hệ mới, MacBook Air M3 mang lại hiệu năng đồ họa cực đỉnh cùng thời lượng pin lên đến 18 tiếng. Đọc ngay đánh giá thực tế.",
                content: [
                    "MacBook Air M3 là sự kết hợp hoàn hảo giữa thiết kế mỏng nhẹ huyền thoại và sức mạnh của chip Apple Silicon thế hệ thứ 3.",
                    "Với khả năng hỗ trợ xuất ra 2 màn hình ngoài khi gập máy, phiên bản M3 đã giải quyết được điểm yếu lớn nhất của các dòng Air trước đây. Tốc độ ổ cứng SSD cũng được cải thiện rõ rệt.",
                    "Dành cho lập trình viên, designer bán chuyên và nhân viên văn phòng, MacBook Air M3 chắc chắn là chiếc laptop tốt nhất trong phân khúc."
                ],
                coverImage: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=1000&h=600&fit=crop",
                date: "28/05/2026",
                views: 8590,
                readingTime: "4 phút đọc",
                tags: ["#Macbook", "#Apple", "#Mới"],
                commentsCount: 1,
                comments: [
                    { id: "c3", author: "Quốc Anh", text: "Đang xài bản 16GB RAM thấy cân tốt mọi tác vụ code web nhẹ nhàng.", date: "2 ngày trước" }
                ],
                likes: 19
            },
            {
                id: "ios-18-guide",
                category: "Hướng dẫn",
                categoryLabel: "Hướng dẫn",
                title: "Hướng dẫn tùy biến giao diện iOS 18 độc đáo và mang đậm cá tính",
                excerpt: "iOS 18 cho phép người dùng tự do sắp xếp icon, đổi màu chủ đề và tùy biến Control Center. Khám phá các bước hướng dẫn cực kỳ đơn giản tại đây.",
                content: [
                    "Apple đã mang đến một cuộc cách mạng về tùy biến giao diện trên iOS 18. Người dùng không còn bị gò bó bởi lưới ứng dụng truyền thống nữa.",
                    "Bạn có thể đổi màu toàn bộ icon sang tone tối hoặc một màu sắc yêu thích để tạo nên giao diện độc nhất vô nhị.",
                    "Hãy làm theo hướng dẫn từng bước của TechHub để sắp xếp các widget hữu ích trực quan nhất."
                ],
                coverImage: "https://images.unsplash.com/photo-1551650975-87deedd944c3?w=1000&h=600&fit=crop",
                date: "01/06/2026",
                views: 5410,
                readingTime: "3 phút đọc",
                tags: ["#iOS", "#Apple", "#Mới"],
                commentsCount: 0,
                comments: [],
                likes: 8
            },
            {
                id: "nvidia-rtx-4070-deal",
                category: "Khuyến mãi",
                categoryLabel: "Khuyến mãi",
                title: "Săn ngay card đồ họa NVIDIA RTX 4070 đang giảm sâu 15% độc quyền tại TechHub",
                excerpt: "Cơ hội sở hữu sức mạnh chiến game Ray Tracing đỉnh cao với mức giá không tưởng. Chương trình diễn ra trong 3 ngày vàng từ TechHub.",
                content: [
                    "NVIDIA RTX 4070 là card đồ họa quốc dân dành cho game thủ muốn trải nghiệm độ phân giải 2K mượt mà.",
                    "Với công nghệ DLSS 3 và dò tia Ray Tracing thế hệ mới, mọi tựa game bom tấn đều trở nên sống động hơn bao giờ hết.",
                    "Hãy nhanh tay đặt hàng trực tuyến hoặc ghé cửa hàng TechHub gần nhất để nhận thêm voucher 500k."
                ],
                coverImage: "https://images.unsplash.com/photo-1591488320449-011701bb6704?w=1000&h=600&fit=crop",
                date: "02/06/2026",
                views: 9120,
                readingTime: "3 phút đọc",
                tags: ["#RTX", "#Hardware", "#Mới"],
                commentsCount: 0,
                comments: [],
                likes: 15
            }
        ];

        return {
            articles: [],
            selectedCategory: "Tất cả",
            searchQuery: "",
            selectedTag: null,
            likedArticles: ["iphone-15-pro-max-review"],
            bookmarkedArticles: [],
            activeArticleId: null,
            notifications: [
                "Tin tức: iPhone 15 Pro Max giảm giá sâu 3 triệu tại TechHub Store!",
                "Đánh giá mới: MacBook Air M3 có đáng tiền không?",
                "Chào mừng bạn đến với kênh tin tức công nghệ TechHub!"
            ],
            showNotificationsDropdown: false,
            showProfileDropdown: false,
            username: "Văn Hoàng",
            userRole: "Độc giả VIP",
            newsletterEmail: "",
            isSubscribed: false,
            isWriteModalOpen: false,
            newTitle: "",
            newExcerpt: "",
            newCategory: "Tin mới",
            newTagsString: "#Apple, #Mới",
            newContentText: "",
            newCoverUrl: "",
            writeError: "",
            popularTags: ["#Apple", "#Macbook", "#iPhone", "#iOS", "#RTX", "#Hardware", "#Mới"],

            init() {
                const savedArticles = localStorage.getItem("techhub_articles");
                if (savedArticles) {
                    try {
                        this.articles = JSON.parse(savedArticles);
                    } catch (e) {
                        this.articles = initialArticles;
                    }
                } else {
                    this.articles = initialArticles;
                    localStorage.setItem("techhub_articles", JSON.stringify(initialArticles));
                }

                const savedLikes = localStorage.getItem("techhub_likes");
                if (savedLikes) {
                    try {
                        this.likedArticles = JSON.parse(savedLikes);
                    } catch (e) {}
                }

                const savedBookmarks = localStorage.getItem("techhub_bookmarks");
                if (savedBookmarks) {
                    try {
                        this.bookmarkedArticles = JSON.parse(savedBookmarks);
                    } catch (e) {}
                }

                this.isSubscribed = localStorage.getItem("techhub_subscribed") === "true";
            },

            get filteredArticles() {
                return this.articles.filter(article => {
                    const matchesCategory = this.selectedCategory === "Tất cả" || article.category === this.selectedCategory;
                    const matchesSearch = this.searchQuery.trim() === "" ||
                        article.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        article.excerpt.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                        article.tags.some(tag => tag.toLowerCase().includes(this.searchQuery.toLowerCase()));
                    const matchesTag = this.selectedTag === null || article.tags.includes(this.selectedTag);
                    return matchesCategory && matchesSearch && matchesTag;
                });
            },

            get featuredArticle() {
                return this.filteredArticles.length > 0 ? this.filteredArticles[0] : null;
            },

            get gridArticles() {
                return this.filteredArticles.slice(1);
            },

            get activeArticle() {
                return this.articles.find(art => art.id === this.activeArticleId) || null;
            },

            getCategoryCount(category) {
                if (category === "Tất cả") return this.articles.length;
                return this.articles.filter(art => art.category === category).length;
            },

            handleToggleLike(id) {
                if (this.likedArticles.includes(id)) {
                    this.likedArticles = this.likedArticles.filter(item => item !== id);
                    this.articles = this.articles.map(art => art.id === id ? { ...art, likes: Math.max(0, art.likes - 1) } : art);
                } else {
                    this.likedArticles.push(id);
                    this.articles = this.articles.map(art => art.id === id ? { ...art, likes: art.likes + 1 } : art);
                }
                localStorage.setItem("techhub_likes", JSON.stringify(this.likedArticles));
                localStorage.setItem("techhub_articles", JSON.stringify(this.articles));
            },

            handleToggleBookmark(id) {
                if (this.bookmarkedArticles.includes(id)) {
                    this.bookmarkedArticles = this.bookmarkedArticles.filter(item => item !== id);
                } else {
                    this.bookmarkedArticles.push(id);
                }
                localStorage.setItem("techhub_bookmarks", JSON.stringify(this.bookmarkedArticles));
            },

            handleAddComment(articleId, text) {
                if (!text.trim()) return;
                const comment = {
                    id: "comment-" + Date.now(),
                    author: this.username,
                    text: text.trim(),
                    date: "Vừa xong"
                };
                this.articles = this.articles.map(art => {
                    if (art.id === articleId) {
                        return {
                            ...art,
                            comments: [comment, ...art.comments],
                            commentsCount: art.commentsCount + 1
                        };
                    }
                    return art;
                });
                localStorage.setItem("techhub_articles", JSON.stringify(this.articles));
            },

            handleSubscribeNewsletter() {
                if (!this.newsletterEmail || !this.newsletterEmail.includes("@")) {
                    alert("Vui lòng nhập địa chỉ email hợp lệ!");
                    return;
                }
                this.isSubscribed = true;
                localStorage.setItem("techhub_subscribed", "true");
                this.newsletterEmail = "";
            },

            handleCreateArticle() {
                if (!this.newTitle.trim() || !this.newExcerpt.trim() || !this.newContentText.trim()) {
                    this.writeError = "Vui lòng nhập đầy đủ Tiêu đề, Tóm tắt và Nội dung bài viết.";
                    return;
                }

                const finalTags = this.newTagsString
                    .split(",")
                    .map(t => t.trim())
                    .filter(t => t.length > 0)
                    .map(t => t.startsWith("#") ? t : "#" + t);

                let cover = this.newCoverUrl.trim();
                if (!cover) {
                    if (this.newCategory === "Đánh giá") {
                        cover = "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop";
                    } else if (this.newCategory === "Hướng dẫn") {
                        cover = "https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=600&h=400&fit=crop";
                    } else if (this.newCategory === "Khuyến mãi") {
                        cover = "https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&h=400&fit=crop";
                    } else {
                        cover = "https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=600&h=400&fit=crop";
                    }
                }

                const newArticle = {
                    id: "article-" + Date.now(),
                    category: this.newCategory,
                    categoryLabel: this.newCategory,
                    title: this.newTitle.trim(),
                    excerpt: this.newExcerpt.trim(),
                    content: this.newContentText.split("\n\n").filter(p => p.trim().length > 0),
                    coverImage: cover,
                    date: new Date().toLocaleDateString("vi-VN"),
                    views: 1,
                    readingTime: Math.max(2, Math.ceil(this.newContentText.split(" ").length / 150)) + " phút đọc",
                    tags: finalTags.length > 0 ? finalTags : ["#Technology", "#New"],
                    commentsCount: 0,
                    comments: [],
                    likes: 0
                };

                this.articles = [newArticle, ...this.articles];
                localStorage.setItem("techhub_articles", JSON.stringify(this.articles));

                this.isWriteModalOpen = false;
                this.selectedCategory = "Tất cả";

                this.newTitle = "";
                this.newExcerpt = "";
                this.newTagsString = "#Apple, #Mới";
                this.newContentText = "";
                this.newCoverUrl = "";
                this.writeError = "";
            },

            scrollToNewsletter() {
                const el = document.getElementById("newsletter-section");
                if (el) el.scrollIntoView({ behavior: "smooth" });
            }
        };
    }
</script>
@endsection
