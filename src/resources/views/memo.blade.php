{{-- resources/views/memo.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    /* Premium Modern Workspace Styles */
    body, html {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: #f8fafc;
        height: 100%;
    }

    .memo-container {
        height: calc(100vh - 60px);
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Left Sidebar */
    .sidebar-section {
        background-color: #f8fafc;
        border-right: 1px solid #e2e8f0 !important;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background-color: #ffffff;
    }

    .user-badge {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-create {
        background-color: #4f46e5;
        color: #ffffff !important;
    }

    .btn-create:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
    }

    .btn-logout {
        background-color: #f1f5f9;
        color: #64748b;
    }

    .btn-logout:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    /* Instant Search Bar */
    .search-box-container {
        padding: 12px 16px 4px;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .search-input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        font-size: 0.875rem;
        color: #334155;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .sidebar-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        padding: 12px 20px 8px;
    }

    .memo-list-wrapper {
        overflow-y: auto;
        flex: 1;
        padding: 8px 12px;
    }

    .memo-card {
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 6px;
        background-color: transparent;
        transition: all 0.15s ease;
        text-decoration: none !important;
        display: block;
    }

    .memo-card:hover {
        background-color: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .memo-card.active {
        background-color: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
    }

    .memo-card .card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .memo-card.active .card-title {
        color: #4f46e5;
    }

    .memo-card .card-date {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .memo-card .card-snippet {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Right Main Editor */
    .editor-section {
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
    }

    .editor-toolbar {
        padding: 12px 28px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .editor-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .editor-input-title {
        border: none;
        outline: none;
        width: 100%;
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        padding: 24px 28px 12px;
        background: transparent;
    }

    .editor-input-title::placeholder {
        color: #cbd5e1;
    }

    .editor-textarea {
        border: none;
        outline: none;
        width: 100%;
        flex: 1;
        font-size: 1.05rem;
        line-height: 1.7;
        color: #334155;
        padding: 0 28px 28px;
        resize: none;
        background: transparent;
    }

    .editor-textarea::placeholder {
        color: #cbd5e1;
    }

    .empty-state {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
</style>

<div class="container-fluid p-3 h-100">
    <div class="row m-0 memo-container">
        <!-- Left Sidebar: Memo List Section -->
        <div class="col-3 h-100 p-0 sidebar-section">
            <div class="sidebar-header d-flex justify-content-between align-items-center">
                <div class="user-badge d-flex align-items-center gap-2">
                    <i class="fas fa-user-circle text-secondary fa-lg mr-2"></i>
                    <span>{{ Auth::user()->name ?? 'Guest' }}</span>
                </div>
                <div class="d-flex gap-2">
                    <!-- Add Memo Button -->
                    <a href="{{ route('memo.add') }}" class="action-btn btn-create mr-1" title="新しいメモ">
                        <i class="fas fa-plus"></i>
                    </a>

                    <!-- Logout Button -->
                    <button class="action-btn btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="ログアウト">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Instant Search Box -->
            <div class="search-box-container">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="memo-search-input" class="search-input" placeholder="検索..." autocomplete="off">
                </div>
            </div>

            <div class="sidebar-title d-flex justify-content-between align-items-center">
                <span>マイメモリスト</span>
                <span id="memo-count-badge" class="badge badge-pill badge-light border text-muted">{{ count($memos) }}</span>
            </div>

            <div class="memo-list-wrapper" id="memo-list-container">
                @forelse ($memos as $memo)
                    <a href="{{ route('memo.index', ['id' => $memo->id]) }}" 
                       class="memo-card {{ (isset($select_memo) && $select_memo->id === $memo->id) ? 'active' : '' }}"
                       data-title="{{ strtolower($memo->title ?? '') }}"
                       data-content="{{ strtolower($memo->content ?? '') }}">
                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                            <div class="card-title text-truncate pr-2">{{ $memo->title ?: '無題のメモ' }}</div>
                            <div class="card-date">{{ $memo->updated_at->format('m/d H:i') }}</div>
                        </div>
                        <p class="card-snippet text-truncate">
                            {{ $memo->content ?: '内容なし' }}
                        </p>
                    </a>
                @empty
                    <div class="empty-state p-4 text-center">
                        <i class="far fa-sticky-note fa-2x mb-2"></i>
                        <p class="small mb-0">メモがありません。<br>「+」ボタンで作成しましょう。</p>
                    </div>
                @endforelse

                <!-- Element when no search results match -->
                <div id="no-search-results" class="empty-state p-4 text-center d-none">
                    <i class="fas fa-search-minus fa-2x mb-2 text-muted"></i>
                    <p class="small mb-0 text-muted">一致するメモが見つかりません</p>
                </div>
            </div>
        </div>

        <!-- Right Main: Memo Editor Section -->
        <div class="col-9 h-100 p-0 editor-section">
            @if(isset($select_memo))
                <form class="w-100 h-100 d-flex flex-column" action="{{ route('memo.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="edit_id" value="{{ $select_memo->id }}" />
                    
                    <div class="editor-toolbar">
                        <!-- Delete Button -->
                        <button type="submit" 
                                class="btn btn-outline-danger editor-btn" 
                                formaction="{{ route('memo.delete') }}" 
                                onclick="return confirm('本当にこのメモを削除しますか？');">
                            <i class="fas fa-trash-alt"></i> 削除
                        </button>

                        <!-- Save Button -->
                        <button type="submit" class="btn btn-indigo text-white editor-btn" style="background-color: #4f46e5;">
                            <i class="fas fa-save"></i> 保存する
                        </button>
                    </div>

                    <input type="text" 
                           id="memo-title" 
                           name="edit_title" 
                           class="editor-input-title" 
                           placeholder="タイトルを入力..." 
                           value="{{ $select_memo->title ?? '' }}" 
                           autocomplete="off" />

                    <textarea id="memo-content" 
                              name="edit_content" 
                              class="editor-textarea" 
                              placeholder="ここへ自由に入力してください...">{{ $select_memo->content ?? '' }}</textarea>
                </form>
            @else
                <div class="empty-state">
                    <i class="fas fa-edit fa-3x mb-3 text-light"></i>
                    <h5 class="text-secondary font-weight-normal">メモを選択するか、新しいメモを作成してください</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript Instant Search Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('memo-search-input');
        const memoCards = document.querySelectorAll('.memo-card');
        const noResults = document.getElementById('no-search-results');
        const countBadge = document.getElementById('memo-count-badge');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                memoCards.forEach(function (card) {
                    const title = card.getAttribute('data-title') || '';
                    const content = card.getAttribute('data-content') || '';

                    // Match query against title or content
                    if (title.includes(query) || content.includes(query)) {
                        card.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        card.classList.add('d-none');
                    }
                });

                // Toggle empty result message
                if (visibleCount === 0 && memoCards.length > 0) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }

                // Update visible count badge
                if (countBadge) {
                    countBadge.textContent = visibleCount;
                }
            });
        }
    });
</script>
@endsection