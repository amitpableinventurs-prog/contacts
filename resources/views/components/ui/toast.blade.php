<div x-data="{
    toasts: [],
    add(toast) {
        const id = Date.now() + Math.random();
        this.toasts.push({ id, ...toast });
        setTimeout(() => this.remove(id), toast.duration || 4000);
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    },
 }"
     x-on:toast.window="add($event.detail)"
     class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2 w-full max-w-sm">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :class="{
                'border-success/30 bg-success/10 text-success-foreground': toast.type === 'success',
                'border-destructive/30 bg-destructive/10 text-destructive': toast.type === 'error',
                'border-border bg-background text-foreground': !toast.type || toast.type === 'default',
             }"
             class="pointer-events-auto flex w-full items-start gap-3 rounded-lg border p-4 shadow-lg">
            <div class="flex-1 min-w-0">
                <p x-show="toast.title" class="text-sm font-semibold" x-text="toast.title"></p>
                <p class="text-sm text-muted-foreground" x-text="toast.message"></p>
            </div>
            <button @click="remove(toast.id)" type="button" class="text-muted-foreground hover:text-foreground">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>

@if (session('toast'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        window.dispatchEvent(new CustomEvent('toast', { detail: @json(session('toast')) }));
    });
    </script>
@endif
