@csrf
<div class="field">
    <label for="title">Judul Artikel</label>
    <input id="title" type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required>
</div>

<div class="field">
    <label for="excerpt">Ringkasan</label>
    <textarea id="excerpt" name="excerpt" rows="2">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
</div>

<div class="field">
    <label for="content">Konten Artikel</label>
    @php
        $contentValue = old('content', $article->content ?? '');
    @endphp

    <div class="editor-toolbar" role="toolbar" aria-label="Toolbar konten artikel">
        <button type="button" data-command="formatBlock" data-value="p">P</button>
        <button type="button" data-command="formatBlock" data-value="h2">H2</button>
        <button type="button" data-command="formatBlock" data-value="h3">H3</button>
        <button type="button" data-command="bold"><strong>B</strong></button>
        <button type="button" data-command="italic"><em>I</em></button>
        <button type="button" data-command="insertUnorderedList">• List</button>
        <button type="button" data-command="insertOrderedList">1. List</button>
        <button type="button" data-command="removeFormat">Clear</button>
    </div>

    <div id="content-editor" class="article-editor" contenteditable="true" spellcheck="true">{!! $contentValue !!}</div>
    <textarea id="content" name="content" rows="10" required hidden>{{ $contentValue }}</textarea>
    <p class="editor-help">Tulis isi artikel per paragraf. Gunakan toolbar untuk heading, tebal, miring, atau list.</p>
</div>

<div class="field">
    <label for="image">Gambar (hero)</label>
    @if(!empty($article->image))
        <div style="margin-bottom:8px;">
            <img src="{{ asset((strpos($article->image, 'http')===0) ? $article->image : (str_starts_with($article->image, 'image/') ? $article->image : (asset('storage/'.$article->image))) ) }}" alt="preview" style="max-width:240px; max-height:160px; object-fit:cover; border-radius:6px; display:block;">
        </div>
    @endif
    <input id="image" type="file" name="image" accept="image/*">
</div>

<button class="btn-primary" type="submit">{{ $submitLabel }}</button>

<style>
    .editor-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .editor-toolbar button {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text);
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .editor-toolbar button:hover {
        background: #fff2de;
    }

    .article-editor {
        min-height: 260px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #fff;
        padding: 12px 14px;
        font-size: 14px;
        line-height: 1.75;
        outline: none;
        white-space: normal;
    }

    .article-editor:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(201, 106, 49, 0.12);
    }

    .article-editor p {
        margin: 0 0 1em;
    }

    .article-editor h2,
    .article-editor h3 {
        margin: 1.2em 0 0.5em;
    }

    .article-editor ul,
    .article-editor ol {
        padding-left: 1.5rem;
        margin: 0 0 1em;
    }

    .editor-help {
        margin: 8px 0 0;
        font-size: 13px;
        color: var(--muted);
    }
</style>

<script>
    (function () {
        const editor = document.getElementById('content-editor');
        const textarea = document.getElementById('content');
        const form = editor ? editor.closest('form') : null;

        if (!editor || !textarea || !form) {
            return;
        }

        const syncContent = () => {
            textarea.value = editor.innerHTML.trim();
        };

        editor.addEventListener('input', syncContent);
        editor.addEventListener('blur', syncContent);

        editor.addEventListener('paste', function (event) {
            event.preventDefault();
            const text = (event.clipboardData || window.clipboardData).getData('text/plain');
            document.execCommand('insertText', false, text);
            syncContent();
        });

        document.querySelectorAll('.editor-toolbar [data-command]').forEach((button) => {
            button.addEventListener('click', () => {
                editor.focus();
                const command = button.dataset.command;
                const value = button.dataset.value || null;

                if (command === 'formatBlock') {
                    document.execCommand(command, false, value);
                } else {
                    document.execCommand(command, false, value);
                }

                syncContent();
            });
        });

        form.addEventListener('submit', syncContent);
        syncContent();
    })();
</script>
